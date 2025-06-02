@extends('layouts.app')

@section('title', 'Security - Polaris Option')
@section('content')
<div class="m-4 py-4" style="margin: 1rem">
    @include('partials.profile')

    <div class="grid lg:grid-cols-2 gap-4">
        <!-- Change Password Section -->
        <div class="panel box-border personal-info-panel mb-3">
            <div class="panel-heading bg-gray-100 dark:bg-gray-700 py-2 px-4 rounded-t-xl">
                <div class="panel-title text-lg font-semibold text-gray-800">Change Password</div>
            </div>
            <div class="panel-body">
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
                    <div class="overflow-x-auto text-gray-700 p-6 mx-auto max-w-xl">
                        <form id="change-password-form">
                            @csrf

                            <!-- Current Password -->
                            <div class="space-y-4 mb-6">
                                <div class="flex justify-between items-center flex gap-6">
                                    <label for="current-password" class="text-gray-600 whitespace-nowrap w-80">Current Password</label>
                                    <div class="w-full">
                                        <input type="password" id="current-password" name="current_password" class="w-full  p-2 border border-gray-300 rounded-md bg-transparent" required>
                                    </div>
                                </div>
                            </div>

                            <!-- New Password -->
                            <div class="space-y-4 mb-6">
                                <div class="flex justify-between items-center flex gap-6">
                                    <label for="new-password" class="text-gray-600 whitespace-nowrap w-80">New Password</label>
                                    <div class="w-full">
                                        <input type="password" id="new-password" name="new_password" class="w-full p-2  border border-gray-300 rounded-md bg-transparent" required minlength="8">
                                    </div>
                                </div>
                            </div>

                            <!-- Confirm New Password -->
                            <div class="space-y-4 mb-6">
                                <div class="flex justify-between items-center flex gap-6">
                                    <label for="confirm-password" class="text-gray-600 whitespace-nowrap w-80">Confirm New Password</label>
                                    <div class="w-full">
                                        <input type="password" id="confirm-password" name="confirm_password" class="w-full  p-2 border border-gray-300 rounded-md bg-transparent" required minlength="8">
                                    </div>
                                </div>
                            </div>

                            <!-- Error Messages -->
                            <div id="error-message" class="text-red-600 hidden mb-4"></div>

                            <!-- Submit Button -->
                            <div class="mt-6 flex justify-center">
                                <button type="submit" class="py-2 px-4 bg-gray-900 text-white font-semibold rounded-md hover:bg-gray-700">
                                    Change Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        {{-- Two-factor authentication (2FA) --}}
        <div class="panel box-border personal-info-panel mb-3">
            <div class="panel-heading bg-gray-100 dark:bg-gray-700 py-2 px-4 rounded-t-xl">
                <div class="panel-title text-lg font-semibold text-gray-800">Two-factor Authentication (2FA)</div>
            </div>
            <div class="panel-body">
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Enable Two-factor Authentication</h3>

                        @if (Auth::user()->google2fa_enabled)
                        <div class="text-green-500 mb-4">
                            <p>You have already enabled Two-factor Authentication.</p>
                            <form action="{{ route('profile.disable2fa') }}" method="POST" class="mt-4">
                                @csrf
                                <button type="submit" class="bg-red-500 text-white py-2 px-4 rounded-md hover:bg-red-600">
                                    Disable 2FA
                                </button>
                            </form>
                        </div>
                        @else
                        <p class="mb-4">To enable 2FA, scan the QR code below using the Google Authenticator app, then enter the generated code.</p>
                        <div class="flex justify-center mb-4">
                            <img src="{{ $google2fa_url }}" alt="QR Code" class="w-32 h-32">
                        </div>

                        <p class="text-gray-600 mb-4">
                            Scan the QR code above with your Google Authenticator app. Then, enter the 6-digit code below to verify.
                        </p>

                        <form id="verify-2fa-form">
                            @csrf
                            <div class="mb-4">
                                <label for="one_time_password" class="block text-gray-600">Enter the 6-digit code:</label>
                                <input type="text" id="one_time_password" name="one_time_password" class="w-64  p-2 border border-gray-300 rounded-md bg-transparent" required>
                            </div>

                            <div id="2fa-error-message" class="text-red-600 hidden text-sm mb-4"></div>

                            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">
                                Verify and Enable 2FA
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>


        <div class="panel box-border personal-info-panel mb-3">
            <div class="panel-heading">
                <div class="panel-title">Login history <small>Last 5</small></div>
            </div>
            <div class="panel-body text-white">
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto text-sm text-gray-900 dark:text-gray-100">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-left whitespace-nowrap">IP Address</th>
                                    <th class="px-4 py-2 text-left whitespace-nowrap">User Agent</th>
                                    <th class="px-4 py-2 text-left whitespace-nowrap">Login Time</th>
                                    <th class="px-4 py-2 text-left whitespace-nowrap">Logout Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($logins->take(5) as $login)
                                <tr class="border-b dark:border-gray-700">
                                    <td class="px-4 py-2">{{ $login['ip_address'] }}</td>
                                    <td class="px-4 py-2">{{ $login['user_agent'] }}</td>
                                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($login['login_at'])->format('Y-m-d H:i:s') }}</td>
                                    <td class="px-4 py-2">
                                        {{ $login['logout_at'] ? \Carbon\Carbon::parse($login['logout_at'])->format('Y-m-d H:i:s') : 'N/A' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel box-border personal-info-panel mb-3">
            <div class="panel-heading">
                <div class="panel-title">Active sessions</div>
            </div>
            <div class="panel-body text-white">
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto text-sm text-gray-900 dark:text-gray-100">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-2 text-left">Session ID</th>
                                    <th class="px-4 py-2 text-left">IP Address</th>
                                    <th class="px-4 py-2 text-left">Last Activity</th>
                                    <th class="px-4 py-2 text-left">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sessions as $session)
                                <tr class="border-b dark:border-gray-700">
                                    <td class="px-4 py-2">{{ $session->id }}</td>
                                    <td class="px-4 py-2">{{ $session->ip_address }}</td>
                                    <td class="px-4 py-2">{{ \Carbon\Carbon::createFromTimestamp($session->last_activity)->format('Y-m-d H:i:s') }}</td>
                                    <td class="px-4 py-2">
                                    @if ($session->id === Session::getId()) 
                                        <span class="bg-red-600 text-white rounded-xl py-1 px-4">
                                            Current Session
                                        </span>
                                    @else
                                        <form action="{{ route('sessions.logout', $session->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to logout this session?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                                                Logout
                                            </button>
                                        </form>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Handle Change Password Form Submission
        $('#change-password-form').on('submit', function(e) {
            e.preventDefault();

            var currentPassword = $('#current-password').val();
            var newPassword = $('#new-password').val();
            var confirmPassword = $('#confirm-password').val();

            // Clear error message
            $('#error-message').hide();

            // Validate if passwords match
            if (newPassword !== confirmPassword) {
                $('#error-message').text('New password and confirm password must match.').show();
                return;
            }

            // Make AJAX request for password change
            $.ajax({
                url: '{{ route("password.update") }}',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    current_password: currentPassword,
                    new_password: newPassword,
                    confirm_password: confirmPassword,
                },
                success: function(response) {
                    if (response.status === 'success') {
                        alert('Password changed successfully!');
                    } else if (response.status === 'error') {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    $('#error-message').text('An error occurred while changing the password. Please try again.').show();
                }
            });
        });

        // Handle 2FA Verification Form Submission
        $('#verify-2fa-form').on('submit', function(e) {
            e.preventDefault();

            var oneTimePassword = $('#one_time_password').val();

            // Clear error message
            $('#2fa-error-message').hide();

            // Make AJAX request for 2FA verification
            $.ajax({
                url: '{{ route("profile.verify2fa") }}',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    one_time_password: oneTimePassword
                },
                success: function(response) {
                    if (response.status === 'success') {
                        alert('2FA enabled successfully!');
                    } else if (response.status === 'error') {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    $('#2fa-error-message').text('Invalid 2FA code. Please try again.').show();
                }
            });
        });
    });
</script>
@endpush