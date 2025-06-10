<nav id="sidebar" class="sidebar-wrapper">
    <!-- App brand starts -->
    <div class="app-brand px-3 py-2 d-flex align-items-center">
        <a href="index.html">
            <img src="assets/images/logo.svg" class="logo" alt="Bootstrap Gallery" />
        </a>
    </div>
    <!-- App brand ends -->

    <!-- Sidebar profile starts -->
    <div class="sidebar-profile">
        <img src="assets/images/user1.png" class="img-3x me-3 rounded-3" alt="Admin Dashboard" />
        <div class="m-0 text-nowrap">
            <p class="m-0">Hello &#128075;</p>
            <h6 class="m-0">{{ auth()->check() ? auth()->user()->name : "Guest User"}}</h6>
        </div>
    </div>
    <!-- Sidebar profile ends -->

    <!-- Sidebar menu starts -->
    <div class="sidebarMenuScroll">
        <ul class="sidebar-menu">
            <li @if(Route::is('dashboard')) class="active current-page" @endif>
                <a href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2"></i>
                    <span class="menu-text">Quick Trading</span>
                </a>
            </li>

            <li @if(Route::is('dashboard-2')) class="active current-page" @endif>
                <a href="{{ route('dashboard-2') }}">
                    <i class="bi bi-terminal-fill"></i>
                    <span class="menu-text">Trade chart 2</span>
                </a>
            </li>

            <li class="treeview  @if(Route::is('deposit.*') || Route::is('payout.*')) active current-page @endif">
                <a href="#!">
                    <i class="bi bi-cash-coin"></i>
                    <span class="menu-text">Finance</span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="{{ route('deposit.index') }}">Deposit</a>
                    </li>
                    <li>
                        <a href="{{ route('payout.index') }}">Withdrawal</a>
                    </li>
                </ul>
            </li>

            <li @if(Route::is('profile.edit')) class="active current-page" @endif>
                <a href="{{ route('profile.edit') }}">
                    <i class="bi bi-person"></i>
                    <span class="menu-text">Profile</span>
                </a>
            </li>

            <li @if(Route::is(config('chatify.routes.prefix'))) class="active current-page" @endif>
                <a href="{{ route(config('chatify.routes.prefix')) }}">
                    <i class="bi bi-chat"></i>
                    <span class="menu-text">Chats</span>
                </a>
            </li>

            <li @if(Route::is('trade.index')) class="active current-page" @endif>
                <a href="{{ route('trade.index') }}">
                    <i class="bi bi-activity"></i>
                    <span class="menu-text">Trades</span>
                </a>
            </li>













            {{-- <li @if(Route::is('dashboard')) class="active current-page" @endif>
                <a href="{{ route('dashboard') }}">
                    <i class="bi bi-pie-chart"></i>
                    <span class="menu-text">Quick Trading</span>
                </a>
            </li>           
            <li>
                <a href="analytics.html">
                    <i class="bi bi-bar-chart-line"></i>
                    <span class="menu-text">Quick Demo Trading</span>
                </a>
            </li>
            <li>
                <a href="widgets.html">
                    <i class="bi bi-box"></i>
                    <span class="menu-text">Shares Trading</span>
                </a>
            </li>
            <li>
                <a href="calendar.html">
                    <i class="bi bi-calendar2"></i>
                    <span class="menu-text">Demo Shares Trading</span>
                </a>
            </li>
            <li class="active current-page">
                <a href="default.html">
                    <i class="bi bi-layout-sidebar"></i>
                    <span class="menu-text">Forex MT4 Trading</span>
                </a>
            </li> --}}




            {{-- <li class="treeview">
                <a href="#!">
                    <i class="bi bi-ui-checks-grid"></i>
                    <span class="menu-text">Forms</span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="form-inputs.html">Form Inputs</a>
                    </li>
                    <li>
                        <a href="form-checkbox-radio.html">Checkbox &amp; Radio</a>
                    </li>
                    <li>
                        <a href="form-file-input.html">File Input</a>
                    </li>
                    <li>
                        <a href="form-validations.html">Validations</a>
                    </li>
                    <li>
                        <a href="date-time-pickers.html">Date Time Pickers</a>
                    </li>
                    <li>
                        <a href="form-layouts.html">Form Layouts</a>
                    </li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#!">
                    <i class="bi bi-window-sidebar"></i>
                    <span class="menu-text">Invoices</span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="create-invoice.html">Create Invoice</a>
                    </li>
                    <li>
                        <a href="view-invoice.html">View Invoice</a>
                    </li>
                    <li>
                        <a href="invoice-list.html">Invoice List</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="tables.html">
                    <i class="bi bi-border-all"></i>
                    <span class="menu-text">Tables</span>
                </a>
            </li>
            <li>
                <a href="subscribers.html">
                    <i class="bi bi-check-circle"></i>
                    <span class="menu-text">Subscribers</span>
                </a>
            </li>
            <li>
                <a href="contacts.html">
                    <i class="bi bi-wallet2"></i>
                    <span class="menu-text">Contacts</span>
                </a>
            </li>
            <li>
                <a href="settings.html">
                    <i class="bi bi-gear"></i>
                    <span class="menu-text">Settings</span>
                </a>
            </li>
            <li>
                <a href="profile.html">
                    <i class="bi bi-person-square"></i>
                    <span class="menu-text">Profile</span>
                </a>
            </li>
            <li class="treeview">
                <a href="#!">
                    <i class="bi bi-code-square"></i>
                    <span class="menu-text">Cards</span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="cards.html">Cards</a>
                    </li>
                    <li>
                        <a href="advanced-cards.html">Advanced Cards</a>
                    </li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#!">
                    <i class="bi bi-pie-chart"></i>
                    <span class="menu-text">Graphs</span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="apex.html">Apex</a>
                    </li>
                    <li>
                        <a href="morris.html">Morris</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="maps.html">
                    <i class="bi bi-pin-map"></i>
                    <span class="menu-text">Maps</span>
                </a>
            </li>
            <li>
                <a href="tabs.html">
                    <i class="bi bi-slash-square"></i>
                    <span class="menu-text">Tabs</span>
                </a>
            </li>
            <li>
                <a href="modals.html">
                    <i class="bi bi-terminal"></i>
                    <span class="menu-text">Modals</span>
                </a>
            </li>
            <li>
                <a href="icons.html">
                    <i class="bi bi-textarea"></i>
                    <span class="menu-text">Icons</span>
                </a>
            </li>
            <li>
                <a href="typography.html">
                    <i class="bi bi-explicit"></i>
                    <span class="menu-text">Typography</span>
                </a>
            </li>
            <li class="treeview">
                <a href="#!">
                    <i class="bi bi-upc-scan"></i>
                    <span class="menu-text">Login/Signup</span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="login.html">Login</a>
                    </li>
                    <li>
                        <a href="signup.html">Signup</a>
                    </li>
                    <li>
                        <a href="forgot-password.html">Forgot Password</a>
                    </li>
                    <li>
                        <a href="reset-password.html">Reset Password</a>
                    </li>
                    <li>
                        <a href="lock-screen.html">Lock Screen</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="page-not-found.html">
                    <i class="bi bi-exclamation-diamond"></i>
                    <span class="menu-text">Page Not Found</span>
                </a>
            </li>
            <li>
                <a href="maintenance.html">
                    <i class="bi bi-exclamation-octagon"></i>
                    <span class="menu-text">Maintenance</span>
                </a>
            </li>
            <li class="treeview">
                <a href="#!">
                    <i class="bi bi-code-square"></i>
                    <span class="menu-text">Nested Menu</span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="#!">Nested 1</a>
                    </li>
                    <li>
                        <a href="#!">
                            Nested 2
                            <i class="bi bi-caret-right-fill"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li>
                                <a href="#!">Nested 2.1</a>
                            </li>
                            <li>
                                <a href="#!">Nested 2.2
                                    <i class="bi bi-caret-right-fill"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <li>
                                        <a href="#!">Nested 2.2.1</a>
                                    </li>
                                    <li>
                                        <a href="#!">Nested 2.2.2</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li> --}}
        </ul>
    </div>
    <!-- Sidebar menu ends -->

</nav>
