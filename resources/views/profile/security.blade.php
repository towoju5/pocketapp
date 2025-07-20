@extends('layouts.app')

@section('title', 'Security - Polaris Option')
@section('content')
<div class="m-4 py-4" style="margin: 1rem">
    @if(!is_mobile())
    @include('partials.profile')
    @endif

    <div class="lg:grid lg:grid-cols-2 gap-4">
        <!-- Change Password Section -->
        <div class="panel box-border personal-info-panel mb-3">
            <div class="panel-heading bg-gray-100 dark:bg-gray-700 py-2 px-4 rounded-t-xl">
                <div class="panel-title text-lg font-semibold text-gray-100">Change Password</div>
            </div>
            <div class="bg-[#1b1e35] border border-[#292d4a] text-white p-2 rounded-b-xl shadow-lg">
                <div class="shadow-md rounded-lg overflow-hidden">
                    <div class="overflow-x-auto text-gray-700 p-6 mx-auto max-w-xl">
                        <form id="change-password-form">
                            @csrf

                            <!-- Current Password -->
                            <div class="space-y-4 mb-6">
                                <div class="lg:flex justify-between items-center gap-6">
                                    <label for="current-password" class="text-gray-200 whitespace-nowrap w-80">Current Password</label>
                                    <div class="w-full">
                                        <input type="password" id="current-password" name="current_password" class="w-full  p-2 border border-gray-300 rounded-md bg-transparent" required>
                                    </div>
                                </div>
                            </div>

                            <!-- New Password -->
                            <div class="space-y-4 mb-6">
                                <div class="lg:flex justify-between items-center gap-6">
                                    <label for="new-password" class="text-gray-200 whitespace-nowrap w-80">New Password</label>
                                    <div class="w-full">
                                        <input type="password" id="new-password" name="new_password" class="w-full p-2  border border-gray-300 rounded-md bg-transparent" required minlength="8">
                                    </div>
                                </div>
                            </div>

                            <!-- Confirm New Password -->
                            <div class="space-y-4 mb-6">
                                <div class="lg:flex justify-between items-center gap-6">
                                    <label for="confirm-password" class="text-gray-200 whitespace-nowrap w-80">Confirm New Password</label>
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
                <div class="panel-title text-lg font-semibold text-white">Two-factor Authentication (2FA)</div>
            </div>
            <div class="bg-[#1b1e35] border border-[#292d4a] text-white p-2 rounded-b-xl shadow-lg">
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-300 mb-4">Enable Two-factor Authentication</h3>

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
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=400x400&data={{ $google2fa_url }}" alt="QR Code" class="w-32 h-32">
                    </div>

                    <p class="text-gray-300 mb-4">
                        Scan the QR code above with your Google Authenticator app. Then, enter the 6-digit code below to verify.
                    </p>

                    <form id="verify-2fa-form">
                        @csrf
                        <div class="mb-4">
                            <label for="one_time_password" class="block text-gray-300">Enter the 6-digit code:</label>
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

        <div class="panel box-border personal-info-panel mb-3">
            <div class="panel-heading bg-gray-100 dark:bg-gray-700 py-2 px-4 rounded-t-xl">
                <div class="panel-title text-lg font-semibold text-gray-100">Login history <small>Last 5</small></div>
            </div>
            <div class="panel-body text-white">
                @if(is_mobile())
                <div class="w-full  border border-[#292d4a] rounded-b-lg px-2">
                    @foreach($logins->take(5) as $login)
                    <div class="bg-[#1b1e35] border border-[#292d4a] text-white p-3 rounded-xl shadow-lg my-3 mx-2">
                        <div class="p-2 text-sm">
                            <div class="flex items-center gap-5 border-b border-[#292d4a] pb-2">
                                <span>
                                    <svg class="svg-icon info-icon" width="15" height="15" viewBox="0 0 12 12" fill="gray" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M6 0C2.68594 0 0 2.68594 0 6C0 9.31406 2.68594 12 6 12C9.31406 12 12 9.31406 12 6C12 2.68594 9.31406 0 6 0ZM6 3C6.41414 3 6.75 3.33586 6.75 3.75C6.75 4.16414 6.41414 4.5 6 4.5C5.58586 4.5 5.25 4.16484 5.25 3.75C5.25 3.33516 5.58516 3 6 3ZM6.9375 9H5.0625C4.75313 9 4.5 8.74922 4.5 8.4375C4.5 8.12578 4.75195 7.875 5.0625 7.875H5.4375V6.375H5.25C4.93945 6.375 4.6875 6.12305 4.6875 5.8125C4.6875 5.50195 4.94063 5.25 5.25 5.25H6C6.31055 5.25 6.5625 5.50195 6.5625 5.8125V7.875H6.9375C7.24805 7.875 7.5 8.12695 7.5 8.4375C7.5 8.74805 7.24922 9 6.9375 9Z"></path>
                                    </svg>
                                </span>
                                <span class="font-medium pt-1">Login Record</span>
                            </div>

                            <div class="flex items-start gap-5 border-b border-[#292d4a] py-2">
                                <span class="text-gray-400 whitespace-nowrap">IP Address:</span>
                                <span class="font-medium break-all">{{ $login['ip_address'] }}</span>
                            </div>

                            <div class="flex items-start gap-5 border-b border-[#292d4a] py-2">
                                <span class="text-gray-400 whitespace-nowrap">User Agent:</span>
                                <span class="font-medium break-words">{{ $login['user_agent'] }}</span>
                            </div>

                            <div class="flex items-center gap-5 border-b border-[#292d4a] py-2">
                                <span class="text-gray-400">Login Time:</span>
                                <span class="font-medium">
                                    {{ \Carbon\Carbon::parse($login['login_at'])->format('Y-m-d H:i:s') }}
                                </span>
                            </div>

                            <div class="flex items-center gap-5 pt-2">
                                <span class="text-gray-400">Logout Time:</span>
                                <span class="font-medium">
                                    {{ $login['logout_at'] ? \Carbon\Carbon::parse($login['logout_at'])->format('Y-m-d H:i:s') : 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else

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
                @endif
            </div>
        </div>

        <div class="panel box-border personal-info-panel mb-3">
            <div class="panel-heading bg-gray-100 dark:bg-gray-700 py-2 px-4 rounded-t-xl">
                <div class="panel-title text-lg font-semibold text-gray-100">Active sessions</div>
            </div>
            <div class="panel-body text-white">
            @if(is_mobile())
            <div class="w-full  border border-[#292d4a] rounded-b-lg px-2">
                @foreach($sessions as $session)
                <div class="bg-[#1b1e35] border border-[#292d4a] text-white p-3 rounded-xl shadow-lg space-y-4 my-3">
                    <div class="p-2 text-sm">
                        <div class="flex items-center gap-5 border-b border-[#292d4a] pb-2">
                            <span>
                                <svg class="svg-icon info-icon" width="15" height="15" viewBox="0 0 12 12" fill="gray" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6 0C2.68594 0 0 2.68594 0 6C0 9.31406 2.68594 12 6 12C9.31406 12 12 9.31406 12 6C12 2.68594 9.31406 0 6 0ZM6 3C6.41414 3 6.75 3.33586 6.75 3.75C6.75 4.16414 6.41414 4.5 6 4.5C5.58586 4.5 5.25 4.16484 5.25 3.75C5.25 3.33516 5.58516 3 6 3ZM6.9375 9H5.0625C4.75313 9 4.5 8.74922 4.5 8.4375C4.5 8.12578 4.75195 7.875 5.0625 7.875H5.4375V6.375H5.25C4.93945 6.375 4.6875 6.12305 4.6875 5.8125C4.6875 5.50195 4.94063 5.25 5.25 5.25H6C6.31055 5.25 6.5625 5.50195 6.5625 5.8125V7.875H6.9375C7.24805 7.875 7.5 8.12695 7.5 8.4375C7.5 8.74805 7.24922 9 6.9375 9Z"></path>
                                </svg>
                            </span>
                            <span class="font-medium pt-1 text-sm truncate">Session ID: {{ $session->id }}</span>
                        </div>

                        <div class="flex items-center gap-5 border-b border-[#292d4a] py-2">
                            <span class="text-gray-400">IP Address:</span>
                            <span class="font-medium break-all">{{ $session->ip_address }}</span>
                        </div>

                        <div class="flex items-center gap-5 border-b border-[#292d4a] py-2">
                            <span class="text-gray-400">Last Activity:</span>
                            <span class="font-medium">
                                {{ \Carbon\Carbon::createFromTimestamp($session->last_activity)->format('Y-m-d H:i:s') }}
                            </span>
                        </div>

                        <div class="flex items-center gap-5 pt-2">
                            <span class="text-gray-400">Action:</span>
                            <span class="font-medium">
                                @if ($session->id === Session::getId())
                                    <span class="bg-red-600 text-white rounded-xl py-1 px-4">Current Session</span>
                                @else
                                    <form action="{{ route('sessions.logout', $session->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to logout this session?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-4 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                            Logout
                                        </button>
                                    </form>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
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
            @endif
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