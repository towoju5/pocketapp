@extends('layouts.desktop.trading')

@section('content')
    <div class="w-full m-8" style="margin-left: .5rem;">
        @include('partials.finance-header')
        <div class="flex gap-5 m-4">
            <!-- Promo Code Input Section -->
            <div class="flex flex-col bg-[#171e33] border border-[#2a3350] p-4 rounded-xl col-span-1 lg:min-w-xl _min_300">
                <div class="text-lg font-semibold mb-2 text-white">Do you have a promo code?</div>
                <input class="bg-[#1c243c] text-white border border-[#2a3350] rounded-md px-4 py-2 w-full" id="promoCodeInput" placeholder="Enter promo code">
                <a class="promoCodeBtn hidden mt-3 bg-[#4f8ef7] text-white font-medium px-4 py-2 rounded-md hover:bg-[#3d7de0] transition duration-300 text-center" href="#">
                    Check
                </a>
                <div class="mt-2 text-[#f4534a] text-sm hidden">Invalid promo code</div>
            </div>

            <!-- Available Promo Codes Table -->
            <div class="bg-[#171e33] border border-[#2a3350] p-4 w-full rounded-xl text-white">
                <div class="text-lg font-semibold mb-[14px] text-xl">Available promo codes</div>
                <table class="w-full border-collapse text-left text-sm">
                    <thead class="border-b border-[#2a3350] text-white">
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
                        <tr class="border-b border-[#2a3350]">
                            <td class="px-4 py-2">50START</td>
                            <td class="px-4 py-2">50%</td>
                            <td class="px-4 py-2">$50</td>
                            <td class="px-4 py-2">$5,000</td>
                            <td class="px-4 py-2"></td>
                            <td class="px-4 py-2"></td>
                            <td class="px-4 py-2">
                                <a class="bg-[#4f8ef7] text-white font-medium px-3 py-1 rounded-md hover:bg-[#3d7de0] transition duration-300 text-sm"
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
            color: #7c86a3;
        }
        .promo .rw>.l .promocode-block__check-button {
            margin-top: 10px;
            text-align: center;
            width: 100%;
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
