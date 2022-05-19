@component('mail::message')
<p>Hallo {{$user->first_name.' '.$user->last_name}},</p>
@if($appointment->cancelled_by=='coach')
<p>dein Berater hat die Buchung storniert. Sollte die Buchung deinem Zahlungsmittel bereits belastet worden sein, so wird der Betrag i.d.R. binnen weniger Tage rückerstattet.</p>

<p>Auf himmlischberaten.de kannst du einfach zu deinem Anliegen einen neuen Termin bei einem
Berater deiner Wahl buchen.</p>
@elseif($appointment->cancel_fee_percent>0)
<p>wie vereinbart haben wir deine Buchung storniert. Hierfür fallen 60% des Beratungsentgeltes an. Die Differenz haben wir deinem verwendeten Zahlungsmittel gutgeschrieben.</p>
@else
<p>wie vereinbart haben wir deine Buchung kostenfrei storniert. Sollte die Buchung deinem
Zahlungsmittel bereits belastet worden sein, so wird der Betrag i.d.R. binnen weniger Tage
rückerstattet.</p>
@endif

<p>Anbei erhältst du die Gutschrift deiner Rechnung.</p>

<p>Herzliche Grüße,<br>
Dein Team von himmlischberaten.de</p>
@endcomponent