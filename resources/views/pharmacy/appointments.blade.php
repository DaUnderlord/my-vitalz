@extends('pharmacy.layout')

@section('title', 'Appointments')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Page Title -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="page-title mb-0">
            <i class='bx bx-calendar me-2'></i>Appointments
        </h4>
        <div>
            <span class="badge bg-label-primary">{{ count($pending_appointments) }} Upcoming</span>
        </div>
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
                            <span>Total Appointments</span>
                            <h4 class="mb-0">{{ count($pending_appointments) }}</h4>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class='bx bx-calendar-check bx-sm'></i>
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
                            <span>Pending</span>
                            <h4 class="mb-0 text-warning">
                                {{ count(array_filter($pending_appointments, function($apt) { return empty($apt->doc_accept); })) }}
                            </h4>
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
                            <span>Accepted</span>
                            <h4 class="mb-0 text-success">
                                {{ count(array_filter($pending_appointments, function($apt) { return $apt->doc_accept == 1; })) }}
                            </h4>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class='bx bx-check-circle bx-sm'></i>
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
                            <span>Rescheduled</span>
                            <h4 class="mb-0 text-info">
                                {{ count(array_filter($pending_appointments, function($apt) { return $apt->doc_accept == 2; })) }}
                            </h4>
                        </div>
                        <div class="avatar">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class='bx bx-refresh bx-sm'></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Appointments List -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="section-title mb-0">
                <i class='bx bx-list-ul me-2'></i>Upcoming Appointments
            </h5>
        </div>
        <div class="card-body">
            @if(count($pending_appointments) > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Patient</th>
                            <th>Date & Time</th>
                            <th>Channel</th>
                            <th>Status</th>
                            <th>Cost</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pending_appointments as $index => $appointment)
                        @php
                            $patient = $appointment_user[$index] ?? null;
                            $statusBadge = '';
                            $statusText = '';
                            if(empty($appointment->doc_accept)) {
                                $statusBadge = 'bg-label-warning';
                                $statusText = 'Pending';
                            } elseif($appointment->doc_accept == 1) {
                                $statusBadge = 'bg-label-success';
                                $statusText = 'Accepted';
                            } elseif($appointment->doc_accept == 2) {
                                $statusBadge = 'bg-label-info';
                                $statusText = 'Rescheduled';
                            } elseif($appointment->doc_accept == 3) {
                                $statusBadge = 'bg-label-danger';
                                $statusText = 'Rejected';
                            }
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2">
                                        @if(!empty($patient->photo))
                                        <img src="/assets/images/{{ $patient->photo }}" alt="{{ $patient->first_name ?? 'Patient' }}" class="rounded-circle">
                                        @else
                                        <span class="avatar-initial rounded-circle bg-label-primary">
                                            {{ strtoupper(substr($patient->first_name ?? $patient->name ?? 'P', 0, 1)) }}
                                        </span>
                                        @endif
                                    </div>
                                    <div>
                                        <strong>{{ $patient->first_name ?? '' }} {{ $patient->last_name ?? '' }}</strong>
                                        <br><small class="text-muted">{{ $patient->phone ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <strong>{{ $appointment->date ?? 'N/A' }}</strong>
                                <br><small class="text-muted">{{ date('h:i A', $appointment->start_time ?? time()) }}</small>
                            </td>
                            <td>
                                @if($appointment->channel == 'virtual')
                                <span class="badge bg-label-info"><i class='bx bx-video'></i> Virtual</span>
                                @else
                                <span class="badge bg-label-primary"><i class='bx bx-clinic'></i> Physical</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $statusBadge }}">{{ $statusText }}</span>
                            </td>
                            <td>
                                @if(!empty($appointment->cost))
                                <strong>₦{{ number_format($appointment->cost, 2) }}</strong>
                                @else
                                <span class="text-muted">Not set</span>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                                        <i class='bx bx-dots-vertical-rounded'></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="/dashboard-pharmacy?pg=appointment-details&aptid={{ $appointment->id }}">
                                            <i class='bx bx-show me-1'></i> View Details
                                        </a>
                                        @if(empty($appointment->doc_accept))
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#acceptModal{{ $appointment->id }}">
                                            <i class='bx bx-check me-1'></i> Accept
                                        </a>
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#rescheduleModal{{ $appointment->id }}">
                                            <i class='bx bx-refresh me-1'></i> Reschedule
                                        </a>
                                        <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $appointment->id }}">
                                            <i class='bx bx-x me-1'></i> Reject
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <!-- Accept Modal -->
                        <div class="modal fade" id="acceptModal{{ $appointment->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Accept Appointment</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="/dashboard-pharmacy?pg=appointments" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <input type="hidden" name="accept_appointment" value="{{ $appointment->id }}">
                                            <div class="mb-3">
                                                <label class="form-label">Consultation Fee (₦)</label>
                                                <input type="number" class="form-control" name="cost" step="0.01" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Address/Location</label>
                                                <textarea class="form-control" name="address" rows="2" required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-success">Accept Appointment</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Reschedule Modal -->
                        <div class="modal fade" id="rescheduleModal{{ $appointment->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Reschedule Appointment</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="/dashboard-pharmacy?pg=appointments" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <input type="hidden" name="reschedule_appointment" value="{{ $appointment->id }}">
                                            <div class="mb-3">
                                                <label class="form-label">New Date</label>
                                                <input type="date" class="form-control" name="appointment_date_reschedule" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">New Time</label>
                                                <input type="time" class="form-control" name="appointment_time_reschedule" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Consultation Fee (₦)</label>
                                                <input type="number" class="form-control" name="cost" step="0.01" value="{{ $appointment->cost ?? '' }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Address/Location</label>
                                                <textarea class="form-control" name="address" rows="2" required>{{ $appointment->address ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-info">Reschedule</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Reject Modal -->
                        <div class="modal fade" id="rejectModal{{ $appointment->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Reject Appointment</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="/dashboard-pharmacy?pg=appointments" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <input type="hidden" name="reject_appointment" value="{{ $appointment->id }}">
                                            <p>Are you sure you want to reject this appointment with <strong>{{ $patient->first_name ?? '' }} {{ $patient->last_name ?? '' }}</strong>?</p>
                                            <div class="alert alert-warning">
                                                <i class='bx bx-info-circle me-1'></i>
                                                The patient will be notified of the rejection.
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger">Reject Appointment</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                <div class="icon">
                    <i class='bx bx-calendar-x bx-lg'></i>
                </div>
                <h5 class="title">No Upcoming Appointments</h5>
                <p class="hint">You don't have any scheduled appointments at the moment.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
