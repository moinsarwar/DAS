@extends('layouts.app')

@section('title', 'Payment Receipt')

@section('content')
    <div class="d-flex justify-content-center mt-4">
        <!-- Thermal Receipt Format (approx 80mm width commonly used ~300-350px) -->
        <div class="card border-0 shadow-sm" id="receipt-card" style="width: 320px; font-family: 'Courier New', monospace;">
            <div class="card-body p-2">
                @php
                    $settings = \App\Models\ClinicSetting::first();
                @endphp
                <div class="text-center mb-2 pb-2 border-bottom border-dark border-2 dashed-border">
                    @if($settings && $settings->logo_path)
                        <img src="{{ asset('storage/' . $settings->logo_path) }}" alt="Logo"
                            style="width: 50px; margin-bottom: 5px;">
                    @else
                        <i class="bi bi-hospital-fill text-dark fs-1"></i>
                    @endif
                    <h5 class="fw-bold text-uppercase mb-1" style="font-size: 1rem;">{{ config('app.name') }}</h5>
                    <p class="small mb-0" style="font-size: 0.75rem;">{{ $settings->address ?? 'Multan' }}</p>
                    <p class="small mb-0" style="font-size: 0.75rem;">Tel: {{ $settings->phone ?? '0300-1234567' }}</p>
                </div>

                <div class="text-center mb-2">
                    <h6 class="fw-bold border text-uppercase border-dark d-inline-block px-2 py-1 mb-1">Receipt</h6>
                    <div class="small fw-bold">#{{ $appointment->id }}</div>
                    <div class="small text-muted">{{ date('d-M-Y h:i A') }}</div>
                </div>

                <div class="mb-2 small">
                    <div class="row g-0">
                        <div class="col-4 fw-bold">Patient:</div>
                        <div class="col-8">{{ $appointment->patient->name }}</div>
                    </div>
                    <div class="row g-0">
                        <div class="col-4 fw-bold">MR #:</div>
                        <div class="col-8">{{ $appointment->patient->mr_number }}</div>
                    </div>
                    <div class="row g-0 mt-1">
                        <div class="col-4 fw-bold">Doctor:</div>
                        <div class="col-8">Dr. {{ $appointment->doctor->user->name }}</div>
                    </div>
                    <div class="row g-0">
                        <div class="col-4 fw-bold">Date:</div>
                        <div class="col-8">{{ date('d-M-Y', strtotime($appointment->appointment_date)) }}</div>
                    </div>
                </div>

                <div class="border-top border-dark border-2 dashed-border py-2 mb-2">
                    <div class="d-flex justify-content-between align-items-center fw-bold fs-5">
                        <span>TOTAL</span>
                        <span>{{ number_format($appointment->fee, 0) }} PKR</span>
                    </div>
                </div>

                <div class="text-center small mt-3">
                    <p class="mb-0 fw-bold">See You Next Time!</p>
                    <p class="mb-0" style="font-size: 0.7rem;">Software by Antigravity</p>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-4 no-print">
        <button onclick="window.print()" class="btn btn-primary me-2"><i class="bi bi-printer"></i> Print Thermal
            Receipt</button>
        <a href="{{ route('receptionist.appointments') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i>
            Back</a>
    </div>

    <style>
        .dashed-border {
            border-style: dashed !important;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            #receipt-card,
            #receipt-card * {
                visibility: visible;
            }

            #receipt-card {
                position: absolute;
                left: 50%;
                /* Center horizontally */
                transform: translateX(-50%);
                /* Adjust based on its own width */
                top: 0;
                width: 80mm;
                /* Standard thermal width */
                margin: 0 auto;
                padding: 0;
                box-shadow: none !important;
            }

            .no-print {
                display: none;
            }

            /* Reset body margins to avoid issues */
            @page {
                margin: 0;
                size: auto;
            }

            body {
                margin: 0.5cm;
            }
        }
    </style>
@endsection