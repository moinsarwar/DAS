<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Category;
use App\Models\Appointment;
use App\Models\Schedule;
use App\Models\BlockedDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiChatController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $userInput = $request->input('message');
        
        // Load active and configured AI providers in routing priority order
        $routing = config('ai.routing', ['deepseek', 'gemini_3_5_flash', 'gemini_2_5_pro', 'gemini_2_5_flash', 'gemini_2_0_flash', 'gemini_3_1_flash_lite', 'gemini_2_0_flash_lite', 'huggingface']);
        $providers = config('ai.providers', []);
        $activeProviders = [];
        
        foreach ($routing as $provName) {
            if (isset($providers[$provName]) && !empty($providers[$provName]['api_key'])) {
                $activeProviders[] = $provName;
            }
        }

        if (empty($activeProviders)) {
            return response()->json([
                'response' => "Cura AI is currently offline. Please configure at least one API key (DEEPSEEK_API_KEY, GEMINI_API_KEY, or HUGGINGFACE_API_KEY) in your environment `.env` file to enable the chatbot."
            ]);
        }

        $messagesBeforeTurn = session()->get('ai_chat_history', []);
        if (empty($messagesBeforeTurn)) {
            $messagesBeforeTurn[] = [
                'role' => 'system',
                'content' => $this->getSystemPrompt()
            ];
        } else {
            if (isset($messagesBeforeTurn[0]) && $messagesBeforeTurn[0]['role'] === 'system') {
                $messagesBeforeTurn[0]['content'] = $this->getSystemPrompt();
            }
        }

        $tools = $this->getToolsDefinition();
        $autoLoggedIn = false;
        $registrationInfo = null;
        $finalAssistantMessage = null;
        $chatHistoryToSave = null;

        // Try active providers in sequence
        foreach ($activeProviders as $providerName) {
            $provider = $providers[$providerName];
            $apiKey = $provider['api_key'];
            $url = $provider['url'];
            $model = $provider['model'];

            // Prepare local message history copy for this provider attempt
            $messages = $messagesBeforeTurn;
            $messages[] = [
                'role' => 'user',
                'content' => $userInput
            ];

            $maxIterations = 5;
            $iteration = 0;
            $failedThisProvider = false;
            $provAutoLoggedIn = false;
            $provRegistrationInfo = null;

            Log::info("AI Chatbot trying provider: {$providerName}");

            while ($iteration < $maxIterations) {
                $iteration++;

                try {
                    $headers = [
                        'Authorization' => 'Bearer ' . $apiKey,
                        'Content-Type' => 'application/json',
                    ];

                    $requestUrl = $url;
                    if (str_starts_with($providerName, 'gemini')) {
                        $requestUrl .= '?key=' . $apiKey;
                    }

                    $response = Http::withHeaders($headers)
                        ->timeout(25)
                        ->post($requestUrl, [
                            'model' => $model,
                            'messages' => $this->cleanPayloadMessages($messages),
                            'tools' => $tools,
                            'tool_choice' => 'auto',
                        ]);

                    if ($response->failed()) {
                        Log::warning("AI Provider {$providerName} failed with status {$response->status()}: " . $response->body());
                        $failedThisProvider = true;
                        break; // Break loop to failover to next provider
                    }

                    $data = $response->json();
                    $assistantMessage = $data['choices'][0]['message'] ?? null;

                    if (!$assistantMessage) {
                        Log::warning("AI Provider {$providerName} returned empty choices payload.");
                        $failedThisProvider = true;
                        break;
                    }

                    // Append assistant message to local history
                    $messages[] = $assistantMessage;

                    // If tool calls are requested, execute them and resend to provider
                    if (isset($assistantMessage['tool_calls']) && !empty($assistantMessage['tool_calls'])) {
                        foreach ($assistantMessage['tool_calls'] as $toolCall) {
                            $toolName = $toolCall['function']['name'];
                            $arguments = json_decode($toolCall['function']['arguments'], true) ?? [];

                            $toolResult = $this->executeTool($toolName, $arguments);

                            if (($toolName === 'register_and_book' || $toolName === 'book_appointment') && isset($toolResult['registered_user'])) {
                                $provAutoLoggedIn = true;
                                $provRegistrationInfo = $toolResult['registered_user'];
                                $provRegistrationInfo['is_existing'] = $toolResult['is_existing'] ?? false;
                                unset($toolResult['registered_user']);
                            }

                            $messages[] = [
                                'role' => 'tool',
                                'tool_call_id' => $toolCall['id'],
                                'content' => json_encode($toolResult)
                            ];
                        }
                        continue; // Proceed to next API call iteration with tools feedback
                    }

                    // Successful text response obtained
                    $finalAssistantMessage = $assistantMessage;
                    break;

                } catch (\Exception $e) {
                    Log::warning("AI Provider {$providerName} exception: " . $e->getMessage());
                    $failedThisProvider = true;
                    break; // Failover
                }
            }

            if ($failedThisProvider) {
                // Discard changes and check next provider
                continue;
            }

            // If we successfully finished without failover trigger
            $chatHistoryToSave = $messages;
            $autoLoggedIn = $provAutoLoggedIn;
            $registrationInfo = $provRegistrationInfo;
            break; // Break the provider loop as we successfully finished
        }

        if (!$chatHistoryToSave || !$finalAssistantMessage) {
            return response()->json([
                'response' => "Sorry, Cura AI is currently experiencing high load or connectivity issues. Please try again in a few moments."
            ]);
        }

        // Limit message history size to keep session lightweight (keep system prompt and last 20 messages)
        if (count($chatHistoryToSave) > 21) {
            $systemMsg = $chatHistoryToSave[0];
            $recentMsgs = array_slice($chatHistoryToSave, -20);
            $chatHistoryToSave = array_merge([$systemMsg], $recentMsgs);
        }

        // Standardize properties in message objects
        foreach ($chatHistoryToSave as &$msg) {
            if (isset($msg['tool_calls'])) {
                foreach ($msg['tool_calls'] as &$tc) {
                    if (isset($tc['function']) && !isset($tc['function']['arguments'])) {
                        $tc['function']['arguments'] = "{}";
                    }
                }
            }
        }

        session()->put('ai_chat_history', $chatHistoryToSave);

        return response()->json([
            'response' => $finalAssistantMessage['content'] ?? "I am not sure how to answer that.",
            'auto_logged_in' => $autoLoggedIn,
            'registration_info' => $registrationInfo,
        ]);
    }

    private function cleanPayloadMessages($messages)
    {
        $cleaned = [];
        foreach ($messages as $msg) {
            $content = $msg['content'] ?? '';
            // Sanitize and clean '(DAS)' from existing history to prevent model mimicry
            $content = str_ireplace(' (DAS)', '', $content);
            $content = str_ireplace('(DAS)', '', $content);

            $item = [
                'role' => $msg['role'],
                'content' => $content,
            ];

            if (isset($msg['tool_calls'])) {
                // Strip unnecessary properties (like refusal/null parameters) sometimes generated by engines
                $cleanedCalls = [];
                foreach ($msg['tool_calls'] as $tc) {
                    $cleanedCalls[] = [
                        'id' => $tc['id'],
                        'type' => $tc['type'],
                        'function' => [
                            'name' => $tc['function']['name'],
                            'arguments' => $tc['function']['arguments'] ?? '{}'
                        ]
                    ];
                }
                $item['tool_calls'] = $cleanedCalls;
            }

            if ($msg['role'] === 'tool') {
                $item['tool_call_id'] = $msg['tool_call_id'];
            }

            $cleaned[] = $item;
        }
        return $cleaned;
    }

    public function reset()
    {
        session()->forget('ai_chat_history');
        return response()->json(['status' => 'success', 'message' => 'Conversation reset successfully.']);
    }

    private function getSystemPrompt()
    {
        $date = date('Y-m-d');
        $day = date('l');
        $time = date('h:i A');

        $userContext = "Guest (Unregistered)";
        $appointmentsContext = "No appointment history available.";
        if (Auth::check()) {
            $userContext = "Authenticated Patient (" . Auth::user()->name . ", MR Number: " . Auth::user()->mr_number . ", ID: " . Auth::id() . ")";
            
            $recentAppointments = \App\Models\Appointment::where('patient_id', Auth::id())
                ->with(['doctor.user', 'prescription'])
                ->orderBy('appointment_date', 'desc')
                ->orderBy('time_slot', 'desc')
                ->take(10)
                ->get();

            if ($recentAppointments->isNotEmpty()) {
                $appointmentsContext = "User's Recent Appointments (ordered newest to oldest):\n";
                foreach ($recentAppointments as $index => $appt) {
                    $docName = $appt->doctor->user->name ?? 'Unknown Doctor';
                    $status = $appt->status;
                    $dateFormatted = date('M j, Y', strtotime($appt->appointment_date));
                    $timeFormatted = date('h:i A', strtotime($appt->time_slot));
                    
                    $appointmentsContext .= ($index + 1) . ". With Dr. {$docName} on {$dateFormatted} at {$timeFormatted} (Status: {$status}).";
                    
                    if ($appt->prescription) {
                        $meds = strip_tags($appt->prescription->medicines ?? 'None');
                        $notes = strip_tags($appt->prescription->notes ?? 'None');
                        $appointmentsContext .= " Prescription Given: Medicines: {$meds}. Notes: {$notes}.\n";
                    } else {
                        $appointmentsContext .= " No prescription given.\n";
                    }
                }
            }
        }

        $settings = \App\Models\ClinicSetting::first();
        $clinicName = $settings->clinic_name ?? config('app.name', 'Multan Cancer Clinic');

        return "You are Cura, the smart AI Medical Assistant for {$clinicName}.
Your goal is to help visitors find medical specialties, search doctors, check schedule slots, and book appointments.

Current Environment Context:
- Today is {$day}, Date: {$date}, Time: {$time}.
- Current User: {$userContext}
- {$appointmentsContext}

Instructions for Conversing:
1. You must converse strictly in English. Do not use Roman Urdu or Urdu under any circumstances. Keep your responses concise, warm, professional, helpful, and natural.
2. If the user wants to book an appointment:
   - Guide them to choose or specify the specialty/category or doctor first. Use 'list_specialties' or 'search_doctors'.
   - Ask for their preferred date. Use 'get_doctor_slots' to show available dates and times. Display the available slots clearly.
   - If the user specifies an exact time (e.g. '5pm', '5:00', 'at 10am') in their request, check if that exact slot is available in the doctor's slots list. If it is available, proceed to book it immediately without asking them to select. If that exact time slot is already booked or unavailable, you must NOT book any other slot automatically; instead, inform the user that their requested time is unavailable, show the other available slots, and ask them to choose.
   - If they select or specify a slot:
     a) If the user is logged in (Authenticated Patient): Call 'book_appointment' directly with doctor_id, date, and time_slot.
     b) If the user is not logged in but says they are already registered and provides their MR Number: Call 'book_appointment' directly, passing the 'mr_number' parameter along with doctor_id, date, and time_slot.
     c) If the user is a guest (unregistered): Ask if they have an MR Number. If they do, they should provide it so you can book. Otherwise, inform them that you will register them and book the appointment in one go. Collect their: Full Name, Mobile Number, and 13-digit CNIC (strictly 13 digits, no dashes). Once collected, call 'register_and_book'. Display their MR number and booking confirmation details clearly once booked!
3. Be professional. Never prescribe medicines or offer medical diagnoses. Only book appointments.
4. You must never make up, guess, or hallucinate medical specialties or doctors. To list specialties, you MUST call the 'list_specialties' tool first, and only present the specialties returned by it. Similarly, to find doctors, you MUST call the 'search_doctors' tool first and only return doctors that are returned by it.
5. NEVER display database IDs (such as doctor_id, category_id, or schedule_id) to the user under any circumstances. Only present names, dates, times, and fees.
6. NEVER use the abbreviation 'DAS' or '(DAS)' under any circumstances. Only refer to the clinic as '{$clinicName}'.
7. If a guest (unregistered/logged out) asks about their past appointments or prescriptions, ask them to provide their MR Number. Once they provide it, use the 'get_patient_history' tool to fetch and present their records.
8. When displaying multiple appointments in your response, ALWAYS show them in newest to oldest order. You MUST separate each appointment visually by placing an HTML `<hr>` tag or Markdown `---` on a new line between them.";
    }

    private function getToolsDefinition()
    {
        return [
            [
                'type' => 'function',
                'function' => [
                    'name' => 'list_specialties',
                    'description' => 'Get all doctor specialties/categories available in the clinic.',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => (object)[]
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'search_doctors',
                    'description' => 'Search for doctors in the clinic by specialty category ID or doctor name.',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'category_id' => [
                                'type' => 'integer',
                                'description' => 'Optional category ID to filter doctors by specialty.'
                            ],
                            'name' => [
                                'type' => 'string',
                                'description' => 'Optional name of the doctor.'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'get_doctor_slots',
                    'description' => 'Retrieve available time slots for a specific doctor on a selected date.',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'doctor_id' => [
                                'type' => 'integer',
                                'description' => 'The ID of the doctor.'
                            ],
                            'date' => [
                                'type' => 'string',
                                'description' => 'Target date in YYYY-MM-DD format (e.g. 2026-06-12).'
                            ]
                        ],
                        'required' => ['doctor_id', 'date']
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'book_appointment',
                    'description' => 'Create a new appointment booking. Use this for logged-in patients. Also use this for unregistered/guest patients who provide an existing MR number.',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'doctor_id' => [
                                'type' => 'integer',
                                'description' => 'The ID of the doctor.'
                            ],
                            'date' => [
                                'type' => 'string',
                                'description' => 'Target date in YYYY-MM-DD format.'
                            ],
                            'time_slot' => [
                                'type' => 'string',
                                'description' => 'The slot time value in HH:MM:SS format (e.g. 14:00:00).'
                            ],
                            'mr_number' => [
                                'type' => 'string',
                                'description' => 'Optional MR number if the user provides their registered MR number (e.g., MR-202606-0001).'
                            ]
                        ],
                        'required' => ['doctor_id', 'date', 'time_slot']
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'get_patient_history',
                    'description' => 'Retrieve a patient\'s recent appointment and prescription history using their MR Number. Use this when an unregistered or logged-out patient provides their MR Number and asks about their past or upcoming appointments.',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'mr_number' => [
                                'type' => 'string',
                                'description' => 'The patient\'s MR Number (e.g. MR-202606-0001).'
                            ]
                        ],
                        'required' => ['mr_number']
                    ]
                ]
            ],
            [
                'type' => 'function',
                'function' => [
                    'name' => 'register_and_book',
                    'description' => 'Register a guest visitor and book their appointment in one single flow. Use this ONLY if the current user is a guest (unregistered).',
                    'parameters' => [
                        'type' => 'object',
                        'properties' => [
                            'name' => [
                                'type' => 'string',
                                'description' => 'The patient\'s full name.'
                            ],
                            'mobile_number' => [
                                'type' => 'string',
                                'description' => 'The patient\'s mobile contact number.'
                            ],
                            'cnic' => [
                                'type' => 'string',
                                'description' => 'The patient\'s 13-digit national identity card number (numbers only, no dashes).'
                            ],
                            'doctor_id' => [
                                'type' => 'integer',
                                'description' => 'The ID of the doctor.'
                            ],
                            'date' => [
                                'type' => 'string',
                                'description' => 'Target date in YYYY-MM-DD format.'
                            ],
                            'time_slot' => [
                                'type' => 'string',
                                'description' => 'The slot time value in HH:MM:SS format (e.g. 14:00:00).'
                            ]
                        ],
                        'required' => ['name', 'mobile_number', 'cnic', 'doctor_id', 'date', 'time_slot']
                    ]
                ]
            ]
        ];
    }

    private function executeTool($name, $args)
    {
        switch ($name) {
            case 'list_specialties':
                return $this->toolListSpecialties();
            case 'search_doctors':
                return $this->toolSearchDoctors($args['category_id'] ?? null, $args['name'] ?? null);
            case 'get_doctor_slots':
                return $this->toolGetDoctorSlots($args['doctor_id'], $args['date']);
            case 'book_appointment':
                return $this->toolBookAppointment(
                    $args['doctor_id'],
                    $args['date'],
                    $args['time_slot'],
                    $args['mr_number'] ?? null
                );
            case 'get_patient_history':
                return $this->toolGetPatientHistory($args['mr_number']);
            case 'register_and_book':
                return $this->toolRegisterAndBook(
                    $args['name'],
                    $args['mobile_number'],
                    $args['cnic'],
                    $args['doctor_id'],
                    $args['date'],
                    $args['time_slot']
                );
            default:
                return ['error' => 'Unknown tool command.'];
        }
    }

    private function toolGetPatientHistory($mrNumber)
    {
        $patient = User::where('mr_number', $mrNumber)->where('role', 'patient')->first();
        if (!$patient) {
            return ['error' => "No patient found with MR Number {$mrNumber}."];
        }

        $appointments = \App\Models\Appointment::where('patient_id', $patient->id)
            ->with(['doctor.user', 'prescription'])
            ->orderBy('appointment_date', 'desc')
            ->orderBy('time_slot', 'desc')
            ->take(10)
            ->get();

        if ($appointments->isEmpty()) {
            return [
                'patient_name' => $patient->name,
                'message' => 'No appointment history found for this patient.'
            ];
        }

        $history = [];
        foreach ($appointments as $index => $appt) {
            $docName = $appt->doctor->user->name ?? 'Unknown Doctor';
            $date = date('M j, Y', strtotime($appt->appointment_date));
            $time = date('h:i A', strtotime($appt->time_slot));
            
            $item = [
                'index' => $index + 1,
                'doctor' => "Dr. {$docName}",
                'date' => $date,
                'time' => $time,
                'status' => $appt->status,
            ];

            if ($appt->prescription) {
                $item['prescription'] = [
                    'medicines' => strip_tags($appt->prescription->medicines ?? 'None'),
                    'notes' => strip_tags($appt->prescription->notes ?? 'None')
                ];
            } else {
                $item['prescription'] = 'No prescription given';
            }

            $history[] = $item;
        }

        return [
            'patient_name' => $patient->name,
            'recent_appointments' => $history
        ];
    }

    private function toolListSpecialties()
    {
        return Category::all(['id', 'name'])->toArray();
    }

    private function toolSearchDoctors($categoryId = null, $name = null)
    {
        $query = Doctor::with('user', 'category');

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($name) {
            $query->whereHas('user', function ($q) use ($name) {
                $q->where('name', 'like', '%' . $name . '%');
            });
        }

        $doctors = $query->get();

        return $doctors->map(function ($doc) {
            return [
                'doctor_id' => $doc->id,
                'name' => $doc->user->name ?? 'Unknown',
                'specialty' => $doc->category->name ?? 'General',
                'bio' => $doc->bio,
                'qualification' => $doc->qualification,
                'fees' => $doc->fees,
                'experience' => $doc->experience_years . ' years',
            ];
        })->toArray();
    }

    private function toolGetDoctorSlots($doctorId, $date)
    {
        $dayOfWeek = date('l', strtotime($date));
        $doctor = Doctor::with('schedules')->find($doctorId);

        if (!$doctor) {
            return ['error' => 'Doctor not found.'];
        }

        // Check if date is blocked
        if ($doctor->blockedDates()->where('date', $date)->exists()) {
            return ['error' => 'Doctor is not available (blocked date) on this date.'];
        }

        $schedule = $doctor->schedules()->where('day', $dayOfWeek)->first();
        if (!$schedule) {
            return ['error' => "Doctor has no schedule on {$dayOfWeek}s."];
        }

        $start = strtotime($schedule->start_time);
        $end = strtotime($schedule->end_time);
        $duration = $schedule->duration * 60; // in seconds

        $bookedSlots = Appointment::where('doctor_id', $doctorId)
            ->where('appointment_date', $date)
            ->pluck('time_slot')
            ->map(fn($t) => date('H:i:s', strtotime($t)))
            ->toArray();

        $slots = [];
        $currentTime = time();
        $isToday = date('Y-m-d') === $date;

        for ($i = $start; $i < $end; $i += $duration) {
            $slotTime = date('H:i:s', $i);

            // Filter past slots if target date is today
            if ($isToday && $i <= $currentTime) {
                continue;
            }

            if (!in_array($slotTime, $bookedSlots)) {
                $slots[] = [
                    'display' => date('h:i A', $i),
                    'value' => $slotTime
                ];
            }
        }

        return [
            'doctor_id' => $doctorId,
            'doctor_name' => $doctor->user->name ?? 'Doctor',
            'date' => $date,
            'day' => $dayOfWeek,
            'available_slots' => $slots,
            'schedule_id' => $schedule->id,
            'fee' => $doctor->fees
        ];
    }

    private function toolBookAppointment($doctorId, $date, $timeSlot, $mrNumber = null)
    {
        $patient = null;
        $registeredUser = null;

        if ($mrNumber) {
            $patient = User::where('mr_number', $mrNumber)->where('role', 'patient')->first();
            if (!$patient) {
                return ['error' => "No patient found with MR Number {$mrNumber}. Please double check the number, or provide Name, Mobile Number, and CNIC for registration if you are a new patient."];
            }
            Auth::login($patient);
            $registeredUser = [
                'name' => $patient->name,
                'cnic' => $patient->cnic,
                'mr_number' => $patient->mr_number,
            ];
        } else {
            if (!Auth::check()) {
                return ['error' => 'User is not authenticated. If you are already registered, please provide your MR Number (e.g. MR-202606-0001). Otherwise, please share your Name, Mobile Number, and CNIC to register.'];
            }
            $patient = Auth::user();
        }

        $patientId = $patient->id;
        $patientName = $patient->name;
        $doctor = Doctor::find($doctorId);

        if (!$doctor) {
            return ['error' => 'Doctor not found.'];
        }

        // Check blocked date
        if ($doctor->blockedDates()->where('date', $date)->exists()) {
            return ['error' => 'Doctor is not available on this date.'];
        }

        // Fetch schedule id
        $dayOfWeek = date('l', strtotime($date));
        $schedule = $doctor->schedules()->where('day', $dayOfWeek)->first();
        if (!$schedule) {
            return ['error' => "Doctor has no schedule on {$dayOfWeek}s."];
        }

        // Double check booking conflict
        $isBooked = Appointment::where('doctor_id', $doctorId)
            ->where('appointment_date', $date)
            ->where('time_slot', $timeSlot)
            ->exists();

        if ($isBooked) {
            return ['error' => 'This slot has already been booked. Please pick another slot.'];
        }

        // Validate future slot
        $slotDateTime = strtotime($date . ' ' . $timeSlot);
        if ($slotDateTime <= time()) {
            return ['error' => 'Cannot book appointments for past time slots.'];
        }

        // Create appointment
        $appointment = Appointment::create([
            'patient_id' => $patientId,
            'doctor_id' => $doctorId,
            'schedule_id' => $schedule->id,
            'appointment_date' => $date,
            'time_slot' => $timeSlot,
            'status' => 'Pending',
            'problem' => $mrNumber ? 'AI Automated Booking via MR Number' : 'AI Automated Booking',
            'fee' => null, // Fee will be collected at clinic or approved by receptionist
        ]);

        // Send notifications
        try {
            $appointment->doctor->user->notify(new \App\Notifications\AppointmentNotification([
                'message' => 'New AI appointment booked by ' . $patientName . ' for ' . $date,
                'url' => route('doctor.appointments', ['status' => 'Pending']),
                'icon' => 'bi-calendar-plus',
                'color' => 'text-primary',
                'type' => 'new_booking'
            ]));
        } catch (\Exception $e) {
            Log::error('AI Notification Error: ' . $e->getMessage());
        }

        $res = [
            'status' => 'success',
            'appointment_id' => $appointment->id,
            'doctor_name' => $doctor->user->name ?? 'Doctor',
            'date' => $date,
            'time' => date('h:i A', strtotime($timeSlot)),
            'message' => 'Appointment request submitted successfully. Status is Pending.',
        ];

        if ($registeredUser) {
            $res['registered_user'] = $registeredUser;
            $res['is_existing'] = true;
        }

        return $res;
    }

    private function toolRegisterAndBook($name, $mobileNumber, $cnic, $doctorId, $date, $timeSlot)
    {
        if (strlen($cnic) !== 13 || !is_numeric($cnic)) {
            return ['error' => 'CNIC must be exactly 13 digits without dashes. Please ask patient to correct.'];
        }

        $doctor = Doctor::find($doctorId);
        if (!$doctor) {
            return ['error' => 'Doctor not found.'];
        }

        // Check availability
        $dayOfWeek = date('l', strtotime($date));
        $schedule = $doctor->schedules()->where('day', $dayOfWeek)->first();
        if (!$schedule) {
            return ['error' => 'Doctor is not scheduled for this day.'];
        }

        $isBooked = Appointment::where('doctor_id', $doctorId)
            ->where('appointment_date', $date)
            ->where('time_slot', $timeSlot)
            ->exists();

        if ($isBooked) {
            return ['error' => 'This slot is already booked. Please ask patient to choose another.'];
        }

        // Create new Patient User
        $mrNumber = User::generateMrNumber();
        
        $user = User::create([
            'name' => $name,
            'mobile_number' => $mobileNumber,
            'cnic' => $cnic,
            'mr_number' => $mrNumber,
            'role' => 'patient',
        ]);

        // Autologin new user
        Auth::login($user);

        // Book the appointment
        $appointment = Appointment::create([
            'patient_id' => $user->id,
            'doctor_id' => $doctorId,
            'schedule_id' => $schedule->id,
            'appointment_date' => $date,
            'time_slot' => $timeSlot,
            'status' => 'Pending',
            'problem' => 'AI Automated Guest Booking',
            'fee' => null,
        ]);

        // Send notifications
        try {
            $doctor->user->notify(new \App\Notifications\AppointmentNotification([
                'message' => 'New AI appointment booked by ' . $user->name . ' (Registered via AI) for ' . $date,
                'url' => route('doctor.appointments', ['status' => 'Pending']),
                'icon' => 'bi-calendar-plus',
                'color' => 'text-primary',
                'type' => 'new_booking'
            ]));
        } catch (\Exception $e) {
            Log::error('AI Notification Error: ' . $e->getMessage());
        }

        return [
            'status' => 'success',
            'registered_user' => [
                'name' => $user->name,
                'cnic' => $user->cnic,
                'mr_number' => $user->mr_number,
            ],
            'appointment_id' => $appointment->id,
            'doctor_name' => $doctor->user->name ?? 'Doctor',
            'date' => $date,
            'time' => date('h:i A', strtotime($timeSlot)),
            'message' => 'Patient registered successfully. Unique MR Number: ' . $mrNumber . '. Appointment request submitted successfully.',
        ];
    }
}
