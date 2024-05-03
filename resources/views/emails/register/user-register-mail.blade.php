@component('mail::message')
    Hello {{ $data['name'] }},<br>
    Welcome to {{ config('app.name') }}, your account has been created. Login with the following credentials <br>
    Email: <strong>{{ $data['email'] }} </strong><br>
    Password: <strong>{{$data['password']}} </strong> <br>
    url ={{ config('app.url') }} <br>

    Regards,
    CEO {{ config('app.name') }}
@endcomponent
