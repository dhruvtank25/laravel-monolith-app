<html>
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type"/>
        <style>
            body { 
            	font-size:14px;
            }
			.footer {
				width: 100%;
				text-align: center;
				position: fixed;
			}
			.header { 
				position: fixed; left: 0px; top: -140px; right: 0px; height: 150px; text-align: center; 
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
			@page { margin-top: 160px; }
        </style>
    </head>
    <body style="width:1024px;position:relative;border-collapse:collapse;">
        <div class="header">
            <table border="0" style="width:730px;padding-bottom:20px;">
                <tr>
                    <td style="padding:10px 10px 10px 0px;">
                        <img alt="x" src="{{public_path().'/logo.png'}}" style="width:230px;"/>
                    </td>
                </tr>
            </table>
        </div>
        <table style="border-collapse:collapse;width:730px;padding-bottom:30px;">
            <tbody>
                <tr>
                    <td style="width:50%;">
                    </td>
                    <td style="width:20%;">
                    </td>
                </tr>
                <tr>
                    <td style="font-size:10px;padding-top:5px;">
                        <strong>lifepresso GmbH <p style="font-size:20px;display:inline-block;margin-top:3px;font-weight:bold;">.</p></strong>Hauptstraße 156 <p style="font-size:20px;display:inline-block;margin-top:3px;font-weight:bold;">.</p> 76351 Linkenheim-Hochstetten
                    </td>
                    <td style="font-size:20px;padding-bottom:20px;text-align:left;">
                        <b>Gutschrift</b>
                    </td>
                </tr>
                <tr>
                    <td style="font-size:14px;padding-top:5px;">
                        {{$coach->coach_company}}
                    </td>
                    <td style="font-size:14px;">
                        Rechnungsnummer:
                    </td>
                    <td style="font-size:14px;padding-left:5px;">
                        {{$appointment->id}}
                    </td>
                </tr>
                <tr>
                    <td style="font-size:14px;padding-top:5px;">
                        {{$coach->first_name.' '.$coach->last_name}}
                    </td>
                    <td style="font-size:14px;">
                        Rechnungsdatum:
                    </td>
                    <td style="font-size:14px;padding-left:5px;">
                        {{$appointment->start->format('d.m.Y')}}
                    </td>
                </tr>
                <tr>
                    <td style="font-size:14px;padding-top:5px;">
                        {{$coach->street}}
                    </td>
                    <td style="font-size:14px;">
                        Leistungszeitpunkt:
                    </td>
                    <td style="font-size:14px;padding-left:5px;">
                        {{$appointment->start->format('d.m.Y')}}
                    </td>
                </tr>
                <tr>
                    <td style="font-size:14px;padding-top:5px;">
                        {{$coach->post_code.' '.$coach->place}}
                    </td>
                    <td style="font-size:14px;">
                        Kundennummer:
                    </td>
                    <td style="font-size:14px;padding-left:5px;">
                        {{$coach->id}}
                    </td>
                </tr>
                @if($coach->tax_number)
	                <tr>
	                    <td style="font-size:14px;padding-top:5px;">
	                    </td>
	                    <td style="font-size:14px;">
	                        Ihre St.-Nr.:
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
	                        Ihre USt.-ID:
	                    </td>
	                    <td style="font-size:14px;padding-left:5px;">
	                        {{$coach->ust_id}}
	                    </td>
	                </tr>
                @endif
                <tr>
                    <td style="font-size:14px;padding-top:5px;">
                    </td>
                    <td style="font-size:14px;">
                        E-Mail:
                    </td>
                    <td style="font-size:14px;padding-left:5px;">
                        {{$coach->email}}
                    </td>
                </tr>
                <tr>
                    <td style="font-size:14px;padding-top:5px;">
                    </td>
                    <td style="font-size:14px;">
                        Telefon:
                    </td>
                    <td style="font-size:14px;padding-left:5px;">
                        {{$coach->phone_number}}
                    </td>
                </tr>
            </tbody>
        </table>
        <table style="width:730px;border-collapse: collapse;padding-bottom:30px;">
            <tr>
                <td style="padding:5px;">
                    <p style="font-weight:bold;">
                        Vielen Dank, dass du himmlischberaten.de genutzt hast. Hierfür wird folgende Gebühr in Rechnung gestellt:
                    </p>
                </td>
            </tr>
        </table>
        <table border="0" style="width:730px;border-collapse:collapse;padding-bottom:50px;">
            <tr>
                <th style="text-align:center;font-size:14px;padding:5px;border-bottom:1px solid #000000;">
                    Nr
                </th>
                <th style="text-align:center;font-size:14px;padding:5px;border-bottom:1px solid #000000;">
                    Buchungs-Nr.
                </th>
                <th style="text-align:center;font-size:14px;padding:5px;border-bottom:1px solid #000000;">
                    Bezeichnung
                </th>
                <th style="text-align:center;font-size:14px;padding:5px;border-bottom:1px solid #000000;">
                    Dauer
                </th>
                <th style="text-align:center;font-size:14px;padding:5px;border-bottom:1px solid #000000;">
                    Preis/Stunde (ohne USt)
                </th>
                <th style="text-align:center;font-size:14px;padding:5px;border-bottom:1px solid #000000;">
                    Gesamtpreis
                </th>
                <th style="text-align:center;font-size:14px;padding:5px;border-bottom:1px solid #000000;">
                    Gebühren-satz
                </th>
                <th style="text-align:center;font-size:14px;padding:5px;border-bottom:1px solid #000000;">
                    Gebühr
                </th>
            </tr>
            <tr>
                <td style="text-align:center;padding:5px;font-size:14px;border-bottom:1px solid #000000;">
                    1
                </td>
                <td style="text-align:center;padding:5px;font-size:14px;border-bottom:1px solid #000000;">
                    {{$appointment->id}}
                </td>
                <td style="text-align:center;padding:5px;font-size:14px;border-bottom:1px solid #000000;">
                    {{$category->title}}
                </td>
                <td style="text-align:center;padding:5px;font-size:14px;border-bottom:1px solid #000000;">
                    {{$cost_calculation['duration_min']}} Minuten
                </td>
                <td style="text-align:center;padding:5px;font-size:14px;border-bottom:1px solid #000000;">
                    {{$cost_calculation['net_per_hr']}} €
                </td>
                <td style="text-align:center;padding:5px;font-size:14px;border-bottom:1px solid #000000;">
                    {{$cost_calculation['final_net_cost']}} €
                </td>
                <td style="text-align:center;padding:5px;font-size:14px;border-bottom:1px solid #000000;">
                    {{$cost_calculation['commission_percent']}}%
                </td>
                <td style="text-align:center;padding:5px;font-size:14px;border-bottom:1px solid #000000;">
                    {{$cost_calculation['commission_amount']}}
                </td>
            </tr>
            <tr>
                <td style="text-align:center;padding:5px;font-size:14px;border-bottom:1px solid #000000;">
                </td>
                <td style="text-align:center;padding:5px;font-size:14px;border-bottom:1px solid #000000;">
                </td>
                <td style="text-align:left;padding:5px;font-size:14px;border-bottom:1px solid #000000;">
                </td>
                <td style="text-align:center;padding:5px;font-size:14px;border-bottom:1px solid #000000;">
                </td>
                <td style="text-align:center;padding:5px;font-size:14px;border-bottom:1px solid #000000;">
                </td>
                <td style="text-align:center;padding:5px;font-size:14px;border-bottom:1px solid #000000;">
                    Zzgl. USt
                </td>
                <td style="text-align:center;padding:5px;font-size:14px;border-bottom:1px solid #000000;">
                    {{$cost_calculation['vat_percent']}}%
                </td>
                <td style="text-align:center;padding:5px;font-size:14px;border-bottom:1px solid #000000;">
                    {{$cost_calculation['commission_vat']}} €
                </td>
            </tr>
            <tr>
                <td colspan="7" style="font-size:14px;padding:5px;text-align:right;border-top:1px solid #000000;">
                    <b>
                        Rechnungsbetrag:
                    </b>
                </td>
                <td colspan="1" style="font-size:14px;padding:5px;text-align:center;border-top:1px solid #000000;">
                    <b>
                        {{$cost_calculation['gross_commission']}} €
                    </b>
                </td>
            </tr>
        </table>
        <table style="width:730px;border-collapse: collapse;">
            <tr>
                <td style="padding:5px;font-size:16px;">
                    Der Rechnungsbetrag wird vom Beratungsentgelt einbehalten.
                </td>
            </tr>
        </table>
        <div class="footer">
            <table style="border-collapse:collapse;width:730px;">
                <tbody>
                    <tr>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                        <th>&nbsp;</th>
                    </tr>
                    <tr>
                        <td style="font-size:12px;padding-left:5px;color:#000000">Rechnung ausgestellt von:</td>
                        <td style="font-size:12px;padding-left:5px;"><b>lifepresso GmbH</b></td>
                        <td style="font-size:12px;padding-left:5px;"><b>Geschäftsführung</b></td>
                        <td style="font-size:12px;padding-left:5px;"><b>Bankkonto</b></td>
                    </tr>
                    <tr>
                        <td style="font-size:12px;padding-left:5px;"></td>
                        <td style="font-size:12px;padding-left:5px;">Hauptstraße 156</td>
                        <td style="font-size:12px;padding-left:5px;">Christian List</td>
                        <td style="font-size:12px;padding-left:5px;">Bankname: solaris Bank AG</td>
                    </tr>
                    <tr>
                        <td style="font-size:12px;padding-left:5px;">&nbsp;</td>
                        <td style="font-size:12px;padding-left:5px;">76351</td>
                        <td style="font-size:12px;padding-left:5px;">Amtsgericht Mannheim</td>
                        <td style="font-size:12px;padding-left:5px;">IBAN: DE30 1101 0100 2163 1695 51</td>
                    </tr>
                    <tr>
                        <td style="font-size:12px;padding-left:5px;">&nbsp;</td>
                        <td style="font-size:12px;padding-left:5px;">Linkenheim-Hochstetten</td>
                        <td style="font-size:12px;padding-left:5px;">HRB 733658</td>
                        <td style="font-size:12px;padding-left:5px;">BIC: SOBKDEBBXXX</td>
                    </tr>
                    <tr>
                        <td style="font-size:12px;padding-left:5px;">&nbsp;</td>
                        <td style="font-size:12px;padding-left:5px;">St.-Nr.: 34415/06666</td>
                        <td style="font-size:12px;padding-left:5px;">Firmensitz:</td>
                        <td style="font-size:12px;padding-left:5px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="font-size:12px;padding-left:5px;">&nbsp;</td>
                        <td style="font-size:12px;padding-left:5px;">USt-IdNr.: DE325035583</td>
                        <td style="font-size:12px;padding-left:5px;">Linkenheim-Hochstetten</td>
                        <td style="font-size:12px;padding-left:5px;">&nbsp;</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>