@extends('layouts.app')

@section('title', 'Security - Polaris Option')
@section('content')
    <div class="container py-4 mx-4">
        @include('partials.profile')
        <div class="panel box-border personal-info-panel mb-3">
            <div class="panel-heading">
                <div class="panel-title">Change Password</div>
            </div>
            <div class="panel-body text-white">
                <table class="table w-full" style="margin-bottom: 0">
                    <colgroup>
                        <col style="width: 35%">
                        <col style="width: 65%">
                    </colgroup>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        {{-- Two-factor authentication (2FA) --}}
        <div class="panel box-border personal-info-panel mb-3">
            <div class="panel-heading">
                <div class="panel-title">Two-factor authentication (2FA)</div>
            </div>
            <div class="panel-body text-white">
                <table class="table w-full" style="margin-bottom: 0">
                    <colgroup>
                        <col style="width: 35%">
                        <col style="width: 65%">
                    </colgroup>
                    <tbody></tbody>
                </table>
            </div>
        </div>

        <div class="panel box-border personal-info-panel mb-3">
            <div class="panel-heading">
                <div class="panel-title">Login history</div>
            </div>
            <div class="panel-body text-white">
                <table class="table w-full" style="margin-bottom: 0">
                    <colgroup>
                        <col style="width: 35%">
                        <col style="width: 65%">
                    </colgroup>
                    <tbody></tbody>
                </table>
            </div>
        </div>
        
        <div class="panel box-border personal-info-panel mb-3">
            <div class="panel-heading">
                <div class="panel-title">Active sessions</div>
            </div>
            <div class="panel-body text-white">
                <table class="table w-full" style="margin-bottom: 0">
                    <colgroup>
                        <col style="width: 35%">
                        <col style="width: 65%">
                    </colgroup>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection