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

                @foreach ($customFields ?? [] as $field)
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-[#7c86a3]">
                            {{ $field->label }} @if($field->is_required) <span class="text-[#f4534a]">*</span> @endif
                        </label>
                        @php $inputName = "custom_fields[{$field->field_key}]"; @endphp
                        @switch($field->field_type)
                            @case('textarea')
                                <textarea name="{{ $inputName }}" rows="3" @required($field->is_required) class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg px-4 py-3 text-sm text-white focus:outline-none focus:border-[#4f8ef7]"></textarea>
                                @break
                            @case('select')
                                <select name="{{ $inputName }}" @required($field->is_required) class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg px-4 py-3 text-sm text-white focus:outline-none focus:border-[#4f8ef7]">
                                    <option value="">Select&hellip;</option>
                                    @foreach ($field->optionList() as $option)
                                        <option value="{{ $option }}">{{ $option }}</option>
                                    @endforeach
                                </select>
                                @break
                            @case('checkbox')
                                <label class="flex items-center gap-2 text-sm text-white">
                                    <input type="checkbox" name="{{ $inputName }}" value="1" class="rounded border-[#2a3350] bg-[#1c243c]">
                                    Yes
                                </label>
                                @break
                            @case('file')
                                <input type="file" name="{{ $inputName }}" @required($field->is_required) class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg px-4 py-3 text-sm text-white focus:outline-none focus:border-[#4f8ef7]">
                                @break
                            @case('date')
                                <input type="date" name="{{ $inputName }}" @required($field->is_required) class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg px-4 py-3 text-sm text-white focus:outline-none focus:border-[#4f8ef7]">
                                @break
                            @case('number')
                                <input type="number" name="{{ $inputName }}" @required($field->is_required) class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg px-4 py-3 text-sm text-white focus:outline-none focus:border-[#4f8ef7]">
                                @break
                            @default
                                <input type="text" name="{{ $inputName }}" @required($field->is_required) class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg px-4 py-3 text-sm text-white focus:outline-none focus:border-[#4f8ef7]">
                        @endswitch
                    </div>
                @endforeach

                <button type="submit" class="w-full bg-[#4f8ef7] hover:bg-[#3f7de6] text-white font-semibold text-sm py-3 rounded-lg">Submit for Review</button>
            </form>
        </div>
    </div>
</div>
@endsection
