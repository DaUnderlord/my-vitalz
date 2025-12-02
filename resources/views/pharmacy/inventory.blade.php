@extends('pharmacy.layout')

@section('title', 'Inventory Management')

@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Pharmacy /</span> Inventory
</h4>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="d-flex gap-2 flex-wrap">
            <input type="text" class="form-control form-control-sm" id="searchInput" placeholder="Search product..." value="{{ $search }}" style="min-width: 200px;">
            <select class="form-select form-select-sm w-auto" id="stockFilter">
                <option value="all" {{ $stock_filter == 'all' ? 'selected' : '' }}>All Stock</option>
                <option value="low" {{ $stock_filter == 'low' ? 'selected' : '' }}>Low Stock</option>
                <option value="out" {{ $stock_filter == 'out' ? 'selected' : '' }}>Out of Stock</option>
            </select>
            <button class="btn btn-sm btn-outline-primary" onclick="filterInventory()">
                <i class="bx bx-search"></i> Search
            </button>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#discountModal">
                <i class="bx bx-purchase-tag-alt me-2"></i>Discount Policy
            </button>
            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addInventoryModal">
                <i class="bx bx-plus me-2"></i>Add Item
            </button>
            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#clearanceModal">
                <i class="bx bx-downvote me-2"></i>Clearance Sale
            </button>
            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#osrModal">
                <i class="bx bx-package me-2"></i>Out of Stock Request
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Medication</th>
                        <th>Generic Name</th>
                        <th>Form</th>
                        <th>Stock</th>
                        <th>Reorder Level</th>
                        <th>Prices</th>
                        <th>Expiry</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inventory as $item)
                    <tr class="{{ $item->stock_quantity <= $item->reorder_level ? 'table-warning' : '' }}">
                        <td>
                            <strong>{{ $item->medication_name }}</strong>
                            @if($item->stock_quantity == 0)
                                <span class="badge bg-danger ms-2">Out of Stock</span>
                            @elseif($item->stock_quantity <= $item->reorder_level)
                                <span class="badge bg-warning ms-2">Low Stock</span>
                            @endif
                        </td>
                        <td>{{ $item->generic_name ?? '-' }}</td>
                        <td><span class="badge bg-label-info">{{ $item->form ?? '-' }}</span></td>
                        <td>
                            <strong class="{{ $item->stock_quantity == 0 ? 'text-danger' : ($item->stock_quantity <= $item->reorder_level ? 'text-warning' : 'text-success') }}">
                                {{ $item->stock_quantity }}
                            </strong>
                        </td>
                        <td>{{ $item->reorder_level }}</td>
                        <td>
                            @php
                                $retail = $item->retail_price;
                                $doctor = $retail * (1 - ($settings->doctor_discount_percentage / 100));
                                $wholesale = $retail * (1 - ($settings->wholesale_discount_percentage / 100));
                            @endphp
                            <small>
                                <div><strong>Retail:</strong> ₦{{ number_format($retail, 2) }}</div>
                                <div><strong>Doctor:</strong> ₦{{ number_format($doctor, 2) }}</div>
                                <div><strong>Wholesale:</strong> ₦{{ number_format($wholesale, 2) }}</div>
                            </small>
                        </td>
                        <td>
                            @if($item->expiry_date)
                                @php
                                    $expiryDate = new DateTime($item->expiry_date);
                                    $now = new DateTime();
                                    $diff = $now->diff($expiryDate);
                                    $daysToExpiry = $diff->days * ($diff->invert ? -1 : 1);
                                @endphp
                                <small class="{{ $daysToExpiry < 0 ? 'text-danger' : ($daysToExpiry < 30 ? 'text-warning' : '') }}">
                                    {{ date('M d, Y', strtotime($item->expiry_date)) }}
                                    @if($daysToExpiry < 0)
                                        <br><span class="badge bg-danger">Expired</span>
                                    @elseif($daysToExpiry < 30)
                                        <br><span class="badge bg-warning">{{ $daysToExpiry }} days</span>
                                    @endif
                                </small>
                            @else
                                <small class="text-muted">-</small>
                            @endif
                        </td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                                    Actions
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="javascript:void(0)" onclick="editStock({{ $item->id }}, '{{ $item->medication_name }}', {{ $item->stock_quantity }})"><i class="bx bx-edit me-2"></i>Update Stock</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0)" onclick="viewDetails({{ $item->id }})"><i class="bx bx-show me-2"></i>View Details</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deleteItem({{ $item->id }})"><i class="bx bx-trash me-2"></i>Delete</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bx bx-package fs-1"></i>
                            <p class="mb-0">No inventory items found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Inventory Modal -->
<div class="modal fade" id="addInventoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Inventory Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addInventoryForm">
                    <div class="mb-3">
                        <label class="form-label">Medication Name *</label>
                        <input type="text" class="form-control" id="medName" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Generic Name</label>
                        <input type="text" class="form-control" id="genericName">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Form</label>
                            <select class="form-select" id="medForm">
                                <option>Tablet</option>
                                <option>Capsule</option>
                                <option>Syrup</option>
                                <option>Injection</option>
                                <option>Suppository</option>
                                <option>Pessary</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Dosage</label>
                            <input type="text" class="form-control" id="dosage" placeholder="e.g., 500mg">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stock Quantity *</label>
                            <input type="number" class="form-control" id="stockQty" min="0" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Reorder Level *</label>
                            <input type="number" class="form-control" id="reorderLevel" min="0" value="10" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Retail Price (₦) *</label>
                        <input type="number" class="form-control" id="retailPrice" step="0.01" min="0" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Expiry Date</label>
                            <input type="date" class="form-control" id="expiryDate">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Batch Number</label>
                            <input type="text" class="form-control" id="batchNumber">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Manufacturer</label>
                        <input type="text" class="form-control" id="manufacturer">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveInventory()">Save Item</button>
            </div>
        </div>
    </div>
</div>

<!-- Discount Policy Modal -->
<div class="modal fade" id="discountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Discount Policy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Doctor Discount (%)</label>
                    <input type="number" class="form-control" id="doctorDiscount" value="{{ $settings->doctor_discount_percentage }}" min="0" max="100">
                </div>
                <div class="mb-3">
                    <label class="form-label">Wholesale Discount (%)</label>
                    <input type="number" class="form-control" id="wholesaleDiscount" value="{{ $settings->wholesale_discount_percentage }}" min="0" max="100">
                </div>
                <small class="text-muted">Discounts apply to all inventory items</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveDiscountPolicy()">Apply</button>
            </div>
        </div>
    </div>
</div>

<!-- Clearance Sale Modal -->
<div class="modal fade" id="clearanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bx bx-downvote me-2"></i>Clearance Sale</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Select Item</label>
                    <select class="form-select" id="clearanceItem">
                        <option value="">Choose item...</option>
                        @foreach($inventory as $item)
                        <option value="{{ $item->id }}">{{ $item->medication_name }} (Stock: {{ $item->stock_quantity }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Offer Price (₦)</label>
                        <input type="number" class="form-control" id="clearancePrice" step="0.01" min="0">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="clearanceQty" min="1" value="1">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Reason</label>
                    <textarea class="form-control" id="clearanceReason" rows="2" placeholder="e.g., Near expiry, overstock..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="publishClearance()"><i class="bx bx-check me-2"></i>Publish</button>
            </div>
        </div>
    </div>
</div>

<!-- Out of Stock Request Modal -->
<div class="modal fade" id="osrModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bx bx-package me-2"></i>Out of Stock Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Drug Name</label>
                    <input type="text" class="form-control" id="osrDrug" placeholder="Enter drug name">
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="osrQty" min="1" value="1">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Fulfillment</label>
                        <select class="form-select" id="osrFulfillment">
                            <option value="pickup">Pickup</option>
                            <option value="delivery">Delivery</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Request From (Partner)</label>
                    <select class="form-select" id="osrPartner">
                        <option value="">Select partner...</option>
                        <!-- Partners will be loaded via AJAX -->
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" id="osrNotes" rows="2" placeholder="Optional notes"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitOSR()"><i class="bx bx-send me-2"></i>Send Request</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function filterInventory() {
    const search = document.getElementById('searchInput').value;
    const stockFilter = document.getElementById('stockFilter').value;
    window.location.href = `/dashboard-pharmacy?pg=inventory&search=${encodeURIComponent(search)}&stock_filter=${stockFilter}`;
}

function saveInventory() {
    const data = {
        medication_name: document.getElementById('medName').value,
        generic_name: document.getElementById('genericName').value,
        form: document.getElementById('medForm').value,
        dosage: document.getElementById('dosage').value,
        stock_quantity: document.getElementById('stockQty').value,
        reorder_level: document.getElementById('reorderLevel').value,
        retail_price: document.getElementById('retailPrice').value,
        expiry_date: document.getElementById('expiryDate').value,
        batch_number: document.getElementById('batchNumber').value,
        manufacturer: document.getElementById('manufacturer').value
    };
    
    // TODO: Implement API call
    alert('Inventory item saved!');
    location.reload();
}

function editStock(id, name, currentStock) {
    const newStock = prompt(`Update stock for ${name}\nCurrent: ${currentStock}`, currentStock);
    if (newStock !== null) {
        // TODO: Implement API call
        alert('Stock updated!');
        location.reload();
    }
}

function deleteItem(id) {
    if (confirm('Are you sure you want to delete this item?')) {
        // TODO: Implement API call
        alert('Item deleted!');
        location.reload();
    }
}

function saveDiscountPolicy() {
    const doctorDiscount = document.getElementById('doctorDiscount').value;
    const wholesaleDiscount = document.getElementById('wholesaleDiscount').value;
    
    // TODO: Implement API call
    alert('Discount policy updated!');
    location.reload();
}

function publishClearance() {
    const itemId = document.getElementById('clearanceItem').value;
    const price = document.getElementById('clearancePrice').value;
    const qty = document.getElementById('clearanceQty').value;
    const reason = document.getElementById('clearanceReason').value;
    
    if (!itemId || !price) {
        alert('Please select item and enter price');
        return;
    }
    
    // TODO: Implement API call
    alert('Clearance sale published to network!');
    location.reload();
}

function submitOSR() {
    const drug = document.getElementById('osrDrug').value;
    const qty = document.getElementById('osrQty').value;
    const partner = document.getElementById('osrPartner').value;
    
    if (!drug || !partner) {
        alert('Please enter drug name and select partner');
        return;
    }
    
    // TODO: Implement API call
    alert('Out of stock request sent!');
    location.reload();
}

// Load partners for OSR
document.addEventListener('DOMContentLoaded', function() {
    // TODO: Load partners via AJAX
});
</script>
@endsection
