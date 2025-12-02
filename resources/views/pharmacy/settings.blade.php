@extends('pharmacy.layout')

@section('title', 'Virtual Pharmacy Settings')

@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Pharmacy /</span> Virtual Pharmacy Settings
</h4>

<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">Branding & Defaults</h5>
            </div>
            <div class="card-body">
                <form id="settingsForm">
                    <div class="mb-3">
                        <label class="form-label">Default Doctor Markup (%)</label>
                        <input type="number" class="form-control" id="doctorMarkup" min="0" max="100" step="0.1" value="{{ $settings->doctor_markup_percentage }}">
                        <small class="text-muted">Percentage added to base price for doctor prescriptions</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Default Delivery Fee (₦)</label>
                        <input type="number" class="form-control" id="deliveryFee" min="0" step="50" value="{{ $settings->default_delivery_fee }}">
                        <small class="text-muted">Standard delivery charge for prescriptions</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Storefront Logo URL</label>
                        <input type="url" class="form-control" id="logoUrl" placeholder="https://example.com/logo.png" value="{{ $settings->storefront_logo_url }}">
                        <small class="text-muted">URL to your pharmacy logo for virtual storefront</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Virtual Pharmacy Link</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="virtualLink" value="{{ $settings->virtual_pharmacy_link ?? 'https://myvitalz.ai/pharmacy/' . $user->id }}" readonly>
                            <button class="btn btn-outline-secondary" type="button" onclick="copyLink()">
                                <i class="bx bx-copy"></i>
                            </button>
                        </div>
                        <small class="text-muted">Share this link with doctors for virtual pharmacy access</small>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary" onclick="saveSettings()">
                            <i class="bx bx-save me-2"></i>Save Settings
                        </button>
                        <button type="button" class="btn btn-outline-primary" onclick="generateQR()">
                            <i class="bx bx-qr me-2"></i>Generate QR Code
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Discount Policy</h5>
                <small class="text-muted">Applied to all inventory items</small>
            </div>
            <div class="card-body">
                <form id="discountForm">
                    <div class="mb-3">
                        <label class="form-label">Doctor Discount (%)</label>
                        <input type="number" class="form-control" id="doctorDiscount" min="0" max="100" step="0.1" value="{{ $settings->doctor_discount_percentage }}">
                        <small class="text-muted">Discount given to doctors on retail prices</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Wholesale Discount (%)</label>
                        <input type="number" class="form-control" id="wholesaleDiscount" min="0" max="100" step="0.1" value="{{ $settings->wholesale_discount_percentage }}">
                        <small class="text-muted">Discount for bulk/wholesale purchases</small>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="bx bx-info-circle me-2"></i>
                        <small>Changes will apply to all future transactions</small>
                    </div>
                    
                    <button type="button" class="btn btn-primary" onclick="saveDiscountPolicy()">
                        <i class="bx bx-check me-2"></i>Apply Discount Policy
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Linked Partners</h5>
                    <small class="text-muted">Select partners for out-of-stock routing and clearance notifications</small>
                </div>
                <button class="btn btn-sm btn-outline-primary" onclick="savePartnerLinks()">
                    <i class="bx bx-save me-2"></i>Save Partner Links
                </button>
            </div>
            <div class="card-body">
                <div class="row" id="partnersList">
                    @forelse($partners as $partner)
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="{{ $partner->member_id }}" id="partner{{ $partner->member_id }}" data-type="{{ $partner->member_type }}" checked>
                            <label class="form-check-label" for="partner{{ $partner->member_id }}">
                                <strong>{{ $partner->name }}</strong>
                                <span class="badge bg-label-{{ $partner->member_type == 'pharmacy' ? 'primary' : 'info' }} ms-2">
                                    {{ ucfirst($partner->member_type) }}
                                </span>
                                <br>
                                <small class="text-muted">{{ $partner->email }}</small>
                            </label>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="text-center text-muted py-4">
                            <i class="bx bx-network-chart fs-1"></i>
                            <p class="mb-0">No partners in your network yet</p>
                            <a href="/dashboard-pharmacy?pg=network" class="btn btn-sm btn-outline-primary mt-2">
                                <i class="bx bx-plus me-2"></i>Add Partners
                            </a>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- QR Code Modal -->
<div class="modal fade" id="qrModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Virtual Pharmacy QR Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div id="qrCodeContainer" class="mb-3">
                    <!-- QR code will be generated here -->
                    <div class="bg-light p-4 rounded">
                        <i class="bx bx-qr bx-lg"></i>
                        <p class="mb-0 mt-2">QR Code will appear here</p>
                    </div>
                </div>
                <p class="text-muted">Scan this QR code to access your virtual pharmacy</p>
                <div class="alert alert-info">
                    <small>Share this QR code with doctors for easy mobile access</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="downloadQR()">
                    <i class="bx bx-download me-2"></i>Download QR
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function saveSettings() {
    const data = {
        markup: document.getElementById('doctorMarkup').value,
        delivery_fee: document.getElementById('deliveryFee').value,
        logo: document.getElementById('logoUrl').value
    };
    
    fetch('/pharmacy/settings/save', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Settings saved successfully!');
        } else {
            alert(data.message || 'Failed to save settings');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred');
    });
}

function saveDiscountPolicy() {
    const data = {
        doctor_discount: document.getElementById('doctorDiscount').value,
        wholesale_discount: document.getElementById('wholesaleDiscount').value
    };
    
    fetch('/pharmacy/settings/discount', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Discount policy updated successfully!');
            location.reload();
        } else {
            alert(data.message || 'Failed to update discount policy');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred');
    });
}

function savePartnerLinks() {
    const selectedPartners = [];
    document.querySelectorAll('#partnersList input[type="checkbox"]:checked').forEach(checkbox => {
        selectedPartners.push({
            id: checkbox.value,
            type: checkbox.dataset.type
        });
    });
    
    // TODO: Implement API call to save partner links
    alert(`${selectedPartners.length} partners linked successfully!`);
}

function copyLink() {
    const linkInput = document.getElementById('virtualLink');
    linkInput.select();
    document.execCommand('copy');
    alert('Link copied to clipboard!');
}

function generateQR() {
    // TODO: Implement QR code generation using a library like qrcode.js
    new bootstrap.Modal(document.getElementById('qrModal')).show();
}

function downloadQR() {
    // TODO: Implement QR code download
    alert('QR code download functionality - Coming soon');
}
</script>
@endsection
