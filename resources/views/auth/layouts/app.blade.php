<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->

<head>
    <meta charset="utf-8" />
    <title>{{ config('app.name', '') }} {{isset($page_title)?$page_title:''}}</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    
    <!-- ================== BEGIN BASE CSS STYLE ================== -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="{{ asset('backend/assets/plugins/jquery-ui/jquery-ui.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('backend/assets/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('backend/assets/plugins/font-awesome/css/all.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('backend/assets/plugins/animate/animate.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('backend/assets/css/default/style.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('backend/assets/css/default/style-responsive.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('backend/assets/css/default/theme/default.css')}}" rel="stylesheet" id="theme" />
    <!-- ================== END BASE CSS STYLE ================== -->
    
    <!-- ================== BEGIN BASE JS ================== -->
    <script src="{{ asset('backend/assets/plugins/pace/pace.min.js')}}"></script>
    <!-- ================== END BASE JS ================== -->

    <!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
    @yield('styles')
    <!-- ================== END PAGE LEVEL STYLE ================== -->
</head>
<body class="pace-top bg-white">
    <!-- begin #page-loader -->
    <div id="page-loader" class="fade show"><span class="spinner"></span></div>
    <!-- end #page-loader -->
    
    <!-- begin #page-container -->
    <div id="page-container" class="fade">
        <!-- ================== PAGE CONTENT START ================== -->
            @yield('content')
        <!-- ================== PAGE CONTENT END ================== -->
    </div>
    <!-- end page container -->

    <!-- ================== BEGIN BASE JS ================== -->
    <script src="{{ asset('backend/assets/plugins/jquery/jquery-3.3.1.min.js')}}"></script>
    <script src="{{ asset('backend/assets/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
    <script src="{{ asset('backend/assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!--[if lt IE 9]>
        <script src="{{ asset('backend/assets/crossbrowserjs/html5shiv.js')}}"></script>
        <script src="{{ asset('backend/assets/crossbrowserjs/respond.min.js')}}"></script>
        <script src="{{ asset('backend/assets/crossbrowserjs/excanvas.min.js')}}"></script>
    <![endif]-->
    <script src="{{ asset('backend/assets/plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>
    <script src="{{ asset('backend/assets/plugins/js-cookie/js.cookie.js')}}"></script>
    <script src="{{ asset('backend/assets/js/theme/default.min.js')}}"></script>
    <script src="{{ asset('backend/assets/js/apps.min.js')}}"></script>
    <!-- ================== END BASE JS ================== -->

    <script>
        $(document).ready(function() {
            App.init();
        });
    </script>
    <script>
        var baseUrl = '{{ url("/") }}';
        var publicUrl = '{{ asset("") }}';
        var uploadUrl = '{{ url("/public/uploads") }}';
    </script>

    <!-- ================== BEGIN PAGE LEVEL JS ================== -->
      @yield('scripts')
    <!-- ================== END PAGE LEVEL JS ================== -->

    
</body>
</html>