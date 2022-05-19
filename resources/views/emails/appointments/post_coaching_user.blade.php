@component('mail::message')
@php
    $coach = $appointment->coach; 
    $user  = $appointment->user;
@endphp
<p>Hallo {{$user->first_name.' '.$user->last_name}},</p>
<p>wir hoffen du hattest eine wertvolle Beratung und bist bei deinem Anliegen einen großen Schritt weiter gekommen!
</p>

@if($user->roles->name=='guest')
<p>Sofern du deinen Berater noch nicht bewertet hast, kannst du über folgenden Link eine <a href="{{route('guest_user.rate-coach', ['appointment_id' => $appointment->id])}}">Bewertung</a> abgeben.</p>
@else
<p>Sofern du deinen Berater noch nicht bewertet hast, kannst du über folgenden Link eine <a href="{{route('user.rate-coach', ['appointment_id' => $appointment->id])}}">Bewertung</a> abgeben.</p>
@endif

<p>Herzliche Grüße,<br>
Dein Team von himmlischberaten.de</p>
@endcomponent