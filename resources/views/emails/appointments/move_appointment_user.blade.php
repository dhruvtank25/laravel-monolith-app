@component('mail::message')
<p>Hallo {{$user->first_name.' '.$user->last_name}},</p>
<p>wie vereinbart bestätigen wir dir die Terminverschiebung.</p>

<table cellpadding="10px" border="1" width="750px" style="border-collapse: collapse;">
	<tr>
		<td>Buchungsnummer: {{$appointment->id}}</td>
		<td></td>
	</tr>
	<tr>
		<td><b>Rechnungsadresse:<br></b>{{$user->first_name.' '.$user->last_name}}<br>{{$user->street}},{{$user->house_no}}<br>{{$user->post_code}} {{$user->place}}</td>
		<td style="vertical-align: top"><b>Zahlmethode:</b> Kreditkarte</td>
	</tr>
	<tr>
		<td><b>Dein Beratungstermin:<br></b>Datum:  {{$appointment->start->format('d.m.Y')}} <br>Startzeit:  {{$appointment->start->format('H:i')}} Uhr<br>Dauer: {{$appointment->end->diffInMinutes($appointment->start)}} Minuten<br>@if($appointment->mode=='offline')
			Ort: offline:<br>{{$coach->street}}<br>{{$coach->post_code}} {{$coach->place}}</td>
		@else
			Ort: online<br></td>
		@endif
		<td><b>Dein Berater:</b><br>{{ $coach->coach_company}}<br>{{$coach->first_name}} {{$coach->last_name}}<br>{{$coach->street}},{{$coach->house_no}}<br>{{$coach->post_code}} {{$coach->place}}<br><a href="{{route('coach-detail', ['name'=>$coach->first_name.'-'.$coach->last_name,'id'=>$coach->id])}}">Zum Profil</a></td>
	</tr>
</table>
<br>
<br>
<table  width="750px" style="border-collapse: collapse;">
	<tr>
		<th style="text-align:left">Nr</th>
		<th style="text-align:left">Bezeichnung</th>
		<th style="text-align:left">Dauer</th>
		<th style="text-align:left">Preis/Stunde (ohne USt.)</th>
		<th style="text-align:left">Gesamtpreis</th>
	</tr>
	<tr>
		<td style="border-top: 1px solid #000">1</td>
		<td style="border-top: 1px solid #000">Lebensberatung</td>
		<td style="border-top: 1px solid #000">{{$cost_calculation['duration_min']}} Minuten</td>
		@if($coach->small_business)
			<td style="border-top: 1px solid #000">{{$cost_calculation['price_per_hour']}} €</td>
			<td style="border-top: 1px solid #000">{{$cost_calculation['gross_cost']}} €</td>
		@else
			<td style="border-top: 1px solid #000">{{$cost_calculation['net_per_hr']}} €</td>
			<td style="border-top: 1px solid #000">{{$cost_calculation['final_net_cost']}} €</td>
		@endif
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>USt.</td>
		@if($coach->small_business)
			<td>0%</td>
			<td>0,00 €</td>
		@else
			<td>{{$cost_calculation['vat_percent']}}%</td>
			<td>{{$cost_calculation['final_vat_cost']}} €</td>
		@endif
	</tr>
	<tr>
		<td style="border-top: 1px solid #000">&nbsp;</td>
		<td style="border-top: 1px solid #000">&nbsp;</td>
		<td style="border-top: 1px solid #000">&nbsp;</td>
		<td style="border-top: 1px solid #000"><b>Gesamtpreis inkl. USt.</b></td>
		<td style="border-top: 1px solid #000"><b>{{$cost_calculation['gross_cost']}} €</b></td>
	</tr>
</table>

@if($coach->small_business)
<br>
Gemäß §19 UStG enthält der Rechnungsbetrag keine Umsatzsteuer.
@endif

@if($user->roles->name=='guest')
Über folgenden Link kannst du deine Buchung bearbeiten.
@component('mail::button', ['url' => URL::signedRoute('guest.login', ['user' => $user->id])])
    Termin bearbeiten
@endcomponent
@endif

<p>Herzliche Grüße,<br>
Dein Team von himmlischberaten.de</p>
@endcomponent