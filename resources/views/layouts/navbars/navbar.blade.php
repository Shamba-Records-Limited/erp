@auth()
<div class="mb-10">

    @include('layouts.navbars.navs.auth')
</div>
@endauth

@guest()
@include('layouts.navbars.navs.guest')
@endguest