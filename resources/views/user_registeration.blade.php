@extends('layouts.app')

@section('style_link')
<link rel="stylesheet" href="{{ asset('frontend/css/jquery-ui.min.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/availability.css') }}">
@endsection

@section('content')
	
	<div class="container-fluid ref_steps_container">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-8" id="rootwizard">
					<form action="get" id="userRegFrm">
						@csrf
						<input type="hidden" name="id" value="">
						<div class="navbar">
							<div class="navbar-inner">
								<div class="container">
									<ul class="noclick">
										<li><a href="#tab1" data-toggle="tab">1</a></li>
										<li><a href="#tab2" data-toggle="tab">2</a></li>
										{{-- @if(!$type || $type!='guest') --}}
										@if(!$appointment)
											<li><a href="#tab3" data-toggle="tab">3</a></li>
										@endif
									</ul>
								</div>
							</div>
						</div>
						<div class="tab-content">
							<div class="tab-pane multi_step_tabone" id="tab1">
								<h3>Persönliche Informationen</h3>
								<div class="personal_info_step">
									<input type="hidden" id="type" name="type" value="{{$type}}">
									<div class="row">
										<div class="col-md-6 mb-3">
											<label for="given-name">Vorname</label>
											<input type="text" class="form-control" placeholder="Vorname" id="first_name" name="first_name">
										</div>
										<div class="col-md-6 mb-3">
											<label for="surname">Nachname</label>
											<input type="text" class="form-control" placeholder="Nachname" id="last_name" name="last_name">
										</div>
										<div class="col-md-6 mb-3">
											<label for="email">E-Mail</label>
											<input type="email" class="form-control" placeholder="E-Mail Adresse" id="email" name="email">
										</div>
										<div class="col-md-6 mb-3">
											<label for="email_confirmation">E-Mail wiederholen</label>
											<input type="email" class="form-control" placeholder="E-Mail wiederholen" id="email_confirmation" name="email_confirmation">
										</div>
										@if($type!='guest')
											<div class="col-md-6 mb-3">
												<label for="password">
													Passwort
													<p class="tool_tip" data-toggle="tooltip" data-placement="right" title="Mindestens acht Zeichen, davon mindestens einen Großbuchstaben und mindestens eine Zahl."><i class="fa fa-question-circle" aria-hidden="true"></i></p>
												</label>
												<input type="password" class="form-control" placeholder="Passwort festlegen" id="password" name="password">
											</div>
											<div class="col-md-6 mb-3">
												<label for="password_confirmation">Passwort wiederholen</label>
												<input type="password" class="form-control" placeholder="Passwort bestätigen" id="password_confirmation" name="password_confirmation">
											</div>
										@endif
										<div class="col-md-6 mb-3">
											<label for="user_name">Benutzername</label>
											<input type="text" class="form-control" id="user_name" name="user_name">
										</div>
										<div class="col-md-6 pl-md-0 mb-3">
											<div class="ano_counselling text-center pt-4">
												<div class="ano_select">
													<h3>Anonyme Beratung?</h3>
													<div class="selection_wrapper">
														<!-- Rounded switch -->
														<label class="switch">
															<input type="checkbox" name="is_anonymous" value="1">
															<span class="slider round"></span>
														</label>
													</div>
													<p class="ano_desc">Wenn du anonyme Beratung auswählst, sieht der Berater nur deinen anonymen Benutzernamen.</p>
												</div>
											</div>
										</div>
										<div class="col-md-12 mb-3">
										    <label for="birth_date">Geburtstag</label>
										    <input type="text" class="form-control" id="birth_date" name="birth_date" autocomplete="off" placeholder="Geburtsdatum auswählen">
										</div>
										<div class="col-md-12 mb-3">
										    <label for="nationality">Nationalität</label>
										    <select name="nationality" id="nationality" class="form-control">
										        <option value="">---Nationalität wählen---</option>
										        @foreach ($countries as $country)
										            <option value="{{$country->code}}">{{$country->name}}</option>
										        @endforeach
										    </select>
										</div>
										<div class="col-md-12 mb-3 addressautocomplete">
                                            <label for="autocomplete">Straße + Hausnummer, Ort</label>
                                            <input type="text" class="form-control" id="autocomplete" placeholder="Musterstraße 14, Musterstadt" autocomplete="false">
                                            <input type="hidden" name="latitude" id="latitude">
                                            <input type="hidden" name="longitude" id="longitude">
                                        </div>
                                        <div class="col-md-12 mt-5 mb-2">
											<h5 class="montbold">Anschrift nicht gefunden? Hier manuell korrigieren:</h5>
                                        </div>
										<div class="col-md-12 mb-3">
											<label for="street">Straße + Hausnummer</label>
											<input type="text" class="form-control" placeholder="Straße" id="street" name="street">
										</div>
										{{-- <div class="col-md-4 pl-md-0 mb-3">
											<label for="house_no">Hausnummer</label>
											<input type="text" class="form-control" placeholder="Hausnummer" id="house_no" name="house_no">
										</div> --}}
										<div class="col-md-4 mb-3">
											<label for="postcode">Postleitzahl</label>
											<input type="text" class="form-control" placeholder="Postleitzahl" id="post_code" name="post_code" readonly>
										</div>
										<div class="col-md-8 pl-md-0 mb-3">
											<label for="place">Ort</label>
											<input type="text" class="form-control" placeholder="Ort" id="place" name="place">
										</div>
										<div class="col-md-12 mb-4">
										    <label>Land</label>
										    <input type="text" class="form-control" name="country" id="country" value="Germany"  readonly>
										    <input type="hidden" name="country_code" id="country_code" value="de">
										</div>
									</div>
								</div>
							</div>
							<div class="tab-pane multi_step_tabone" id="tab2">
								<h3>Zahlungsinformationen</h3>
								<div class="userpayement-wrapper">
									<div class="creditcard-wrapper">
										<div class="credit-title">Kreditkarte</div>
										<div class="creditform row">
											<div class="col-md-5 mb-3 term_wrapper">
											    <div class="term_category">
											        <input type="radio" name="card_type" id="card_type_cb" class="css-checkbox" value="CB_VISA_MASTERCARD" checked />
											        <label for="card_type_cb" class="css-label">CB / VISA / MASTERCARD</label>
											    </div>
											</div>
											<div class="col-md-3 mb-3 term_wrapper">
											    <div class="term_category">
											        <input type="radio" name="card_type" id="card_type_maestro" class="css-checkbox" value="MAESTRO" />
											        <label for="card_type_maestro" class="css-label">MAESTRO</label>
											    </div>
											</div>
											<div class="col-md-3 mb-3 term_wrapper">
											    <div class="term_category">
											        <input type="radio" name="card_type" id="card_type_diners" class="css-checkbox" value="DINERS" />
											        <label for="card_type_diners" class="css-label">DINERS</label>
											    </div>
											</div>
											<div class="col-md-12 mb-3">
												<label for="cardholder">Karteninhaber</label>
												<input type="text" class="form-control" id="cardholder" name="cardholder">
											</div>
											<div class="col-md-12 mb-3">
												<label for="cardnumber">Kartennummer</label>
												<input type="text" class="form-control" id="cardnumber" name="cardnumber">
											</div>
											<div class="col-md-12 mb-3">
												<label>Gültig bis</label>
												<div class="dateofexpiry">
													<div class="creditexiperymonth">
														{{-- <input type="text" class="form-control" id="expirymonth"> --}}
														<select id="expirymonth" class="form-control">
															@for ($i = 1; $i <= 12; $i++)
																<option value="{{ sprintf("%02d", $i) }}">{{ sprintf("%02d", $i) }}</option>
															@endfor
														</select>
													</div>
													<div class="creditexiperyyear">
														{{-- <input type="text" class="form-control" id="expiryyear"> --}}
														@php
															$curr_year = (int) date('y');
															$last_year = (int) date('y', strtotime('+10 years'));
														@endphp
														<select id="expiryyear" class="form-control">
															@while ($curr_year<=$last_year)
																<option value="{{ $curr_year }}">{{ $curr_year }}</option>
																@php $curr_year++; @endphp
															@endwhile
														</select>
													</div>
												</div>
											</div>
											<div class="col-md-12 mb-3">
												<label for="cvv">Sicherheitscode</label>
												<input type="text" class="form-control cvvwrapper" id="cvv" name="cvv">
											</div>
											<div class="col-md-12">
											    <img class="pay_powered" src="{{asset('frontend/img/powered-by-mangopay.png')}}" alt="">
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="tab-pane multi_step_tabone" id="tab3">
								<div class="usercompleteregistration-wrapper">
									<h3>Deine Registrierung</h3>
									<p>Danke für deine Registrierung bei himmlischberaten.de</p>
									<div class="personalregistrationinfo mt-md-5 ">
										@if($appointment)
											<input type="hidden" id="coach_id" value="{{ $appointment['coach_id'] }}">
											<input type="hidden" id="coach_name" value="{{ $appointment['coach']['first_name'].' '.$appointment['coach']['last_name'] }}">
										@endif
										<div class="usertc-wrapper">
											<div class="termswrapper">
												<label class="container123" for="terms_condition">Ich habe die <a target="_blank" href="{{route('agb')}}" class="link">Nutzungsbedingungen</a> gelesen und erkläre mich mit ihnen einverstanden.
													<input type="checkbox" name="terms_condition" class="checkmark" id="terms_condition" value="1">
													<span class="checkmark"></span>
												</label>
											</div>
											<div class="privacywrapper mb-md-5">
												<label class="container123" for="privacy_policy">Ich habe die <a target="_blank" href="{{route('data-protection')}}" class="link">Hinweise zum Datenschutz</a> gelesen und erkläre mich mit ihnen einverstanden.
												<input type="checkbox" name="privacy_policy" class="checkmark" id="privacy_policy" value="1">
												<span class="checkmark"></span>
												</label>	
											</div>
										</div>
									</div>
								</div>
							</div>
							<ul class="col-md-12 pl-0 pr-0 pager wizard">
								<li class="previous"><a href="#">Zurück</a></li>
								<li class="next"><a href="#">Fortfahren</a></li>
								<li class="finish"><a href="javascript:;">{{$type=='guest' || $appointment?'Weiter':'Registrierung absenden'}}</a></li>
								<div class="clearfix"></div>
							</ul>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	
@endsection

@section('scripts')
	<script src="{{ asset('frontend/js/jquery.bootstrap.wizard.js')}}"></script>
	<script src="{{ asset('frontend/js/jquery-ui.min.js') }}"></script>
	<script src="{{ asset('frontend/js/mangopay.min.js')}}"></script>
	<script src="{{ asset('frontend/js/custom/card_payment.js')}}"></script>
	<script>
		var is_appointment = "{{ !is_null($appointment)?'yes':'no' }}";
		
	    $(document).ready(function() {
	    	// Jquery Ui Tooltip
	        $('[data-toggle="tooltip"]').tooltip({
	            position: {'at': 'right+5 top-18'},
	            tooltipClass: 'customtooltip'
	        });

	        var user_type = $("input[name='type']").val();
            $validator = $('#userRegFrm').validate({
			            	errorClass: "is-invalid",
			            	rules:{
			            		type: {
			            			required: true,
			            			nowhitespace: true,
			            		},
			            		first_name:{
			            			required: true,
			            			nowhitespace: true,
			            		},
			            		last_name: {
			            			required: true,
			            			nowhitespace: true,
			            		},
			            		email:{
			            			required:true,
			            			nowhitespace: true,
			            			remote: {
			            				url: baseUrl+'/user/unique-email-check?type='+user_type,
			            			}
			            		},
			            		email_confirmation: {
			            			equalTo: "#email",
			            		},
			            		password: {
			            			required: true,
			            			nowhitespace: true,
			            			minlength: 8,
			            			pwcheck: true
			            		},
			            		password_confirmation: {
			            			equalTo: "#password"
			            		},
			            		user_name: {
	                                required: function(element) {
	                                    return $("input[name='is_anonymous']").is(':checked');
	                                },
	                                nowhitespace: true,
	                                remote: {
	                                	url: baseUrl+'/user/unique-username-check?type='+user_type
	                                }
	                            },
	                            birth_date: {
	                               required: true,
	                               birthdate: true  
	                            },
			            		nationality: {
			            			required: true,
			            			nowhitespace: true,
			            		},
			            		latitude:  {
			            			required: true,
			            			nowhitespace: true,
			            		},
                           		longitude: {
                           			required: true,
                           			nowhitespace: true,
                           		},
			            		street:    {
			            			required: true,
			            			nowhitespace: true,
			            		},
			            		house_no:  {
			            			required: true,
			            			nowhitespace: true,
			            		},
			            		post_code: {
			            			required: true,
			            			nowhitespace: true,
			            		},
			            		place: 	   {
			            			required: true,
			            			nowhitespace: true,
			            		},
			            		country:   {
			            			required: true,
			            			nowhitespace: true,
			            		},
                           		country_code: {
                           			required: true,
                           			nowhitespace: true,
                           		},
			            		terms_condition: {
			            			required: true,
			            			minlength: 1
			            		},
			            		privacy_policy: {
			            			required: true,
			            			minlength: 1
			            		},
			            		card_type: {
			            			required: true,
			            			nowhitespace: true,
			            		},
			            		cardholder: {
			            			required: true,
			            			nowhitespace: true,
			            		},
			            		cardnumber: {
			            			required: true,
			            			nowhitespace: true,
			            		},
			            		cvv: {
			            			required: true,
			            			nowhitespace: true,
			            		},
			            	},
			            	messages: {
			            		email: {
			            			remote: "Email is already taken"
			            		},
			            		email_confirmation: {
			            			equalTo: "Email confirmation does not match"
			            		},
			            		user_name: {
			            			remote: "Username is already taken"
			            		},
			            		password: {
			            			pwcheck: "Password must include at least one capital letter and at least one number"
			            		},
			            		password_confirmation: {
			            			equalTo: "Password confirmation does not match"
			            		},
			            		latitude: {
			            		    required: "Please select a valid Address"
			            		},
			            		longitude: {
			            		    required: "Please select a valid Address"
			            		},
			            	},
			            	errorPlacement: function(error, element){
			            		// Blank so that no error message is shown.
			            		// All Error will be displayed via toastr
			            	},
			            	highlight: function(element) {
			            		var element_name = $(element).attr('name');
			            		if(element_name=='post_code' || element_name=='country' || element_name=='country_code') {
			            			var $add_element = $(element).closest('.row').find('.addressautocomplete input[type=text]');
			            			$add_element.addClass('border border-danger');
			            			$add_element.parent().find('label').addClass('text-danger');
			            		} else {
				            		if($(element).attr('type')=='checkbox') {
				            		    $('input[name="'+element_name+'"]').parent().addClass('text-danger');
				            		}
				            		else {
				            		    $(element).addClass('border border-danger');
				            		}
			            			$(element).parent().find('label').addClass('text-danger');
			            		}
			            	},
			            	unhighlight: function(element) {
			            		var element_name = $(element).attr('name');
	                           	if($(element).attr('type')=='checkbox') {
	                            	$('input[name="'+element_name+'"]').parent().removeClass('text-danger');
	                           	}
	                           	else {
	                            	$(element).removeClass('border border-danger');
	                           	}
			            		$(element).parent().find('label').removeClass('text-danger');
			            	}
			            });

	        // Bootstrap wizard
	        $('#rootwizard').bootstrapWizard({
	        	onPrevious: function(tab, navigation, index, nextIndex) {
	        		if(index==-1) {
	        			if(is_appointment=='yes') {
		        			var coach_id   = $("#coach_id").val();
		        			var coach_name = $("#coach_name").val();
		        			window.location.href = "{{route('coach-detail', ['name'=>'','id' => ''])}}"+"/"+coach_name+"/"+coach_id;
		        			//window.location.href = publicUrl+'/coach-detail/'+coach_id;
	        			} else {
	        				window.history.back();
	        			}
	        		}
	        	},
                onNext: function(tab, navigation, index, nextIndex) {
                    showNext(index)
                    return false;
                },
                onFinish: function () {
                    showNext(4);
                    return false;
                }
            });

            // Disable navigation on number clicks 
            $("#rootwizard .nav-pills a[data-toggle=tab]").on("click", function(e) {
                e.preventDefault();
                return false;
            });

            $("#birth_date").datepicker({
                dateFormat: "yy-mm-dd",
                yearRange: "-100:-18",
                maxDate: "-18Y",
                changeYear:true,
                changeMonth:true,
                showMonthAfterYear: true,
            });

            function showNext(index) {
            	var index = $('#rootwizard').bootstrapWizard('currentIndex')+1;
            	var valid = $("#userRegFrm").valid();
            	if(!valid) {
	                $validator.focusInvalid();
	            	var error_html   = '';
	            	var required_err = false;
	            	$.each($validator.errorMap,function(key, data) {
	            		if(data.includes('field is required') || data.includes('Feld ist ein Pflichtfeld.')) {
	            			if(required_err==false) {
	                			error_html   = 'Bitte füllen Sie alle erforderlichen Felder aus, um fortzufahren. <br>'+error_html;
	                			required_err = true;
	            			}
	            		} else {
	            			//error_html+=key+':'+data+'<br/>';
	            			error_html+=data+'<br/>';
	            		}
	            	});
	                toastr.error(error_html);
	                return false;
            	}
            	updateUserDetails(index);
            }

            function updateUserDetails (index) {
            	console.log(index);
                var form_data = '';
                if(index==1) {
                	$('#rootwizard').bootstrapWizard('show', index);
                	return false;
                }
                /*if(index==2) {
                	createCardRegister();
                	return false;
                }*/ 
                else {
                	form_data = $("#userRegFrm :input").serialize();
                }

                /*if(index==1) {
                    form_data = $('#tab1 :input').serialize();
                } else if(index==2) {
                	createCardRegister();
                	return false;
                }
                else {
                	form_data = $('#tab3 :input').serialize();
                    // $('#rootwizard').bootstrapWizard('show', index);
                    // return true;
                }
                form_data = form_data + '&_token='+$('input[name="_token"]').val();*/
                var user_id = $('input[name="id"]').val();
                if(user_id=='') {
                    var api_url = baseUrl+'/user-register';
                    var method  = 'POST';
                } else {
                    var api_url = baseUrl+'/user/my-profile/update';
                    var method  = 'PUT';
                }
            	showLoader();
                $.ajax({
                    url: api_url,
                    type: method,
                    data: form_data,
                })
                .done(function(data) {
                    console.log("success");
                    if(data.success=="true") {
                    	//if(index==1 && user_id=='') {
                    	if(data.id!=undefined && user_id=='') {
	                        $('input[name="id"]').val(data.id);
                    	}
                        if(index<3) {
                        	// Save Card
                        	createCardRegister();
                            /*$('#rootwizard').bootstrapWizard('show', index);
                            hideLoader();*/
                        }
                        else {
                        	if(is_appointment=='no')
                            	window.location.href = '{{route('user-register/complete')}}';
                            else {
                            	// book an appointment
                            	window.location.href = '{{route('book-coach')}}';
                            }
                        }
                    } else {
                    	hideLoader();
                        alert(data.message);
                    }
                })
                .fail(function(error) {
                    console.log("error");
                    hideLoader();
                    if(error.status==422){
                        ajaxValidationError(error.responseJSON);
                        return false;
                    }else{
                        console.log("Something unexpected happended!");
                        toastr.error("Something unexpected happended!");
                    }
                })
                .always(function() {
                    console.log("complete");
                });
            }
	    });
		
	</script>
	<script>
		var autocomplete;
        function initMap() {
        	var input = document.getElementById('autocomplete');
        	var options = {
	            //types: ['geocode'], //this should work !
	            //region:'EU',
	            //componentRestrictions: {country: "AU"}
	        };
        	autocomplete = new google.maps.places.Autocomplete(input, options);
        	autocomplete.addListener('place_changed', fillInAddress);
        }

	    function fillInAddress () {
	        // Get the place details from the autocomplete object.
	        var place = autocomplete.getPlace();
	        console.log(place);
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
	                    address_obj.city1 = place.address_components[i]["long_name"];
	                    break;
	                case "postal_code":
	                    address_obj.post_code = place.address_components[i]["short_name"];
	                    break;
	                case "locality":
	                    address_obj.place = place.address_components[i]["long_name"];
	                    break;
	                case "street_number":
	                    address_obj.street_no = place.address_components[i]["short_name"];
	                    break;
	                case "route":
	                    address_obj.route = place.address_components[i]["long_name"];
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
            document.getElementById("post_code").value         = address_obj.post_code;
            document.getElementById("country").value           = address_obj.country;
            document.getElementById("country_code").value      = address_obj.country_code;
            document.getElementById("street").value            = address_obj.route+' '+address_obj.street_no;
            if(address_obj.place!='')
            	document.getElementById("place").value  = address_obj.place;
            else if(address_obj.city1!='')
            	document.getElementById("place").value  = address_obj.city1;
	    }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBR5FirOPNOnP9WBqT0ZMBbzyQ8reeVLhI&libraries=places&callback=initMap">
    </script>
@endsection