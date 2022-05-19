@component('mail::message')
<p>Hallo {{$user->first_name.' '.$user->last_name}},</p>
<p>wir hoffen du hattest eine wertvolle Beratung und bist bei deinem Anliegen einen großen Schritt weiter gekommen!
</p>

<p>wie gewünscht haben wir dein Nutzerkonto gelöscht. Es wurden alle gesetzlich vorgeschriebenen Daten unwiederbringlich gelöscht. Über ein Feedback zu unserer Plattform würden wir uns freuen.
</p>

<p>Wir hoffen, dich bald wieder bei himmlischberaten.de begrüßen zu dürfen.</p>

<p>Herzliche Grüße,<br>
Dein Team von himmlischberaten.de</p>
@endcomponent