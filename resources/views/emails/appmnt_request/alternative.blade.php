@component('mail::message')

<p>Hallo {{$user->first_name}},</p>

<p>leider hat dein Berater an keinem deiner Terminvorschläge Zeit, hat allerdings alternative
    Terminvorschläge für dich:</p>



@foreach ($slots as $slot)
@php
    $start =  \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$slot->start);
    $end =  \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$slot->end);
@endphp
<p>Vorschlag {{$loop->iteration}}: {{$start->format('d.m.Y')}} , {{$start->format('H:i')}} Uhr, {{$end->diffInMinutes($start)}} Minuten, {{$slot->mode}} </p>
@endforeach


<p>In deinem Profil kannst du einen der Terminvorschläge ganz einfach bestätigen und die Buchung
    abschließen.</p>

<p>Sollte kein Termin bei dir passen, musst du nichts weiter tun. Nach 24 Stunden verlieren die
    Vorschläge Ihre Gültigkeit.</p>

<p>Wir wünschen dir ein wertvolles Gespräch um bei deinem Anliegen weiter zu kommen!</p>

<p>Herzliche Grüße,<br>
dein Team von himmlischberaten.de</p>
@endcomponent