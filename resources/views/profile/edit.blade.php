@extends('layouts.app')

@section('title', 'Profile')
@section('content')
    <div class="container py-4 mx-4">
        <!-- Trading Accounts Tabbed Section -->
        <div class="bg-[#293341] mb-4 p-6 rounded-lg shadow-md text-white">
            <div class="mb-4">
                <ul class="flex space-x-4 border-b border-gray-600 pb-2">
                    <li class="cursor-pointer text-sm py-2 px-4 hover:bg-gray-700 rounded-md" id="accountsTab"
                        onclick="openTab('accounts')">Trading Accounts</li>
                    <li class="cursor-pointer text-sm py-2 px-4 hover:bg-gray-700 rounded-md" id="personalTab"
                        onclick="openTab('personal')">Personal Information</li>
                    <li class="cursor-pointer text-sm py-2 px-4 hover:bg-gray-700 rounded-md" id="securityTab"
                        onclick="openTab('security')">Security Settings</li>
                </ul>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Profile Overview Card -->
            <div class="lg:flex items-center justify-betweenmb-4">
                <div class="bg-[#293341] p-6 rounded-lg shadow-md text-white">
                    <div class="relative inline-block mb-3 items-center">
                        <img src="{{ Auth::user()->profile_photo_url ?? asset('assets/images/user1.png') }}"
                            class="rounded-full w-28 h-28 mx-auto" id="blah">
                        <button class="absolute bottom-0 right-0 bg-blue-500 text-white rounded-full p-2 px-3"
                            id="profileImageButton">
                            <i class="fas fa-camera"></i>
                        </button>
                    </div>
                    <h4 class="text-xl font-semibold">{{ Auth::user()->name }}</h4>
                    <p class="text-gray-300">Member since {{ Auth::user()->created_at->format('j, M Y') }}</p>
                </div>

            </div>

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
                                    @foreach ($wallets['all_wallets'] as $wallet)
                                        <tr class="border-b">
                                            <td class="px-4 py-2">{{ ucwords(str_replace('_', ' ', $wallet->type)) }}</td>
                                            <td class="px-4 py-2">${{ number_format($wallet->balance, 2) }}</td>
                                            <td class="px-4 py-2">
                                                <span
                                                    class="inline-block px-3 py-1 rounded-full text-white bg-{{ $wallet->mode === 'live' ? 'green' : 'yellow' }}-500">
                                                    {{ ucfirst($wallet->mode) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-2">
                                                <button class="bg-blue-500 text-white text-sm py-1 px-3 rounded-md"
                                                    onclick="setActiveWallet('{{ $wallet->id }}')">
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
                                        <input type="text" class="w-full p-2 border border-gray-300 rounded-md"
                                            name="name" value="{{ Auth::user()->name }}">
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium mb-2">Email</label>
                                        <input type="email" class="w-full p-2 border border-gray-300 rounded-md"
                                            value="{{ Auth::user()->email }}" readonly>
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium mb-2">Phone</label>
                                        <input type="tel" class="w-full p-2 border border-gray-300 rounded-md"
                                            name="phone" value="{{ Auth::user()->phone }}">
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium mb-2">Country</label>
                                        <select class="w-full p-2 border border-gray-300 rounded-md" name="country">
                                            <option value="">Select Country</option>
                                            <!-- Add country options -->
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-md">Update
                                    Profile</button>
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
                                        <input class="form-checkbox" type="checkbox" id="2faToggle"
                                            {{ Auth::user()->two_factor_enabled ? 'checked' : '' }}>
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
                                        <input type="password" class="w-full p-2 border border-gray-300 rounded-md"
                                            name="current_password">
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium mb-2">New Password</label>
                                        <input type="password" class="w-full p-2 border border-gray-300 rounded-md"
                                            name="password">
                                    </div>
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium mb-2">Confirm New Password</label>
                                        <input type="password" class="w-full p-2 border border-gray-300 rounded-md"
                                            name="password_confirmation">
                                    </div>
                                    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-md">Update
                                        Password</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form action="{{ route('profile.photo.update') }}" method="POST" enctype="multipart/form-data" class="hidden">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block text-white text-sm font-medium mb-2">Choose Photo</label>
            <input type="file" id="imgInp"
                class="w-full p-2 text-white bg-gray-700 rounded-md border border-gray-600 focus:border-blue-500 focus:ring-blue-500"
                name="photo" accept="image/*">
        </div>
    </form>
@endsection

@push('js')
    <script>
        imgInp.onchange = evt => {
            const [file] = imgInp.files;
            if (file) {
                // Preview the image
                blah.src = URL.createObjectURL(file);

                // Create a FormData object to hold the file
                const formData = new FormData();
                formData.append('profile_image', file);

                // Send the file to the server
                fetch('{{ route("profile.photo.update") }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to upload the image');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Image uploaded successfully:', data);
                        toastr.success('Image uploaded successfully');
                    })
                    .catch(error => {
                        console.error('Error uploading image:', error);
                        toastr.error('Failed to upload the image');
                    });
            }
        };


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

        $("#profileImageButton").click(function() {
            $('#imgInp').click();
        })
    </script>
@endpush
