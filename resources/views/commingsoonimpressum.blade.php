<!doctype html>
<html class="no-js" lang="de">

<head>
    <meta charset="utf-8">
    <title>{{ config('app.name', '') }}{{isset($page_title)?' :: '.$page_title:''}}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#fafafa">

    <!-- ================== CSRF TOKEN ====================== -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- ================== BEGIN BASE CSS STYLE ================== -->
    <link rel="stylesheet" href="{{ asset('frontend/css/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/font-awesome.min.css') }}">   
    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}">
    <!-- ================== END BASE CSS STYLE ================== -->
    <link rel="stylesheet" href="{{ asset('frontend/css/main.css') }}">
</head>

<body>
    <div id="app">
        <!-- ================== HEADER START ================== -->
        <header class="container-fluid">
            <div class="container">
                <div class="row">
                    <div class="logo_wrap col-md-auto">
                        <a href="{{ url('/') }}"><img src="{{ asset('frontend/img/logo.png') }}" alt="x"/></a>
                    </div>
                </div>
            </div>
        </header>
        <!-- ================== HEADER END ================== -->

		<div class="container-fluid contact_container imprint_container">
			<div class="container">
				<div class="row">
					<div class="col-md-12 col-lg-7 register_frm">
						<h2 class="section_head mb-5">Impressum</h2>
						<h5 class="ch_tabdata_head mb-2">Betreiber und Kontakt</h5>
						<p class="promise_data">lifepresso GmbH</p>
						<!-- <p class="promise_data">himmlischberaten.de</p>
						<p class="promise_data">Eine Marke der lifepresso GmbH</p> -->
						<p class="promise_data">Hauptstraße 156</p>
						<p class="promise_data">76351 Linkenheim-Hochstetten</p>
						<h5 class="ch_tabdata_head mb-2 mt-4">Vertretungsberechtigter Geschäftsführer</h5>
						<p class="promise_data">Christian List</p>
						<h5 class="ch_tabdata_head mb-2 mt-4">Telefon</h5>
						<p class="promise_data">0177 1835867</p>
						<h5 class="ch_tabdata_head mb-2 mt-4">E-Mail</h5>
						<p class="promise_data">kontakt@himmlischberaten.de</p>
						<h5 class="ch_tabdata_head mb-2 mt-4">Registergericht</h5>
						<p class="promise_data">Amtsgericht Mannheim</p>
						<h5 class="ch_tabdata_head mb-2 mt-4">Register und Registernummer</h5>
						<p class="promise_data">HRB 733658</p>
						<h5 class="ch_tabdata_head mb-2 mt-4">USt-ID</h5>
						<p class="promise_data">DE325035583</p>
						<h5 class="ch_tabdata_head imprint_hd mb-2 mt-4">Verantwortlicher für journalistisch- <span>redaktionelle Inhalte gem. § 55 II RstV</span></h5>
						<p class="promise_data">Christian List</p>
						<p class="promise_data">Hauptstraße 156</p>
						<p class="promise_data">76351 Linkenheim-Hochstetten</p>
						<h5 class="ch_tabdata_head imprint_hd mb-2 mt-4">Angaben der Quelle für verwendetes Bilder- <span>und Grafikmaterial:</span></h5>
						<p class="promise_data"><a href="http://www.fotolia.de">http://www.fotolia.de</a></p>
						<p class="promise_data"><a href="http://www.unsplash.com">http://www.unsplash.com</a></p>
						<p class="promise_data"><a href="http://www.pixabay.com">http://www.pixabay.com</a></p>
						<p class="promise_data"><a href="http://www.flaticon.com">http://www.flaticon.com</a></p>
						<h5 class="ch_tabdata_head imprint_hd mb-2 mt-4">Online-Streitbeilegung gemäß Art. 14 Abs. 1 <span>ODR-VO</span></h5>
						<p class="promise_data">Die Europäische Kommission stellt eine</p>
						<p class="promise_data">Plattform zur Online-Streitbeilegung (OS)</p>
						<p class="promise_data">bereit, die Sie unter <a href="http://ec.europa.eu/consumers/odr/">http://ec.europa.eu/consumers/odr/</a> finden.</p>
					</div>
				</div>
			</div>
		</div>
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
	</div>
	<!-- ================== BEGIN BASE JS ================== -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="{{ asset('frontend/js/vendor/jquery-3.3.1.min.js') }}"><\/script>')</script>
</body>
</html>