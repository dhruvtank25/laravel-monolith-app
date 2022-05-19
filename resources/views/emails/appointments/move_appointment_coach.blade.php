@component('mail::message')
<p>Hallo {{$coach->first_name.' '.$coach->last_name}},</p>
<p>deine Buchung wurde verschoben. Nachfolgend haben wir dir die neuen Buchungsdaten </p>
<p>zusammengefasst:</p>
<table cellpadding="10px" border="1" width="750px" style="border-collapse: collapse;">
	<tr>
		<td>Buchungsnummer: {{$appointment->id}}</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td><b>Dein Beratungstermin:</b> <br>Datum: {{$appointment->start->format('d.m.Y')}}<br>Startzeit: {{$appointment->start->format('H:i')}} Uhr<br>Dauer: {{$appointment->end->diffInMinutes($appointment->start)}} Minuten<br>
		@if($appointment->mode=='offline')
			Ort: offline:<br>{{$coach->street}}, {{$coach->house_no}} <br>{{$coach->post_code}} {{$coach->place}}</td>
		@else
			Ort: online<br></td>
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