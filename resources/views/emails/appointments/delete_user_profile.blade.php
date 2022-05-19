@component('mail::message')
<p>Hallo {{$user->first_name.' '.$user->last_name}},</p>

<p>wie gewünscht haben wir dein Nutzerkonto gelöscht. Wir hoffen, dich bald wieder bei himmlischberaten.de begrüßen zu dürfen. Über ein Feedback zu unserer Plattform würden wir uns freuen.</p>

<p>Herzliche Grüße,<br>
Dein Team von himmlischberaten.de</p>
@endcomponent