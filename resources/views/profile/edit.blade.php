@extends('layouts.app')

@section('title', 'Profile')
@section('content')
    <div class="lg:m-4 lg:container py-4">
        @if(!is_mobile())
            @include('partials.profile')
        @endif
       
        <div class="group">
            <!-- Identity Info -->
            <div class="panel box-border personal-info-panel bg-gray-800 rounded-lg p-4 mb-3">
                <h3 class="text-white text-lg font-semibold mb-3">Identity info</h3>
                <div class="flex flex-col gap-3 text-white">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <label class="w-full sm:w-1/3 text-sm font-medium">First name <span class="required-field text-red-500">*</span></label>
                    <div class="w-full sm:w-2/3">
                    <a href="#" class="editable-click block border border-gray-600 rounded px-3 py-2 text-white" data-name="first_name">{{ $user->first_name ?? 'Empty' }}</a>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <label class="w-full sm:w-1/3 text-sm font-medium">Last name <span class="required-field text-red-500">*</span></label>
                    <div class="w-full sm:w-2/3">
                    <a href="#" class="editable-click block border border-gray-600 rounded px-3 py-2 text-white" data-name="last_name">{{ $user->last_name ?? 'Empty' }}</a>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <label class="w-full sm:w-1/3 text-sm font-medium">Email</label>
                    <div class="w-full sm:w-2/3">
                    <strong class="block text-white mb-2">{{ $user->email }}</strong>
                    <div class="email_status_block-js">
                        @if(auth()->user()->email_verified_at == null)
                        <form method="POST" action="{{ route('verification.send') }}" class="inline-block">
                            @csrf
                            <button type="submit" class="bg-green-500 text-white text-xs px-3 py-1 rounded">Send verification</button>
                        </form>
                        @else
                        <span class="bg-green-500 text-white text-xs px-3 py-1 rounded inline-block">Verified</span>
                        @endif
                    </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <label class="w-full sm:w-1/3 text-sm font-medium">Phone</label>
                    <div class="w-full sm:w-2/3">
                    <a href="#" class="editable-click block border border-gray-600 rounded px-3 py-2 text-white" data-name="phone">{{ $user->phone ?? 'Empty' }}</a>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <label class="w-full sm:w-1/3 text-sm font-medium">Date of birth <span class="required-field text-red-500">*</span></label>
                    <div class="w-full sm:w-2/3">
                    <a href="#" class="editable-click block border border-gray-600 rounded px-3 py-2 text-white" data-name="birthday">{{ $user->birthday ?? 'Empty' }}</a>
                    </div>
                </div>
                </div>
                <div class="mt-3 text-sm">
                <span class="required-text__char text-red-500">*</span> Required data
                </div>
            </div>

            <!-- Social Trading -->
            <div class="panel box-border bg-gray-800 rounded-lg p-4 mb-3">
                <h3 class="text-white text-lg font-semibold mb-3">Social Trading</h3>
                <div class="flex flex-col gap-4 text-white">
                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                    <label class="w-full sm:w-1/3 text-sm font-medium">Avatar</label>
                    <div class="w-full sm:w-2/3 flex flex-col items-start">
                    <div class="relative mb-2">
                        <img class="w-20 h-20 rounded-full object-cover" src="//pocket-uploads.com/images/cabinet/no_avatar.png?v=1724660313&w=98" alt="">
                        <span class="absolute top-0 left-16 w-3.5 h-3.5 bg-green-400 border-2 border-white rounded-full"></span>
                    </div>
                    <form class="crop-avatar-form js-crop-avatar-form w-full" enctype="multipart/form-data" action="{{ route('profile.photo.update') }}" method="post">
                        <div class="mb-2">
                        <label class="block cursor-pointer">
                            <span class="inline-block bg-gray-700 text-white px-3 py-1 rounded text-sm">Choose File</span>
                            <input type="file" name="avatar" id="avatar" accept="image/*" class="hidden">
                        </label>
                        </div>
                        <div class="progress light w-full bg-gray-700 rounded-full overflow-hidden h-2 mb-2">
                        <div class="progress-bar progress-bar-success bg-green-500 h-full w-0"></div>
                        </div>
                        <div class="has-error mb-2">
                        <div class="upload_error text-red-500 text-sm"></div>
                        </div>
                        <div id="crop_wrap" class="mb-2"></div>
                        <div class="btn-wrap js-btn-wrap flex gap-2">
                        <button id="resize" type="button" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded text-sm">Save</button>
                        <a id="cancel" class="bg-red-600 hover:bg-red-700 text-white px-4 py-1 rounded text-sm cursor-pointer">Cancel</a>
                        </div>
                    </form>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <label class="w-full sm:w-1/3 text-sm font-medium">Hide my profile</label>
                    <div class="w-full sm:w-2/3 flex items-center">
                    <label class="inline-flex relative items-center cursor-pointer">
                        <input name="show_rating" type="checkbox" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-600 peer-checked:bg-blue-600 rounded-full peer transition-all duration-300"></div>
                    </label>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <label class="w-full sm:w-1/3 text-sm font-medium">Nickname</label>
                    <div class="w-full sm:w-2/3">
                    <a href="#" class="editable-click block border border-gray-600 rounded px-3 py-2 text-white" data-name="username">{{ $user->username ?? 'Nameless' }}</a>
                    </div>
                </div>
                </div>
            </div>

            <!-- Settings -->
            <div class="panel box-border bg-gray-800 rounded-lg p-4">
                <h3 class="text-white text-lg font-semibold mb-3">Settings</h3>
                <div class="flex flex-col gap-3 text-white">
                @php
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
                @endphp

                @foreach ($settings as $key => $label)
                    @php $isChecked = isset($user->config[$key]) && $user->config[$key]; @endphp
                    <div class="flex items-center justify-between">
                    <span class="text-sm">{{ $label }}</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer toggle-setting" data-key="{{ $key }}" {{ $isChecked ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-600 peer-checked:bg-blue-600 rounded-full peer transition-all duration-300"></div>
                    </label>
                    </div>
                @endforeach

                <!-- Language Selection -->
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium">Language</label>
                    <ul class="flex flex-wrap gap-2">
                    @php
                        $languages = [
                        'en' => 'English',
                        'ru' => 'Русский',
                        'pt' => 'Português',
                        'es' => 'Español',
                        'it' => 'Italiano',
                        'pl' => 'Polski',
                        'id' => 'Indonesian',
                        'fr' => 'Français',
                        'th' => 'Thai',
                        'zh' => '中文',
                        'ja' => '日本語',
                        'ko' => '한국어',
                        ];
                        $currentLanguage = $user->config['default_language'] ?? 'en';
                    @endphp
                    @foreach ($languages as $code => $name)
                        @php $isActive = $currentLanguage === $code; @endphp
                        <li>
                        <a href="#" class="change-language flex items-center gap-2 bg-gray-700 hover:bg-gray-600 px-3 py-1 rounded text-sm {{ $isActive ? 'bg-blue-600' : '' }}"
                            data-key="default_language" data-value="{{ $code }}">
                            {{ $name }}
                            @if ($isActive)
                            <svg class="w-4 h-4 languages-check-icon" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M4.296 2.457a6.667 6.667 0 1 1 7.408 11.086A6.667 6.667 0 0 1 4.296 2.457ZM9.813 5.86l-2.86 2.867-1.1-1.1a.666.666 0 1 0-.94.94L6.48 10.14a.667.667 0 0 0 .94 0l3.334-3.333a.666.666 0 0 0-.47-1.14.667.667 0 0 0-.47.193Z"
                                fill="currentColor"/>
                            </svg>
                            @endif
                        </a>
                        </li>
                    @endforeach
                    </ul>
                </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/jqueryui-editable/js/jqueryui-editable.min.js"></script>
    <script>
        $(document).ready(function () {
            // Set X-editable to inline mode
            $.fn.editable.defaults.mode = 'inline';

            const getCSRFToken = () => $('meta[name="csrf-token"]').attr('content');

            const postSettingUpdate = (url, name, value, onSuccess, onFail) => {
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _token: getCSRFToken(),
                        name: name,
                        value: value
                    },
                    success: function (response) {
                        if (response.success) {
                            onSuccess?.(response);
                        } else {
                            onFail?.(response);
                        }
                    },
                    error: function () {
                        onFail?.();
                    }
                });
            };

            // Initialize editable fields
            $('.editable-click').editable();

            // Handle toggle switches
            $('.toggle-setting').on('change', function () {
                const checkbox = $(this);
                const key = checkbox.data('key');
                const value = checkbox.is(':checked') ? 1 : 0;

                postSettingUpdate(
                    "{{ route('profile.update.pk') }}",
                    key,
                    value,
                    () => {
                        // Successfully updated, do nothing
                    },
                    () => {
                        // Revert checkbox if update fails
                        checkbox.prop('checked', !checkbox.is(':checked'));
                        alert('Failed to update setting. Please try again.');
                    }
                );
            });

            // Handle language selection and checkmark update
            $('.change-language').on('click', function (e) {
                e.preventDefault();
                const link = $(this);
                const key = link.data('key');
                const value = link.data('value');

                postSettingUpdate(
                    "{{ url('settings/update') }}",
                    key,
                    value,
                    () => {
                        // Remove active class from all
                        $('.profile__languages li').removeClass('active');
                        $('.languages-check-icon').remove();

                        // Mark selected language
                        const selectedLi = link.closest('li');
                        selectedLi.addClass('active');

                        // Append checkmark icon
                        link.append(`
                            <svg class="svg-icon languages-check-icon" width="16" height="16" viewBox="0 0 16 16" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M4.296 2.457a6.667 6.667 0 1 1 7.408 11.086A6.667 6.667 0 0 1 4.296 2.457ZM9.813 5.86l-2.86 2.867-1.1-1.1a.666.666 0 1 0-.94.94L6.48 10.14a.667.667 0 0 0 .94 0l3.334-3.333a.666.666 0 0 0-.47-1.14.667.667 0 0 0-.47.193Z"
                                    fill="currentColor"/>
                            </svg>
                        `);
                    },
                    () => {
                        alert('Failed to update language. Please try again.');
                    }
                );
            });
        });
    </script>
@endpush


@push('css')
    <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/jqueryui-editable/css/jqueryui-editable.css"
        rel="stylesheet" />
    <style>
        .table>tbody>tr>td,
        .theme-dark-blue .table>tbody>tr>th,
        .theme-dark-blue .table>tfoot>tr>td,
        .theme-dark-blue .table>tfoot>tr>th,
        .theme-dark-blue .table>thead>tr>td,
        .theme-dark-blue .table>thead>tr>th {
            border-color: #292d4a !important;
        }

        .profile .table td {
            vertical-align: middle;
        }

        .table>tbody>tr>td,
        .table>tbody>tr>th,
        .table>tfoot>tr>td,
        .table>tfoot>tr>th,
        .table>thead>tr>td,
        .table>thead>tr>th {
            padding: 10px 15px;
        }

        tr {
            display: table-row;
            vertical-align: inherit;
            unicode-bidi: isolate;
            border-color: inherit;
        }

        .profile__languages {
            display: grid;
            gap: 8px;
            grid-template-columns: repeat(auto-fill, 120px);
        }

        .profile__languages .active a {
            background-color: #314463;
            border: 1px solid #009af9;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2px 4px;
            border-radius: 4px;
        }

        .email_add_subscribes {
            padding: 1rem 10px;
        }

        .panel {
            border-radius: 10px;
            border: none;
        }

        .panel-body {
            background-color: #131628;
            border-radius: 10px;
            padding: 15px;
        }

        .panel-heading+.panel-body {
            border-top: none;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }

        .panel-heading {
            background-color: #20273f;
            color: #7e91a7;
            display: -webkit-box;
            display: flex;
            -webkit-box-align: center;
            align-items: center;
            -webkit-box-pack: justify;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            justify-content: space-between;
            padding: 10px 15px;
            border-bottom: 1px solid transparent;
        }

        /* Style the form container */
        /* Style the input field */

        .editable-container input {
            /* border: 2px solid #007bff; */
            border-radius: 4px;
            padding: 2px 8px;
            font-size: 14px;
            outline: none;
            transition: all 0.3s ease;
            background-color: transparent;
            border-radius: 4px;
            -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
            -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
            -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
            transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
        }

        .choose-file {
            display: -webkit-box;
            display: flex;
            position: relative;
            -webkit-box-align: center;
            align-items: center;
            -webkit-box-pack: center;
            cursor: pointer;
            font-size: 16px;
            justify-content: center;
            min-height: 100px;
            padding: 15px;
        }

        .profile .choose-file {
            background-color: rgba(48, 153, 245, .05);
            border: 1px dashed rgba(48, 153, 245, .96);
            -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .2), 0 0 8px rgba(48, 153, 245, .5);
            box-shadow: 0 1px 1px rgba(0, 0, 0, .2), 0 0 8px rgba(48, 153, 245, .5);
            color: #fff;
        }

        .choose-file [type=file] {
            cursor: pointer;
            font-size: 200px;
            height: 100%;
            left: 0;
            opacity: 0;
            overflow: hidden;
            position: absolute;
            top: 0;
            width: 100%;
        }

        .choose-file span {
            font-size: 14px;
            text-align: center;
        }

        /* CSS */
        input {
            padding-right: 24px;
            background-color: transparent !important;
            width: 100%;
            border-radius: .25rem;
            border-color: #4B5563;
            margin-bottom: 6px;
        }

    </style>
@endpush
