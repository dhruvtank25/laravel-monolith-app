@component('mail::message')
@php
    $coach = $appointment->coach; 
    $user = $appointment->user;
@endphp
<p>Hallo {{$coach->first_name.' '.$coach->last_name}},</p>

@if($day=='tommorrow')
<p>bald ist es soweit und deine Beratung findet statt:</p>
@else
<p>In Kürze startet dein Beratungsgespräch. Nachfolgend die Informationen zu deiner Buchung.</p>
@endif
<table cellpadding="10px" border="1" width="750px" style="border-collapse: collapse;">
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
		<td style="vertical-align: top;">
			<b>Dein Kunde:<br></b>
			@if($user->is_anonymous)
			    {{$user->user_name}}
			@else
			    {{$user->first_name}} {{$user->last_name}}
			    <br>{{$user->street}}
			    <br>{{$user->post_code}} {{$user->place}}
			@endif
		</td>
	</tr>
</table>
<br>
<p>Wir wünschen dir viel Erfolg bei deinem Beratungsgespräch! Du findest die Details zu deiner Buchung auch in deinem <a href="{{route('coach.bookings')}}">Profil.</a></p>

<p>Herzliche Grüße,<br>
Dein Team von himmlischberaten.de</p>
@endcomponent