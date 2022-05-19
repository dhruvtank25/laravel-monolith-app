@extends('layouts.app')

@section('style_link')
<style type="text/css">
    body {margin-top: 0px;margin-left: 0px;}
#page_1 {position:relative; overflow: hidden;margin: 104px 0px 5px 0px;padding: 0px;border: none;}
#page_2 {position:relative; overflow: hidden;margin: 0px 0px 40px 96px;padding: 0px;border: none;}
#page_3 {position:relative; overflow: hidden;margin: 66px 0px 20px 96px;padding: 0px;border: none;}
#page_4 {position:relative; overflow: hidden;margin: 40px 0px 30px 96px;padding: 0px;border: none;}

#page_5 {position:relative; overflow: hidden;margin: 66px 0px 50px 96px;padding: 0px;border: none;}

#page_6 {position:relative; overflow: hidden;margin: 67px 0px 40px 96px;padding: 0px;border: none;}

#page_7 {position:relative; overflow: hidden;margin: 40px 0px 0px 96px;padding: 0px;border: none;}

#page_8 {position:relative; overflow: hidden;margin: 0px 0px 40px 96px;padding: 0px;border: none;}

#page_9 {position:relative; overflow: hidden;margin: 66px 0px 40px 96px;66px 0px 112px 96px;padding: 0px;border: none;}

#page_10 {position:relative; overflow: hidden;margin: 66px 0px 40px 96px;padding: 0px;border: none;}

#page_11 {position:relative; overflow: hidden;margin: 66px 0px 50px 96px;padding: 0px;border: none;}




.ft0{font: bold 21px 'Arial';line-height: 24px;}
.ft1{font: 15px 'Arial';line-height: 17px;}
.ft2{font: 19px 'Arial';line-height: 22px;}
.ft3{font: italic bold 19px 'Arial';margin-left: 30px;line-height: 23px;}
.ft4{font: italic bold 19px 'Arial';margin-left: 29px;line-height: 23px;}
.ft5{font: bold 19px 'Arial';line-height: 22px;}
.ft6{font: italic bold 19px 'Arial';text-decoration: underline;margin-left: 30px;line-height: 23px;}
.ft7{font: bold 15px 'Arial';line-height: 18px;}
.ft8{font: bold 15px 'Arial';margin-left: 35px;line-height: 18px;}
.ft9{font: bold 15px 'Arial';line-height: 17px;}
.ft10{font: italic 15px 'Arial';line-height: 17px;}
.ft11{font: 16px 'Times New Roman';line-height: 19px;}
.ft12{font: 15px 'Arial';margin-left: 20px;line-height: 17px;}
.ft13{font: 16px 'Times New Roman';line-height: 18px;}
.ft14{font: 15px 'Arial';margin-left: 16px;line-height: 17px;}
.ft15{font: 16px 'Times New Roman';line-height: 17px;}
.ft16{font: 15px 'Arial';margin-left: 22px;line-height: 17px;}
.ft17{font: 15px 'Arial';line-height: 16px;}
.ft18{font: 15px 'Arial';margin-left: 22px;line-height: 16px;}
.ft19{font: 15px 'Arial';text-decoration: underline;margin-left: 22px;line-height: 17px;}
.ft20{font: 15px 'Arial';text-decoration: underline;margin-left: 22px;line-height: 16px;}
.ft21{font: bold 15px 'Arial';margin-left: 27px;line-height: 18px;}
.ft22{font: 15px 'Arial';margin-left: 12px;line-height: 17px;}
.ft23{font: 15px 'Arial';margin-left: 12px;line-height: 16px;}
.ft24{font: 14px 'Arial';line-height: 16px;}
.ft25{font: 15px 'Arial';margin-left: 13px;line-height: 17px;}
.ft26{font: 15px 'Arial';margin-left: 14px;line-height: 17px;}
.ft27{font: 15px 'Times New Roman';line-height: 17px;}
.ft28{font: 15px 'Arial';margin-left: 17px;line-height: 17px;}
.ft29{font: 15px 'Arial';margin-left: 14px;line-height: 16px;}
.ft30{font: 15px 'Arial';text-decoration: underline;margin-left: 14px;line-height: 17px;}

.p0{text-align: center;padding-left: 189px;margin-top: 0px;margin-bottom: 0px;}
.p1{text-align: right;padding-right: 96px;margin-top: 21px;margin-bottom: 0px;}
.p2{text-align: left;padding-left: 96px;margin-top: 22px;margin-bottom: 0px;}
.p3{text-align: left;padding-left: 144px;padding-right: 281px;margin-top: 21px;margin-bottom: 0px;text-indent: -48px;}
.p4{text-align: left;padding-left: 143px;padding-right: 257px;margin-top: 19px;margin-bottom: 0px;text-indent: -47px;}
.p5{text-align: left;padding-left: 144px;padding-right: 279px;margin-top: 55px;margin-bottom: 0px;text-indent: -48px;}
.p6{text-align: left;padding-left: 96px;margin-top: 55px;margin-bottom: 0px;}
.p7{text-align: left;padding-left: 96px;margin-top: 15px;margin-bottom: 0px;}
.p8{text-align: left;padding-left: 96px;padding-right: 96px;margin-top: 1px;margin-bottom: 0px;}
.p9{text-align: left;padding-left: 96px;margin-top: 31px;margin-bottom: 0px;}
.p10{text-align: left;padding-left: 155px;padding-right: 96px;margin-top: 17px;margin-bottom: 0px;text-indent: -35px;}
.p11{text-align: left;padding-left: 120px;margin-top: 0px;margin-bottom: 0px;}
.p12{text-align: left;padding-left: 155px;padding-right: 96px;margin-top: 0px;margin-bottom: 0px;text-indent: -35px;}
.p13{text-align: left;padding-left: 155px;padding-right: 96px;margin-top: 0px;margin-bottom: 0px;text-indent: -35px;}
.p14{text-align: left;padding-left: 59px;padding-right: 96px;margin-top: 0px;margin-bottom: 0px;text-indent: -35px;}
.p15{text-align: left;padding-left: 59px;padding-right: 96px;margin-top: 0px;margin-bottom: 0px;text-indent: -35px;}
.p16{text-align: left;margin-top: 31px;margin-bottom: 0px;}
.p17{text-align: left;padding-right: 96px;margin-top: 15px;margin-bottom: 0px;}
.p18{text-align: left;margin-top: 33px;margin-bottom: 0px;}
.p19{text-align: left;padding-left: 47px;padding-right: 96px;margin-top: 16px;margin-bottom: 0px;text-indent: -47px;}
.p20{text-align: left;padding-left: 47px;padding-right: 96px;margin-top: 0px;margin-bottom: 0px;text-indent: -47px;}
.p21{text-align: left;padding-left: 47px;padding-right: 97px;margin-top: 4px;margin-bottom: 0px;text-indent: -47px;}
.p22{text-align: left;padding-left: 47px;padding-right: 96px;margin-top: 0px;margin-bottom: 0px;text-indent: -47px;}
.p23{text-align: left;padding-left: 47px;padding-right: 96px;margin-top: 1px;margin-bottom: 0px;text-indent: -47px;}
.p24{text-align: left;margin-top: 0px;margin-bottom: 0px;}
.p25{text-align: left;padding-right: 96px;margin-top: 16px;margin-bottom: 0px;}
.p26{text-align: left;margin-top: 40px;margin-bottom: 0px;}
.p27{text-align: left;margin-top: 16px;margin-bottom: 0px;}
.p28{text-align: left;padding-left: 47px;padding-right: 96px;margin-top: 8px;margin-bottom: 0px;text-indent: -47px;}
.p29{text-align: left;padding-left: 47px;padding-right: 96px;margin-top: 4px;margin-bottom: 0px;text-indent: -47px;}
.p30{text-align: left;padding-left: 47px;padding-right: 96px;margin-top: 3px;margin-bottom: 0px;text-indent: -47px;}
.p31{text-align: left;margin-top: 15px;margin-bottom: 0px;}
.p32{text-align: left;padding-left: 96px;padding-right: 96px;margin-top: 17px;margin-bottom: 0px;text-indent: -24px;}
.p33{text-align: left;padding-left: 96px;padding-right: 96px;margin-top: 0px;margin-bottom: 0px;text-indent: -24px;}
.p34{text-align: left;padding-left: 96px;padding-right: 96px;margin-top: 5px;margin-bottom: 0px;text-indent: -24px;}
.p35{text-align: left;padding-left: 96px;padding-right: 96px;margin-top: 0px;margin-bottom: 0px;text-indent: -24px;}
.p36{text-align: left;padding-right: 96px;margin-top: 16px;margin-bottom: 0px;}
.p37{text-align: left;padding-right: 256px;margin-top: 0px;margin-bottom: 0px;}
.p38{text-align: left;margin-top: 43px;margin-bottom: 0px;}
.p39{text-align: left;padding-right: 96px;margin-top: 1px;margin-bottom: 0px;}
.p40{text-align: left;padding-left: 47px;margin-top: 0px;margin-bottom: 0px;}
.p41{text-align: left;padding-left: 47px;padding-right: 96px;margin-top: 15px;margin-bottom: 0px;text-indent: -47px;}
.p42{text-align: left;padding-left: 47px;padding-right: 96px;margin-top: 9px;margin-bottom: 0px;text-indent: -47px;}
.p43{text-align: left;margin-top: 7px;margin-bottom: 0px;}
.p44{text-align: left;padding-left: 47px;padding-right: 96px;margin-top: 16px;margin-bottom: 0px;text-indent: -47px;}
.p45{text-align: left;padding-left: 47px;padding-right: 96px;margin-top: 2px;margin-bottom: 0px;text-indent: -47px;}
.p46{text-align: left;padding-left: 48px;padding-right: 96px;margin-top: 16px;margin-bottom: 0px;text-indent: -23px;}
.p47{text-align: left;padding-left: 48px;padding-right: 96px;margin-top: 1px;margin-bottom: 0px;text-indent: -24px;}
.p48{text-align: left;padding-left: 48px;padding-right: 96px;margin-top: 0px;margin-bottom: 0px;text-indent: -24px;}
.p49{text-align: left;padding-right: 96px;margin-top: 18px;margin-bottom: 0px;}
.p50{text-align: left;margin-top: 9px;margin-bottom: 0px;}
.p51{text-align: left;margin-top: 40px;margin-bottom: 0px;}
.p52{text-align: left;padding-left: 47px;padding-right: 96px;margin-top: 3px;margin-bottom: 0px;text-indent: -47px;}
.p53{text-align: left;padding-left: 96px;padding-right: 96px;margin-top: 16px;margin-bottom: 0px;text-indent: -24px;}
.p54{text-align: left;padding-left: 96px;padding-right: 96px;margin-top: 4px;margin-bottom: 0px;text-indent: -24px;}
.p55{text-align: left;padding-left: 96px;padding-right: 96px;margin-top: 1px;margin-bottom: 0px;text-indent: -24px;}
.p56{text-align: left;padding-right: 96px;margin-top: 15px;margin-bottom: 0px;}
.p57{text-align: left;margin-top: 34px;margin-bottom: 0px;}
</style>
@endsection

@section('content')
<div class="container">
    <div id="page_1">
        <p class="p0 ft0">
            ALLGEMEINE GESCHÄFTSBEDINGUNGEN
        </p>
        <p class="p1 ft1">
            Stand: 12/2019
        </p>
        <p class="p2 ft2">
            INHALT:
        </p>
        <p class="p3 ft2">
            <span class="ft2">
                A.
            </span>
            <span class="ft3">
                BEZUG
            </span>
            VON DIENSTLEISTUNGEN AUF DER
            <nobr>
                HIMMLISCHBERATEN-PLATTFORM
            </nobr>
        </p>
        <p class="p4 ft2">
            <span class="ft2">
                B.
            </span>
            <span class="ft4">
                VERKAUF
            </span>
            VON DIENSTLEISTUNGEN AUF DER
            <nobr>
                HIMMLISCHBERATEN-PLATTFORM
            </nobr>
        </p>
        <p class="p5 ft5">
            <span class="ft5">
                A.
            </span>
            <span class="ft6">
                BEZUG
            </span>
            VON DIENSTLEISTUNGEN AUF DER
            <nobr>
                HIMMLISCHBERATEN-PLATTFORM
            </nobr>
        </p>
        <p class="p6 ft7">
            <span class="ft7">
                1.
            </span>
            <span class="ft8">
                Betreiber und Gegenstand
            </span>
        </p>
        <p class="p7 ft1">
            Die
            <nobr>
                HIMMLISCHBERATEN-Website
            </nobr>
            (nachstehend „
            <span class="ft9">
                Plattform
            </span>
            “) wird von der lifepresso GmbH,
        </p>
        <p class="p8 ft1">
            Hauptstraße 156, 76351
            <nobr>
                Linkenheim-Hochstetten
            </nobr>
            betrieben (nachstehend als „
            <span class="ft7">
                wir
            </span>
            “ oder „
            <span class="ft7">
                uns
            </span>
            “ bezeichnet). Dieser Abschnitt A. der Allgemeinen Geschäftsbedingungen (nachstehend „
            <span class="ft7">
                AGB
            </span>
            “) gilt für den
            <span class="ft10">
                Bezug
            </span>
            von Dienstleistungen auf der Plattform.
        </p>
        <p class="p9 ft7">
            <span class="ft7">
                2.
            </span>
            <span class="ft8">
                Begriffsdefinitionen und Inhalt des Angebotes
            </span>
        </p>
        <p class="p10 ft1">
            <span class="ft11">
                (1)
            </span>
            <span class="ft12">
                „Nutzer“ im Sinne dieser AGB ist der Oberbegriff jeder Person, welche über die Plattform Leistungen in Anspruch nimmt bzw. über die Plattform seine Leistungen anbietet.
            </span>
        </p>
        <p class="p11 ft1">
            <span class="ft13">
                (2)
            </span>
            <span class="ft14">
                „Anbieter“ ist ein Nutzer, welcher seine Dienstleistung auf der Plattform anbietet.
            </span>
        </p>
        <p class="p12 ft1">
            <span class="ft15">
                (3)
            </span>
            <span class="ft14">
                „Kunde“ ist ein Nutzer, welcher die auf der Plattform angebotene Dienstleistung in Anspruch nimmt.
            </span>
        </p>
        <p class="p13 ft1">
            <span class="ft11">
                (4)
            </span>
            <span class="ft14">
                Die Plattform stellt eine technische Infrastruktur bereit, welche zur Zusammenführung von Kunden und Anbietern sowie die Durchführung von Beratungsgesprächen geeignet ist. Wir fungieren dadurch lediglich als Vermittler zwischen Kunden und Anbietern. Die Plattform übernimmt keine Verantwortung für die Qualität und Richtigkeit der Leistung eines Anbieters und überprüft auch nicht die Vollständigkeit und Richtigkeit seiner Angaben, da diese nicht durch die Plattform überprüft werden kann. Die Beratungsleistung wird ausschließlich durch unabhängige Anbieter erbracht, welche nicht bei uns oder einer verbundenen Gesellschaft angestellt sind. Den Vertrag über die jeweilige Beratungsleistung schließt der Kunde nicht mit uns, sondern mit dem betreffenden Anbieter ab.
            </span>
        </p>
    </div>
    <div id="page_2">
        <p class="p14 ft1">
            <span class="ft11">
                (5)
            </span>
            <span class="ft14">
                Kunden können gezielt nach Anbietern suchen und gemäß der im Profil enthaltenen Angaben selbstständig einen passenden Anbieter auswählen. Die Beurteilung der Kompetenz des Anbieters obliegt dem Kunden auf eigene Verantwortung.
            </span>
        </p>
        <p class="p15 ft1">
            <span class="ft15">
                (6)
            </span>
            <span class="ft14">
                Sofern ein jeweiliger Anbieter es anbietet, kann die Beratung über das auf der Plattform bereitgestellte Videokommunikationstool durchgeführt werden.
            </span>
        </p>
        <p class="p14 ft1">
            <span class="ft11">
                (7)
            </span>
            <span class="ft14">
                Der Kunde wird darauf hingewiesen, dass Auskünfte eines Anbieters über die Plattform nicht dazu geeignet und bestimmt sind, eine professionelle psychologische Betreuung bzw. Behandlung durch einen Experten zu ersetzen. Hingewiesen wird darauf, dass die Befolgung von Ratschlägen aus einer Auskunft in der Regel außerhalb der Verantwortung eines Anbieters liegt. Jeder Kunde handelt insofern auf eigene Verantwortung. Sämtliche über die Plattform bereitgestellten Services stellen keine ärztliche- oder psychologische Beratung und/oder Behandlung dar und können eine solche weder ganz noch teilweise ersetzen. Insbesondere ist die Registrierung eines Nutzers auf der Plattform nicht geeignet, den Besuch bei einem Arzt oder Psychologen zu ersetzen. Es wird daher jedem Nutzer dringend empfohlen, im Bedarfsfall einen Arzt oder fachlich geeigneten Berater vor Ort zu konsultieren.
            </span>
        </p>
        <p class="p16 ft7">
            <span class="ft7">
                3.
            </span>
            <span class="ft8">
                Geltungsbereich
            </span>
        </p>
        <p class="p17 ft1">
            Für die über die Plattform begründeten Rechtsbeziehungen zwischen uns und seinen Nutzern gelten ausschließlich die AGB in der jeweiligen Fassung zum Zeitpunkt des Rechtsgeschäftes oder der rechtsgeschäftsähnlichen Handlung. Abweichende und/oder über diese Allgemeinen Geschäftsbedingungen hinausgehende Geschäftsbedingungen des Nutzers werden nicht Vertragsinhalt.
        </p>
        <p class="p18 ft7">
            <span class="ft7">
                4.
            </span>
            <span class="ft8">
                Abschluss des Vertrages mit uns über die Nutzung der Plattform
            </span>
        </p>
        <p class="p19 ft1">
            <span class="ft1">
                4.1.
            </span>
            <span class="ft16">
                Die Bereitstellung der Website stellt noch kein verbindliches Angebot zum Abschluss eines entsprechenden Nutzungsvertrages zwischen dem Nutzer und uns dar. Ein verbindliches Angebot erfolgt vielmehr erst dadurch, dass der Nutzer sein Registrierungsgesuch über die Website an uns übermittelt. Dieses Angebot nehmen wir ggf. dadurch an, dass wir die Registrierung des Nutzers durch eine Registrierungsbestätigung per
            </span>
            <nobr>
                E-Mail
            </nobr>
            bestätigen.
        </p>
        <p class="p20 ft17">
            <span class="ft17">
                4.2.
            </span>
            <span class="ft18">
                Der Nutzer ist verpflichtet, bei einer Registrierung wahrheitsgemäße Angaben zu machen und seine Daten auch nach der Registrierung stets aktuell zu halten. Ein Nutzer darf sein Konto nicht an Dritte übertragen oder sonst zugänglich machen. Bei einem Verstoß behalten wir uns vor den Zugang zur Plattform zu verwehren oder den Vertrag mit dem Nutzer zu kündigen.
            </span>
        </p>
        <p class="p21 ft1">
            <span class="ft1">
                4.3.
            </span>
            <span class="ft16">
                Bei Verlust oder einer unbefugten Nutzung Ihrer Zugangsdaten sind wir unverzüglich zu unterrichten.
            </span>
        </p>
        <p class="p22 ft1">
            <span class="ft1">
                4.4.
            </span>
            <span class="ft16">
                Kunden steht darüber hinaus die Möglichkeit zur Nutzung der Plattform im Rahmen einer Gastbuchung zu Verfügung.
            </span>
        </p>
        <p class="p22 ft17">
            <span class="ft17">
                4.5.
            </span>
            <span class="ft18">
                Anonyme Beratung ist im Geltungsbereich der Kleinbetragsregelung gemäß 33 UStDV möglich.
            </span>
        </p>
        <p class="p23 ft1">
            <span class="ft1">
                4.6.
            </span>
            <span class="ft16">
                Leistungen, die auf der Plattform angeboten oder gesucht und sodann in Anspruch genommen werden, sind kostenpflichtig.
            </span>
        </p>
        <p class="p24 ft1">
            <span class="ft1">
                4.7.
            </span>
            <span class="ft16">
                Natürlichen Personen ist die Anmeldung nur gestattet, wenn sie volljährig sind.
            </span>
        </p>
        <p class="p24 ft1">
            <span class="ft1">
                4.8.
            </span>
            <span class="ft16">
                Die Vertragssprache ist deutsch.
            </span>
        </p>
    </div>
    <div id="page_3">
        <p class="p24 ft7">
            <span class="ft7">
                5.
            </span>
            <span class="ft8">
                Abschluss von Verträgen mit Anbietern
            </span>
        </p>
        <p class="p19 ft1">
            <span class="ft1">
                5.1.
            </span>
            <span class="ft16">
                Die Präsentation der Produkte auf der Plattform beinhaltet noch kein Angebot des jeweiligen Anbieters auf Abschluss eines entsprechenden Vertrages. Erst indem der Kunde eine Bestellung über die jeweilige Leistung absendet, unterbreitet er damit dem jeweiligen Anbieter ein Angebot zum Abschluss eines entsprechenden Vertrages. Der Vertrag kommt zustande, wenn und sobald der Anbieter das Angebot innerhalb einer angemessenen Frist im Wege einer Mitteilung über ein plattformeigenes Nachrichtensystem oder auch durch Inanspruchnahme der Dienstleistung annimmt.
            </span>
        </p>
        <p class="p24 ft1">
            <span class="ft1">
                5.2.
            </span>
            <span class="ft16">
                Die Vertragssprache ist deutsch
            </span>
        </p>
        <p class="p18 ft7">
            <span class="ft7">
                6.
            </span>
            <span class="ft8">
                Verpflichtung zur Verschwiegenheit
            </span>
        </p>
        <p class="p25 ft1">
            Nutzer sind zur Diskretion, Verschwiegenheit und Geheimhaltung aller über die Plattform ausgetauschten Informationen verpflichtet. Jede Form der Weitergabe von personenbezogenen Informationen und Daten an Dritte sowie deren Nutzung außerhalb des bestimmungsgemäßen Gebrauchs der Plattform ist ausdrücklich verboten.
        </p>
        <p class="p26 ft7">
            <span class="ft7">
                7.
            </span>
            <span class="ft8">
                Zahlungsabwicklung
            </span>
        </p>
        <p class="p27 ft1">
            <span class="ft1">
                7.1.
            </span>
            <span class="ft16">
                Die Zahlung leistet der Kunde direkt an den Anbieter.
            </span>
        </p>
        <p class="p22 ft17">
            <span class="ft17">
                7.2.
            </span>
            <span class="ft18">
                Die Abwicklung der Zahlungen von über die Plattform gebuchten Dienstleistungen ist ausschließlich durch Mangopay möglich. Mangopay ist ein elektronisches Zahlungssystem des Finanzdienstleisters Mangopay (59 Boulevard Royal,
            </span>
            <nobr>
                L-2449
            </nobr>
            Luxembourg), betrieben durch Leetchi Corp. S.A., 14 Rue Aldringen,
            <nobr>
                L-1118
            </nobr>
            Luxemburg. Im Falle der Nutzung des elektronischen Zahlungssystems Mangopay kommt zwischen dem Nutzer und Mangopay ein eigenständiger Vertrag nach Maßgabe der
            <a href="https://www.mangopay.com/terms/end-user-terms-and-conditions/Mangopay_Terms-DE.pdf">
                Allgemeinen Geschäftsbedingungen von Mangopay.
            </a>
            Mit der Zustimmung unserer AGB stimmt der Nutzer gleichzeitig den Allgemeinen Geschäftsbedingungen von Mangopay zu:
            <nobr>
                https://www.mangopay.com/terms/end-user-terms-and-conditions/Mangopay_Terms-
            </nobr>
            DE.pdf. Für den Zahlvorgang mit Hilfe von Mangopay übernehmen wir keine Haftung.
        </p>
        <p class="p28 ft1">
            <span class="ft1">
                7.3.
            </span>
            <span class="ft16">
                Die Leetchi Corp. S.A., 14 Rue Aldringen,
            </span>
            <nobr>
                L-1118
            </nobr>
            Luxemburg ist ein nach den Bestimmungen der CSSF in Luxemburg zugelassenes und überwachtes
            <nobr>
                E-Geld-Institut
            </nobr>
            (No. W00000005).
        </p>
        <p class="p20 ft17">
            <span class="ft17">
                7.4.
            </span>
            <span class="ft18">
                Bei Fragen oder Reklamationen rund um Buchungen und Abrechnungen sind wir über das
            </span>
            <a href="{{route('contact-us')}}">
                Kontaktformular
            </a>
            oder über
            <nobr>
                e-mail
            </nobr>
            an
            <a href="mailto:kontakt@himmlischberaten.de">
                kontakt@himmlischberaten.de
            </a>
            erreichbar. Reklamationen sind innerhalb von 24 Stunden nach Erhalt der Abrechnung bzw. Tätigung der Buchung anzumelden. Bei verspäteter Anzeige können wir die Reklamation zurückweisen.
        </p>
        <p class="p29 ft1">
            <span class="ft1">
                7.5.
            </span>
            <span class="ft16">
                Das Beratungsentgelt ist mit erfolgreicher Buchung fällig und wird dem verwendeten Zahlungsmittel belastet.
            </span>
        </p>
        <p class="p20 ft1">
            <span class="ft1">
                7.6.
            </span>
            <span class="ft16">
                Der Anbieter trägt das alleinige Risiko für Zahlungsausfälle von Kunden. Bei endgültigem Zahlungsausfall wird die Servicegebühr zur Nutzung der Plattform dem Anbieter erstattet.
            </span>
        </p>
    </div>
    <div id="page_4">
        <p class="p24 ft7">
            <span class="ft7">
                8.
            </span>
            <span class="ft8">
                Rechnungsstellung
            </span>
        </p>
        <p class="p19 ft1">
            <span class="ft1">
                8.1.
            </span>
            <span class="ft16">
                Anbieter werden im Rahmen ihrer selbstständigen Tätigkeit im eigenen Namen und auf eigene Verantwortung tätig. Der Anbieter erbringt eine entgeltliche Dienstleistung, für die der Kunde nach Abschluss der vereinbarten Dienstleistung ein Beratungsentgelt bezahlt.
            </span>
        </p>
        <p class="p20 ft1">
            <span class="ft1">
                8.2.
            </span>
            <span class="ft16">
                Der Anbieter beauftragt uns, eine Rechnung über die jeweils vereinbarte Dienstleistung an den Kunden zu erstellen. Die Rechnungsdokumente werden in elektronischer Form erstellt und bereitgestellt. Für die ordnungsgemäße Archivierung der Dokumente ist der Nutzer selbst verantwortlich.
            </span>
        </p>
        <p class="p18 ft7">
            <span class="ft7">
                9.
            </span>
            <span class="ft8">
                Stornierung und Terminverschiebung
            </span>
        </p>
        <p class="p19 ft1">
            <span class="ft1">
                9.1.
            </span>
            <span class="ft19">
                Stornierung durch den Kunden:
            </span>
            Storniert ein Kunde eine bestätigte Buchung bis zu 48 Stunden vor Beginn der Beratung, wird das gesamte Beratungsentgelt erstattet. Storniert er innerhalb von 48 Stunden vor Beginn der Beratung, werden 60% des Beratungsentgeltes einbehalten.
        </p>
        <p class="p20 ft17">
            <span class="ft17">
                9.2.
            </span>
            <span class="ft20">
                Stornierung durch den Anbieter:
            </span>
            Sofern ein Anbieter eine bestätigte Buchung storniert, wird dies für alle Nutzer auf dem Profil des jeweiligen Anbieters sichtbar gemacht und der Kunde erhält das gesamte Beratungsentgelt erstattet. Für den Anbieter entstehen keine Kosten.
        </p>
        <p class="p30 ft1">
            <span class="ft1">
                9.3.
            </span>
            <span class="ft16">
                Der registrierte Kunde hat die Möglichkeit bis zu 48 Stunden vor der Beratung den Termin einmalig zu verschieben. Hierzu kann er aus den verfügbaren Terminen seines gebuchten Anbieters einen Alternativtermin auswählen und verbindlich buchen. Eine Terminanfrage ist im Terminverschiebungsprozess nicht vorgesehen.
            </span>
        </p>
        <p class="p22 ft1">
            <span class="ft1">
                9.4.
            </span>
            <span class="ft16">
                Erscheint der Kunde nicht zur vereinbarten Beratung am gewählten Beratungsort, online oder offline, ist die volle Höhe des Beratungsentgeltes zu entrichten.
            </span>
        </p>
        <p class="p18 ft7">
            <span class="ft7">
                10.
            </span>
            <span class="ft21">
                Haftung
            </span>
        </p>
        <p class="p31 ft1">
            Für eine Haftung von uns auf Schadensersatz gilt:
        </p>
        <p class="p32 ft1">
            <span class="ft1">
                a.
            </span>
            <span class="ft22">
                Bei Vorsatz und grober Fahrlässigkeit, auch unserer Erfüllungsgehilfen, haften wir nach den gesetzlichen Bestimmungen. Das gleiche gilt bei fahrlässig verursachten Schäden aus der Verletzung des Lebens, des Körpers oder der Gesundheit.
            </span>
        </p>
        <p class="p33 ft17">
            <span class="ft17">
                b.
            </span>
            <span class="ft23">
                Bei fahrlässig verursachten Sach- und Vermögensschäden haften wir nur bei der Verletzung einer wesentlichen Vertragspflicht, jedoch der Höhe nach beschränkt auf die bei Vertragsschluss vorhersehbaren und vertragstypischen Schäden; wesentliche Vertragspflichten sind solche, deren Erfüllung die ordnungsgemäße Durchführung des Vertrags überhaupt erst ermöglicht und auf deren Einhaltung der Vertragspartner regelmäßig vertrauen darf.
            </span>
        </p>
        <p class="p34 ft1">
            <span class="ft24">
                c.
            </span>
            <span class="ft25">
                Im Übrigen ist eine Haftung von uns, unabhängig von deren Rechtsgrund, ausgeschlossen.
            </span>
        </p>
        <p class="p35 ft1">
            <span class="ft1">
                d.
            </span>
            <span class="ft22">
                Die Haftungsausschlüsse und
            </span>
            <nobr>
                -beschränkungen
            </nobr>
            der vorstehenden Absätze (a) bis (c) gelten sinngemäß auch zugunsten unserer Erfüllungsgehilfen.
        </p>
        <p class="p33 ft1">
            <span class="ft1">
                e.
            </span>
            <span class="ft22">
                Eine Haftung wegen Übernahme einer Garantie oder nach dem Produkthaftungsgesetz bleibt von den Haftungsausschlüssen und - beschränkungen der vorstehenden Absätze (a) bis (d) unberührt.
            </span>
        </p>
    </div>
    <div id="page_5">
        <p class="p24 ft7">
            <span class="ft7">
                11.
            </span>
            <span class="ft21">
                Nutzungsrechte
            </span>
        </p>
        <p class="p25 ft1">
            Wir behalten uns vor, von Nutzern eingestellten Content, sowohl Texte als auch Bild- Video und Tonmaterial auf die Einhaltung der gesetzlichen Vorgaben und der Zweckdienlichkeit im Sinne dieser AGB zu überprüfen und, wenn nötig, ganz oder teilweise zu löschen. Eine Verpflichtung zu einer solchen Überprüfung besteht für uns ausdrücklich nicht.
        </p>
        <p class="p18 ft7">
            <span class="ft7">
                12.
            </span>
            <span class="ft21">
                Kündigung
            </span>
        </p>
        <p class="p27 ft1">
            <span class="ft1">
                12.1.
            </span>
            <span class="ft26">
                Das Vertragsverhältnis wird auf unbestimmte Zeit geschlossen.
            </span>
        </p>
        <p class="p22 ft1">
            <span class="ft1">
                12.2.
            </span>
            <span class="ft26">
                Jede Partei ist berechtigt, den Vertrag zur Nutzung der Plattform jederzeit ohne Kündigungsfrist und ohne Angaben von Gründen schriftlich zu kündigen.
            </span>
        </p>
        <p class="p24 ft1">
            <span class="ft1">
                12.3.
            </span>
            <span class="ft26">
                Etwaige Zahlungspflichten bleiben hiervon unberührt.
            </span>
        </p>
        <p class="p22 ft1">
            <span class="ft1">
                12.4.
            </span>
            <span class="ft26">
                Wir behalten uns vor, nach einer Kündigung andere Nutzer, über diese Kündigung zu informieren, sofern diese mit der gekündigten Person in Kontakt standen.
            </span>
        </p>
        <p class="p20 ft1">
            <span class="ft1">
                12.5.
            </span>
            <span class="ft26">
                Nach Beendigung des Vertragsverhältnisses zwischen uns und dem Nutzer werden sämtliche Daten des Nutzers gelöscht, es sei denn, gesetzliche oder vertragliche Aufbewahrungsverpflichtungen stehen dagegen.
            </span>
        </p>
        <p class="p18 ft7">
            <span class="ft7">
                13.
            </span>
            <span class="ft21">
                Datenschutz
            </span>
        </p>
        <p class="p36 ft1">
            Für die Nutzung der Website gilt die Datenschutzerklärung, die unter Datenschutz abgerufen werden kann.
        </p>
        <p class="p18 ft7">
            <span class="ft7">
                14.
            </span>
            <span class="ft21">
                Gerichtsstand, anwendbares Recht
            </span>
        </p>
        <p class="p19 ft1">
            <span class="ft1">
                14.1.
            </span>
            <span class="ft26">
                Es gilt deutsches Recht. Gegenüber einem Verbraucher gilt diese Rechtswahl nur insoweit, als dadurch keine zwingend anwendbaren gesetzlichen Bestimmungen des Staates, in dem er seinen Wohnsitz oder gewöhnlichen Aufenthalt hat, eingeschränkt werden.
            </span>
        </p>
        <p class="p20 ft1">
            <span class="ft1">
                14.2.
            </span>
            <span class="ft26">
                Gerichtsstand im Verkehr mit Kaufleuten, juristischen Personen des öffentlichen Rechts oder
            </span>
            <nobr>
                öffentlich-rechtlichen
            </nobr>
            Sondervermögen ist der Sitz unseres Unternehmens. Wir sind jedoch nach unserer Wahl berechtigt, am Sitz des Kunden zu klagen.
        </p>
    </div>
    <div id="page_6">
        <p class="p37 ft2">
            <span class="ft2">
                B.
            </span>
            <span class="ft3">
                VERKAUF
            </span>
            VON DIENSTLEISTUNGEN AUF DER
            <nobr>
                HIMMLISCHBERATEN-PLATTFORM
            </nobr>
        </p>
        <p class="p38 ft7">
            <span class="ft7">
                1.
            </span>
            <span class="ft8">
                Betreiber und Gegenstand
            </span>
        </p>
        <p class="p31 ft1">
            Die
            <nobr>
                HIMMLISCHBERATEN-Website
            </nobr>
            (nachstehend „
            <span class="ft9">
                Plattform
            </span>
            “) wird von der lifepresso GmbH,
        </p>
        <p class="p39 ft1">
            Hauptstraße 156, 76351
            <nobr>
                Linkenheim-Hochstetten
            </nobr>
            betrieben (nachstehend als „
            <span class="ft7">
                wir
            </span>
            “ oder „
            <span class="ft7">
                uns
            </span>
            “ bezeichnet). Dieser Abschnitt B. der Allgemeinen Geschäftsbedingungen (nachstehend „
            <span class="ft7">
                AGB
            </span>
            “) gilt für den
            <span class="ft10">
                Verkauf
            </span>
            von Dienstleistungen auf der Plattform.
        </p>
        <p class="p16 ft7">
            <span class="ft7">
                2.
            </span>
            <span class="ft8">
                Begriffsdefinitionen und Inhalt des Angebotes
            </span>
        </p>
        <p class="p27 ft1">
            <span class="ft1">
                2.1.
            </span>
            <span class="ft16">
                „Nutzer“ im Sinne dieser AGB ist der Oberbegriff jeder Person, welche über die Plattform
            </span>
        </p>
        <p class="p40 ft1">
            Leistungen in Anspruch nimmt bzw. über die Plattform seine Leistungen anbietet.
        </p>
        <p class="p24 ft1">
            <span class="ft1">
                2.2.
            </span>
            <span class="ft16">
                „Anbieter“ ist ein Nutzer, welcher seine Dienstleistung auf der Plattform anbietet.
            </span>
        </p>
        <p class="p24 ft1">
            <span class="ft1">
                2.3.
            </span>
            <span class="ft16">
                „Kunde“ ist ein Nutzer, welcher die auf der Plattform angebotene Dienstleistung in
            </span>
        </p>
        <p class="p40 ft1">
            Anspruch nimmt.
        </p>
        <p class="p20 ft17">
            <span class="ft17">
                2.4.
            </span>
            <span class="ft18">
                Die Plattform stellt eine technische Infrastruktur bereit, welche zur Zusammenführung von Kunden und Anbietern sowie die Durchführung von Beratungsgesprächen geeignet ist. Wir fungieren dadurch lediglich als Vermittler zwischen Kunden und Anbietern. Die Plattform übernimmt keine Verantwortung für die Qualität und Richtigkeit der Leistung eines Anbieters und überprüft auch nicht die Vollständigkeit und Richtigkeit seiner Angaben, da diese nicht durch die Plattform überprüft werden kann. Die Beratungsleistung wird ausschließlich durch unabhängige Anbieter erbracht, welche nicht bei uns oder einer verbundenen Gesellschaft angestellt sind. Den Vertrag über die jeweilige Beratungsleistung schließt der Kunde nicht mit uns, sondern mit dem betreffenden Anbieter ab.
            </span>
        </p>
        <p class="p28 ft1">
            <span class="ft1">
                2.5.
            </span>
            <span class="ft16">
                Kunden können gezielt nach Anbietern suchen und gemäß der im Profil enthaltenen Angaben selbstständig einen passenden Anbieter auswählen. Die Beurteilung der Kompetenz der Anbieter obliegt dem Kunden auf eigene Verantwortung
            </span>
        </p>
        <p class="p22 ft1">
            <span class="ft1">
                2.6.
            </span>
            <span class="ft16">
                Sofern ein jeweiliger Anbieter es anbietet, kann die Beratung über das auf der Plattform bereitgestellte Videokommunikationstool durchgeführt werden.
            </span>
        </p>
        <p class="p20 ft1">
            <span class="ft1">
                2.7.
            </span>
            <span class="ft16">
                Der Kunde wird darauf hingewiesen, dass Auskünfte eines Anbieters über die Plattform nicht dazu geeignet und bestimmt sind, eine professionelle psychologische Betreuung bzw. Behandlung durch einen Experten zu ersetzen. Hingewiesen wird darauf, dass die Befolgung von Ratschlägen aus einer Auskunft in der Regel außerhalb der Verantwortung eines Anbieters liegt. Jeder Kunde handelt insofern auf eigene Verantwortung. Sämtliche über die Plattform bereitgestellten Services stellen keine ärztliche- oder psychologische Beratung und/oder Behandlung dar und können eine solche weder ganz noch teilweise ersetzen. Insbesondere ist die Registrierung eines Nutzers auf der Plattform nicht geeignet, den Besuch bei einem Arzt oder Psychologen zu ersetzen. Es wird daher jedem Nutzer dringend empfohlen, im Bedarfsfall einen Arzt oder fachlich geeigneten Berater vor Ort zu konsultieren.
            </span>
        </p>
    </div>
    <div id="page_7">
        <p class="p24 ft7">
            <span class="ft7">
                3.
            </span>
            <span class="ft8">
                Geltungsbereich
            </span>
        </p>
        <p class="p25 ft1">
            Für die über die Plattform begründeten Rechtsbeziehungen zwischen uns und seinen Nutzern gelten ausschließlich die AGB in der jeweiligen Fassung zum Zeitpunkt des Rechtsgeschäftes oder der rechtsgeschäftsähnlichen Handlung. Abweichende und/oder über diese Allgemeinen Geschäftsbedingungen hinausgehende Geschäftsbedingungen des Nutzers werden nicht Vertragsinhalt.
        </p>
        <p class="p18 ft7">
            <span class="ft7">
                4.
            </span>
            <span class="ft8">
                Abschluss des Vertrages mit uns über die Nutzung der Plattform
            </span>
        </p>
        <p class="p41 ft17">
            <span class="ft17">
                4.1.
            </span>
            <span class="ft18">
                Die Bereitstellung der Website stellt noch kein verbindliches Angebot zum Abschluss eines entsprechenden Nutzungsvertrages zwischen dem Nutzer und uns dar. Ein verbindliches Angebot erfolgt vielmehr erst dadurch, dass der Nutzer sein Registrierungsgesuch über die Website an uns übermittelt. Dieses Angebot nehmen wir ggf. dadurch an, dass wir die Registrierung des Nutzers durch eine Registrierungsbestätigung per
            </span>
            <nobr>
                E-Mail
            </nobr>
            bestätigen. Wir sind berechtigt ohne Angabe von Gründen ein Registrierungsgesuch und folglich das Vertragsangebot aufgrund fehlender Angaben, unzureichender Qualifikation oder zu unserem Angebot unpassendem Leistungsspektrum des Anbieters abzulehnen.
        </p>
        <p class="p42 ft1">
            <span class="ft1">
                4.2.
            </span>
            <span class="ft16">
                Der Nutzer ist verpflichtet, bei einer Registrierung wahrheitsgemäße Angaben zu machen und seine Daten auch nach der Registrierung stets aktuell zu halten. Ein Nutzer darf sein Konto nicht an Dritte übertragen oder sonst zugänglich machen. Bei einem Verstoß behalten wir uns vor den Zugang zur Plattform zu verwehren oder den Vertrag mit dem Nutzer zu kündigen.
            </span>
        </p>
        <p class="p22 ft17">
            <span class="ft17">
                4.3.
            </span>
            <span class="ft18">
                Bei Verlust oder einer unbefugten Nutzung Ihrer Zugangsdaten sind wir unverzüglich zu unterrichten.
            </span>
        </p>
        <p class="p23 ft1">
            <span class="ft1">
                4.4.
            </span>
            <span class="ft16">
                Kunden steht darüber hinaus die Möglichkeit der Nutzung der Plattform im Rahmen einer Gastbuchung offen. Der Leistungsumfang der Plattform ist hierbei eingeschränkt.
            </span>
        </p>
        <p class="p22 ft1">
            <span class="ft1">
                4.5.
            </span>
            <span class="ft16">
                Leistungen, die auf der Plattform angeboten oder gesucht und sodann in Anspruch genommen werden, sind kostenpflichtig.
            </span>
        </p>
        <p class="p24 ft1">
            <span class="ft1">
                4.6.
            </span>
            <span class="ft16">
                Natürlichen Personen ist die Anmeldung nur gestattet, wenn sie volljährig sind.
            </span>
        </p>
        <p class="p24 ft1">
            <span class="ft1">
                4.7.
            </span>
            <span class="ft16">
                Die Vertragssprache ist Deutsch.
            </span>
        </p>
        <p class="p18 ft7">
            <span class="ft7">
                5.
            </span>
            <span class="ft8">
                Abschluss von Verträgen mit Anbietern
            </span>
        </p>
        <p class="p41 ft17">
            <span class="ft17">
                5.1.
            </span>
            <span class="ft18">
                Die Präsentation der Produkte auf der Plattform beinhaltet noch kein Angebot des jeweiligen Anbieters auf Abschluss eines entsprechenden Vertrages. Erst indem der Kunde eine Bestellung über die jeweilige Leistung absendet, unterbreitet er damit dem jeweiligen Anbieter ein Angebot zum Abschluss eines entsprechenden Vertrages. Der Vertrag kommt zustande, wenn und sobald der Anbieter das Angebot innerhalb einer angemessenen Frist im Wege einer Mitteilung über ein plattformeigenes Nachrichtensystem oder auch durch Inanspruchnahme der Dienstleistung annimmt.
            </span>
        </p>
        <p class="p43 ft1">
            <span class="ft1">
                5.2.
            </span>
            <span class="ft16">
                Die Vertragssprache ist deutsch
            </span>
        </p>
        <p class="p18 ft7">
            <span class="ft7">
                6.
            </span>
            <span class="ft8">
                Präsentation der Dienstleistung auf der Plattform und deren Nutzung als Anbieter
            </span>
        </p>
        <p class="p44 ft1">
            <span class="ft1">
                6.1.
            </span>
            <span class="ft16">
                Anbieter können die Präsentation ihrer Dienstleistungen in einem jeweils selbst administrierten Bereich gestalten.
            </span>
        </p>
    </div>
    <div id="page_8">
        <p class="p20 ft1">
            <span class="ft1">
                6.2.
            </span>
            <span class="ft19">
                Impressumspflicht:
            </span>
            Anbieter haben dafür Sorge zu tragen, dass Ihre Präsenz auf der Plattform (nachstehend "Produktpräsentation") ein Impressum enthält, das der Impressumspflicht im Sinne von
            <a href="{{route('imprint')}}">
                § 5 Telemediengesetz
            </a>
            genügt.
        </p>
        <p class="p20 ft17">
            <span class="ft17">
                6.3.
            </span>
            <span class="ft20">
                Einhaltung geltender Gesetze:
            </span>
            Anbieter haben dafür Sorge zu tragen, dass Ihre Produktpräsentation mit einschlägigen gesetzlichen Bestimmungen im Einklang steht. Unlautere, irreführende oder sonstige wettbewerbswidrige Werbung ist verboten.
        </p>
        <p class="p45 ft1">
            <span class="ft1">
                6.4.
            </span>
            <span class="ft19">
                Kein Verstoß der Produktpräsentationen gegen Rechte Dritter:
            </span>
            Anbieter haben dafür Sorge zu tragen, dass Ihre Produktpräsentation keine gewerblichen Schutzrechte Dritter oder Rechte Dritter an geistigem Eigentum verletzen wie bspw. Patentrechte, Urheberrechte, Namensrechte oder Kennzeichenrechte (Marken, Designs) und dass sie nicht gegen Datenschutzrecht oder Persönlichkeitsrechte Dritter oder sonstige Rechte Dritter verstößt.
        </p>
        <p class="p18 ft7">
            <span class="ft7">
                7.
            </span>
            <span class="ft8">
                Sonstige Mitwirkungspflichten der Anbieter
            </span>
        </p>
        <p class="p41 ft1">
            <span class="ft1">
                7.1.
            </span>
            <span class="ft16">
                Die Anbieter dürfen keine medizinische Beratung, Gesundheitsberatung sowie Beratung zum Thema Tod vornehmen. Darüber hinaus ist die Ausübung der Heilkunde, insbesondere die Stellung von Diagnosen und Therapien zur Linderung von Krankheiten über unsere Plattform verboten. Der Anbieter hat den Kunden im entsprechenden Fall über dieses Verbot zu unterrichten und ein Beratungsgespräch abzulehnen.
            </span>
        </p>
        <p class="p22 ft1">
            <span class="ft1">
                7.2.
            </span>
            <span class="ft16">
                Die Anbieter behandeln die Identität der Interessenten und die Inhalte der Beratung vertraulich.
            </span>
        </p>
        <p class="p26 ft7">
            <span class="ft7">
                8.
            </span>
            <span class="ft8">
                Gebühr
            </span>
        </p>
        <p class="p36 ft1">
            Für die Nutzung der Plattform als Anbieter berechnen wir dem Anbieter eine Gebühr welche sich nach der Anzahl an erbrachten Stunden staffelt:
        </p>
        <p class="p46 ft1">
            <span class="ft27">
                ∙
            </span>
            Bis 5 erbrachte Stunden beträgt die Gebühr 20% des vereinbarten Dienstleistungsentgeltes.
        </p>
        <p class="p47 ft1">
            <span class="ft27">
                ∙
            </span>
            <span class="ft28">
                Von 6 bis 10 erbrachte Stunden beträgt die Gebühr 17% des vereinbarten Dienstleistungsentgeltes.
            </span>
        </p>
        <p class="p48 ft1">
            <span class="ft27">
                ∙
            </span>
            <span class="ft28">
                Von 11 bis 15 erbrachte Stunden beträgt die Gebühr 15% des vereinbarten Dienstleistungsentgeltes.
            </span>
        </p>
        <p class="p47 ft1">
            <span class="ft27">
                ∙
            </span>
            <span class="ft28">
                Ab 15 erbrachte Stunden beträgt die Gebühr 12% des vereinbarten Dienstleistungsentgeltes.
            </span>
        </p>
        <p class="p49 ft1">
            Der anwendbare prozentuale Satz für die an uns zu entrichtende Gebühr ergibt sich aus den insgesamt auf einen Anbieter zum Zeitpunkt der jeweiligen Buchung erbrachten Beraterstunden und bezieht sich auf das
            <nobr>
                netto-Beratungsentgelt.
            </nobr>
            Mit der Inanspruchnahme der Leistungen auf himmlischberaten.de erklären sich die Nutzer mit diesem Preismodell einverstanden. Die Gebühr wird mit dem Beratungsentgelt verrechnet.
        </p>
    </div>
    <div id="page_9">
        <p class="p24 ft7">
            <span class="ft7">
                9.
            </span>
            <span class="ft8">
                Verpflichtung zur Verschwiegenheit
            </span>
        </p>
        <p class="p25 ft1">
            Nutzer sind zur Diskretion, Verschwiegenheit und Geheimhaltung aller über die Plattform ausgetauschten Informationen verpflichtet. Jede Form der Weitergabe von personenbezogenen Informationen und Daten an Dritte sowie deren Nutzung außerhalb des bestimmungsgemäßen Gebrauchs der Plattform ist ausdrücklich verboten.
        </p>
        <p class="p18 ft7">
            <span class="ft7">
                10.
            </span>
            <span class="ft21">
                Zahlungsabwicklung
            </span>
        </p>
        <p class="p27 ft1">
            <span class="ft1">
                10.1.
            </span>
            <span class="ft26">
                Die Zahlung leistet der Kunde direkt an den Anbieter.
            </span>
        </p>
        <p class="p22 ft17">
            <span class="ft17">
                10.2.
            </span>
            <span class="ft29">
                Die Abwicklung der Zahlungen von über die Plattform gebuchten Dienstleistungen ist ausschließlich durch Mangopay möglich. Mangopay ist ein elektronisches Zahlungssystem des Finanzdienstleisters Mangopay (59 Boulevard Royal,
            </span>
            <nobr>
                L-2449
            </nobr>
            Luxembourg), betrieben durch Leetchi Corp. S.A., 14 Rue Aldringen,
            <nobr>
                L-1118
            </nobr>
            Luxemburg. Im Falle der Nutzung des elektronischen Zahlungssystems Mangopay kommt zwischen dem Nutzer und Mangopay ein eigenständiger Vertrag nach Maßgabe der
            <a href="https://www.mangopay.com/terms/end-user-terms-and-conditions/Mangopay_Terms-DE.pdf">
                Allgemeinen Geschäftsbedingungen von Mangopay.
            </a>
            Mit der Zustimmung unserer AGB stimmt der Nutzer gleichzeitig den Allgemeinen Geschäftsbedingungen von Mangopay zu:
            <nobr>
                https://www.mangopay.com/terms/end-user-terms-and-conditions/Mangopay_Terms-
            </nobr>
            DE.pdf. Für den Zahlvorgang mit Hilfe von Mangopay übernehmen wir keine Haftung.
        </p>
        <p class="p50 ft1">
            <span class="ft1">
                10.3.
            </span>
            <span class="ft26">
                Die Gebühr von Mangopay tragen wir.
            </span>
        </p>
        <p class="p20 ft1">
            <span class="ft1">
                10.4.
            </span>
            <span class="ft26">
                Die Leetchi Corp. S.A., 14 Rue Aldringen,
            </span>
            <nobr>
                L-1118
            </nobr>
            Luxemburg ist ein nach den Bestimmungen der CSSF in Luxemburg zugelassenes und überwachtes
            <nobr>
                E-Geld-Institut
            </nobr>
            (No. W00000005).
        </p>
        <p class="p20 ft17">
            <span class="ft17">
                10.5.
            </span>
            <span class="ft29">
                Bei Fragen oder Reklamationen rund um Buchungen und Abrechnungen sind wir über das
            </span>
            <a href="{{route('contact-us')}}">
                Kontaktformular
            </a>
            oder über
            <nobr>
                e-mail
            </nobr>
            an
            <a href="mailto:kontakt@himmlischberaten.de">
                kontakt@himmlischberaten.de
            </a>
            erreichbar. Reklamationen sind innerhalb von 24 Stunden nach Erhalt der Abrechnung bzw. Tätigung der Buchung anzumelden. Bei verspäteter Anzeige können wir die Reklamation zurückweisen.
        </p>
        <p class="p29 ft1">
            <span class="ft1">
                10.6.
            </span>
            <span class="ft26">
                Das Beratungsentgelt ist mit erfolgreicher Buchung fällig und wird dem verwendeten Zahlungsmittel belastet.
            </span>
        </p>
        <p class="p20 ft17">
            <span class="ft17">
                10.7.
            </span>
            <span class="ft29">
                Der Anbieter erhält das Beratungsentgelt abzüglich der Servicegebühr auf sein hinterlegtes Bankkonto innerhalb von 5 Arbeitstagen nach Leistungserbringung ausbezahlt.
            </span>
        </p>
        <p class="p45 ft1">
            <span class="ft1">
                10.8.
            </span>
            <span class="ft26">
                Der Anbieter trägt das alleinige Risiko für Zahlungsausfälle von Kunden. Bei endgültigem Zahlungsausfall wird die Servicegebühr zur Nutzung der Plattform dem Anbieter erstattet.
            </span>
        </p>
        <p class="p51 ft7">
            <span class="ft7">
                11.
            </span>
            <span class="ft21">
                Rechnungsstellung und Abgaben
            </span>
        </p>
        <p class="p19 ft1">
            <span class="ft1">
                11.1.
            </span>
            <span class="ft26">
                Anbieter werden im Rahmen ihrer selbstständigen Tätigkeit im eigenen Namen und auf eigene Verantwortung tätig. Der Anbieter erbringt eine entgeltliche Dienstleistung, für die der Kunde nach Abschluss der vereinbarten Dienstleistung ein Beratungsentgelt bezahlt.
            </span>
        </p>
        <p class="p20 ft1">
            <span class="ft1">
                11.2.
            </span>
            <span class="ft26">
                Der Anbieter beauftragt uns, eine Rechnung über die jeweils vereinbarte Dienstleistung an den Kunden zu erstellen. Der Anbieter ist für die korrekte Versteuerung sowie für möglicherweise anfallenden
            </span>
            <nobr>
                Steuer-,
            </nobr>
            Sozial- oder sonstigen Abgaben selbst verantwortlich. Die Rechnungsdokumente werden in elektronischer Form erstellt und bereitgestellt. Für die ordnungsgemäße Archivierung der Dokumente ist der Anbieter selbst verantwortlich.
        </p>
    </div>
    <div id="page_10">
        <p class="p24 ft7">
            <span class="ft7">
                12.
            </span>
            <span class="ft21">
                Stornierung und Terminverschiebung
            </span>
        </p>
        <p class="p19 ft1">
            <span class="ft1">
                12.1.
            </span>
            <span class="ft30">
                Stornierung durch den Kunden:
            </span>
            Storniert ein Kunde eine bestätigte Buchung bis zu 48 Stunden vor Beginn der Beratung, wird das gesamte Beratungsentgelt erstattet. Storniert er innerhalb von 48 Stunden vor Beginn der Beratung, werden 60% des Beratungsentgeltes einbehalten.
        </p>
        <p class="p20 ft1">
            <span class="ft1">
                12.2.
            </span>
            <span class="ft30">
                Stornierung durch den Anbieter:
            </span>
            Sofern ein Anbieter eine bestätigte Buchung storniert, wird dies für alle Nutzer auf dem Profil des jeweiligen Anbieters sichtbar gemacht und der Kunde erhält das gesamte Beratungsentgelt erstattet. Für den Anbieter entstehen keine Kosten.
        </p>
        <p class="p20 ft17">
            <span class="ft17">
                12.3.
            </span>
            <span class="ft29">
                Der registrierte Kunde hat die Möglichkeit bis zu 48 Stunden vor der Beratung den Termin einmalig zu verschieben. Hierzu kann er aus den verfügbaren Terminen seines gebuchten Anbieters einen Alternativtermin auswählen und verbindlich buchen. Eine Terminanfrage ist im Terminverschiebungsprozess nicht vorgesehen.
            </span>
        </p>
        <p class="p52 ft1">
            <span class="ft1">
                12.4.
            </span>
            <span class="ft26">
                Erscheint der Kunde nicht zur vereinbarten Beratung am gewählten Beratungsort, online oder offline, ist die volle Höhe des Beratungsentgeltes zu entrichten.
            </span>
        </p>
        <p class="p18 ft7">
            <span class="ft7">
                13.
            </span>
            <span class="ft21">
                Haftung
            </span>
        </p>
        <p class="p27 ft1">
            <span class="ft1">
                13.1.
            </span>
            <span class="ft26">
                Für eine Haftung von uns auf Schadensersatz gilt:
            </span>
        </p>
        <p class="p53 ft17">
            <span class="ft17">
                a.
            </span>
            <span class="ft23">
                Bei Vorsatz und grober Fahrlässigkeit, auch unserer Erfüllungsgehilfen, haften wir nach den gesetzlichen Bestimmungen. Das gleiche gilt bei fahrlässig verursachten Schäden aus der Verletzung des Lebens, des Körpers oder der Gesundheit.
            </span>
        </p>
        <p class="p54 ft1">
            <span class="ft1">
                b.
            </span>
            <span class="ft22">
                Bei fahrlässig verursachten Sach- und Vermögensschäden haften wir nur bei der Verletzung einer wesentlichen Vertragspflicht, jedoch der Höhe nach beschränkt auf die bei Vertragsschluss vorhersehbaren und vertragstypischen Schäden; wesentliche Vertragspflichten sind solche, deren Erfüllung die ordnungsgemäße Durchführung des Vertrags überhaupt erst ermöglicht und auf deren Einhaltung der Vertragspartner regelmäßig vertrauen darf.
            </span>
        </p>
        <p class="p35 ft1">
            <span class="ft24">
                c.
            </span>
            <span class="ft25">
                Im Übrigen ist eine Haftung von uns, unabhängig von deren Rechtsgrund, ausgeschlossen.
            </span>
        </p>
        <p class="p35 ft17">
            <span class="ft17">
                d.
            </span>
            <span class="ft23">
                Die Haftungsausschlüsse und
            </span>
            <nobr>
                -beschränkungen
            </nobr>
            der vorstehenden Absätze (a) bis (c) gelten sinngemäß auch zugunsten unserer Erfüllungsgehilfen.
        </p>
        <p class="p55 ft1">
            <span class="ft1">
                e.
            </span>
            <span class="ft22">
                Eine Haftung wegen Übernahme einer Garantie oder nach dem Produkthaftungsgesetz bleibt von den Haftungsausschlüssen und - beschränkungen der vorstehenden Absätze (a) bis (d) unberührt.
            </span>
        </p>
        <p class="p18 ft7">
            <span class="ft7">
                14.
            </span>
            <span class="ft21">
                Nutzungsrechte
            </span>
        </p>
        <p class="p25 ft1">
            Wir behalten uns vor, von Nutzern eingestellten Content, sowohl Texte als auch Bild- Video und Tonmaterial auf die Einhaltung der gesetzlichen Vorgaben und der Zweckdienlichkeit im Sinne dieser AGB zu überprüfen und, wenn nötig, ganz oder teilweise zu löschen. Eine Verpflichtung zu einer solchen Überprüfung besteht für uns ausdrücklich nicht.
        </p>
    </div>
    <div id="page_11">
        <p class="p24 ft7">
            <span class="ft7">
                15.
            </span>
            <span class="ft21">
                Kündigung
            </span>
        </p>
        <p class="p27 ft1">
            <span class="ft1">
                15.1.
            </span>
            <span class="ft26">
                Das Vertragsverhältnis wird auf unbestimmte Zeit geschlossen.
            </span>
        </p>
        <p class="p20 ft1">
            <span class="ft1">
                15.2.
            </span>
            <span class="ft26">
                Jede Partei ist berechtigt, den Vertrag zur Nutzung der Plattform jederzeit ohne Kündigungsfrist und ohne Angaben von Gründen schriftlich zu kündigen. Etwaige Zahlungspflichten bleiben hiervon unberührt.
            </span>
        </p>
        <p class="p22 ft1">
            <span class="ft1">
                15.3.
            </span>
            <span class="ft26">
                Wir behalten uns vor, nach einer Kündigung andere Nutzer, über diese Kündigung zu informieren, sofern diese mit der gekündigten Person in Kontakt standen.
            </span>
        </p>
        <p class="p20 ft1">
            <span class="ft1">
                15.4.
            </span>
            <span class="ft26">
                Nach Beendigung des Vertragsverhältnisses zwischen uns und dem Nutzer werden sämtliche Daten des Nutzers gelöscht, es sei denn, gesetzliche oder vertragliche Aufbewahrungsverpflichtungen stehen dagegen.
            </span>
        </p>
        <p class="p26 ft7">
            <span class="ft7">
                16.
            </span>
            <span class="ft21">
                Datenschutz
            </span>
        </p>
        <p class="p56 ft1">
            Für die Nutzung der Website gilt die Datenschutzerklärung, die unter Datenschutz auf der Website abgerufen werden kann.
        </p>
        <p class="p57 ft7">
            <span class="ft7">
                17.
            </span>
            <span class="ft21">
                Gerichtsstand, anwendbares Recht
            </span>
        </p>
        <p class="p19 ft1">
            <span class="ft1">
                17.1.
            </span>
            <span class="ft26">
                Es gilt deutsches Recht. Gegenüber einem Verbraucher gilt diese Rechtswahl nur insoweit, als dadurch keine zwingend anwendbaren gesetzlichen Bestimmungen des Staates, in dem er seinen Wohnsitz oder gewöhnlichen Aufenthalt hat, eingeschränkt werden.
            </span>
        </p>
        <p class="p20 ft1">
            <span class="ft1">
                17.2.
            </span>
            <span class="ft26">
                Gerichtsstand im Verkehr mit Kaufleuten, juristischen Personen des öffentlichen Rechts oder
            </span>
            <nobr>
                öffentlich-rechtlichen
            </nobr>
            Sondervermögen ist der Sitz unseres Unternehmens. Wir sind jedoch nach unserer Wahl berechtigt, am Sitz des Kunden zu klagen.
        </p>
    </div>
</div>
@endsection
