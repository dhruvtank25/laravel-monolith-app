@component('mail::message')

Hallo {{$user->first_name}},

Du hast dein Passwort vergessen? Unter folgendem Link kannst du dir ein neues Passwort erstellen:

<a href="{{$link}}">Neues Passwort erstellen</a>

Solltest du das Passwort nicht selbst angefordert haben, wende dich bitte an den
himmlischberaten.de Kundenservice unter kontakt@himmlischberaten.de.

Herzliche Grüße,<br>
Dein Team von himmlischberaten.de

<a href="{{route('imprint')}}">Impressum</a> | <a href="{{route('data-protection')}}">Datenschutz</a>
@endcomponent