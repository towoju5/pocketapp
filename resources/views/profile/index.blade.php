@extends('layouts.desktop.trading')

@section('title', 'Profile')

@section('content')
@php
    $activeTab = in_array($tab, ['account', 'verification', 'security', 'preferences', 'trading', 'loyalty']) ? $tab : 'account';
    $kyc = $user->kyc;
    $settings = [
        'email_notifications' => 'Email notifications',
        'manager_updates' => 'Subscribed to updates from your Manager',
        'company_news' => "Subscribed to Company's news",
        'company_promotions' => "Subscribed to Company's promotions",
        'trading_analytics' => "Subscribed to Company's Trading Analytics",
        'trading_statements' => 'Subscribed to trading statements',
        'education_emails' => 'Subscribed to Education Emails',
        'sound_notifications' => 'Sound notifications',
    ];
    $languages = ['en' => 'English', 'ru' => 'Русский', 'pt' => 'Português', 'es' => 'Español', 'fr' => 'Français'];
@endphp

<div class="flex-1 overflow-y-auto overflow-x-hidden p-4 sm:p-6">
    <div class="mx-auto min-w-0">

        <div class="flex items-center gap-4 mb-6 bg-[#171e33] border border-[#2a3350] rounded-xl p-5">
            @if($user->avatar)
                <img src="{{ $user->avatar }}" alt="Avatar" class="w-16 h-16 rounded-full object-cover flex-shrink-0 bg-[#33406b]">
            @else
                <div class="w-16 h-16 rounded-full bg-[#33406b] flex items-center justify-center text-white text-xl font-bold flex-shrink-0">
                    {{ strtoupper(substr($user->first_name ?? $user->username ?? 'U', 0, 1)) }}
                </div>
            @endif
            <div class="flex-1 min-w-0">
                <div class="text-lg font-bold text-white truncate">{{ trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: $user->username }}</div>
                <div class="text-sm text-[#7c86a3] truncate">{{ $user->email }}</div>
                <div class="text-xs text-[#7c86a3]">Member since {{ $user->created_at->format('M Y') }}</div>
            </div>
            <span class="px-3 py-1.5 rounded-lg text-xs font-bold bg-[#4f8ef7]/15 text-[#4f8ef7] flex-shrink-0">{{ $loyalty['tier'] }}</span>
        </div>

        <div class="flex flex-wrap gap-2 mb-6">
            @foreach(['account' => 'Account', 'verification' => 'Verification', 'security' => 'Security', 'preferences' => 'Preferences', 'trading' => 'Trading Stats', 'loyalty' => 'Loyalty'] as $key => $label)
                <button type="button" class="profile-tab-btn {{ $activeTab === $key ? 'profile-tab-btn--active' : '' }}" data-tab="{{ $key }}">{{ $label }}</button>
            @endforeach
        </div>

        {{-- Account --}}
        <div class="profile-tab-panel {{ $activeTab === 'account' ? '' : 'hidden' }}" data-panel="account">
            <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6 mb-4">
                <h3 class="text-white font-semibold mb-4">Avatar</h3>
                <form action="{{ route('profile.photo.update') }}" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row sm:items-center gap-3">
                    @csrf
                    @method('PATCH')
                    <input type="file" name="avatar" accept="image/*" class="w-full sm:w-auto min-w-0 text-sm text-[#d7dcea] file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-[#1c243c] file:text-[#d7dcea] file:text-sm">
                    <button type="submit" class="bg-[#4f8ef7] text-white text-sm font-semibold px-4 py-2 rounded-lg w-full sm:w-auto flex-shrink-0">Upload</button>
                </form>
            </div>

            <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6">
                <h3 class="text-white font-semibold mb-4">Account info</h3>
                <form method="POST" action="{{ route('profile.update') }}" class="grid grid-cols-1 sm:grid-cols-2 gap-4 min-w-0">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label class="block text-xs text-[#7c86a3] mb-1">First name</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg p-2.5 text-white">
                    </div>
                    <div>
                        <label class="block text-xs text-[#7c86a3] mb-1">Last name</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg p-2.5 text-white">
                    </div>
                    <div>
                        <label class="block text-xs text-[#7c86a3] mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg p-2.5 text-white">
                    </div>
                    <div>
                        <label class="block text-xs text-[#7c86a3] mb-1">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg p-2.5 text-white">
                    </div>
                    <div>
                        <label class="block text-xs text-[#7c86a3] mb-1">Date of birth</label>
                        <input type="date" name="birthday" value="{{ old('birthday', $user->birthday) }}" class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg p-2.5 text-white">
                    </div>
                    <div class="col-span-2">
                        <button type="submit" class="bg-[#4f8ef7] text-white text-sm font-semibold px-5 py-2.5 rounded-lg">Save changes</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Verification --}}
        <div class="profile-tab-panel {{ $activeTab === 'verification' ? '' : 'hidden' }}" data-panel="verification">
            <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6">
                <h3 class="text-white font-semibold mb-2">Identity verification</h3>
                @php $status = $kyc->status ?? 'unverified'; @endphp
                <span class="inline-block px-3 py-1 rounded-lg text-xs font-bold mb-4
                    {{ $status === 'verified' ? 'bg-[#16c087]/15 text-[#16c087]' : ($status === 'pending' ? 'bg-[#d97706]/15 text-[#d97706]' : ($status === 'rejected' ? 'bg-[#f4534a]/15 text-[#f4534a]' : 'bg-[#7c86a3]/15 text-[#7c86a3]')) }}">
                    {{ ucfirst($status) }}
                </span>

                @if($status === 'verified')
                    <p class="text-sm text-[#7c86a3]">Your identity has been verified.</p>
                @elseif($status === 'pending')
                    <p class="text-sm text-[#7c86a3]">Your documents are under review{{ $kyc->submitted_at ? ' since ' . $kyc->submitted_at->diffForHumans() : '' }}.</p>
                @else
                    @if($status === 'rejected' && $kyc->rejection_reason)
                        <p class="text-sm text-[#f4534a] mb-3">{{ $kyc->rejection_reason }}</p>
                    @endif
                    <a href="{{ route('kyc.create') }}" class="inline-block bg-[#4f8ef7] text-white text-sm font-semibold px-5 py-2.5 rounded-lg">
                        {{ $status === 'rejected' ? 'Resubmit documents' : 'Verify identity' }}
                    </a>
                @endif
            </div>
        </div>

        {{-- Security --}}
        <div class="profile-tab-panel {{ $activeTab === 'security' ? '' : 'hidden' }}" data-panel="security">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
                <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6">
                    <h3 class="text-white font-semibold mb-4">Change password</h3>
                    <form id="change-password-form">
                        @csrf
                        <input type="password" id="current-password" name="current_password" placeholder="Current password" class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg p-2.5 text-white mb-3" required>
                        <input type="password" id="new-password" name="new_password" placeholder="New password" class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg p-2.5 text-white mb-3" required minlength="8">
                        <input type="password" id="confirm-password" name="confirm_password" placeholder="Confirm new password" class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg p-2.5 text-white mb-3" required minlength="8">
                        <div id="password-error" class="text-[#f4534a] text-xs hidden mb-3"></div>
                        <button type="submit" class="bg-[#4f8ef7] text-white text-sm font-semibold px-5 py-2.5 rounded-lg">Change password</button>
                    </form>
                </div>

                <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6">
                    <h3 class="text-white font-semibold mb-4">Two-factor authentication</h3>
                    @if($user->google2fa_enabled)
                        <p class="text-[#16c087] text-sm mb-4">2FA is enabled on your account.</p>
                        <form action="{{ route('profile.disable2fa') }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-[#f4534a] text-white text-sm font-semibold px-5 py-2.5 rounded-lg">Disable 2FA</button>
                        </form>
                    @else
                        <p class="text-xs text-[#7c86a3] mb-3">Scan with Google Authenticator, then enter the code.</p>
                        <img src="{{ $google2fa_url }}" alt="QR code" class="w-32 h-32 mb-3 rounded-lg bg-white p-1">
                        <form id="verify-2fa-form">
                            @csrf
                            <input type="text" id="one_time_password" name="one_time_password" placeholder="6-digit code" class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg p-2.5 text-white mb-3" required>
                            <div id="twofa-error" class="text-[#f4534a] text-xs hidden mb-3"></div>
                            <button type="submit" class="bg-[#4f8ef7] text-white text-sm font-semibold px-5 py-2.5 rounded-lg">Verify and enable</button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6 mb-4">
                <h3 class="text-white font-semibold mb-4">Login history <span class="text-xs text-[#7c86a3] font-normal">(last 5)</span></h3>
                <div class="responsive-table">
                <table class="w-full text-sm text-left">
                    <thead class="text-[#7c86a3] text-xs uppercase">
                        <tr><th class="py-2">IP address</th><th class="py-2">Device</th><th class="py-2">Login</th><th class="py-2">Logout</th></tr>
                    </thead>
                    <tbody class="text-[#d7dcea]">
                        @foreach($logins->take(5) as $login)
                            <tr class="border-t border-[#1c243c]">
                                <td class="py-2" data-label="IP address">{{ $login->ip_address }}</td>
                                <td class="py-2 truncate max-w-[200px]" data-label="Device">{{ $login->user_agent }}</td>
                                <td class="py-2" data-label="Login">{{ optional($login->login_at)->format('Y-m-d H:i') }}</td>
                                <td class="py-2" data-label="Logout">{{ $login->logout_at ? $login->logout_at->format('Y-m-d H:i') : '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>

            <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6">
                <h3 class="text-white font-semibold mb-4">Active sessions</h3>
                <div class="responsive-table">
                <table class="w-full text-sm text-left">
                    <thead class="text-[#7c86a3] text-xs uppercase">
                        <tr><th class="py-2">IP address</th><th class="py-2">Last activity</th><th class="py-2"></th></tr>
                    </thead>
                    <tbody class="text-[#d7dcea]">
                        @foreach($sessions as $session)
                            <tr class="border-t border-[#1c243c]">
                                <td class="py-2" data-label="IP address">{{ $session->ip_address }}</td>
                                <td class="py-2" data-label="Last activity">{{ \Carbon\Carbon::createFromTimestamp($session->last_activity)->format('Y-m-d H:i') }}</td>
                                <td class="py-2" data-label="Action">
                                    @if($session->id === \Illuminate\Support\Facades\Session::getId())
                                        <span class="text-xs text-[#16c087] font-semibold">Current session</span>
                                    @else
                                        <form action="{{ route('sessions.logout', $session->id) }}" method="POST" onsubmit="return confirm('Logout this session?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-[#f4534a] font-semibold">Logout</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>

        {{-- Preferences --}}
        <div class="profile-tab-panel {{ $activeTab === 'preferences' ? '' : 'hidden' }}" data-panel="preferences">
            <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6 mb-4">
                <h3 class="text-white font-semibold mb-4">Notifications</h3>
                @foreach($settings as $key => $label)
                    @php $isChecked = !empty($user->config[$key]); @endphp
                    <div class="flex items-center justify-between py-2 border-t border-[#1c243c] first:border-t-0">
                        <span class="text-sm text-[#d7dcea]">{{ $label }}</span>
                        <button type="button" class="toggle preference-toggle {{ $isChecked ? 'toggle--on' : '' }}" data-key="{{ $key }}"></button>
                    </div>
                @endforeach
            </div>

            <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6">
                <h3 class="text-white font-semibold mb-4">Language</h3>
                <select id="language-select" class="bg-[#1c243c] border border-[#2a3350] rounded-lg p-2.5 text-white">
                    @foreach($languages as $code => $name)
                        <option value="{{ $code }}" @selected(($user->config['default_language'] ?? 'en') === $code)>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Trading Stats --}}
        <div class="profile-tab-panel {{ $activeTab === 'trading' ? '' : 'hidden' }}" data-panel="trading">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-4">
                <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-4"><div class="text-xs text-[#7c86a3]">Trades</div><div class="text-lg font-bold text-white">{{ $totalTrades }}</div></div>
                <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-4"><div class="text-xs text-[#7c86a3]">Profitable trades</div><div class="text-lg font-bold text-white">{{ number_format($profitableTradesPercentage, 1) }}%</div></div>
                <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-4"><div class="text-xs text-[#7c86a3]">Trading turnover</div><div class="text-lg font-bold text-white">${{ number_format($tradingTurnover, 2) }}</div></div>
                <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-4"><div class="text-xs text-[#7c86a3]">Trading profit</div><div class="text-lg font-bold text-white">${{ number_format($tradingProfit, 2) }}</div></div>
                <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-4"><div class="text-xs text-[#7c86a3]">Max. trade</div><div class="text-lg font-bold text-white">${{ number_format($maxTrade, 2) }}</div></div>
                <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-4"><div class="text-xs text-[#7c86a3]">Max. profit</div><div class="text-lg font-bold text-white">${{ number_format($maxProfit, 2) }}</div></div>
            </div>
            <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6 mb-4">
                <h3 class="text-white font-semibold mb-4">Won vs Lost Trades</h3>
                <canvas id="profitabilityChart"></canvas>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6"><canvas id="tradeAmountsChart"></canvas></div>
                <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6"><canvas id="tradesDistributionChart"></canvas></div>
            </div>
        </div>

        {{-- Loyalty --}}
        <div class="profile-tab-panel {{ $activeTab === 'loyalty' ? '' : 'hidden' }}" data-panel="loyalty">
            <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-white font-semibold">Current tier: {{ $loyalty['tier'] }}</h3>
                    <span class="text-sm text-[#7c86a3]">Total deposited: ${{ number_format($loyalty['totalDeposited'], 2) }}</span>
                </div>
                @if($loyalty['nextTier'])
                    <div class="w-full bg-[#1c243c] rounded-full h-2 mb-2">
                        <div class="bg-[#4f8ef7] h-2 rounded-full" style="width: {{ $loyalty['progressToNextTier'] }}%"></div>
                    </div>
                    <p class="text-xs text-[#7c86a3]">
                        ${{ number_format(max(0, $loyalty['nextTierThreshold'] - $loyalty['totalDeposited']), 2) }} more to reach {{ $loyalty['nextTier'] }}
                    </p>
                @else
                    <p class="text-xs text-[#7c86a3]">You've reached the highest tier.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.profile-tab-btn { background:#1c243c; border:1px solid #2a3350; color:#7c86a3; font-size:13px; font-weight:600; padding:8px 14px; border-radius:8px; cursor:pointer; }
.profile-tab-btn--active { background:rgba(79,142,247,0.15); color:#4f8ef7; border-color:#4f8ef7; }

/* .toggle/.toggle--on had no visual styling at all — the click handler
   worked and persisted the change, but the button rendered as an
   almost-invisible default browser button, so toggling looked broken. */
.toggle { position:relative; width:40px; height:22px; flex-shrink:0; border:none; border-radius:999px; background:#2a3350; cursor:pointer; padding:0; transition:background .15s ease; }
.toggle::after { content:''; position:absolute; top:2px; left:2px; width:18px; height:18px; border-radius:50%; background:#7c86a3; transition:transform .15s ease, background .15s ease; }
.toggle.toggle--on { background:#4f8ef7; }
.toggle.toggle--on::after { transform:translateX(18px); background:#fff; }
</style>

@push('js')
<script src="//cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.querySelectorAll('.profile-tab-btn').forEach((btn) => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.profile-tab-btn').forEach((b) => b.classList.remove('profile-tab-btn--active'));
            btn.classList.add('profile-tab-btn--active');
            document.querySelectorAll('.profile-tab-panel').forEach((p) => p.classList.toggle('hidden', p.dataset.panel !== btn.dataset.tab));
            history.replaceState(null, '', '?tab=' + btn.dataset.tab);
        });
    });

    document.querySelectorAll('.preference-toggle').forEach((btn) => {
        btn.addEventListener('click', () => {
            const willBeOn = !btn.classList.contains('toggle--on');
            fetch('{{ route('profile.update.pk') }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ name: btn.dataset.key, value: willBeOn ? '1' : '' }),
            })
                .then((res) => {
                    if (!res.ok) throw new Error('Request failed');
                    return res.json();
                })
                .then(() => btn.classList.toggle('toggle--on', willBeOn))
                .catch(() => toastr.error('Failed to update preference.'));
        });
    });

    document.getElementById('language-select')?.addEventListener('change', function () {
        fetch('{{ route('profile.update.pk') }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ name: 'default_language', value: this.value }),
        }).then(() => toastr.success('Language updated.'));
    });

    $(document).ready(function () {
        $('#change-password-form').on('submit', function (e) {
            e.preventDefault();
            const newPassword = $('#new-password').val();
            const confirmPassword = $('#confirm-password').val();
            $('#password-error').hide();

            if (newPassword !== confirmPassword) {
                $('#password-error').text('New password and confirmation must match.').show();
                return;
            }

            $.ajax({
                url: '{{ route("password.update") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    current_password: $('#current-password').val(),
                    new_password: newPassword,
                    new_password_confirmation: confirmPassword,
                },
                success: function (response) {
                    if (response.status === 'success') {
                        toastr.success('Password changed successfully!');
                        this.reset?.();
                    } else {
                        $('#password-error').text(response.message).show();
                    }
                },
                error: function () {
                    $('#password-error').text('An error occurred while changing the password.').show();
                },
            });
        });

        $('#verify-2fa-form').on('submit', function (e) {
            e.preventDefault();
            $('#twofa-error').hide();

            $.ajax({
                url: '{{ route("profile.verify2fa") }}',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}', one_time_password: $('#one_time_password').val() },
                success: function (response) {
                    if (response.status === 'success') {
                        toastr.success('2FA enabled successfully!');
                        setTimeout(() => window.location.reload(), 1000);
                    } else {
                        $('#twofa-error').text(response.message).show();
                    }
                },
                error: function () {
                    $('#twofa-error').text('Invalid 2FA code. Please try again.').show();
                },
            });
        });
    });

    const profitabilityLabels = @json($profitabilityLabels);
    const wonByDate = @json($wonByDate);
    const lostByDate = @json($lostByDate);
    const tradeAmountsByAssets = @json($tradeAmountsByAssets);
    const tradesDistributionByAssets = @json($tradesDistributionByAssets);

    new Chart(document.getElementById('profitabilityChart'), {
        type: 'bar',
        data: {
            labels: profitabilityLabels,
            datasets: [
                { label: 'Won', data: wonByDate, backgroundColor: 'rgba(22,192,135,0.7)', borderColor: '#16c087', borderWidth: 1 },
                { label: 'Lost', data: lostByDate, backgroundColor: 'rgba(244,83,74,0.7)', borderColor: '#f4534a', borderWidth: 1 },
            ],
        },
        options: {
            responsive: true,
            scales: {
                x: { ticks: { color: '#9ca3af' }, grid: { color: 'rgba(255,255,255,0.05)' } },
                y: { ticks: { color: '#9ca3af', precision: 0 }, beginAtZero: true, grid: { color: 'rgba(255,255,255,0.05)' } },
            },
            plugins: { legend: { labels: { color: '#e5e7eb' } } },
        },
    });
    new Chart(document.getElementById('tradeAmountsChart'), {
        type: 'bar',
        data: { labels: Object.keys(tradeAmountsByAssets), datasets: [{ label: 'Trade amounts', data: Object.values(tradeAmountsByAssets), backgroundColor: 'rgba(153,102,255,0.6)' }] },
    });
    new Chart(document.getElementById('tradesDistributionChart'), {
        type: 'pie',
        data: { labels: Object.keys(tradesDistributionByAssets), datasets: [{ label: 'Trades distribution', data: Object.values(tradesDistributionByAssets), backgroundColor: ['#f45362','#4f8ef7','#facc15','#16c087','#a855f7'] }] },
    });
</script>
@endpush
@endsection
