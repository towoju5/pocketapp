@extends('layouts.app')

@section('content')
<div class="w-full" style="margin: 1rem">
    @include('partials.finance-header')
    <div class="panel panel-results box-border">
        <div class="panel-heading">
            <div class="panel-title">Balance history</div>
        </div>
        <div class="panel-body" id="trading-history">
            <div class="block-top-wrap">
                <ul class="sub-menu">
                    <li><a class="btn btn-default" href="{{ url()->current() }}/?t=d">Deposits</a></li>
                    <li><a class="btn btn-default" href="{{ url()->current() }}/?t=w">Withdrawal</a></li>
                    <li><a class="btn btn-default" href="{{ url()->current() }}/?t=it">Internal transfers</a></li>
                    <li class="active"><a class="btn btn-default" href="{{ url()->current() }}/">All Types</a></li>
                </ul>
                <div class="filters-block">
                    <form action="{{ url()->current() }}/" method="get" accept-charset="utf-8"> <input type="hidden" name="t" value="">
                        <div class="filters-block__row">
                            <div class="filters-block__col filters-block__col--datepicker">
                                <div id="reportrange" class="form-control reportrange" data-date="2025-02-09 17:49:24" data-opens="left" data-max-date="2025-02-09"> <input type="hidden" name="date_from" value="2024-12-21"> <input type="hidden" name="date_to" value="2025-02-09"> <i class="fa fa-calendar"></i>&nbsp;
                                    <span><span id="date-start">2024-12-21</span> - <span id="date-end">2025-02-09</span></span> <b class="caret"></b>
                                </div>
                            </div>
                            <div class="filters-block__col filters-block__col--btn">
                                <button type="submit" class="btn btn-primary">Apply</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <table class="table table-adaptive">
                <colgroup>
                    <col style="width: 12%">
                    <col style="width: 16%">
                    <col style="">
                    <col style="width: 12%">
                    <col style="width: 12%">
                    <col style="width: 12%">
                    <col style="width: 20%">
                </colgroup>
                <thead>
                    <tr>
                        <th>
                            <div class="tr-id">ID</div>
                        </th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>
                            Bonus amount </th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="js-parent parent">
                        <td>
                            <div class="flex flex-aic">
                                <div class="open-btn js-open-btn inline-flex flex-aic" title="More details">
                                    <svg class="svg-icon info-icon" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M6 0C2.68594 0 0 2.68594 0 6C0 9.31406 2.68594 12 6 12C9.31406 12 12 9.31406 12 6C12 2.68594 9.31406 0 6 0ZM6 3C6.41414 3 6.75 3.33586 6.75 3.75C6.75 4.16414 6.41414 4.5 6 4.5C5.58586 4.5 5.25 4.16484 5.25 3.75C5.25 3.33516 5.58516 3 6 3ZM6.9375 9H5.0625C4.75313 9 4.5 8.74922 4.5 8.4375C4.5 8.12578 4.75195 7.875 5.0625 7.875H5.4375V6.375H5.25C4.93945 6.375 4.6875 6.12305 4.6875 5.8125C4.6875 5.50195 4.94063 5.25 5.25 5.25H6C6.31055 5.25 6.5625 5.50195 6.5625 5.8125V7.875H6.9375C7.24805 7.875 7.5 8.12695 7.5 8.4375C7.5 8.74805 7.24922 9 6.9375 9Z"></path>
                                    </svg>
                                </div>
                                61853828
                            </div>
                        </td>
                        <td>
                            <span class="adaptive-label">Date</span> 2025-01-19 12:08:11
                        </td>
                        <td><span class="adaptive-label">Amount</span>$30</td>
                        <td><span class="adaptive-label">Method</span>Tether (USDT) TRC-20</td>
                        <td><span class="adaptive-label">Type</span>Deposit</td>
                        <td><span class="adaptive-label">Status</span>
                            <div class="label label-danger">Expired</div>
                        </td>
                        <td>
                            <div class="flex flex-aic">
                                <span class="adaptive-label">Bonus amount</span> $0
                            </div>
                        </td>
                    </tr>
                    <tr class="js-child full-info-tr hidden">
                        <td colspan="2">
                            <div class="full-info tr-id">
                                <div class="full-info__key">Payment Amount</div>
                                <div class="full-info__val">$30</div>
                            </div>
                        </td>
                        <td colspan="2">
                            <div class="full-info">
                                <div class="full-info__key">Account Details</div>
                                <div class="full-info__val">TYP4M3tnDydpUSufVmXE8CnhiznRvAxe94</div>
                            </div>
                        </td>
                        <td>
                            <div class="full-info">
                                <div class="full-info__key">Get help</div>
                                <div class="full-info__val">
                                    <a href="https://pocketoption.com/en/cabinet/support/create?o=10,31,85&amp;a=form&amp;field=deposit_id&amp;id=61853828" target="_blank" class="btn btn-green">Contact support</a>
                                </div>
                            </div>
                        </td>
                        <td colspan="2">
                            <div class="full-info">
                                <div class="full-info__key">Comment</div>
                                <div class="full-info__val">
                                    Payment has expired. Try again later or use another payment method. </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="js-parent parent">
                        <td>
                            <div class="flex flex-aic">
                                <div class="open-btn js-open-btn inline-flex flex-aic" title="More details">
                                    <svg class="svg-icon info-icon" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M6 0C2.68594 0 0 2.68594 0 6C0 9.31406 2.68594 12 6 12C9.31406 12 12 9.31406 12 6C12 2.68594 9.31406 0 6 0ZM6 3C6.41414 3 6.75 3.33586 6.75 3.75C6.75 4.16414 6.41414 4.5 6 4.5C5.58586 4.5 5.25 4.16484 5.25 3.75C5.25 3.33516 5.58516 3 6 3ZM6.9375 9H5.0625C4.75313 9 4.5 8.74922 4.5 8.4375C4.5 8.12578 4.75195 7.875 5.0625 7.875H5.4375V6.375H5.25C4.93945 6.375 4.6875 6.12305 4.6875 5.8125C4.6875 5.50195 4.94063 5.25 5.25 5.25H6C6.31055 5.25 6.5625 5.50195 6.5625 5.8125V7.875H6.9375C7.24805 7.875 7.5 8.12695 7.5 8.4375C7.5 8.74805 7.24922 9 6.9375 9Z"></path>
                                    </svg>
                                </div>
                                60989843
                            </div>
                        </td>
                        <td>
                            <span class="adaptive-label">Date</span> 2025-01-09 19:01:55
                        </td>
                        <td><span class="adaptive-label">Amount</span>$150</td>
                        <td><span class="adaptive-label">Method</span>Tether (USDT) TRC-20</td>
                        <td><span class="adaptive-label">Type</span>Deposit</td>
                        <td><span class="adaptive-label">Status</span>
                            <div class="label label-danger">Expired</div>
                        </td>
                        <td>
                            <div class="flex flex-aic">
                                <span class="adaptive-label">Bonus amount</span> $0
                            </div>
                        </td>
                    </tr>
                    <tr class="js-child full-info-tr hidden">
                        <td colspan="2">
                            <div class="full-info tr-id">
                                <div class="full-info__key">Payment Amount</div>
                                <div class="full-info__val">$150</div>
                            </div>
                        </td>
                        <td colspan="2">
                            <div class="full-info">
                                <div class="full-info__key">Account Details</div>
                                <div class="full-info__val">TYP4M3tnDydpUSufVmXE8CnhiznRvAxe94</div>
                            </div>
                        </td>
                        <td>
                            <div class="full-info">
                                <div class="full-info__key">Get help</div>
                                <div class="full-info__val">
                                    <a href="https://pocketoption.com/en/cabinet/support/create?o=10,31,85&amp;a=form&amp;field=deposit_id&amp;id=60989843" target="_blank" class="btn btn-green">Contact support</a>
                                </div>
                            </div>
                        </td>
                        <td colspan="2">
                            <div class="full-info">
                                <div class="full-info__key">Comment</div>
                                <div class="full-info__val">
                                    Payment has expired. Try again later or use another payment method. </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="js-parent parent">
                        <td>
                            <div class="flex flex-aic">
                                <div class="open-btn js-open-btn inline-flex flex-aic" title="More details">
                                    <svg class="svg-icon info-icon" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M6 0C2.68594 0 0 2.68594 0 6C0 9.31406 2.68594 12 6 12C9.31406 12 12 9.31406 12 6C12 2.68594 9.31406 0 6 0ZM6 3C6.41414 3 6.75 3.33586 6.75 3.75C6.75 4.16414 6.41414 4.5 6 4.5C5.58586 4.5 5.25 4.16484 5.25 3.75C5.25 3.33516 5.58516 3 6 3ZM6.9375 9H5.0625C4.75313 9 4.5 8.74922 4.5 8.4375C4.5 8.12578 4.75195 7.875 5.0625 7.875H5.4375V6.375H5.25C4.93945 6.375 4.6875 6.12305 4.6875 5.8125C4.6875 5.50195 4.94063 5.25 5.25 5.25H6C6.31055 5.25 6.5625 5.50195 6.5625 5.8125V7.875H6.9375C7.24805 7.875 7.5 8.12695 7.5 8.4375C7.5 8.74805 7.24922 9 6.9375 9Z"></path>
                                    </svg>
                                </div>
                                60859570
                            </div>
                        </td>
                        <td>
                            <span class="adaptive-label">Date</span> 2025-01-08 11:48:33
                        </td>
                        <td><span class="adaptive-label">Amount</span>$10</td>
                        <td><span class="adaptive-label">Method</span>QafPay</td>
                        <td><span class="adaptive-label">Type</span>Deposit</td>
                        <td><span class="adaptive-label">Status</span>
                            <div class="label label-danger">Expired</div>
                        </td>
                        <td>
                            <div class="flex flex-aic">
                                <span class="adaptive-label">Bonus amount</span> $0
                            </div>
                        </td>
                    </tr>
                    <tr class="js-child full-info-tr hidden">
                        <td colspan="2">
                            <div class="full-info tr-id">
                                <div class="full-info__key">Payment Amount</div>
                                <div class="full-info__val">₦15,452</div>
                            </div>
                        </td>
                        <td colspan="2" class="empty">
                        </td>
                        <td>
                            <div class="full-info">
                                <div class="full-info__key">Get help</div>
                                <div class="full-info__val">
                                    <a href="https://pocketoption.com/en/cabinet/support/create?o=10,32,90&amp;a=form&amp;field=deposit_id&amp;id=60859570" target="_blank" class="btn btn-green">Contact support</a>
                                </div>
                            </div>
                        </td>
                        <td colspan="2">
                            <div class="full-info">
                                <div class="full-info__key">Comment</div>
                                <div class="full-info__val">
                                    Payment has expired. Try again later or use another payment method. </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="js-parent parent">
                        <td>
                            <div class="flex flex-aic">
                                <div class="open-btn js-open-btn inline-flex flex-aic" title="More details">
                                    <svg class="svg-icon info-icon" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M6 0C2.68594 0 0 2.68594 0 6C0 9.31406 2.68594 12 6 12C9.31406 12 12 9.31406 12 6C12 2.68594 9.31406 0 6 0ZM6 3C6.41414 3 6.75 3.33586 6.75 3.75C6.75 4.16414 6.41414 4.5 6 4.5C5.58586 4.5 5.25 4.16484 5.25 3.75C5.25 3.33516 5.58516 3 6 3ZM6.9375 9H5.0625C4.75313 9 4.5 8.74922 4.5 8.4375C4.5 8.12578 4.75195 7.875 5.0625 7.875H5.4375V6.375H5.25C4.93945 6.375 4.6875 6.12305 4.6875 5.8125C4.6875 5.50195 4.94063 5.25 5.25 5.25H6C6.31055 5.25 6.5625 5.50195 6.5625 5.8125V7.875H6.9375C7.24805 7.875 7.5 8.12695 7.5 8.4375C7.5 8.74805 7.24922 9 6.9375 9Z"></path>
                                    </svg>
                                </div>
                                60859434
                            </div>
                        </td>
                        <td>
                            <span class="adaptive-label">Date</span> 2025-01-08 11:46:47
                        </td>
                        <td><span class="adaptive-label">Amount</span>$10</td>
                        <td><span class="adaptive-label">Method</span>Bank Transfer (NGN)</td>
                        <td><span class="adaptive-label">Type</span>Deposit</td>
                        <td><span class="adaptive-label">Status</span>
                            <div class="label label-danger">Expired</div>
                        </td>
                        <td>
                            <div class="flex flex-aic">
                                <span class="adaptive-label">Bonus amount</span> $0
                            </div>
                        </td>
                    </tr>
                    <tr class="js-child full-info-tr hidden">
                        <td colspan="2">
                            <div class="full-info tr-id">
                                <div class="full-info__key">Payment Amount</div>
                                <div class="full-info__val">₦15,452</div>
                            </div>
                        </td>
                        <td colspan="2" class="empty">
                        </td>
                        <td>
                            <div class="full-info">
                                <div class="full-info__key">Get help</div>
                                <div class="full-info__val">
                                    <a href="https://pocketoption.com/en/cabinet/support/create?o=10,32,90&amp;a=form&amp;field=deposit_id&amp;id=60859434" target="_blank" class="btn btn-green">Contact support</a>
                                </div>
                            </div>
                        </td>
                        <td colspan="2">
                            <div class="full-info">
                                <div class="full-info__key">Comment</div>
                                <div class="full-info__val">
                                    Payment has expired. Try again later or use another payment method. </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="js-parent parent">
                        <td>
                            <div class="flex flex-aic">
                                <div class="open-btn js-open-btn inline-flex flex-aic" title="More details">
                                    <svg class="svg-icon info-icon" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M6 0C2.68594 0 0 2.68594 0 6C0 9.31406 2.68594 12 6 12C9.31406 12 12 9.31406 12 6C12 2.68594 9.31406 0 6 0ZM6 3C6.41414 3 6.75 3.33586 6.75 3.75C6.75 4.16414 6.41414 4.5 6 4.5C5.58586 4.5 5.25 4.16484 5.25 3.75C5.25 3.33516 5.58516 3 6 3ZM6.9375 9H5.0625C4.75313 9 4.5 8.74922 4.5 8.4375C4.5 8.12578 4.75195 7.875 5.0625 7.875H5.4375V6.375H5.25C4.93945 6.375 4.6875 6.12305 4.6875 5.8125C4.6875 5.50195 4.94063 5.25 5.25 5.25H6C6.31055 5.25 6.5625 5.50195 6.5625 5.8125V7.875H6.9375C7.24805 7.875 7.5 8.12695 7.5 8.4375C7.5 8.74805 7.24922 9 6.9375 9Z"></path>
                                    </svg>
                                </div>
                                60859403
                            </div>
                        </td>
                        <td>
                            <span class="adaptive-label">Date</span> 2025-01-08 11:46:20
                        </td>
                        <td><span class="adaptive-label">Amount</span>$150</td>
                        <td><span class="adaptive-label">Method</span>Bank Transfer (NGN)</td>
                        <td><span class="adaptive-label">Type</span>Deposit</td>
                        <td><span class="adaptive-label">Status</span>
                            <div class="label label-danger">Expired</div>
                        </td>
                        <td>
                            <div class="flex flex-aic">
                                <span class="adaptive-label">Bonus amount</span> $0
                            </div>
                        </td>
                    </tr>
                    <tr class="js-child full-info-tr hidden">
                        <td colspan="2">
                            <div class="full-info tr-id">
                                <div class="full-info__key">Payment Amount</div>
                                <div class="full-info__val">₦231,777</div>
                            </div>
                        </td>
                        <td colspan="2" class="empty">
                        </td>
                        <td>
                            <div class="full-info">
                                <div class="full-info__key">Get help</div>
                                <div class="full-info__val">
                                    <a href="https://pocketoption.com/en/cabinet/support/create?o=10,32,90&amp;a=form&amp;field=deposit_id&amp;id=60859403" target="_blank" class="btn btn-green">Contact support</a>
                                </div>
                            </div>
                        </td>
                        <td colspan="2">
                            <div class="full-info">
                                <div class="full-info__key">Comment</div>
                                <div class="full-info__val">
                                    Payment has expired. Try again later or use another payment method. </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="js-parent parent">
                        <td>
                            <div class="flex flex-aic">
                                <div class="open-btn js-open-btn inline-flex flex-aic" title="More details">
                                    <svg class="svg-icon info-icon" width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M6 0C2.68594 0 0 2.68594 0 6C0 9.31406 2.68594 12 6 12C9.31406 12 12 9.31406 12 6C12 2.68594 9.31406 0 6 0ZM6 3C6.41414 3 6.75 3.33586 6.75 3.75C6.75 4.16414 6.41414 4.5 6 4.5C5.58586 4.5 5.25 4.16484 5.25 3.75C5.25 3.33516 5.58516 3 6 3ZM6.9375 9H5.0625C4.75313 9 4.5 8.74922 4.5 8.4375C4.5 8.12578 4.75195 7.875 5.0625 7.875H5.4375V6.375H5.25C4.93945 6.375 4.6875 6.12305 4.6875 5.8125C4.6875 5.50195 4.94063 5.25 5.25 5.25H6C6.31055 5.25 6.5625 5.50195 6.5625 5.8125V7.875H6.9375C7.24805 7.875 7.5 8.12695 7.5 8.4375C7.5 8.74805 7.24922 9 6.9375 9Z"></path>
                                    </svg>
                                </div>
                                60858541
                            </div>
                        </td>
                        <td>
                            <span class="adaptive-label">Date</span> 2025-01-08 11:34:47
                        </td>
                        <td><span class="adaptive-label">Amount</span>$150</td>
                        <td><span class="adaptive-label">Method</span>Tether (USDT) TRC-20</td>
                        <td><span class="adaptive-label">Type</span>Deposit</td>
                        <td><span class="adaptive-label">Status</span>
                            <div class="label label-danger">Expired</div>
                        </td>
                        <td>
                            <div class="flex flex-aic">
                                <span class="adaptive-label">Bonus amount</span> $0
                            </div>
                        </td>
                    </tr>
                    <tr class="js-child full-info-tr hidden">
                        <td colspan="2">
                            <div class="full-info tr-id">
                                <div class="full-info__key">Payment Amount</div>
                                <div class="full-info__val">$150</div>
                            </div>
                        </td>
                        <td colspan="2">
                            <div class="full-info">
                                <div class="full-info__key">Account Details</div>
                                <div class="full-info__val">TYP4M3tnDydpUSufVmXE8CnhiznRvAxe94</div>
                            </div>
                        </td>
                        <td>
                            <div class="full-info">
                                <div class="full-info__key">Get help</div>
                                <div class="full-info__val">
                                    <a href="https://pocketoption.com/en/cabinet/support/create?o=10,31,85&amp;a=form&amp;field=deposit_id&amp;id=60858541" target="_blank" class="btn btn-green">Contact support</a>
                                </div>
                            </div>
                        </td>
                        <td colspan="2">
                            <div class="full-info">
                                <div class="full-info__key">Comment</div>
                                <div class="full-info__val">
                                    Payment has expired. Try again later or use another payment method. </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="pull-right">
                <ul class="pagination m-t-0 m-b-10">
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection


@push('js')
<script src="//cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" href="//cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script>
    $(function() {
        $('input[name="daterange"]').daterangepicker({
            opens: 'right',
            startDate: '12/21/2024',
            endDate: '01/09/2025',
            locale: {
                format: 'YYYY-MM-DD'
            }
        });
    });
</script>
@endpush

@push('css')
<link rel="stylesheet" href="//pocketoption.com/themes/cabinet/css/vendors.bootstrap-select.min.css">
<link rel="stylesheet" href="//pocketoption.com/themes/cabinet/css/vendors.daterangepicker.min.css">
<link rel="stylesheet" href="//pocketoption.com/plugins/jquery.slick/1.6.0/slick-full.css">
<link rel="stylesheet" href="//pocketoption.com/platform/main.css">
<link rel="stylesheet" href="//pocketoption.com/themes/cabinet/css/vendors.fonts.min.css">
<link rel="stylesheet" href="//pocketoption.com/themes/cabinet/css/desktop.min.css">
<link rel="stylesheet" href="//pocketoption.com/themes/cabinet/css/desktop.theme-dark-blue.min.css">
<link rel="stylesheet" href="//pocketoption.com/themes/cabinet/css/pages/loyalty-program/index.min.css">
<link rel="stylesheet" href="{{ asset('theme/table-index.min.css') }}">
@endpush