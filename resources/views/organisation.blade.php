@extends('layouts.app')

@section('content')
	<div class="container-fluid coach_banner">
		<!--<img class="img-fluid" src="{{ asset('frontend/img/organisation-banner.jpg') }}" alt="x"/>-->
		<div class="container">
            <div class="row">
				<div class="org_banner_txt col-md-12">
					<h2>Setzen Sie mehr Qualität <span>in Ihrer Organisation frei</span></h2>
					<h5>Die Plattform für Kommunikation und Weiterentwicklung</h5>
				</div>
				<p class="org_request col-md-12 text-center"><a href="{{route('contact-us')}}">Anfragen</a></p>
			</div>
		</div>
	</div>
	
	<!-- individual solution -->
	<div class="container-fluid consultant_adv_wrapper">
		<div class="container text-center indi_sol">
			<div class="row">
				<h2 class="col-12 section_head">Nutzen Sie unsere Technologie <span>für Ihre Organisation</span></h2>
			</div>
			<div class="row justify-content-center">
				<p class="col-sm-12 col-md-8 promise_data">Gemeinsam entwickeln wir maßgeschneiderte Konzepte, um mehr Qualität in Ihrer Organisation freizusetzen. Nutzen Sie umfangreichen Funktionen von himmlischberaten.de wie beispielsweise die Suche des passenden Beraters, Terminvereinbarung, Bezahlung und Rechnungsstellung um schnell und kosteneffizient große Wirkung zu erzielen.</p>
			</div>
			<div class="row justify-content-center solution_select_wrap">
				<div class="solution_select">
					<img src="{{ asset('frontend/img/icons/church.png') }}" alt="x">
					<h5>Kirchen & <span>Gemeinden</span></h5>
				</div>
				<div class="solution_select">
					<img src="{{ asset('frontend/img/icons/mission_services.png') }}" alt="x">
					<h5>Missionarische <span>Dienste</span></h5>
				</div>
				<div class="solution_select">
					<img src="{{ asset('frontend/img/icons/consulting_org.png') }}" alt="x">
					<h5>Beratungs- <span>organisationen</span></h5>
				</div>
				<div class="solution_select">
					<img src="{{ asset('frontend/img/icons/org_socities.png') }}" alt="x">
					<h5>Vereine</h5>
				</div>
				<div class="solution_select">
					<img src="{{ asset('frontend/img/icons/org_companies.png') }}" alt="x">
					<h5>Unternehmen</h5>
				</div>
			</div>
		</div>
	</div>
	<!-- individual solution end -->
	
	<!-- organisation tabs -->
	<div class="container-fluid org_tabs_container">
		<div class="container">
			<div class="row text-center">
				<h2 class="col-12 section_head">So setzen Organisationen himmlischberaten.de ein</h2>
			</div>
			<ul class="nav nav-pills org_tabs mb-2 text-center" id="pills-tab" role="tablist">
				<li class="nav-item col-md-4 pl-md-1 pr-md-1 pl-0 pr-0 mb-1 mb-md-0">
					<a class="nav-link org_pill_head active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Ihre Plattform zur <span>Weiterentwicklung</span></a>
				</li>
				<li class="nav-item col-md-4 pl-md-1 pr-md-1 pl-0 pr-0 mb-1 mb-md-0">
					<a class="nav-link org_pill_head" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Ihr exklusiver <span>Berater-Pool</span></a>
				</li>
				<li class="nav-item col-md-4 pl-md-1 pr-md-1 pl-0 pr-0 mb-1 mb-md-0">
					<a class="nav-link org_pill_head" id="pills-contact-tab" data-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Ihr Werkzeug zur <span>Vernetzung</span></a>
				</li>
			</ul>
			<div class="tab-content org_tab_data" id="pills-tabContent">
				<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
					<div class="row">
						<div class="col-md-12 mb-3 org_tab_desc">
							<p>Bauen Sie Ihren eigenen Pool an Beratern bei himmlischberaten.de</p>
						</div>
						<div class="col-md-12 mb-3 org_tab_desc">
							<p>Sparen Sie Zeit und Kosten, die richtigen Berater zu finden</p>
						</div>
						<div class="col-md-12 mb-3 org_tab_desc">
							<p>Zugang zu allen registrierten Beratern auf himmlischberaten.de</p>
						</div>
						<div class="col-md-12 mb-3 org_tab_desc">
							<p>Profitieren Sie von automatisierter Zahlung und Rechnungsstellung</p>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
					<div class="row">
						<div class="col-md-12 mb-3 org_tab_desc">
							<p>Nutzen Sie die Technologie für Ihren exklusiven Pool an internen und externen Beratern</p>
						</div>
						<div class="col-md-12 mb-3 org_tab_desc">
							<p>Anpassung an Ihr Design möglich</p>
						</div>
						<div class="col-md-12 mb-3 org_tab_desc">
							<p>Binden Sie die Plattform in Ihren Webauftritt ein</p>
						</div>
						<div class="col-md-12 mb-3 org_tab_desc">
							<p>Profitieren Sie von automatisierter Zahlung und Rechnungsstellung</p>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
					<div class="row">
						<div class="col-md-12 mb-3 org_tab_desc">
							<p>Vernetzen Sie Interessenten mit aktiven Mitarbeitern</p>
						</div>
						<div class="col-md-12 mb-3 org_tab_desc">
							<p>Teilen Sie Erfahrungen innerhalb Ihrer Organisation</p>
						</div>
						<div class="col-md-12 mb-3 org_tab_desc">
							<p>Führen Sie Schulungen und Wissenstransfer online durch</p>
						</div>
						<div class="col-md-12 mb-3 org_tab_desc">
							<p>Anpassung an Ihr Design möglich</p>
						</div>
						<div class="col-md-12 mb-3 org_tab_desc">
							<p>Profitieren Sie von automatisierter Zahlung und Rechnungsstellung</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- organisation tabs end -->
	
	<!-- individual solution -->
	<div class="container-fluid individual_sol_wrapper">
		<div class="container indi_sol">
			<div class="row">
				<div class="col-md-12 col-lg-6">
					<h2 class="section_head">Wir realisieren <span>individuelle Lösungen</span></h2>
					<p class="our_promise_btn"><a href="{{route('contact-us')}}">Anfragen</a></p>
				</div>
			</div>
		</div>
	</div>
	<!-- individual solution end -->
	
	<!-- life counselling -->
	<div class="container-fluid life_counsel_wrapper">
		<div class="container text-center">
			<div class="row justify-content-center">
				<div class="col-sm-12 col-md-8">
					<h4>Mit himmlischberaten.de schaffen wir einen einfachen und schnellen Zugang zu professioneller Lebensberatung auf christlicher Basis.</h4>
					<h4>Niemand, der Hilfe sucht, bleibt mit seinen Herausforderungen alleine.</h4>
				</div>
			</div>
		</div>
	</div>
	<!-- life counselling end-->
	
@endsection

@section('scripts')
@endsection
