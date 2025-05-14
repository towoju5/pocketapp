@extends('layouts.app')

@section('title', 'Profile')
@section('content')
    <div class="m-4 ml-2 container py-4" style="margin: 1rem">
        @include('partials.profile')
        <div class="panel box-border personal-info-panel mb-3">
            <div class="panel-heading">
                <div class="panel-title">Identity info</div>
            </div>
            <div class="panel-body text-white">
                <table class="table w-full" style="margin-bottom: 0">
                    <colgroup>
                        <col style="width: 35%">
                        <col style="width: 65%">
                    </colgroup>
                    <tbody>
                        <tr>
                            <td style="border-top: none">
                                First name <span class="required-field"> *</span>
                            </td>
                            <td style="border-top: none">
                                <a href="#" class="editable-click" data-name="first_name" data-type="text"
                                    data-pk="1" data-url="{{ route('profile.update.pk') }}" data-title="Edit First Name">
                                    {!! $user->first_name ?? '<span class="text-red-600 italic">Empty</span>' !!}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>Last name <span class="required-field"> *</span></td>
                            <td>
                                <a href="#" class="editable-click" data-name="last_name" data-type="text"
                                    data-pk="1" data-url="{{ route('profile.update.pk') }}" data-title="Edit Last Name">
                                    {!! $user->last_name ?? '<span class="text-red-600 italic">Empty</span>' !!}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td>
                                <span class="user_email"><strong>{{ $user->email }}</strong></span>
                                <span class="email_status_block-js">
                                    <span class="label label-success">Verified</span>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Phone</td>
                            <td>
                                <a href="#" class="editable-click" data-name="phone" data-type="text" data-pk="1"
                                    data-url="{{ route('profile.update.pk') }}" data-title="Edit Phone">
                                    {!! $user->phone ?? '<span class="text-red-600 italic">Empty</span>' !!}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>Date of birth <span class="required-field"> *</span></td>
                            <td>
                                <a href="#" class="editable-click" data-name="birthday" data-type="date"
                                    data-pk="1" data-url="{{ route('profile.update.pk') }}"
                                    data-title="Edit Date of Birth">
                                    {!! $user->birthday ?? '<span class="text-red-600 italic">Empty</span>' !!}
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="required-text">
                    <span class="required-text__char">*</span> Required data
                </div>
            </div>
        </div>

        <div class="panel box-border mb-3">
            <div class="panel-heading">
                <div class="panel-title">Social Trading</div>
            </div>
            <div class="panel-body" style="display: block;">
                <div class="">
                    <table class="table w-full text-white" style="margin-bottom: 0">
                        <colgroup>
                            <col style="width: 30%">
                            <col style="width: 70%">
                        </colgroup>
                        <tbody>
                            <tr>
                                <td style="border-top: none">Avatar</td>
                                <td style="border-top: none">
                                    <div class="flex avatar-block">
                                        <div
                                            class="relative inline-block  border border-yellow-500 text-yellow-500 p-1 rounded-full">
                                            <img class="inline-block h-32 w-32 rounded-full"
                                                src="//images.unsplash.com/photo-1568602471122-7832951cc4c5?ixlib=rb-4.0.3&amp;ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=300&amp;h=300&amp;q=80"
                                                alt="Avatar">
                                            <span class="absolute top-0 end-0 block size-8">
                                                <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <g clip-path="url(#clip0_56_499)">
                                                        <path
                                                            d="M4.82139 23.8988L0.685152 19.8821C0.902685 20.0931 1.17616 20.2701 1.50246 20.3911L5.6387 24.4079C5.3124 24.2868 5.04204 24.1099 4.82139 23.8988Z"
                                                            fill="black"></path>
                                                        <path
                                                            d="M1.49928 20.3941L5.63553 24.4109C5.30923 24.2898 5.03886 24.1129 4.81822 23.9018L0.68198 19.8851C0.899514 20.0961 1.17298 20.2731 1.49928 20.3941Z"
                                                            fill="#1A5922"></path>
                                                        <path
                                                            d="M20.9189 0.646409L25.0551 4.66317C25.3286 4.92702 25.5399 5.27158 25.6642 5.69685L21.528 1.68009C21.4068 1.25482 21.1923 0.910261 20.9189 0.646409Z"
                                                            fill="black"></path>
                                                        <path
                                                            d="M20.9189 0.646409L25.0551 4.66317C25.3286 4.92702 25.5399 5.27158 25.6642 5.69685L21.528 1.68009C21.4068 1.25482 21.1923 0.910261 20.9189 0.646409Z"
                                                            fill="#1A5922"></path>
                                                        <path
                                                            d="M11.3847 24.1006L15.5241 28.1173L5.6387 24.4079L1.49928 20.3941L11.3847 24.1006Z"
                                                            fill="#1A5821"></path>
                                                        <path
                                                            d="M21.528 1.68009L25.6642 5.69685L28.604 15.8963L24.4647 11.8795L21.528 1.68009Z"
                                                            fill="#195621"></path>
                                                        <path
                                                            d="M23.5635 24.1005L27.6997 28.1173L23.2527 38.3144L19.1133 34.2977L23.5635 24.1005Z"
                                                            fill="#1A5A22"></path>
                                                        <path
                                                            d="M19.073 39.3512L14.9367 35.3345C15.3811 35.7659 15.9809 35.9832 16.5993 35.9832C17.5937 35.9832 18.6286 35.4214 19.1165 34.3008L23.2527 38.3144C22.7648 39.4381 21.73 40 20.7355 40C20.1202 40 19.5173 39.7827 19.073 39.3512Z"
                                                            fill="black"></path>
                                                        <path
                                                            d="M19.1133 34.2977L23.2527 38.3144C22.7648 39.4381 21.7268 39.9967 20.7324 39.9967C20.1171 39.9967 19.5142 39.7794 19.0698 39.3479L14.9336 35.3311C15.378 35.7626 15.9777 35.9799 16.5961 35.9799C17.5906 35.9799 18.6254 35.4182 19.1133 34.2977Z"
                                                            fill="#1A5922"></path>
                                                        <path
                                                            d="M33.9957 20.3942L38.132 24.411L27.6997 28.1173L23.5635 24.1005L33.9957 20.3942Z"
                                                            fill="#1B5C23"></path>
                                                        <path
                                                            d="M35.1704 16.0981L39.3067 20.1149C40.5466 21.3193 40.0805 23.7157 38.132 24.411L33.9957 20.3942C35.9473 19.6989 36.4104 17.3025 35.1704 16.0981Z"
                                                            fill="black"></path>
                                                        <path
                                                            d="M35.1704 16.0981L39.3067 20.1149C40.5466 21.3193 40.0805 23.7157 38.132 24.411L33.9957 20.3942C35.9473 19.6989 36.4104 17.3025 35.1704 16.0981Z"
                                                            fill="#1A5922"></path>
                                                        <path
                                                            d="M19.2563 0C20.2507 0 21.2048 0.559494 21.528 1.68009L24.4647 11.8795L34.35 15.5859C36.5253 16.4023 36.2892 19.5778 33.9957 20.3942L23.5603 24.0975L19.1133 34.2915C18.6254 35.4152 17.5906 35.9739 16.5962 35.9739C15.6017 35.9739 14.6508 35.4121 14.3245 34.2915L11.3847 24.1006L1.50246 20.3911C-0.669771 19.5748 -0.43981 16.3992 1.85361 15.5859L12.289 11.8796L16.7391 1.68245C17.227 0.56185 18.265 0 19.2563 0Z"
                                                            fill="#32AC41"></path>
                                                    </g>
                                                    <defs>
                                                        <clipPath id="clip0_56_499">
                                                            <rect width="40" height="40" fill="white"></rect>
                                                        </clipPath>
                                                    </defs>
                                                </svg>
                                            </span>
                                        </div>

                                        <form class="crop-avatar-form js-crop-avatar-form" enctype="multipart/form-data"
                                            action="{{ route('profile.photo.update') }}" method="post">
                                            <a id="avatar-choose" class="choose-file">
                                                <span>
                                                    Click or Drop image here </span>
                                                <input type="file" name="avatar" id="avatar" accept="image/*">
                                            </a>
                                            <div class="progress light">
                                                <div class="progress-bar progress-bar-success" style="width: 0;"></div>
                                            </div>
                                            <div class="has-error">
                                                <div class="upload_error control-label"></div>
                                            </div>
                                            <div id="crop_wrap"></div>
                                            <div class="btn-wrap js-btn-wrap">
                                                <div class="btn-wrap__in">
                                                    <button id="resize" type="button"
                                                        class="btn btn-primary">Save</button>
                                                    <a id="cancel" class="btn btn-danger-light">Cancel</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Hide my profile</td>
                                <td>
                                    <label
                                        class="mdl-switch mdl-js-switch mdl-js-ripple-effect js-mdl-switch mdl-js-ripple-effect--ignore-events is-upgraded"
                                        data-upgraded=",MaterialSwitch">
                                        <input name="show_rating" type="checkbox" class="mdl-switch__input">
                                        <span class="mdl-switch__label"></span>
                                        <div class="mdl-switch__track"></div>
                                        <div class="mdl-switch__thumb"><span class="mdl-switch__focus-helper"></span>
                                        </div><span
                                            class="mdl-switch__ripple-container mdl-js-ripple-effect mdl-ripple--center"><span
                                                class="mdl-ripple"></span></span>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td>Nickname</td>
                                <td><a class="editable-click editable"
                                        data-value="{{ $user->username ?? 'Usernameless' }}"
                                        value="{{ $user->username ?? 'Usernameless' }}"
                                        data-name="nickname">{{ $user->username ?? 'Usernameless' }}</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="panel box-border">
            <div class="panel-heading">
                <div class="panel-title">Settings</div>
            </div>
            <div class="panel-body" style="display: block;">
                <table class="table w-full text-gray-200" style="margin-bottom: 0">
                    <colgroup>
                        <col style="width: 30%">
                        <col style="width: 70%">
                    </colgroup>
                    <tbody>
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
                            <tr>
                                <td style="border-top: none">{{ $label }}</td>
                                <td style="border-top: none">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only peer toggle-setting"
                                            data-key="{{ $key }}" {{ $isChecked ? 'checked' : '' }}>
                                        <div
                                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                        </div>
                                    </label>
                                </td>
                            </tr>
                        @endforeach

                        <!-- Language Selection -->
                        <tr>
                            <td style="border-top: none">Language</td>
                            <td style="border-top: none">
                                <ul class="profile__languages">
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
                                        <li class="{{ $isActive ? 'active' : '' }}">
                                            <a href="#" class="change-language" data-key="default_language"
                                                data-value="{{ $code }}">
                                                {{ $name }}
                                                @if ($isActive)
                                                    <svg class="svg-icon languages-check-icon" width="16"
                                                        height="16" viewBox="0 0 16 16" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                            d="M4.296 2.457a6.667 6.667 0 1 1 7.408 11.086A6.667 6.667 0 0 1 4.296 2.457ZM9.813 5.86l-2.86 2.867-1.1-1.1a.666.666 0 1 0-.94.94L6.48 10.14a.667.667 0 0 0 .94 0l3.334-3.333a.666.666 0 0 0-.47-1.14.667.667 0 0 0-.47.193Z"
                                                            fill="currentColor"></path>
                                                    </svg>
                                                @endif
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection

@push('js')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/jqueryui-editable/js/jqueryui-editable.min.js"></script>
    <script>
        $(document).ready(function() {
            // Set X-editable to inline mode
            $.fn.editable.defaults.mode = 'inline';

            // Initialize editable elements
            $('.editable-click').editable();

            // Handle toggle switches
            $('.toggle-setting').on('change', function() {
                const checkbox = $(this);
                const key = checkbox.data('key');
                const value = checkbox.is(':checked') ? 1 : 0;

                $.ajax({
                    url: "{{ route('profile.update.pk') }}",
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        name: key,
                        value: value
                    },
                    success: function(response) {
                        if (response.status !== 200) {
                            // Revert the checkbox if the response is not successful
                            checkbox.prop('checked', !checkbox.is(':checked'));
                            alert('Failed to update setting. Please try again.');
                        }
                    },
                    error: function() {
                        // Revert the checkbox on error
                        checkbox.prop('checked', !checkbox.is(':checked'));
                        alert('An error occurred. Please try again.');
                    }
                });
            });

            // Handle language change
            $('.change-language').on('click', function(e) {
                e.preventDefault();
                const link = $(this);
                const key = link.data('key');
                const value = link.data('value');

                $.ajax({
                    url: '/settings/update',
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        name: key,
                        value: value
                    },
                    success: function(response) {
                        if (response.status === 200) {
                            // Highlight the selected language
                            $('.profile__languages li').removeClass('active');
                            link.closest('li').addClass('active');
                        } else {
                            alert('Failed to update language. Please try again.');
                        }
                    },
                    error: function() {
                        alert('An error occurred. Please try again.');
                    }
                });
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
    </style>
@endpush
