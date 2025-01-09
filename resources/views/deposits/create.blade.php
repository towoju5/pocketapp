@extends('layouts.app')

@section('content')
    <div class="bg-[#15182a] rounded-lg w-full m-6 max-h-[90%] text-white">
        <!-- Multi-Step Wizard -->
        <div class="max-w-6xl mx-auto py-10 px-4 lg:px-20">
            <div class="shadow-lg p-6 lg:p-10">
                <!-- Step Progress -->
                <div class="flex items-center justify-between mb-8">
                    <div class="text-teal-500 flex items-center">
                        <span
                            class="w-8 h-8 bg-teal-500 text-white rounded-full flex items-center justify-center font-bold">1</span>
                        <span class="ml-2">Deposit method</span>
                    </div>
                    <div class="text-white flex items-center">
                        <span
                            class="w-8 h-8 bg-gray-700 text-white rounded-full flex items-center justify-center font-bold">2</span>
                        <span class="ml-2">Payment details</span>
                    </div>
                    <div class="text-gray-500 flex items-center">
                        <span
                            class="w-8 h-8 bg-gray-700 text-white rounded-full flex items-center justify-center font-bold">3</span>
                        <span class="ml-2">Payment process</span>
                    </div>
                    <div class="text-gray-500 flex items-center">
                        <span
                            class="w-8 h-8 bg-gray-700 text-white rounded-full flex items-center justify-center font-bold">4</span>
                        <span class="ml-2">Payment execution</span>
                    </div>
                </div>

                <!-- Deposit Form -->
                <div class="grid grid-cols-1 gap-6">
                    <div class="step-1">
                        @include('deposits.partials.step-1')
                    </div>
                    <div class="step-2 hidden">
                        @include('deposits.partials.step-2')
                    </div>
                    <div class="step-3 hidden">
                        @include('deposits.partials.step-3')
                    </div>
                    <div class="step-4 hidden">
                        @include('deposits.partials.step-4')
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex justify-between mt-8">
                    <button id="prevBtn" class="bg-gray-500 hover:bg-gray-400 text-white font-bold py-2 px-6 rounded hidden">
                        Previous
                    </button>
                    <button id="nextBtn" class="bg-teal-500 hover:bg-teal-400 text-white font-bold py-2 px-6 rounded">
                        Next
                    </button>
                    <button id="submitBtn" class="bg-green-500 hover:bg-green-400 text-white font-bold py-2 px-6 rounded hidden">
                        Submit
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let currentStep = 1;
            const totalSteps = 4;

            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const submitBtn = document.getElementById('submitBtn');

            function showStep(step) {
                document.querySelectorAll('.step-1, .step-2, .step-3, .step-4').forEach((el, index) => {
                    el.classList.add('hidden');
                    if (index + 1 === step) {
                        el.classList.remove('hidden');
                    }
                });

                prevBtn.classList.toggle('hidden', step === 1);
                nextBtn.classList.toggle('hidden', step === totalSteps);
                submitBtn.classList.toggle('hidden', step !== totalSteps);
            }

            prevBtn.addEventListener('click', () => {
                if (currentStep > 1) {
                    currentStep--;
                    showStep(currentStep);
                }
            });

            nextBtn.addEventListener('click', () => {
                if (currentStep < totalSteps) {
                    currentStep++;
                    showStep(currentStep);
                }
            });

            // Initialize the first step
            showStep(currentStep);
        });
    </script>
@endsection
