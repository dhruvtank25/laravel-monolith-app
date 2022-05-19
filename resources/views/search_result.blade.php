@extends('layouts.app')

@section('style_link')
<link rel="stylesheet" href="{{ asset('frontend/css/jquery-ui.min.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/bootstrap-slider.min.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/availability.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/jquery.rateyo.min.css') }}">
@endsection

@section('content')
	@if($page_request === 'category')
		@if(isset($category_detail))
			<div class="category_banner" style="background-image:url('{{ FileUploadHelper::getDocPath($category_detail->banner, 'cat_banner') }}')">
		@else
			<div class="category_banner" style="background-image: url({{asset('frontend/img/category-banner-image.jpg')}});">
		@endif
				<div class="cat_banner_data">
					<div class="cat_icon srchbannered">
						@if(isset($category_detail))
							{!! $category_detail->icon !!}
						@else
							<img src="{{ asset('frontend/img/icons/heart-icon.png') }}">
						@endif
					</div>
					@if(isset($category_detail))
						<h2 class="cat_title">{{ $category_detail->title }}</h2>
					@endif
				</div>
			</div>
	@endif
	
	<!-- teriff section -->
	<div class="container-fluid padding-0">
		@if($page_request === 'category')
			<div class="cat_tab_wrapper">
				<div class="container">
					<div class="row">
						<ul class="nav cat_tabs" role="tablist">
							<li role="presentation" class="active"><a href="#berater" role="tab" class="btn btn-block blue-btn active-btn" data-toggle="tab">Berater wählen</a></li>
							<li role="presentation"><a href="#mehr" class="btn btn-primary btn-block blue-btn" role="tab" data-toggle="tab">Mehr zum Thema</a></li>
						</ul>	
					</div>
				</div>
			</div>
		@endif
		<div class="cat_tab_content">				
			<div class="tab-content cat_dashboard_tab_content">
				<div role="tabpanel" class="tab-pane active" id="berater">
					<!-- Filter -->
					<div class="container-fluid padding-0 {{ $page_request!='category'?'bg_grey':'' }} ">
						<div class="container coach_filter all_consultant_filter">
							<form id = "search_coach" action="{{route('coach-search',['url_slug' => 'alle-berater'])}}">
								<div class="row">
									<input type="hidden" name="category_id" id="category_id">
									<input type="hidden" name="sorting" id="sorting" value="">
									<input type="hidden" name="offline_check" id="offline_check">
									<input type="hidden" name="online_check" id="online_check">
									<div class="filter_list coach_date col-lg-2">
										<ul class="all_filter">
											<li>
												Datum & Zeit
												<div class="all_filter_option">
													<div class="mb-5 period_date_wrap">
														<div class="datepicker"></div>
													<input id="availability_date" type="hidden" name="availability_date" value="{{$availability_date}}">
													</div>
													<h6 class="mb-3">Zeitraum</h6>
													<p class="mb-3">Alle verfügbaren Berater in diesem Zeitraum werden angezeigt.</p>
													<input id="availability_start_time" type="hidden" name="availability_start_time" value="">
													<input id="availability_end_time" type="hidden" name="availability_end_time" value="">
													<div class="range_period_slider mb-4">
														<input name="availability_time" data-slider-min="04.00" data-slider-max="23.00" id="time_slider" type="text" class="period_range_slider" data-slider-value="[{{$availability_start_time}},{{$availability_end_time}}]" value=""/>
								                        <p class="period_range_val mt-4 period_from" data-time="{{$availability_start_time}}">{{$availability_start_time}} Uhr</p>
								                        <p class="mt-3">bis</p>
								                        <p class="period_range_val mt-4 period_to" data-time="{{$availability_end_time}}">{{$availability_end_time}} Uhr</p>
													</div>
													<button type="button" class="btn blue_background_btn filterbox_close">Speichern</button>
												</div>
											</li>
										</ul>
									</div>
									<div class="filter_list coach_price col-lg-1">
										<ul class="all_filter">
											<li>
												Preis
												<div class="all_filter_option">
													<h6 class="mb-3">Preis pro Stunde</h6>
													<div class="range_period_slider mb-4">
														<input name="price_range" id="period" type="text" class="period_range_slider" value="" data-slider-min="0" data-slider-max="{{isset($max_price) ? $max_price : 100}}" data-slider-step="1" data-slider-value="[{{isset($start_slider_value) ? $start_slider_value : 0}},{{isset($end_slider_value) ? $end_slider_value : $max_price}}]"/>
								                        <p class="period_range_val mt-4 period_from" value = "{{isset($start_slider_value) ? $start_slider_value : 0}}" >{{isset($start_slider_value) ? $start_slider_value : 0}} €</p>
								                        <p class="mt-3">bis</p>
								                        <p class="period_range_val mt-4 period_to" value = "{{isset($end_slider_value) ? $end_slider_value : $max_price}}" > {{isset($end_slider_value) ? $end_slider_value : $max_price}} €</p>
													</div>
													<button type="button" class="btn blue_background_btn filterbox_close">Speichern</button>
												</div>
											</li>
										</ul>
									</div>
									<!-- <div class="coach_appraisal col-md-2">
										<input type="text"  name="coachdateappraisal" placeholder="Bewertung">
									</div> -->
									<div class="filter_list coach_ortv col-lg-1">
										<ul class="all_filter">
											<li>
												Ort
												<div class="all_filter_option">
													<input type="text" id="autocomplete_loc" class="form-control mb-3" name="place" placeholder="PLZ oder Ort eingeben" value="{{@$place}}" autocomplete="off">
													<input type="hidden" name="latitude" id="latitude" value="{{@$coordinates['latitude']}}">
                                            		<input type="hidden" name="longitude" id="longitude" value="{{@$coordinates['longitude']}}">
													<h6 class="mb-2">Umkreis</h6>
													<select name="distance" class="mb-4" id="distance_sel">
														<option value="10" {{@$distance=='10'?'selected':''}}>10km</option>
														<option value="15" {{@$distance=='15'?'selected':''}}>15km</option>
														<option value="20" {{@$distance=='20'?'selected':''}}>20km</option>
													</select>
													<button type="button" class="btn blue_background_btn filterbox_close">Speichern</button>
												</div>
											</li>
										</ul>
									</div>
									<div class="filter_list coach_community col-lg-2">
										<ul class="all_filter">
											<li>
												Gemeinde/Verein
												<div class="all_filter_option">
													<p class="mb-3">Suche nach einem Berater in einer Gemeinde oder einem Verein.</p>
													<input name="coach_community" type="text" class="form-control mb-3" id="" placeholder="Gemeinde/Verein eingeben" value="{{$community}}">
													<button type="button" class="btn blue_background_btn filterbox_close">Speichern</button>
												</div>
											</li>
										</ul>
									</div>
									<div class="filter_list coach_starttime col-lg-2">
										<!-- <input type="text"  name="coachdatestarttime" placeholder="Online/Offline"> -->
										<ul class="online_status all_filter">
											<li>
												Online/Offline
												<ul class="online_status_option all_filter_option">
													<li>Wähle aus, ob du deine Beratung vor Ort oder online in Anspruch nehmen möchtest.</li>
													<li>
														<div class="ano_counselling rounded_cust_switch">
															<div class="ano_select">
																<h6>Offline-Beratung</h6>
																<div class="selection_wrapper">
																	<!-- Rounded switch -->
																	<label class="switch">
																		<input id="is_offline" type="checkbox" name="is_offline" class="period_switch offline_advise">
																		<span class="slider round"></span>
																	</label>
																</div>
															</div>
														</div>
													</li>
													<li>
														<div class="ano_counselling rounded_cust_switch">
															<div class="ano_select">
																<h6>Online-Beratung</h6>
																<div class="selection_wrapper">
																	<!-- Rounded switch -->
																	<label class="switch">
																		<input id="is_online" type="checkbox" name="is_offline" class="period_switch offline_advise">
																		<span class="slider round"></span>
																	</label>
																</div>
															</div>
														</div>
														<button type="button" class="btn blue_background_btn mt-3 filterbox_close">Speichern</button>
													</li>
												</ul>

											</li>
										</ul>
									</div>
									<div class="col-lg-2 search_consult">
										<input type="text"  name="search_consult" value="{{@$search_title}}" placeholder="Suche" style="{{ $page_request!='category'?'':'border: 2px solid #292929;' }}">
									</div>
									<div class="coach_viewprofile src_fil_sub col-lg-2">
										<button id="filterBtn" class="orange_background_btn" data-coachid="9">Suche</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					<!-- Filter end -->
					<!-- Map -->
					<div class="map_consultant">
						<div id="googleMap" style="width:100%;height:450px;"></div>
					</div>
					<!-- Map end -->
					<!-- Categories list for all coach -->
					@if($page_request === 'all_coach')
						<div class="container-fluid concern_container">
							<div class="container text-center">
								<div class="row">
									<h2 class="col-12 section_head">Wähle dein Anliegen</h2>
									@foreach ($categories as $category)
										<div class="col-sm-12 col-md-3 concern_choice">
											<a href="{{ route('coach-search',['title' => $category->url_slug]) }}">
												<div class="choice_wrapper">
													<div class="home_cat_banner object-fit-ie">
														<img class="tp_img object-fit-ie-img all_consult_concern" src="{{ FileUploadHelper::getDocPath($category->banner, 'cat_banner') }}" alt="x" />
													</div>
													<div class="choice_data">
														<div class="home_cat_icon">
															{!! $category->icon !!}
														</div>
														{{-- <img src="{{ $category->icon }}" alt="x" /> --}}
														<h6>{{$category->title}}</h6>
													</div>
												</div>
											</a>
										</div>
									@endforeach
								</div>
							</div>
						</div>
					@endif
					<!-- Categories list end -->
					<!-- Search result -->
					<div class="coach_search_result">
						<div class="container">
							<div class="row">
								<div class="col-md-6 col-6">
									<p class="searchresult_count"><span class="coach_count">{{ $coaches->total() }}</span> Ergebnisse</p>
								</div>
								<div class="col-md-6 col-6 text-right">
									<div class="searchresult_sortby">Sortieren nach  <i class="fa fa-sort" aria-hidden="true"></i>
									<div class="searchresult_sortby_drpdwn">
										<ul>
											<li id="asc_li"data-val="asc" class="active">Preis aufsteigend</li>
											<li id="desc_li" data-val="desc">Preis absteigend</li>
										</ul>
									</div>
									</div>
								</div>
								<div id="filtered_data" class="clearfix"></div>
								<div class= "coach_filtered" id ="sort">
									@foreach ($coaches as $coach)
											<div  class="coach_wrapper col-md-12">
												<div class=" coach_main_section">
													<div class="row">
														<div class="coach_profile_img col-lg-2 pb-3">
															<div class="search-circle-object-fit-ie">
																<img class="object-fit-ie-img" src="{{ FileUploadHelper::getDocPath($coach->avatar, 'avatar') }}" alt="x" />
															</div>
														</div>
														<div class="coach_title col-lg-4">
															<h3 class="profile-title">{{ $coach->first_name.' '.$coach->last_name }}</h3>
															@if(count($coach->companies)>0)
																@php
																	$cur_company = $coach->companies[0];
																@endphp
																<p class="coach_subtitle">{{ $cur_company->pivot->designation}} </p>
															@else
																<p class="coach_subtitle"></p>
															@endif
															{{-- <div class="widget_appraisal">
																<p>
																	<span class="fa fa-star checked"></span>
																	<span class="fa fa-star checked"></span>
																	<span class="fa fa-star checked"></span>
																	<span class="fa fa-star-o"></span>
																	<span class="fa fa-star-o"></span>
																</p>
															</div> --}}
															<div class="star_wrap rating_div coachsearchrating_wrapper" data-rateyo-rating="{{$coach->reviews->avg('rating')?$coach->reviews->avg('rating'):0}}">
                                    						</div>
														</div>
														<div class="coach_location col-lg-2">
															<p><i class="fa fa-map-marker" aria-hidden="true"></i> {{ $coach->work_place }}</p>
														</div>
														<div class="coach_hourrate col-lg-2" >
															<p ><span class= "price">{{ $coach->price_per_hour }}</span><span>€/Std.</span></p>
														</div>
														<div class="coach_viewprofile col-lg-2 mb-3">
															<a href="{{route('coach-detail',['name'=>$coach->first_name.'-'.$coach->last_name,'id'=>$coach->id])}}" class="orange_background_btn srh_pro_rst">Profil ansehen</a>
															{{-- <button class="orange_background_btn" data-coachid="{{ $coach->id }}">Profil ansehen</button> --}}
														</div>
														<div class="coach_moreinfo_wrapper col-md-12 no-gutters">
															<div class="coach_moreinfo col-md-12" style="">
																<div class="coach_cat_wrapper">
																	<div class="row">
																		<div class="col-md-4">
																			<h3 class="srch_prop">Kategorien</h3>
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
																		</div>
																		<div class="col-md-6">
																			<h3 class="srch_prop">Themenfelder</h3>
																			<div class="selected_categories mt-3">
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
																</div>
															</div>
															<div class="view_more">
																<p><i class="view_mr fa fa-angle-down" aria-hidden="true"></i></p>
															</div>
														</div>
													</div>
												</div>	
											</div>	
									@endforeach
									{{ $coaches->appends((request()->except('page'))) }}
								</div>	
								<!-- <p class="col-12 show_more_coach text-center"><a href="javascript:void()">Mehr anzeigen</a></p> -->
							</div>
						</div>
					</div>
					<!-- Search result end -->
				</div>
				<div role="tabpanel" class="tab-pane" id="mehr">
					<div class="container">
						<div class="row">
							<!-- Category detail -->
							<div class="mehr_wrapper col-sm-12">
								@if(isset($category_detail))
									{!! $category_detail->description !!}
								@endif
							</div>
							<!-- Category detail secion end -->
						</div>
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
	</div>
	
@endsection

@section('scripts')
<!-- ================== PARTIAL INFO JS ================== -->
<script src="{{ asset('frontend/js/select2.min.js') }}"></script>
<script src="{{ asset('frontend/js/jquery.jscroll.js') }}"></script>
<script src="{{ asset('frontend/js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('frontend/js/datepicker-de.js') }}"></script>
<script src="{{ asset('frontend/js/bootstrap-slider.min.js') }}"></script>
<script src="{{ asset('frontend/js/all_coaches.js')}}"></script>
<script src="{{ asset('frontend/js/jquery.rateyo.min.js') }}"></script>
	<script>
		// Note: This example requires that you consent to location sharing when
		// prompted by your browser. If you see the error "The Geolocation service
		// failed.", it means you probably did not give permission for the browser to
		// locate you.

		var coach_avatar_baseurl = "{{ FileUploadHelper::getDocPath('', 'avatar') }}";
		var icon_arr  = [];
		var coach_base_url = "{{route('coach-detail', ['name' => '','id' => ''])}}";
		@foreach($categories as $category)
			icon_arr.push({id: {{$category->id}}, icon:{!! json_encode($category->icon) !!}});
		@endforeach


		function initAutoComplete() {
			var input      = document.getElementById('autocomplete_loc');
			var options = {
				//types: ['geocode'], //this should work !
				//region:'EU',
				//componentRestrictions: {country: "AU"}
				types: ['(cities)']
			};
			home_autocomplete = new google.maps.places.Autocomplete(input, options);
			// console.log(home_autocomplete);
			home_autocomplete.addListener('place_changed', fillInAddress);
			
			// Stop form submit on address select by enter
			google.maps.event.addDomListener(input, 'keydown', function(event) { 
			    if (event.keyCode === 13) { 
			        event.preventDefault(); 
			    }
			});
		}
		function fillInAddress(){
			place = home_autocomplete.getPlace();
			search_lat = place.geometry.location.lat();
			search_lng = place.geometry.location.lng();
			var address_obj = {lat:"", lng:"", street_no:"", route:"", city:"", city1:"", state:"", post_code:"", country:"",country_code:""};

			// Address co-ordinates
			address_obj.lat = place.geometry.location.lat();
			address_obj.lng = place.geometry.location.lng();

			// Get each component of the address from the place details
			// and fill the corresponding field on the form.
			for (var i = 0; i < place.address_components.length; i++) {
				var addressType = place.address_components[i].types[0];
				switch (addressType) {
					case "administrative_area_level_1":
						address_obj.state = place.address_components[i]["short_name"];
						break;
					case "administrative_area_level_2":
						address_obj.city1 = place.address_components[i]["short_name"];
						break;
					case "postal_code":
						address_obj.post_code = place.address_components[i]["short_name"];
						break;
					case "locality":
						address_obj.place = place.address_components[i]["long_name"];
						break;
					case "country":
						address_obj.country = place.address_components[i]["long_name"];
						address_obj.country_code = place.address_components[i]["short_name"];
						break;
					default:
						console.log(addressType);
						break;
				}
			}
			document.getElementById("latitude").value          = address_obj.lat;
            document.getElementById("longitude").value         = address_obj.lng;
			// document.getElementById("autocomplete_loc").value  = address_obj.city!=''?address_obj.city:address_obj.city1;
		}
      	var map, infoWindow;
	    function initMap() {
			initAutoComplete();

	        map = new google.maps.Map(document.getElementById('googleMap'), {
	          //center: {lat: -34.397, lng: 150.644},
	          zoom: 12,
	          disableDefaultUI: true,
	          zoomControl: true,
	        });


	        infoWindow = new google.maps.InfoWindow;

	        // Try HTML5 geolocation.
	        if (navigator.geolocation) {
	          navigator.geolocation.getCurrentPosition(function(position) {
	            var pos = {
	              lat: position.coords.latitude,
	              lng: position.coords.longitude
	            };

	        	var marker = new google.maps.Marker({
	        		position: pos, 
	        		map: map,
	        		icon: '{{asset('frontend/img/icons/marker_blue.png')}}'
	        	});
	            //infoWindow.setPosition(pos);
	            //infoWindow.setContent('You are here');
	            //infoWindow.open(map, marker);
	            map.setCenter(pos);
	          }, function() {
	          	var pos = {
	              lat: 51.1657,
	              lng: 10.4515
	            };
	          	map.setCenter(pos);
	            handleLocationError(true, infoWindow, map.getCenter());
	          });
	        } else {
	          // Browser doesn't support Geolocation
	          handleLocationError(false, infoWindow, map.getCenter());
	        }
	    }

		$.ajax({
			url: baseUrl+'/get_all_coaches/locations',
			type: 'GET',
		})
		.done(function(data) {
			console.log("success");
			var infowindow = new google.maps.InfoWindow();
			if(data.success=="true"){
				$.each(data.getAllCoachLocations, function ( index, value ) {
					marker = new google.maps.Marker({
						position: new google.maps.LatLng(value.latitude, value.longitude),
						map: map,
						icon: '{{asset('frontend/img/icons/marker_red.png')}}'
					});

					google.maps.event.addListener(marker, 'mouseover', (function(marker, value) {
						return function() {
							var company = value.companies;
							var categories = value.categories;
							if(company.length>0) {
								//var company_name = company[0].name=='other'?company[0].pivot.company_name:company[0].name;
								var company_detail = company[0].pivot.designation;
							} else {
								var company_detail = '';
							}
							var coach_cats = [];
							for(let i = 0, length1 = categories.length; i < length1; i++){
								coach_cats.push(categories[i].id);
							}

							var html = '<div class="row">'+
  									   '<div class="coach_profile_img col-md-4" style="padding-right:5px;">'+
  					  				   '<div class="search-circle-object-fit-ie">'+
        							   '<img class="object-fit-ie-img marker-profile-img" src="'+coach_avatar_baseurl+value.avatar+'" alt="x">'+
   									   '</div>'+
									   '</div>'+
									   '<div class="coach_title col-md-7" style="padding:0px;">'+
									   '<h5 class="profile-title markerprofile-title">'+value.first_name+' '+value.last_name+'</h5>'+
									   '<p class="coach_subtitle">'+company_detail+'</p>'+
									   
									   '<div class="reaction_icons marker_reactions">';
									   	for(let i = 0, length1 = icon_arr.length; i < length1; i++){
									   		var active = coach_cats.includes(icon_arr[i].id)?'active':'';
									   		html += '<div class="react_icons_wrapper '+active+'">'+icon_arr[i].icon+'</div>';
									   	}
								html+= '</div>'+
								'<div class="row">'+
								'<div class="coach_hourrate marker_price col-md-4">'+
									   '<p><span class=" price">'+value.price_per_hour+'</span><span>€/Std.</span></p>'+
									   '</div>'+
									   '<div class="selected_categories marker_cat col-md-6 mt-2">'+  
									   '<a href="'+coach_base_url+'/'+value.first_name+' '+value.last_name+'/'+value.id+'">Anzeigen</a>'+
									   '</div>'+			
									   '</div>'+
									   '</div>'+
									   '</div>';
							infowindow.setContent(html);
							/*infowindow.setContent('Coach name: <a href="'+coach_base_url+'/'+value.first_name+' '+value.last_name+'/'+value.id+'">'+ value.first_name +'</a><p>Address: '+value.house_no+' '+value.street+' '+value.place+' '+value.country+'</p>' );*/
							infowindow.open(map, marker);
						}
					})(marker, value));
					
				})
			}else{
				alert('Mobile number already exists.');
			}
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});

		function handleLocationError(browserHasGeolocation, infoWindow, pos) {
			infoWindow.setPosition(pos);
			infoWindow.setContent(browserHasGeolocation ?
			                      'Error: The Geolocation service failed.' :
			                      'Error: Your browser doesn\'t support geolocation.');
			infoWindow.open(map);
		}

		function setStarRating() {
			$(".rating_div").rateYo({
			    //starWidth: "24px",
			    halfStar: true,
			    readOnly: true,
			    //precision: 0.5
			});
		}

    </script>
    <script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBR5FirOPNOnP9WBqT0ZMBbzyQ8reeVLhI&libraries=places&callback=initMap">
    </script>

<script>
	$(document).ready(function() {

		setStarRating();

		$('.coach_moreinfo:first').css('display','block');
		$('.view_mr:first').removeClass('fa-angle-down');
		$('.view_mr:first').addClass('fa-angle-up');
	
		/** Filters */
		
		/*var isFocused;
		// Fix for IE for select triggering parent hover
        var userAgent, ieReg, ie;
        userAgent = window.navigator.userAgent;
        ieReg = /msie|Trident.*rv[ :]*11\./gi;
        ie = ieReg.test(userAgent);
        if(ie) {
			$("#distance_sel").selectmenu({
				open: function (event, ui) {
					isFocused = true;
				},
				close: function () {
					isFocused = false;
				}
			});
        }*/

		// Prevent filter box close on filter body click
		$(".all_filter_option").click(function(event) {
			event.stopImmediatePropagation();
		});

		$(".all_consultant_filter .all_filter").click(function(event) {
			if($(this).hasClass('active')) {
				closeAllFilterBox();
			} else{
				closeAllFilterBox();
				$(this).addClass('active');
				$(this).find('.all_filter_option').css('display', 'block');
				$( ".map_consultant" ).addClass( "map_over" );
			}
		});

		function closeAllFilterBox() {
			var $filter = $(".all_consultant_filter .all_filter");
			$filter.removeClass('active');
			$filter.find('.all_filter_option').css('display', 'none');
			$( ".map_consultant" ).removeClass( "map_over" );
		}

		$(".filterbox_close").click(function(event) {
			closeAllFilterBox();
		});

		// $(".all_consultant_filter .all_filter").mouseout(function(event) {
		// 	if(!$(this).parent().hasClass('coach_ortv') || !isFocused) {
		// 		$(this).find('.all_filter_option').removeAttr('style');
		// 		$( ".map_consultant" ).removeClass( "map_over" );
		// 	}
		// });

		/*$(".filterbox_close").click(function(event) {
			$(this).closest('.all_filter').trigger('mouseleave');
		});*/

		/*$( "#search_coach .all_filter, #search_coach .online_status" ).mouseover(function() {
		  	$( ".map_consultant" ).addClass( "map_over" );
		}).mouseout(function(event) {
			$( ".map_consultant" ).removeClass( "map_over" );
		});*/

		/*$(document).on('mouseover','.all_filter li', function(e){
			$('.all_filter_option').removeClass("active_filter");
			$(this).children().addClass("active_filter");
		});

		$(document).on('click', '.blue_background_btn', function(e){
			$(this).parent().removeClass("active_filter");
		});*/
		
		/** Filters End */

		/** Sort */
		$(".searchresult_sortby").click(function(event) {
			$(".searchresult_sortby_drpdwn").toggle();
		});

		if(getUrlVars()["sorting"] == 'desc'){
			$('#desc_li').addClass('active');
			$('#asc_li').removeClass('active');
		}else{
			$('#desc_li').removeClass('active');
			$('#asc_li').addClass('active');
		}
			
		$(".searchresult_sortby_drpdwn li").click(function(event) {
			
			if($(this).hasClass('active'))
				return true;
			$(".searchresult_sortby_drpdwn li").removeClass('active');
			$(this).addClass('active');

			let sort_value = $(".searchresult_sortby_drpdwn li.active").attr('data-val');
			$("#sorting").val(sort_value);
			$("#search_coach").submit();

			// if($(this).data('val')=='price_asc')
			// 	desc = true;
			// else if($(this).data('val')=='price_desc')
			// 	desc = false;
			// var content = document.querySelector('.coach_filtered > div');
			// var els = Array.prototype.slice.call(document.querySelectorAll('.coach_filtered > div .coach_wrapper'));
			// els.sort(function(a, b) {
			// 	na = parseInt(a.querySelector('.price').innerHTML);
			// 	nb = parseInt(b.querySelector('.price').innerHTML);
			// 	return desc ? (nb - na) : (na - nb);
			// });
			// els.forEach(function(el) {
			// 	content.appendChild(el);
			// });
		});
		/** Sort End */

		var myDateVal = moment($('#availability_date').val()).format('YYYY-MM-DD');
		$( ".datepicker" ).datepicker({
			numberOfMonths: 1,
			showButtonPanel: false,
			firstDay: 1,
			minDate:0,
			dateFormat: 'yy-mm-dd',
			setDate: myDateVal,
			onSelect: function(dateText) {
				$('#availability_date').val(this.value);
			}
		});

		$( ".datepicker" ).datepicker('setDate', myDateVal );

		$('b[role="presentation"]').hide();

		//bootstrap range slider for datefilter
		$(".period_range_slider:first").bootstrapSlider({
			min: 04,
			max: 23,
			step: 0.25,
			//value: [04, 23]
			value: [convertTimeToNumber('{{$availability_start_time}}'), convertTimeToNumber(
				'{{$availability_end_time}}')]
		});

		//boostrap range slider for price filter
		$(".period_range_slider:last").bootstrapSlider({});

		$(document).on('click', 'body', function(event) {
			if($(event.target).closest('.filter_list ').length==0) {
				if($(".all_consultant_filter .all_filter.active").length>0) {
					closeAllFilterBox();
					//event.preventDefault();
				}
			}
		});

	});



	//boostarp range slider on change event
	$(document).on('slide', '.period_range_slider:first', function(slideEvt) {
		slideEvt.preventDefault();
		$start_time = $('.period_from').data('time');
		$end_time = $('.period_to').data('time');
		$('#availability_start_time').val($start_time);
		$('#availability_end_time').val($end_time);
	});

	$(document).on('slideStop', '.period_range_slider:first', function(slideEvt) {
		slideEvt.preventDefault();
		$start_time = $('.period_from').data('time');
		$end_time = $('.period_to').data('time');
		$('#availability_start_time').val($start_time);
		$('#availability_end_time').val($end_time);
	});

	$('.select2-selection__arrow').append('<i class="fa fa-angle-down"></i>');
	
</script>
@endsection
