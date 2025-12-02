@extends('pharmacy.layout')

@section('title', 'Doctor Rewards')

@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Pharmacy /</span> Doctor Rewards
</h4>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="d-block mb-1">Total Rewards</span>
                        <h4 class="mb-0">₦{{ number_format($total_rewards, 2) }}</h4>
                    </div>
                    <span class="avatar">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="bx bx-gift bx-sm"></i>
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="d-block mb-1">Doctors</span>
                        <h4 class="mb-0">{{ $doctor_count }}</h4>
                    </div>
                    <span class="avatar">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="bx bx-user bx-sm"></i>
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="d-block mb-1">Pending Payout</span>
                        <h4 class="mb-0">₦{{ number_format($pending_payout, 2) }}</h4>
                    </div>
                    <span class="avatar">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="bx bx-time bx-sm"></i>
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="d-block mb-1">Paid Out</span>
                        <h4 class="mb-0">₦{{ number_format($paid_out, 2) }}</h4>
                    </div>
                    <span class="avatar">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="bx bx-wallet bx-sm"></i>
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Rewards Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Rewards History</h5>
        <select class="form-select form-select-sm w-auto" id="rewardsFilter" onchange="filterRewards()">
            <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>All</option>
            <option value="pending" {{ $filter == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="paid" {{ $filter == 'paid' ? 'selected' : '' }}>Paid</option>
        </select>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Doctor</th>
                        <th>Prescription</th>
                        <th>Patient</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rewards as $reward)
                    <tr data-status="{{ $reward->status }}">
                        <td><small>{{ date('M d, Y', strtotime($reward->created_at)) }}</small></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-2">
                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                        {{ substr($reward->doctor_name ?? 'D', 0, 1) }}
                                    </span>
                                </div>
                                <span>{{ $reward->doctor_name ?? 'Unknown' }}</span>
                            </div>
                        </td>
                        <td><small class="text-primary">{{ $reward->prescription_id }}</small></td>
                        <td>{{ $reward->patient_name ?? '-' }}</td>
                        <td><strong>₦{{ number_format($reward->reward_amount, 2) }}</strong></td>
                        <td>
                            <span class="badge bg-{{ $reward->status == 'paid' ? 'success' : 'warning' }}">
                                {{ ucfirst($reward->status) }}
                            </span>
                        </td>
                        <td>
                            @if($reward->status == 'pending')
                            <button class="btn btn-sm btn-outline-primary" onclick="markAsPaid({{ $reward->id }})">
                                <i class="bx bx-check me-1"></i>Mark Paid
                            </button>
                            @else
                            <button class="btn btn-sm btn-outline-secondary" disabled>
                                <i class="bx bx-check-circle me-1"></i>Paid
                            </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="bx bx-gift fs-1"></i>
                            <p class="mb-0">No rewards found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Payment Confirmation Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="rewardId">
                <p>Are you sure you want to mark this reward as paid?</p>
                <div class="alert alert-info">
                    <i class="bx bx-info-circle me-2"></i>
                    <small>This action will update the reward status and notify the doctor.</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Payment Reference (Optional)</label>
                    <input type="text" class="form-control" id="paymentRef" placeholder="Enter payment reference">
                </div>
                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" id="paymentNotes" rows="2" placeholder="Optional notes"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="confirmPayment()">
                    <i class="bx bx-check me-2"></i>Confirm Payment
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function filterRewards() {
    const filter = document.getElementById('rewardsFilter').value;
    window.location.href = `/dashboard-pharmacy?pg=rewards&filter=${filter}`;
}

function markAsPaid(rewardId) {
    document.getElementById('rewardId').value = rewardId;
    new bootstrap.Modal(document.getElementById('paymentModal')).show();
}

function confirmPayment() {
    const rewardId = document.getElementById('rewardId').value;
    const reference = document.getElementById('paymentRef').value;
    const notes = document.getElementById('paymentNotes').value;
    
    fetch('/api/pharmacy/reward/mark-paid', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        body: JSON.stringify({
            uid: getCookie('uid'),
            auth: getCookie('authen'),
            reward_id: rewardId,
            reference: reference,
            notes: notes
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Reward marked as paid successfully!');
            location.reload();
        } else {
            alert(data.message || 'Failed to update reward');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred');
    });
}

function getCookie(name) {
    const match = document.cookie.match(new RegExp('(?:^|; )' + name.replace(/([.$?*|{}()\[\]\\\/\+^])/g, '\\$1') + '=([^;]*)'));
    return match ? decodeURIComponent(match[1]) : '';
}
</script>
@endsection
