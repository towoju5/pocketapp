@php $field = $field ?? null; @endphp

<div class="space-y-4" x-data="{ type: '{{ old('field_type', $field->field_type ?? 'text') }}' }">
    <div>
        <x-input-label value="Label" />
        <x-text-input type="text" name="label" value="{{ old('label', $field->label ?? '') }}" class="w-full" required />
        <p class="mt-1 text-xs text-slate-500">Shown to the user above the field, e.g. "Full legal name".</p>
        <x-input-error :messages="$errors->get('label')" class="mt-1" />
    </div>

    <div>
        <x-input-label value="Field key" />
        <x-text-input type="text" name="field_key" value="{{ old('field_key', $field->field_key ?? '') }}" class="w-full" required @if($field) readonly @endif />
        <p class="mt-1 text-xs text-slate-500">Lowercase letters, numbers, underscores only — this is the storage key, so changing it later loses the link to previously-submitted answers.</p>
        <x-input-error :messages="$errors->get('field_key')" class="mt-1" />
    </div>

    <div>
        <x-input-label value="Field type" />
        <select name="field_type" x-model="type" class="brand-input-dark" required>
            @foreach (\App\Models\KycCustomField::TYPES as $value => $label)
                <option value="{{ $value }}" @selected(old('field_type', $field->field_type ?? '') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('field_type')" class="mt-1" />
    </div>

    <div x-show="type === 'select'" x-cloak>
        <x-input-label value="Options" />
        <textarea name="options" rows="4" class="brand-input-dark" placeholder="One option per line">{{ old('options', $field ? implode("\n", $field->optionList()) : '') }}</textarea>
        <p class="mt-1 text-xs text-slate-500">One choice per line.</p>
    </div>

    <div>
        <x-input-label value="Sort order" />
        <x-text-input type="number" name="sort_order" value="{{ old('sort_order', $field->sort_order ?? 0) }}" class="w-full" />
        <p class="mt-1 text-xs text-slate-500">Lower numbers show first on the form.</p>
    </div>

    <div class="flex flex-wrap gap-8 pt-2">
        <label class="flex items-center gap-2 text-sm text-white">
            <input type="checkbox" name="is_required" value="1" @checked(old('is_required', $field->is_required ?? false)) class="rounded border-white/20 bg-white/5">
            Required
        </label>
        <label class="flex items-center gap-2 text-sm text-white">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $field->is_active ?? true)) class="rounded border-white/20 bg-white/5">
            Active — shown on the manual KYC form
        </label>
    </div>
</div>

<div class="mt-6 flex items-center gap-3">
    <x-primary-button>{{ $field ? 'Save' : 'Add field' }}</x-primary-button>
    <a href="{{ route('admin.kyc-custom-fields.index') }}" class="text-sm text-slate-400 hover:text-white">Cancel</a>
</div>
