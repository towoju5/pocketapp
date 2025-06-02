@extends('layouts.app')

@section('title', 'MT5 Trading')
@section('content')
    <div class="row gx-3">
        <div class="col-xxl-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="chart-block">
                        <div class="chart-block__wrap">
                            <div class="charts-container layout-full">
                                <div class="chart-item">
                                    <div class="top-left-block">
                                        <div class="top-left-block__block1"><a class="block1__item currencies-block"><span
                                                    class="asset-icon-flag-wrapper flag--one"><span
                                                        class="flag-icon flag-icon--aa"></span></span><span
                                                    class="current-symbol">Alcoa Inc</span><i
                                                    class="fa fa-caret-down"></i></a>
                                            <div class="icons-wrap"><a
                                                    class="tooltip2 block1__item items__link items__link--chart-type">
                                                    <div class="svg-wrapper">
                                                        <div><svg xmlns="http://www.w3.org/2000/svg"
                                                                class="svg-icon candles-icon injected-svg"
                                                                viewBox="0 0 500.1 435.1" width="55" height="35"
                                                                data-src="/themes/cabinet/svg/icons/chart-types/candles.svg"
                                                                xmlns:xlink="http://www.w3.org/1999/xlink" role="img">
                                                                <path
                                                                    d="M2.8 405H499c.6 0 1.1.5 1.1 1.1v26.4c0 2.2-.2 2.4-2.5 2.5h-2c-163.7 0-327.3 0-491 .1-3.7 0-4.7-.8-4.6-4.6.3-7.6.1-15.2.1-22.8 0-1.5 1.2-2.7 2.7-2.7zM425.1 1.8c0 11.9.1 23.8-.1 35.7 0 2 1.6 3.6 3.6 3.6 7.7-.2 15.3.1 23-.1 2.8-.1 3.6.8 3.6 3.6-.1 79.7-.1 159.3 0 239 0 2.8-.7 3.7-3.6 3.6-7.5-.2-15 .1-22.5-.1-3.3-.1-4.1.8-4.1 4.1.2 17.3 0 34.7.2 52 0 3.3-.8 4-4.2 4.1-7.2.1-14.5 0-21.9 0-3.3 0-4.1-.8-4.1-4.1.2-17.3 0-34.7.2-52 0-3.3-.8-4.2-4.1-4.1-7.5.2-15-.1-22.5.1-2.9.1-3.4-.7-3.4-3.6 0-5.1-.1-8.3-.1-12.5 0-75 0-150-.1-225 0-4.1.9-5.4 5.1-5.1 7.1.4 14.3 0 21.5.2 2 .1 3.6-1.5 3.6-3.5-.1-11.9-.1-23.8-.1-35.7 0-1 .8-1.9 1.9-1.9h26.3c1-.1 1.8.7 1.8 1.7zM199.1 164c0-18.3.1-36.7-.1-55 0-3.2.7-4.2 4.1-4.1 7.5.3 15 0 22.5.1 2.8.1 3.7-.7 3.6-3.6-.2-7.7.1-15.3-.1-23-.1-3.1.8-3.6 4.4-3.6 7.9 0 14.8.1 22.1 0 2.9 0 3.7.4 3.7 3-.1 7.8.1 15.7-.1 23.5-.1 2.8.7 3.7 3.6 3.6 7.7-.2 15.3.1 23-.1 2 0 3.6 1.5 3.6 3.5-.1 37-.1 74 0 111 0 2.8-.7 3.7-3.5 3.6-7.5-.2-15 .1-22.5-.1-3.2-.1-4.1.8-4.1 4.1.2 17.3 0 34.7.2 52 0 4-1.1 3.8-6.7 3.9-5.6.1-10.9 0-16.5 0s-7 .3-6.9-3.8c.2-17.3 0-34.7.2-52 0-3.2-.8-4.2-4.1-4.1-7.3.3-14.7-.1-22 .2-3.3.1-4.1-.8-4.1-4.1-.2-18.3-.3-36.7-.3-55zM44.1 228c0-18.3.1-36.7-.1-55 0-3.2.8-4.2 4.1-4.1 7.5.3 15 0 22.5.1 2.8.1 3.6-.7 3.6-3.6-.2-9.7 0-19.3-.1-29-.1-3.2 1-3.5 4.9-3.5h20.7c3.6 0 4.6.4 4.5 3.5-.2 9.7 0 19.3-.1 29 0 2 1.6 3.6 3.6 3.6 7.7-.2 15.3.1 23-.1 2.8-.1 3.6.8 3.6 3.6-.1 37-.1 74 0 111 0 2.8-.7 3.7-3.6 3.6-7.7-.2-15.3.1-23-.1-2-.1-3.6 1.6-3.6 3.5.1 15.5 0 31 .1 46.5 0 3.5-.9 4-5 4-6.7.1-13.3 0-20.1-.1-4 0-5-.2-5-3.5.1-15.5 0-31 .1-46.5 0-3.3-.8-4.2-4.1-4.1-7.3.3-14.7-.1-22 .2-3.3.1-4.1-.8-4.1-4.1.2-18.2.1-36.6.1-54.9z">
                                                                </path>
                                                            </svg></div>
                                                    </div>
                                                    <div class="counter animated zoomOut"><span
                                                            class="counter__number">M1</span></div>
                                                    <div class="tooltip-content tooltip-status-on position-down">
                                                        <div class="tooltip-text">Chart type: Candles<p>Timeframe: M1</p>
                                                        </div>
                                                    </div>
                                                </a><a class="tooltip2 block1__item items__link items__link--indicators"><i
                                                        class="items__fa fa fa-sliders"></i>
                                                    <div class="tooltip-content tooltip-status-on position-down">
                                                        <div class="tooltip-text">Indicators</div>
                                                    </div>
                                                </a><a class="tooltip2 block1__item items__link items__link--drawings"><i
                                                        class="items__fa fa fa-paint-brush"></i>
                                                    <div class="tooltip-content tooltip-status-on position-down">
                                                        <div class="tooltip-text">Drawings</div>
                                                    </div>
                                                </a></div>
                                        </div>
                                        <div class="session-opens-in-message">Not trading time. Alcoa Inc will open 00:48:39
                                        </div>
                                        <div class="top-left-block__block2"></div>
                                    </div>
                                    <div class="layout-container">
                                        <div class="chart-container">
                                            <div class="scroll-to-end"><i class="fa fa-angle-right fa-2x"
                                                    aria-hidden="true"></i></div>
                                            <div class="chart" id="chart-1" style="cursor: default;"><canvas
                                                    class="layer plot" width="963" height="567"
                                                    style="touch-action: none; width: 963px; height: 567px; position: absolute; cursor: inherit;"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="Toastify" id="chart-#AA"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://pocketoption.com/mt5-chart/index.js"></script>
@endpush
