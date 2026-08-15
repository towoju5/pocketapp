<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KycCustomField;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KycCustomFieldController extends Controller
{
    public function index()
    {
        $fields = KycCustomField::orderBy('sort_order')->orderBy('label')->get();

        return view('admin.kyc-custom-fields.index', compact('fields'));
    }

    public function create()
    {
        return view('admin.kyc-custom-fields.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);

        KycCustomField::create($validated);

        return redirect()->route('admin.kyc-custom-fields.index')->with('success', 'Field added to the manual KYC form.');
    }

    public function edit(KycCustomField $kycCustomField)
    {
        return view('admin.kyc-custom-fields.edit', ['field' => $kycCustomField]);
    }

    public function update(Request $request, KycCustomField $kycCustomField)
    {
        $validated = $this->validated($request, $kycCustomField);

        $kycCustomField->update($validated);

        return redirect()->route('admin.kyc-custom-fields.index')->with('success', 'Field updated.');
    }

    public function destroy(KycCustomField $kycCustomField)
    {
        // Deliberately hard-deletes the definition, not the answers already
        // submitted under it — kyc_verifications.custom_field_values keeps
        // whatever was collected while this field existed; admin/kyc/show
        // just falls back to the raw field_key as a label for orphaned keys.
        $kycCustomField->delete();

        return redirect()->route('admin.kyc-custom-fields.index')->with('success', 'Field removed.');
    }

    private function validated(Request $request, ?KycCustomField $field = null): array
    {
        $validated = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'field_key' => [
                'required', 'string', 'max:100', 'regex:/^[a-z0-9_]+$/',
                Rule::unique('kyc_custom_fields', 'field_key')->ignore($field?->id),
            ],
            'field_type' => ['required', Rule::in(array_keys(KycCustomField::TYPES))],
            'options' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        // Textarea of options (one per line) -> JSON array. Only meaningful
        // for field_type=select; harmless (just unused) for every other type.
        $validated['options'] = $validated['field_type'] === 'select'
            ? array_values(array_filter(array_map('trim', explode("\n", $validated['options'] ?? ''))))
            : null;

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_required'] = $request->boolean('is_required');
        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }
}
