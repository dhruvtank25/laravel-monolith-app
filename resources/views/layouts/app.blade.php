<!doctype html>
<html class="no-js" lang="de">

<head>
  <meta charset="utf-8">
  <title>{{ config('app.name', '') }}{{isset($page_title)?' :: '.$page_title:''}}</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0,user-scalable=0"">
  <meta name="theme-color" content="#fafafa">

  <!-- ================== CSRF TOKEN ====================== -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Google Analytics -->
  <script>
  var gaProperty = 'UA-154679103-1';
  var disableStr = 'ga-disable-' + gaProperty;
  // checking for opt-out cookie on document and applying to window if set
  if (document.cookie.indexOf(disableStr + '=true') > -1) {
    window[disableStr] = true;
  }
  // user is opting out, setting cookie on document, window and alerting success
  function gaOptout() {
    document.cookie = disableStr + '=true; expires=Thu, 31 Dec 2099 23:59:59 UTC; path=/';
    window[disableStr] = true;
    toastr.success('Google Analytics Tracking is now deactivated!');
    //alert('Google Analytics Tracking is now deactivated!');
  }

  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');


  ga('create', gaProperty, 'auto');
  ga('set', 'anonymizeIp', true);
  ga('send', 'pageview');
  </script>
  <!-- End Google Analytics -->

  <!-- ================== BEGIN BASE CSS STYLE ================== -->
  <link rel="stylesheet" href="{{ asset('frontend/css/normalize.css') }}">
  <link rel="stylesheet" href="{{ asset('frontend/css/font-awesome.min.css') }}">   
  <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}">
  <!-- ================== END BASE CSS STYLE ================== -->

  <!-- ================== BEGIN TOASTR NOTIFICATION STYLE ================== -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css" integrity="sha256-R91pD48xW+oHbpJYGn5xR0Q7tMhH4xOrWn1QqMRINtA=" crossorigin="anonymous" />
  <!-- ================== END TOASTR NOTIFICATION STYLE ================== -->
    
  <!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
  @yield('style_link')
  <!-- ================== END PAGE LEVEL STYLE ================== -->

  <!-- ================== BEGIN CUSTOM CSS ================== -->
  {{-- <link rel="stylesheet" href="{{ asset('css/app.css') }}"> --}}
  <link rel="stylesheet" href="{{ asset('frontend/css/jquery.rateyo.min.css') }}">
  <link rel="stylesheet" href="{{ asset('frontend/css/main.css') }}">
  <!-- ================== END CUSTOM CSS ================== -->

  <!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
  @yield('styles')
  <!-- ================== END PAGE LEVEL STYLE ================== -->

</head>

<body>
  <div class="page-loader">
    <img src="{{ asset('frontend/img/icons/loader.svg') }}" alt="">
  </div>
	<div id="app">
		<!-- ================== HEADER START ================== -->
	  <header class="container-fluid page_header has_cookie">
      @include('cookieConsent::index')
			<div class="container">
				<div class="row">
					<div class="logo_wrap col-md-auto">
						<a href="{{ route('home') }}"><img src="{{ asset('frontend/img/logo.png') }}" alt="x"/></a>
					</div>
					<div class="mobile-nav-icon">
						<i class="fa fa-bars" aria-hidden="true"></i>
					</div>
					<nav class="col">
						<ul>
							<li class="header_links">
                  <a id="category_main_menu" href="{{route('home').'#dein-anliegen'}}">Dein Anliegen <i class="fa fa-angle-down res_navdwn_arrow"></i></a>
                  <ul class="nav_dropdown">
                      @foreach ($categories as $category)
                        <li><a href="{{ route('coach-search',['url_slug' => $category->url_slug]) }}">{{$category->title}}</a></li>
                      @endforeach
                  </ul>
              </li>
							<li><a href="{{ route('coach-search',['url_slug' => 'alle-berater']) }}">Alle Berater</a></li>
							<li><a href="{{ route('about-us') }}">Über uns</a></li>
							<li class="becoach"><a href="{{ route('become-a-coach') }}">Berater werden</a></li>
              @if(Auth::guard('admin')->check())
                  @php
                      $guard = 'admin';
                      $user  = Auth::guard('admin')->user();
                  @endphp
              @elseif(Auth::guard('coach')->check())
                  @php
                      $guard = 'coach';
                      $user  = Auth::guard('coach')->user();
                  @endphp
              @elseif(Auth::guard('user')->check())
                  @php
                      $guard = 'user';
                      $user  = Auth::guard('user')->user();
                  @endphp
               @elseif(Auth::guard('guest_user')->check())
                  @php
                      $guard = 'guest_user';
                      $user  = Auth::guard('guest_user')->user();
                  @endphp
              @endif
              @if(isset($user))
                  <li class="nav_login float-right">
                      <a href="javascript:void()"><i class="fa fa-user" aria-hidden="true"></i> {{$user->first_name}}</a>
                      <ul class="login_dropdown">
                          @if($guard!='guest_user')
                            <li><a href="{{ route($guard) }}">Übersicht</a></li>
                          @endif
                          @if($guard!='admin')
                            <li><a href="{{ route($guard.'.bookings') }}">Meine Termine</a></li>
                          @endif
                          <li><a href="{{route('logout')}}">Logout</a></li>
                      </ul>
                  </li>
              @else
							  <li class="nav_login float-right"><a href="{{ route('login') }}"><i class="fa fa-user" aria-hidden="true"></i> Login</a></li>
              @endif
						</ul>
					</nav>
				</div>
			</div>
		</header>
		<!-- ================== HEADER END ================== -->

    <!-- =============== COACH DASHBOARD MENU START ================ -->
    @if(isset($user) && $guard=='coach' && (Request::is('coach/*') || Request::is('coach') || Request::is('berater/*') || Request::is('berater')))
      <div class="coach_dash_wrapper">
        <!-- coach profile section -->
        <div class="container-fluid coach_profile_wrapper">
          <div class="container">
            <div class="row">
              <div class="col-md-12 mb-5 profile_details">
                <img src="{{ FileUploadHelper::getDocPath($user->avatar, 'avatar') }}" alt="x"/>
                <div class="coach_profile_name">
                  <h2>{{ $user->first_name.' '.$user->last_name}}</h2>
                  @php
                    $coach_companies = $user->companies;
                  @endphp
                  @if($user->status=='active')
                    <h5>{{ $coach_companies[0]->pivot->designation }}</h5>
                    <span class="badge badge-pill bg-info text-white">Aktiv</span>
                  @elseif($user->status=='inactive')
                    <span class="badge badge-pill bg_site_red">Inaktiv</span>
                  @elseif($user->status=='incomplete')
                    <span class="badge badge-pill bg_site_yellow">Unvollständig</span>
                  @else
                    {{-- <span class="badge badge-pill bg_site_yellow">{{$user->status}}</span> --}}
                    <span class="badge badge-pill bg_site_yellow">Profil in Prüfung</span>
                  @endif
                </div>
              </div>
              <div class="col-md-12 mb-5 pb-4 coach_dash_tabs">
                <ul class="row">
                  @php
                    $routeName = Route::currentRouteName();
                  @endphp
                  <li class="col-md-2 {{ ($routeName=='coach' || Request::is('coach/dashboard') || Request::is('coach')) ? 'coach_tab_active' : '' }}">
                    <a href="{{ route('coach') }}">Übersicht</a>
                  </li>
                  <li class="col-md-3 {{ $routeName=='coach.bookings' || Request::is('coach/bookings*') ? 'coach_tab_active' : '' }}">
                    <a href="{{ route('coach.bookings') }}">Meine Termine {{-- <sup>2</sup> --}}</a>
                  </li>
                  <li class="col-md-3 {{ $routeName=='coach.availabilites' || Request::is('coach/availabilites*') ? 'coach_tab_active' : '' }}">
                    <a href="{{ route('coach.availabilites') }}">Verfügbarkeit</a>
                  </li>
                  <li class="col-md-2 {{ $routeName=='coach.my-profile' || Request::is('coach/my-profile*') ? 'coach_tab_active' : '' }}">
                    <a href="{{ route('coach.my-profile') }}">Profil</a>
                  </li>
                  {{-- <li class="col-md-2 {{ $routeName=='coach.blog' || Request::is('coach/blog*') ? 'coach_tab_active' : '' }}">
                    <a href="{{ route('coach.blog') }}">Blogeinträge</a>
                  </li> --}}
                  <li class="col-md-2 {{ $routeName=='coach.messages' || Request::is('coach/messages*') ? 'coach_tab_active' : '' }}">
                    <a href="{{ route('coach.messages') }}">Mitteilungen<sup class="unseen_thread_count">0</sup></a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <!-- coach profile section end -->
    @endif
    <!-- =============== COACH DASHBOARD MENU END ================ -->
  		
      <!-- ================== PAGE CONTENT START ================== -->
  			@yield('content')
  		<!-- ================== PAGE CONTENT END ================== -->

   <!-- ============ COACH DASHBOARD CONTAINER DIV ============= -->
		@if(isset($user) && $guard=='coach')
      </div>
    @endif
   <!-- =============== COACH DASHBOARD END ================ -->

		<!-- ================== FOOTER START ================== -->
		<footer class="container-fluid">
			<div class="container">
				<div class="row foot_nav_container">
					<div class="col-md-4 foot_nav">
						<h5>Dein Anliegen</h5>
						<ul>
              @foreach ($categories as $category)
                <li><a href="{{ route('coach-search', ['url_slug' => $category->url_slug]) }}">{{$category->title}}</a></li>
              @endforeach
						</ul>
					</div>
					<div class="col-md-4 foot_nav">
						<h5>Mehr erfahren</h5>
						<ul>
							<li><a href="{{ route('become-a-coach') }}">Berater werden</a></li>
							<li><a href="{{ route('organisation') }}">Für Organisationen</a></li>
							<li><a href="{{ route('about-us') }}">Über uns</a></li>
							{{-- <li><a href="javascript:void()">Blog</a></li> --}}
              <li><a href="{{ route('faq') }}">FAQ</a></li>
						</ul>
					</div>
					<div class="col-md-4 foot_nav">
						<h5>Newsletter-Anmeldung</h5>
						<div class="foot_login">
              <div class="form-group">
                <form action="{{route('newsletter')}}">
                  <input type="text" name="newsletter_email" class="form-control" value="{{isset($user)?$user->email:''}}" required>
                  <button type="submit" class="btn ft_btn_login">Anmelden</button>
                </form>
              </div>
							{{-- <div class="form-group">
								<input type="text" name="nl2go_email" class="form-control">
								<button type="button" class="btn ft_btn_login" id="newsletterClick">Anmelden</button>
							</div> --}}
						</div>
						<div class="foot_social">
							<ul>
								<li><a href="mailto:kontakt@himmlischberaten.de"><i class="fa fa-envelope" aria-hidden="true"></i></a></li>
								<li><a target="_blank" href="https://www.facebook.com/Himmlischberatende-100609611472626"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
								<li><a target="_blank" href="https://www.instagram.com/himmlischberaten_de/"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
								{{-- <li><a href="javascript:void()"><i class="fa fa-linkedin-square" aria-hidden="true"></i></a></li> --}}
							</ul>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3 copyright">
						<a href="{{ route('home') }}"><img src="{{ asset('frontend/img/foot_logo.png') }}" alt="x" /></a>
					</div>
					<div class="col-md-6 copyright">
						<ul>
							<li><a href="{{ route('imprint') }}">Impressum</a></li>
							<li><a href="{{ route('data-protection') }}">Datenschutz</a></li>
              <li><a href="{{ route('agb') }}">AGB</a></li>
							<li><a href="{{ route('contact-us') }}">Kontakt</a></li>
						</ul>
					</div>
					<div class="col-md-3 copyright">
						<p>© {{date('Y')}} lifepresso GmbH</p>
					</div>
				</div>
			</div>
		</footer>
		<!-- ================== FOOTER END ================== -->
	</div>
  
  <!-- ================== BEGIN BASE JS ================== -->
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script>window.jQuery || document.write('<script src="{{ asset('frontend/js/vendor/jquery-3.3.1.min.js') }}"><\/script>')</script>
  <script src="{{ asset('frontend/js/vendor/modernizr-3.7.1.min.js') }}"></script>
  {{-- <script src="{{ asset('frontend/js/plugins.js') }}"></script> --}}
  <script src="{{ asset('frontend/js/jquery.rateyo.min.js') }}"></script>
  <script src="{{ asset('frontend/js/bootstrap.min.js') }}"></script>
  {{-- <script src="{{ asset('js/app.js') }}"></script> --}}
  <!-- ================== END BASE JS ================== -->

  <!-- ================== BEGIN JQUERY VALIDATOR JS ================== -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js" crossorigin="anonymous"></script>
   <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/localization/messages_de.js"></script>
  <!-- ================== END JQUERY VALIDATOR JS ================== -->

  <!-- ================== BEGIN BOOTBOXJS =================================== -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.js" integrity="sha256-vHsV98JlYVo7h9eo1BQrqWgGQDt6prGrUbKAlHfP+0Y=" crossorigin="anonymous"></script>
  <!-- ================== END BOOTBOXJS =================================== -->

  <!-- ================== BEGIN TOASTR NOTIFICATION JS ================== -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js" integrity="sha256-yNbKY1y6h2rbVcQtf0b8lq4a+xpktyFc3pSYoGAY1qQ=" crossorigin="anonymous"></script>
  <!-- ================== END TOASTR NOTIFICATION JS ================== -->
  
  <!-- ================== BEGIN MOMENT JS ================== -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js" integrity="sha256-4iQZ6BVL4qNKlQ27TExEhBN1HFPvAvAMbFavKKosSWQ=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.27/moment-timezone-with-data.min.js" integrity="sha256-ltodGpbck6Njvgsa9pG7Xnr0xkVF57ctgfAS+LOeZt4=" crossorigin="anonymous"></script>
  <!-- ================== END MOMENT JS ================== -->
  <script>
    @if(Request::is('coach') || Request::is('coach/*') || Request::is('berater') || Request::is('berater/*'))
      var baseUrl = '{{ url("/coach") }}';
    @elseif(Request::is('user') || Request::is('user/*') || Request::is('nutzer') || Request::is('nutzer/*'))
      var baseUrl = '{{ url("/user") }}';
    @elseif(Request::is('guest_user') || Request::is('guest_user/*') || Request::is('gast_nutzer') || Request::is('gast_nutzer/*')) 
      var baseUrl = '{{ url("/guest_user") }}';
    @else
      var baseUrl = '{{ url("/") }}';
    @endif
    var publicUrl = '{{ asset("") }}';
    var uploadUrl = '{{ url("/public/uploads") }}';
    
    $(document).ready(function() {

        $('.mobile-nav-icon i').click(function(){
            $('nav.col').slideToggle();
        });
        if($(window).width()<992) {
          $('.header_links').click(function(){
            $(this).find(".nav_dropdown").slideToggle();
          });
        }

        // Fix for IE for Object fit css not working
        
        var userAgent, ieReg, ie;
        userAgent = window.navigator.userAgent;
        ieReg = /msie|Trident.*rv[ :]*11\./gi;
        ie = ieReg.test(userAgent);

        if(ie) {
          $(".object-fit-ie").each(function () {
            var $container = $(this),
                imgUrl = $container.find("img").prop("src");
            if (imgUrl) {
              $container.css("backgroundImage", 'url(' + imgUrl + ')').addClass("custom-object-fit");
            }
          });

          $(".circle-object-fit-ie").each(function () {
            var $container = $(this),
                imgUrl = $container.find("img").prop("src");
            if (imgUrl) {
              $container.css("backgroundImage", 'url(' + imgUrl + ')').addClass("circle-object-fit");
            }
          });

          $(".search-circle-object-fit-ie").each(function () {
            var $container = $(this),
                imgUrl = $container.find("img").prop("src");
            if (imgUrl) {
              $container.css("backgroundImage", 'url(' + imgUrl + ')').addClass("search-circle-object-fit");
            }
          });

        }

        // jQuery Validator Custom to check password min 8 digit with 1 capital letter and 1 number
        $.validator.addMethod("pwcheck", function(value, element, param) {
            if (this.optional(element)) {
                return true;
            } 
            else {
                return /^[A-Za-z0-9\d=!\-#@._*]*$/.test(value) // consists of only these
                    && /[A-Z]/.test(value) // has a lowercase letter
                    && /\d/.test(value) // has a digit
            }
        });

        // jQuery Validator Custom to check ther is no space
        $.validator.addMethod("nowhitespace", function(value, element) {
          return this.optional(element) || !$.trim(value)=="";
        }, "No white space please");

        jQuery.validator.addMethod("alphanumeric", function(value, element) {
          return this.optional(element) || /^[a-zA-Z0-9]+$/.test(value);
        }, "Only Numbers and letters are allowed without space or special characters");

        jQuery.validator.addMethod("birthdate", function(value, element) {
          return this.optional(element) || /^\d{4}-\d{2}-\d{2}$/.test(value);
        }, "Invalid Birthdate");

        @if(isset($user) && $guard=='coach' && (Request::is('coach/*') || Request::is('coach') || Request::is('berater/*') || Request::is('berater')))
          // Get total unread threads
          $.ajax({
              url: baseUrl+'/messages/unread-thread',
          })
          .done(function(data) {
              console.log("success");
              $(".unseen_thread_count").text(data);
          })
          .fail(function(error) {
              console.log("error while fetching unread messages count");
              console.log(error);
          })
          .always(function() {
              console.log("complete");
          });
        @endif

    });

    // Set Toastr to be full width by default
    toastr.options = {
        "closeButton": true,
        "positionClass": "toast-top-full-width",
        "timeOut": "0",
        "extendedTimeOut": "0",
        //"progressBar": true,
        "toastClass": "toastr", // to fix bootstrap 4.2 full width issue; 
    };

    function showLoader () {
      $(".page-loader").css('display', 'block');
      $("body").css('overflow-y', 'hidden');
    }

    function hideLoader () {
      $(".page-loader").css('display', 'none');
      $("body").css('overflow-y', 'scroll'); 
    }

    function ajaxValidationError (json_response) {
      var error_html   = '';
      var required_err = false;
      $.each(json_response.errors,function(key, data) {
          $.each(data, function (index, data) {
            if(data.includes('field is required') ) {
                if(required_err==false) {
                    error_html   = 'Please fill in all required fields to continue. <br>'+error_html;
                    required_err = true;
                }
            } else {
                error_html+=data+'<br/>';
            }
          });
      });
      toastr.error(error_html);
    }
    $(window).scroll(function(){
        /*if($(window).scrollTop()>0)
          $('header').addClass('header_fixed');
        else
          $('header').removeClass('header_fixed');*/

        var screenht = $( window ).height();
        if ($(window).scrollTop() > 50) {
            $('header').addClass('header_fixed visible-nav');
            $('header').removeClass('not-visible-nav');
        }
        else {
            $('header').removeClass('header_fixed visible-nav');
            $('header').addClass('not-visible-nav');
        }
        if ($(window).scrollTop() < 45)
            $('header').removeClass('not-visible-nav');
    });
  </script>

  <!-- ================== BEGIN COMMON JS ================== -->
  {{-- <script src="{{ asset('frontend/js/main.js') }}"></script> --}}
  <!-- ================== END COMMON JS ================== -->

  <!-- ================== BEGIN PAGE LEVEL JS ================== -->
  @yield('scripts')
  <!-- ================== END PAGE LEVEL JS ================== -->

  <!-- BEGIN newsletter2go -->
  {{-- <script id="n2g_script">!function(e,t,n,c,r,a,i){e.Newsletter2GoTrackingObject=r,e[r]=e[r]||function(){(e[r].q=e[r].q||[]).push(arguments)},e[r].l=1*new Date,a=t.createElement(n),i=t.getElementsByTagName(n)[0],a.async=1,a.src=c,i.parentNode.insertBefore(a,i)}(window,document,"script","https://static.newsletter2go.com/utils.js","n2g");
    n2g('create', '8a29sbmr-uw066wxv-zz6');
    $(function () {
      $("#newsletterClick").on('click', function(event) {
        event.preventDefault();
        var recipient = {email: $('input[name=nl2go_email').val()};
        // Daten an Newsletter2Go senden
        n2g(
          'subscribe:send', {
            recipient: recipient
          },
          function (data) {
            if(data.status == 201) {
              toastr.success('Anmeldung erfolgreich!');
              $('input[name=nl2go_email').val('');
            } else if (data.status==200) {
              if(data.values[0].result.error.recipients.invalid.length) {
                 toastr.error('Ihre E-mail Addresse ist nicht valide.');
              }
              // Ausgabe der Statusmeldung anstelle des Formulars
              toastr.error('Du bist bereits angemeldet!');
            } else {
              toastr.error('Es ist ein Fehler aufgetreten!');
            }
          },
          function (data) {
            toastr.error('Es ist ein Fehler aufgetreten!');
          }
        );
      });
    });
  </script> --}}
  <!-- newsletter2go END -->

</body>

</html>
