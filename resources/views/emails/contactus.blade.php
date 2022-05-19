@component('mail::message')
# New contact request

<b>Name:</b> {{$name}}

<b>Email:</b> {{$email}}

@component('mail::panel')
{{$description}}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent