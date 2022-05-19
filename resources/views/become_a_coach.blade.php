@extends('layouts.app')

@section('style_link')
    <link rel="stylesheet" href="{{ asset('frontend/css/owl.carousel.min.css') }}">
@endsection

@section('content')
<div class="container-fluid coach_banner">
    <!--<img class="img-fluid" src="{{ asset('frontend/img/coach_banner.jpg') }}" alt="x"/>-->
	<div class="container">
		<div class="row">
			<div class="col-md-12 coach_banner_txt">
				<h2>Erweitere deine <span>Möglichkeiten.</span></h2>
				<h5>Werde Berater auf himmlischberaten.de</h5>
			</div>
		</div>
	</div>
</div>

<!-- consultant advantage -->
<div class="container-fluid consultant_adv_wrapper">
    <div class="container text-center">
        <div class="row">
            <h2 class="col-12 section_head">Deine Vorteile als Berater <span>bei himmlischberaten.de</span></h2>
        </div>
        <div class="row advice_boxes consult_adv_boxes">
            <div class="col-sm-12 col-md-6 advice adv_online">
                <img src="{{ asset('frontend/img/icons/help_network.png') }}" alt="x">
                <h5>Erweitere dein <span>Hilfenetzwerk online</span></h5>
                <p>Erstelle kostenlos dein Profil. Mache durch dein Wissen und deine Erfahrung auf dich aufmerksam und profitiere von Marketingmaßnahmen.</p>
            </div>
            <div class="col-sm-12 col-md-6 advice adv_online">
                <img src="{{ asset('frontend/img/icons/berate.png') }}" alt="x">
                <h5>Berate, wann und wo <span>du willst</span></h5>
                <p>Du kannst sowohl online als auch in deinen Räumlichkeiten deine Beratung anbieten. Spare Aufwand für Terminabstimmungen durch deinen Verfügbarkeitskalender.</p>
            </div>
            <div class="col-sm-12 col-md-6 advice">
                <img src="{{ asset('frontend/img/icons/payment_system.png') }}" alt="x">
                <h5>Bezahlsystem inklusive</h5>
                <p>Wir kümmern uns um die Bezahlung deiner Beratung und zahlen dir innerhalb von 48 Stunden das Beratungsentgelt auf dein hinterlegtes Bankkonto aus.</p>
            </div>
            <div class="col-sm-12 col-md-6 advice">
                <img src="{{ asset('frontend/img/icons/invoices.png') }}" alt="x">
                <h5>Rechnungsstellung inklusive</h5>
                <p>Wir erstellen deine Rechnung und senden sie sowohl dir als auch dem Kunden elektronisch zu. Selbstverständlich unter Berücksichtigung deiner Steuerangaben.</p>
            </div>
        </div>
    </div>
</div>
<!-- consultant advantage end -->

<!-- profile steps -->
<div class="container-fluid profile_step_wrapper">
    <div class="container text-center">
        <div class="row">
            <h2 class="col-12 section_head">In 4 Schritten zu deinem Profil</h2>
        </div>
    </div>
</div>
<div class="container-fluid profile_step_data">
    <img class="profile_line" src="{{ asset('frontend/img/line_dot.png') }}" alt="x">
    <div class="col-sm-12 col-md-3 step_pro pro_step_one text-center">
        <div class="pro_step_icon">
            <img src="{{ asset('frontend/img/icons/profile_data.png') }}" alt="x">
        </div>
        <h5>1. Profildaten <span>eingeben</span></h5>
        <p>Erstelle dein Profil,indem du deine Stammdaten, Ausbildung und Kategorien eingibst und festlegst, ob du Offline-Beratung anbieten möchtest.</p>
    </div>
    <div class="col-sm-12 col-md-3 step_pro pro_step_two text-center">
        <div class="pro_step_icon">
            <img src="{{ asset('frontend/img/icons/available.png') }}" alt="x">
        </div>
        <h5>2. Verfügbarkeit <span>festlegen</span></h5>
        <p>Trage deine Verfügbarkeit in einer einfachen Kalenderfunktion ein. Du kannst diese Daten jederzeit ändern.</p>
    </div>
    <div class="col-sm-12 col-md-3 step_pro pro_step_three text-center">
        <div class="pro_step_icon">
            <img src="{{ asset('frontend/img/icons/acc_deposite.png') }}" alt="x">
        </div>
        <h5>3. Bankverbindung<span>hinterlegen</span></h5>
        <p>Hinterlege deine deutsche Bank-verbindung, auf die wir dein Beratungsentgeld überweisen dürfen.</p>
    </div>
    <div class="col-sm-12 col-md-3 step_pro pro_step_four text-center">
        <div class="pro_step_icon">
            <img src="{{ asset('frontend/img/icons/pro_confirm.png') }}" alt="x">
        </div>
        <h5>4. Bestätigung <span>& Los geht’s!</span></h5>
        <p>Nachdem wir deine dein Profil freigeschalten haben, kannst du loslegen!</p>
    </div>
</div>
<!-- profile steps end -->

<!-- our fee modal -->
<div class="container-fluid hm_consultant_wrapper">
    <div class="container">
        <div class="row justify-content-center text-center">
            <h2 class="col-12 section_head">Unser Gebührenmodell</h2>
        </div>
        <div class="row function_ready_wrap">
            <div class="col-md-12 profile_desc">
                <p>Wir haben die Vision, dass himmlischberaten.de ein Angebot ist mit dem niemand der Hilfe sucht alleine bleibt und stellen dir hierfür eine zeitgemäße Plattform mit vielen Funktionen bereit.</p>
            </div>
            <div class="col-md-6 profile_step_instruct">
                <ul>
                    <li>Keine Anmeldegebühr</li>
                    <li>Keine monatliche Gebühr im Basistarif</li>
                    <li>Nur bei einer durchgeführten Beratung entstehen Kosten</li>
                </ul>
            </div>
            <div class="col-md-6">
                <table class="table profile_table table-bordered text-center">
                  <thead>
                    <tr>
                      <th scope="col">Beratungszeit</th>
                      <th scope="col">Gebühren</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Bis 5 Stunden</td>
                      <td>20%</td>
                    </tr>
                    <tr>
                      <td>6 bis 10 Stunden</td>
                      <td>17%</td>
                    </tr>
                    <tr>
                      <td>11 bis 15 Stunden</td>
                      <td>15%</td>
                    </tr>
                    <tr>
                      <td>Ab 15 Stunden</td>
                      <td>12%</td>
                    </tr>
                  </tbody>
                </table>
            </div>
			<div class="col-md-12 profile_step_instruct">
                <p>Wir bauen auf eine gute und langfristige Zusammenarbeit und berechnen daher weniger Gebühren, je mehr Beratungen du über himmlischberaten.de durchführst. Die Gebühren sind netto und beziehen sich auf die Gesamtzeit aller bei himmlischberaten.de durchgeführten Beratungen.</p>
            </div>
        </div>
    </div>
</div>
<!-- our fee modal -->

<!-- teriff section -->
<div class="container-fluid tariff_container">
    <div class="container text-center">
        <div class="row justify-content-center">
            <h1 class="col-12 section_head tariff_head">Wähle deinen Tarif</h1>
            <div class="col-md-4 col-lg-3 text-center">
                <div class="tariff_data">
                    <h5>Basis-Tarif</h5>
                    <h2>0,- €/Monat</h2>
                    <ul>
                        <li>Online-Profil</li>
                        <li>Online/Offline-Beratung</li>
                        <li>Terminbuchung</li>
                        <li>Bezahlfunktion</li>
                        <li>Rechnungsstellung</li>
                        <li>Blog</li>
                    </ul>
                    <p class="tariff_choose text-center"><a href="{{ route('coach-register') }}">Auswählen</a></p>
                </div>
            </div>
            <div class="col-md-4 col-lg-3 text-center">
                <div class="tariff_data launching_soon">
                    <p class="soon_strip">Demnächst</p>
                    <h5>Plus-Tarif</h5>
                    <h2>19,- €/Monat</h2>
                    <ul>
                        <li>Online-Profil</li>
                        <li>Online/Offline-Beratung</li>
                        <li>Terminbuchung</li>
                        <li>Bezahlfunktion</li>
                        <li>Rechnungsstellung</li>
                        <li>Blog</li>
                        <li>Top-Platzierung</li>
                    </ul>
                    <p class="tariff_choose text-center"><a href="javascript:void()">Auswählen</a></p>
                </div>
            </div>
            <div class="col-md-4 col-lg-3 text-center">
                <div class="tariff_data launching_soon">
                    <p class="soon_strip">Demnächst</p>
                    <h5>Premium-Tarif</h5>
                    <h2>39,- €/Monat</h2>
                    <ul>
                        <li>Online-Profil</li>
                        <li>Online/Offline-Beratung</li>
                        <li>Terminbuchung</li>
                        <li>Bezahlfunktion</li>
                        <li>Rechnungsstellung</li>
                        <li>Blog</li>
                        <li>Top-Platzierung</li>
                        <li>Online-Marketing</li>
                    </ul>
                    <p class="tariff_choose text-center"><a href="javascript:void()">Auswählen</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- customer say section -->
@if($coachsays->count()>0)
<div class="container-fluid hm_customer_wrapper">
    <div class="container text-center">
        <div class="row justify-content-center">
            <h2 class="col-12 section_head">Das sagen andere Coaches</h2>
            <div class="col-md-10 col-lg-6 owl_testi_hm owl_ot_coach">
                <div class="owl-carousel">
                    @foreach ($coachsays as $coachsay)
                        @php 
                            $by_coach = $coachsay->coach;
                            $coach_company  = $by_coach->companies->first();
                        @endphp
                        <div class="carousal_data">
                            <p>„{{$coachsay->comment}}“</p>
                            <img src="{{ FileUploadHelper::getDocPath($by_coach->avatar, 'avatar') }}" alt="{{$by_coach->first_name}} {{$by_coach->last_name}}" />
                            <h4>
                                <a style="color:#ffffff" href="{{route('coach-detail',['name'=>$by_coach->first_name.'-'.$by_coach->last_name,'id'=>$by_coach->id])}}">
                                    {{$by_coach->first_name}} {{$by_coach->last_name}}
                                    @if($coach_company)
                                        <span>{{ $coach_company->pivot->designation }} bei {{ strtolower($coach_company->name)=='other'?$coach_company->pivot->company_name:$coach_company->name }}</span>
                                    @endif
                                </a>
                            </h4>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- FAQ section -->
<div class="container-fluid faq_coach_wrapper">
    <div class="container">
        <div class="row">
            <h2 class="col-12 text-center section_head">Häufig gestellte Fragen</h2>
            <div class="col-md-12 accordion coach_faq_accord" id="forconsultant">
                @foreach ($coach_faqs as $faq)
                    <div class="card">
                        <div class="card-header" id="heading{{$faq->id}}">
                          <h2 class="mb-0">
                            <button class="btn btn-link {{ !$loop->first?'collapsed':'' }}" type="button" data-toggle="collapse" data-target="#consultant{{$faq->id}}" aria-expanded="{{ $loop->first?'true':'false' }}" aria-controls="consultant{{$faq->id}}">
                              {{$faq->title}}
                            </button>
                          </h2>
                        </div>
                        <div id="consultant{{$faq->id}}" class="collapse {{ $loop->first?'show':'' }}" aria-labelledby="heading{{$faq->id}}" data-parent="#forconsultant">
                          <div class="card-body">
                            {{$faq->description}}
                          </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- life counselling -->
<div class="container-fluid life_counsel_wrapper">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-sm-12 col-md-8">
                <h4>Mit himmlischberaten.de schaffen wir einen einfachen und schnellen Zugang zu professioneller Lebensberatung auf christlicher Basis. Niemand, der Hilfe sucht, bleibt mit seinen Herausforderungen alleine.</h4>
            </div>
        </div>
    </div>
</div>
<!-- life counselling end-->
@endsection

@section('scripts')
    <script src="{{ asset('frontend/js/owl.carousel.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(".owl_testi_hm .owl-carousel").owlCarousel({
                items:1,
                loop:true,
                nav:true,
                dots:true,
                margin:30,
                autoplay:true,
                autoplayTimeout:6000,
                navText: [
                    '<i class="fa fa-angle-left" aria-hidden="true"></i>',
                    '<i class="fa fa-angle-right" aria-hidden="true"></i>'
                ]
            });
        });
    </script>
@endsection