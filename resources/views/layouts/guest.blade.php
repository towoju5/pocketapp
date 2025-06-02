@if ($browser->isMobile())
    @include('layouts.mobile.guest')
@else
    @include('layouts.desktop.guest')
@endif