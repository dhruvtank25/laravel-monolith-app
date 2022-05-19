@component('mail::message')

<p>Hallo {{$user->first_name.' '.$user->last_name}},</p>

<p>du hast eine Terminanfrage mit folgenden Vorschlägen herhalten:</p>




@foreach ($slots as $slot)
@php
    $start =  \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$slot->start);
    $end =  \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$slot->end);
@endphp
<p>Vorschlag {{$loop->iteration}}: {{$start->format('d.m.Y')}} , {{$start->format('H:i')}} Uhr, {{$end->diffInMinutes($start)}} Minuten, {{$slot->mode}} </p>
@endforeach


<p>In deinem Profil kannst du einen der Terminvorschläge bestätigen oder einen Alternativtermin vorschlagen.</p>

<p>Herzliche Grüße,<br>
dein Team von himmlischberaten.de</p>
@endcomponent