<ul>
    <li class="flex gap-4 uppercase text-white mb-3 -mt-3">
        <a class="py-2 px-6 rounded shadow-md @if(Route::is('trading.profile')) bg-[#434858] @else bg-gray-900 @endif" href="{{ route('trading.profile') }}">Trading Profile</a>

        <a class="py-2 px-6 rounded shadow-md @if(Route::is('profile.edit')) bg-[#434858] @else bg-gray-900 @endif" href="{{ route('profile.edit') }}">Profile</a>

        <a class="py-2 px-6 rounded shadow-md @if(Route::is('profile.security')) bg-[#434858] @else bg-gray-900 @endif" href="{{ route('profile.security') }}">Security</a>

        <a class="py-2 px-6 rounded shadow-md @if(Route::is('trade.index')) bg-[#434858] @else bg-gray-900 @endif" href="{{ route('trade.index') }}">Trading history</a>
       {{--  <a class="py-2 px-6 rounded shadow-md @if(Route::is('deposit.create')) bg-[#434858] @else bg-gray-900 @endif" href="{{ route('deposit.create') }}">My Safe</a> --}}
    </li>
</ul>