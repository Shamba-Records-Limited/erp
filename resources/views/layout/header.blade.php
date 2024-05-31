<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center">

        @php $user = Auth::user(); @endphp
        <a class="navbar-brand brand-logo" href="{{ url('/') }}">
            @if($user && $user->cooperative && $user->cooperative->logo && $user->cooperative->logo  !== null)
                <img src="{{ url('assets/images/logo.svg') }}" alt="logo"/>
            @else
                <img src="{{ url('assets/images/favicon.png') }}" alt="logo"/>
            @endif
        </a>
        <a class="navbar-brand brand-logo-mini" href="{{ url('/') }}">
            @if($user && $user->cooperative && $user->cooperative->logo !== null)
                <img src="{{ url('assets/images/logo-mini.svg') }}" alt="logo"/>
            @else
                <img src="{{ url('assets/images/favicon.png') }}" alt="logo"/>
            @endif
        </a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button"
                data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
        </button>
        <ul class="navbar-nav navbar-nav-left header-links">
            {{--      <li class="nav-item d-none d-xl-flex">--}}
            {{--        <a href="#" class="nav-link">Schedule <span class="badge badge-primary ml-1">New</span>--}}
            {{--        </a>--}}
            {{--      </li>--}}
            {{--      <li class="nav-item active d-none d-lg-flex">--}}
            {{--        <a href="#" class="nav-link">--}}
            {{--          <i class="mdi mdi-elevation-rise"></i>Reports</a>--}}
            {{--      </li>--}}
            {{--      <li class="nav-item d-none d-md-flex">--}}
            {{--        <a href="#" class="nav-link">--}}
            {{--          <i class="mdi mdi-bookmark-plus-outline"></i>Score</a>--}}
            {{--      </li>--}}
            {{--      <li class="nav-item dropdown d-none d-lg-flex">--}}
            {{--        <a class="nav-link dropdown-toggle px-0" id="quickDropdown" href="#" data-toggle="dropdown" aria-expanded="false"> Quick Links </a>--}}
            {{--        <div class="dropdown-menu dropdown-menu-right navbar-dropdown pt-3" aria-labelledby="quickDropdown">--}}
            {{--          <a href="#" class="dropdown-item">Schedule <span class="badge badge-primary ml-1">New</span></a>--}}
            {{--          <a href="#" class="dropdown-item"><i class="mdi mdi-elevation-rise"></i>Reports</a>--}}
            {{--          <a href="#" class="dropdown-item"><i class="mdi mdi-bookmark-plus-outline"></i>Score</a>--}}
            {{--        </div>--}}
            {{--      </li>--}}
        </ul>
        <ul class="navbar-nav navbar-nav-right">
            {{--      <li class="nav-item dropdown">--}}
            {{--        <a class="nav-link count-indicator dropdown-toggle" id="messageDropdown" href="#" data-toggle="dropdown" aria-expanded="false">--}}
            {{--          <i class="mdi mdi-file-outline"></i>--}}
            {{--          <span class="count">7</span>--}}
            {{--        </a>--}}
            {{--        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0" aria-labelledby="messageDropdown">--}}
            {{--          <a class="dropdown-item py-3">--}}
            {{--            <p class="mb-0 font-weight-medium float-left">You have 7 unread mails </p>--}}
            {{--            <span class="badge badge-pill badge-primary float-right">View all</span>--}}
            {{--          </a>--}}
            {{--          <div class="dropdown-divider"></div>--}}
            {{--          <a class="dropdown-item preview-item">--}}
            {{--            <div class="preview-thumbnail">--}}
            {{--              <img src="{{ url('assets/images/avatar.png') }}" alt="image" class="img-sm profile-pic"> </div>--}}
            {{--            <div class="preview-item-content flex-grow py-2">--}}
            {{--              <p class="preview-subject ellipsis font-weight-medium text-dark">Marian Garner </p>--}}
            {{--              <p class="font-weight-light small-text"> The meeting is cancelled </p>--}}
            {{--            </div>--}}
            {{--          </a>--}}
            {{--          <a class="dropdown-item preview-item">--}}
            {{--            <div class="preview-thumbnail">--}}
            {{--              <img src="{{ url('assets/images/avatar.png') }}" alt="image" class="img-sm profile-pic"> </div>--}}
            {{--            <div class="preview-item-content flex-grow py-2">--}}
            {{--              <p class="preview-subject ellipsis font-weight-medium text-dark">David Grey </p>--}}
            {{--              <p class="font-weight-light small-text"> The meeting is cancelled </p>--}}
            {{--            </div>--}}
            {{--          </a>--}}
            {{--          <a class="dropdown-item preview-item">--}}
            {{--            <div class="preview-thumbnail">--}}
            {{--              <img src="{{ url('assets/images/avatar.png') }}" alt="image" class="img-sm profile-pic"> </div>--}}
            {{--            <div class="preview-item-content flex-grow py-2">--}}
            {{--              <p class="preview-subject ellipsis font-weight-medium text-dark">Travis Jenkins </p>--}}
            {{--              <p class="font-weight-light small-text"> The meeting is cancelled </p>--}}
            {{--            </div>--}}
            {{--          </a>--}}
            {{--        </div>--}}
            {{--      </li>--}}
            {{--      <li class="nav-item dropdown">--}}
            {{--        <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">--}}
            {{--          <i class="mdi mdi-bell-outline"></i>--}}
            {{--          <span class="count bg-success">4</span>--}}
            {{--        </a>--}}
            {{--        <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0" aria-labelledby="notificationDropdown">--}}
            {{--          <a class="dropdown-item py-3 border-bottom">--}}
            {{--            <p class="mb-0 font-weight-medium float-left">4 new notifications </p>--}}
            {{--            <span class="badge badge-pill badge-primary float-right">View all</span>--}}
            {{--          </a>--}}
            {{--          <a class="dropdown-item preview-item py-3">--}}
            {{--            <div class="preview-thumbnail">--}}
            {{--              <i class="mdi mdi-alert m-auto text-primary"></i>--}}
            {{--            </div>--}}
            {{--            <div class="preview-item-content">--}}
            {{--              <h6 class="preview-subject font-weight-normal text-dark mb-1">Application Error</h6>--}}
            {{--              <p class="font-weight-light small-text mb-0"> Just now </p>--}}
            {{--            </div>--}}
            {{--          </a>--}}
            {{--          <a class="dropdown-item preview-item py-3">--}}
            {{--            <div class="preview-thumbnail">--}}
            {{--              <i class="mdi mdi-settings m-auto text-primary"></i>--}}
            {{--            </div>--}}
            {{--            <div class="preview-item-content">--}}
            {{--              <h6 class="preview-subject font-weight-normal text-dark mb-1">Settings</h6>--}}
            {{--              <p class="font-weight-light small-text mb-0"> Private message </p>--}}
            {{--            </div>--}}
            {{--          </a>--}}
            {{--          <a class="dropdown-item preview-item py-3">--}}
            {{--            <div class="preview-thumbnail">--}}
            {{--              <i class="mdi mdi-airballoon m-auto text-primary"></i>--}}
            {{--            </div>--}}
            {{--            <div class="preview-item-content">--}}
            {{--              <h6 class="preview-subject font-weight-normal text-dark mb-1">New user registration</h6>--}}
            {{--              <p class="font-weight-light small-text mb-0"> 2 days ago </p>--}}
            {{--            </div>--}}
            {{--          </a>--}}
            {{--        </div>--}}
            {{--      </li>--}}
            <li class="nav-item dropdown d-none d-xl-inline-block">
                <a class="nav-link dropdown-toggle" id="UserDropdown" href="#"
                   data-toggle="dropdown" aria-expanded="false">
                    @if($user)
                        <span class="profile-text d-none d-md-inline-flex">{{ ucwords( strtolower($user->first_name)) }} {{ ucwords(strtolower($user->other_names)) }}</span>

                        @if($user->profile_picture)
                            <img class="img-xs rounded-circle" src="{{url('storage/'.$user->profile_picture)}}"/>
                        @else
                            <img class="img-xs rounded-circle" src="{{ url('assets/images/avatar.png') }}" alt="profile image">
                        @endif
                @endif
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown"
                     aria-labelledby="UserDropdown">
                    <a class="dropdown-item" href="{{ route('change-password') }}"> Change
                        Password </a>

                    <a class="dropdown-item"
                       href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        {{ __('Sign Out') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                          class="d-none">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center"
                type="button" data-toggle="offcanvas">
            <span class="mdi mdi-menu icon-menu"></span>
        </button>
    </div>
</nav>
