@extends('pharmacy.layout')

@section('title', 'Appointment Details')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Page Title -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="page-title mb-0">
            <i class='bx bx-calendar-event me-2'></i>Appointment Details
        </h4>
        <div>
            <a href="/dashboard-pharmacy?pg=appointments" class="btn btn-label-secondary">
                <i class='bx bx-arrow-back me-1'></i>Back to Appointments
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
        <!-- Patient Information -->
        <div class="col-md-4 mb-4">
            <div class="card stat-card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if(!empty($pat_user->photo))
                        <img src="/assets/images/{{ $pat_user->photo }}" alt="Patient" class="rounded-circle" width="100" height="100" style="object-fit: cover; border: 3px solid #5a5fc7;">
                        @else
                        <div class="avatar avatar-xl mx-auto" style="width: 100px; height: 100px;">
                            <span class="avatar-initial rounded-circle bg-label-primary" style="font-size: 2rem;">
                                {{ strtoupper(substr($pat_user->first_name ?? $pat_user->name ?? 'P', 0, 1)) }}
                            </span>
                        </div>
                        @endif
                    </div>
                    
                    <h5 class="mb-1">{{ $pat_user->first_name ?? '' }} {{ $pat_user->last_name ?? '' }}</h5>
                    <p class="text-muted mb-3">
                        <span class="badge bg-label-info">Patient</span>
                    </p>

                    <div class="text-start">
                        <div class="mb-2">
                            <small class="text-muted">Phone:</small>
                            <p class="mb-0"><strong>{{ $pat_user->phone ?? 'N/A' }}</strong></p>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Email:</small>
                            <p class="mb-0">{{ $pat_user->email ?? 'N/A' }}</p>
                        </div>
                        <div class="mb-0">
                            <small class="text-muted">Location:</small>
                            <p class="mb-0">{{ $pat_user->city ?? 'N/A' }}, {{ $pat_user->state ?? '' }}</p>
                        </div>
                    </div>

                    <hr class="my-3">

                    <div class="d-grid gap-2">
                        <a href="/dashboard-pharmacy?pg=patient-details&ptid={{ $pat_user->ref_code }}" class="btn btn-outline-primary btn-sm">
                            <i class='bx bx-user me-1'></i>View Patient Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Appointment Details -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="section-title mb-0">
                        <i class='bx bx-info-circle me-2'></i>Appointment Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small">Appointment ID</label>
                                <p class="mb-0"><span class="badge bg-label-secondary">#{{ $appointment->id ?? 'N/A' }}</span></p>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">Date</label>
                                <p class="mb-0"><strong>{{ $appointment->date ?? 'N/A' }}</strong></p>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">Time</label>
                                <p class="mb-0">
                                    <strong>{{ date('h:i A', $appointment->start_time ?? time()) }}</strong> - 
                                    {{ date('h:i A', $appointment->end_time ?? time()) }}
                                </p>
                            </div>
                            <div class="mb-0">
                                <label class="text-muted small">Day</label>
                                <p class="mb-0">{{ $appointment->day ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted small">Channel</label>
                                <p class="mb-0">
                                    @if($appointment->channel == 'virtual')
                                    <span class="badge bg-label-info"><i class='bx bx-video'></i> Virtual</span>
                                    @else
                                    <span class="badge bg-label-primary"><i class='bx bx-clinic'></i> Physical</span>
                                    @endif
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">Status</label>
                                <p class="mb-0">
                                    @if(empty($appointment->doc_accept))
                                    <span class="badge bg-warning">Pending</span>
                                    @elseif($appointment->doc_accept == 1)
                                    <span class="badge bg-success">Accepted</span>
                                    @elseif($appointment->doc_accept == 2)
                                    <span class="badge bg-info">Rescheduled</span>
                                    @elseif($appointment->doc_accept == 3)
                                    <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">Consultation Fee</label>
                                <p class="mb-0">
                                    @if(!empty($appointment->cost))
                                    <strong class="text-success">₦{{ number_format($appointment->cost, 2) }}</strong>
                                    @else
                                    <span class="text-muted">Not set</span>
                                    @endif
                                </p>
                            </div>
                            <div class="mb-0">
                                <label class="text-muted small">Booking Date</label>
                                <p class="mb-0">{{ $appointment->booking_date ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    @if(!empty($appointment->address))
                    <div class="alert alert-info">
                        <strong><i class='bx bx-map me-1'></i>Location:</strong><br>
                        {{ $appointment->address }}
                    </div>
                    @endif

                    @if(empty($appointment->doc_accept))
                    <div class="d-flex gap-2 mt-4">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#acceptModal">
                            <i class='bx bx-check me-1'></i>Accept Appointment
                        </button>
                        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#rescheduleModal">
                            <i class='bx bx-refresh me-1'></i>Reschedule
                        </button>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class='bx bx-x me-1'></i>Reject
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Accept Modal -->
<div class="modal fade" id="acceptModal" tabindex="-1">
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
                        <input type="number" class="form-control" name="cost" step="0.01" value="{{ $appointment->cost ?? '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address/Location</label>
                        <textarea class="form-control" name="address" rows="2" required>{{ $appointment->address ?? '' }}</textarea>
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
<div class="modal fade" id="rescheduleModal" tabindex="-1">
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
                        <input type="date" class="form-control" name="appointment_date_reschedule" value="{{ $appointment->date ?? '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Time</label>
                        <input type="time" class="form-control" name="appointment_time_reschedule" value="{{ date('H:i', $appointment->start_time ?? time()) }}" required>
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
<div class="modal fade" id="rejectModal" tabindex="-1">
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
                    <p>Are you sure you want to reject this appointment with <strong>{{ $pat_user->first_name ?? '' }} {{ $pat_user->last_name ?? '' }}</strong>?</p>
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
@endsection
