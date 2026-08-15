@extends('layouts.admin.app')

@section('title', 'KYC Custom Fields')

@section('content')
    <x-page-header title="KYC Custom Fields" subtitle="Extra fields shown on the manual KYC submission form, beyond document type / front / back / selfie.">
        <x-slot:actions>
            <a href="{{ route('admin.kyc-custom-fields.create') }}" class="brand-btn-primary">Add Field</a>
        </x-slot:actions>
    </x-page-header>

    @if (session('success'))
        <div class="mb-6 rounded-xl border border-brand-emerald/20 bg-brand-emerald/10 px-4 py-3 text-sm text-brand-emerald">
            {{ session('success') }}
        </div>
    @endif

    <x-glass-card>
        <x-data-table>
            <thead>
                <tr>
                    <th>Label</th>
                    <th>Key</th>
                    <th>Type</th>
                    <th>Required</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($fields as $field)
                    <tr>
                        <td class="font-medium text-white">{{ $field->label }}</td>
                        <td class="font-mono text-xs text-slate-400">{{ $field->field_key }}</td>
                        <td>{{ \App\Models\KycCustomField::TYPES[$field->field_type] ?? $field->field_type }}</td>
                        <td>{{ $field->is_required ? 'Yes' : 'No' }}</td>
                        <td><x-badge :status="$field->is_active ? 'active' : 'cancelled'">{{ $field->is_active ? 'Active' : 'Inactive' }}</x-badge></td>
                        <td class="text-right space-x-3">
                            <a href="{{ route('admin.kyc-custom-fields.edit', $field) }}" class="text-brand-blue hover:underline">Edit</a>
                            <form method="POST" action="{{ route('admin.kyc-custom-fields.destroy', $field) }}" class="inline" onsubmit="return confirm('Remove this field? Already-submitted answers are kept.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-brand-danger hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-10 text-slate-400">No custom fields yet — the manual form only asks for document type, front/back, and selfie.</td></tr>
                @endforelse
            </tbody>
        </x-data-table>
    </x-glass-card>
@endsection
