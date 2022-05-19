@component('mail::message')

Hallo {{$user->first_name}},

herzlich willkommen in der Beratergemeinschaft bei himmlischberaten.de. Wir haben dein Profil aktiviert und du kannst ab sofort Buchungen empfangen und deine Beratung gemäß deinen Einstellungen vornehmen. Mit dem hinterlegen deiner Verfügbarkeiten erhöhst du die
Wahrscheinlichkeit einer Buchung und kannst in deinem <a href="{{route('coach')}}">Profil</a> jederzeit deine Angaben anpassen.

Wir wünschen dir viel Erfolg mit deiner Beratung.

Herzliche Grüße,<br>
Dein Team von himmlischberaten.de

<p><a href="{{route('imprint')}}">Impressum</a> | <a href="{{route('data-protection')}}">Datenschutz</a></p>
@endcomponent