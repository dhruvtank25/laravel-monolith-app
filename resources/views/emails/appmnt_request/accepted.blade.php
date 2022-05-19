@component('mail::message')

Hallo {{$user->first_name}},


<p>dein Berater hat folgenden Terminvorschlag bestätigt:</p>

{{$slot->start->format('d.m.Y')}}, {{$slot->start->format('H:i')}} Uhr, {{$slot->end->diffInMinutes($slot->start)}} Minuten, {{$slot->mode}}


Über folgenden Link kannst du deine Buchung abschließen:
@component('mail::button', ['url' => route('book-coach', ['request_id' => $appRequest->id])])
Link zur Buchung
@endcomponent
<p>Wir wünschen dir ein wertvolles Gespräch um bei deinem Anliegen weiter zu kommen!</p>


Herzliche Grüße,<br>
Dein Team von himmlischberaten.de
@endcomponent
