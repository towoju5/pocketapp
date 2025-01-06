@extends('layouts.app')

@section('title', 'Profile')
@section('content')
<div class="container py-4 mx-4">
        <!-- Trading Accounts Tabbed Section -->
        <div class="bg-[#293341] mb-4 p-6 rounded-lg shadow-md text-white">
            <div class="mb-4">
                <ul class="flex space-x-4 border-b border-gray-600 pb-2">
                    <li class="cursor-pointer text-sm py-2 px-4 hover:bg-gray-700 rounded-md" id="accountsTab" onclick="openTab('accounts')">Trading Accounts</li>
                    <li class="cursor-pointer text-sm py-2 px-4 hover:bg-gray-700 rounded-md" id="personalTab" onclick="openTab('personal')">Personal Information</li>
                    <li class="cursor-pointer text-sm py-2 px-4 hover:bg-gray-700 rounded-md" id="securityTab" onclick="openTab('security')">Security Settings</li>
                </ul>
            </div>
        </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Profile Overview Card -->
        <div class="lg:flex items-center justify-betweenmb-4">
            <div class="bg-[#293341] p-6 rounded-lg shadow-md text-white">
                <div class="relative inline-block mb-3">
                    <img src="{{ Auth::user()->profile_photo_url ?? asset('assets/images/user1.png') }}" class="rounded-full w-32 h-32 mx-auto">
                    <button class="absolute bottom-0 right-0 bg-blue-500 text-white rounded-full p-2" data-bs-toggle="modal" data-bs-target="#updatePhotoModal">
                        <i class="fas fa-camera"></i>
                    </button>
                </div>
                <h4 class="text-xl font-semibold">{{ Auth::user()->name }}</h4>
                <p class="text-gray-300">Member since {{ Auth::user()->created_at->format('j, M Y') }}</p>
            </div>

            <!-- Photo Update Modal -->
            <div x-show="photoModal" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-50 overflow-y-auto" 
                 style="display: none;">
                <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                    </div>
                    <div class="inline-block align-bottom bg-[#293341] rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex justify-between items-center pb-3">
                                <h3 class="text-lg font-medium text-white">Update Profile Photo</h3>
                                <button @click="photoModal = false" class="text-white hover:text-gray-300">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            <form action="{{ route('profile.photo.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="mb-4">
                                    <label class="block text-white text-sm font-medium mb-2">Choose Photo</label>
                                    <input type="file" class="w-full p-2 text-white bg-gray-700 rounded-md border border-gray-600 focus:border-blue-500 focus:ring-blue-500" name="photo" accept="image/*">
                                </div>
                                <div class="mt-5 sm:mt-6">
                                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-500 text-base font-medium text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                                        Upload Photo
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>        </div>

        <!-- Main Content Area -->
        <div class="md:col-span-2">
            <!-- Trading Accounts Tabbed Section -->
            <div class="bg-[#293341] mb-4 p-6 rounded-lg shadow-md text-white">

                <div id="accounts" class="tab-content">
                    <!-- Trading Accounts Content -->
                    <div class="overflow-x-auto mt-4">
                        <table class="min-w-full table-auto">
                            <thead>
                                <tr class="border-b">
                                    <th class="px-4 py-2">Account Type</th>
                                    <th class="px-4 py-2">Balance</th>
                                    <th class="px-4 py-2">Status</th>
                                    <th class="px-4 py-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($wallets['all_wallets'] as $wallet)
                                <tr class="border-b">
                                    <td class="px-4 py-2">{{ ucwords(str_replace('_', ' ', $wallet->type)) }}</td>
                                    <td class="px-4 py-2">${{ number_format($wallet->balance, 2) }}</td>
                                    <td class="px-4 py-2">
                                        <span class="inline-block px-3 py-1 rounded-full text-white bg-{{ $wallet->mode === 'live' ? 'green' : 'yellow' }}-500">
                                            {{ ucfirst($wallet->mode) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2">
                                        <button class="bg-blue-500 text-white text-sm py-1 px-3 rounded-md" onclick="setActiveWallet('{{ $wallet->id }}')">
                                            {{ $wallet->currently_active ? 'Active' : 'Set Active' }}
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="personal" class="tab-content hidden">
                    <!-- Personal Information Content -->
                    <div class="mt-4">
                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-2">Full Name</label>
                                    <input type="text" class="w-full p-2 border border-gray-300 rounded-md" name="name" value="{{ Auth::user()->name }}">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-2">Email</label>
                                    <input type="email" class="w-full p-2 border border-gray-300 rounded-md" value="{{ Auth::user()->email }}" readonly>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-2">Phone</label>
                                    <input type="tel" class="w-full p-2 border border-gray-300 rounded-md" name="phone" value="{{ Auth::user()->phone }}">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-2">Country</label>
                                    <select class="w-full p-2 border border-gray-300 rounded-md" name="country">
                                        <option value="">Select Country</option>
                                        <!-- Add country options -->
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-md">Update Profile</button>
                        </form>
                    </div>
                </div>

                <div id="security" class="tab-content hidden">
                    <!-- Security Settings Content -->
                    <div class="mt-4">
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-3">
                                <div>
                                    <h6 class="text-sm font-medium">Two-Factor Authentication</h6>
                                    <p class="text-gray-300 text-sm mb-0">Add additional security to your account</p>
                                </div>
                                <div>
                                    <input class="form-checkbox" type="checkbox" id="2faToggle" {{ Auth::user()->two_factor_enabled ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h6 class="text-sm font-medium mb-2">Change Password</h6>
                            <form action="{{ route('password.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-2">Current Password</label>
                                    <input type="password" class="w-full p-2 border border-gray-300 rounded-md" name="current_password">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-2">New Password</label>
                                    <input type="password" class="w-full p-2 border border-gray-300 rounded-md" name="password">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-2">Confirm New Password</label>
                                    <input type="password" class="w-full p-2 border border-gray-300 rounded-md" name="password_confirmation">
                                </div>
                                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-md">Update Password</button>
                            </form>
                        </div>
                    </div>
                </div>
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

function openTab(tabName) {
    const tabs = document.querySelectorAll('.tab-content');
    tabs.forEach(tab => tab.classList.add('hidden'));
    
    const activeTab = document.getElementById(tabName);
    if (activeTab) {
        activeTab.classList.remove('hidden');
    }

    const tabLinks = document.querySelectorAll('ul li');
    tabLinks.forEach(link => link.classList.remove('bg-gray-700', 'text-white'));
    const activeTabLink = document.getElementById(`${tabName}Tab`);
    if (activeTabLink) {
        activeTabLink.classList.add('bg-gray-700', 'text-white');
    }
}
</script>
@endpush
