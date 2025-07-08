@extends('layouts.app')

@section('title', 'Profile')
@section('content')
    <div class="m-4 ml-2 container py-4" style="margin: 1rem">
        @if(!is_mobile())
            @include('partials.profile')
        @endif
        <div class="panel box-border personal-info-panel mb-3">
            <div class="panel-heading">
                <div class="panel-title">Trading profile</div>
            </div>
            <div class="panel-body text-white">
            <div class="min-h-screen bg-gray-900 text-white" @if(is_mobile()) style="margin-bottom: 10rem;" @endif">
                @include('profile._trading-profile')
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
