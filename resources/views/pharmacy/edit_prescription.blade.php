@extends('pharmacy.layout')

@section('title', 'Edit Prescription')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Page Title -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="page-title mb-0">
            <i class='bx bx-edit me-2'></i>Edit Prescription
        </h4>
        <div>
            <a href="/dashboard-pharmacy?pg=patient-medications&ptid={{ $pat_user->ref_code }}" class="btn btn-label-secondary">
                <i class='bx bx-arrow-back me-1'></i>Back to Medications
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
        <!-- Patient Info Card -->
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
                            <small class="text-muted">Location:</small>
                            <p class="mb-0">{{ $pat_user->city ?? 'N/A' }}, {{ $pat_user->state ?? '' }}</p>
                        </div>
                        <div class="mb-0">
                            <small class="text-muted">Ref Code:</small>
                            <p class="mb-0"><span class="badge bg-label-primary">{{ $pat_user->ref_code ?? 'N/A' }}</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Prescription Info -->
            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="mb-3"><i class='bx bx-info-circle me-1'></i>Prescription Info</h6>
                    <div class="mb-2">
                        <small class="text-muted">Date Prescribed:</small>
                        <p class="mb-0"><strong>{{ date('F d, Y', $medication->date ?? time()) }}</strong></p>
                    </div>
                    <div class="mb-0">
                        <small class="text-muted">Prescription ID:</small>
                        <p class="mb-0"><span class="badge bg-label-secondary">#{{ $medication->id ?? 'N/A' }}</span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="section-title mb-0">
                        <i class='bx bx-edit me-2'></i>Update Prescription Details
                    </h5>
                </div>
                <div class="card-body">
                    <form action="/dashboard-pharmacy?pg=edit-prescription" method="POST">
                        @csrf
                        <input type="hidden" name="ptid" value="{{ $pat_user->ref_code }}">
                        <input type="hidden" name="pres_id" value="{{ $medication->id }}">

                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label class="form-label">Drug Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="drug_nameed" value="{{ $medication->drug_name ?? '' }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Type <span class="text-danger">*</span></label>
                                <select class="form-select" name="drug_typeed" required>
                                    <option value="">Select Type</option>
                                    <option value="tablet" {{ ($medication->drug_type ?? '') == 'tablet' ? 'selected' : '' }}>Tablet</option>
                                    <option value="capsule" {{ ($medication->drug_type ?? '') == 'capsule' ? 'selected' : '' }}>Capsule</option>
                                    <option value="syrup" {{ ($medication->drug_type ?? '') == 'syrup' ? 'selected' : '' }}>Syrup</option>
                                    <option value="injection" {{ ($medication->drug_type ?? '') == 'injection' ? 'selected' : '' }}>Injection</option>
                                    <option value="cream" {{ ($medication->drug_type ?? '') == 'cream' ? 'selected' : '' }}>Cream/Ointment</option>
                                    <option value="drops" {{ ($medication->drug_type ?? '') == 'drops' ? 'selected' : '' }}>Drops</option>
                                    <option value="inhaler" {{ ($medication->drug_type ?? '') == 'inhaler' ? 'selected' : '' }}>Inhaler</option>
                                    <option value="other" {{ ($medication->drug_type ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Dosage <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="dosageed" value="{{ $medication->dosage ?? '' }}" placeholder="e.g., 500mg" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Frequency <span class="text-danger">*</span></label>
                                <select class="form-select" name="frequencyed" required>
                                    <option value="">Select Frequency</option>
                                    <option value="once_daily" {{ ($medication->frequency ?? '') == 'once_daily' ? 'selected' : '' }}>Once Daily</option>
                                    <option value="twice_daily" {{ ($medication->frequency ?? '') == 'twice_daily' ? 'selected' : '' }}>Twice Daily</option>
                                    <option value="thrice_daily" {{ ($medication->frequency ?? '') == 'thrice_daily' ? 'selected' : '' }}>Thrice Daily</option>
                                    <option value="four_times_daily" {{ ($medication->frequency ?? '') == 'four_times_daily' ? 'selected' : '' }}>Four Times Daily</option>
                                    <option value="every_4_hours" {{ ($medication->frequency ?? '') == 'every_4_hours' ? 'selected' : '' }}>Every 4 Hours</option>
                                    <option value="every_6_hours" {{ ($medication->frequency ?? '') == 'every_6_hours' ? 'selected' : '' }}>Every 6 Hours</option>
                                    <option value="every_8_hours" {{ ($medication->frequency ?? '') == 'every_8_hours' ? 'selected' : '' }}>Every 8 Hours</option>
                                    <option value="every_12_hours" {{ ($medication->frequency ?? '') == 'every_12_hours' ? 'selected' : '' }}>Every 12 Hours</option>
                                    <option value="as_needed" {{ ($medication->frequency ?? '') == 'as_needed' ? 'selected' : '' }}>As Needed</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Duration (Days) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="durationed" value="{{ $medication->duration ?? '' }}" min="1" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Additional Instructions</label>
                            <textarea class="form-control" name="additionaled" rows="3" placeholder="e.g., Take after meals, Avoid alcohol, etc.">{{ $medication->additional_info ?? '' }}</textarea>
                        </div>

                        <div class="alert alert-info">
                            <i class='bx bx-info-circle me-1'></i>
                            <strong>Note:</strong> Changes to this prescription will be saved immediately. The patient will be notified of any updates.
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="/dashboard-pharmacy?pg=patient-medications&ptid={{ $pat_user->ref_code }}" class="btn btn-label-secondary">
                                <i class='bx bx-x me-1'></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class='bx bx-save me-1'></i>Update Prescription
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
