@extends('layouts.app')

@section('style_link')
<link rel="stylesheet" href="{{ asset('frontend/css/jquery-ui.min.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/availability.css') }}">
@endsection

@section('content')
	<div class="coach_dash_wrapper">
		<!-- booking section start -->
		<div class="container-fluid ch_overview_container">
			<div class="container">
				<div class="row">
					<p class="section_head booking_hd me_profil">
                        Mein Profil
                    </p>
                    <p class="section_head booking_hd me_profil">
                        <a href=" {{route('user.bookings')}}">Buchungen</a>
                    </p>
				</div>
                <hr>
			</div>
		</div>
		<!-- booking section end -->

		<!-- my profile tabs -->
		<div class="container-fluid ch_profile_tabs_wrap">
			<div class="container">
				<ul class="row nav nav-pills ch_profile_tabs mb-5 text-center" id="pills-tab" role="tablist">
					<li class="nav-item col-md-6 col-lg-4 pl-md-1 pr-md-1 pl-0 pr-0 mb-1 mb-md-0">
						<a class="nav-link ch_profile_pill_head active" id="pills-personinfo-tab" data-toggle="pill" href="#pills-personinfo" role="tab" aria-controls="pills-personinfo" aria-selected="true">Persönliche Informationen</a>
					</li>
					<li class="nav-item col-md-6 col-lg-4 pl-md-1 pr-md-1 pl-0 pr-0 mb-1 mb-md-0">
						<a class="nav-link ch_profile_pill_head" id="pills-edu-tab" data-toggle="pill" href="#pills-edu" role="tab" aria-controls="pills-edu" aria-selected="false">Zahlungsinformationen</a>
                    </li>
                    <li class="nav-item col-md-2 pl-md-1 pr-md-1 pl-0 pr-0 mb-1 mb-md-0">
                        <a class="nav-link ch_profile_pill_head" id="pills-ischem-tab" data-toggle="pill" href="#pills-ischem" role="tab" aria-controls="pills-ischem" aria-selected="false">Profil Iöschen</a>
                    </li>
				</ul>
				<form action="get" id="userProfileFrm">
    				<div class="tab-content ch_profile_tab_data" id="pills-tabContent">
    					<div class="tab-pane fade show active" id="pills-personinfo" role="tabpanel" aria-labelledby="pills-personinfo-tab">
    						<div class="row">
    							@csrf
    							<h5 class="col-12 ch_tabdata_head">Persönliche Informationen</h5>
    							<div class="col-md-12 col-lg-8 personal_info_step">
    								<div class="row">
    									<div class="col-md-6 mb-3">
    										<label for="first_name">Vorname</label>
    										<input type="text" class="form-control"  id="first_name"  name="first_name" value="{{$user->first_name}}">
    									</div>
    									<div class="col-md-6 mb-3">
    										<label for="last_name">Nachname</label>
    										<input type="text" class="form-control" id="last_name" name="last_name" value="{{ $user->last_name }}">
    									</div>
    									<div class="col-md-6 mb-3">
    										<label for="email">E-Mail</label>
    										<input type="email" class="form-control"  id="email" name="email" value="{{ $user->email }}">
    									</div>
    									<div class="col-md-6 mb-3">
    										<label for="email_confirmation">E-Mail wiederholen</label>
    										<input type="email" class="form-control" id="email_confirmation" name="email_confirmation" value="{{ $user->email }}">
    									</div>
    									<div class="col-md-6 mb-3">
    										<label for="password">
                                                Passwort
                                                <p class="tool_tip" data-toggle="tooltip" data-placement="right" title="Mindestens acht Zeichen, davon mindestens einen Großbuchstaben und mindestens eine Zahl."><i class="fa fa-question-circle" aria-hidden="true"></i></p>
                                            </label>
    										<input type="text" class="form-control" id="password">
    									</div>
    									<div class="col-md-6 mb-3">
    										<label for="password_confirmation">Passwort wiederholen</label>
    										<input type="text" class="form-control" id="password_confirmation" name="password_confirmation" value="{{ $user->password_confirmation }}">
    									</div>
    									<div class="col-md-6 mb-3">
    										<label for="user_name">Benutzername</label>
    										<input type="text" class="form-control" id="user_name" name="user_name" value="{{ $user->user_name }}">
    									</div>
    									<div class="col-md-6 pl-md-0 mb-3 mb-md-5">
    										<div class="ano_counselling pt-0 pt-md-4">
    											<div class="ano_select">
    												<h3 class="mb-4">Anonyme Beratung?</h3>
    												<div class="selection_wrapper">
    													<!-- Rounded switch -->
    													<label class="switch">
    														<input type="checkbox" name="is_anonymous" value="1" {{$user->is_anonymous==1?'checked':''}}>
    														<span class="slider round"></span>
    													</label>
    												</div>
    												<p class="ano_desc">Wenn du anonyme Beratung auswählst, sieht der Berater nur deinen anonymen Benutzernamen.</p>
    											</div>
    										</div>
    									</div>
                                        <div class="col-md-12 mb-3">
                                            <label for="birth_date">Geburtstag</label>
                                            <input type="text" class="form-control" id="birth_date" name="birth_date" autocomplete="off" placeholder="Geburtsdatum auswählen" value="{{ $user->birth_date->format('Y-m-d') }}">
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="nationality">Nationalität</label>
                                            <select name="nationality" id="nationality" class="form-control">
                                                <option value="">---Nationalität wählen---</option>
                                                @foreach ($countries as $country)
                                                    <option value="{{$country->code}}" {{$user->nationality==$country->code?'selected':''}}>{{$country->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-12 mb-3 addressautocomplete">
                                            <label for="autocomplete">Straße + Hausnummer, Ort</label>
                                            <input type="text" class="form-control" id="autocomplete" placeholder="Musterstraße 14, Musterstadt" value="{{$user->street.' '.$user->place}}" autocomplete="false">
                                            <input type="hidden" name="latitude" id="latitude" value="{{$user->latitude}}">
                                            <input type="hidden" name="longitude" id="longitude" value="{{$user->longitude}}">
                                        </div>
                                        <div class="col-md-12 mt-5 mb-2">
                                            <h5 class="montbold">Anschrift nicht gefunden? Hier manuell korrigieren:</h5>
                                        </div>
    									<div class="col-md-12 mb-3">
    										<label for="street">Straße + Hausnummer</label>
    										<input type="text" class="form-control" placeholder="Straße"  id="street" name="street" value="{{ $user->street }}">
    									</div>
    									<div class="col-md-4 mb-5">
    										<label for="post_code">Postleitzahl</label>
    										<input type="text" class="form-control" placeholder="Postleitzahl" id="post_code" name="post_code" value="{{ $user->post_code }}" readonly>
    									</div>
    									<div class="col-md-8 pl-md-0 mb-5">
    										<label for="place">Ort</label>
    										<input type="text" class="form-control" placeholder="Ort" id="place" name="place" value="{{ $user->place }}">
    									</div>
                                        <div class="col-md-12 mb-4">
                                            <label>Land</label>
                                            <input type="text" class="form-control" name="country" id="country" value="Germany"  readonly>
                                            <input type="hidden" name="country_code" id="country_code" value="{{ $user->country_code }}">
                                        </div>
    								</div>
    							</div>
    							<div class="col-md-12 col-lg-8">
    								<button type="button" id="cancelProfileBtn" class="btn blk_bor_btn pull-left">Abbrechen</button>
    								<button type="button" id="updateProfileBtn" class="btn orange_background_btn pull-right">Speichern</button>
                                </div>
    						</div>
    					</div>
    					<div class="tab-pane fade" id="pills-edu" role="tabpanel" aria-labelledby="pills-edu-tab">
    						<div class="row">
    							<h5 class="col-12 ch_tabdata_head">Zahlungsinformationen</h5>
    							<div class="col-md-12 col-lg-8 personal_info_step">
    								<div class="row">
    									<div class="col-md-12 userpayement-wrapper">
                                            @if($card_details)
                                                <div class="creditcard-wrapper">
                                                    <div class="credit-title">Saved Cards</div>
                                                    <div class="crd_saved row">
                                                        <p class="col-md-6"><i class="fa fa-credit-card" aria-hidden="true"></i> {{$card_details->Alias}} <span>{{$card_details->CardProvider}}</span></p>
                                                        <div class="expiry_wrap col-md-6">
                                                            <p class="expiry exp_date mb-2">Exp. {{substr_replace( $card_details->ExpirationDate, '/', 2, 0 )}}</p>
                                                            <p class="expiry validity_date">Validity. {{$card_details->Validity}}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
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
    													<input type="text" class="form-control" id="cardholder">
    												</div>
    												<div class="col-md-12 mb-3">
    													<label for="cardnumber">Kartennummer</label>
    													<input type="text" class="form-control" id="cardnumber">
    												</div>
    												<div class="col-md-12">
    													<label>Gültig bis</label>
    													<div class="dateofexpiry">
    														<div class="creditexiperymonth mb-3">
    															{{-- <input type="text" class="form-control" name="expierymonth"> --}}
                                                                <select id="expirymonth" class="form-control">
                                                                    @for ($i = 1; $i <= 12; $i++)
                                                                        <option value="{{ sprintf("%02d", $i) }}">{{ sprintf("%02d", $i) }}</option>
                                                                    @endfor
                                                                </select>
    														</div>
    														<div class="creditexiperyyear mb-3">
    															{{-- <input type="text" class="form-control" name="expieryyear"> --}}
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
    													<input type="text" class="form-control cvvwrapper" id="cvv">
    												</div>
                                                    <div class="col-md-12">
                                                        <img class="pay_powered" src="{{asset('frontend/img/powered-by-mangopay.png')}}" alt="">
                                                    </div>
    											</div>
    										</div>
    									</div>
    								</div>
    							</div>
    							<div class="col-md-12 col-lg-8">
    								<button type="button" class="btn blk_bor_btn pull-left">Abbrechen</button>
    								<button type="button" id="updateCardBtn" class="btn orange_background_btn pull-right">Speichern</button>
    							</div>
    						</div>
                        </div>
                        <div class="tab-pane fade" id="pills-ischem" role="tabpanel" aria-labelledby="pills-ischem-tab">
                            <div class="row">
                                <h5 class="col-12 ch_tabdata_head">Profil Iöschen</h5>
                                <p class="col-md-12 col-lg-8 mb-3 coach_tab_para">Vielen Dank, dass du himmlischberaten.de nutzt und somit vielen Menschen auf Ihrem Lebensweg hilfst. Natürlich kannst du dich ganz einfach online wieder von dieser Plattform abmelden. Gib hierzu dein Passwort ein und Iösche dein Profil über den Button „Profil löschen". Alle deine Daten werden unwiderruflich gelöscht. Sollten Beratungstermine noch offen sein, werden diese automatisch storniert. Solltest du später himmlischberaten.de wieder nutzen wollen, freuen wir uns auf deine Registrierung.</p>
                                <div class="col-md-12 col-lg-8 personal_info_step">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="existing_password">Passwort eingeben</label>
                                            <input type="password" class="form-control" id="existing_password" name="existing_password">
                                        </div>
                                        <div class="col-md-12 mb-3 term_wrapper">
                                            <div class="mb-3 term_category">
                                                <label class="container123">Ich möchte mein Profil und alle zugehörigen Daten unwiderruflich löschen.
                                                    <input type="checkbox" name="delete_profile" value="1">
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-8">
                                    <button type="button" class="btn blk_bor_btn pull-left">Abbrechen</button>
                                    <button type="button" class="btn orange_background_btn pull-right deleteBtn">Profil Iöschen</button>
                                </div>
                            </div>
                        </div>
    				</div>
                </form>
			</div>
		</div>
		<!-- my profile tabs end -->
	</div>
@endsection

@section('scripts')
    <script src="{{ asset('frontend/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('frontend/js/mangopay.min.js')}}"></script>
    <script src="{{ asset('frontend/js/custom/card_payment.js')}}"></script>
	<script>
	    $(document).ready(function() {
            // Jquery Ui Tooltip
            $('[data-toggle="tooltip"]').tooltip({
                position: {'at': 'right+5 top-18'},
                tooltipClass: 'customtooltip'
            });
            
            // jQuery Validator
            $validator = $('#userProfileFrm').validate({
			            	errorClass: "is-invalid",
			            	rules:{
			            		first_name:"required",
			            		last_name: "required",
			            		email:{
			            			required:true,
			            			remote: {
                                      url: publicUrl+'/user/unique-email-check/',
                                    }
			            		},
			            		email_confirmation: {
			            			equalTo: "#email",
			            		},
			            		password: {
			            			required: true,
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
                                    remote: {
                                        url: baseUrl+'/user/unique-username-check/',
                                    }
                                },
                                birth_date: {
                                   required: true,
                                   birthdate: true  
                                },
                                nationality: "required",
                                latitude:  "required",
                                longitude: "required",
			            		street:    "required",
			            		house_no:  "required",
			            		post_code: "required",
			            		place: 	   "required",
                                existing_password: "required",
                                delete_profile: "required",
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

            $("#birth_date").datepicker({
                dateFormat: "yy-mm-dd",
                yearRange: "-100:-18",
                maxDate: "-18Y",
                changeYear:true,
                changeMonth:true,
                showMonthAfterYear: true,
            });

            $('#updateProfileBtn').click(function(event) {
            	updateUserDetails();
            });

            $("#cancelProfileBtn").click(function(event) {
            	location.reload();
            });

            function showValidationErrors () {
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
            }

            function updateUserDetails () {
                var valid = $("#userProfileFrm").valid();
                if(!valid) {
                	showValidationErrors();
                    return false;
                }

                var form_data = '';
                form_data     = $('#userProfileFrm :input').serialize();
                form_data     = form_data + '&_token='+$('input[name="_token"]').val();
                var api_url   = baseUrl+'/my-profile/update';
                var method    = 'PUT';

                showLoader();
                $.ajax({
                    url: api_url,
                    type: method,
                    data: form_data,
                })
                .done(function(data) {
                    console.log("success");
                    if(data.success=="true") {
                    	toastr.success('Profile updated successfully');
                    } else {
                    	toastr.error(data.message);
                    }
                })
                .fail(function(error) {
                    console.log("error");
                    if(error.status==422){
                        ajaxValidationError(error.responseJSON)
                    }else{
                        console.log(error);
                        toastr.error("Something unexpected happended!"); 
                    }
                })
                .always(function() {
                    console.log("complete");
                    hideLoader();
                });
            }

            $("#updateCardBtn").click(function(event) {
                createCardRegister();
            });

            // Delete profile
            $(".deleteBtn").click(function(event) {
                var valid = $("#userProfileFrm").valid();
                if(valid) {
                    // Confirm Deletion
                    bootbox.confirm({
                        message: "Möchtest du dein Profil auf himmlischberaten.de löschen? Diese Aktion kann nicht rückgängig werden.",
                        backdrop: true,
                        buttons: {
                            confirm: {
                                label: 'Profil löschen',
                                className: 'btn-danger'
                            },
                            cancel: {
                                label: 'Abbrechen',
                                className: 'btn-success'
                            }
                        },
                        callback: function (result) {
                            if(result)
                                deleteUserProfile();
                        }
                    });
                }
            });

            function deleteUserProfile () {
                showLoader();
                $.ajax({
                    url: baseUrl+'/my-profile',
                    type: 'POST',
                    data: {
                            existing_password: $('input[name=existing_password]').val(),
                            _method: 'DELETE', 
                            _token: $('input[name="_token"]').val()
                        },
                })
                .done(function(data) {
                    console.log("success");
                    if(data.success=="true") {
                        toastr.success('Profile deleted succesfully');
                        setTimeout(function(){
                            location.href = publicUrl+'/login'
                        }, 1000);
                    } else {
                        toastr.error(data.message);
                    }
                })
                .fail(function(error) {
                    console.log("error");
                    console.log(error);
                    if(error.status==422){
                        ajaxValidationError(error.responseJSON);
                        return false;
                    }else{
                        toastr.error("Something unexpected happended!");
                    }
                })
                .always(function() {
                    console.log("complete");
                    hideLoader();
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