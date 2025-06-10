@extends('layouts.app')

@section('title', 'Loyalty Program')

@section('content')
<div class="loyalty-program__content">
    <div class="loyalty-program__header">
        <div class="loyalty-program__header-title">
            Loyalty Program </div>
        <div class="loyalty-program__header-description">
            We value your trust and offer unique benefits and rewards. Enjoy exclusive offers, personalized discounts, and many other delightful bonuses. <br>The Pocket Option loyalty program is a trusted guide on your trading journey! </div>
    </div>
    <div class="loyalty-program__center">
        <div class="loyalty-program__next-level-info next-level-info next-level-info--beginner">
            <div class="next-level-info__icon">
                <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_56_499)">
                        <path d="M4.82139 23.8988L0.685152 19.8821C0.902685 20.0931 1.17616 20.2701 1.50246 20.3911L5.6387 24.4079C5.3124 24.2868 5.04204 24.1099 4.82139 23.8988Z" fill="black"></path>
                        <path d="M1.49928 20.3941L5.63553 24.4109C5.30923 24.2898 5.03886 24.1129 4.81822 23.9018L0.68198 19.8851C0.899514 20.0961 1.17298 20.2731 1.49928 20.3941Z" fill="#1A5922"></path>
                        <path d="M20.9189 0.646409L25.0551 4.66317C25.3286 4.92702 25.5399 5.27158 25.6642 5.69685L21.528 1.68009C21.4068 1.25482 21.1923 0.910261 20.9189 0.646409Z" fill="black"></path>
                        <path d="M20.9189 0.646409L25.0551 4.66317C25.3286 4.92702 25.5399 5.27158 25.6642 5.69685L21.528 1.68009C21.4068 1.25482 21.1923 0.910261 20.9189 0.646409Z" fill="#1A5922"></path>
                        <path d="M11.3847 24.1006L15.5241 28.1173L5.6387 24.4079L1.49928 20.3941L11.3847 24.1006Z" fill="#1A5821"></path>
                        <path d="M21.528 1.68009L25.6642 5.69685L28.604 15.8963L24.4647 11.8795L21.528 1.68009Z" fill="#195621"></path>
                        <path d="M23.5635 24.1005L27.6997 28.1173L23.2527 38.3144L19.1133 34.2977L23.5635 24.1005Z" fill="#1A5A22"></path>
                        <path d="M19.073 39.3512L14.9367 35.3345C15.3811 35.7659 15.9809 35.9832 16.5993 35.9832C17.5937 35.9832 18.6286 35.4214 19.1165 34.3008L23.2527 38.3144C22.7648 39.4381 21.73 40 20.7355 40C20.1202 40 19.5173 39.7827 19.073 39.3512Z" fill="black"></path>
                        <path d="M19.1133 34.2977L23.2527 38.3144C22.7648 39.4381 21.7268 39.9967 20.7324 39.9967C20.1171 39.9967 19.5142 39.7794 19.0698 39.3479L14.9336 35.3311C15.378 35.7626 15.9777 35.9799 16.5961 35.9799C17.5906 35.9799 18.6254 35.4182 19.1133 34.2977Z" fill="#1A5922"></path>
                        <path d="M33.9957 20.3942L38.132 24.411L27.6997 28.1173L23.5635 24.1005L33.9957 20.3942Z" fill="#1B5C23"></path>
                        <path d="M35.1704 16.0981L39.3067 20.1149C40.5466 21.3193 40.0805 23.7157 38.132 24.411L33.9957 20.3942C35.9473 19.6989 36.4104 17.3025 35.1704 16.0981Z" fill="black"></path>
                        <path d="M35.1704 16.0981L39.3067 20.1149C40.5466 21.3193 40.0805 23.7157 38.132 24.411L33.9957 20.3942C35.9473 19.6989 36.4104 17.3025 35.1704 16.0981Z" fill="#1A5922"></path>
                        <path d="M19.2563 0C20.2507 0 21.2048 0.559494 21.528 1.68009L24.4647 11.8795L34.35 15.5859C36.5253 16.4023 36.2892 19.5778 33.9957 20.3942L23.5603 24.0975L19.1133 34.2915C18.6254 35.4152 17.5906 35.9739 16.5962 35.9739C15.6017 35.9739 14.6508 35.4121 14.3245 34.2915L11.3847 24.1006L1.50246 20.3911C-0.669771 19.5748 -0.43981 16.3992 1.85361 15.5859L12.289 11.8796L16.7391 1.68245C17.227 0.56185 18.265 0 19.2563 0Z" fill="#32AC41"></path>
                    </g>
                    <defs>
                        <clipPath id="clip0_56_499">
                            <rect width="40" height="40" fill="white"></rect>
                        </clipPath>
                    </defs>
                </svg>
            </div>
            <div class="next-level-info__label">
                Beginner </div>
            <div class="next-level-info__text">
                Current Progress </div>
            <div class="next-level-info__statusbar">

                <div class="progress js-progress progress--size-large progress--postfix-line progress--active progress--bubble">
                    <div class="progress__bar" style="width: 0%">

                    </div>
                </div>
            </div>

            <table>
                <tbody>
                    <tr>
                        <td>Total deposits:</td>
                        <td class="nowrap">$0</td>
                    </tr>
                    <tr>
                        <td>Until the next level:</td>
                        <td class="nowrap">
                            $1,000 </td>
                    </tr>
                </tbody>
            </table>
            <div class="next-level-info__btn-wrap">
                <a class="h-btn h-btn--deposit" href="https://pocketoption.com/en/cabinet/deposit-step-1/">
                    <div class="h-btn__start">
                        <svg width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17 4H16V3C16 2.20435 15.6839 1.44129 15.1213 0.87868C14.5587 0.316071 13.7956 0 13 0H3C2.20435 0 1.44129 0.316071 0.87868 0.87868C0.316071 1.44129 0 2.20435 0 3V15C0 15.7956 0.316071 16.5587 0.87868 17.1213C1.44129 17.6839 2.20435 18 3 18H17C17.7956 18 18.5587 17.6839 19.1213 17.1213C19.6839 16.5587 20 15.7956 20 15V7C20 6.20435 19.6839 5.44129 19.1213 4.87868C18.5587 4.31607 17.7956 4 17 4ZM3 2H13C13.2652 2 13.5196 2.10536 13.7071 2.29289C13.8946 2.48043 14 2.73478 14 3V4H3C2.73478 4 2.48043 3.89464 2.29289 3.70711C2.10536 3.51957 2 3.26522 2 3C2 2.73478 2.10536 2.48043 2.29289 2.29289C2.48043 2.10536 2.73478 2 3 2ZM18 12H17C16.7348 12 16.4804 11.8946 16.2929 11.7071C16.1054 11.5196 16 11.2652 16 11C16 10.7348 16.1054 10.4804 16.2929 10.2929C16.4804 10.1054 16.7348 10 17 10H18V12ZM18 8H17C16.2044 8 15.4413 8.31607 14.8787 8.87868C14.3161 9.44129 14 10.2044 14 11C14 11.7956 14.3161 12.5587 14.8787 13.1213C15.4413 13.6839 16.2044 14 17 14H18V15C18 15.2652 17.8946 15.5196 17.7071 15.7071C17.5196 15.8946 17.2652 16 17 16H3C2.73478 16 2.48043 15.8946 2.29289 15.7071C2.10536 15.5196 2 15.2652 2 15V5.83C2.32127 5.94302 2.65943 6.00051 3 6H17C17.2652 6 17.5196 6.10536 17.7071 6.29289C17.8946 6.48043 18 6.73478 18 7V8Z" fill="currentColor"></path>
                        </svg>
                    </div>
                    <div class="h-btn__end">
                        <div class="h-btn__text">
                            Top up </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="loyalty-program__end">
            <div class="loyalty-program__your-level-description your-level-description">
                <div class="block-title">
                    Your Loyalty Level Advantages </div>
                <div class="block-sub-title">
                    A starting set on your trading journey. </div>
                <div class="your-level-description__list">
                    <div class="your-level-description__list-item">
                        <div class="your-level-description__list-item-k">
                            Max trade amount </div>
                        <div class="your-level-description__list-item-v">
                            $1,000 </div>
                    </div>
                    <div class="your-level-description__list-item">
                        <div class="your-level-description__list-item-k">
                            Personal discount in the market </div>
                        <div class="your-level-description__list-item-v">
                            5%
                        </div>
                    </div>
                    <div class="your-level-description__list-item">
                        <div class="your-level-description__list-item-k">
                            Increased payout (when available) </div>
                        <div class="your-level-description__list-item-v">
                            <svg width="25" height="24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.333 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20Zm0 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16Zm4-9h-8a1 1 0 0 0 0 2h8a1 1 0 0 0 0-2Z" fill="#8EA5C0" fill-opacity=".5"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="your-level-description__list-item">
                        <div class="your-level-description__list-item-k">
                            Personal assistant </div>
                        <div class="your-level-description__list-item-v">
                            <svg width="25" height="24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.333 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20Zm0 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16Zm4-9h-8a1 1 0 0 0 0 2h8a1 1 0 0 0 0-2Z" fill="#8EA5C0" fill-opacity=".5"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="your-level-description__list-item">
                        <div class="your-level-description__list-item-k">
                            Prioritized support resolutions </div>
                        <div class="your-level-description__list-item-v">
                            <svg width="25" height="24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.333 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20Zm0 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16Zm4-9h-8a1 1 0 0 0 0 2h8a1 1 0 0 0 0-2Z" fill="#8EA5C0" fill-opacity=".5"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="your-level-description__list-item">
                        <div class="your-level-description__list-item-k">
                            Prioritized withdrawals </div>
                        <div class="your-level-description__list-item-v">
                            <svg width="25" height="24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.333 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20Zm0 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16Zm4-9h-8a1 1 0 0 0 0 2h8a1 1 0 0 0 0-2Z" fill="#8EA5C0" fill-opacity=".5"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <div class="loyalty-program__support-info">
                <div class="loyalty-program__support-info-item support-info-item">
                    <div class="support-info-item__icon">
                        <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g opacity=".3" stroke="#8EA5C0">
                                <path d="M23.192 14.367A9.808 9.808 0 0 1 33 24.174a9.692 9.692 0 0 1-1.639 5.423L33 33.982l-5.515-.992a9.808 9.808 0 1 1-4.293-18.623Z" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M24.6 8.62A12.092 12.092 0 0 0 3 16.098a12 12 0 0 0 2.03 6.716L3 28.213l4.892-.877" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="m24.771 24.272.96 1.202.28-.123-.246.649h-4.53l-.247-.65.282.124.959-1.202 1.271-1.594 1.271 1.594Z" stroke-width="4"></path>
                            </g>
                        </svg>
                        <div class="support-info-item__close-icon">
                            <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g filter="url(#a8515)">
                                    <circle cx="14" cy="13" r="12" fill="#D63232"></circle>
                                </g>
                                <path d="m18 9-8 8M10 9l8 8" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                <defs>
                                    <filter id="a8515" x="0" y="0" width="28" height="28" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                        <feFlood flood-opacity="0" result="BackgroundImageFix"></feFlood>
                                        <feColorMatrix in="SourceAlpha" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"></feColorMatrix>
                                        <feOffset dy="1"></feOffset>
                                        <feGaussianBlur stdDeviation="1"></feGaussianBlur>
                                        <feComposite in2="hardAlpha" operator="out"></feComposite>
                                        <feColorMatrix values="0 0 0 0 0.114632 0 0 0 0 0.114632 0 0 0 0 0.114632 0 0 0 0.35 0"></feColorMatrix>
                                        <feBlend in2="BackgroundImageFix" result="effect1_dropShadow_175_16613"></feBlend>
                                        <feBlend in="SourceGraphic" in2="effect1_dropShadow_175_16613" result="shape"></feBlend>
                                    </filter>
                                </defs>
                            </svg>
                        </div>
                    </div>
                    <div class="support-info-item__center">
                        <div class="support-info-item__title">
                            Private Chat with VIP Traders </div>
                        <div class="support-info-item__text">
                            Not available at current level </div>
                    </div>
                    <div class="support-info-item__end">
                        <a href="https://pocketoption.com/en/cabinet/deposit-step-1/" class="btn btn-primary">
                            Upgrade Your Level </a>
                    </div>
                </div>
                <div class="loyalty-program__support-info-item support-info-item">
                    <div class="support-info-item__icon">
                        <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g opacity=".3" clip-path="url(#a9675)" stroke="#8EA5C0" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M8.154 18.002v-6.474A9.674 9.674 0 0 1 18 2.002a9.674 9.674 0 0 1 9.846 9.526v6.474M4.462 14.31h2.461a1.23 1.23 0 0 1 1.23 1.23v7.385a1.23 1.23 0 0 1-1.23 1.23H4.462A2.462 2.462 0 0 1 2 21.694V16.77a2.461 2.461 0 0 1 2.462-2.462ZM31.538 24.155h-2.461a1.231 1.231 0 0 1-1.23-1.23V15.54a1.231 1.231 0 0 1 1.23-1.23h2.461A2.462 2.462 0 0 1 34 16.77v4.924a2.462 2.462 0 0 1-2.462 2.461ZM22.923 30.925A4.923 4.923 0 0 0 27.846 26v-5.538M22.923 30.925A3.077 3.077 0 0 1 19.846 34h-3.692a3.077 3.077 0 0 1 0-6.153h3.692a3.077 3.077 0 0 1 3.077 3.077Z"></path>
                            </g>
                            <defs>
                                <clipPath id="a9675">
                                    <path fill="#fff" d="M0 0h36v36H0z"></path>
                                </clipPath>
                            </defs>
                        </svg>
                        <div class="support-info-item__close-icon">
                            <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g filter="url(#a8515)">
                                    <circle cx="14" cy="13" r="12" fill="#D63232"></circle>
                                </g>
                                <path d="m18 9-8 8M10 9l8 8" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                <defs>
                                    <filter id="a8515" x="0" y="0" width="28" height="28" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                        <feFlood flood-opacity="0" result="BackgroundImageFix"></feFlood>
                                        <feColorMatrix in="SourceAlpha" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"></feColorMatrix>
                                        <feOffset dy="1"></feOffset>
                                        <feGaussianBlur stdDeviation="1"></feGaussianBlur>
                                        <feComposite in2="hardAlpha" operator="out"></feComposite>
                                        <feColorMatrix values="0 0 0 0 0.114632 0 0 0 0 0.114632 0 0 0 0 0.114632 0 0 0 0.35 0"></feColorMatrix>
                                        <feBlend in2="BackgroundImageFix" result="effect1_dropShadow_175_16613"></feBlend>
                                        <feBlend in="SourceGraphic" in2="effect1_dropShadow_175_16613" result="shape"></feBlend>
                                    </filter>
                                </defs>
                            </svg>
                        </div>
                    </div>
                    <div class="support-info-item__center">
                        <div class="support-info-item__title">
                            Personal Assistant </div>
                        <div class="support-info-item__text">
                            Not available at current level </div>
                    </div>
                    <div class="support-info-item__end">
                        <a href="https://pocketoption.com/en/cabinet/deposit-step-1/" class="btn btn-primary">
                            Upgrade Your Level </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="loyalty-program__levels levels">
        <div class="block-title">
            The loyalty program levels we offer <div class="levels__arrows js-dots">
                <ul class="slick-dots" style="" role="tablist">
                    <li class="slick-active" role="presentation"><button type="button" role="tab" id="slick-slide-control00" aria-controls="slick-slide00" aria-label="1 of 3" tabindex="0" aria-selected="true">1</button></li>
                    <li role="presentation"><button type="button" role="tab" id="slick-slide-control01" aria-controls="slick-slide01" aria-label="2 of 3" tabindex="-1">2</button></li>
                    <li role="presentation"><button type="button" role="tab" id="slick-slide-control02" aria-controls="slick-slide02" aria-label="3 of 3" tabindex="-1">3</button></li>
                    <li role="presentation"><button type="button" role="tab" id="slick-slide-control03" aria-controls="slick-slide03" aria-label="4 of 3" tabindex="-1">4</button></li>
                </ul>
            </div>
        </div>
        <div class="tt-slider js-tt-slider slick-initialized slick-slider slick-dotted" style="max-width: 899px;">
            <div class="slick-list draggable">
                <div class="slick-track" style="opacity: 1; width: 2250px; transform: translate3d(0px, 0px, 0px);">
                    <div class="slick-slide slick-current slick-active" data-slick-index="0" aria-hidden="false" style="width: 430px;" role="tabpanel" id="slick-slide00" aria-describedby="slick-slide-control00">
                        <div>
                            <div class="levels__item levels__item--active levels__item--beginner" style="width: 100%; display: inline-block;">
                                <div class="levels__item-label">
                                    Beginner <div class="levels__item-label-icon">
                                        <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <g clip-path="url(#clip0_56_499)">
                                                <path d="M4.82139 23.8988L0.685152 19.8821C0.902685 20.0931 1.17616 20.2701 1.50246 20.3911L5.6387 24.4079C5.3124 24.2868 5.04204 24.1099 4.82139 23.8988Z" fill="black"></path>
                                                <path d="M1.49928 20.3941L5.63553 24.4109C5.30923 24.2898 5.03886 24.1129 4.81822 23.9018L0.68198 19.8851C0.899514 20.0961 1.17298 20.2731 1.49928 20.3941Z" fill="#1A5922"></path>
                                                <path d="M20.9189 0.646409L25.0551 4.66317C25.3286 4.92702 25.5399 5.27158 25.6642 5.69685L21.528 1.68009C21.4068 1.25482 21.1923 0.910261 20.9189 0.646409Z" fill="black"></path>
                                                <path d="M20.9189 0.646409L25.0551 4.66317C25.3286 4.92702 25.5399 5.27158 25.6642 5.69685L21.528 1.68009C21.4068 1.25482 21.1923 0.910261 20.9189 0.646409Z" fill="#1A5922"></path>
                                                <path d="M11.3847 24.1006L15.5241 28.1173L5.6387 24.4079L1.49928 20.3941L11.3847 24.1006Z" fill="#1A5821"></path>
                                                <path d="M21.528 1.68009L25.6642 5.69685L28.604 15.8963L24.4647 11.8795L21.528 1.68009Z" fill="#195621"></path>
                                                <path d="M23.5635 24.1005L27.6997 28.1173L23.2527 38.3144L19.1133 34.2977L23.5635 24.1005Z" fill="#1A5A22"></path>
                                                <path d="M19.073 39.3512L14.9367 35.3345C15.3811 35.7659 15.9809 35.9832 16.5993 35.9832C17.5937 35.9832 18.6286 35.4214 19.1165 34.3008L23.2527 38.3144C22.7648 39.4381 21.73 40 20.7355 40C20.1202 40 19.5173 39.7827 19.073 39.3512Z" fill="black"></path>
                                                <path d="M19.1133 34.2977L23.2527 38.3144C22.7648 39.4381 21.7268 39.9967 20.7324 39.9967C20.1171 39.9967 19.5142 39.7794 19.0698 39.3479L14.9336 35.3311C15.378 35.7626 15.9777 35.9799 16.5961 35.9799C17.5906 35.9799 18.6254 35.4182 19.1133 34.2977Z" fill="#1A5922"></path>
                                                <path d="M33.9957 20.3942L38.132 24.411L27.6997 28.1173L23.5635 24.1005L33.9957 20.3942Z" fill="#1B5C23"></path>
                                                <path d="M35.1704 16.0981L39.3067 20.1149C40.5466 21.3193 40.0805 23.7157 38.132 24.411L33.9957 20.3942C35.9473 19.6989 36.4104 17.3025 35.1704 16.0981Z" fill="black"></path>
                                                <path d="M35.1704 16.0981L39.3067 20.1149C40.5466 21.3193 40.0805 23.7157 38.132 24.411L33.9957 20.3942C35.9473 19.6989 36.4104 17.3025 35.1704 16.0981Z" fill="#1A5922"></path>
                                                <path d="M19.2563 0C20.2507 0 21.2048 0.559494 21.528 1.68009L24.4647 11.8795L34.35 15.5859C36.5253 16.4023 36.2892 19.5778 33.9957 20.3942L23.5603 24.0975L19.1133 34.2915C18.6254 35.4152 17.5906 35.9739 16.5962 35.9739C15.6017 35.9739 14.6508 35.4121 14.3245 34.2915L11.3847 24.1006L1.50246 20.3911C-0.669771 19.5748 -0.43981 16.3992 1.85361 15.5859L12.289 11.8796L16.7391 1.68245C17.227 0.56185 18.265 0 19.2563 0Z" fill="#32AC41"></path>
                                            </g>
                                            <defs>
                                                <clipPath id="clip0_56_499">
                                                    <rect width="40" height="40" fill="white"></rect>
                                                </clipPath>
                                            </defs>
                                        </svg>
                                    </div>
                                </div>
                                <div class="levels__item-desc">
                                    A starting set on your trading journey. </div>
                                <div class="levels__item-total-dpst">
                                    <div class="levels__item-total-dpst-title">
                                        Total Deposits </div>
                                    <div class="levels__item-total-dpst-number">
                                        &lt; $1,000 </div>
                                </div>
                                <div class="levels__item-btn-wrap">
                                    <div class="levels__item-your-level">
                                        Your level </div>
                                </div>
                                <div class="levels__item-text">
                                    <div class="levels__item-text-1">
                                        Max trade amount </div>
                                    <div class="levels__item-text-2">
                                        $1,000 </div>
                                </div>
                                <div class="levels__item-text">
                                    <div class="levels__item-text-1">
                                        Personal discount in the market </div>
                                    <div class="levels__item-text-2">
                                        5% (Deposits &gt; $100)
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slick-slide slick-active" data-slick-index="1" aria-hidden="false" style="width: 430px;" role="tabpanel" id="slick-slide01" aria-describedby="slick-slide-control01">
                        <div>
                            <div class="levels__item  levels__item--master" style="width: 100%; display: inline-block;">
                                <div class="levels__item-label">
                                    Master <div class="levels__item-label-icon">
                                        <svg viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <g clip-path="url(#clip0_56_487)">
                                                <path d="M17.6336 0.248901L20.7325 3.26171C20.9164 3.44098 21.0166 3.6971 20.9956 3.97882L17.8967 0.966014C17.9176 0.68662 17.8175 0.42818 17.6336 0.248901Z" fill="black"></path>
                                                <path d="M17.6336 0.248901L20.7325 3.26171C20.9164 3.44098 21.0166 3.6971 20.9956 3.97882L17.8967 0.966014C17.9176 0.68662 17.8175 0.42818 17.6336 0.248901Z" fill="#122F59"></path>
                                                <path d="M7.65219 20.114L4.55324 17.1012C4.71622 17.2596 4.93741 17.3504 5.17722 17.3504L8.27617 20.3632C8.03403 20.3632 7.81285 20.2724 7.65219 20.114Z" fill="black"></path>
                                                <path d="M5.17722 17.3504L8.27617 20.3632C8.03403 20.3632 7.81285 20.2724 7.65219 20.114L4.55324 17.1012C4.71622 17.2596 4.93741 17.3504 5.17722 17.3504Z" fill="#122F59"></path>
                                                <path d="M17.8967 0.966014L20.9956 3.97882L20.3553 12.6494L17.2564 9.63657L17.8967 0.966014Z" fill="#112E58"></path>
                                                <path d="M10.9328 17.3503L14.0317 20.3631L8.27617 20.3632L5.17722 17.3504L10.9328 17.3503Z" fill="#122F5B"></path>
                                                <path d="M23.6336 9.88574L26.7325 12.8985C26.8094 12.9731 26.8746 13.0639 26.9188 13.1663C27.0631 13.4829 27.0119 13.8671 26.7861 14.1605L23.6871 11.1477C23.913 10.8543 23.9619 10.4701 23.8198 10.1535C23.7733 10.0511 23.7104 9.96025 23.6336 9.88574Z" fill="black"></path>
                                                <path d="M23.6336 9.88574L26.7325 12.8985C26.8094 12.9731 26.8746 13.0639 26.9188 13.1663C27.0631 13.4829 27.0119 13.8671 26.7861 14.1605L23.6871 11.1477C23.913 10.8543 23.9619 10.4701 23.8198 10.1535C23.7733 10.0511 23.7104 9.96025 23.6336 9.88574Z" fill="#122F59"></path>
                                                <path d="M23.6871 11.1477L26.7861 14.1605L15.0981 29.5809L11.9991 26.5681L23.6871 11.1477Z" fill="#12305C"></path>
                                                <path d="M13.6545 29.7508L10.5556 26.738C10.6487 26.8288 10.7651 26.901 10.8978 26.9429C10.9886 26.9731 11.0841 26.9871 11.1795 26.9871C11.4892 26.9871 11.7989 26.8358 11.9991 26.5681L15.0981 29.5809C14.8955 29.8486 14.5881 29.9999 14.2785 29.9999C14.183 29.9999 14.0899 29.986 13.9968 29.9557C13.864 29.9138 13.7476 29.8416 13.6545 29.7508Z" fill="black"></path>
                                                <path d="M11.9991 26.5681L15.0981 29.5809C14.8955 29.8486 14.5881 29.9999 14.2785 29.9999C14.183 29.9999 14.0899 29.986 13.9968 29.9557C13.864 29.9138 13.7476 29.8416 13.6545 29.7508L10.5556 26.738C10.6487 26.8288 10.7651 26.901 10.8978 26.9429C10.9886 26.9731 11.0841 26.9871 11.1795 26.9871C11.4892 26.9871 11.7989 26.8358 11.9991 26.5681Z" fill="#122F59"></path>
                                                <path d="M17.0072 -0.00012207C17.1027 -0.00012207 17.1958 0.0138477 17.289 0.0441154C17.6801 0.169843 17.9269 0.542266 17.8967 0.966014L17.2564 9.63657L23.0119 9.63899C23.3681 9.63899 23.6801 9.8369 23.8221 10.1559C23.9665 10.4725 23.9153 10.8567 23.6894 11.1501L11.9991 26.5681C11.7989 26.8358 11.4892 26.9871 11.1795 26.9871C11.0841 26.9871 10.9886 26.9731 10.8978 26.9429C10.5067 26.8172 10.2599 26.4469 10.2901 26.0255L10.9328 17.3503L5.17722 17.3504C4.82099 17.3504 4.50899 17.1524 4.36464 16.8334C4.22261 16.5168 4.27383 16.1349 4.49735 15.8392L16.1877 0.41897C16.3879 0.151217 16.6976 -0.00012207 17.0072 -0.00012207Z" fill="#225AAC"></path>
                                            </g>
                                            <defs>
                                                <clipPath id="clip0_56_487">
                                                    <rect width="30" height="30" fill="white"></rect>
                                                </clipPath>
                                            </defs>
                                        </svg>
                                    </div>
                                </div>
                                <div class="levels__item-desc">
                                    Expanded set of trading options for confident trading. </div>
                                <div class="levels__item-total-dpst">
                                    <div class="levels__item-total-dpst-title">
                                        Total Deposits </div>
                                    <div class="levels__item-total-dpst-number">
                                        $1,000 - $10,000 </div>
                                </div>
                                <div class="levels__item-btn-wrap">
                                    <a href="https://pocketoption.com/en/cabinet/deposit-step-1/" class="levels__item-btn" tabindex="0">
                                        Upgrade Your Level </a>
                                </div>
                                <div class="levels__item-text">
                                    <div class="levels__item-text-1">
                                        Max trade amount </div>
                                    <div class="levels__item-text-2">
                                        $2,000 </div>
                                </div>
                                <div class="levels__item-text">
                                    <div class="levels__item-text-1">
                                        Increased payout (when available) </div>
                                    <div class="levels__item-text-2">
                                        + 2% </div>
                                </div>
                                <div class="levels__item-text">
                                    <div class="levels__item-text-1">
                                        Personal discount in the market </div>
                                    <div class="levels__item-text-2">
                                        10% </div>
                                </div>
                                <div class="levels__item-text">
                                    <div class="levels__item-text-1">
                                        <div class="levels__item-text-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M6.4443 3.6853C8.08879 2.58649 10.0222 2 12 2C13.3132 2 14.6136 2.25866 15.8268 2.7612C17.0401 3.26375 18.1425 4.00035 19.0711 4.92893C19.9997 5.85752 20.7363 6.95991 21.2388 8.17317C21.7413 9.38642 22 10.6868 22 12C22 13.9778 21.4135 15.9112 20.3147 17.5557C19.2159 19.2002 17.6541 20.4819 15.8268 21.2388C13.9996 21.9957 11.9889 22.1937 10.0491 21.8079C8.10929 21.422 6.32746 20.4696 4.92894 19.0711C3.53041 17.6725 2.578 15.8907 2.19215 13.9509C1.8063 12.0111 2.00433 10.0004 2.76121 8.17317C3.51809 6.3459 4.79981 4.78412 6.4443 3.6853ZM14.72 8.79006L10.43 13.0901L8.77999 11.4401C8.69034 11.3354 8.58002 11.2504 8.45596 11.1903C8.3319 11.1303 8.19676 11.0966 8.05904 11.0913C7.92132 11.0859 7.78399 11.1091 7.65567 11.1594C7.52734 11.2097 7.41079 11.286 7.31334 11.3834C7.21589 11.4809 7.13963 11.5974 7.08935 11.7257C7.03908 11.8541 7.01587 11.9914 7.02119 12.1291C7.02651 12.2668 7.06024 12.402 7.12027 12.526C7.18029 12.6501 7.26531 12.7604 7.36999 12.8501L9.71999 15.2101C9.81343 15.3027 9.92425 15.3761 10.0461 15.4258C10.1679 15.4756 10.2984 15.5008 10.43 15.5001C10.6923 15.499 10.9437 15.3948 11.13 15.2101L16.13 10.2101C16.2237 10.1171 16.2981 10.0065 16.3489 9.88464C16.3997 9.76278 16.4258 9.63207 16.4258 9.50006C16.4258 9.36805 16.3997 9.23734 16.3489 9.11548C16.2981 8.99362 16.2237 8.88302 16.13 8.79006C15.9426 8.60381 15.6892 8.49927 15.425 8.49927C15.1608 8.49927 14.9074 8.60381 14.72 8.79006Z" fill="#8EA5C0"></path>
                                            </svg>
                                        </div>
                                        Express Trades
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slick-slide" data-slick-index="2" aria-hidden="true" style="width: 430px;" tabindex="-1" role="tabpanel" id="slick-slide02" aria-describedby="slick-slide-control02">
                        <div>
                            <div class="levels__item  levels__item--guru" style="width: 100%; display: inline-block;">
                                <div class="levels__item-label">
                                    Guru <div class="levels__item-label-icon">
                                        <svg viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <g clip-path="url(#clip0_56_491)">
                                                <path d="M7.81419 10.2538L10.9131 13.2666L5.21116 9.81139L2.11221 6.79858L7.81419 10.2538Z" fill="#7B5718"></path>
                                                <path d="M12.4917 5.12922L15.5907 8.14202C16.0913 8.62863 16.5034 9.22467 16.8037 9.92549L13.7048 6.91268C13.4067 6.21187 12.9923 5.61583 12.4917 5.12922Z" fill="black"></path>
                                                <path d="M12.4917 5.12922L15.5907 8.14202C16.0913 8.62863 16.5034 9.22467 16.8037 9.92549L13.7048 6.91268C13.4067 6.21187 12.9923 5.61583 12.4917 5.12922Z" fill="#7A5618"></path>
                                                <path d="M7.68613 16.6682L10.7851 19.681L4.27286 23.3038L1.1739 20.291L7.68613 16.6682Z" fill="#7B5718"></path>
                                                <path d="M18.5336 2.03958L21.6326 5.05239C23.8933 7.25029 24.2123 10.7055 22.0959 13.3877L18.9969 10.3749C21.1133 7.69267 20.7944 4.23749 18.5336 2.03958Z" fill="black"></path>
                                                <path d="M18.5336 2.03958L21.6326 5.05239C23.8933 7.25029 24.2123 10.7055 22.0959 13.3877L18.9969 10.3749C21.1133 7.69267 20.7944 4.23749 18.5336 2.03958Z" fill="#7A5618"></path>
                                                <path d="M12.7781 19.4482L15.877 22.4633L15.3206 30L12.2216 26.9872L12.7781 19.4482Z" fill="#7B5718"></path>
                                                <path d="M10.9178 28.2328L7.81882 25.22C8.83628 26.2095 10.3031 26.8801 12.2216 26.9872L15.3206 30C13.4021 29.8929 11.9352 29.2223 10.9178 28.2328Z" fill="black"></path>
                                                <path d="M12.2216 26.9872L15.3206 30C13.4021 29.8929 11.9352 29.2223 10.9178 28.2328L7.81882 25.22C8.83628 26.2095 10.3031 26.8801 12.2216 26.9872Z" fill="#7A5618"></path>
                                                <path d="M25.43 6.79627L28.529 9.80908C30.7804 15.057 26.7315 19.6251 22.3241 19.6251C21.9678 19.6251 21.6116 19.5949 21.2531 19.5343L18.1541 16.5215C18.5103 16.5821 18.8689 16.6123 19.2251 16.6123C23.6326 16.6123 27.6815 12.0442 25.43 6.79627Z" fill="black"></path>
                                                <path d="M25.43 6.79627L28.529 9.80908C30.7804 15.057 26.7315 19.6251 22.3241 19.6251C21.9678 19.6251 21.6116 19.5949 21.2531 19.5343L18.1541 16.5215C18.5103 16.5821 18.8689 16.6123 19.2251 16.6123C23.6326 16.6123 27.6815 12.0442 25.43 6.79627Z" fill="#7A5618"></path>
                                                <path d="M14.2146 0C19.9818 0.887078 22.0912 6.45635 18.9969 10.3749L25.43 6.79627C27.6815 12.0442 23.6326 16.6123 19.2251 16.6123C18.8689 16.6123 18.5103 16.5821 18.1541 16.5215L24.373 20.291C22.671 22.6403 20.4777 23.6531 18.4428 23.6531C15.8677 23.6531 13.5441 22.0349 12.7781 19.4482L12.2216 26.9872C6.06796 26.6426 4.54992 20.4959 7.68613 16.6682L1.1739 20.291C-0.493151 15.0198 2.99695 10.2421 7.44631 10.2421C7.56738 10.2421 7.69312 10.2468 7.81419 10.2538L2.11221 6.79858C4.01907 4.60301 6.37064 3.58091 8.49637 3.58091C10.7711 3.58091 12.7851 4.75436 13.7048 6.91268L14.2146 0Z" fill="#E3A02D"></path>
                                                <path d="M17.4067 25.0058L14.3078 21.993C15.3951 23.0501 16.8735 23.6531 18.4428 23.6531C20.4777 23.6531 22.671 22.6403 24.373 20.291L27.4719 23.3038C25.77 25.6531 23.5767 26.6659 21.5418 26.6659C19.9702 26.6659 18.4941 26.0629 17.4067 25.0058Z" fill="black"></path>
                                                <path d="M24.373 20.291L27.4719 23.3038C25.77 25.6531 23.5767 26.6659 21.5418 26.6659C19.9702 26.6659 18.4941 26.0629 17.4067 25.0058L14.3078 21.993C15.3951 23.0501 16.8735 23.6531 18.4428 23.6531C20.4777 23.6531 22.671 22.6403 24.373 20.291Z" fill="#7A5618"></path>
                                            </g>
                                            <defs>
                                                <clipPath id="clip0_56_491">
                                                    <rect width="30" height="30" fill="white"></rect>
                                                </clipPath>
                                            </defs>
                                        </svg>
                                    </div>
                                </div>
                                <div class="levels__item-desc">
                                    Maximum available trading options and priority service for lucrative trading. </div>
                                <div class="levels__item-total-dpst">
                                    <div class="levels__item-total-dpst-title">
                                        Total Deposits </div>
                                    <div class="levels__item-total-dpst-number">
                                        $10,000 + </div>
                                </div>
                                <div class="levels__item-btn-wrap">
                                    <a href="https://pocketoption.com/en/cabinet/deposit-step-1/" class="levels__item-btn" tabindex="-1">
                                        Upgrade Your Level </a>
                                </div>
                                <div class="levels__item-text">
                                    <div class="levels__item-text-1">
                                        Max trade amount </div>
                                    <div class="levels__item-text-2">
                                        $3,000 </div>
                                </div>
                                <div class="levels__item-text">
                                    <div class="levels__item-text-1">
                                        Increased payout (when available) </div>
                                    <div class="levels__item-text-2">
                                        + 4% </div>
                                </div>
                                <div class="levels__item-text">
                                    <div class="levels__item-text-1">
                                        Personal discount in the market </div>
                                    <div class="levels__item-text-2">
                                        15% </div>
                                </div>
                                <div class="levels__item-text">
                                    <div class="levels__item-text-1">
                                        <div class="levels__item-text-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M6.4443 3.6853C8.08879 2.58649 10.0222 2 12 2C13.3132 2 14.6136 2.25866 15.8268 2.7612C17.0401 3.26375 18.1425 4.00035 19.0711 4.92893C19.9997 5.85752 20.7363 6.95991 21.2388 8.17317C21.7413 9.38642 22 10.6868 22 12C22 13.9778 21.4135 15.9112 20.3147 17.5557C19.2159 19.2002 17.6541 20.4819 15.8268 21.2388C13.9996 21.9957 11.9889 22.1937 10.0491 21.8079C8.10929 21.422 6.32746 20.4696 4.92894 19.0711C3.53041 17.6725 2.578 15.8907 2.19215 13.9509C1.8063 12.0111 2.00433 10.0004 2.76121 8.17317C3.51809 6.3459 4.79981 4.78412 6.4443 3.6853ZM14.72 8.79006L10.43 13.0901L8.77999 11.4401C8.69034 11.3354 8.58002 11.2504 8.45596 11.1903C8.3319 11.1303 8.19676 11.0966 8.05904 11.0913C7.92132 11.0859 7.78399 11.1091 7.65567 11.1594C7.52734 11.2097 7.41079 11.286 7.31334 11.3834C7.21589 11.4809 7.13963 11.5974 7.08935 11.7257C7.03908 11.8541 7.01587 11.9914 7.02119 12.1291C7.02651 12.2668 7.06024 12.402 7.12027 12.526C7.18029 12.6501 7.26531 12.7604 7.36999 12.8501L9.71999 15.2101C9.81343 15.3027 9.92425 15.3761 10.0461 15.4258C10.1679 15.4756 10.2984 15.5008 10.43 15.5001C10.6923 15.499 10.9437 15.3948 11.13 15.2101L16.13 10.2101C16.2237 10.1171 16.2981 10.0065 16.3489 9.88464C16.3997 9.76278 16.4258 9.63207 16.4258 9.50006C16.4258 9.36805 16.3997 9.23734 16.3489 9.11548C16.2981 8.99362 16.2237 8.88302 16.13 8.79006C15.9426 8.60381 15.6892 8.49927 15.425 8.49927C15.1608 8.49927 14.9074 8.60381 14.72 8.79006Z" fill="#8EA5C0"></path>
                                            </svg>
                                        </div>
                                        Prioritized support resolutions
                                    </div>
                                </div>
                                <div class="levels__item-text">
                                    <div class="levels__item-text-1">
                                        <div class="levels__item-text-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M6.4443 3.6853C8.08879 2.58649 10.0222 2 12 2C13.3132 2 14.6136 2.25866 15.8268 2.7612C17.0401 3.26375 18.1425 4.00035 19.0711 4.92893C19.9997 5.85752 20.7363 6.95991 21.2388 8.17317C21.7413 9.38642 22 10.6868 22 12C22 13.9778 21.4135 15.9112 20.3147 17.5557C19.2159 19.2002 17.6541 20.4819 15.8268 21.2388C13.9996 21.9957 11.9889 22.1937 10.0491 21.8079C8.10929 21.422 6.32746 20.4696 4.92894 19.0711C3.53041 17.6725 2.578 15.8907 2.19215 13.9509C1.8063 12.0111 2.00433 10.0004 2.76121 8.17317C3.51809 6.3459 4.79981 4.78412 6.4443 3.6853ZM14.72 8.79006L10.43 13.0901L8.77999 11.4401C8.69034 11.3354 8.58002 11.2504 8.45596 11.1903C8.3319 11.1303 8.19676 11.0966 8.05904 11.0913C7.92132 11.0859 7.78399 11.1091 7.65567 11.1594C7.52734 11.2097 7.41079 11.286 7.31334 11.3834C7.21589 11.4809 7.13963 11.5974 7.08935 11.7257C7.03908 11.8541 7.01587 11.9914 7.02119 12.1291C7.02651 12.2668 7.06024 12.402 7.12027 12.526C7.18029 12.6501 7.26531 12.7604 7.36999 12.8501L9.71999 15.2101C9.81343 15.3027 9.92425 15.3761 10.0461 15.4258C10.1679 15.4756 10.2984 15.5008 10.43 15.5001C10.6923 15.499 10.9437 15.3948 11.13 15.2101L16.13 10.2101C16.2237 10.1171 16.2981 10.0065 16.3489 9.88464C16.3997 9.76278 16.4258 9.63207 16.4258 9.50006C16.4258 9.36805 16.3997 9.23734 16.3489 9.11548C16.2981 8.99362 16.2237 8.88302 16.13 8.79006C15.9426 8.60381 15.6892 8.49927 15.425 8.49927C15.1608 8.49927 14.9074 8.60381 14.72 8.79006Z" fill="#8EA5C0"></path>
                                            </svg>
                                        </div>
                                        Express Trades
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slick-slide" data-slick-index="3" aria-hidden="true" style="width: 430px;" tabindex="-1" role="tabpanel" id="slick-slide03" aria-describedby="slick-slide-control03">
                        <div>
                            <div class="levels__item  levels__item--vip" style="width: 100%; display: inline-block;">
                                <div class="levels__item-label">
                                    VIP <div class="levels__item-label-icon">
                                        <svg width="20" height="12" viewBox="0 0 20 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M3.01082 3.34995L5.01444 5.29855H3.99526L1.99164 3.34995H3.01082Z" fill="#715D44"></path>
                                            <path d="M6.37379 3.34995L8.37741 5.29855H7.36981L5.36475 3.34995H6.37379Z" fill="#715D44"></path>
                                            <path d="M5.36472 3.34995L7.36979 5.29855L6.41286 7.34705L4.40924 5.39844L5.36472 3.34995Z" fill="#715D44"></path>
                                            <path d="M1.99164 3.34995L3.99526 5.29855L5.4473 9.34777L3.44368 7.39917L1.99164 3.34995Z" fill="#715D44"></path>
                                            <mask id="mask0_29_66" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="4" y="5" width="3" height="4">
                                                <path d="M4.40926 5.39853L6.41288 7.34714C6.35353 7.47743 6.29272 7.60917 6.22903 7.73946C6.16533 7.8712 6.10307 8.00439 6.04372 8.13903L4.0401 6.19042C4.09946 6.05434 4.16171 5.9226 4.22541 5.79086C4.2891 5.66056 4.34991 5.52882 4.40926 5.39853Z" fill="white"></path>
                                            </mask>
                                            <g mask="url(#mask0_29_66)">
                                                <path d="M4.40926 5.39853L6.41288 7.34714C6.35353 7.47743 6.29272 7.60917 6.22903 7.73946C6.16533 7.8712 6.10307 8.00439 6.04372 8.13903L4.0401 6.19042C4.09946 6.05434 4.16171 5.9226 4.22541 5.79086C4.2891 5.66056 4.34991 5.52882 4.40926 5.39853Z" fill="#715D44"></path>
                                            </g>
                                            <mask id="mask1_29_66" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="3" y="6" width="4" height="3">
                                                <path d="M4.04018 6.19183L6.0438 8.14044C6.02787 8.17374 6.0134 8.20703 5.99892 8.24178L3.9953 6.29317C4.00978 6.25988 4.02425 6.22513 4.04018 6.19183Z" fill="white"></path>
                                            </mask>
                                            <g mask="url(#mask1_29_66)">
                                                <path d="M4.04018 6.19183L6.0438 8.14044C6.02787 8.17374 6.0134 8.20703 5.99892 8.24178L3.9953 6.29317C4.00978 6.25988 4.02425 6.22513 4.04018 6.19183Z" fill="#715D44"></path>
                                            </g>
                                            <path d="M7.42041 3.34995L9.42547 5.29855L9.36177 6.14835L7.35815 4.20119L7.42041 3.34995Z" fill="#715D44"></path>
                                            <mask id="mask2_29_66" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="0" y="9" width="5" height="3">
                                                <path d="M2.66475 11.3833L0.661133 9.43466C1.05491 9.8183 1.5978 10.0514 2.21162 10.0514L4.21524 12C3.60141 12 3.05853 11.7669 2.66475 11.3833Z" fill="white"></path>
                                            </mask>
                                            <g mask="url(#mask2_29_66)">
                                                <path d="M2.21162 10.0514L4.21524 12C3.60141 12 3.05853 11.7669 2.66475 11.3833L0.661133 9.43466C1.05491 9.8183 1.5978 10.0514 2.21162 10.0514Z" fill="#715D44"></path>
                                            </g>
                                            <path d="M10.2116 3.34995L12.2166 5.29855H9.42548L7.42041 3.34995H10.2116Z" fill="#715D44"></path>
                                            <path d="M7.18449 6.55084L9.18811 8.498L9.12585 9.3478L7.12079 7.3992L7.18449 6.55084Z" fill="#715D44"></path>
                                            <path d="M8.08781 6.55084L10.0914 8.498H9.18807L7.18445 6.55084H8.08781Z" fill="#715D44"></path>
                                            <path d="M8.26004 4.20119L10.2651 6.14835L10.0914 8.49797L8.08777 6.55081L8.26004 4.20119Z" fill="#715D44"></path>
                                            <path d="M9.97556 6.55084L11.9806 8.498H11.0715L9.06641 6.55084H9.97556Z" fill="#715D44"></path>
                                            <path d="M13.9366 3.34995L15.9402 5.29855H13.8048L11.7997 3.34995H13.9366Z" fill="#715D44"></path>
                                            <mask id="mask3_29_66" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="13" y="3" width="4" height="3">
                                                <path d="M14.8805 3.71042L16.8841 5.65903C16.8103 5.58809 16.7278 5.52584 16.6351 5.47228C16.4354 5.35646 16.2052 5.29855 15.9388 5.29855L13.9352 3.34995C14.2001 3.34995 14.4317 3.40785 14.6315 3.52367C14.7242 3.57723 14.8067 3.63949 14.8805 3.71042Z" fill="white"></path>
                                            </mask>
                                            <g mask="url(#mask3_29_66)">
                                                <path d="M14.8805 3.71042L16.8841 5.65903C16.8103 5.58809 16.7278 5.52584 16.6351 5.47228C16.4354 5.35646 16.2052 5.29855 15.9388 5.29855L13.9352 3.34995C14.2001 3.34995 14.4317 3.40785 14.6315 3.52367C14.7242 3.57723 14.8067 3.63949 14.8805 3.71042Z" fill="#715D44"></path>
                                            </g>
                                            <path d="M11.7997 3.34995L13.8048 5.29855L13.5051 9.34777L11.5015 7.39917L11.7997 3.34995Z" fill="#715D44"></path>
                                            <path d="M13.5732 5.21892L15.5783 7.16752H14.6272L12.6235 5.21892H13.5732Z" fill="#715D44"></path>
                                            <mask id="mask4_29_66" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="13" y="5" width="3" height="3">
                                                <path d="M13.8787 5.14944L15.8823 7.09805C15.7911 7.14438 15.6897 7.16754 15.5783 7.16754L13.5746 5.21893C13.6861 5.21893 13.7875 5.19577 13.8787 5.14944Z" fill="white"></path>
                                            </mask>
                                            <g mask="url(#mask4_29_66)">
                                                <path d="M13.8787 5.14944L15.8823 7.09805C15.7911 7.14438 15.6897 7.16754 15.5783 7.16754L13.5746 5.21893C13.6861 5.21893 13.7875 5.19577 13.8787 5.14944Z" fill="#715D44"></path>
                                            </g>
                                            <mask id="mask5_29_66" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="14" y="4" width="3" height="3">
                                                <path d="M14.0959 4.37497L16.0995 6.32358C16.1198 6.3424 16.1371 6.36411 16.1531 6.38727C16.2023 6.45676 16.2226 6.54073 16.2139 6.64207C16.2052 6.74341 16.1733 6.83317 16.114 6.91424L14.1104 4.96563C14.1697 4.88456 14.203 4.7948 14.2103 4.69346C14.2175 4.59212 14.1972 4.50816 14.1495 4.43867C14.1335 4.41551 14.1162 4.39524 14.0959 4.37497Z" fill="white"></path>
                                            </mask>
                                            <g mask="url(#mask5_29_66)">
                                                <path d="M14.0959 4.37497L16.0995 6.32358C16.1198 6.3424 16.1371 6.36411 16.1531 6.38727C16.2023 6.45676 16.2226 6.54073 16.2139 6.64207C16.2052 6.74341 16.1733 6.83317 16.114 6.91424L14.1104 4.96563C14.1697 4.88456 14.203 4.7948 14.2103 4.69346C14.2175 4.59212 14.1972 4.50816 14.1495 4.43867C14.1335 4.41551 14.1162 4.39524 14.0959 4.37497Z" fill="#715D44"></path>
                                            </g>
                                            <path d="M13.647 4.21864C13.7585 4.21864 13.8569 4.23746 13.9423 4.27655C14.0321 4.31419 14.1016 4.36776 14.1493 4.43869C14.1986 4.50818 14.2188 4.59215 14.2101 4.69349C14.2015 4.79483 14.1696 4.88459 14.1102 4.96566C14.0509 5.04239 13.9742 5.10464 13.8772 5.15096C13.786 5.19729 13.6846 5.22045 13.5731 5.22045H12.622L12.6958 4.22009H13.647V4.21864Z" fill="#F0D88B"></path>
                                            <mask id="mask6_29_66" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="13" y="4" width="4" height="4">
                                                <path d="M14.1102 4.96413L16.1138 6.91273C16.0545 6.98946 15.9778 7.05171 15.8808 7.09804L13.8771 5.14943C13.9727 5.10311 14.0509 5.04085 14.1102 4.96413Z" fill="white"></path>
                                            </mask>
                                            <g mask="url(#mask6_29_66)">
                                                <path d="M14.1102 4.96413L16.1138 6.91273C16.0545 6.98946 15.9778 7.05171 15.8808 7.09804L13.8771 5.14943C13.9727 5.10311 14.0509 5.04085 14.1102 4.96413Z" fill="#715D44"></path>
                                            </g>
                                            <mask id="mask7_29_66" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="16" y="0" width="4" height="5">
                                                <path d="M16.4122 0.615265L18.4158 2.56387C18.866 3.00253 19.1223 3.63517 19.0702 4.34165L17.0665 2.39304C17.1186 1.68801 16.8624 1.05392 16.4122 0.615265Z" fill="white"></path>
                                            </mask>
                                            <g mask="url(#mask7_29_66)">
                                                <path d="M16.4122 0.615265L18.4158 2.56387C18.866 3.00253 19.1223 3.63517 19.0702 4.34165L17.0665 2.39304C17.1186 1.68801 16.8624 1.05392 16.4122 0.615265Z" fill="#715D44"></path>
                                            </g>
                                            <path d="M14.1205 10.0514L16.1241 12H4.21529L2.21167 10.0514H14.1205Z" fill="#715D44"></path>
                                            <path d="M17.0678 2.39304L19.0715 4.34164L18.6835 9.60549L16.6784 7.65833L17.0678 2.39304Z" fill="#715D44"></path>
                                            <mask id="mask8_29_66" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="14" y="7" width="5" height="5">
                                                <path d="M16.6785 7.65834L18.6821 9.60695C18.5851 10.9301 17.44 12 16.1226 12L14.119 10.0514C15.4349 10.0514 16.5801 8.98009 16.6785 7.65834Z" fill="white"></path>
                                            </mask>
                                            <g mask="url(#mask8_29_66)">
                                                <path d="M16.6785 7.65834L18.6821 9.60695C18.5851 10.9301 17.44 12 16.1226 12L14.119 10.0514C15.4349 10.0514 16.5801 8.98009 16.6785 7.65834Z" fill="#715D44"></path>
                                            </g>
                                            <path d="M14.8616 0C16.1776 0 17.1649 1.0713 17.0665 2.39305L16.677 7.65689C16.58 8.98009 15.4349 10.0499 14.1175 10.0499H2.21161C0.897094 10.0499 -0.0916861 8.97865 0.00675766 7.65689L0.394742 2.39305C0.491738 1.0713 1.63832 0 2.95428 0H14.8631H14.8616ZM4.34118 7.3992L6.37375 3.34853H5.36615L4.40922 5.39703C4.34986 5.52733 4.28906 5.65907 4.22536 5.78936C4.16166 5.9211 4.09941 6.05429 4.04005 6.18892C4.02413 6.22222 4.00965 6.25552 3.99518 6.29026L3.98794 6.26421C3.95754 6.15708 3.92424 6.05284 3.88949 5.9515C3.85909 5.84727 3.82579 5.74882 3.79105 5.65762C3.7563 5.56062 3.7259 5.47086 3.69984 5.39124L3.01074 3.34853H1.99156L3.4436 7.3992H4.34118ZM14.9832 5.39848C15.1294 5.18567 15.212 4.94535 15.2322 4.67463C15.2511 4.42418 15.2062 4.19834 15.0976 3.9971C14.989 3.79732 14.8341 3.63807 14.6314 3.52226C14.4317 3.40644 14.2015 3.34853 13.9351 3.34853H11.7997L11.5001 7.3992H12.4787L12.5757 6.08035H13.6702C13.9452 6.08035 14.1986 6.0181 14.4259 5.89504C14.6546 5.77199 14.8385 5.6055 14.9818 5.39848M10.1494 4.20123L10.2116 3.34998H7.42044L7.35819 4.20123H8.26155L8.08783 6.55085H7.18446L7.12221 7.40065H9.91338L9.97563 6.55085H9.06647L9.2402 4.20123H10.1494Z" fill="url(#paint0_linear_29_66)"></path>
                                            <defs>
                                                <linearGradient id="paint0_linear_29_66" x1="8.53662" y1="3.76866" x2="8.53662" y2="10.0499" gradientUnits="userSpaceOnUse">
                                                    <stop stop-color="#FAE798"></stop>
                                                    <stop offset="1" stop-color="#B27C3E"></stop>
                                                </linearGradient>
                                            </defs>
                                        </svg>
                                    </div>
                                </div>
                                <div class="levels__item-desc">
                                    Exclusive opportunities and personalized service. </div>
                                <div class="levels__item-total-dpst">
                                    <div class="levels__item-total-dpst-title">
                                        Total Deposits </div>
                                    <div class="levels__item-total-dpst-number">
                                        $10,000 + </div>
                                </div>
                                <div class="levels__item-oblock oblock">
                                    <div class="oblock__icon">
                                        <svg width="49" height="48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M29.788 24.988a11.277 11.277 0 0 0 3.897-8.527 11.461 11.461 0 0 0-22.923 0 11.277 11.277 0 0 0 4.012 8.527A18.339 18.339 0 0 0 4 41.676a2.292 2.292 0 1 0 4.585 0 13.753 13.753 0 0 1 27.507 0 2.292 2.292 0 1 0 4.584 0 18.338 18.338 0 0 0-10.888-16.688Zm-7.564-1.65a6.877 6.877 0 1 1 0-13.753 6.877 6.877 0 0 1 0 13.753Zm19.484-11.461a2.292 2.292 0 0 0-2.292 2.292v4.585a2.292 2.292 0 0 0 4.584 0v-4.585a2.292 2.292 0 0 0-2.292-2.292Zm-1.72 12.126a2.407 2.407 0 0 0-.664 1.627 2.293 2.293 0 0 0 .665 1.628c.222.202.478.365.756.481a2.156 2.156 0 0 0 1.742 0 2.062 2.062 0 0 0 1.238-1.238c.128-.272.191-.57.183-.87a2.292 2.292 0 0 0-3.92-1.628Z" fill="#895E1A"></path>
                                        </svg>
                                    </div>
                                    <div class="oblock__text">
                                        Limited membership </div>
                                    <div class="oblock__q-icon-tooltip tooltip2">
                                        <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <g opacity=".8" clip-path="url(#a6595)">
                                                <path d="M8.6 0a8 8 0 0 0-8 8 8 8 0 0 0 8 8 8 8 0 0 0 8-8 8 8 0 0 0-8-8Zm0 14.5A6.507 6.507 0 0 1 2.1 8c0-3.584 2.916-6.5 6.5-6.5s6.5 2.916 6.5 6.5-2.916 6.5-6.5 6.5Zm0-4c-.563 0-1 .438-1 1 0 .563.41 1 1 1 .534 0 1-.438 1-1 0-.563-.466-1-1-1ZM9.634 4H8.037A2.174 2.174 0 0 0 5.85 6.188c0 .406.344.75.75.75a.76.76 0 0 0 .75-.75c0-.375.284-.688.66-.688h1.596c.403 0 .744.313.744.688 0 .25-.125.44-.344.565l-1.781 1.09a.77.77 0 0 0-.375.657V9c0 .406.344.75.75.75A.76.76 0 0 0 9.35 9v-.063l1.41-.874a2.23 2.23 0 0 0 1.062-1.876C11.85 4.97 10.882 4 9.634 4Z" fill="#895E1A"></path>
                                            </g>
                                            <defs>
                                                <clipPath id="a6595">
                                                    <path fill="#fff" transform="translate(.6)" d="M0 0h16v16H0z"></path>
                                                </clipPath>
                                            </defs>
                                        </svg>
                                        <div class="tooltip-content position-left">
                                            <div class="tooltip-text">Your personal assistant will contact you upon meeting the criteria for VIP level.</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="levels__item-text">
                                    <div class="levels__item-text-1">
                                        Max trade amount </div>
                                    <div class="levels__item-text-2">
                                        $5,000 </div>
                                </div>
                                <div class="levels__item-text">
                                    <div class="levels__item-text-1">
                                        Increased payout (when available) </div>
                                    <div class="levels__item-text-2">
                                        + 6% </div>
                                </div>
                                <div class="levels__item-text">
                                    <div class="levels__item-text-1">
                                        Personal discount in the market </div>
                                    <div class="levels__item-text-2">
                                        20% </div>
                                </div>
                                <div class="levels__item-text">
                                    <div class="levels__item-text-1">
                                        <div class="levels__item-text-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M6.4443 3.6853C8.08879 2.58649 10.0222 2 12 2C13.3132 2 14.6136 2.25866 15.8268 2.7612C17.0401 3.26375 18.1425 4.00035 19.0711 4.92893C19.9997 5.85752 20.7363 6.95991 21.2388 8.17317C21.7413 9.38642 22 10.6868 22 12C22 13.9778 21.4135 15.9112 20.3147 17.5557C19.2159 19.2002 17.6541 20.4819 15.8268 21.2388C13.9996 21.9957 11.9889 22.1937 10.0491 21.8079C8.10929 21.422 6.32746 20.4696 4.92894 19.0711C3.53041 17.6725 2.578 15.8907 2.19215 13.9509C1.8063 12.0111 2.00433 10.0004 2.76121 8.17317C3.51809 6.3459 4.79981 4.78412 6.4443 3.6853ZM14.72 8.79006L10.43 13.0901L8.77999 11.4401C8.69034 11.3354 8.58002 11.2504 8.45596 11.1903C8.3319 11.1303 8.19676 11.0966 8.05904 11.0913C7.92132 11.0859 7.78399 11.1091 7.65567 11.1594C7.52734 11.2097 7.41079 11.286 7.31334 11.3834C7.21589 11.4809 7.13963 11.5974 7.08935 11.7257C7.03908 11.8541 7.01587 11.9914 7.02119 12.1291C7.02651 12.2668 7.06024 12.402 7.12027 12.526C7.18029 12.6501 7.26531 12.7604 7.36999 12.8501L9.71999 15.2101C9.81343 15.3027 9.92425 15.3761 10.0461 15.4258C10.1679 15.4756 10.2984 15.5008 10.43 15.5001C10.6923 15.499 10.9437 15.3948 11.13 15.2101L16.13 10.2101C16.2237 10.1171 16.2981 10.0065 16.3489 9.88464C16.3997 9.76278 16.4258 9.63207 16.4258 9.50006C16.4258 9.36805 16.3997 9.23734 16.3489 9.11548C16.2981 8.99362 16.2237 8.88302 16.13 8.79006C15.9426 8.60381 15.6892 8.49927 15.425 8.49927C15.1608 8.49927 14.9074 8.60381 14.72 8.79006Z" fill="#EDCC91"></path>
                                            </svg>
                                        </div>
                                        Personal assistant
                                    </div>
                                </div>
                                <div class="levels__item-text">
                                    <div class="levels__item-text-1">
                                        <div class="levels__item-text-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M6.4443 3.6853C8.08879 2.58649 10.0222 2 12 2C13.3132 2 14.6136 2.25866 15.8268 2.7612C17.0401 3.26375 18.1425 4.00035 19.0711 4.92893C19.9997 5.85752 20.7363 6.95991 21.2388 8.17317C21.7413 9.38642 22 10.6868 22 12C22 13.9778 21.4135 15.9112 20.3147 17.5557C19.2159 19.2002 17.6541 20.4819 15.8268 21.2388C13.9996 21.9957 11.9889 22.1937 10.0491 21.8079C8.10929 21.422 6.32746 20.4696 4.92894 19.0711C3.53041 17.6725 2.578 15.8907 2.19215 13.9509C1.8063 12.0111 2.00433 10.0004 2.76121 8.17317C3.51809 6.3459 4.79981 4.78412 6.4443 3.6853ZM14.72 8.79006L10.43 13.0901L8.77999 11.4401C8.69034 11.3354 8.58002 11.2504 8.45596 11.1903C8.3319 11.1303 8.19676 11.0966 8.05904 11.0913C7.92132 11.0859 7.78399 11.1091 7.65567 11.1594C7.52734 11.2097 7.41079 11.286 7.31334 11.3834C7.21589 11.4809 7.13963 11.5974 7.08935 11.7257C7.03908 11.8541 7.01587 11.9914 7.02119 12.1291C7.02651 12.2668 7.06024 12.402 7.12027 12.526C7.18029 12.6501 7.26531 12.7604 7.36999 12.8501L9.71999 15.2101C9.81343 15.3027 9.92425 15.3761 10.0461 15.4258C10.1679 15.4756 10.2984 15.5008 10.43 15.5001C10.6923 15.499 10.9437 15.3948 11.13 15.2101L16.13 10.2101C16.2237 10.1171 16.2981 10.0065 16.3489 9.88464C16.3997 9.76278 16.4258 9.63207 16.4258 9.50006C16.4258 9.36805 16.3997 9.23734 16.3489 9.11548C16.2981 8.99362 16.2237 8.88302 16.13 8.79006C15.9426 8.60381 15.6892 8.49927 15.425 8.49927C15.1608 8.49927 14.9074 8.60381 14.72 8.79006Z" fill="#EDCC91"></path>
                                            </svg>
                                        </div>
                                        Prioritized support resolutions
                                    </div>
                                </div>
                                <div class="levels__item-text">
                                    <div class="levels__item-text-1">
                                        <div class="levels__item-text-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M6.4443 3.6853C8.08879 2.58649 10.0222 2 12 2C13.3132 2 14.6136 2.25866 15.8268 2.7612C17.0401 3.26375 18.1425 4.00035 19.0711 4.92893C19.9997 5.85752 20.7363 6.95991 21.2388 8.17317C21.7413 9.38642 22 10.6868 22 12C22 13.9778 21.4135 15.9112 20.3147 17.5557C19.2159 19.2002 17.6541 20.4819 15.8268 21.2388C13.9996 21.9957 11.9889 22.1937 10.0491 21.8079C8.10929 21.422 6.32746 20.4696 4.92894 19.0711C3.53041 17.6725 2.578 15.8907 2.19215 13.9509C1.8063 12.0111 2.00433 10.0004 2.76121 8.17317C3.51809 6.3459 4.79981 4.78412 6.4443 3.6853ZM14.72 8.79006L10.43 13.0901L8.77999 11.4401C8.69034 11.3354 8.58002 11.2504 8.45596 11.1903C8.3319 11.1303 8.19676 11.0966 8.05904 11.0913C7.92132 11.0859 7.78399 11.1091 7.65567 11.1594C7.52734 11.2097 7.41079 11.286 7.31334 11.3834C7.21589 11.4809 7.13963 11.5974 7.08935 11.7257C7.03908 11.8541 7.01587 11.9914 7.02119 12.1291C7.02651 12.2668 7.06024 12.402 7.12027 12.526C7.18029 12.6501 7.26531 12.7604 7.36999 12.8501L9.71999 15.2101C9.81343 15.3027 9.92425 15.3761 10.0461 15.4258C10.1679 15.4756 10.2984 15.5008 10.43 15.5001C10.6923 15.499 10.9437 15.3948 11.13 15.2101L16.13 10.2101C16.2237 10.1171 16.2981 10.0065 16.3489 9.88464C16.3997 9.76278 16.4258 9.63207 16.4258 9.50006C16.4258 9.36805 16.3997 9.23734 16.3489 9.11548C16.2981 8.99362 16.2237 8.88302 16.13 8.79006C15.9426 8.60381 15.6892 8.49927 15.425 8.49927C15.1608 8.49927 14.9074 8.60381 14.72 8.79006Z" fill="#EDCC91"></path>
                                            </svg>
                                        </div>
                                        Express Trades
                                    </div>
                                </div>
                                <div class="levels__item-text">
                                    <div class="levels__item-text-1">
                                        <div class="levels__item-text-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M6.4443 3.6853C8.08879 2.58649 10.0222 2 12 2C13.3132 2 14.6136 2.25866 15.8268 2.7612C17.0401 3.26375 18.1425 4.00035 19.0711 4.92893C19.9997 5.85752 20.7363 6.95991 21.2388 8.17317C21.7413 9.38642 22 10.6868 22 12C22 13.9778 21.4135 15.9112 20.3147 17.5557C19.2159 19.2002 17.6541 20.4819 15.8268 21.2388C13.9996 21.9957 11.9889 22.1937 10.0491 21.8079C8.10929 21.422 6.32746 20.4696 4.92894 19.0711C3.53041 17.6725 2.578 15.8907 2.19215 13.9509C1.8063 12.0111 2.00433 10.0004 2.76121 8.17317C3.51809 6.3459 4.79981 4.78412 6.4443 3.6853ZM14.72 8.79006L10.43 13.0901L8.77999 11.4401C8.69034 11.3354 8.58002 11.2504 8.45596 11.1903C8.3319 11.1303 8.19676 11.0966 8.05904 11.0913C7.92132 11.0859 7.78399 11.1091 7.65567 11.1594C7.52734 11.2097 7.41079 11.286 7.31334 11.3834C7.21589 11.4809 7.13963 11.5974 7.08935 11.7257C7.03908 11.8541 7.01587 11.9914 7.02119 12.1291C7.02651 12.2668 7.06024 12.402 7.12027 12.526C7.18029 12.6501 7.26531 12.7604 7.36999 12.8501L9.71999 15.2101C9.81343 15.3027 9.92425 15.3761 10.0461 15.4258C10.1679 15.4756 10.2984 15.5008 10.43 15.5001C10.6923 15.499 10.9437 15.3948 11.13 15.2101L16.13 10.2101C16.2237 10.1171 16.2981 10.0065 16.3489 9.88464C16.3997 9.76278 16.4258 9.63207 16.4258 9.50006C16.4258 9.36805 16.3997 9.23734 16.3489 9.11548C16.2981 8.99362 16.2237 8.88302 16.13 8.79006C15.9426 8.60381 15.6892 8.49927 15.425 8.49927C15.1608 8.49927 14.9074 8.60381 14.72 8.79006Z" fill="#EDCC91"></path>
                                            </svg>
                                        </div>
                                        Prioritized withdrawals
                                    </div>
                                </div>
                                <div class="levels__item-text">
                                    <div class="levels__item-text-1">
                                        <div class="levels__item-text-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M6.4443 3.6853C8.08879 2.58649 10.0222 2 12 2C13.3132 2 14.6136 2.25866 15.8268 2.7612C17.0401 3.26375 18.1425 4.00035 19.0711 4.92893C19.9997 5.85752 20.7363 6.95991 21.2388 8.17317C21.7413 9.38642 22 10.6868 22 12C22 13.9778 21.4135 15.9112 20.3147 17.5557C19.2159 19.2002 17.6541 20.4819 15.8268 21.2388C13.9996 21.9957 11.9889 22.1937 10.0491 21.8079C8.10929 21.422 6.32746 20.4696 4.92894 19.0711C3.53041 17.6725 2.578 15.8907 2.19215 13.9509C1.8063 12.0111 2.00433 10.0004 2.76121 8.17317C3.51809 6.3459 4.79981 4.78412 6.4443 3.6853ZM14.72 8.79006L10.43 13.0901L8.77999 11.4401C8.69034 11.3354 8.58002 11.2504 8.45596 11.1903C8.3319 11.1303 8.19676 11.0966 8.05904 11.0913C7.92132 11.0859 7.78399 11.1091 7.65567 11.1594C7.52734 11.2097 7.41079 11.286 7.31334 11.3834C7.21589 11.4809 7.13963 11.5974 7.08935 11.7257C7.03908 11.8541 7.01587 11.9914 7.02119 12.1291C7.02651 12.2668 7.06024 12.402 7.12027 12.526C7.18029 12.6501 7.26531 12.7604 7.36999 12.8501L9.71999 15.2101C9.81343 15.3027 9.92425 15.3761 10.0461 15.4258C10.1679 15.4756 10.2984 15.5008 10.43 15.5001C10.6923 15.499 10.9437 15.3948 11.13 15.2101L16.13 10.2101C16.2237 10.1171 16.2981 10.0065 16.3489 9.88464C16.3997 9.76278 16.4258 9.63207 16.4258 9.50006C16.4258 9.36805 16.3997 9.23734 16.3489 9.11548C16.2981 8.99362 16.2237 8.88302 16.13 8.79006C15.9426 8.60381 15.6892 8.49927 15.425 8.49927C15.1608 8.49927 14.9074 8.60381 14.72 8.79006Z" fill="#EDCC91"></path>
                                            </svg>
                                        </div>
                                        VIP badge
                                    </div>
                                </div>
                                <div class="levels__item-text">
                                    <div class="levels__item-text-1">
                                        <div class="levels__item-text-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M6.4443 3.6853C8.08879 2.58649 10.0222 2 12 2C13.3132 2 14.6136 2.25866 15.8268 2.7612C17.0401 3.26375 18.1425 4.00035 19.0711 4.92893C19.9997 5.85752 20.7363 6.95991 21.2388 8.17317C21.7413 9.38642 22 10.6868 22 12C22 13.9778 21.4135 15.9112 20.3147 17.5557C19.2159 19.2002 17.6541 20.4819 15.8268 21.2388C13.9996 21.9957 11.9889 22.1937 10.0491 21.8079C8.10929 21.422 6.32746 20.4696 4.92894 19.0711C3.53041 17.6725 2.578 15.8907 2.19215 13.9509C1.8063 12.0111 2.00433 10.0004 2.76121 8.17317C3.51809 6.3459 4.79981 4.78412 6.4443 3.6853ZM14.72 8.79006L10.43 13.0901L8.77999 11.4401C8.69034 11.3354 8.58002 11.2504 8.45596 11.1903C8.3319 11.1303 8.19676 11.0966 8.05904 11.0913C7.92132 11.0859 7.78399 11.1091 7.65567 11.1594C7.52734 11.2097 7.41079 11.286 7.31334 11.3834C7.21589 11.4809 7.13963 11.5974 7.08935 11.7257C7.03908 11.8541 7.01587 11.9914 7.02119 12.1291C7.02651 12.2668 7.06024 12.402 7.12027 12.526C7.18029 12.6501 7.26531 12.7604 7.36999 12.8501L9.71999 15.2101C9.81343 15.3027 9.92425 15.3761 10.0461 15.4258C10.1679 15.4756 10.2984 15.5008 10.43 15.5001C10.6923 15.499 10.9437 15.3948 11.13 15.2101L16.13 10.2101C16.2237 10.1171 16.2981 10.0065 16.3489 9.88464C16.3997 9.76278 16.4258 9.63207 16.4258 9.50006C16.4258 9.36805 16.3997 9.23734 16.3489 9.11548C16.2981 8.99362 16.2237 8.88302 16.13 8.79006C15.9426 8.60381 15.6892 8.49927 15.425 8.49927C15.1608 8.49927 14.9074 8.60381 14.72 8.79006Z" fill="#EDCC91"></path>
                                            </svg>
                                        </div>
                                        Private VIP chat room
                                    </div>
                                </div>
                                <div class="levels__item-text">
                                    <div class="levels__item-text-1">
                                        <div class="levels__item-text-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M6.4443 3.6853C8.08879 2.58649 10.0222 2 12 2C13.3132 2 14.6136 2.25866 15.8268 2.7612C17.0401 3.26375 18.1425 4.00035 19.0711 4.92893C19.9997 5.85752 20.7363 6.95991 21.2388 8.17317C21.7413 9.38642 22 10.6868 22 12C22 13.9778 21.4135 15.9112 20.3147 17.5557C19.2159 19.2002 17.6541 20.4819 15.8268 21.2388C13.9996 21.9957 11.9889 22.1937 10.0491 21.8079C8.10929 21.422 6.32746 20.4696 4.92894 19.0711C3.53041 17.6725 2.578 15.8907 2.19215 13.9509C1.8063 12.0111 2.00433 10.0004 2.76121 8.17317C3.51809 6.3459 4.79981 4.78412 6.4443 3.6853ZM14.72 8.79006L10.43 13.0901L8.77999 11.4401C8.69034 11.3354 8.58002 11.2504 8.45596 11.1903C8.3319 11.1303 8.19676 11.0966 8.05904 11.0913C7.92132 11.0859 7.78399 11.1091 7.65567 11.1594C7.52734 11.2097 7.41079 11.286 7.31334 11.3834C7.21589 11.4809 7.13963 11.5974 7.08935 11.7257C7.03908 11.8541 7.01587 11.9914 7.02119 12.1291C7.02651 12.2668 7.06024 12.402 7.12027 12.526C7.18029 12.6501 7.26531 12.7604 7.36999 12.8501L9.71999 15.2101C9.81343 15.3027 9.92425 15.3761 10.0461 15.4258C10.1679 15.4756 10.2984 15.5008 10.43 15.5001C10.6923 15.499 10.9437 15.3948 11.13 15.2101L16.13 10.2101C16.2237 10.1171 16.2981 10.0065 16.3489 9.88464C16.3997 9.76278 16.4258 9.63207 16.4258 9.50006C16.4258 9.36805 16.3997 9.23734 16.3489 9.11548C16.2981 8.99362 16.2237 8.88302 16.13 8.79006C15.9426 8.60381 15.6892 8.49927 15.425 8.49927C15.1608 8.49927 14.9074 8.60381 14.72 8.79006Z" fill="#EDCC91"></path>
                                            </svg>
                                        </div>
                                        Educational webinars
                                    </div>
                                </div>
                                <div class="levels__item-text">
                                    <div class="levels__item-text-1">
                                        <div class="levels__item-text-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M6.4443 3.6853C8.08879 2.58649 10.0222 2 12 2C13.3132 2 14.6136 2.25866 15.8268 2.7612C17.0401 3.26375 18.1425 4.00035 19.0711 4.92893C19.9997 5.85752 20.7363 6.95991 21.2388 8.17317C21.7413 9.38642 22 10.6868 22 12C22 13.9778 21.4135 15.9112 20.3147 17.5557C19.2159 19.2002 17.6541 20.4819 15.8268 21.2388C13.9996 21.9957 11.9889 22.1937 10.0491 21.8079C8.10929 21.422 6.32746 20.4696 4.92894 19.0711C3.53041 17.6725 2.578 15.8907 2.19215 13.9509C1.8063 12.0111 2.00433 10.0004 2.76121 8.17317C3.51809 6.3459 4.79981 4.78412 6.4443 3.6853ZM14.72 8.79006L10.43 13.0901L8.77999 11.4401C8.69034 11.3354 8.58002 11.2504 8.45596 11.1903C8.3319 11.1303 8.19676 11.0966 8.05904 11.0913C7.92132 11.0859 7.78399 11.1091 7.65567 11.1594C7.52734 11.2097 7.41079 11.286 7.31334 11.3834C7.21589 11.4809 7.13963 11.5974 7.08935 11.7257C7.03908 11.8541 7.01587 11.9914 7.02119 12.1291C7.02651 12.2668 7.06024 12.402 7.12027 12.526C7.18029 12.6501 7.26531 12.7604 7.36999 12.8501L9.71999 15.2101C9.81343 15.3027 9.92425 15.3761 10.0461 15.4258C10.1679 15.4756 10.2984 15.5008 10.43 15.5001C10.6923 15.499 10.9437 15.3948 11.13 15.2101L16.13 10.2101C16.2237 10.1171 16.2981 10.0065 16.3489 9.88464C16.3997 9.76278 16.4258 9.63207 16.4258 9.50006C16.4258 9.36805 16.3997 9.23734 16.3489 9.11548C16.2981 8.99362 16.2237 8.88302 16.13 8.79006C15.9426 8.60381 15.6892 8.49927 15.425 8.49927C15.1608 8.49927 14.9074 8.60381 14.72 8.79006Z" fill="#EDCC91"></path>
                                            </svg>
                                        </div>
                                        Exclusive offers and benefits*
                                    </div>
                                </div>
                                <div class="levels__item-text">
                                    <div class="levels__item-text-1">
                                        <div class="levels__item-text-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M6.4443 3.6853C8.08879 2.58649 10.0222 2 12 2C13.3132 2 14.6136 2.25866 15.8268 2.7612C17.0401 3.26375 18.1425 4.00035 19.0711 4.92893C19.9997 5.85752 20.7363 6.95991 21.2388 8.17317C21.7413 9.38642 22 10.6868 22 12C22 13.9778 21.4135 15.9112 20.3147 17.5557C19.2159 19.2002 17.6541 20.4819 15.8268 21.2388C13.9996 21.9957 11.9889 22.1937 10.0491 21.8079C8.10929 21.422 6.32746 20.4696 4.92894 19.0711C3.53041 17.6725 2.578 15.8907 2.19215 13.9509C1.8063 12.0111 2.00433 10.0004 2.76121 8.17317C3.51809 6.3459 4.79981 4.78412 6.4443 3.6853ZM14.72 8.79006L10.43 13.0901L8.77999 11.4401C8.69034 11.3354 8.58002 11.2504 8.45596 11.1903C8.3319 11.1303 8.19676 11.0966 8.05904 11.0913C7.92132 11.0859 7.78399 11.1091 7.65567 11.1594C7.52734 11.2097 7.41079 11.286 7.31334 11.3834C7.21589 11.4809 7.13963 11.5974 7.08935 11.7257C7.03908 11.8541 7.01587 11.9914 7.02119 12.1291C7.02651 12.2668 7.06024 12.402 7.12027 12.526C7.18029 12.6501 7.26531 12.7604 7.36999 12.8501L9.71999 15.2101C9.81343 15.3027 9.92425 15.3761 10.0461 15.4258C10.1679 15.4756 10.2984 15.5008 10.43 15.5001C10.6923 15.499 10.9437 15.3948 11.13 15.2101L16.13 10.2101C16.2237 10.1171 16.2981 10.0065 16.3489 9.88464C16.3997 9.76278 16.4258 9.63207 16.4258 9.50006C16.4258 9.36805 16.3997 9.23734 16.3489 9.11548C16.2981 8.99362 16.2237 8.88302 16.13 8.79006C15.9426 8.60381 15.6892 8.49927 15.425 8.49927C15.1608 8.49927 14.9074 8.60381 14.72 8.79006Z" fill="#EDCC91"></path>
                                            </svg>
                                        </div>
                                        Cashback*
                                    </div>
                                </div>
                                <div class="levels__item-text">
                                    <div class="levels__item-text-1">
                                        <div class="levels__item-text-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M6.4443 3.6853C8.08879 2.58649 10.0222 2 12 2C13.3132 2 14.6136 2.25866 15.8268 2.7612C17.0401 3.26375 18.1425 4.00035 19.0711 4.92893C19.9997 5.85752 20.7363 6.95991 21.2388 8.17317C21.7413 9.38642 22 10.6868 22 12C22 13.9778 21.4135 15.9112 20.3147 17.5557C19.2159 19.2002 17.6541 20.4819 15.8268 21.2388C13.9996 21.9957 11.9889 22.1937 10.0491 21.8079C8.10929 21.422 6.32746 20.4696 4.92894 19.0711C3.53041 17.6725 2.578 15.8907 2.19215 13.9509C1.8063 12.0111 2.00433 10.0004 2.76121 8.17317C3.51809 6.3459 4.79981 4.78412 6.4443 3.6853ZM14.72 8.79006L10.43 13.0901L8.77999 11.4401C8.69034 11.3354 8.58002 11.2504 8.45596 11.1903C8.3319 11.1303 8.19676 11.0966 8.05904 11.0913C7.92132 11.0859 7.78399 11.1091 7.65567 11.1594C7.52734 11.2097 7.41079 11.286 7.31334 11.3834C7.21589 11.4809 7.13963 11.5974 7.08935 11.7257C7.03908 11.8541 7.01587 11.9914 7.02119 12.1291C7.02651 12.2668 7.06024 12.402 7.12027 12.526C7.18029 12.6501 7.26531 12.7604 7.36999 12.8501L9.71999 15.2101C9.81343 15.3027 9.92425 15.3761 10.0461 15.4258C10.1679 15.4756 10.2984 15.5008 10.43 15.5001C10.6923 15.499 10.9437 15.3948 11.13 15.2101L16.13 10.2101C16.2237 10.1171 16.2981 10.0065 16.3489 9.88464C16.3997 9.76278 16.4258 9.63207 16.4258 9.50006C16.4258 9.36805 16.3997 9.23734 16.3489 9.11548C16.2981 8.99362 16.2237 8.88302 16.13 8.79006C15.9426 8.60381 15.6892 8.49927 15.425 8.49927C15.1608 8.49927 14.9074 8.60381 14.72 8.79006Z" fill="#EDCC91"></path>
                                            </svg>
                                        </div>
                                        Upgrade your friend's level to VIP for 1 month*
                                    </div>
                                </div>
                                <div class="levels__item-text">
                                    <div class="levels__item-text-1">
                                        <div class="levels__item-text-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M6.4443 3.6853C8.08879 2.58649 10.0222 2 12 2C13.3132 2 14.6136 2.25866 15.8268 2.7612C17.0401 3.26375 18.1425 4.00035 19.0711 4.92893C19.9997 5.85752 20.7363 6.95991 21.2388 8.17317C21.7413 9.38642 22 10.6868 22 12C22 13.9778 21.4135 15.9112 20.3147 17.5557C19.2159 19.2002 17.6541 20.4819 15.8268 21.2388C13.9996 21.9957 11.9889 22.1937 10.0491 21.8079C8.10929 21.422 6.32746 20.4696 4.92894 19.0711C3.53041 17.6725 2.578 15.8907 2.19215 13.9509C1.8063 12.0111 2.00433 10.0004 2.76121 8.17317C3.51809 6.3459 4.79981 4.78412 6.4443 3.6853ZM14.72 8.79006L10.43 13.0901L8.77999 11.4401C8.69034 11.3354 8.58002 11.2504 8.45596 11.1903C8.3319 11.1303 8.19676 11.0966 8.05904 11.0913C7.92132 11.0859 7.78399 11.1091 7.65567 11.1594C7.52734 11.2097 7.41079 11.286 7.31334 11.3834C7.21589 11.4809 7.13963 11.5974 7.08935 11.7257C7.03908 11.8541 7.01587 11.9914 7.02119 12.1291C7.02651 12.2668 7.06024 12.402 7.12027 12.526C7.18029 12.6501 7.26531 12.7604 7.36999 12.8501L9.71999 15.2101C9.81343 15.3027 9.92425 15.3761 10.0461 15.4258C10.1679 15.4756 10.2984 15.5008 10.43 15.5001C10.6923 15.499 10.9437 15.3948 11.13 15.2101L16.13 10.2101C16.2237 10.1171 16.2981 10.0065 16.3489 9.88464C16.3997 9.76278 16.4258 9.63207 16.4258 9.50006C16.4258 9.36805 16.3997 9.23734 16.3489 9.11548C16.2981 8.99362 16.2237 8.88302 16.13 8.79006C15.9426 8.60381 15.6892 8.49927 15.425 8.49927C15.1608 8.49927 14.9074 8.60381 14.72 8.79006Z" fill="#EDCC91"></path>
                                            </svg>
                                        </div>
                                        Free participation in tournaments*
                                    </div>
                                </div>
                                <div class="levels__item-text">
                                    <div class="levels__item-text-1">
                                        <div class="levels__item-text-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M6.4443 3.6853C8.08879 2.58649 10.0222 2 12 2C13.3132 2 14.6136 2.25866 15.8268 2.7612C17.0401 3.26375 18.1425 4.00035 19.0711 4.92893C19.9997 5.85752 20.7363 6.95991 21.2388 8.17317C21.7413 9.38642 22 10.6868 22 12C22 13.9778 21.4135 15.9112 20.3147 17.5557C19.2159 19.2002 17.6541 20.4819 15.8268 21.2388C13.9996 21.9957 11.9889 22.1937 10.0491 21.8079C8.10929 21.422 6.32746 20.4696 4.92894 19.0711C3.53041 17.6725 2.578 15.8907 2.19215 13.9509C1.8063 12.0111 2.00433 10.0004 2.76121 8.17317C3.51809 6.3459 4.79981 4.78412 6.4443 3.6853ZM14.72 8.79006L10.43 13.0901L8.77999 11.4401C8.69034 11.3354 8.58002 11.2504 8.45596 11.1903C8.3319 11.1303 8.19676 11.0966 8.05904 11.0913C7.92132 11.0859 7.78399 11.1091 7.65567 11.1594C7.52734 11.2097 7.41079 11.286 7.31334 11.3834C7.21589 11.4809 7.13963 11.5974 7.08935 11.7257C7.03908 11.8541 7.01587 11.9914 7.02119 12.1291C7.02651 12.2668 7.06024 12.402 7.12027 12.526C7.18029 12.6501 7.26531 12.7604 7.36999 12.8501L9.71999 15.2101C9.81343 15.3027 9.92425 15.3761 10.0461 15.4258C10.1679 15.4756 10.2984 15.5008 10.43 15.5001C10.6923 15.499 10.9437 15.3948 11.13 15.2101L16.13 10.2101C16.2237 10.1171 16.2981 10.0065 16.3489 9.88464C16.3997 9.76278 16.4258 9.63207 16.4258 9.50006C16.4258 9.36805 16.3997 9.23734 16.3489 9.11548C16.2981 8.99362 16.2237 8.88302 16.13 8.79006C15.9426 8.60381 15.6892 8.49927 15.425 8.49927C15.1608 8.49927 14.9074 8.60381 14.72 8.79006Z" fill="#EDCC91"></path>
                                            </svg>
                                        </div>
                                        Prioritized access to new platform features*
                                    </div>
                                </div>
                                <div class="levels__item-text-divider"></div>
                                <div class="levels__item-text">
                                    <div class="levels__item-text-1">
                                        <div class="levels__item-text-asterisk">*</div>
                                        For more information, contact your personal assistant.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slick-slide" data-slick-index="4" aria-hidden="true" style="width: 430px;" tabindex="-1" role="tabpanel" id="slick-slide04">
                        <div>
                            <div class="levels__item  levels__item--vip-elite" style="width: 100%; display: inline-block;">
                                <div class="levels__item-label">
                                    VIP Elite <div class="levels__item-label-icon">
                                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <g clip-path="url(#clip0_546_322261176)">
                                                <mask id="mask0_546_322261176" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="2" y="6" width="4" height="5">
                                                    <path d="M2.6853 6.91541L5.89474 10.0356C5.89474 10.0356 5.91886 10.0597 5.9285 10.0718L2.71906 6.95157C2.707 6.93952 2.69736 6.92746 2.6853 6.91541Z" fill="white"></path>
                                                </mask>
                                                <g mask="url(#mask0_546_322261176)">
                                                    <path d="M2.6853 6.91541L5.89474 10.0356C5.89474 10.0356 5.91886 10.0597 5.9285 10.0718L2.71906 6.95157C2.707 6.93952 2.69736 6.92746 2.6853 6.91541Z" fill="#786A43"></path>
                                                </g>
                                                <mask id="mask1_546_322261176" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="15" y="2" width="5" height="5">
                                                    <path d="M15.8945 2.92468L19.104 6.04491C19.1546 6.09554 19.2004 6.151 19.239 6.2137L16.0296 3.09347C15.991 3.03078 15.9452 2.97532 15.8945 2.92468Z" fill="white"></path>
                                                </mask>
                                                <g mask="url(#mask1_546_322261176)">
                                                    <path d="M15.8945 2.92468L19.104 6.04491C19.1546 6.09554 19.2004 6.151 19.239 6.2137L16.0296 3.09347C15.991 3.03078 15.9452 2.97532 15.8945 2.92468Z" fill="#786A43"></path>
                                                </g>
                                                <path d="M2.71924 6.95154L5.92868 10.0718L10.6717 15.2199L7.46227 12.0997L2.71924 6.95154Z" fill="#746640"></path>
                                                <mask id="mask2_546_322261176" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="1" y="23" width="6" height="5">
                                                    <path d="M4.3154 26.7169L1.10596 23.5967C1.78594 24.2574 2.70706 24.6263 3.69328 24.6263L6.90272 27.7465C5.9165 27.7465 4.99538 27.3776 4.3154 26.7169Z" fill="white"></path>
                                                </mask>
                                                <g mask="url(#mask2_546_322261176)">
                                                    <path d="M3.69084 24.6262L6.90028 27.7464C5.91406 27.7464 4.99294 27.3775 4.31296 26.7168L1.10352 23.5966C1.7835 24.2573 2.70462 24.6262 3.69084 24.6262Z" fill="#786A43"></path>
                                                </g>
                                                <path d="M16.0295 3.09094L19.239 6.21358L24.8501 15.2198L21.6382 12.0996L16.0295 3.09094Z" fill="#746640"></path>
                                                <mask id="mask3_546_322261176" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="28" y="6" width="5" height="5">
                                                    <path d="M28.5154 6.91785L31.7248 10.0381C31.9153 10.2237 32.019 10.4914 31.9973 10.7783L28.7879 7.65812C28.8096 7.36876 28.7059 7.10352 28.5154 6.91785Z" fill="white"></path>
                                                </mask>
                                                <g mask="url(#mask3_546_322261176)">
                                                    <path d="M28.5154 6.91785L31.7248 10.0381C31.9153 10.2237 32.019 10.4914 31.9973 10.7783L28.7879 7.65812C28.8096 7.36876 28.7059 7.10352 28.5154 6.91785Z" fill="#786A43"></path>
                                                </g>
                                                <path d="M23.56 24.6265L26.7695 27.7467H6.90277L3.69092 24.6265H23.56Z" fill="#7A6B43"></path>
                                                <path d="M28.7876 7.65796L31.997 10.7782L31.0373 23.7534L27.8279 20.6332L28.7876 7.65796Z" fill="#766842"></path>
                                                <mask id="mask4_546_322261176" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="23" y="20" width="9" height="8">
                                                    <path d="M27.8281 20.6333L31.0375 23.7535C30.9603 24.8121 30.466 25.8297 29.6655 26.5772C28.8625 27.3247 27.8232 27.7466 26.7695 27.7466L23.5601 24.6264C24.6138 24.6264 25.6555 24.2044 26.456 23.4569C27.2566 22.7094 27.7509 21.6919 27.8281 20.6333Z" fill="white"></path>
                                                </mask>
                                                <g mask="url(#mask4_546_322261176)">
                                                    <path d="M27.8281 20.6333L31.0375 23.7535C30.9603 24.8121 30.466 25.8297 29.6655 26.5772C28.8625 27.3247 27.8232 27.7466 26.7695 27.7466L23.5601 24.6264C24.6138 24.6264 25.6555 24.2044 26.456 23.4569C27.2566 22.7094 27.7509 21.6919 27.8281 20.6333Z" fill="#786A43"></path>
                                                </g>
                                                <path d="M15.2483 2.66663C15.5714 2.66663 15.8656 2.82577 16.0295 3.09101L21.6382 12.0996L27.1408 6.95151C27.3458 6.76101 27.6062 6.65974 27.8642 6.65974C27.992 6.65974 28.1198 6.68385 28.2379 6.7369C28.5972 6.89122 28.8118 7.25533 28.7829 7.66043L27.8256 20.6356C27.7485 21.6942 27.2541 22.7118 26.4536 23.4593C25.6506 24.2068 24.6113 24.6288 23.5576 24.6288H3.6909C2.63716 24.6288 1.65576 24.2068 0.966132 23.4593C0.2765 22.7118 -0.0683165 21.6942 0.0112564 20.6356L0.968543 7.66043C0.997479 7.25533 1.26754 6.89364 1.65094 6.7369C1.77874 6.68626 1.90895 6.65974 2.03675 6.65974C2.29476 6.65974 2.54071 6.76101 2.71674 6.95151L7.45976 12.0996L14.4019 3.09101C14.6069 2.82577 14.9227 2.66663 15.2483 2.66663Z" fill="#E6CB80"></path>
                                            </g>
                                            <defs>
                                                <clipPath id="clip0_546_322261176">
                                                    <rect width="32" height="32" fill="white"></rect>
                                                </clipPath>
                                            </defs>
                                        </svg>
                                    </div>
                                </div>
                                <div class="levels__item-desc">
                                    Premium service for maximum trading comfort. </div>
                                <div class="levels__item-total-dpst">
                                    <div class="levels__item-total-dpst-title">
                                        Total Deposits </div>
                                    <div class="levels__item-total-dpst-number">
                                        $100,000 + </div>
                                </div>
                                <div class="levels__item-oblock oblock">
                                    <div class="oblock__icon">
                                        <svg width="49" height="44" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M34.8 12h2v2a2 2 0 1 0 4 0v-2h2a2 2 0 1 0 0-4h-2V6a2 2 0 0 0-4 0v2h-2a2 2 0 0 0 0 4Zm8 8a2 2 0 0 0-2 2v12a2 2 0 0 1-2 2h-28a2 2 0 0 1-2-2V14.82L20.56 26.6a6 6 0 0 0 8.48 0l4.94-4.94a2.008 2.008 0 1 0-2.84-2.84l-4.94 4.94a2 2 0 0 1-2.8 0L11.62 12H26.8a2 2 0 1 0 0-4h-16a6 6 0 0 0-6 6v20a6 6 0 0 0 6 6h28a6 6 0 0 0 6-6V22a2 2 0 0 0-2-2Z" fill="#E7CB80"></path>
                                        </svg>
                                    </div>
                                    <div class="oblock__text">
                                        Special invite required </div>
                                    <div class="oblock__q-icon-tooltip tooltip2">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <g opacity=".8" clip-path="url(#a)">
                                                <path d="M8 0a8 8 0 0 0-8 8 8 8 0 0 0 8 8 8 8 0 0 0 8-8 8 8 0 0 0-8-8Zm0 14.5A6.507 6.507 0 0 1 1.5 8c0-3.584 2.915-6.5 6.5-6.5 3.584 0 6.5 2.916 6.5 6.5s-2.916 6.5-6.5 6.5Zm0-4c-.563 0-1 .438-1 1 0 .563.41 1 1 1 .534 0 1-.438 1-1 0-.563-.466-1-1-1ZM9.034 4H7.437A2.174 2.174 0 0 0 5.25 6.188c0 .406.344.75.75.75a.76.76 0 0 0 .75-.75c0-.375.284-.688.66-.688h1.596c.403 0 .744.313.744.688 0 .25-.125.44-.344.565l-1.781 1.09a.77.77 0 0 0-.375.657V9c0 .406.344.75.75.75A.76.76 0 0 0 8.75 9v-.063l1.41-.874a2.23 2.23 0 0 0 1.062-1.876C11.25 4.97 10.282 4 9.034 4Z" fill="#E6CB80"></path>
                                            </g>
                                            <defs>
                                                <clipPath id="a">
                                                    <path fill="#fff" d="M0 0h16v16H0z"></path>
                                                </clipPath>
                                            </defs>
                                        </svg>
                                        <div class="tooltip-content position-left">
                                            <div class="tooltip-text">Your personal assistant will contact you upon meeting the criteria for VIP level.</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="levels__item-text">
                                    <div class="levels__item-text-1">
                                        Max trade amount </div>
                                    <div class="levels__item-text-2">
                                        $20,000 </div>
                                </div>
                                <div class="levels__item-text">
                                    <div class="levels__item-text-1">
                                        Increased payout (when available) </div>
                                    <div class="levels__item-text-2">
                                        + 8% </div>
                                </div>
                                <div class="levels__item-text">
                                    <div class="levels__item-text-1">
                                        Personal discount in the market </div>
                                    <div class="levels__item-text-2">
                                        25% </div>
                                </div>
                                <div class="levels__item-text">
                                    <div class="levels__item-text-1">
                                        <div class="levels__item-text-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M6.4443 3.6853C8.08879 2.58649 10.0222 2 12 2C13.3132 2 14.6136 2.25866 15.8268 2.7612C17.0401 3.26375 18.1425 4.00035 19.0711 4.92893C19.9997 5.85752 20.7363 6.95991 21.2388 8.17317C21.7413 9.38642 22 10.6868 22 12C22 13.9778 21.4135 15.9112 20.3147 17.5557C19.2159 19.2002 17.6541 20.4819 15.8268 21.2388C13.9996 21.9957 11.9889 22.1937 10.0491 21.8079C8.10929 21.422 6.32746 20.4696 4.92894 19.0711C3.53041 17.6725 2.578 15.8907 2.19215 13.9509C1.8063 12.0111 2.00433 10.0004 2.76121 8.17317C3.51809 6.3459 4.79981 4.78412 6.4443 3.6853ZM14.72 8.79006L10.43 13.0901L8.77999 11.4401C8.69034 11.3354 8.58002 11.2504 8.45596 11.1903C8.3319 11.1303 8.19676 11.0966 8.05904 11.0913C7.92132 11.0859 7.78399 11.1091 7.65567 11.1594C7.52734 11.2097 7.41079 11.286 7.31334 11.3834C7.21589 11.4809 7.13963 11.5974 7.08935 11.7257C7.03908 11.8541 7.01587 11.9914 7.02119 12.1291C7.02651 12.2668 7.06024 12.402 7.12027 12.526C7.18029 12.6501 7.26531 12.7604 7.36999 12.8501L9.71999 15.2101C9.81343 15.3027 9.92425 15.3761 10.0461 15.4258C10.1679 15.4756 10.2984 15.5008 10.43 15.5001C10.6923 15.499 10.9437 15.3948 11.13 15.2101L16.13 10.2101C16.2237 10.1171 16.2981 10.0065 16.3489 9.88464C16.3997 9.76278 16.4258 9.63207 16.4258 9.50006C16.4258 9.36805 16.3997 9.23734 16.3489 9.11548C16.2981 8.99362 16.2237 8.88302 16.13 8.79006C15.9426 8.60381 15.6892 8.49927 15.425 8.49927C15.1608 8.49927 14.9074 8.60381 14.72 8.79006Z" fill="#EDCC91"></path>
                                            </svg>
                                        </div>
                                        Personal assistant
                                    </div>
                                </div>
                                <div class="levels__item-text">
                                    <div class="levels__item-text-1">
                                        <div class="levels__item-text-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M6.4443 3.6853C8.08879 2.58649 10.0222 2 12 2C13.3132 2 14.6136 2.25866 15.8268 2.7612C17.0401 3.26375 18.1425 4.00035 19.0711 4.92893C19.9997 5.85752 20.7363 6.95991 21.2388 8.17317C21.7413 9.38642 22 10.6868 22 12C22 13.9778 21.4135 15.9112 20.3147 17.5557C19.2159 19.2002 17.6541 20.4819 15.8268 21.2388C13.9996 21.9957 11.9889 22.1937 10.0491 21.8079C8.10929 21.422 6.32746 20.4696 4.92894 19.0711C3.53041 17.6725 2.578 15.8907 2.19215 13.9509C1.8063 12.0111 2.00433 10.0004 2.76121 8.17317C3.51809 6.3459 4.79981 4.78412 6.4443 3.6853ZM14.72 8.79006L10.43 13.0901L8.77999 11.4401C8.69034 11.3354 8.58002 11.2504 8.45596 11.1903C8.3319 11.1303 8.19676 11.0966 8.05904 11.0913C7.92132 11.0859 7.78399 11.1091 7.65567 11.1594C7.52734 11.2097 7.41079 11.286 7.31334 11.3834C7.21589 11.4809 7.13963 11.5974 7.08935 11.7257C7.03908 11.8541 7.01587 11.9914 7.02119 12.1291C7.02651 12.2668 7.06024 12.402 7.12027 12.526C7.18029 12.6501 7.26531 12.7604 7.36999 12.8501L9.71999 15.2101C9.81343 15.3027 9.92425 15.3761 10.0461 15.4258C10.1679 15.4756 10.2984 15.5008 10.43 15.5001C10.6923 15.499 10.9437 15.3948 11.13 15.2101L16.13 10.2101C16.2237 10.1171 16.2981 10.0065 16.3489 9.88464C16.3997 9.76278 16.4258 9.63207 16.4258 9.50006C16.4258 9.36805 16.3997 9.23734 16.3489 9.11548C16.2981 8.99362 16.2237 8.88302 16.13 8.79006C15.9426 8.60381 15.6892 8.49927 15.425 8.49927C15.1608 8.49927 14.9074 8.60381 14.72 8.79006Z" fill="#E7CB80"></path>
                                            </svg>
                                        </div>
                                        Prioritized support resolutions
                                    </div>
                                </div>
                                <div class="levels__item-text">
                                    <div class="levels__item-text-1">
                                        <div class="levels__item-text-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M6.4443 3.6853C8.08879 2.58649 10.0222 2 12 2C13.3132 2 14.6136 2.25866 15.8268 2.7612C17.0401 3.26375 18.1425 4.00035 19.0711 4.92893C19.9997 5.85752 20.7363 6.95991 21.2388 8.17317C21.7413 9.38642 22 10.6868 22 12C22 13.9778 21.4135 15.9112 20.3147 17.5557C19.2159 19.2002 17.6541 20.4819 15.8268 21.2388C13.9996 21.9957 11.9889 22.1937 10.0491 21.8079C8.10929 21.422 6.32746 20.4696 4.92894 19.0711C3.53041 17.6725 2.578 15.8907 2.19215 13.9509C1.8063 12.0111 2.00433 10.0004 2.76121 8.17317C3.51809 6.3459 4.79981 4.78412 6.4443 3.6853ZM14.72 8.79006L10.43 13.0901L8.77999 11.4401C8.69034 11.3354 8.58002 11.2504 8.45596 11.1903C8.3319 11.1303 8.19676 11.0966 8.05904 11.0913C7.92132 11.0859 7.78399 11.1091 7.65567 11.1594C7.52734 11.2097 7.41079 11.286 7.31334 11.3834C7.21589 11.4809 7.13963 11.5974 7.08935 11.7257C7.03908 11.8541 7.01587 11.9914 7.02119 12.1291C7.02651 12.2668 7.06024 12.402 7.12027 12.526C7.18029 12.6501 7.26531 12.7604 7.36999 12.8501L9.71999 15.2101C9.81343 15.3027 9.92425 15.3761 10.0461 15.4258C10.1679 15.4756 10.2984 15.5008 10.43 15.5001C10.6923 15.499 10.9437 15.3948 11.13 15.2101L16.13 10.2101C16.2237 10.1171 16.2981 10.0065 16.3489 9.88464C16.3997 9.76278 16.4258 9.63207 16.4258 9.50006C16.4258 9.36805 16.3997 9.23734 16.3489 9.11548C16.2981 8.99362 16.2237 8.88302 16.13 8.79006C15.9426 8.60381 15.6892 8.49927 15.425 8.49927C15.1608 8.49927 14.9074 8.60381 14.72 8.79006Z" fill="#EDCC91"></path>
                                            </svg>
                                        </div>
                                        Express Trades
                                    </div>
                                </div>
                                <div class="levels__item-text">
                                    <div class="levels__item-text-1">
                                        <div class="levels__item-text-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M6.4443 3.6853C8.08879 2.58649 10.0222 2 12 2C13.3132 2 14.6136 2.25866 15.8268 2.7612C17.0401 3.26375 18.1425 4.00035 19.0711 4.92893C19.9997 5.85752 20.7363 6.95991 21.2388 8.17317C21.7413 9.38642 22 10.6868 22 12C22 13.9778 21.4135 15.9112 20.3147 17.5557C19.2159 19.2002 17.6541 20.4819 15.8268 21.2388C13.9996 21.9957 11.9889 22.1937 10.0491 21.8079C8.10929 21.422 6.32746 20.4696 4.92894 19.0711C3.53041 17.6725 2.578 15.8907 2.19215 13.9509C1.8063 12.0111 2.00433 10.0004 2.76121 8.17317C3.51809 6.3459 4.79981 4.78412 6.4443 3.6853ZM14.72 8.79006L10.43 13.0901L8.77999 11.4401C8.69034 11.3354 8.58002 11.2504 8.45596 11.1903C8.3319 11.1303 8.19676 11.0966 8.05904 11.0913C7.92132 11.0859 7.78399 11.1091 7.65567 11.1594C7.52734 11.2097 7.41079 11.286 7.31334 11.3834C7.21589 11.4809 7.13963 11.5974 7.08935 11.7257C7.03908 11.8541 7.01587 11.9914 7.02119 12.1291C7.02651 12.2668 7.06024 12.402 7.12027 12.526C7.18029 12.6501 7.26531 12.7604 7.36999 12.8501L9.71999 15.2101C9.81343 15.3027 9.92425 15.3761 10.0461 15.4258C10.1679 15.4756 10.2984 15.5008 10.43 15.5001C10.6923 15.499 10.9437 15.3948 11.13 15.2101L16.13 10.2101C16.2237 10.1171 16.2981 10.0065 16.3489 9.88464C16.3997 9.76278 16.4258 9.63207 16.4258 9.50006C16.4258 9.36805 16.3997 9.23734 16.3489 9.11548C16.2981 8.99362 16.2237 8.88302 16.13 8.79006C15.9426 8.60381 15.6892 8.49927 15.425 8.49927C15.1608 8.49927 14.9074 8.60381 14.72 8.79006Z" fill="#E7CB80"></path>
                                            </svg>
                                        </div>
                                        Prioritized withdrawals
                                    </div>
                                </div>
                                <div class="levels__item-text">
                                    <div class="levels__item-text-1">
                                        <div class="levels__item-text-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M6.4443 3.6853C8.08879 2.58649 10.0222 2 12 2C13.3132 2 14.6136 2.25866 15.8268 2.7612C17.0401 3.26375 18.1425 4.00035 19.0711 4.92893C19.9997 5.85752 20.7363 6.95991 21.2388 8.17317C21.7413 9.38642 22 10.6868 22 12C22 13.9778 21.4135 15.9112 20.3147 17.5557C19.2159 19.2002 17.6541 20.4819 15.8268 21.2388C13.9996 21.9957 11.9889 22.1937 10.0491 21.8079C8.10929 21.422 6.32746 20.4696 4.92894 19.0711C3.53041 17.6725 2.578 15.8907 2.19215 13.9509C1.8063 12.0111 2.00433 10.0004 2.76121 8.17317C3.51809 6.3459 4.79981 4.78412 6.4443 3.6853ZM14.72 8.79006L10.43 13.0901L8.77999 11.4401C8.69034 11.3354 8.58002 11.2504 8.45596 11.1903C8.3319 11.1303 8.19676 11.0966 8.05904 11.0913C7.92132 11.0859 7.78399 11.1091 7.65567 11.1594C7.52734 11.2097 7.41079 11.286 7.31334 11.3834C7.21589 11.4809 7.13963 11.5974 7.08935 11.7257C7.03908 11.8541 7.01587 11.9914 7.02119 12.1291C7.02651 12.2668 7.06024 12.402 7.12027 12.526C7.18029 12.6501 7.26531 12.7604 7.36999 12.8501L9.71999 15.2101C9.81343 15.3027 9.92425 15.3761 10.0461 15.4258C10.1679 15.4756 10.2984 15.5008 10.43 15.5001C10.6923 15.499 10.9437 15.3948 11.13 15.2101L16.13 10.2101C16.2237 10.1171 16.2981 10.0065 16.3489 9.88464C16.3997 9.76278 16.4258 9.63207 16.4258 9.50006C16.4258 9.36805 16.3997 9.23734 16.3489 9.11548C16.2981 8.99362 16.2237 8.88302 16.13 8.79006C15.9426 8.60381 15.6892 8.49927 15.425 8.49927C15.1608 8.49927 14.9074 8.60381 14.72 8.79006Z" fill="#E7CB80"></path>
                                            </svg>
                                        </div>
                                        VIP badge
                                    </div>
                                </div>
                                <div class="levels__item-text">
                                    <div class="levels__item-text-1">
                                        <div class="levels__item-text-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M6.4443 3.6853C8.08879 2.58649 10.0222 2 12 2C13.3132 2 14.6136 2.25866 15.8268 2.7612C17.0401 3.26375 18.1425 4.00035 19.0711 4.92893C19.9997 5.85752 20.7363 6.95991 21.2388 8.17317C21.7413 9.38642 22 10.6868 22 12C22 13.9778 21.4135 15.9112 20.3147 17.5557C19.2159 19.2002 17.6541 20.4819 15.8268 21.2388C13.9996 21.9957 11.9889 22.1937 10.0491 21.8079C8.10929 21.422 6.32746 20.4696 4.92894 19.0711C3.53041 17.6725 2.578 15.8907 2.19215 13.9509C1.8063 12.0111 2.00433 10.0004 2.76121 8.17317C3.51809 6.3459 4.79981 4.78412 6.4443 3.6853ZM14.72 8.79006L10.43 13.0901L8.77999 11.4401C8.69034 11.3354 8.58002 11.2504 8.45596 11.1903C8.3319 11.1303 8.19676 11.0966 8.05904 11.0913C7.92132 11.0859 7.78399 11.1091 7.65567 11.1594C7.52734 11.2097 7.41079 11.286 7.31334 11.3834C7.21589 11.4809 7.13963 11.5974 7.08935 11.7257C7.03908 11.8541 7.01587 11.9914 7.02119 12.1291C7.02651 12.2668 7.06024 12.402 7.12027 12.526C7.18029 12.6501 7.26531 12.7604 7.36999 12.8501L9.71999 15.2101C9.81343 15.3027 9.92425 15.3761 10.0461 15.4258C10.1679 15.4756 10.2984 15.5008 10.43 15.5001C10.6923 15.499 10.9437 15.3948 11.13 15.2101L16.13 10.2101C16.2237 10.1171 16.2981 10.0065 16.3489 9.88464C16.3997 9.76278 16.4258 9.63207 16.4258 9.50006C16.4258 9.36805 16.3997 9.23734 16.3489 9.11548C16.2981 8.99362 16.2237 8.88302 16.13 8.79006C15.9426 8.60381 15.6892 8.49927 15.425 8.49927C15.1608 8.49927 14.9074 8.60381 14.72 8.79006Z" fill="#E7CB80"></path>
                                            </svg>
                                        </div>
                                        Private VIP chat room
                                    </div>
                                </div>
                                <div class="levels__item-text">
                                    <div class="levels__item-text-1">
                                        <div class="levels__item-text-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M6.4443 3.6853C8.08879 2.58649 10.0222 2 12 2C13.3132 2 14.6136 2.25866 15.8268 2.7612C17.0401 3.26375 18.1425 4.00035 19.0711 4.92893C19.9997 5.85752 20.7363 6.95991 21.2388 8.17317C21.7413 9.38642 22 10.6868 22 12C22 13.9778 21.4135 15.9112 20.3147 17.5557C19.2159 19.2002 17.6541 20.4819 15.8268 21.2388C13.9996 21.9957 11.9889 22.1937 10.0491 21.8079C8.10929 21.422 6.32746 20.4696 4.92894 19.0711C3.53041 17.6725 2.578 15.8907 2.19215 13.9509C1.8063 12.0111 2.00433 10.0004 2.76121 8.17317C3.51809 6.3459 4.79981 4.78412 6.4443 3.6853ZM14.72 8.79006L10.43 13.0901L8.77999 11.4401C8.69034 11.3354 8.58002 11.2504 8.45596 11.1903C8.3319 11.1303 8.19676 11.0966 8.05904 11.0913C7.92132 11.0859 7.78399 11.1091 7.65567 11.1594C7.52734 11.2097 7.41079 11.286 7.31334 11.3834C7.21589 11.4809 7.13963 11.5974 7.08935 11.7257C7.03908 11.8541 7.01587 11.9914 7.02119 12.1291C7.02651 12.2668 7.06024 12.402 7.12027 12.526C7.18029 12.6501 7.26531 12.7604 7.36999 12.8501L9.71999 15.2101C9.81343 15.3027 9.92425 15.3761 10.0461 15.4258C10.1679 15.4756 10.2984 15.5008 10.43 15.5001C10.6923 15.499 10.9437 15.3948 11.13 15.2101L16.13 10.2101C16.2237 10.1171 16.2981 10.0065 16.3489 9.88464C16.3997 9.76278 16.4258 9.63207 16.4258 9.50006C16.4258 9.36805 16.3997 9.23734 16.3489 9.11548C16.2981 8.99362 16.2237 8.88302 16.13 8.79006C15.9426 8.60381 15.6892 8.49927 15.425 8.49927C15.1608 8.49927 14.9074 8.60381 14.72 8.79006Z" fill="#E7CB80"></path>
                                            </svg>
                                        </div>
                                        Educational webinars
                                    </div>
                                </div>
                                <div class="levels__item-text">
                                    <div class="levels__item-text-1">
                                        <div class="levels__item-text-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M6.4443 3.6853C8.08879 2.58649 10.0222 2 12 2C13.3132 2 14.6136 2.25866 15.8268 2.7612C17.0401 3.26375 18.1425 4.00035 19.0711 4.92893C19.9997 5.85752 20.7363 6.95991 21.2388 8.17317C21.7413 9.38642 22 10.6868 22 12C22 13.9778 21.4135 15.9112 20.3147 17.5557C19.2159 19.2002 17.6541 20.4819 15.8268 21.2388C13.9996 21.9957 11.9889 22.1937 10.0491 21.8079C8.10929 21.422 6.32746 20.4696 4.92894 19.0711C3.53041 17.6725 2.578 15.8907 2.19215 13.9509C1.8063 12.0111 2.00433 10.0004 2.76121 8.17317C3.51809 6.3459 4.79981 4.78412 6.4443 3.6853ZM14.72 8.79006L10.43 13.0901L8.77999 11.4401C8.69034 11.3354 8.58002 11.2504 8.45596 11.1903C8.3319 11.1303 8.19676 11.0966 8.05904 11.0913C7.92132 11.0859 7.78399 11.1091 7.65567 11.1594C7.52734 11.2097 7.41079 11.286 7.31334 11.3834C7.21589 11.4809 7.13963 11.5974 7.08935 11.7257C7.03908 11.8541 7.01587 11.9914 7.02119 12.1291C7.02651 12.2668 7.06024 12.402 7.12027 12.526C7.18029 12.6501 7.26531 12.7604 7.36999 12.8501L9.71999 15.2101C9.81343 15.3027 9.92425 15.3761 10.0461 15.4258C10.1679 15.4756 10.2984 15.5008 10.43 15.5001C10.6923 15.499 10.9437 15.3948 11.13 15.2101L16.13 10.2101C16.2237 10.1171 16.2981 10.0065 16.3489 9.88464C16.3997 9.76278 16.4258 9.63207 16.4258 9.50006C16.4258 9.36805 16.3997 9.23734 16.3489 9.11548C16.2981 8.99362 16.2237 8.88302 16.13 8.79006C15.9426 8.60381 15.6892 8.49927 15.425 8.49927C15.1608 8.49927 14.9074 8.60381 14.72 8.79006Z" fill="#E7CB80"></path>
                                            </svg>
                                        </div>
                                        Even more exclusive promotions and benefits*
                                    </div>
                                </div>
                                <div class="levels__item-text">
                                    <div class="levels__item-text-1">
                                        <div class="levels__item-text-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M6.4443 3.6853C8.08879 2.58649 10.0222 2 12 2C13.3132 2 14.6136 2.25866 15.8268 2.7612C17.0401 3.26375 18.1425 4.00035 19.0711 4.92893C19.9997 5.85752 20.7363 6.95991 21.2388 8.17317C21.7413 9.38642 22 10.6868 22 12C22 13.9778 21.4135 15.9112 20.3147 17.5557C19.2159 19.2002 17.6541 20.4819 15.8268 21.2388C13.9996 21.9957 11.9889 22.1937 10.0491 21.8079C8.10929 21.422 6.32746 20.4696 4.92894 19.0711C3.53041 17.6725 2.578 15.8907 2.19215 13.9509C1.8063 12.0111 2.00433 10.0004 2.76121 8.17317C3.51809 6.3459 4.79981 4.78412 6.4443 3.6853ZM14.72 8.79006L10.43 13.0901L8.77999 11.4401C8.69034 11.3354 8.58002 11.2504 8.45596 11.1903C8.3319 11.1303 8.19676 11.0966 8.05904 11.0913C7.92132 11.0859 7.78399 11.1091 7.65567 11.1594C7.52734 11.2097 7.41079 11.286 7.31334 11.3834C7.21589 11.4809 7.13963 11.5974 7.08935 11.7257C7.03908 11.8541 7.01587 11.9914 7.02119 12.1291C7.02651 12.2668 7.06024 12.402 7.12027 12.526C7.18029 12.6501 7.26531 12.7604 7.36999 12.8501L9.71999 15.2101C9.81343 15.3027 9.92425 15.3761 10.0461 15.4258C10.1679 15.4756 10.2984 15.5008 10.43 15.5001C10.6923 15.499 10.9437 15.3948 11.13 15.2101L16.13 10.2101C16.2237 10.1171 16.2981 10.0065 16.3489 9.88464C16.3997 9.76278 16.4258 9.63207 16.4258 9.50006C16.4258 9.36805 16.3997 9.23734 16.3489 9.11548C16.2981 8.99362 16.2237 8.88302 16.13 8.79006C15.9426 8.60381 15.6892 8.49927 15.425 8.49927C15.1608 8.49927 14.9074 8.60381 14.72 8.79006Z" fill="#E7CB80"></path>
                                            </svg>
                                        </div>
                                        Cashback*
                                    </div>
                                </div>
                                <div class="levels__item-text">
                                    <div class="levels__item-text-1">
                                        <div class="levels__item-text-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M6.4443 3.6853C8.08879 2.58649 10.0222 2 12 2C13.3132 2 14.6136 2.25866 15.8268 2.7612C17.0401 3.26375 18.1425 4.00035 19.0711 4.92893C19.9997 5.85752 20.7363 6.95991 21.2388 8.17317C21.7413 9.38642 22 10.6868 22 12C22 13.9778 21.4135 15.9112 20.3147 17.5557C19.2159 19.2002 17.6541 20.4819 15.8268 21.2388C13.9996 21.9957 11.9889 22.1937 10.0491 21.8079C8.10929 21.422 6.32746 20.4696 4.92894 19.0711C3.53041 17.6725 2.578 15.8907 2.19215 13.9509C1.8063 12.0111 2.00433 10.0004 2.76121 8.17317C3.51809 6.3459 4.79981 4.78412 6.4443 3.6853ZM14.72 8.79006L10.43 13.0901L8.77999 11.4401C8.69034 11.3354 8.58002 11.2504 8.45596 11.1903C8.3319 11.1303 8.19676 11.0966 8.05904 11.0913C7.92132 11.0859 7.78399 11.1091 7.65567 11.1594C7.52734 11.2097 7.41079 11.286 7.31334 11.3834C7.21589 11.4809 7.13963 11.5974 7.08935 11.7257C7.03908 11.8541 7.01587 11.9914 7.02119 12.1291C7.02651 12.2668 7.06024 12.402 7.12027 12.526C7.18029 12.6501 7.26531 12.7604 7.36999 12.8501L9.71999 15.2101C9.81343 15.3027 9.92425 15.3761 10.0461 15.4258C10.1679 15.4756 10.2984 15.5008 10.43 15.5001C10.6923 15.499 10.9437 15.3948 11.13 15.2101L16.13 10.2101C16.2237 10.1171 16.2981 10.0065 16.3489 9.88464C16.3997 9.76278 16.4258 9.63207 16.4258 9.50006C16.4258 9.36805 16.3997 9.23734 16.3489 9.11548C16.2981 8.99362 16.2237 8.88302 16.13 8.79006C15.9426 8.60381 15.6892 8.49927 15.425 8.49927C15.1608 8.49927 14.9074 8.60381 14.72 8.79006Z" fill="#E7CB80"></path>
                                            </svg>
                                        </div>
                                        Upgrade your friend's level to VIP for 1 month*
                                    </div>
                                </div>
                                <div class="levels__item-text">
                                    <div class="levels__item-text-1">
                                        <div class="levels__item-text-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M6.4443 3.6853C8.08879 2.58649 10.0222 2 12 2C13.3132 2 14.6136 2.25866 15.8268 2.7612C17.0401 3.26375 18.1425 4.00035 19.0711 4.92893C19.9997 5.85752 20.7363 6.95991 21.2388 8.17317C21.7413 9.38642 22 10.6868 22 12C22 13.9778 21.4135 15.9112 20.3147 17.5557C19.2159 19.2002 17.6541 20.4819 15.8268 21.2388C13.9996 21.9957 11.9889 22.1937 10.0491 21.8079C8.10929 21.422 6.32746 20.4696 4.92894 19.0711C3.53041 17.6725 2.578 15.8907 2.19215 13.9509C1.8063 12.0111 2.00433 10.0004 2.76121 8.17317C3.51809 6.3459 4.79981 4.78412 6.4443 3.6853ZM14.72 8.79006L10.43 13.0901L8.77999 11.4401C8.69034 11.3354 8.58002 11.2504 8.45596 11.1903C8.3319 11.1303 8.19676 11.0966 8.05904 11.0913C7.92132 11.0859 7.78399 11.1091 7.65567 11.1594C7.52734 11.2097 7.41079 11.286 7.31334 11.3834C7.21589 11.4809 7.13963 11.5974 7.08935 11.7257C7.03908 11.8541 7.01587 11.9914 7.02119 12.1291C7.02651 12.2668 7.06024 12.402 7.12027 12.526C7.18029 12.6501 7.26531 12.7604 7.36999 12.8501L9.71999 15.2101C9.81343 15.3027 9.92425 15.3761 10.0461 15.4258C10.1679 15.4756 10.2984 15.5008 10.43 15.5001C10.6923 15.499 10.9437 15.3948 11.13 15.2101L16.13 10.2101C16.2237 10.1171 16.2981 10.0065 16.3489 9.88464C16.3997 9.76278 16.4258 9.63207 16.4258 9.50006C16.4258 9.36805 16.3997 9.23734 16.3489 9.11548C16.2981 8.99362 16.2237 8.88302 16.13 8.79006C15.9426 8.60381 15.6892 8.49927 15.425 8.49927C15.1608 8.49927 14.9074 8.60381 14.72 8.79006Z" fill="#E7CB80"></path>
                                            </svg>
                                        </div>
                                        Free participation in tournaments*
                                    </div>
                                </div>
                                <div class="levels__item-text">
                                    <div class="levels__item-text-1">
                                        <div class="levels__item-text-icon">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd" d="M6.4443 3.6853C8.08879 2.58649 10.0222 2 12 2C13.3132 2 14.6136 2.25866 15.8268 2.7612C17.0401 3.26375 18.1425 4.00035 19.0711 4.92893C19.9997 5.85752 20.7363 6.95991 21.2388 8.17317C21.7413 9.38642 22 10.6868 22 12C22 13.9778 21.4135 15.9112 20.3147 17.5557C19.2159 19.2002 17.6541 20.4819 15.8268 21.2388C13.9996 21.9957 11.9889 22.1937 10.0491 21.8079C8.10929 21.422 6.32746 20.4696 4.92894 19.0711C3.53041 17.6725 2.578 15.8907 2.19215 13.9509C1.8063 12.0111 2.00433 10.0004 2.76121 8.17317C3.51809 6.3459 4.79981 4.78412 6.4443 3.6853ZM14.72 8.79006L10.43 13.0901L8.77999 11.4401C8.69034 11.3354 8.58002 11.2504 8.45596 11.1903C8.3319 11.1303 8.19676 11.0966 8.05904 11.0913C7.92132 11.0859 7.78399 11.1091 7.65567 11.1594C7.52734 11.2097 7.41079 11.286 7.31334 11.3834C7.21589 11.4809 7.13963 11.5974 7.08935 11.7257C7.03908 11.8541 7.01587 11.9914 7.02119 12.1291C7.02651 12.2668 7.06024 12.402 7.12027 12.526C7.18029 12.6501 7.26531 12.7604 7.36999 12.8501L9.71999 15.2101C9.81343 15.3027 9.92425 15.3761 10.0461 15.4258C10.1679 15.4756 10.2984 15.5008 10.43 15.5001C10.6923 15.499 10.9437 15.3948 11.13 15.2101L16.13 10.2101C16.2237 10.1171 16.2981 10.0065 16.3489 9.88464C16.3997 9.76278 16.4258 9.63207 16.4258 9.50006C16.4258 9.36805 16.3997 9.23734 16.3489 9.11548C16.2981 8.99362 16.2237 8.88302 16.13 8.79006C15.9426 8.60381 15.6892 8.49927 15.425 8.49927C15.1608 8.49927 14.9074 8.60381 14.72 8.79006Z" fill="#E7CB80"></path>
                                            </svg>
                                        </div>
                                        Prioritized access to new platform features*
                                    </div>
                                </div>
                                <div class="levels__item-text-divider"></div>
                                <div class="levels__item-text">
                                    <div class="levels__item-text-1">
                                        <div class="levels__item-text-asterisk">*</div>
                                        For more information, contact your personal assistant.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection