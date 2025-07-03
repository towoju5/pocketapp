@if ($browser->isMobile() || $browser->isTablet())
    @include('layouts.mobile.app')
@else
    @include('layouts.desktop.app')
@endif
