<!-- Top navbar -->
<nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
    <div class="container-fluid d-flex justify-content-between">
        <div class="d-flex flex-column wallet-container">
            <!-- Brand -->
            <a class="h4 mb-0 text-white text-uppercase" href="{{ route('home') }}">{{ __('Dashboard') }}</a>

            <!-- Wallet Section on the far left below the Dashboard -->
            <div class="d-flex mt-3" id="wallet_cont">
                <!-- Wallet content goes here -->
                <div class="wallet-card p-3">
                    <!-- Your wallet card HTML here -->
                </div>
            </div>

            <!-- Back Button (only displayed if not on the miller or cooperative dashboard) -->
            @if (!in_array(Route::currentRouteName(), ['miller-admin.dashboard', 'cooperative-admin.dashboard']))
            <div class="mt-3">
                <button class="btn btn-secondary" onclick="history.back()">
                    <i class="fas fa-arrow-left"></i> <!-- Left Arrow Icon -->
                </button>
            </div>
            @endif

        </div>

        <!-- User Dropdown on the far right -->
        <ul class="navbar-nav align-items-center">
            <li class="nav-item dropdown">
                <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="media align-items-center">
                        <span class="avatar avatar-sm rounded-circle">
                            <img alt="Image placeholder" src="{{ asset('argon') }}/img/theme/team-4-800x800.jpg">
                        </span>
                        <div class="media-body ml-2 d-none d-lg-block">
                            <span class="mb-0 text-sm font-weight-bold">{{ auth()->user()->name }}</span>
                        </div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <i class="ni ni-single-02"></i>
                        <span>{{ __('My profile') }}</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="ni ni-user-run"></i>
                        <span>{{ __('Logout') }}</span>
                    </a>
                </div>
            </li>
        </ul>
    </div>
</nav>
