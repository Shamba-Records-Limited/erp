<div id="printable">
    <div class="d-flex align-items-center">
        <img src="{{ url('assets/images/shamba_records_logo.jpeg') }}" alt="logo" height="50" width="50" />
       @php $user = Auth::user();
                        
        @endphp

        <div class="coop-profile-image">
            <img class="coop-profile-image" src="{{ asset('argon') }}/img/avatar.jpeg"
                alt="Profile Image">
        </div>

         <p class="profile-name">
            @if($user)
            @if ($user->cooperative)
            <strong>{{ ucwords(strtolower($user->cooperative->name)) }}</strong><br>
            @elseif ($user->miller_admin && $user->miller_admin->miller)
            <strong>{{ ucwords(strtolower($user->miller_admin->miller->name)) }}</strong><br>
            @endif
            <!-- <p class="semi-bold">
            {{ ucwords(strtolower($user->first_name)) }}
            {{ ucwords(strtolower($user->other_names)) }}
            </p> -->
            @endif
        </p>
    </div>
    
    @yield('content')
</div>