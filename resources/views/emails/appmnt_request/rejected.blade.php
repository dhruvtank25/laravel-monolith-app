@component('mail::message')

<p>Hallo {{$user->first_name}},</p>

<p>leider hat dein Berater an keinem deiner Terminvorschläge Zeit und kann dir keinen alternativen Termin vorschlagen. Über die Beratersuche kannst du dir einfach einen anderen passenden Berater auswählen der zu deiner gewünschten Zeit eine Verfügbarkeit hinterlegt hat.</p>

<p>Bei Fragen kannst du dich gerne an kontakt@himmlischberaten.de wenden. Wir helfen dir gerne weiter.</p>


<p>Herzliche Grüße,<br>
dein Team von himmlischberaten.de</p>
@endcomponent