@extends('layouts.app')

@section('content')
	
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
	
	<!-- helped us -->
	<div class="container-fluid helpedus_container">
		<div class="container">
			<div class="row">
				<h2 class="col-12 section_head font50">Das ist es, was uns bei himmlischberaten.de <span>immer wieder aufs Neue antreibt.</span></h2>
			</div>
			<div class="row">
				<div class="col-md-6 col-lg-6 drive_section descpadding">
					<p>Eine vertrauensvolle internationale Gemeinschaft aufzubauen, die Berater und Ratsuchende zusammenbringt – heute in Deutschland und morgen auf der ganzen Welt. Wir ermöglichen es vielen Menschen egal welcher Herkunft, jung oder alt, sich selbst besser zu verstehen, Herausforderungen zu meistern und nächste konkrete Schritte im Leben zu gehen. Gemeinsam erschaffen wir einen einfachen und schnellen Zugang zu professioneller Lebensberatung auf christlicher Basis. Niemand, der Hilfe sucht, bleibt mit seinem Anliegen alleine. </p>
				</div>
				<div class="col-md-6 col-lg-6 drive_section">
					<img src="{{ asset('frontend/img/thats_it.png') }}" alt="x"/>
				</div>
			</div>
		</div>
	</div>
	<!-- helped us end -->
	
	<!-- values modal -->
	<div class="container-fluid abt_values_wrapper">
		<div class="container text-center">
			<div class="row">
				<h2 class="col-12 section_head">Unsere Werte</h2>
			</div>
			<div class="row advice_boxes consult_adv_boxes abt_value_box">
				<div class="col-sm-12 col-md-6 advice adv_online">
					<img src="{{ asset('frontend/img/icons/help.png') }}" alt="x">
					<h5>Helfen</h5>
					<p>Helfen ist die DNA von himmlischberaten.de. Wir helfen Menschen dort, wo sie gerade stehen und sind erste Anlaufstelle bei Lebensberatung. Wir leben und teilen unsere Vision, damit niemand, der Hilfe sucht, mit seinem Anliegen alleine bleibt.</p>
				</div>
				<div class="col-sm-12 col-md-6 advice adv_online">
					<img src="{{ asset('frontend/img/icons/benefit.png') }}" alt="x">
					<h5>Nutzen stiften</h5>
					<p>Es ist die Begeisterung für technische Möglichkeiten einen einfachen Zugang zu professioneller Beratung zu schaffen die uns antreibt. Hilfe in Anspruch zu nehmen soll transparent, intuitiv und sicher sein – sowohl für den Ratsuchenden als auch für den Berater. </p>
				</div>
				<div class="col-sm-12 col-md-6 advice">
					<img src="{{ asset('frontend/img/icons/faith.png') }}" alt="x">
					<h5>Glaube</h5>
					<p>Uns verbindet der Glaube an Jesus Christus, der  uns motiviert, Beratung auf christlicher Basis für jeden verfügbar zu machen. Wir sind überzeugt, dass insbesondere durch den Glauben Kraft, Ermutigung und Wegweisung möglich sind und wollen mit diesen Grundlagen Beratungen möglich machen.</p>
				</div>
				<div class="col-sm-12 col-md-6 advice">
					<img src="{{ asset('frontend/img/icons/professional.png') }}" alt="x">
					<h5>Professionalität</h5>
					<p>Professionalität ist uns ein wichtiges Anliegen. Unser Ziel ist es, himmlischberaten.de so professionell wie möglich zu machen. Darum arbeiten wir mit den führenden Ausbildungsbetrieben zusammen, um eine besondere Professionalitätskultur der Beratung zu gewährleisten.</p>
				</div>
			</div>
		</div>
	</div>
	<!-- values modal end -->
	
	<!-- partner about -->
	{{-- <div class="container-fluid helpedus_container">
		<div class="container text-center">
			<div class="row">
				<h2 class="col-12 section_head">Partnerorganisationen</h2>
				<p class="partnerdesc">Unsere Berater sind bei ausgewählten Organisationen ausgebildet worden und stellen somit ein hohes Maß an Professionalität in Verbindung mit christlichen Wertesystemen dar.</p>
			</div>
			<div class="partner_logo">
				<ul>
					<li><img src="{{ asset('frontend/img/icl.png') }}" alt="x"></li>
					<li><img src="{{ asset('frontend/img/IGNIS.png') }}" alt="x"></li>
					<li><img src="{{ asset('frontend/img/acc.png') }}" alt="x"></li>
					<li><img src="{{ asset('frontend/img/Logo-CISL.png') }}" alt="x"></li>
					<li><img src="{{ asset('frontend/img/fcom.png') }}" alt="x"></li>
				</ul>
			</div>
		</div>
	</div> --}}
	<!-- partner about end -->
@endsection

@section('scripts')
@endsection
	
