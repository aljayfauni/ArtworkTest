<nav class="main-header navbar
    {{ config('adminlte.classes_topnav_nav', 'navbar-expand') }}
    {{ config('adminlte.classes_topnav', 'navbar-white navbar-light') }}">

    {{-- Navbar left links --}}
    <ul class="navbar-nav">
        {{-- Left sidebar toggler link --}}
        @include('adminlte::partials.navbar.menu-item-left-sidebar-toggler')

        {{-- Configured left links --}}
        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-left'), 'item')

        {{-- Custom left links --}}
        @yield('content_top_nav_left')
    </ul>

    {{-- Navbar right links --}}
    <ul class="navbar-nav ml-auto">
        {{-- Custom right links --}}
        @yield('content_top_nav_right')

        {{-- Live Clock --}}
        @if(request()->is('dashboard') || (!request()->is('user/*') && !request()->is('profile/*')))

        <div class="row align-items-center justify-content-between">
            <div class="col-md-3 d-flex justify-content-md-end justify-content-start mt-2 mt-md-0">
                <div class="clock-widget"
                    style="
                        padding: 0px 0px;
                        /* background: rgba(255, 255, 255, 0.75); */
                        backdrop-filter: blur(12px);
                        -webkit-backdrop-filter: blur(12px);
                        /* border: 1px solid rgba(226, 232, 240, 0.8); */
                        /* border-radius: 16px; */
                        /* box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.08); */
                        text-align: right;
                        transition: transform 0.2s ease, box-shadow 0.2s ease;
                        
                        /* FIXED WIDTH & LAYOUT BINDINGS */
                        width: max-content;
                        /* min-width: 220px; */
                        display: inline-block;
                    "
                    onmouseover="this.style.transform='translateY(-2px) scale(1.02)'"
                    onmouseout="this.style.transform='translateY(0) scale(1)'">

                    {{-- Live Time --}}
                    <!-- FIXED: Added d-flex alignment so AM/PM doesn't wrap to a new line -->
                    <div class="d-flex align-items-center justify-content-end">
                        <span style="
                            height: 8px;
                            width: 8px;
                            background-color: #10b981;
                            border-radius: 50%;
                            display: inline-block;
                            box-shadow: 0 0 8px #10b981;
                            margin-right: 8px;
                            flex-shrink: 0;
                        "></span>

                        <div id="live-time" style="
                            font-size: 1.2rem;
                            font-weight: 700;
                            letter-spacing: -0.03em;
                            color: #0f172a;
                            font-variant-numeric: tabular-nums;
                            white-space: nowrap;
                        ">--:--:--</div>
                    </div>

                    {{-- Live Date --}}
                    <div id="live-date" style="
                        color: #64748b;
                        font-size: 0.70rem;
                        font-weight: 600;
                        /* margin-top: 2px; */
                        text-transform: uppercase;
                        white-space: nowrap;
                    ">Loading...</div>

                </div>
            </div>
        </div>
        @endif

        {{-- Configured right links --}}
        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-right'), 'item')

        {{-- User menu link --}}
        @if(Auth::user())
        <!-- Notification Scrollable Body -->
                <div id="activityFeedList" style="">
                    @include('partials.artwork_logs')
                </div>
            @if(config('adminlte.usermenu_enabled'))
                @include('adminlte::partials.navbar.menu-item-dropdown-user-menu')
            @else
                @include('adminlte::partials.navbar.menu-item-logout-link')
            @endif
        @endif

        {{-- Right sidebar toggler link --}}
        @if(config('adminlte.right_sidebar'))
            @include('adminlte::partials.navbar.menu-item-right-sidebar-toggler')
        @endif
    </ul>

</nav>
