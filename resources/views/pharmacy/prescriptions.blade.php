@extends('pharmacy.layout')

@section('title', 'E-Prescriptions')

@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Pharmacy /</span> E-Prescriptions
</h4>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div class="d-flex gap-2 flex-wrap">
            <select class="form-select form-select-sm w-auto" id="statusFilter" onchange="filterPrescriptions()">
                <option value="all" {{ $status_filter == 'all' ? 'selected' : '' }}>All Statuses</option>
                <option value="pending" {{ $status_filter == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ $status_filter == 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="ready" {{ $status_filter == 'ready' ? 'selected' : '' }}>Ready</option>
                <option value="dispensed" {{ $status_filter == 'dispensed' ? 'selected' : '' }}>Dispensed</option>
                <option value="delivered" {{ $status_filter == 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="cancelled" {{ $status_filter == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <input type="text" class="form-control form-control-sm w-auto" id="searchInput" placeholder="Search patient/doctor..." value="{{ $search }}" style="min-width: 200px;">
            <button class="btn btn-sm btn-outline-primary" onclick="filterPrescriptions()">
                <i class="bx bx-search"></i> Search
            </button>
        </div>
        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createRxModal">
            <i class="bx bx-plus me-2"></i>New Prescription
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Consultation</th>
                        <th>Medications</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($prescriptions as $rx)
                    <tr class="prescription-row" data-id="{{ $rx->id }}">
                        <td><small class="text-primary fw-semibold">{{ $rx->prescription_id }}</small></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-2">
                                    <span class="avatar-initial rounded-circle bg-label-info">{{ substr($rx->patient_name ?? 'P', 0, 1) }}</span>
                                </div>
                                <span>{{ $rx->patient_name ?? 'Unknown' }}</span>
                            </div>
                        </td>
                        <td>{{ $rx->doctor_name ?? 'Unknown' }}</td>
                        <td><span class="badge bg-label-primary">{{ ucfirst($rx->consultation_type ?? 'online') }}</span></td>
                        <td>
                            <span class="badge bg-label-secondary">{{ $rx->medication_count ?? 0 }} meds</span>
                        </td>
                        <td>
                            @php
                                $statusColors = [
                                    'pending' => 'warning',
                                    'processing' => 'info',
                                    'ready' => 'primary',
                                    'dispensed' => 'success',
                                    'delivered' => 'success',
                                    'cancelled' => 'danger'
                                ];
                                $color = $statusColors[$rx->status] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $color }}">{{ ucfirst($rx->status) }}</span>
                        </td>
                        <td><small>{{ date('M d, Y H:i', strtotime($rx->created_at)) }}</small></td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                                    Actions
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="javascript:void(0)" onclick="viewPrescription({{ $rx->id }})"><i class="bx bx-show me-2"></i>View Details</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0)" onclick="updateStatus({{ $rx->id }}, 'processing')"><i class="bx bx-play me-2"></i>Start Processing</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0)" onclick="updateStatus({{ $rx->id }}, 'ready')"><i class="bx bx-check me-2"></i>Mark Ready</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0)" onclick="updateStatus({{ $rx->id }}, 'delivered')"><i class="bx bx-package me-2"></i>Mark Delivered</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="javascript:void(0)" onclick="updateStatus({{ $rx->id }}, 'cancelled')"><i class="bx bx-x me-2"></i>Cancel</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr class="medication-details" id="details-{{ $rx->id }}" style="display: none;">
                        <td colspan="8" class="bg-light">
                            <div class="p-3">
                                <h6 class="mb-3">Medications</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Drug</th>
                                                <th>Type</th>
                                                <th>Dosage</th>
                                                <th>Qty</th>
                                                <th>Frequency</th>
                                                <th>Duration</th>
                                                <th>Instructions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($rx->medications ?? [] as $index => $med)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $med->drug_name }}</td>
                                                <td>{{ $med->type }}</td>
                                                <td>{{ $med->dosage }}</td>
                                                <td>{{ $med->quantity }}</td>
                                                <td>{{ $med->frequency_per_day }}x/day ({{ $med->frequency_time }})</td>
                                                <td>{{ $med->duration_value }} {{ $med->duration_unit }}</td>
                                                <td>{{ $med->instructions ?? '-' }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="8" class="text-center text-muted">No medications</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bx bx-receipt fs-1"></i>
                            <p class="mb-0">No prescriptions found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Prescription Modal -->
<div class="modal fade" id="createRxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create E-Prescription <span id="medCount" class="badge bg-light text-dark ms-2">0 meds</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="rxForm">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Patient Name *</label>
                            <input type="text" class="form-control" id="rxPatient" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Consultation Type</label>
                            <select class="form-select" id="rxConsultation">
                                <option value="online">Online</option>
                                <option value="physical">Physical</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Fulfillment</label>
                            <select class="form-select" id="rxFulfillment">
                                <option value="pickup">Pickup</option>
                                <option value="delivery">Delivery</option>
                            </select>
                        </div>
                        
                        <div class="col-12"><hr class="my-2"><h6 class="mb-2">Add Medication</h6></div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Drug Name *</label>
                            <input type="text" class="form-control" id="rxDrug" list="drugList">
                            <datalist id="drugList"></datalist>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Type</label>
                            <select class="form-select" id="rxType">
                                <option>Tablet</option>
                                <option>Capsule</option>
                                <option>Syrup</option>
                                <option>Injection</option>
                                <option>Suppository</option>
                                <option>Pessary</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Dosage</label>
                            <input type="text" class="form-control" id="rxDosage" placeholder="e.g., 500 mg">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Qty (auto)</label>
                            <input type="number" class="form-control" id="rxQty" value="1" readonly>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label">Frequency (per day)</label>
                            <select class="form-select" id="rxFreqDay" onchange="updateQty()">
                                <option value="1">OD (1x/day)</option>
                                <option value="2">BD (2x/day)</option>
                                <option value="3">TDS (3x/day)</option>
                                <option value="4">QDS (4x/day)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Frequency (per time)</label>
                            <select class="form-select" id="rxFreqTime">
                                <option>24-hourly</option>
                                <option>12-hourly</option>
                                <option>8-hourly</option>
                                <option>6-hourly</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Duration</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="rxDurationVal" value="7" min="1" onchange="updateQty()">
                                <select class="form-select" id="rxDurationUnit" onchange="updateQty()">
                                    <option value="days">Days</option>
                                    <option value="weeks">Weeks</option>
                                    <option value="months">Months</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Instructions</label>
                            <input type="text" class="form-control" id="rxInstructions" placeholder="e.g., after meals">
                        </div>
                        
                        <div class="col-12 d-flex justify-content-end">
                            <button type="button" class="btn btn-outline-primary" onclick="addMedication()">
                                <i class="bx bx-plus me-2"></i>Add Medication
                            </button>
                        </div>
                        
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Medication</th>
                                            <th>Qty</th>
                                            <th>Frequency</th>
                                            <th>Duration</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="medList"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="savePrescription()">Save Prescription</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let medications = [];

function filterPrescriptions() {
    const status = document.getElementById('statusFilter').value;
    const search = document.getElementById('searchInput').value;
    window.location.href = `/dashboard-pharmacy?pg=prescriptions&status=${status}&search=${encodeURIComponent(search)}`;
}

function updateQty() {
    const freqDay = parseInt(document.getElementById('rxFreqDay').value);
    const durVal = parseInt(document.getElementById('rxDurationVal').value);
    const durUnit = document.getElementById('rxDurationUnit').value;
    
    let days = durVal;
    if (durUnit === 'weeks') days = durVal * 7;
    if (durUnit === 'months') days = durVal * 30;
    
    const qty = freqDay * days;
    document.getElementById('rxQty').value = qty;
}

function addMedication() {
    const drug = document.getElementById('rxDrug').value.trim();
    if (!drug) {
        alert('Please enter drug name');
        return;
    }
    
    const med = {
        drug_name: drug,
        type: document.getElementById('rxType').value,
        dosage: document.getElementById('rxDosage').value,
        quantity: parseInt(document.getElementById('rxQty').value),
        frequency_per_day: parseInt(document.getElementById('rxFreqDay').value),
        frequency_time: document.getElementById('rxFreqTime').value,
        duration_value: parseInt(document.getElementById('rxDurationVal').value),
        duration_unit: document.getElementById('rxDurationUnit').value,
        instructions: document.getElementById('rxInstructions').value
    };
    
    medications.push(med);
    renderMedications();
    
    // Clear form
    document.getElementById('rxDrug').value = '';
    document.getElementById('rxDosage').value = '';
    document.getElementById('rxInstructions').value = '';
}

function renderMedications() {
    const tbody = document.getElementById('medList');
    tbody.innerHTML = '';
    
    medications.forEach((med, index) => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${med.drug_name} <small class="text-muted">(${med.type}, ${med.dosage})</small></td>
            <td>${med.quantity}</td>
            <td>${med.frequency_per_day}x/day (${med.frequency_time})</td>
            <td>${med.duration_value} ${med.duration_unit}</td>
            <td>
                <button class="btn btn-sm btn-outline-danger" onclick="removeMedication(${index})">
                    <i class="bx bx-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    });
    
    document.getElementById('medCount').textContent = `${medications.length} ${medications.length === 1 ? 'med' : 'meds'}`;
}

function removeMedication(index) {
    medications.splice(index, 1);
    renderMedications();
}

function savePrescription() {
    const patient = document.getElementById('rxPatient').value.trim();
    if (!patient) {
        alert('Please enter patient name');
        return;
    }
    
    if (medications.length === 0) {
        alert('Please add at least one medication');
        return;
    }
    
    // TODO: Implement API call to save prescription
    alert('Prescription saved successfully!');
    location.reload();
}

function updateStatus(id, status) {
    if (confirm(`Update prescription status to ${status}?`)) {
        // TODO: Implement API call
        alert('Status updated!');
        location.reload();
    }
}

function viewPrescription(id) {
    const detailsRow = document.getElementById('details-' + id);
    if (detailsRow.style.display === 'none') {
        detailsRow.style.display = '';
    } else {
        detailsRow.style.display = 'none';
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updateQty();
    
    // Add click handler to rows
    document.querySelectorAll('.prescription-row').forEach(row => {
        row.style.cursor = 'pointer';
        row.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown')) {
                const id = this.dataset.id;
                viewPrescription(id);
            }
        });
    });
});
</script>
@endsection
