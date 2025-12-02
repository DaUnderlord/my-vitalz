@extends('pharmacy.layout')

@section('title', 'Dashboard')

@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Pharmacy /</span> Dashboard
</h4>

<!-- Welcome Alert -->
<div class="row">
    <div class="col-12">
        <div class="alert alert-primary border-0" role="alert">
            <i class="bx bx-check-circle me-2"></i>
            <strong>Welcome to MyVitalz Pharmacy!</strong> Your comprehensive pharmacy management system is ready.
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span>Network Members</span>
                        <div class="d-flex align-items-end mt-2">
                            <h4 class="mb-0 me-2">{{ $stats['network_members'] }}</h4>
                            <small class="text-primary">(Active)</small>
                        </div>
                    </div>
                    <span class="avatar">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="bx bx-network-chart bx-sm"></i>
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span>Pending Prescriptions</span>
                        <div class="d-flex align-items-end mt-2">
                            <h4 class="mb-0 me-2">{{ $stats['pending_prescriptions'] }}</h4>
                            <small class="text-warning">(Needs attention)</small>
                        </div>
                    </div>
                    <span class="avatar">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="bx bx-receipt bx-sm"></i>
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span>Inventory Items</span>
                        <div class="d-flex align-items-end mt-2">
                            <h4 class="mb-0 me-2">{{ $stats['inventory_items'] }}</h4>
                            <small class="text-info">({{ $stats['low_stock_items'] }} low stock)</small>
                        </div>
                    </div>
                    <span class="avatar">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="bx bx-package bx-sm"></i>
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12 mb-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="content-left">
                        <span>Monthly Revenue</span>
                        <div class="d-flex align-items-end mt-2">
                            <h4 class="mb-0 me-2">₦{{ number_format($stats['monthly_revenue'], 2) }}</h4>
                            <small class="text-success">(This month)</small>
                        </div>
                    </div>
                    <span class="avatar">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="bx bx-coin bx-sm"></i>
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Prescriptions</h5>
                <a href="/dashboard-pharmacy?pg=prescriptions" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_prescriptions as $rx)
                            <tr>
                                <td><small>{{ $rx->prescription_id }}</small></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-2">
                                            <span class="avatar-initial rounded-circle bg-label-primary">{{ substr($rx->patient_name ?? 'P', 0, 1) }}</span>
                                        </div>
                                        <span>{{ $rx->patient_name ?? 'Unknown' }}</span>
                                    </div>
                                </td>
                                <td>{{ $rx->doctor_name ?? 'Unknown' }}</td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'processing' => 'info',
                                            'ready' => 'primary',
                                            'delivered' => 'success',
                                            'cancelled' => 'danger'
                                        ];
                                        $color = $statusColors[$rx->status] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $color }}">{{ ucfirst($rx->status) }}</span>
                                </td>
                                <td><small>{{ date('M d, Y', strtotime($rx->created_at)) }}</small></td>
                                <td>
                                    <a href="/dashboard-pharmacy?pg=prescriptions&view={{ $rx->id }}" class="btn btn-sm btn-outline-primary">View</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No recent prescriptions</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="/dashboard-pharmacy?pg=prescriptions" class="btn btn-primary">
                        <i class="bx bx-plus me-2"></i>New Prescription
                    </a>
                    <a href="/dashboard-pharmacy?pg=inventory" class="btn btn-outline-primary">
                        <i class="bx bx-package me-2"></i>Add Inventory
                    </a>
                    <a href="/dashboard-pharmacy?pg=network" class="btn btn-outline-primary">
                        <i class="bx bx-user-plus me-2"></i>Invite Partner
                    </a>
                    <a href="/dashboard-pharmacy?pg=monitoring" class="btn btn-outline-primary">
                        <i class="bx bx-bell me-2"></i>Patient Monitoring
                    </a>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Low Stock Alerts</h5>
            </div>
            <div class="card-body">
                @forelse($low_stock_alerts as $item)
                <div class="d-flex align-items-center mb-3">
                    <i class="bx bx-error-circle {{ $item->stock_quantity == 0 ? 'text-danger' : 'text-warning' }} me-3 fs-4"></i>
                    <div>
                        <h6 class="mb-0">{{ $item->medication_name }}</h6>
                        <small class="text-muted">Only {{ $item->stock_quantity }} left</small>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-3">
                    <i class="bx bx-check-circle fs-1"></i>
                    <p class="mb-0">All items well stocked</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Auto-refresh stats every 30 seconds
setInterval(function() {
    location.reload();
}, 30000);
</script>
@endsection
