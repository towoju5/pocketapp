<nav id="sidebar" class="">
    <div class="sidebar-header">
        <img src="assets/img/bootstraper-logo.png" alt="bootraper logo" class="app-logo">
    </div>
    <ul class="list-unstyled components text-secondary">
        <li>
            <a  class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-home"></i>
                Dashboard
            </a>
        </li>
        <li>
            <a  class="{{ request()->routeIs('admin.assets.*') ? 'active' : '' }}" href="{{ route('admin.assets.index') }}">
                <i class="fas fa-file-alt"></i>
                Assets
            </a>
        </li>
        <li>
            <a  class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                <i class="fas fa-users"></i>
                Users
            </a>
        </li>
        <li>
            <a  class="{{ request()->routeIs('admin.cashbacks.*') ? 'active' : '' }}" href="{{ route('admin.cashbacks.index') }}">
                <i class="fas fa-bank"></i>
                Cashbacks
            </a>
        </li>
        <li>
            <a class="{{ request()->routeIs('admin.signals.*') ? 'active' : '' }}" href="{{ route('admin.signals.index') }}">
                <i class="fas fa-chart-bar"></i>
                Signals</a>
        </li>
        <!-- <li>
            <a href="icons.html"><i class="fas fa-icons"></i> Icons</a>
        </li>
        <li>
            <a href="#uielementsmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle no-caret-down"><i class="fas fa-layer-group"></i> UI Elements</a>
            <ul class="collapse list-unstyled" id="uielementsmenu">
                <li>
                    <a href="ui-buttons.html"><i class="fas fa-angle-right"></i> Buttons</a>
                </li>
                <li>
                    <a href="ui-badges.html"><i class="fas fa-angle-right"></i> Badges</a>
                </li>
                <li>
                    <a href="ui-cards.html"><i class="fas fa-angle-right"></i> Cards</a>
                </li>
                <li>
                    <a href="ui-alerts.html"><i class="fas fa-angle-right"></i> Alerts</a>
                </li>
                <li>
                    <a href="ui-tabs.html"><i class="fas fa-angle-right"></i> Tabs</a>
                </li>
                <li>
                    <a href="ui-date-time-picker.html"><i class="fas fa-angle-right"></i> Date & Time Picker</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="#authmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle no-caret-down"><i class="fas fa-user-shield"></i> Authentication</a>
            <ul class="collapse list-unstyled" id="authmenu">
                <li>
                    <a href="login.html"><i class="fas fa-lock"></i> Login</a>
                </li>
                <li>
                    <a href="signup.html"><i class="fas fa-user-plus"></i> Signup</a>
                </li>
                <li>
                    <a href="forgot-password.html"><i class="fas fa-user-lock"></i> Forgot password</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="#pagesmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle no-caret-down"><i class="fas fa-copy"></i> Pages</a>
            <ul class="collapse list-unstyled" id="pagesmenu">
                <li>
                    <a href="blank.html"><i class="fas fa-file"></i> Blank page</a>
                </li>
                <li>
                    <a href="404.html"><i class="fas fa-info-circle"></i> 404 Error page</a>
                </li>
                <li>
                    <a href="500.html"><i class="fas fa-info-circle"></i> 500 Error page</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="users.html"><i class="fas fa-user-friends"></i>Users</a>
        </li>
        <li>
            <a href="settings.html"><i class="fas fa-cog"></i>Settings</a>
        </li> -->
    </ul>
</nav>