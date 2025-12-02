@extends('pharmacy.layout')

@section('title', 'Patient Medications')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Page Title -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="page-title mb-0">
            <i class='bx bx-capsule me-2'></i>Patient Medications
        </h4>
        <div>
            <a href="/dashboard-pharmacy?pg=new-prescription&ptid={{ $pat_user->ref_code }}" class="btn btn-primary me-2">
                <i class='bx bx-plus me-1'></i>New Prescription
            </a>
            <a href="/dashboard-pharmacy?pg=patient-details&ptid={{ $pat_user->ref_code }}" class="btn btn-label-secondary">
                <i class='bx bx-arrow-back me-1'></i>Back to Patient
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

    <!-- Patient Info Card -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="avatar avatar-lg me-3">
                    @if(!empty($pat_user->photo))
                    <img src="/assets/images/{{ $pat_user->photo }}" alt="Patient" class="rounded-circle">
                    @else
                    <span class="avatar-initial rounded-circle bg-label-primary">
                        {{ strtoupper(substr($pat_user->first_name ?? $pat_user->name ?? 'P', 0, 1)) }}
                    </span>
                    @endif
                </div>
                <div>
                    <h5 class="mb-0">{{ $pat_user->first_name ?? '' }} {{ $pat_user->last_name ?? '' }}</h5>
                    <p class="text-muted mb-0">
                        <i class='bx bx-phone me-1'></i>{{ $pat_user->phone ?? 'N/A' }}
                        <span class="mx-2">|</span>
                        <i class='bx bx-map me-1'></i>{{ $pat_user->city ?? 'N/A' }}, {{ $pat_user->state ?? '' }}
                    </p>
                </div>
                <div class="ms-auto">
                    <span class="badge bg-label-primary">{{ count($medications) }} Prescriptions</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Medications List -->
    <div class="card">
        <div class="card-header">
            <h5 class="section-title mb-0">
                <i class='bx bx-list-ul me-2'></i>Prescription History
            </h5>
        </div>
        <div class="card-body">
            @if(count($medications) > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Drug Name</th>
                            <th>Type</th>
                            <th>Dosage</th>
                            <th>Frequency</th>
                            <th>Duration</th>
                            <th>Date Prescribed</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($medications as $med)
                        <tr>
                            <td>
                                <strong>{{ $med->drug_name ?? 'N/A' }}</strong>
                            </td>
                            <td>
                                @if($med->drug_type == 'tablet')
                                <span class="badge bg-label-primary"><i class='bx bx-capsule'></i> Tablet</span>
                                @elseif($med->drug_type == 'syrup')
                                <span class="badge bg-label-info"><i class='bx bx-test-tube'></i> Syrup</span>
                                @elseif($med->drug_type == 'injection')
                                <span class="badge bg-label-warning"><i class='bx bx-injection'></i> Injection</span>
                                @else
                                <span class="badge bg-label-secondary">{{ ucfirst($med->drug_type ?? 'Other') }}</span>
                                @endif
                            </td>
                            <td>{{ $med->dosage ?? 'N/A' }}</td>
                            <td>
                                @if($med->frequency == 'once_daily')
                                <span class="badge bg-label-success">Once Daily</span>
                                @elseif($med->frequency == 'twice_daily')
                                <span class="badge bg-label-info">Twice Daily</span>
                                @elseif($med->frequency == 'thrice_daily')
                                <span class="badge bg-label-warning">Thrice Daily</span>
                                @else
                                <span class="badge bg-label-secondary">{{ ucfirst(str_replace('_', ' ', $med->frequency ?? 'N/A')) }}</span>
                                @endif
                            </td>
                            <td>{{ $med->duration ?? 'N/A' }} days</td>
                            <td>{{ date('M d, Y', $med->date ?? time()) }}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                                        <i class='bx bx-dots-vertical-rounded'></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#viewModal{{ $med->id }}">
                                            <i class='bx bx-show me-1'></i> View Details
                                        </a>
                                        <a class="dropdown-item" href="/dashboard-pharmacy?pg=edit-prescription&ptid={{ $pat_user->ref_code }}&pscid={{ $med->id }}">
                                            <i class='bx bx-edit me-1'></i> Edit
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <!-- View Details Modal -->
                        <div class="modal fade" id="viewModal{{ $med->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Prescription Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="text-muted small">Drug Name</label>
                                            <p class="mb-0"><strong>{{ $med->drug_name ?? 'N/A' }}</strong></p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="text-muted small">Drug Type</label>
                                            <p class="mb-0">{{ ucfirst($med->drug_type ?? 'N/A') }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="text-muted small">Dosage</label>
                                            <p class="mb-0">{{ $med->dosage ?? 'N/A' }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="text-muted small">Frequency</label>
                                            <p class="mb-0">{{ ucfirst(str_replace('_', ' ', $med->frequency ?? 'N/A')) }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="text-muted small">Duration</label>
                                            <p class="mb-0">{{ $med->duration ?? 'N/A' }} days</p>
                                        </div>
                                        @if(!empty($med->additional_info))
                                        <div class="mb-3">
                                            <label class="text-muted small">Additional Instructions</label>
                                            <p class="mb-0">{{ $med->additional_info }}</p>
                                        </div>
                                        @endif
                                        <div class="mb-0">
                                            <label class="text-muted small">Date Prescribed</label>
                                            <p class="mb-0">{{ date('F d, Y', $med->date ?? time()) }}</p>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                                        <a href="/dashboard-pharmacy?pg=edit-prescription&ptid={{ $pat_user->ref_code }}&pscid={{ $med->id }}" class="btn btn-primary">
                                            <i class='bx bx-edit me-1'></i>Edit Prescription
                                        </a>
                                    </div>
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
                    <i class='bx bx-capsule bx-lg'></i>
                </div>
                <h5 class="title">No Prescriptions Found</h5>
                <p class="hint">This patient doesn't have any prescriptions yet.</p>
                <a href="/dashboard-pharmacy?pg=new-prescription&ptid={{ $pat_user->ref_code }}" class="btn btn-primary mt-3">
                    <i class='bx bx-plus me-1'></i>Create First Prescription
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
