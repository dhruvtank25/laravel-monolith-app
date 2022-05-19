@component('mail::message')

Hallo {{$coach->first_name.' '.$coach->last_name}},

vielen Dank, dass Sie himmlischberaten.de nutzen. Nachfolgend finden Sie Ihre
Anmeldeinformationen.

@component('mail::panel')
username: {{$coach->email}}
<br>
password: {{$password}}
@endcomponent
<br>

Unter <a href="{{env('APP_URL', 'https://himmlischberaten.de').'/login'}}">{{env('APP_URL', 'https://himmlischberaten.de').'/login'}}</a> können Sie einfach Ihr Profil erstellen. Bei Fragen unterstützen wir Sie gerne. Schreiben Sie uns einfach eine Nachricht an <a href="mailto:{{env('ADMIN_EMAIL', 'kontakt@himmlischberaten.de')}}">{{env('ADMIN_EMAIL', 'kontakt@himmlischberaten.de')}}</a>.

Herzliche Grüße,<br>
{{ env('MAIL_THANK_BY', 'Ihr Team von himmlischberaten.de') }}
@endcomponent