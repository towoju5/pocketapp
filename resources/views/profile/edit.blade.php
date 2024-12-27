@extends('layouts.app')

@section('title', 'Profile')
@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Profile Overview Card -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="position-relative d-inline-block mb-3">
                        <img src="{{ Auth::user()->profile_photo_url ?? asset('assets/images/user1.png') }}" class="rounded-circle" width="120" height="120">
                        <button class="btn btn-sm btn-primary position-absolute bottom-0 end-0" data-bs-toggle="modal" data-bs-target="#updatePhotoModal">
                            <i class="fas fa-camera"></i>
                        </button>
                    </div>
                    <h4>{{ Auth::user()->name }}</h4>
                    <p class="text-muted">Member since {{ Auth::user()->created_at->format('j, M Y') }}</p>
                    {{-- <div class="d-flex justify-content-around mt-3">
                        <div>
                            <h6>Trading Balance</h6>
                            <p class="text-primary">${{ number_format($wallets['active_wallet']->balance, 2) }}</p>
                        </div>
                        <div>
                            <h6>Total Trades</h6>
                            <p class="text-success">{{ $totalTrades ?? 0 }}</p>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="col-md-8">
            <!-- Trading Accounts -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Trading Accounts</h5>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#newAccountModal">
                        <i class="fas fa-plus"></i> New Account
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Account Type</th>
                                    <th>Balance</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($wallets['all_wallets'] as $wallet)
                                <tr>
                                    <td>{{ ucwords(str_replace('_', ' ', $wallet->type)) }}</td>
                                    <td>${{ number_format($wallet->balance, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $wallet->mode === 'live' ? 'success' : 'warning' }}">
                                            {{ ucfirst($wallet->mode) }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" onclick="setActiveWallet('{{ $wallet->id }}')">
                                            {{ $wallet->currently_active ? 'Active' : 'Set Active' }}
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Personal Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" name="name" value="{{ Auth::user()->name }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" value="{{ Auth::user()->email }}" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="tel" class="form-control" name="phone" value="{{ Auth::user()->phone }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Country</label>
                                <select class="form-select" name="country">
                                    <option value="">Select Country</option>
                                    <!-- Add country options -->
                                    
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </form>
                </div>
            </div>

            <!-- Security Settings -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Security Settings</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6>Two-Factor Authentication</h6>
                                <p class="text-muted mb-0">Add additional security to your account</p>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="2faToggle" 
                                    {{ Auth::user()->two_factor_enabled ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h6>Change Password</h6>
                        <form action="{{ route('password.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label">Current Password</label>
                                <input type="password" class="form-control" name="current_password">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password" class="form-control" name="password">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" name="password_confirmation">
                            </div>
                            <button type="submit" class="btn btn-primary">Update Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Photo Update Modal -->
<div class="modal fade" id="updatePhotoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Profile Photo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('profile.photo.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Choose Photo</label>
                        <input type="file" class="form-control" name="photo" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-primary">Upload Photo</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script>
function setActiveWallet(walletId) {
    fetch(`/profile/set-active-wallet/${walletId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        }
    });
}
</script>
@endpush



