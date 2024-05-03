@component('mail::message')
    Hello {{$data['name']}},<br>
    Thank you for shopping with us.<br>

    Regards,
    CEO {{ config('app.name') }}
@endcomponent

