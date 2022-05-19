@component('mail::message')
@php
    $coach = $appointment->coach; 
    $user  = $appointment->user;
@endphp
<p>Hallo {{$coach->first_name.' '.$coach->last_name}},</p>
<p>wir hoffen du hattest ein wertvolles Beratungsgespräch und konntest deinem Kunden bei seinem
Anliegen helfen!</p>

<p>Die Rechnung zur Nutzung von himmlischberaten.de haben wir dieser E-Mail beigefügt. Die
Plattformgebühr wird mit dem Beratungsentgelt verrechnet.</p>

<p>Bei Fragen kannst du dich gerne an uns wenden. Unser Kundensupport ist unter <a href="mailto:kontakt@himmlischberaten.de">kontakt@himmlischberaten.de</a> für dich da.</p>

<p>Herzliche Grüße,<br>
Dein Team von himmlischberaten.de</p>
@endcomponent