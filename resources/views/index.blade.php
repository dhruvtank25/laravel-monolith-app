@extends('layouts.app')

@section('style_link')
  <link rel="stylesheet" href="{{ asset('frontend/css/owl.carousel.min.css') }}">
  <link rel="stylesheet" href="{{ asset('frontend/css/jquery.rateyo.min.css') }}">
@endsection

@section('content')
      {{-- <div class="container-fluid cookies_wrapper">
        <div class="container ">
            <div class="row">
                <div class="col-md-10">
                    <p>Vielen Dank fur deinen Besuch auf himmlischberaten.de Wir sind gerade mit unserem Angebot gestartet und bauen aktuell unseren Beraterpool auf. In den nachsten Tagen kannst du deine Beratung bei himmilischberaten.de buchen.</p>
                </div>
                <div class="col-md-2">
                    <button class="btn accept_cookies">Zustimmen</button>
                </div>
            </div>
        </div>
    </div> --}}
    <div class="container-fluid hm_banner">
        <!--<img class="img-fluid" src="{{ asset('frontend/img/bild_buhne.png') }}" alt="x"/>-->
		<div class="container">
            <div class="row">
				<div class="hm_banner_txt col-md-12">
					<h2>Deine Plattform für<span>christliche Lebensberatung</span></h2>
					<p>Hilfe auf steinigen Wegen</p>
				</div>
				<p class="hm_fnd_coach col-md-12 text-center"><a href="{{ route('coach-search',['url_slug' => 'alle-berater']) }}">Finde deinen Berater</a></p>
			</div>
		</div>
    </div>
    {{-- <div class="container-fluid visting_info_section">
        <div class="container ">
            <p>Vielen Dank für deinen Besuch auf himmlischberaten.de. Wir sind gerade mit unserem Angebot gestartet und bauen aktuell unseren Beraterpool auf. In den nächsten Tagen kannst du deine Beratung bei himmlischberaten.de buchen.</p>
        </div>
    </div> --}}
    <!-- life journey section -->
    <div class="container-fluid life_journey_container">
        <div class="container text-center">
            <div class="row">
                <h2 class="col-12 section_head">Wir beraten dich auf deinem Lebensweg <span>auf Basis christlicher Werte.</span></h2>
                <h3 class="col-12 advise_hd">In 3 Schritten zu deiner Beratung</h3>
            </div>
        </div>
    </div>
    <div class="container-fluid advice_boxes text-center">
        <img class="profile_line hm_overlap_line" src="{{asset('frontend/img/line_dot.png')}}" alt="x">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-4 advice">
                    <div class="advice_icon_wrapper circle-object-fit-ie">
                        <img class="object-fit-ie-img" src="{{ asset('frontend/img/icons/search_coach_icon.png') }}" alt="x" />
                    </div>
                    <h5>1. Finde deinen Berater</h5>
                    <p>Wähle dein Anliegen und suche dir deinen passenden Berater.</p>
                </div>
                <div class="col-sm-12 col-md-4 advice">
                    <div class="advice_icon_wrapper circle-object-fit-ie">
                        <img class="object-fit-ie-img" src="{{ asset('frontend/img/icons/appointment_coach_icon.png') }}" alt="x" />
                    </div>
                    <h5>2. Buche einen Termin</h5>
                    <p>Verfügbaren Termin auswählen – online oder offline, was besser zu dir passt.</p>
                </div>
                <div class="col-sm-12 col-md-4 advice">
                    <div class="advice_icon_wrapper circle-object-fit-ie">
                        <img class="object-fit-ie-img" src="{{ asset('frontend/img/icons/chat_coach_icon.png') }}" alt="x" />
                    </div>
                    <h5>3. Starte deine Beratung</h5>
                    <p>Räume deine Steine aus dem Weg - mit deinem persönlichen Experten.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- concern section -->
    <div id="category_section" class="container-fluid concern_container">
        <div class="container text-center">
            <div class="row">
                <h2 class="col-12 section_head">Wähle dein Anliegen</h2>
                @foreach ($categories as $category)
                    <div class="col-sm-12 col-md-6 concern_choice">
                        <div class="choice_wrapper">
                            <div class="home_cat_banner object-fit-ie"><img class="tp_img object-fit-ie-img" src="{{ FileUploadHelper::getDocPath($category->banner, 'cat_banner') }}" alt="x" /></div>
                            <div class="choice_data">
                                <div class="home_cat_icon">{!! $category->icon !!}</div>
                                <h6>{{$category->title}}</h6>
                            </div>
                            <div class="choice_overlay">
                                <div class="home_cat_icon_hover">{!! $category->icon !!}</div>
                                <h6>{{$category->title}}</h6>
                                <div class="home_cat_desc"> {!! $category->short_description !!}</div>
                                <p><a href="{{ route('coach-search', ['url_slug' => $category->url_slug]) }}">Berater auswählen</a></p>
                            </div>
                        </div>
                    </div>  
                @endforeach
            </div>
        </div>
    </div>

    <!-- top consultants section -->
    {{-- <div class="container-fluid hm_consultant_wrapper">
        <div class="container text-center">
            <div class="row justify-content-center">
                <h2 class="col-12 section_head">Unsere Top-Berater</h2>
                <div class="col-md-10 owl_wrap_hm">
                    <div class="owl-carousel owl-theme">
                        @foreach ($top_coaches as $coach)
                            <div class="carousal_data">
                                <a href="{{ route('coach-detail', ['name'=>$coach->first_name.'-'.$coach->last_name,'id'=>$coach->id]) }}">
                                    <div class="home_coach_profile">
                                        <img class="profile_img" src="{{ FileUploadHelper::getDocPath($coach->avatar, 'avatar') }}" alt="x" />
                                    </div>
                                    <h5>{{$coach->first_name.' '.$coach->last_name}}</h5>
                                    <div class="star_wrap rating_div" data-rateyo-rating="{{$coach->avg_rating?$coach->avg_rating:0}}">
                                    </div>
                                    <div class="reaction_icons">
                                        @php
                                            $category_id_arr = $coach->categories->pluck('id')->toArray();
                                        @endphp
                                        @foreach ($categories as $category)
                                           @php
                                                $active_class = '';
                                                if(in_array($category->id, $category_id_arr))
                                                    $active_class = 'active';
                                           @endphp
                                            <div class="react_icons_wrapper {{$active_class}}">
                                                {!! $category->icon !!}
                                                <div class="caption">
                                                    <div class="cat_icon_title">
                                                          {{$category->title}}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
                <p class="col-12 watch_coach"><a href="{{ route('coach-search',['url_slug' => 'alle-berater']) }}">Alle Berater ansehen</a></p>
            </div>
        </div>
    </div> --}}

    <!-- heavenly wrap -->
    <div class="container-fluid heavenly_wrapper">
        <div class="container text-center">
            <div class="row">
                <h2 class="col-12 section_head">Darum himmlischberaten.de</h2>
                <div class="col-sm-12 col-md-3 heaven_data">
                    <div class="heaven_icon_wrapper">
                        <img class="star_ratehm" src="{{ asset('frontend/img/icons/rating_icon.png') }}" alt="x" />
                    </div>
                    <h5>Echte <span>Experten</span></h5>
                    <p>Nur ausgebildete Berater werden zu himmlischberaten.de zugelassen, um deinem Anliegen professionell zu begegnen.</p>
                </div>
                <div class="col-sm-12 col-md-3 heaven_data">
                    <div class="heaven_icon_wrapper">
                        <img src="{{ asset('frontend/img/icons/fingerprint_icon.png') }}" alt="x" />
                    </div>
                    <h5>Individuelle <span>Hilfe</span></h5>
                    <p>Unsere Berater gehen individuell auf dich ein und erarbeiten mit dir zusammen eine maßgeschneiderte Lösung.</p>
                </div>
                <div class="col-sm-12 col-md-3 heaven_data">
                    <div class="heaven_icon_wrapper">
                        <img src="{{ asset('frontend/img/icons/mask_icon.png') }}" alt="x" />
                    </div>
                    <h5>Auf Wunsch <span>anonym</span></h5>
                    <p>Du entscheidest, ob du gegenüber deinem Berater anonym bleiben möchtest oder nicht.</p>
                </div>
                <div class="col-sm-12 col-md-3 heaven_data">
                    <div class="heaven_icon_wrapper">
                        <img src="{{ asset('frontend/img/icons/shield_icon.png') }}" alt="x" />
                    </div>
                    <h5>Sichere <span>Abwicklung</span></h5>
                    <p>Deine persönlichen Daten werden nach aktuellen Sicherheitsstandards gespeichert. Gesprächsdaten werden nicht erfasst.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- customer say section -->
    {{-- <div class="container-fluid hm_customer_wrapper">
        <div class="container text-center">
            <div class="row justify-content-center">
                <h2 class="col-12 section_head">Das sagen unsere Kunden</h2>
                <div class="col-md-10 col-lg-6 owl_testi_hm">
                    <div class="owl-carousel">
                        <div class="carousal_data">
                            <p>"Das Coaching auf himmlischberaten.de hat mir geholfen, mich mit meiner Vergangenheit zu versöhnen.“</p>
                            <h4>Maximilian M.</h4>
                        </div>
                        <div class="carousal_data">
                            <p>"Das Coaching auf himmlischberaten.de hat mir geholfen, mich mit meiner Vergangenheit zu versöhnen.“</p>
                            <h4>Joe H.</h4>
                        </div>
                        <div class="carousal_data">
                            <p>"Das Coaching auf himmlischberaten.de hat mir geholfen, mich mit meiner Vergangenheit zu versöhnen.“</p>
                            <h4>Maxi M.</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Blog -->
    {{-- <div class="lastestblogs_wrapper">
        <div class="container">
            <div class="row">
                <h2 class="col-12 text-center section_head">Aktuelle Blogeinträge</h2>
                <div class="col-md-6 blogsdata">
                    <div class="blog_header">
                        <div class="blog_profile_img">
                            <img src="{{asset('frontend/img/talin.png') }}">
                        </div>
                        <div class="blog_title">
                            <p>Klaus Kleber</p>
                            <div class="date"><i class="fa fa-clock-o" aria-hidden="true"></i><p>24. Mai 2019</p></div>
                        </div>
                    </div>
                    <div class="blog_body">
                        <div class="blog_image">
                            <img src="{{asset('frontend/img/blog-image.jpg') }}"  width="100%"> 
                        </div>
                        <h3 class="blog_heading">Konflikte in der Familie richtig lösen.</h3>
                        <div class="blog_shortdesc">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna […]</div>
                    </div>
                </div>
                <div class="col-md-6 blogsdata">
                    <div class="blog_header">
                        <div class="blog_profile_img">
                            <img src="{{asset('frontend/img/talin.png') }}">
                        </div>
                        <div class="blog_title">
                            <p>Klaus Kleber</p>
                            <div class="date"><i class="fa fa-clock-o" aria-hidden="true"></i><p>24. Mai 2019</p></div>
                        </div>
                    </div>
                    <div class="blog_body">
                        <div class="blog_image">
                            <img src="{{asset('frontend/img/blog-image.jpg') }}"  width="100%"> 
                        </div>
                        <h3 class="blog_heading">Konflikte in der Familie richtig lösen.</h3>
                        <div class="blog_shortdesc">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna […]</div>
                    </div>
                </div>
                <p class="col-md-12 text-center mt-5"><a href="#" class="orange_background_btn">Mehr anzeigen</a></p>
            </div>
        </div>
    </div> --}}

    <!-- life counseling -->
    <div class="container-fluid life_counsel_wrapper">
        <div class="container text-center">
            <div class="row justify-content-center">
                <div class="col-sm-12 col-md-8">
                    <h4>Mit himmlischberaten.de schaffen wir einen einfachen und schnellen Zugang zu professioneller Lebensberatung auf christlicher Basis. Niemand, der Hilfe sucht, bleibt mit seinen Herausforderungen alleine.</h4>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
  <script src="{{ asset('frontend/js/owl.carousel.min.js') }}"></script>
  <script src="{{ asset('frontend/js/jquery.rateyo.min.js') }}"></script>
  <script>
      $(document).ready(function() {

        $(document).ready(function() {
            $(".rating_div").rateYo({
                //starWidth: "24px",
                halfStar: true,
                readOnly: true,
                //precision: 0.5
            });
        });

        function goToCategorySection () {
            if($(window).width()>992) {
                var header_size = $(".header_fixed").length>0?$(".header_fixed").outerHeight():220;
                $('html, body').animate({
                   scrollTop: $("#category_section").offset().top-(header_size+30)
                }, 1500);
                // Fix Dropdown stops on hover
                //$(".page_header").find('.nav_dropdown').removeAttr('style');
            }
        }

        if(window.location.hash=='#dein-anliegen') {
            goToCategorySection();
        }

        // Category menu click
        $("#category_main_menu").click(function() {
            goToCategorySection();
        });

          $(".owl_wrap_hm .owl-carousel").owlCarousel({
              //loop:true,
              nav:true,
              dots:false,
              margin:30,
              autoplay:false,
              autoplayTimeout:6000,
              navText: [
                  '<i class="fa fa-angle-left" aria-hidden="true"></i>',
                  '<i class="fa fa-angle-right" aria-hidden="true"></i>'
              ],
              responsive:{
                  0:{
                      items:1
                  },
                  560:{
                      items:2
                  },
                  992:{
                      items:4
                  }
              }
          });

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