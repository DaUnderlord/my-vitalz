@extends('pharmacy.layout')

@section('title', 'Affiliate Network')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Page Title -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="page-title mb-0">
            <i class='bx bx-network-chart me-2'></i>Affiliate Network
        </h4>
    </div>

    <!-- Alert Messages -->
    @if(!empty($a_type) && !empty($a_message))
    <div class="alert alert-{{ $a_type == 'success' ? 'success' : ($a_type == 'warning' ? 'warning' : 'danger') }} alert-dismissible fade show" role="alert">
        <strong>{{ $a_type == 'success' ? 'Success!' : ($a_type == 'warning' ? 'Warning!' : 'Error!') }}</strong> {{ $a_message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="content-left">
                            <span>Pending Requests</span>
                            <h4 class="mb-0 text-warning">{{ count($my_requests) }}</h4>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class='bx bx-time bx-sm'></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="content-left">
                            <span>Patients</span>
                            <h4 class="mb-0 text-info">{{ count($my_patients) }}</h4>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class='bx bx-user bx-sm'></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="content-left">
                            <span>Hospitals</span>
                            <h4 class="mb-0 text-primary">{{ count($my_hospital) }}</h4>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class='bx bx-clinic bx-sm'></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="content-left">
                            <span>Pharmacies</span>
                            <h4 class="mb-0 text-success">{{ count($my_pharmacy) }}</h4>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class='bx bx-store bx-sm'></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Requests -->
    @if(count($my_requests) > 0)
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="section-title mb-0">
                <i class='bx bx-bell me-2'></i>Pending Requests
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Contact</th>
                            <th>Location</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($request_details as $index => $request)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2">
                                        @if(!empty($request->photo))
                                        <img src="/assets/images/{{ $request->photo }}" alt="{{ $request->first_name ?? 'User' }}" class="rounded-circle">
                                        @else
                                        <span class="avatar-initial rounded-circle bg-label-primary">
                                            {{ strtoupper(substr($request->first_name ?? $request->name ?? 'U', 0, 1)) }}
                                        </span>
                                        @endif
                                    </div>
                                    <div>
                                        <strong>{{ $request->first_name ?? '' }} {{ $request->last_name ?? '' }}</strong>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($request->doctor == 1)
                                <span class="badge bg-label-primary">Doctor</span>
                                @elseif($request->hospital == 1)
                                <span class="badge bg-label-info">Hospital</span>
                                @elseif($request->pharmacy == 1)
                                <span class="badge bg-label-success">Pharmacy</span>
                                @else
                                <span class="badge bg-label-secondary">Patient</span>
                                @endif
                            </td>
                            <td>{{ $request->phone ?? 'N/A' }}</td>
                            <td>{{ $request->city ?? 'N/A' }}, {{ $request->state ?? '' }}</td>
                            <td>
                                <form action="/dashboard-pharmacy?pg=affiliates" method="POST" style="display: inline;">
                                    @csrf
                                    <input type="hidden" name="approve_affliate" value="{{ $my_requests[$index]->id }}">
                                    <button type="submit" class="btn btn-sm btn-success me-1">
                                        <i class='bx bx-check'></i> Approve
                                    </button>
                                </form>
                                <form action="/dashboard-pharmacy?pg=affiliates" method="POST" style="display: inline;">
                                    @csrf
                                    <input type="hidden" name="decline_affliate" value="{{ $my_requests[$index]->id }}">
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class='bx bx-x'></i> Decline
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Network Tabs -->
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#patients-tab" type="button" role="tab">
                        <i class='bx bx-user me-1'></i>Patients ({{ count($my_patients) }})
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#hospitals-tab" type="button" role="tab">
                        <i class='bx bx-clinic me-1'></i>Hospitals ({{ count($my_hospital) }})
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#pharmacies-tab" type="button" role="tab">
                        <i class='bx bx-store me-1'></i>Pharmacies ({{ count($my_pharmacy) }})
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <!-- Patients Tab -->
                <div class="tab-pane fade show active" id="patients-tab" role="tabpanel">
                    @if(count($my_patients) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Contact</th>
                                    <th>Location</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($patients_details as $patient)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                @if(!empty($patient->photo))
                                                <img src="/assets/images/{{ $patient->photo }}" alt="{{ $patient->first_name ?? 'Patient' }}" class="rounded-circle">
                                                @else
                                                <span class="avatar-initial rounded-circle bg-label-info">
                                                    {{ strtoupper(substr($patient->first_name ?? $patient->name ?? 'P', 0, 1)) }}
                                                </span>
                                                @endif
                                            </div>
                                            <div>
                                                <strong>{{ $patient->first_name ?? '' }} {{ $patient->last_name ?? '' }}</strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $patient->phone ?? 'N/A' }}</td>
                                    <td>{{ $patient->city ?? 'N/A' }}, {{ $patient->state ?? '' }}</td>
                                    <td>
                                        <a href="/dashboard-pharmacy?pg=patient-details&ptid={{ $patient->ref_code }}" class="btn btn-sm btn-outline-primary">
                                            <i class='bx bx-show'></i> View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="empty-state">
                        <div class="icon">
                            <i class='bx bx-user bx-lg'></i>
                        </div>
                        <h5 class="title">No Patients Yet</h5>
                        <p class="hint">You don't have any patients in your network.</p>
                    </div>
                    @endif
                </div>

                <!-- Hospitals Tab -->
                <div class="tab-pane fade" id="hospitals-tab" role="tabpanel">
                    @if(count($my_hospital) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Contact</th>
                                    <th>Location</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hospital_details as $hospital)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                @if(!empty($hospital->photo))
                                                <img src="/assets/images/{{ $hospital->photo }}" alt="{{ $hospital->first_name ?? 'Hospital' }}" class="rounded-circle">
                                                @else
                                                <span class="avatar-initial rounded-circle bg-label-primary">
                                                    {{ strtoupper(substr($hospital->first_name ?? $hospital->name ?? 'H', 0, 1)) }}
                                                </span>
                                                @endif
                                            </div>
                                            <div>
                                                <strong>{{ $hospital->first_name ?? '' }} {{ $hospital->last_name ?? '' }}</strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $hospital->phone ?? 'N/A' }}</td>
                                    <td>{{ $hospital->city ?? 'N/A' }}, {{ $hospital->state ?? '' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="empty-state">
                        <div class="icon">
                            <i class='bx bx-clinic bx-lg'></i>
                        </div>
                        <h5 class="title">No Hospitals Yet</h5>
                        <p class="hint">You don't have any hospitals in your network.</p>
                    </div>
                    @endif
                </div>

                <!-- Pharmacies Tab -->
                <div class="tab-pane fade" id="pharmacies-tab" role="tabpanel">
                    @if(count($my_pharmacy) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Contact</th>
                                    <th>Location</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pharmacy_details as $pharmacy)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                @if(!empty($pharmacy->photo))
                                                <img src="/assets/images/{{ $pharmacy->photo }}" alt="{{ $pharmacy->first_name ?? 'Pharmacy' }}" class="rounded-circle">
                                                @else
                                                <span class="avatar-initial rounded-circle bg-label-success">
                                                    {{ strtoupper(substr($pharmacy->first_name ?? $pharmacy->name ?? 'P', 0, 1)) }}
                                                </span>
                                                @endif
                                            </div>
                                            <div>
                                                <strong>{{ $pharmacy->first_name ?? '' }} {{ $pharmacy->last_name ?? '' }}</strong>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $pharmacy->phone ?? 'N/A' }}</td>
                                    <td>{{ $pharmacy->city ?? 'N/A' }}, {{ $pharmacy->state ?? '' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="empty-state">
                        <div class="icon">
                            <i class='bx bx-store bx-lg'></i>
                        </div>
                        <h5 class="title">No Pharmacies Yet</h5>
                        <p class="hint">You don't have any pharmacies in your network.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
