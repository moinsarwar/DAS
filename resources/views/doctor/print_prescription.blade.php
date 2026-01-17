@php
    $settings = \App\Models\ClinicSetting::first();
    $vital = $appointment->vital;
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription - {{ $appointment->patient->name }}</title>
    <!-- Bootstrap CSS for basic grid -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: white;
            font-family: 'Times New Roman', Times, serif;
            color: black;
            font-size: 12pt;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            border-bottom: 2px solid black;
            padding-bottom: 15px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h2 {
            margin: 0;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 24px;
        }

        .doctor-info {
            text-align: right;
        }

        .doctor-info h3 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }

        .patient-info {
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .content {
            display: flex;
            min-height: 600px;
            /* Ensure space even if content is short */
        }

        .sidebar {
            width: 28%;
            border-right: 1px solid #ccc;
            padding-right: 15px;
            font-size: 11pt;
        }

        .main {
            width: 72%;
            padding-left: 20px;
        }

        .section-title {
            text-decoration: underline;
            font-weight: bold;
            margin-bottom: 5px;
            margin-top: 15px;
            font-size: 12pt;
        }

        .rx-symbol {
            font-size: 32px;
            font-weight: bold;
            font-style: italic;
            margin-bottom: 15px;
        }

        .footer {
            border-top: 1px solid black;
            padding-top: 10px;
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
        }

        .signature-box {
            margin-top: 50px;
            text-align: right;
            padding-right: 40px;
        }

        .no-print {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 40px;
        }

        /* Buttons styling */
        .btn-custom {
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-family: sans-serif;
            display: inline-block;
            margin: 0 5px;
        }

        .btn-print {
            background-color: #0d6efd;
            border: 1px solid #0d6efd;
        }

        .btn-back {
            background-color: #6c757d;
            border: 1px solid #6c757d;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                margin: 0;
                padding: 0;
            }

            .container {
                width: 100%;
                max-width: 100%;
                margin: 0;
                padding: 0;
            }

            .header,
            .footer {
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="d-flex align-items-center gap-3">
                @if($settings && $settings->logo_path)
                    <img src="{{ asset('storage/' . $settings->logo_path) }}" alt="Logo"
                        style="height: 60px; margin-right: 15px;">
                @endif
                <div>
                    <h2>{{ config('app.name') }}</h2>
                    <div style="font-size: 14px;">{{ $settings->address ?? 'Clinic Details' }}</div>
                    <div style="font-size: 14px;">Tel: {{ $settings->phone ?? '' }}</div>
                </div>
            </div>
            <div class="doctor-info">
                <h3>Dr. {{ $appointment->doctor->user->name }}</h3>
                <div>{{ $appointment->doctor->category->name ?? 'General Physician' }}</div>
                <div>{{ $appointment->doctor->qualification }}</div>
            </div>
        </div>

        <!-- Patient Info -->
        <div class="patient-info">
            <div class="row">
                <div class="col-6"><strong>Patient Name:</strong> {{ $appointment->patient->name }}</div>
                <div class="col-3"><strong>Date:</strong> {{ date('d M, Y') }}</div>
                <div class="col-3"><strong>MR No:</strong> {{ $appointment->patient->mr_number }}</div>
            </div>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Left Sidebar -->
            <div class="sidebar">
                @if($vital)
                    <div class="section-title" style="margin-top: 0;">Vitals</div>
                    <p class="mb-1"><strong>BP:</strong> {{ $vital->bp ?? '-' }}</p>
                    <p class="mb-1"><strong>Weight:</strong> {{ $vital->weight ?? '-' }} kg</p>
                    <p class="mb-1"><strong>Temp:</strong> {{ $vital->temperature ?? '-' }} °F</p>
                    <p class="mb-1"><strong>Pulse:</strong> {{ $vital->pulse ?? '-' }}</p>
                    <p class="mb-1"><strong>Height:</strong> {{ $vital->height ?? '-' }}</p>
                @endif

                @if($appointment->problem)
                    <div class="section-title">Diagnosis</div>
                    <p>{{ $appointment->problem }}</p>
                @endif
            </div>

            <!-- Main RX Section -->
            <div class="main">
                <div class="rx-symbol">Rx</div>

                @if($appointment->prescription)
                    <div style="line-height: 1.8; margin-bottom: 40px; white-space: pre-line;">
                        {!! $appointment->prescription->medicines !!}</div>

                    @if($appointment->prescription->notes)
                        <div class="section-title">Medical Advice</div>
                        <p style="white-space: pre-line;">{{ $appointment->prescription->notes }}</p>
                    @endif
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="signature-box">
            __________________________<br>
            Doctor's Signature
        </div>

        <div class="footer">
            {{ config('app.name') }} | Generated on {{ date('d M, Y h:i A') }}
        </div>
    </div>

    <!-- No Print Section -->
    <div class="no-print">
        <button onclick="window.print()" class="btn-custom btn-print">Print Prescription</button>
        <a href="{{ route('doctor.appointments') }}" class="btn-custom btn-back">Back to Dashboard</a>
    </div>

</body>

</html>