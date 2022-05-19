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
    <div class="container-fluid comingsoon_container">
        <img class="soon_logo" src="{{asset('frontend/img/soon_logo.png')}}" alt="x"/>
        <img class="soon_back" src="{{asset('frontend/img/comingsoon_bck.png')}}" alt="x"/>
        <div class="soon_data">
            <h2>himmlischberaten.de <span>ist bald verfügbar!</span></h2>
        </div>
        <div class="footer_soon">
            <ul>
                <li><a href="{{route('impressum')}}">Impressum</a></li>
                <li><a href="#">© {{date('Y')}} lifepresso GmbH</a></li>
            </ul>
        </div>
    </div>

    <!-- ================== BEGIN BASE JS ================== -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="{{ asset('frontend/js/vendor/jquery-3.3.1.min.js') }}"><\/script>')</script>
</body>
</html>