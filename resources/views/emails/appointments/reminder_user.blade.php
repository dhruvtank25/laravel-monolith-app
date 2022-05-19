@component('mail::message')
@php
    $coach = $appointment->coach; 
    $user = $appointment->user;
@endphp
<p>Hallo {{$user->first_name.' '.$user->last_name}},</p>

@if($day=='tommorrow')
<p>bald ist es soweit und deine Beratung findet statt:</p>
@else
<p>In Kürze startet dein Beratungsgespräch. Nachfolgend die Informationen zu deiner Buchung.</p>
@endif
<table border="1" width="750px" style="border-collapse: collapse;">
	<tr>
		<td>Buchungsnummer: {{$appointment->id}}</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>
			<b>Dein Beratungstermin:</b> 
			<br>Datum: {{$appointment->start->format('d.m.Y')}}<br>Startzeit: {{$appointment->start->format('H:i')}} Uhr<br>Dauer: {{$appointment->end->diffInMinutes($appointment->start)}} Minuten
			<br>
		@if($appointment->mode=='offline')
			Ort: offline:<br>{{$coach->street}}<br>{{$coach->post_code}} {{$coach->place}}
		</td>
		@else
			Ort: online<br>
		</td>
		@endif
		<td>
			<b>Dein Berater:</b><br>{{$coach->company_name}}<br>{{$coach->first_name}} {{$coach->last_name}}<br>{{$coach->street}}<br>{{$coach->post_code}} {{$coach->place}}<br><a href="{{route('coach-detail',['name'=>$coach->first_name.'-'.$coach->last_name,'id'=>$coach->id])}}">Zum Profil</a>
		</td>
	</tr>
</table>
<br>
@if($day=='today')
@php
	$booking_url = $user->roles->name=='guest'?URL::signedRoute('guest.login', ['user' => $user->id]):route('user.bookings');
@endphp
@if($appointment->mode=='offline')
Über folgenden <a href="{{$booking_url}}">Link</a> kommst du zu deiner Buchung.
@else
Über folgenden <a href="{{$booking_url}}">Link</a>  kannst du deine Beratung starten. Bitte beachte dass die Anwendung mit dem "Internet Explorer" nur eingeschränkt zur Verfügung steht.  
@endif
@endif
<p>Wir wünschen dir ein wertvolles Gespräch um bei deinem Anliegen weiter zu kommen!</p>

<p>Herzliche Grüße,<br>
Dein Team von himmlischberaten.de</p>
@endcomponent