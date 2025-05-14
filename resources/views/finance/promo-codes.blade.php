@extends('layouts.app')

@section('content')
    <div class="w-full m-8" style="margin-left: .5rem;">
        @include('partials.finance-header')
        <div class="flex gap-5 m-4">
            <!-- Promo Code Input Section -->
            <div class="flex flex-col bg-gray-900 p-4 rounded-lg col-span-1 lg:min-w-xl _min_300">
                <div class="text-lg font-semibold mb-2">Do you have a promo code?</div>
                <input class="bg-gray-700 text-white border border-gray-600 rounded-md px-4 py-2 w-full" id="promoCodeInput" placeholder="Enter promo code">
                <a class="promoCodeBtn hidden mt-3 bg-orange-600 text-white font-medium px-4 py-2 rounded-md hover:bg-orange-700 transition duration-300 text-center" href="#">
                    Check
                </a>
                <div class="mt-2 text-orange-400 text-sm hidden">Invalid promo code</div>
            </div>

            <!-- Available Promo Codes Table -->
            <div class="bg-gray-900 p-4 w-full rounded-xl text-white">
                <div class="text-lg font-semibold mb-[14px] text-xl">Available promo codes</div>
                <table class="w-full border-collapse text-left text-sm">
                    <thead class="border-b  text-white">
                        <tr>
                            <th class="px-4 py-2">Promo code</th>
                            <th class="px-4 py-2">Percent</th>
                            <th class="px-4 py-2">Min. deposit</th>
                            <th class="px-4 py-2">Max. bonus</th>
                            <th class="px-4 py-2">Valid from</th>
                            <th class="px-4 py-2">Valid until</th>
                            <th class="px-4 py-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-gray-600">
                            <td class="px-4 py-2">50START</td>
                            <td class="px-4 py-2">50%</td>
                            <td class="px-4 py-2">$50</td>
                            <td class="px-4 py-2">$5,000</td>
                            <td class="px-4 py-2"></td>
                            <td class="px-4 py-2"></td>
                            <td class="px-4 py-2">
                                <a class="bg-orange-600 text-white font-medium px-3 py-1 rounded-md hover:bg-orange-700 transition duration-300 text-sm"
                                href="https://pocketoption.com/en/">
                                    Check
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>


    </div>
    <style>
        ._min_300 {
            min-width: 25rem ;
            color: #7f838c;
        }
        .promo .rw>.l .promocode-block__check-button {
            margin-top: 10px;
            text-align: center;
            width: 100%;
        }

        .btn-warn {
            background-color: var(--po-ui-kit-btn-warn-background-color-base);
            border-color: var(--po-ui-kit-btn-warn-border-color-base);
            color: var(--po-ui-kit-btn-warn-color-base);
        }

        .btn {
            border: 1px solid transparent;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.4285;
            padding: 7px 16px;
            text-align: center;
            text-decoration: none;
            -webkit-transition: color .3s, background-color .3s, border-color .3s;
            transition: color .3s, background-color .3s, border-color .3s;
            -webkit-transition: background-color .3s, border-color .3s, color .3s;
            transition: background-color .3s, border-color .3s, color .3s;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
            white-space: normal;
        }
    </style>
@endsection


@push('js')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const promoCodeInput = document.getElementById("promoCodeInput");
        const promoCodeBtn = document.getElementById("promoCodeBtn");

        promoCodeInput.addEventListener("input", function () {
            if (promoCodeInput.value.trim().length > 0) {
                promoCodeBtn.classList.remove("hidden");
            } else {
                promoCodeBtn.classList.add("hidden");
            }
        });
    });
</script>

@endpush
