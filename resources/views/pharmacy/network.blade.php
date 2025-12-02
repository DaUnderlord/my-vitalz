@extends('pharmacy.layout')

@section('title', 'Network Management')

@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Pharmacy /</span> Network
</h4>

<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">Invite to Network</h5>
            </div>
            <div class="card-body">
                <form id="inviteForm" onsubmit="event.preventDefault(); inviteMember();">
                    <div class="mb-3">
                        <label class="form-label">Email or Phone *</label>
                        <input type="text" class="form-control" id="inviteIdentifier" placeholder="partner@example.com or 0803..." required>
                        <small class="text-muted">You can type a phone number or an email address.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Partner Type</label>
                        <select class="form-select" id="inviteType">
                            <option value="pharmacy">Pharmacy</option>
                            <option value="hospital">Hospital</option>
                        </select>
                    </div>
                    <button class="btn btn-primary w-100" type="submit">
                        <i class="bx bx-send me-2"></i>Send Invite
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header d-flex flex-wrap gap-2 justify-content-between align-items-center">
                <h5 class="mb-0">Network Partners</h5>
                <div class="d-flex gap-2">
                    <input class="form-control form-control-sm" id="partnerSearch" placeholder="Search name/email/phone" oninput="filterNetwork()" />
                    <select class="form-select form-select-sm w-auto" id="networkFilter" onchange="filterNetwork()">
                        <option value="all">All Partners</option>
                        <option value="pharmacy">Pharmacies</option>
                        <option value="hospital">Hospitals</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="networkTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($network_members as $member)
                            <tr data-type="{{ $member->member_type }}" data-name="{{ strtolower($member->name ?? '') }}" data-email="{{ strtolower($member->email ?? '') }}" data-phone="{{ preg_replace('/\D+/', '', $member->phone ?? '') }}">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-2">
                                            <span class="avatar-initial rounded-circle bg-label-{{ $member->member_type == 'pharmacy' ? 'primary' : 'info' }}">
                                                {{ substr($member->name ?? 'P', 0, 1) }}
                                            </span>
                                        </div>
                                        <strong>{{ $member->name ?? 'Unknown' }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-label-{{ $member->member_type == 'pharmacy' ? 'primary' : 'info' }}">
                                        {{ ucfirst($member->member_type) }}
                                    </span>
                                </td>
                                <td>{{ $member->email ?? '-' }}</td>
                                <td>{{ $member->phone ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $member->status == 'active' ? 'success' : 'warning' }}">
                                        {{ ucfirst($member->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                                            Actions
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="viewPartner({{ $member->member_id }}, '{{ $member->member_type }}')"><i class="bx bx-show me-2"></i>View Details</a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="messagePartner({{ $member->member_id }}, '{{ $member->name }}')"><i class="bx bx-message me-2"></i>Send Message</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item text-danger" href="javascript:void(0)" onclick="removePartner({{ $member->id }}, '{{ $member->name }}')"><i class="bx bx-trash me-2"></i>Remove</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="bx bx-network-chart fs-1"></i>
                                    <p class="mb-0">No network partners yet</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Partner Details Modal -->
<div class="modal fade" id="partnerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Partner Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 id="partnerName" class="text-primary mb-3"></h6>
                        <div class="mb-2"><strong>Type:</strong> <span id="partnerType"></span></div>
                        <div class="mb-2"><strong>Email:</strong> <span id="partnerEmail"></span></div>
                        <div class="mb-2"><strong>Status:</strong> <span id="partnerStatus"></span></div>
                    </div>
                    <div class="col-md-6">
                        <div id="partnerInfo"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="navigateToChat()">
                    <i class="bx bx-message me-2"></i>Chat
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function inviteMember() {
    const identifier = document.getElementById('inviteIdentifier').value;
    const type = document.getElementById('inviteType').value;
    
    fetch('/pharmacy/network/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        body: JSON.stringify({ identifier, type })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Redirect to messages with banner and open the specific thread
            const params = new URLSearchParams({
                pg: 'messages',
                banner: 'new_partner',
                partner_id: data.partner_id,
                partner_type: data.partner_type,
                partner_name: data.partner_name
            });
            window.location.href = `/dashboard-pharmacy?${params.toString()}`;
        } else {
            alert(data.message || 'Failed to send invitation');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred');
    });
}

function filterNetwork() {
    const filter = document.getElementById('networkFilter').value;
    const search = (document.getElementById('partnerSearch').value || '').toLowerCase().trim();
    const rows = document.querySelectorAll('#networkTable tbody tr');
    
    rows.forEach(row => {
        const type = row.dataset.type;
        const name = (row.dataset.name || '').toLowerCase();
        const email = (row.dataset.email || '').toLowerCase();
        const phone = (row.dataset.phone || '').toLowerCase();
        const matchesType = (filter === 'all' || type === filter);
        const matchesSearch = (!search || name.includes(search) || email.includes(search) || phone.includes(search.replace(/\D+/g,'')));
        row.style.display = (matchesType && matchesSearch) ? '' : 'none';
    });
}

function viewPartner(memberId, type) {
    // TODO: Fetch partner details via API
    document.getElementById('partnerName').textContent = 'Partner Name';
    document.getElementById('partnerType').textContent = type;
    document.getElementById('partnerEmail').textContent = 'email@example.com';
    document.getElementById('partnerStatus').innerHTML = '<span class="badge bg-success">Active</span>';
    
    new bootstrap.Modal(document.getElementById('partnerModal')).show();
}

function messagePartner(memberId, name) {
    window.location.href = `/dashboard-pharmacy?pg=messages&partner=${memberId}`;
}

function removePartner(id, name) {
    if (confirm(`Are you sure you want to remove ${name} from your network?`)) {
        // TODO: Implement API call
        alert('Partner removed successfully!');
        location.reload();
    }
}

function navigateToChat() {
    window.location.href = '/dashboard-pharmacy?pg=messages';
}
</script>
@endsection
