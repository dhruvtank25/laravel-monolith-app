@component('mail::message')

Hallo {{$coach->first_name}},

herzlich willkommen bei himmlischberaten.de – deinem Portal für individuelle Lebensberatung! Bitte bestätige deine E-Mail-Adresse durch klick auf folgenden Link.

Zur Sicherheit deiner Daten ist die Bestätigung deiner E-Mail-Adresse nur mit demselben Gerät
möglich, das du auch für deine Registrierung verwendet hast.

<a href="{{$verificationUrl}}">E-Mail-Adresse bestätigen</a>

Anschließend ergänzt du ganz einfach dein Profil in deinem Dashboard. Bei Fragen kannst du uns
gerne eine Nachricht an <a href="mailto:kontakt@himmlischberaten.de">kontakt@himmlischberaten.de</a> schreiben.

Herzliche Grüße,<br>
Dein Team von himmlischberaten.de

<a href="{{route('imprint')}}">Impressum</a> | <a href="{{route('data-protection')}}">Datenschutz</a>
@endcomponent