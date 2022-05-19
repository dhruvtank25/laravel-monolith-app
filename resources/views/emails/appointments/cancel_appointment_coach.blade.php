@component('mail::message')
<p>Hallo {{$coach->first_name.' '.$coach->last_name}},</p>

@if($appointment->cancel_fee_percent>0)
<p>die Buchung wurde durch den Kunden kostenpflichtig storniert. Das Stornoentgelt haben wir vom Rechnungsbetrag des Kunden einbehalten und für die Differenz eine Gutschrift erstellt. Das Gutschriftsdokument haben wir für deine Unterlagen dieser E-Mail beigefügt. Anbei erhältst du auch die Rechnung zur Plattformgebühr von himmlischberaten.de. Die Gebühr wurde von dem Beratungsentgelt einbehalten.</p>
@else
@if($appointment->cancelled_by=='coach')
<p>Du hast die Buchung für den Kunden kostenfrei storniert.</p>
@else
<p>die Buchung wurde durch den Kunden kostenfrei storniert.</p>
@endif
<table border="1" width="750px" style="border-collapse: collapse;">
    <tr>
        <td>Buchungsnummer: {{$appointment->id}}</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td><b>Dein Beratungstermin:</b> <br>Datum: {{$appointment->start->format('d.m.Y')}}<br>Startzeit: {{$appointment->start->format('H:i')}} Uhr<br>Dauer: {{$appointment->end->diffInMinutes($appointment->start)}} Minuten<br>
        @if($appointment->mode=='offline')
            Ort: Offline:<br>{{$coach->street}}<br>{{$coach->post_code.' '.$coach->place}}</td>
        @else
            Ort: Online<br></td>
        @endif
        <td style="vertical-align: top">
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
@if($appointment->cancelled_by=='coach')
<p>Anbei erhältst du die Gutschrift zu deiner Buchung.</p>
@endif
@endif

<br>
<p>Herzliche Grüße,<br>
Dein Team von himmlischberaten.de</p>
@endcomponent