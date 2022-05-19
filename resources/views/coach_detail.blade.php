@extends('layouts.app')

@section('style_link')
	<link rel="stylesheet" href="{{ asset('frontend/css/owl.carousel.min.css') }}">
	<link rel="stylesheet" href="{{ asset('frontend/css/jquery-ui.min.css') }}">
	<link rel="stylesheet" href="{{ asset('frontend/css/availability.css') }}">
	<link rel="stylesheet" href="{{ asset('frontend/css/select2.min.css') }}">
	<link rel="stylesheet" href="{{ asset('frontend/css/jquery.rateyo.min.css') }}">
@endsection

@section('content')
    @csrf
	<!-- Request Availability Modal -->
	<div class="modal fade coach_msg_modal user_booking_pop" id="coach_booking_modal" tabindex="-1" role="dialog" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	        <div class="modal-header">
	            <h5 class="modal-title mb-3" id="exampleModalLongTitle">Termin vorschlagen</h5>

	        </div>
	        <div class="modal-body coach_msg_body">
				<div class="row availContainer m-0">
	                <input type="hidden" class="request_id">
					<p class="col-md-12">Bitte wähle drei Termine aus, die du dem Berater als Vorschlag senden möchtest.</p>
					<div class="col-md-12 mb-5 mt-4 period_date_wrap">
						<div class="datepicker" id="unavailDatepicker"></div>
					</div>
	                <div class="col-md-12 avail_request_container">
	                    <div class="row avail_request avail_timeslots unavail_active">
	        				<h5 class="col-md-7 ch_tabdata_head mb-3">Terminvorschlag 1</h5>
	                        <a href="javascript:void(0);" class="col-md-5 choosetimeslots">bearbeiten</a>
	                        <div class="unavail_inactive_container col-md-6" style="display:none;">
	                            <div class="row">
	                                <div class="col-md-6">
	                                    <p class="inactivedata unavail_hdate">No date selected</p>
	                                </div>
	                                <div class="col-md-6">
	                                    <p class="inactivedata unavail_htime">No time selected</p>
	                                </div>
	                                <div class="col-md-6">
	                                    <p class="inactivedata unavail_hduratoin">No duration selected</p>
	                                </div>
	                                <div class="col-md-6">
	                                    <p class="inactivedata unavail_hstatus">No status selected</p>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="col-md-12 unavail_data_container">
	                            <div class="row">
	                				<div class="col-md-6 book_pop_avail mb-4">
	                					<h6 class="mb-2">Ausgewählter Termin</h6>
	                					<p class="blk_rnd_select select_date active">10. Mai 2019</p>
	                				</div>
	                				<div class="col-md-6 book_pop_avail mb-4">
	                					<h6 class="mb-2">Startzeit auswählen</h6>
	                					<div class="mb-3 mb-md-0 reg_single_select book_pop_select">
	                						<select class="select_time">
	                						</select>
	                					</div>
	                				</div>
	                				<div class="col-md-12 book_pop_avail mb-4 avail_dur_container">
	                					<h6 class="mb-2">Beratungsdauer</h6>
	                                    <div class="avail_duration" id="availability_duration">
	                                    </div>
	                				</div>
	                				<div class="col-md-12 book_pop_avail mb-4 coach_mode_container">
	                				</div>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="row avail_request avail_timeslots">
	                        <h5 class="col-md-7 ch_tabdata_head mb-3">Terminvorschlag 2</h5>
	                        <a href="javascript:void(0);" class="col-md-5 choosetimeslots">bearbeiten</a>
	                        <div class="unavail_inactive_container col-md-6">
	                            <div class="row">
	                                <div class="col-md-6">
	                                    <p class="inactivedata unavail_hdate">No date selected</p>
	                                </div>
	                                <div class="col-md-6">
	                                    <p class="inactivedata unavail_htime">No time selected</p>
	                                </div>
	                                <div class="col-md-6">
	                                    <p class="inactivedata unavail_hduratoin">No duration selected</p>
	                                </div>
	                                <div class="col-md-6">
	                                    <p class="inactivedata unavail_hstatus">No status selected</p>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="col-md-12 unavail_data_container" style="display:none;">
	                            <div class="row">
	                                <div class="col-md-6 book_pop_avail mb-4">
	                                    <h6 class="mb-2">Ausgewählter Termin</h6>
	                                    <p class="blk_rnd_select select_date active">10. Mai 2019</p>
	                                </div>
	                                <div class="col-md-6 book_pop_avail mb-4">
	                                    <h6 class="mb-2">Startzeit auswählen</h6>
	                                    <div class="mb-3 mb-md-0 reg_single_select book_pop_select">
	                                        <select class="select_time">
	                                        </select>
	                                    </div>
	                                </div>
	                                <div class="col-md-12 book_pop_avail mb-4 avail_dur_container">
	                                    <h6 class="mb-2">Beratungsdauer</h6>
	                                    <div class="avail_duration" id="availability_duration">
	                                    </div>
	                                </div>
	                                <div class="col-md-12 book_pop_avail mb-4 coach_mode_container">
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="row avail_request avail_timeslots">
	                        <h5 class="col-md-7 ch_tabdata_head mb-3">Terminvorschlag 3</h5>
	                        <a href="javascript:void(0);" class="col-md-5 choosetimeslots">bearbeiten</a>
	                        <div class="unavail_inactive_container col-md-6">
	                            <div class="row">
	                                <div class="col-md-6">
	                                    <p class="inactivedata unavail_hdate">No date selected</p>
	                                </div>
	                                <div class="col-md-6">
	                                    <p class="inactivedata unavail_htime">No time selected</p>
	                                </div>
	                                <div class="col-md-6">
	                                    <p class="inactivedata unavail_hduratoin">No duration selected</p>
	                                </div>
	                                <div class="col-md-6">
	                                    <p class="inactivedata unavail_hstatus">No status selected</p>
	                                </div>
	                            </div>
	                        </div>
	                        <div class="col-md-12 unavail_data_container" style="display:none;">
	                            <div class="row">
	                                <div class="col-md-6 book_pop_avail mb-4">
	                                    <h6 class="mb-2">Ausgewählter Termin</h6>
	                                    <p class="blk_rnd_select select_date active">10. Mai 2019</p>
	                                </div>
	                                <div class="col-md-6 book_pop_avail mb-4">
	                                    <h6 class="mb-2">Startzeit auswählen</h6>
	                                    <div class="mb-3 mb-md-0 reg_single_select book_pop_select">
	                                        <select class="select_time">
	                                        </select>
	                                    </div>
	                                </div>
	                                <div class="col-md-12 book_pop_avail mb-4 avail_dur_container">
	                                    <h6 class="mb-2">Beratungsdauer</h6>
	                                    <div class="avail_duration" id="availability_duration">
	                                    </div>
	                                </div>
	                                <div class="col-md-12 book_pop_avail mb-4 coach_mode_container">
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                </div>
				    <div class="col-md-12">
				        <div class="widget-title">
				            <p>Nachricht hinzufügen <span class="optional">(optional)</span></p>
				            <textarea class="addmesage_box notes" placeholder="Das ist mein Thema..."></textarea>
				        </div>
				    </div>
				    <div class="col-md-12">
			        	<button type="button" class="btn orange_background_btn mt-3 requestAppmntBtn">Beratung buchen</button>
				    </div>
				</div>
	        </div>
	    </div>
	  </div>
	</div>
	<!-- Request Availability Modal end -->
	
	<!-- Reviews Modal -->
	<div class="modal fade coach_msg_modal user_booking_pop" id="show_review" tabindex="-1" role="dialog" aria-hidden="true">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title mb-3" id="">Alle Bewertungen</h5>
	      </div>
	      <div class="modal-body coach_msg_body">
			<form action="get">
				<div class="comment_wrapper">
					@foreach ($reviews as $review)
						<div class="comments {{$loop->last?'border-0':''}}">
							<div class="comment-head">
								<div class="chat_icon">
									<i class="fa fa-comment" aria-hidden="true"></i>
								</div>
								<div class="comment_title">
									<h6>{{$review->user?$review->user->first_name:''}} <span class="chat_user_name">({{$review->created_at->diffForHumans()}})</span></h6>
								</div>
							</div>
							<div class="comment-body">
								<p class="comment_desc">{{$review->comment}}</p>
								<button type="button" class="btn orange_background_btn mehr_review mt-2 full_review_btn">mehr</button>
							</div>
						</div>
					@endforeach
				</div>
				<!-- <div class="all_reviews_btn text-center">
					<button class="no_background_btn" data-toggle="modal" data-target="#show_review">Alle Bewertungen</button>
				</div> -->
			</form>
	      </div>
	    </div>
	  </div>
	</div>
	<!-- Reviews Modal end-->

	<!-- Impressum Modal -->
	<div class="modal fade" id="coach_impressum_modal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Impressum</h4>
				</div>
				<div class="modal-body">
					@if($coach->impressum)
						{!! $coach->impressum !!}
					@else
						<p style="margin-bottom:20px;">Angaben	gemäB &5 TMG</p>
						<p>Klaus Kleber<br>
						Kleber-Beratung<br>
						BeraterstraBe 5<br>
						12345 Berlin
						</p>
						<br>
						<p>
							Kontakt:<br>
							Kontakt@kleberberatung.de<br>
							01234-56719283<br>
						</p>
					@endif
				</div>
			</div>
		</div>
	</div>
	<!-- Impressum Modal end -->

	<div class="main-container profile-wrapper" id="coach_booking_div">
		<input type="hidden" id="id" value="{{$coach->id}}">
		<div class="profile-banner-wrapper">
            <img src="{{ FileUploadHelper::getDocPath($coach->banner, 'banner') }}" class="w-100">
        </div>
        <div class="profile-img-wrapper text-center">
        	<div class="search-circle-object-fit-ie pro_detail_fit">
            	<img class="object-fit-ie-img" src="{{ FileUploadHelper::getDocPath($coach->avatar, 'avatar') }}" alt="x" />
        	</div>
        </div>
		<h2 class="profile-title text-center">{{ $coach->first_name.' '.$coach->last_name }}</h2>
		@if(count($coach_companies)>0)
        	<h4 class="profile-subtitle text-center">{{ $coach_companies[0]->pivot->designation }}</h4>
        @else
        	<h4 class="profile-subtitle text-center"></h4>
        @endif
		<div class="main-section-wrapper">
			<div class="container">
				<div class="row">
					<div class="col-md-5 sidebar_right">
						<div class="widget-section">
							<div class="widget-title">
								<h5>Über {{ $coach->first_name }}</h5>
							</div>
							<div class="widget-body">
								<ul>
									<li class="widget_coach_cost">
										<p class="widget_sub_title">Beratungspreis pro Stunde</p>
										<p class="price_perhour"><strong>{{ $coach->price_per_hour }} €</strong></p>
									</li>
									<li class="widget_cat coach_category_section">
										<p class="widget_sub_title">Kategorien</p>
										<div class="reaction_icons">
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
									</li>
									<li class="widget_lang">
										<p class="widget_sub_title">Sprachen </p>
										<p><strong>{{ $coach->language }}</strong></p>
									</li>
									<li class="widget_place">
										<p class="widget_sub_title">Ort</p>
										<p><strong>{{ $coach->work_place }}</strong></p>
									</li>
									<li class="widget_appraisal">
										<p class="widget_sub_title">Bewertung</p>
										<p id="rateYo" data-rateyo-rating="{{$reviews->avg('rating')?$reviews->avg('rating'):0}}">
											{{-- <span class="fa fa-star checked"></span>
											<span class="fa fa-star checked"></span>
											<span class="fa fa-star checked"></span>
											<span class="fa fa-star-o"></span>
											<span class="fa fa-star-o"></span> --}}
										</p>
									</li>
								</ul>
							</div>
						</div>
						<div class="widget-section">
							<div class="widget-title">
								<h5>Schwerpunkte</h5>
							</div>
							<div class="widget-body">
								<div class="selected_categories">
									@if($coach->priorities!='')
                                        @php
                                            $priority_arr = explode(', ', $coach->priorities);
                                        @endphp
                                        @foreach($priority_arr as $priority)
                                            <span>{{ $priority }}</span>
                                        @endforeach
                                    @endif
								</div>
							</div>
						</div>
						@if(count($coach_companies)>0)
							<div class="widget-section">
								<div class="widget-title">
									<h5>Erfahrung</h5>
								</div>
								<div class="widget-body">
									<div class="experince-image text-center">
										<div class="col-md-12 owl_exp">
											<div class="owl-carousel">
												@foreach ($coach_companies as $company)
													<div class="carousal_data">
														<p class="corousal-title"><strong>{{$company->pivot->designation}}</strong></p>
														<p class="widget_sub_title">Seit {{Carbon\Carbon::createFromFormat('Y-m-d',$company->pivot->joining_date)->format('m/Y')}}</p>
														<hr class="seprator"/>
														@if(strtolower($company->name)=='sonstige')
															<p class="corousal_company_name">
																<strong>
																{{$company->pivot->company_name}}
																</strong>
															</p>
														@else
															<img src="{{ FileUploadHelper::getDocPath($company->image, 'company_logo') }}" width="60%">
														@endif
													</div>
												@endforeach
											</div>
										</div>
									</div>
								</div>
							</div>
						@endif
						@if(isset($coach->community))
							<div class="widget-section">
								<div class="widget-title">
									<h5>Gemeinde/Verein</h5>
								</div>
								<div class="widget-body">
									<p>{{ $coach->community }}</p>
								</div>
							</div>
						@endif
						<div class="widget-section">
							<div class="widget-title has_rating">
								<h5>Bewertungen <span class="chat_count">({{$reviews->count()}})</span></h5>
							</div>
							<div class="widget-body">
								<div class="comment_wrapper">
									@foreach ($reviews as $review)
										<div class="comments">
											<div class="comment-head {{($loop->index==2 || $loop->last)?'border-0':''}}">
												<div class="chat_icon">
													<i class="fa fa-comment" aria-hidden="true"></i>
												</div>
												<div class="comment_title">
													<h6>{{$review->user?$review->user->first_name:''}} <span class="chat_user_name">({{$review->created_at->diffForHumans()}})</span></h6>
												</div>
											</div>
											<div class="comment-body">
												<p class="comment_desc">{{str_limit($review->comment, 100)}}</p>
											</div>
										</div>
										@if($loop->index==2)
											@php break; @endphp
										@endif
									@endforeach
								</div>
								@if($reviews->count()>0)
									<div class="all_reviews_btn text-center">
										<button class="no_background_btn" data-toggle="modal" data-target="#show_review">Alle Bewertungen</button>
									</div>
								@endif
							</div>
						</div>
						<div class="widget-section">
							<div class="widget-title has_rating">
								<h5>Impressum</h5>
							</div>
							<div class="widget-body">
								<div class="comment-body">
									@if($coach->impressum)
										{!! \Illuminate\Support\Str::limit($coach->impressum, 100, $end='...') !!}
									@else
										<p style="margin-bottom:20px;">Angaben	gemäB &5 TMG</p>
										<p>Klaus Kleber<br>
										Kleber-Beratung<br>
										BeraterstraBe 5<br>
										12345 Berlin
										</p>
									@endif
									<a class="popuplink" data-toggle="modal" href='#coach_impressum_modal'>Vollstandig anzeigen</a>
								</div>
							</div>
						</div>
						<div class="widget-section">
							<div class="widget-title has_rating">
								<h5>Stornierung</h5>
							</div>
							<div class="widget-body">
								<div class="comment-body">
									<p class="cancelinfo">Kostenlose Stornierung bis 48 Stunden vor dem Beratungstermin. Anschließend Rückerstattung von 40% des Rechnungsbetrages.</p>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-7 main-section">
						@if(isset($coach->video))
							<div class="coach_instruction_video">
								<video width="100%" height="360px" class="coach_presetation_video">
									<source src="{{ FileUploadHelper::getDocPath($coach->video, 'video') }}">
									Your browser does not support the video tag.
								</video>
								<div class="playpause playbtn"><img width="90px" src="{{asset('frontend/img/icons/videoplay_icon.png')}}"></div>
	                            <div class="playpause pausebtn" style="display:none;"><i class="fa fa-pause" aria-hidden="true"></i></div>
								{{-- <h3 class="video_title">Klaus stellt sich vor <span class="video_time">(2:30 min)</span></h3> --}}
							</div>
						@endif
						<div class="widget-section">
							<div class="widget-title">
								<h5>Beschreibung</h5>
							</div>
							<div class="widget-body">
								<p class="coach_description">{{$coach->description}} </p>
								<div class="readmore showfull_coachdesc text-right">
									<p class="rdmore_chdetail"><i class="fa fa-angle-down" aria-hidden="true"></i></p>
								</div> 
							</div>
						</div>	
						<div class="widget-section session_booking_wrapper">
							<div class="widget-title">
								<h5>Verfügbarkeit</h5>
							</div>
							<div class="widget-body form_dat_wrapper">
								<div class="row availContainer m-0">
								    <div class="col-md-12 mb-5 mt-4 period_date_wrap ch_detail_period">
								        <div class="datepicker" id="availabilityDatepicker"></div>
								    </div>
								    <div class="col-md-12 avail_request_container">
								        <div class="row avail_request avail_timeslots unavail_active">
								            <div class="col-md-12 unavail_data_container">
								                <div class="row">
								                    <div class="col-md-6 book_pop_avail mb-4">
								                        <h6 class="mb-2">Ausgewählter Termin</h6>
								                        <p class="blk_rnd_select select_date active">10. Mai 2019</p>
								                    </div>
								                    <div class="col-md-6 book_pop_avail mb-4">
								                        <h6 class="mb-2">Startzeit auswählen</h6>
								                        <div class="mb-3 mb-md-0 reg_single_select book_pop_select">
								                            <select class="select_time">
								                            </select>
								                        </div>
								                    </div>
								                    <div class="col-md-12 book_pop_avail mb-4 avail_dur_container">
								                        <h6 class="mb-2">Beratungsdauer</h6>
								                        <div class="avail_duration" id="availability_duration">
								                        </div>
								                    </div>
								                    <div class="col-md-12 book_pop_avail mb-4 coach_mode_container">
								                    </div>
								                </div>
								            </div>
								        </div>
								    </div>
								    <div class="col-md-12">
								        <div class="widget-title">
								            <p>Nachricht hinzufügen <span class="optional">(optional)</span></p>
								            <textarea class="addmesage_box notes" placeholder="Das ist mein Thema..."></textarea>
								        </div>
								    </div>
								    <div class="col-md-12">
					            		<button type="button" class="btn orange_background_btn mt-3 bookAppmntBtn">Beratung buchen</button>
								    </div>
								</div>
					            <div class="row m-0">
									<div class="col-md-12 nosuitable_date" style="margin-top:15px">
										<a href="#">Kein passender Termin verfügbar?</a>
									</div>
									<div class="col-md-12 request_appointment">
										<button class="no_background_btn" id="request_app_btn">Termin anfragen</button>
									</div>
					            </div>
							</div>
						</div>
					</div>					
				</div>
			</div>
			{{-- <div class="lastestblogs_wrapper">
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<h1 class="blog_main_title">Letzte Blogeinträge</h1>
						</div>
						<div class="col-md-6 blogsdata">
							<div class="blog_header">
								<div class="blog_profile_img">
									<img src="{{ asset('frontend/img/talin.png') }}">
								</div>
								<div class="blog_title">
									<p>Klaus Kleber</p>
									<div class="date"><i class="fa fa-clock-o" aria-hidden="true"></i><p>24. Mai 2019</p></div>
								</div>
							</div>
							<div class="blog_body">
								<div class="blog_image">
									<img src="{{ asset('frontend/img/blog-image.jpg') }}"  width="100%"> 
								</div>
								<h3 class="blog_heading">Konflikte in der Familie richtig lösen.</h3>
								<div class="blog_shortdesc">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna […]</div>
							</div>
						</div>
						<div class="col-md-6 blogsdata">
							<div class="blog_header">
								<div class="blog_profile_img">
									<img src="{{ asset('frontend/img/talin.png') }}">
								</div>
								<div class="blog_title">
									<p>Klaus Kleber</p>
									<div class="date"><i class="fa fa-clock-o" aria-hidden="true"></i><p>24. Mai 2019</p></div>
								</div>
							</div>
							<div class="blog_body">
								<div class="blog_image">
									<img src="{{ asset('frontend/img/blog-image.jpg') }}"  width="100%"> 
								</div>
								<h3 class="blog_heading">Konflikte in der Familie richtig lösen.</h3>
								<div class="blog_shortdesc">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna […]</div>
							</div>
						</div>
					</div>
				</div>
			</div> --}}
			<div class="container-fluid life_counsel_wrapper">
				<div class="container text-center">
					<div class="row justify-content-center">
						<div class="col-sm-12 col-md-8">
							<h4>Mit himmlischberaten.de schaffen wir einen einfachen und schnellen Zugang zu professioneller Lebensberatung auf christlicher Basis. Niemand, der Hilfe sucht, bleibt mit seinen Herausforderungen alleine.</h4>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
	
@section('scripts')
  	<script src="{{ asset('frontend/js/owl.carousel.min.js') }}"></script>
  	<script src="{{ asset('frontend/js/jquery-ui.min.js') }}"></script>
  	<script src="{{ asset('frontend/js/datepicker-de.js') }}"></script>
  	<script src="{{ asset('frontend/js/select2.min.js') }}"></script>
    <script src="{{ asset('frontend/js/jquery.rateyo.min.js') }}"></script>
	<script src="{{ asset('frontend/js/custom/display_availabilities.js') }}"></script>
	<script>
		$(document).ready(function() {
	        var coach_id     	= '{{$coach->id}}';
			var allowed_min_dur = {{ env('SLOT_MIN_DURATION', 30) }};
			var allowed_max_dur = {{ env('SLOT_MAX_DURATION', 120) }};
	        
			// Initialize availability/unabailability calendar
            AvailCalendarModule.initData(coach_id, allowed_min_dur, allowed_max_dur, true);
            AvailCalendarModule.initAvailCalendar("availabilityDatepicker");
            AvailCalendarModule.initUnAvailCalendar("unavailDatepicker");
            AvailCalendarModule.initSlotStatus('both');
            
            // Other Intializations
            $(".owl_exp .owl-carousel").owlCarousel({
                items:1,
                //loop:true,
                nav:false,
                dots:true,
                margin:30,
                autoplay:false,
                autoplayTimeout:6000
            });

            $('.playbtn').click(function(event) {
                $(this).hide();
                //$('.pausebtn').show();
                $('.coach_presetation_video')[0].play();
                //$('.coach_presetation_video').attr('controls', 'controls');
                $('.coach_presetation_video').attr({
                	controls: 'controls',
                	disablePictureInPicture: true,
                	controlsList:'nodownload'
                });
            });
            
            $('.pausebtn').click(function(){
                $(this).hide();
                $('.playbtn').show();
                $('.coach_presetation_video')[0].pause();
            });
            
            $('.showfull_coachdesc').click(function(){
                $('.coach_description').toggleClass('fulldesc');
                var icon_elem = $(this).find('i');
                if($(icon_elem).hasClass("fa-angle-down")) {
                    $(icon_elem).removeClass('fa-angle-down');
                    $(icon_elem).addClass('fa-angle-up');
                } else {
                    $(icon_elem).removeClass('fa-angle-up');
                    $(icon_elem).addClass('fa-angle-down');
                }
            });

            $('.full_review_btn').click(function(event) {
                $(this).parent().find('p').toggleClass('comment_desc');
            });

            $("#request_app_btn").click(function(event) {
            	var logged_in = '{{Auth::guard('user')->check()}}';
            	if(logged_in)
            		$("#coach_booking_modal").modal('show');
            	else {
            		var coach_relative_url = "{{route('coach-detail', ['name' => $coach->first_name.'-'.$coach->last_name,'id' => $coach->id], false)}}";
            		window.location.href =  publicUrl+'login?redirect_to='+coach_relative_url;
            	}
            });

		});
	</script>
@endsection