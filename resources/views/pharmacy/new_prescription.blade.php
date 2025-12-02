@extends('pharmacy.layout')

@section('title', 'New Prescription')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Page Title -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="page-title mb-0">
            <i class='bx bx-plus-medical me-2'></i>New Prescription
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

            <!-- Quick Tips -->
            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="mb-3"><i class='bx bx-info-circle me-1'></i>Prescription Tips</h6>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><small><i class='bx bx-check text-success me-1'></i>Add multiple drugs at once</small></li>
                        <li class="mb-2"><small><i class='bx bx-check text-success me-1'></i>Specify clear dosage instructions</small></li>
                        <li class="mb-2"><small><i class='bx bx-check text-success me-1'></i>Include duration in days</small></li>
                        <li class="mb-0"><small><i class='bx bx-check text-success me-1'></i>Add special instructions if needed</small></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Prescription Form -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="section-title mb-0">
                        <i class='bx bx-edit me-2'></i>Prescription Details
                    </h5>
                </div>
                <div class="card-body">
                    <form action="/dashboard-pharmacy?pg=new-prescription" method="POST" id="prescriptionForm">
                        @csrf
                        <input type="hidden" name="ptid" value="{{ $pat_user->ref_code }}">

                        <div id="medicationsContainer">
                            <!-- Medication 1 (Default) -->
                            <div class="medication-item card mb-3" style="border: 2px dashed #5a5fc7;">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0"><i class='bx bx-capsule me-1'></i>Medication #1</h6>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-8">
                                            <label class="form-label">Drug Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="drug_name[]" placeholder="e.g., Paracetamol" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Type <span class="text-danger">*</span></label>
                                            <select class="form-select" name="drug_type[]" required>
                                                <option value="">Select Type</option>
                                                <option value="tablet">Tablet</option>
                                                <option value="capsule">Capsule</option>
                                                <option value="syrup">Syrup</option>
                                                <option value="injection">Injection</option>
                                                <option value="cream">Cream/Ointment</option>
                                                <option value="drops">Drops</option>
                                                <option value="inhaler">Inhaler</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Dosage <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="dosage[]" placeholder="e.g., 500mg" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Frequency <span class="text-danger">*</span></label>
                                            <select class="form-select" name="frequency[]" required>
                                                <option value="">Select Frequency</option>
                                                <option value="once_daily">Once Daily</option>
                                                <option value="twice_daily">Twice Daily</option>
                                                <option value="thrice_daily">Thrice Daily</option>
                                                <option value="four_times_daily">Four Times Daily</option>
                                                <option value="every_4_hours">Every 4 Hours</option>
                                                <option value="every_6_hours">Every 6 Hours</option>
                                                <option value="every_8_hours">Every 8 Hours</option>
                                                <option value="every_12_hours">Every 12 Hours</option>
                                                <option value="as_needed">As Needed</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Duration (Days) <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" name="quantity[]" placeholder="e.g., 7" min="1" required>
                                        </div>
                                    </div>

                                    <div class="mb-0">
                                        <label class="form-label">Additional Instructions</label>
                                        <textarea class="form-control" name="additional[]" rows="2" placeholder="e.g., Take after meals, Avoid alcohol, etc."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Add More Button -->
                        <div class="text-center mb-3">
                            <button type="button" class="btn btn-outline-primary" onclick="addMedication()">
                                <i class='bx bx-plus me-1'></i>Add Another Medication
                            </button>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="/dashboard-pharmacy?pg=patient-medications&ptid={{ $pat_user->ref_code }}" class="btn btn-label-secondary">
                                <i class='bx bx-x me-1'></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class='bx bx-save me-1'></i>Create Prescription
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let medicationCount = 1;

function addMedication() {
    medicationCount++;
    const container = document.getElementById('medicationsContainer');
    
    const newMedication = `
        <div class="medication-item card mb-3" style="border: 2px dashed #5a5fc7;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0"><i class='bx bx-capsule me-1'></i>Medication #${medicationCount}</h6>
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeMedication(this)">
                        <i class='bx bx-trash'></i>
                    </button>
                </div>

                <div class="row mb-3">
                    <div class="col-md-8">
                        <label class="form-label">Drug Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="drug_name[]" placeholder="e.g., Paracetamol" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Type <span class="text-danger">*</span></label>
                        <select class="form-select" name="drug_type[]" required>
                            <option value="">Select Type</option>
                            <option value="tablet">Tablet</option>
                            <option value="capsule">Capsule</option>
                            <option value="syrup">Syrup</option>
                            <option value="injection">Injection</option>
                            <option value="cream">Cream/Ointment</option>
                            <option value="drops">Drops</option>
                            <option value="inhaler">Inhaler</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Dosage <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="dosage[]" placeholder="e.g., 500mg" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Frequency <span class="text-danger">*</span></label>
                        <select class="form-select" name="frequency[]" required>
                            <option value="">Select Frequency</option>
                            <option value="once_daily">Once Daily</option>
                            <option value="twice_daily">Twice Daily</option>
                            <option value="thrice_daily">Thrice Daily</option>
                            <option value="four_times_daily">Four Times Daily</option>
                            <option value="every_4_hours">Every 4 Hours</option>
                            <option value="every_6_hours">Every 6 Hours</option>
                            <option value="every_8_hours">Every 8 Hours</option>
                            <option value="every_12_hours">Every 12 Hours</option>
                            <option value="as_needed">As Needed</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Duration (Days) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="quantity[]" placeholder="e.g., 7" min="1" required>
                    </div>
                </div>

                <div class="mb-0">
                    <label class="form-label">Additional Instructions</label>
                    <textarea class="form-control" name="additional[]" rows="2" placeholder="e.g., Take after meals, Avoid alcohol, etc."></textarea>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', newMedication);
}

function removeMedication(button) {
    const medicationItem = button.closest('.medication-item');
    medicationItem.remove();
    
    // Renumber remaining medications
    const medications = document.querySelectorAll('.medication-item');
    medications.forEach((item, index) => {
        const heading = item.querySelector('h6');
        heading.innerHTML = `<i class='bx bx-capsule me-1'></i>Medication #${index + 1}`;
    });
    medicationCount = medications.length;
}
</script>
@endsection
