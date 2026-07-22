@extends('layouts.desktop.trading')

@section('title', 'Submit Identity Documents')

@section('content')
<div class="flex-1 overflow-y-auto p-6">
    <div class="mx-auto">
        @if ($errors->any())
            <div class="bg-[#f4534a]/10 border-l-4 border-[#f4534a] text-[#f4534a] p-4 rounded-lg mb-6">{{ $errors->first() }}</div>
        @endif

        <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6">
            <h2 class="text-xl font-bold text-white mb-1">Submit Identity Documents</h2>
            <p class="text-sm text-[#7c86a3] mb-6">Upload a government-issued document to unlock withdrawals.</p>

            <form method="POST" action="{{ route('kyc.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-[#7c86a3]">Document Type</label>
                    <select name="document_type" class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg px-4 py-3 text-sm text-white focus:outline-none focus:border-[#4f8ef7]" required>
                        <option value="passport">Passport</option>
                        <option value="national_id">National ID</option>
                        <option value="drivers_license">Driver's License</option>
                    </select>
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-[#7c86a3]">Document (Front)</label>
                    <input type="file" name="document_front" class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg px-4 py-3 text-sm text-white focus:outline-none focus:border-[#4f8ef7]" accept="image/*,.pdf" required>
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-[#7c86a3]">Document (Back) &mdash; Optional</label>
                    <input type="file" name="document_back" class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg px-4 py-3 text-sm text-white focus:outline-none focus:border-[#4f8ef7]" accept="image/*,.pdf">
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-[#7c86a3]">Selfie Holding Document &mdash; Optional</label>
                    <input type="file" name="selfie" class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg px-4 py-3 text-sm text-white focus:outline-none focus:border-[#4f8ef7]" accept="image/*">
                </div>

                <button type="submit" class="w-full bg-[#4f8ef7] hover:bg-[#3f7de6] text-white font-semibold text-sm py-3 rounded-lg">Submit for Review</button>
            </form>
        </div>
    </div>
</div>
@endsection
