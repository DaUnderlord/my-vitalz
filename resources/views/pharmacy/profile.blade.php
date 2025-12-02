@extends('pharmacy.layout')

@section('title', 'Profile Settings')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Page Title -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="page-title mb-0">
            <i class='bx bx-user-circle me-2'></i>Profile Settings
        </h4>
    </div>

    <!-- Alert Messages -->
    @if(!empty($a_type) && !empty($a_message))
    <div class="alert alert-{{ $a_type == 'success' ? 'success' : ($a_type == 'warning' ? 'warning' : 'danger') }} alert-dismissible fade show" role="alert">
        <strong>{{ $a_type == 'success' ? 'Success!' : ($a_type == 'warning' ? 'Warning!' : 'Error!') }}</strong> {{ $a_message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <!-- Profile Photo Card -->
        <div class="col-md-4 mb-4">
            <div class="card stat-card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if(!empty($user->photo))
                        <img src="/assets/images/{{ $user->photo }}" alt="Profile" class="rounded-circle" width="150" height="150" style="object-fit: cover; border: 4px solid #5a5fc7;">
                        @else
                        <div class="avatar avatar-xl mx-auto" style="width: 150px; height: 150px;">
                            <span class="avatar-initial rounded-circle bg-label-primary" style="font-size: 3rem;">
                                {{ strtoupper(substr($user->first_name ?? $user->name ?? 'U', 0, 1)) }}
                            </span>
                        </div>
                        @endif
                    </div>
                    
                    <h5 class="mb-1">{{ $user->first_name ?? '' }} {{ $user->last_name ?? '' }}</h5>
                    <p class="text-muted mb-3">
                        @if($user->doctor == 1)
                            <span class="badge bg-label-primary">Doctor</span>
                        @endif
                        @if($user->pharmacy == 1)
                            <span class="badge bg-label-success">Pharmacy</span>
                        @endif
                        @if($user->hospital == 1)
                            <span class="badge bg-label-info">Hospital</span>
                        @endif
                    </p>

                    <!-- Upload Photo Form -->
                    <form action="/dashboard-pharmacy?pg=profile" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <input type="file" class="form-control" name="upload_profile" id="upload_profile" accept="image/*" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class='bx bx-upload me-1'></i>Upload Photo
                        </button>
                    </form>

                    <hr class="my-4">

                    <!-- Profile Stats -->
                    <div class="d-flex justify-content-around text-center">
                        <div>
                            <h4 class="mb-0 text-primary">{{ $user->ref_code ?? 'N/A' }}</h4>
                            <small class="text-muted">Ref Code</small>
                        </div>
                        <div>
                            <h4 class="mb-0 text-success">
                                @if($user->public == 1)
                                    <i class='bx bx-globe'></i>
                                @else
                                    <i class='bx bx-lock'></i>
                                @endif
                            </h4>
                            <small class="text-muted">{{ $user->public == 1 ? 'Public' : 'Private' }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Information Card -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="section-title mb-0">
                        <i class='bx bx-edit me-2'></i>Profile Information
                    </h5>
                </div>
                <div class="card-body">
                    <form action="/dashboard-pharmacy?pg=profile" method="POST">
                        @csrf
                        <input type="hidden" name="action" value="update_profile">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="first_name" name="first_name" value="{{ $user->first_name ?? '' }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="last_name" name="last_name" value="{{ $user->last_name ?? '' }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="phone" name="phone" value="{{ $user->phone ?? '' }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" value="{{ $user->email ?? '' }}" disabled>
                                <small class="text-muted">Email cannot be changed</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address" value="{{ $user->address ?? '' }}">
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control" id="city" name="city" value="{{ $user->city ?? '' }}">
                            </div>
                            <div class="col-md-4">
                                <label for="state" class="form-label">State</label>
                                <input type="text" class="form-control" id="state" name="state" value="{{ $user->state ?? '' }}">
                            </div>
                            <div class="col-md-4">
                                <label for="country" class="form-label">Country</label>
                                <input type="text" class="form-control" id="country" name="country" value="{{ $user->country ?? '' }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="practice_location" class="form-label">Practice Location</label>
                            <input type="text" class="form-control" id="practice_location" name="practice_location" value="{{ $user->practice_location ?? '' }}" placeholder="e.g., Main Clinic, Hospital Name">
                        </div>

                        <div class="mb-3">
                            <label for="about" class="form-label">About / Bio</label>
                            <textarea class="form-control" id="about" name="about" rows="4" placeholder="Tell us about yourself...">{{ $user->about ?? '' }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Profile Visibility</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="profile_status" name="profile_status" value="1" {{ ($user->public ?? 0) == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="profile_status">
                                    <strong>Make Profile Public</strong>
                                    <br>
                                    <small class="text-muted">When enabled, your profile will be discoverable by patients, pharmacies, and hospitals searching for healthcare providers.</small>
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="/dashboard-pharmacy" class="btn btn-label-secondary">
                                <i class='bx bx-x me-1'></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class='bx bx-save me-1'></i>Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Information Cards -->
    <div class="row">
        <!-- Account Information -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="section-title mb-0">
                        <i class='bx bx-info-circle me-2'></i>Account Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Account Type</label>
                        <p class="mb-0">
                            @if($user->doctor == 1)
                                <span class="badge bg-label-primary me-1">Doctor</span>
                            @endif
                            @if($user->pharmacy == 1)
                                <span class="badge bg-label-success me-1">Pharmacy</span>
                            @endif
                            @if($user->hospital == 1)
                                <span class="badge bg-label-info me-1">Hospital</span>
                            @endif
                            @if($user->sales_rep == 1)
                                <span class="badge bg-label-warning me-1">Sales Rep</span>
                            @endif
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Member Since</label>
                        <p class="mb-0">{{ $user->created_at ?? $user->date ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Referral Code</label>
                        <div class="input-group">
                            <input type="text" class="form-control" value="{{ $user->ref_code ?? 'N/A' }}" id="refCodeInput" readonly>
                            <button class="btn btn-outline-primary" type="button" onclick="copyRefCode()">
                                <i class='bx bx-copy'></i>
                            </button>
                        </div>
                    </div>
                    @if($user->doctor == 1 && !empty($user->specialization))
                    <div class="mb-0">
                        <label class="text-muted small">Specialization</label>
                        <p class="mb-0"><span class="badge bg-label-primary">{{ $user->specialization }}</span></p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Security & Privacy -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="section-title mb-0">
                        <i class='bx bx-shield me-2'></i>Security & Privacy
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Profile Visibility</label>
                        <p class="mb-0">
                            @if($user->public == 1)
                                <span class="badge bg-success"><i class='bx bx-globe'></i> Public</span>
                                <br><small class="text-muted">Your profile is discoverable in search</small>
                            @else
                                <span class="badge bg-secondary"><i class='bx bx-lock'></i> Private</span>
                                <br><small class="text-muted">Only invited members can see your profile</small>
                            @endif
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small">Password</label>
                        <p class="mb-0">
                            <button class="btn btn-sm btn-outline-primary" disabled>
                                <i class='bx bx-key me-1'></i>Change Password
                            </button>
                            <br><small class="text-muted">Password management coming soon</small>
                        </p>
                    </div>
                    <div class="mb-0">
                        <label class="text-muted small">Two-Factor Authentication</label>
                        <p class="mb-0">
                            <span class="badge bg-label-warning">Not Enabled</span>
                            <br><small class="text-muted">Coming soon</small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyRefCode() {
    const refCodeInput = document.getElementById('refCodeInput');
    refCodeInput.select();
    document.execCommand('copy');
    
    // Show toast notification
    const btn = event.target.closest('button');
    const originalHTML = btn.innerHTML;
    btn.innerHTML = '<i class="bx bx-check"></i>';
    btn.classList.add('btn-success');
    btn.classList.remove('btn-outline-primary');
    
    setTimeout(() => {
        btn.innerHTML = originalHTML;
        btn.classList.remove('btn-success');
        btn.classList.add('btn-outline-primary');
    }, 2000);
}
</script>
@endsection
