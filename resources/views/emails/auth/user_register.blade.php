@component('mail::message')

Hallo {{$user->first_name}},

herzlich willkommen bei himmlischberaten.de – deinem Portal für individuelle Lebensberatung! Du hast dich erfolgreich angemeldet. Bitte bestätige deine E-Mail-Adresse durch klick auf folgenden Link und starte sofort die Suche nach deinem passenden Berater.

Zur Sicherheit deiner Daten ist die Bestätigung deiner E-Mail-Adresse nur mit demselben Gerät
möglich, das du auch für deine Registrierung verwendet hast.

<a href="{{$verificationUrl}}">E-Mail-Adresse bestätigen</a>

Herzliche Grüße,<br>
Dein Team von himmlischberaten.de

<a href="{{route('imprint')}}">Impressum</a> | <a href="{{route('data-protection')}}">Datenschutz</a>
@endcomponent