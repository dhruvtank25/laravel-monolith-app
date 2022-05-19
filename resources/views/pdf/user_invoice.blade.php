<html>
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
        <style>
            body {  font-size:14px;}
			.footer {
				width: 100%;
				text-align: center;
				position: fixed;
			}
			.header { 
				position: fixed;
				left: 0px;
				top: -140px;
				right: 0px;
				height: 150px;
				text-align: center;
			}
			.footer {
				bottom: 100px;
			}
			.pagenum:before {
				content: counter(page);
			}
			td span{
				padding-left:25px;
			}
			@page { 
				margin-top: 160px; 
			}
        </style>
    </head>
    <body style="width:1024px;position:relative;border-collapse:collapse;">
        <div class="header">
            <table border="0" style="width:730px;padding-bottom:20px;">
                <tr>
                    <td style="padding:10px 10px 10px 0px;">
                     <img alt="x" src="{{public_path().'/logo.png'}}" style="width:230px;"/>
                    </td>
            </table>
        </div>
        <table style="border-collapse:collapse;width:730px;padding-bottom:20px;">
            <tbody>
                <tr>
                    <th style="width:50%;">
                    </th>
                    <th style="width:20%;"></th>
                </tr>
                <tr>
                    <td style="font-size:10px;padding-top:5px;">
                        <strong>{{$coach->coach_company}} </strong><p style="font-size:20px;display:inline-block;margin-top:3px;font-weight:bold;">.</p> {{$coach->street}} <p style="font-size:20px;font-weight:bold;display:inline-block;margin-top:3px;">.</p> {{$coach->post_code.' '.$coach->place}}
                    </td>
                    <td colspan="2" style="font-size:20px;padding-bottom:20px;text-align:left;">
                        <b>
                            Rechnung
                        </b>
                    </td>
                </tr>
                <tr>
                    <td style="font-size:14px;padding-top:5px;">
                    	@if(!$user->is_anonymous)
                    		Herr
                        @else
                        	Kundennummer: {{$user->id}}
                        @endif
                    </td>
                    <td style="font-size:14px;">
                        Firma:
                    </td>
                    <td style="font-size:14px;padding-left:5px;">
                        {{$coach->coach_company}}
                    </td>
                </tr>
                <tr>
                    <td style="font-size:14px;padding-top:5px;">
                    	{{!$user->is_anonymous?$user->first_name.' '.$user->last_name:''}}
                    </td>
                    <td style="font-size:14px;">
                        Name:
                    </td>
                    <td style="font-size:14px;padding-left:5px;">
                        {{$coach->first_name.' '.$coach->last_name}}
                    </td>
                </tr>
                <tr>
                    <td style="font-size:14px;padding-top:5px;">
                    	{{!$user->is_anonymous?$user->street:''}}
                    </td>
                    <td style="font-size:14px;">
                        Straße:
                    </td>
                    <td style="font-size:14px;padding-left:5px;">
                    	{{$coach->street}}
                    </td>
                </tr>
                <tr>
                    <td style="font-size:14px;padding-top:5px;">
                    	{{!$user->is_anonymous?$user->post_code.' '.$user->place:''}}
                    </td>
                    <td style="font-size:14px;">
                        Ort:
                    </td>
                    <td style="font-size:14px;padding-left:5px;">
                    	{{$coach->post_code.' '.$coach->place}}
                    </td>
                </tr>
                <tr>
                    <td style="font-size:14px;padding-top:5px;">
                    </td>
                    <td style="font-size:14px;">
                        Rechnungsnummer
                    </td>
                    <td style="font-size:14px;padding-left:5px;">
                        {{$appointment->id}}
                    </td>
                </tr>
                <tr>
                    <td style="font-size:14px;padding-top:5px;">
                    </td>
                    <td style="font-size:14px;">
                        Rechnungsdatum:
                    </td>
                    <td style="font-size:14px;padding-left:5px;">
                        {{$appointment->created_at->format('d.m.Y')}}
                    </td>
                </tr>
                <tr>
                    <td style="font-size:14px;padding-top:5px;">
                    </td>
                    <td style="font-size:14px;">
                        Leistungszeitpunkt:
                    </td>
                    <td style="font-size:14px;padding-left:5px;">
                        {{$appointment->start->format('d.m.Y')}}
                    </td>
                </tr>
                @if(!$user->is_anonymous)
	                <tr>
	                    <td style="font-size:14px;padding-top:5px;">
	                    </td>
	                    <td style="font-size:14px;">
	                        Kundennummer:
	                    </td>
	                    <td style="font-size:14px;padding-left:5px;">
	                        {{$user->id}}
	                    </td>
	                </tr>
                @endif
                <tr>
                    <td style="font-size:14px;padding-top:5px;">
                    </td>
                    <td style="font-size:14px;">
                        Buchungsnummer:
                    </td>
                    <td style="font-size:14px;padding-left:5px;">
                        {{$appointment->id}}
                    </td>
                </tr>
                @if($coach->tax_number)
	                <tr>
	                    <td style="font-size:14px;padding-top:5px;">
	                    </td>
	                    <td style="font-size:14px;">
	                        St.-Nr.(Berater):
	                    </td>
	                    <td style="font-size:14px;padding-left:5px;">
	                        {{$coach->tax_number}}
	                    </td>
	                </tr>
                @else
	                <tr>
	                    <td style="font-size:14px;padding-top:5px;">
	                    </td>
	                    <td style="font-size:14px;">
	                        USt.-ID (Berater):
	                    </td>
	                    <td style="font-size:14px;padding-left:5px;">
	                        {{$coach->ust_id}}
	                    </td>
	                </tr>
                @endif
                <tr>
                    <td colspan="3" style="padding:5px;padding-top:30px">
                        <p>Vielen Dank für deine Buchung bei himmlischberaten.de.</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <table border="0" style="width:730px;border-collapse:collapse;padding-bottom:30px;">
            <tr>
                <th style="font-weight:bold;text-align:left;font-size:14px;padding:5px;border-bottom:1px solid #000000;">
                    Nr
                </th>
                <th style="font-weight:bold;text-align:left;font-size:14px;padding:5px;border-bottom:1px solid #000000;">
                    Bezeichnung
                </th>
                <th style="font-weight:bold;text-align:left;font-size:14px;padding:5px;border-bottom:1px solid #000000;">
                    Dauer
                </th>
                <th style="font-weight:bold;text-align:left;font-size:14px;padding:5px;border-bottom:1px solid #000000;">
                    Preis/Stunde <br> (ohne USt)
                </th>
                <th style="font-weight:bold;text-align:left;font-size:14px;padding:5px;border-bottom:1px solid #000000;">
                    Gesamtbetrag
                </th>
            </tr>
            <tr>
                <td style="padding:5px;font-size:14px;border-bottom:1px solid #000000;">1</td>
                <td style="padding:5px;font-size:14px;border-bottom:1px solid #000000;">{{$category->title}}</td>
                <td style="padding:5px;font-size:14px;border-bottom:1px solid #000000;">{{$cost_calculation['duration_min']}} Minuten</td>
                @if($coach->small_business)
	                <td style="padding:5px;font-size:14px;border-bottom:1px solid #000000;">{{$cost_calculation['price_per_hour']}} €</td>
                    <td style="padding:5px;font-size:14px;border-bottom:1px solid #000000;">{{$cost_calculation['gross_cost']}} €</td>
                @else
                	<td style="padding:5px;font-size:14px;border-bottom:1px solid #000000;">{{$cost_calculation['net_per_hr']}} €</td>
                    <td style="padding:5px;font-size:14px;border-bottom:1px solid #000000;">{{$cost_calculation['final_net_cost']}} €</td>
                @endif
            </tr>
            <tr>
                <td style="padding:5px;font-size:14px;border-bottom:1px solid #000000;"></td>
                <td style="padding:5px;font-size:14px;border-bottom:1px solid #000000;"></td>
                <td style="padding:5px;font-size:14px;border-bottom:1px solid #000000;">Zzgl. USt</td>
                @if($coach->small_business)
                	<td style="padding:5px;font-size:14px;border-bottom:1px solid #000000;">0%</td>
                	<td style="padding:5px;font-size:14px;border-bottom:1px solid #000000;">0,00 €</td>
                @else
                	<td style="padding:5px;font-size:14px;border-bottom:1px solid #000000;">{{$cost_calculation['vat_percent']}}%</td>
					<td style="padding:5px;font-size:14px;border-bottom:1px solid #000000;">{{$cost_calculation['final_vat_cost']}} €</td>
                @endif
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td style="font-size:14px;padding:5px;border-top:1px solid #000000;">
                    <b>Rechnungsbetrag:</b>
                </td>
                <td colspan="1" style="font-size:14px;padding:5px;border-top:1px solid #000000;">
                    <b>{{$cost_calculation['gross_cost']}} €</b>
                </td>
            </tr>
        </table>
        <table style="width:730px;border-collapse: collapse;">
        	@if($coach->small_business)
	            <tr>
	                <td style="padding:5px;font-size:14px;">
	                    Gemäß §19 UStG enthält der Rechnungsbetrag keine Umsatzsteuer.
	                </td>
	            </tr>
            @endif
            <tr>
                <td style="padding:5px;font-size:14px;">
                    Der Rechnungsbetrag wurde mit VISA bezahlt.
                </td>
            </tr>
        </table>
    </body>
</html>