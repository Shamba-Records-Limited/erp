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
                <div class="media-body mr-2 d-none d-lg-block">
                    <!-- Display user's name on the left -->
                    <span class="mb-0 text-md font-weight-bold">
                        {{ ucwords(strtolower(Auth::user()->first_name . ' ' . Auth::user()->other_names)) }}
                    </span>
                </div>
                <div class="rounded-full position-relative pl-2 d-flex align-items-center">
                    @if(Auth::user()->profile_picture)
                        <!-- Display user's profile picture on the right, fully rounded -->
                        <img src="{{ url('storage/' . Auth::user()->profile_picture) }}" alt="Profile Picture"
                             class="img-fluid rounded-circle bg-light" style="width:50px; height:50px;">
                    @else
                        <!-- Fallback to default avatar -->
                        <img src="{{ url('assets/images/avatar.png') }}" alt="Default Avatar"
                             class="img-fluid rounded-circle bg-light" style="width:50px; height:50px;">
                    @endif
                    <!-- Dropdown arrow icon using Font Awesome -->
                    <i class="fas fa-caret-down ml-2"></i>
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
