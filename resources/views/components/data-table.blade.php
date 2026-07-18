{{-- Wraps a <table> with the app-wide dark data-table styling.
     Usage: <x-data-table><thead>...</thead><tbody>...</tbody></x-data-table> --}}
<div class="overflow-x-auto rounded-xl border border-glass-border">
    <table {{ $attributes->class(['w-full text-left text-sm']) }}>
        {{ $slot }}
    </table>
</div>
