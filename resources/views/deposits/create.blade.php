@extends('layouts.desktop.trading')

@section('title', 'Make new Deposit')
@section('content')
    <div class="container -mt-4">
        <div class="rounded-[28px] border border-glass-border bg-[rgba(15,23,42,0.85)] backdrop-blur-[25px] text-white">
            <div class="px-4 py-2">
                @include('partials.finance-header')
            </div>
            <!-- Multi-Step Wizard -->
            <div class="shadow-lg p-6 lg:p-10">
                <!-- Step Progress -->
                <div class="flex items-center justify-between mb-8">
                    <div class="text-brand-blue flex items-center">
                        <span
                            class="w-8 h-8 bg-brand-blue text-white rounded-full flex items-center justify-center font-bold">1</span>
                        <span class="ml-2">Deposit method</span>
                    </div>
                    <div class="text-white flex items-center">
                        <span
                            class="w-8 h-8 bg-white/10 text-white rounded-full flex items-center justify-center font-bold">2</span>
                        <span class="ml-2">Payment details</span>
                    </div>
                    <div class="text-slate-500 flex items-center">
                        <span
                            class="w-8 h-8 bg-white/10 text-white rounded-full flex items-center justify-center font-bold">3</span>
                        <span class="ml-2">Payment process</span>
                    </div>
                    <div class="text-slate-500 flex items-center">
                        <span
                            class="w-8 h-8 bg-white/10 text-white rounded-full flex items-center justify-center font-bold">4</span>
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
                    success: function (response) {
                        if (response && typeof response === 'object' && response.redirect) {
                            window.location.href = response.redirect;
                            return;
                        }
                        $('#contentArea').html(response); // Update the content area with the response
                    },
                    error: function (error) {
                        toastr.error(error.responseJSON?.message || 'An error occurred.');
                        console.error('Error:', error.responseJSON);
                    }
                });
            });

            // Trigger form submission when clicking Submit button (delegated — #submitBtn
            // gets replaced with each AJAX step swap, so this must not be a direct binding)
            $(document).on('click', '#submitBtn', function () {
                $('.payinForm').trigger('submit');
            });
        });
    </script>
@endpush
