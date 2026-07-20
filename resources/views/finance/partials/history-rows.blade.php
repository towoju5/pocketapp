@forelse ($transactions as $item)
    <tr class="border-t border-[#1c243c]">
        <td class="px-4 py-3 font-mono text-xs text-[#7c86a3]">{{ $item->uuid }}</td>
        <td class="px-4 py-3 text-[#7c86a3]">{{ $item->created_at->format('Y-m-d H:i:s') }}</td>
        <td class="px-4 py-3">${{ number_format($item->amountFloat, 2) }}</td>
        <td class="px-4 py-3">{{ ucfirst($item->type) }}</td>
        <td class="px-4 py-3">
            @if($item->confirmed)
                <span class="bg-[#16c087]/15 text-[#16c087] px-2 py-1 rounded text-xs">Confirmed</span>
            @else
                <span class="bg-[#7c86a3]/15 text-[#7c86a3] px-2 py-1 rounded text-xs">Pending</span>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="px-4 py-10 text-center text-[#7c86a3]">No transactions match these filters.</td>
    </tr>
@endforelse
