<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->

<head>
	<meta charset="utf-8" />
	<title>{{ config('app.name', '') }}{{isset($page_title)?' :: '.$page_title:''}}</title>
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

	<!-- ================== BEGIN DATATABLE CSS STYLE ================== -->
	<link href="{{ asset('backend/assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css')}}" rel="stylesheet" />
	<link href="{{ asset('backend/assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css')}}" rel="stylesheet" />
	<!-- ================== END DATATABLE CSS STYLE ================== -->

	<!-- ================== BEGIN TOASTR NOTIFICATION STYLE ================== -->
	{{-- <link href="{{ asset('backend/assets/plugins/toastr/css/toastr.min.css') }}" rel="stylesheet" /> --}}
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css" integrity="sha256-R91pD48xW+oHbpJYGn5xR0Q7tMhH4xOrWn1QqMRINtA=" crossorigin="anonymous" />
	<!-- ================== END TOASTR NOTIFICATION STYLE ================== -->

	<!-- ================== BEGIN SELECT2 STYLE ================== -->
	<link href="{{ asset('backend/assets/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
	<!-- ================== END SELECT2 STYLE ================== -->

	<!-- ================== BEGIN CUSTOM CSS ================== -->
	<link href="{{ asset('backend/css/style.css')}}" rel="stylesheet"></link>
	<!-- ================== END CUSTOM CSS ================== -->

	<!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
	@yield('styles')
	<!-- ================== END PAGE LEVEL STYLE ================== -->

	<!-- ================== BEGIN BASE JS ================== -->
	<script src="{{ asset('backend/assets/plugins/pace/pace.min.js') }}"></script>
	<!-- ================== END BASE JS ================== -->

</head>
<body>

	@if(Request::is('admin*'))
		@php
			$guard = 'admin';
			$user  = Auth::guard('admin')->user();
		@endphp
	@elseif(Request::is('user*'))
		@php
			$guard = 'user';
			$user = Auth::guard('user')->user();
		@endphp
	@endif

	<!-- begin #page-loader -->
	<div id="page-loader" class="fade show"><span class="spinner"></span></div>
	<!-- end #page-loader -->
	
	<!-- begin #page-container -->
	<div id="page-container" class="page-container fade page-sidebar-fixed page-header-fixed">
		<!-- begin #header -->
		@include('layouts.section.header')
		<!-- end #header -->
		
		<!-- begin #sidebar -->
		@include('layouts.section.'.$guard.'_sidebar')
		<!-- end #sidebar -->
		
		<!-- begin #content -->
		<div id="content" class="content">
			<!-- begin page-header -->
			<h1 class="page-header">{{isset($page_title)?$page_title:''}} <small>{{isset($page_subtitle)?$page_subtitle:''}}</small></h1>
			<!-- end page-header -->
			@yield('content')
		</div>
		<!-- end #content -->
		
		<!-- begin scroll to top btn -->
		<a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
		<!-- end scroll to top btn -->
	</div>
	<!-- end page container -->
	
	<!-- ================== BEGIN BASE JS ================== -->
	<script src="{{ asset('backend/assets/plugins/jquery/jquery-3.3.1.min.js') }}"></script>
	<script src="{{ asset('backend/assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
	<script src="{{ asset('frontend/js/datepicker-de.js') }}"></script>
	<script src="{{ asset('backend/assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
	<!--[if lt IE 9]>
		<script src="{{ asset('backend/assets/crossbrowserjs/html5shiv.js') }}"></script>
		<script src="{{ asset('backend/assets/crossbrowserjs/respond.min.js') }}"></script>
		<script src="{{ asset('backend/assets/crossbrowserjs/excanvas.min.js') }}"></script>
	<![endif]-->
	<script src="{{ asset('backend/assets/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
	<script src="{{ asset('backend/assets/plugins/js-cookie/js.cookie.js') }}"></script>
	<script src="{{ asset('backend/assets/plugins/moment/moment.min.js') }}"></script>
	<script src="{{ asset('backend/assets/js/theme/default.min.js') }}"></script>
	<script src="{{ asset('backend/assets/js/apps.min.js') }}"></script>
	<!-- ================== END BASE JS ================== -->
	
	<!-- ================== BEGIN DATATABLE JS ================== -->
	<script src="{{ asset('backend/assets/plugins/DataTables/media/js/jquery.dataTables.js')}}"></script>
	<script src="{{ asset('backend/assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js')}}"></script>
	<script src="{{ asset('backend/assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js')}}"></script>
	<!-- ================== END DATATABLE JS ================== -->

	<!-- ================== BEGIN TOASTR NOTIFICATION JS ================== -->
	{{-- <script src="{{ asset('backend/assets/plugins/toastr/js/toastr.min.js') }}"></script> --}}
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js" integrity="sha256-yNbKY1y6h2rbVcQtf0b8lq4a+xpktyFc3pSYoGAY1qQ=" crossorigin="anonymous"></script>
	<!-- ================== END TOASTR NOTIFICATION JS ================== -->
	
	<!-- ================== BEGIN SELECT2 JS ================== -->
	<script src="{{ asset('backend/assets/plugins/select2/dist/js/select2.min.js') }}"></script>
	<!-- ================== END SELECT2 JS ================== -->

	<!-- ================== BEGIN JQUERY VALIDATOR JS ================== -->
	<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.0/dist/jquery.validate.js"></script>
	<!-- ================== END JQUERY VALIDATOR JS ================== -->
	
	<script>
		$(document).ready(function() {
			// Inititalize theme js
			App.init({
				disableDraggablePanel: true
			});
			
		});
	</script>

	<script>
	  var baseUrl = '{{ url("/".$guard) }}';
	  var publicUrl = '{{ asset("") }}';
	  var uploadUrl = '{{ url("/public/uploads") }}';
	  var s3Url     = '{{ Storage::disk('s3')->url('/') }}';
	  s3Url         = s3Url.slice(0, -1);
	</script>

	<!-- ================== BEGIN CUSTOM JS ================== -->
	<script src="{{ asset('backend/js/common.js')}}"></script>
	<!-- ================== END CUSTOM JS ================== -->
	
	<!-- ================== BEGIN PAGE LEVEL JS ================== -->
	@yield('scripts')
	<!-- ================== END PAGE LEVEL JS ================== -->
	
</body>
</html>
