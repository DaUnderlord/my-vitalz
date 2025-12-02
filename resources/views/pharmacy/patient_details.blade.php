@extends('pharmacy.layout')

@section('title', 'Patient Details')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Page Title -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="page-title mb-0">
            <i class='bx bx-user-circle me-2'></i>Patient Details
        </h4>
        <div>
            <a href="/dashboard-pharmacy?pg=monitoring" class="btn btn-label-secondary">
                <i class='bx bx-arrow-back me-1'></i>Back to Monitoring
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(!empty($a_type) && !empty($a_message))
    <div class="alert alert-{{ $a_type == 'success' ? 'success' : ($a_type == 'warning' ? 'warning' : 'danger') }} alert-dismissible fade show" role="alert">
        <strong>{{ $a_type == 'success' ? 'Success!' : ($a_type == 'warning' ? 'Warning!' : 'Error!') }}</strong> {{ $a_message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <!-- Patient Profile Card -->
        <div class="col-md-4 mb-4">
            <div class="card stat-card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if(!empty($pat_user->photo))
                        <img src="/assets/images/{{ $pat_user->photo }}" alt="Patient" class="rounded-circle" width="120" height="120" style="object-fit: cover; border: 4px solid #5a5fc7;">
                        @else
                        <div class="avatar avatar-xl mx-auto" style="width: 120px; height: 120px;">
                            <span class="avatar-initial rounded-circle bg-label-primary" style="font-size: 2.5rem;">
                                {{ strtoupper(substr($pat_user->first_name ?? $pat_user->name ?? 'P', 0, 1)) }}
                            </span>
                        </div>
                        @endif
                    </div>
                    
                    <h5 class="mb-1">{{ $pat_user->first_name ?? '' }} {{ $pat_user->last_name ?? '' }}</h5>
                    <p class="text-muted mb-3">
                        <span class="badge bg-label-info">Patient</span>
                    </p>

                    <!-- Patient Info -->
                    <div class="text-start">
                        <div class="mb-2">
                            <small class="text-muted">Phone:</small>
                            <p class="mb-0"><strong>{{ $pat_user->phone ?? 'N/A' }}</strong></p>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Email:</small>
                            <p class="mb-0">{{ $pat_user->email ?? 'N/A' }}</p>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Location:</small>
                            <p class="mb-0">{{ $pat_user->city ?? 'N/A' }}, {{ $pat_user->state ?? '' }}</p>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Ref Code:</small>
                            <p class="mb-0"><span class="badge bg-label-primary">{{ $pat_user->ref_code ?? 'N/A' }}</span></p>
                        </div>
                    </div>

                    <hr class="my-3">

                    <!-- Quick Actions -->
                    <div class="d-grid gap-2">
                        <a href="/dashboard-pharmacy?pg=patient-reading-history&ptid={{ $pat_user->ref_code }}" class="btn btn-primary btn-sm">
                            <i class='bx bx-line-chart me-1'></i>View Reading History
                        </a>
                        <a href="/dashboard-pharmacy?pg=patient-medications&ptid={{ $pat_user->ref_code }}" class="btn btn-outline-primary btn-sm">
                            <i class='bx bx-capsule me-1'></i>View Medications
                        </a>
                        <a href="/dashboard-pharmacy?pg=new-prescription&ptid={{ $pat_user->ref_code }}" class="btn btn-success btn-sm">
                            <i class='bx bx-plus me-1'></i>New Prescription
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vital Readings -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="section-title mb-0">
                        <i class='bx bx-pulse me-2'></i>Latest Vital Readings
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Heart Rate -->
                        <div class="col-md-6 mb-3">
                            <div class="card bg-label-danger">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="text-muted small">Heart Rate</span>
                                            <h4 class="mb-0 text-danger">
                                                @if(count($heart_rate_readings) > 0)
                                                {{ $heart_rate_readings[0]->reading ?? 'N/A' }} <small>bpm</small>
                                                @else
                                                N/A
                                                @endif
                                            </h4>
                                            @if(count($heart_rate_readings) > 0)
                                            <small class="text-muted">{{ date('M d, Y', strtotime($heart_rate_readings[0]->date)) }}</small>
                                            @endif
                                        </div>
                                        <div class="avatar">
                                            <span class="avatar-initial rounded bg-danger">
                                                <i class='bx bx-heart bx-sm'></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Blood Pressure -->
                        <div class="col-md-6 mb-3">
                            <div class="card bg-label-warning">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="text-muted small">Blood Pressure</span>
                                            <h4 class="mb-0 text-warning">
                                                @if(count($blood_pressure_readings) > 0)
                                                {{ $blood_pressure_readings[0]->reading ?? 'N/A' }} <small>mmHg</small>
                                                @else
                                                N/A
                                                @endif
                                            </h4>
                                            @if(count($blood_pressure_readings) > 0)
                                            <small class="text-muted">{{ date('M d, Y', strtotime($blood_pressure_readings[0]->date)) }}</small>
                                            @endif
                                        </div>
                                        <div class="avatar">
                                            <span class="avatar-initial rounded bg-warning">
                                                <i class='bx bx-droplet bx-sm'></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Oxygen Saturation -->
                        <div class="col-md-6 mb-3">
                            <div class="card bg-label-info">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="text-muted small">Oxygen Saturation</span>
                                            <h4 class="mb-0 text-info">
                                                @if(count($oxygen_saturation_readings) > 0)
                                                {{ $oxygen_saturation_readings[0]->reading ?? 'N/A' }} <small>%</small>
                                                @else
                                                N/A
                                                @endif
                                            </h4>
                                            @if(count($oxygen_saturation_readings) > 0)
                                            <small class="text-muted">{{ date('M d, Y', strtotime($oxygen_saturation_readings[0]->date)) }}</small>
                                            @endif
                                        </div>
                                        <div class="avatar">
                                            <span class="avatar-initial rounded bg-info">
                                                <i class='bx bx-wind bx-sm'></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Blood Glucose -->
                        <div class="col-md-6 mb-3">
                            <div class="card bg-label-success">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="text-muted small">Blood Glucose</span>
                                            <h4 class="mb-0 text-success">
                                                @if(count($blood_glucose_readings) > 0)
                                                {{ $blood_glucose_readings[0]->reading ?? 'N/A' }} <small>mg/dL</small>
                                                @else
                                                N/A
                                                @endif
                                            </h4>
                                            @if(count($blood_glucose_readings) > 0)
                                            <small class="text-muted">{{ date('M d, Y', strtotime($blood_glucose_readings[0]->date)) }}</small>
                                            @endif
                                        </div>
                                        <div class="avatar">
                                            <span class="avatar-initial rounded bg-success">
                                                <i class='bx bx-test-tube bx-sm'></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Body Temperature -->
                        <div class="col-md-6 mb-3">
                            <div class="card bg-label-primary">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="text-muted small">Body Temperature</span>
                                            <h4 class="mb-0 text-primary">
                                                @if(count($body_temperature_readings) > 0)
                                                {{ $body_temperature_readings[0]->reading ?? 'N/A' }} <small>°F</small>
                                                @else
                                                N/A
                                                @endif
                                            </h4>
                                            @if(count($body_temperature_readings) > 0)
                                            <small class="text-muted">{{ date('M d, Y', strtotime($body_temperature_readings[0]->date)) }}</small>
                                            @endif
                                        </div>
                                        <div class="avatar">
                                            <span class="avatar-initial rounded bg-primary">
                                                <i class='bx bx-thermometer bx-sm'></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Stress Level -->
                        <div class="col-md-6 mb-3">
                            <div class="card bg-label-danger">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="text-muted small">Stress Level</span>
                                            <h4 class="mb-0 text-danger">
                                                @if(count($stress_readings) > 0)
                                                {{ $stress_readings[0]->reading ?? 'N/A' }}
                                                @else
                                                N/A
                                                @endif
                                            </h4>
                                            @if(count($stress_readings) > 0)
                                            <small class="text-muted">{{ date('M d, Y', strtotime($stress_readings[0]->date)) }}</small>
                                            @endif
                                        </div>
                                        <div class="avatar">
                                            <span class="avatar-initial rounded bg-danger">
                                                <i class='bx bx-brain bx-sm'></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <a href="/dashboard-pharmacy?pg=patient-reading-history&ptid={{ $pat_user->ref_code }}" class="btn btn-primary">
                            <i class='bx bx-line-chart me-1'></i>View Complete Reading History
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Vitals -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="section-title mb-0">
                        <i class='bx bx-health me-2'></i>Additional Health Metrics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Lipids -->
                        <div class="col-md-4 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar me-3">
                                    <span class="avatar-initial rounded bg-label-warning">
                                        <i class='bx bx-water'></i>
                                    </span>
                                </div>
                                <div>
                                    <small class="text-muted">Lipids</small>
                                    <h5 class="mb-0">
                                        @if(count($lipids_readings) > 0)
                                        {{ $lipids_readings[0]->reading ?? 'N/A' }}
                                        @else
                                        N/A
                                        @endif
                                    </h5>
                                </div>
                            </div>
                        </div>

                        <!-- HbA1c -->
                        <div class="col-md-4 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar me-3">
                                    <span class="avatar-initial rounded bg-label-success">
                                        <i class='bx bx-dna'></i>
                                    </span>
                                </div>
                                <div>
                                    <small class="text-muted">HbA1c</small>
                                    <h5 class="mb-0">
                                        @if(count($hba1c_readings) > 0)
                                        {{ $hba1c_readings[0]->reading ?? 'N/A' }}
                                        @else
                                        N/A
                                        @endif
                                    </h5>
                                </div>
                            </div>
                        </div>

                        <!-- IHRA -->
                        <div class="col-md-4 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar me-3">
                                    <span class="avatar-initial rounded bg-label-info">
                                        <i class='bx bx-pulse'></i>
                                    </span>
                                </div>
                                <div>
                                    <small class="text-muted">IHRA</small>
                                    <h5 class="mb-0">
                                        @if(count($ihra_readings) > 0)
                                        {{ $ihra_readings[0]->reading ?? 'N/A' }}
                                        @else
                                        N/A
                                        @endif
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
