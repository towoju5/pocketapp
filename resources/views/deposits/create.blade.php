@extends('layouts.app')

@section('title', 'Make new Deposit')
@section('content')
    <div class="container -mt-4">
        <div class="bg-[#15182a] rounded-lg text-white">
            <div class="px-4 py-2">
                @include('partials.finance-header')
            </div>
            <!-- Multi-Step Wizard -->
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
                <div class="grid grid-cols-1 gap-6" id="contentArea">
                    @include('deposits.partials.step-1')
                </div>

            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function () {
            // Attach submit event to payinForm
            $(document).on('submit', '.payinForm', function (e) {
                e.preventDefault(); // Prevent default form submission
                
                const formData = new FormData(this);

                $.ajax({
                    url: this.action,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function (html) {
                        $('#contentArea').html(html); // Update the content area with the response
                    },
                    error: function (error) {
                        toastr.error(error.responseJSON?.message || 'An error occurred.');
                        console.error('Error:', error.responseJSON);
                    }
                });
            });

            // Trigger form submission when clicking Submit button
            $('#submitBtn').on('click', function () {
                $('.payinForm').trigger('submit');
            });
        });
    </script>
@endpush
