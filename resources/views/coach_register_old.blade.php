@extends('layouts.app')

@section('style_link')
<link rel="stylesheet" href="{{ asset('frontend/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/dropzone.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/jquery-ui.min.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/bootstrap-slider.min.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/availability.css') }}">
@endsection

@section('content')

	<!-- Modal -->
	<div class="modal fade cerificate_modal" id="certificate_modal" tabindex="-1" role="dialog" aria-hidden="true">
	   <div class="modal-dialog" role="document">
    	    <div class="modal-content">
    	        <div class="modal-header">
    	            <h5 class="modal-title" id="doc_title">Lade dein Zertifikat oder <span>eine offizielle Bestätigung hoch.</span></h5>
    	        </div>
    	        <div class="modal-body">
    	            <p class="upload_info mt-3 mb-5" id="doc_type">Upload von PDF, JPEG und PNG möglich</p>
        			<form action="{{ url('upload-documents') }}" method="POST" id="dropzone" class="dropzone">
                        @csrf
                        <input type="hidden" name="type" value="avatar">
        			    <div class="fallback">
        				    <input name="file" type="file" />
        			    </div>
        			</form>
    			    <button type="button" id="cancel_upload_btn" class="btn stop_modal_btn mt-5">Abbrechen</button>
    			    <button type="button" id="choose_file_btn" class="btn choose_modal_btn mt-5">Auswählen</button>
    	        </div>
        	      <!-- <div class="modal-footer">
        	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        	        <button type="button" class="btn btn-primary">Save changes</button>
        	      </div> -->
    	    </div>
	   </div>
	</div>
	<!-- Modal end-->
	
	<div class="container-fluid ref_steps_container">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-12 col-lg-8" id="rootwizard">
					<form action="get" id="coachRegFrm">
                        @csrf
                        <input type="hidden" name="id" id="id" value="{{@$coach->id}}">
						<div class="navbar">
							<div class="navbar-inner">
								<div class="container">
									<ul class="noclick">
										<li><a href="#tab1" data-toggle="tab">1</a></li>
										<li><a href="#tab2" data-toggle="tab">2</a></li>
										<li><a href="#tab3" data-toggle="tab">3</a></li>
                                        <li><a href="#tab4" data-toggle="tab">4</a></li>
									</ul>
								</div>
							</div>
						</div>
						<div class="tab-content">
							<div class="tab-pane multi_step_tabone" id="tab1">
								<h3>Persönliche Informationen</h3>
								<div class="personal_info_step">
									<div class="row">
										<div class="col-md-6 mb-3">
											<label for="first_name">Vorname</label>
											<input type="text" class="form-control" placeholder="Vorname" id="first_name" name="first_name" value="{{@$coach->first_name}}">
										</div>
										<div class="col-md-6 pl-md-0 mb-3">
											<label for="last_name">Nachname</label>
											<input type="text" class="form-control" placeholder="Nachname" id="last_name" name="last_name" value="{{@$coach->last_name}}">
										</div>
										<div class="col-md-6 mb-3">
											<label for="email">E-Mail Adresse</label>
											<input type="email" class="form-control" placeholder="E-Mail Adresse" id="email" name="email" value="{{@$coach->email}}">
										</div>
                                        <div class="col-md-6 pl-md-0 mb-3">
                                            <label for="email_confirmation">E-Mail wiederholen</label>
                                            <input type="email" class="form-control" placeholder="E-Mail wiederholen" id="email_confirmation" name="email_confirmation" value="{{@$coach->email}}">
                                        </div>
										<div class="col-md-6 mb-3 mb-md-5">
											<label for="password">Passwort festlegen</label>
											<input type="password" class="form-control" placeholder="Passwort festlegen" id="password" name="password">
										</div>
										<div class="col-md-6 pl-md-0 mb-3 mb-md-5">
											<label for="password_confirmation">Passwort bestätigen</label>
											<input type="password" class="form-control" placeholder="Passwort bestätigen" id="password_confirmation" name="password_confirmation">
										</div>
										<div class="col-md-12 mb-3">
											<label for="phone_number">Telefonnummer</label>
											<input type="tel" class="form-control" placeholder="Telefonnummer" id="phone_number" name="phone_number" value="{{@$coach->phone_number}}">
										</div>
                                        <div class="col-md-12 mb-3">
                                            <label for="birth_date">Geburtstag</label>
                                            <input type="text" class="form-control" id="birth_date" name="birth_date" autocomplete="off" placeholder="Geburtsdatum auswählen" value="{{$coach?$coach->birth_date->format('Y-m-d'):''}}">
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="nationality">Nationalität</label>
                                            <input type="text" class="form-control" placeholder="nationality" id="nationality" name="nationality" value="{{@$coach->nationality}}">
                                        </div>
										<div class="col-md-12 mb-3">
                                            <label for="autocomplete_loc">Anschrift</label>
                                            <input type="text" class="form-control" id="autocomplete_loc" placeholder="Straße" autocomplete="false">
                                            <input type="hidden" name="latitude" id="latitude">
                                            <input type="hidden" name="longitude" id="longitude">
                                        </div>
                                        <div class="col-md-8 mb-3">
											<label for="street">Straße</label>
											<input type="text" class="form-control" placeholder="Straße" id="street" name="street" value="{{@$coach->street}}">
										</div>
										<div class="col-md-4 pl-md-0 mb-3">
											<label for="house_no">Hausnummer</label>
											<input type="text" class="form-control" placeholder="Hausnummer" id="house_no" name="house_no" value="{{@$coach->house_no}}">
										</div>
										<div class="col-md-4 mb-3">
											<label for="post_code">Postleitzahl</label>
											<input type="text" class="form-control" placeholder="Postleitzahl" id="post_code" name="post_code" value="{{@$coach->post_code}}" readonly>
										</div>
										<div class="col-md-8 pl-md-0 mb-3">
											<label for="place">Ort</label>
											<input type="text" class="form-control" placeholder="Ort" id="place" name="place" value="{{@$coach->place}}" readonly>
										</div>
                                        <div class="col-md-12 mb-4">
                                            <label>Land</label>
                                            <input type="text" class="form-control" name="country" id="country" value="Germany"  readonly>
                                            <input type="hidden" name="country_code" id="country_code" value="de">
                                        </div>
									</div>
								</div>
								<h3 class="mb-4">Deine Beratung</h3>
								<div class="personal_info_step">
									<div class="row">
                                        <div class="col-md-12 mb-3">
                                            <div class="ano_counselling rounded_cust_switch">
                                                <div class="ano_select">
                                                    <div class="selection_wrapper">
                                                        <!-- Rounded switch -->
                                                        <label class="switch">
                                                            <input type="checkbox" class="period_switch" name="online_coaching" value="1" id="online_coaching" {{!$coach?'checked':($coach->coaching_method=='both' || $coach->coaching_method=='online')?'checked':''}}>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>
                                                    <h6>Online-Beratung</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <div class="ano_counselling rounded_cust_switch">
                                                <div class="ano_select">
                                                    <div class="selection_wrapper">
                                                        <!-- Rounded switch -->
                                                        <label class="switch">
                                                            <input type="checkbox" class="period_switch" name="offline_coaching" value="1" id="offline_coaching" {{(@$coach->coaching_method=='both' || @$coach->coaching_method=='offline')?'checked':''}}>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>
                                                    <h6>Offline-Beratung</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <div class="ano_counselling rounded_cust_switch">
                                                <div class="ano_select">
                                                    <div class="selection_wrapper">
                                                        <!-- Rounded switch -->
                                                        <label class="switch">
                                                            <input type="checkbox" class="period_switch" name="show_on_map" value="1" {{@$coach->show_on_map?'checked':''}}>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>
                                                    <h6>Standort auf der Karte anzeigen</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-4">
                                            <div class="ano_counselling rounded_cust_switch">
                                                <div class="ano_select">
                                                    <div class="selection_wrapper">
                                                        <!-- Rounded switch -->
                                                        <label class="switch">
                                                            <input type="checkbox" class="period_switch offline_advise" id="different_work" name="different_work" value="1" {{@$coach->different_work?'checked':''}} disabled>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>
                                                    <h6>Abweichende Adresse für Offline-Beratung</h6>
                                                </div>
                                            </div>
                                        </div>
									</div>
									<div class="row different_work_div" style="{{@$coach->different_work?'display:flex;':''}}">
                                        <div class="col-md-12 mb-3">
                                            <label for="diff_autocomplete_loc">Search location</label>
                                            <input type="text" class="form-control" id="diff_autocomplete_loc" placeholder="Straße" autocomplete="false">
                                            <input type="hidden" name="work_latitude" id="work_latitude">
                                            <input type="hidden" name="work_longitude" id="work_longitude">
                                        </div>
                                        <div class="col-md-8 mb-3">
											<label for="work_street">Straße</label>
											<input type="text" class="form-control" placeholder="Straße" id="work_street" name="work_street" value="{{ @$coach->work_street }}">
										</div>
										<div class="col-md-4 pl-md-0 mb-3">
											<label for="work_house_no">Hausnummer</label>
											<input type="text" class="form-control" placeholder="Hausnummer" id="work_house_no" name="work_house_no" value="{{ @$coach->work_house_no }}">
										</div>
										<div class="col-md-4 mb-3 mb-md-5">
											<label for="work_post_code">Postleitzahl</label>
											<input type="text" class="form-control" placeholder="Postleitzahl" id="work_post_code" name="work_post_code" value="{{ @$coach->work_post_code }}" readonly>
										</div>
										<div class="col-md-8 pl-md-0 mb-4 mb-md-5">
											<label for="work_place">Ort</label>
											<input type="text" class="form-control" placeholder="Ort" id="work_place" name="work_place" value="{{ @$coach->work_place }}" readonly>
										</div>
                                        <div class="col-md-12 mb-4">
                                            <label>Land</label>
                                            <input type="text" class="form-control" name="work_country" id="work_country" value="Germany" readonly>
                                            <input type="hidden" name="work_country_code" id="work_country_code" value="de">
                                        </div>
									</div>
									<div class="row">
										<div class="col-md-12 mb-5 consulting_price">
											<h6 class="consult_crg">Beratungspreis pro Stunde:</h6>
											<input type="text" class="form-control" id="price_per_hour" name="price_per_hour" value="{{@$coach->price_per_hour}}">
											<h6>€</h6>
                                            <p class="tool_tip" data-toggle="tooltip" data-placement="right" title="Der Betrag ist brutto anzugeben (inkl. Mehrwertsteuer)"><i class="fa fa-question-circle" aria-hidden="true"></i></p>
										</div>
									</div>
								</div>
							</div>
							<div class="tab-pane multi_step_tabone" id="tab2">
								<h3>Ausbildung</h3>
								<div class="personal_info_step">
                                    <div id="company_skeleton" class="d-none">
								        <div class="row company_content">
                                            <div class="col-md-6 mb-3 reg_single_select">
                                                <select class="basic-select2 company_id" >
                                                    <option value="">Ausbildungsträger</option>
                                                    @foreach ($companies as $company)
                                                        @if(strtolower($company->name)=='other')
                                                            @php $other_id = $company->id @endphp
                                                        @else
                                                            <option value="{{$company->id}}">{{$company->name}}</option>
                                                        @endif
                                                    @endforeach
                                                    <option value="{{isset($other_id)?$other_id:1}}">Sonstige</option>
                                                </select>
                                            </div>
    										<div class="col-md-12 mb-3 company_name_div" style="display: none;">
    											<label for="company_name">Name des Ausbildungsträgers</label>
    											<input type="text" class="form-control company_name" placeholder="Ausbildungsträger" id="company_name">
    										</div>
                                            <div class="col-md-12 mb-3">
                                                <label for="training">Abschlusszeitpunkt</label>
                                                <div class="row">
                                                    <div class="col-md-2 mb-3 mb-md-0 reg_single_select completion_point">
                                                        <select class="basic-select2 company_month">
                                                            @for ($i = 1; $i <= 12; $i++)
                                                                <option value="{{$i}}">{{sprintf("%02d", $i)}}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4 mb-md-0 reg_single_select completion_point">
                                                        <select class="basic-select2 company_year">
                                                            @for ($i = date('Y'); $i>=2001; $i--)
                                                                <option value="{{$i}}">{{$i}}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
    										<div class="col-md-12 mb-3">
    											<label for="designation">Offizieller Abschluss / Bezeichnung</label>
    											<input type="text" class="form-control company_designation" placeholder="Offizieller Abschluss / Bezeichnung" >
    										</div>
    										<div class="col-md-12 mb-3 cus_upload_btn">
    											<div class="file-upload">
                                                    <input type="hidden" class="company_doc do-not-ignore" value="">
    												<button type="button" class="btn modal_file_upload" data-type="company_doc">Zertifikat hochladen</button>
    											</div>
    											<p class="nt_visible">Das Zertifikat ist für Nutzer nicht sichtbar.</p>
    										</div>
                                            <div class="col-md-12 mb-3">
                                                <button type="button" class="btn orange_background_btn delete_company">Löschen</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="company_div">
                                        @if($coach)
                                            @foreach ($coach->companies as $coach_company)
                                                @php
                                                    $company_pivot = $coach_company->pivot;
                                                    $is_other = false;
                                                    if(strtolower($coach_company->name)=='other') {
                                                        $is_other = true;
                                                    }
                                                @endphp
                                                <div class="row company_content">
                                                    <div class="col-md-6 mb-3 reg_single_select">
                                                        <select class="basic-select2 company_id" >
                                                            <option value="">Ausbildungsträger</option>
                                                            @foreach ($companies as $company)
                                                                @if(strtolower($company->name)=='other')
                                                                    @php $other_id = $company->id @endphp
                                                                @else
                                                                    <option value="{{$company->id}}" {{$coach_company->id==$company->id?'selected':''}}>{{$company->name}}</option>
                                                                @endif
                                                            @endforeach
                                                            <option value="{{isset($other_id)?$other_id:1}}" {{$is_other?'selected':''}}>Sonstige</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12 mb-3 company_name_div" style="{{!$is_other?'display: none;':''}}">
                                                        <label>Name des Ausbildungsträgers</label>
                                                        <input type="text" class="form-control company_name" placeholder="Ausbildungsträger" value="{{$is_other?$company_pivot->company_name:''}}">
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        @php
                                                            $carbon_joining = Carbon\Carbon::createFromFormat('Y-m-d',$company_pivot->joining_date);
                                                        @endphp
                                                        <label for="training">Abschlusszeitpunkt</label>
                                                        <div class="row">
                                                            <div class="col-md-2 mb-3 mb-md-0 reg_single_select completion_point">
                                                                <select class="basic-select2 company_month">
                                                                    @for ($i = 1; $i <= 12; $i++)
                                                                        <option value="{{$i}}" {{$carbon_joining->format('m')==$i?'selected':''}}>{{sprintf("%02d", $i)}}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4 mb-md-0 reg_single_select completion_point">
                                                                <select class="basic-select2 company_year">
                                                                    @for ($i = date('Y'); $i>=2001; $i--)
                                                                        <option value="{{$i}}" {{$carbon_joining->format('Y')==$i?'selected':''}}>{{$i}}</option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <label for="designation">Offizieller Abschluss / Bezeichnung</label>
                                                        <input type="text" class="form-control company_designation" placeholder="Offizieller Abschluss / Bezeichnung" value="{{$company_pivot->designation}}">
                                                    </div>
                                                    <div class="col-md-12 mb-3 cus_upload_btn">
                                                        <div class="file-upload">
                                                            <input type="hidden" class="company_doc do-not-ignore" data-url="{{FileUploadHelper::getDocPath($company_pivot->document, 'company_doc')}}" value="{{$company_pivot->document}}">
                                                            <button type="button" class="btn modal_file_upload" data-type="company_doc">Zertifikat hochladen</button>
                                                        </div>
                                                        <p class="nt_visible">Das Zertifikat ist für Nutzer nicht sichtbar.</p>
                                                    </div>
                                                    <div class="col-md-12 mb-3">
                                                        <button type="button" class="btn orange_background_btn delete_company">Löschen</button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
									</div>
                                    <div class="row">
                                        <div class="col-md-12 mb-4 pb-4">
                                            <button type="button" class="btn blk_bor_btn mr-3 add_company">+ Weitere Ausbildung</button>
                                        </div>
                                    </div>
								</div>
								<h3 class="mb-3">Kenntnisse</h3>
								<div class="personal_info_step">
									<div class="row">
										<p class="col-md-12 mb-3">Kategorien</p>
                                        @foreach ($categories as $category)
    										<div class="col-md-6 mb-2 know_category">
                                                <label class="container123">{{$category->title}}
                                                    <input type="checkbox" name="categories[]" value="{{$category->id}}" {{$coach_cat->contains($category->id)?'checked':''}}>
                                                    <span class="checkmark"></span>
                                                </label>

                                                {{-- <input type="checkbox" class="css-checkbox" id="cat_chk_{{$category->id}}" name="categories[]" value="{{$category->id}}" {{$coach_cat->contains($category->id)?'checked':''}}>
                                                <label for="cat_chk_{{$category->id}}">{{$category->title}}</label> --}}
    										</div>
                                        @endforeach
										<div class="lang_multi_select col-md-12 mb-3">
                                            <label class="mb-2 mt-1">Sprachen</label>
											<select class="multi-select do-not-ignore" multiple="multiple" id="language" name="language[]">
                                                @foreach ($languages as $language)
                                                    <option value="{{$language}}" {{$coach_lang->contains($language)?'selected':''}}>{{$language}}</option>
                                                @endforeach
											</select>
										</div>
										<div class="lang_multi_select col-md-12 mb-3">
    										<label class="mb-2">Deine Schwerpunkte</label>
    										<div class="priorities_desc">
    											<p class="mb-1">Du kannst Schwerpunkte festlegen, in denen du besondere Kompetenzen bzw.</p>
    											<p class="mb-1">Erfahrungen darstellst. Schwerpunkte schärfen dein Profil und erleichtern es</p>
    											<p class="mb-2">Kunden, dich als  Experten für seine Herausforderungen zu finden.</p>
    										</div>
											<select class="multi-select do-not-ignore" multiple="multiple" id="priorities" name="priorities[]">
                                                @foreach ($priorities as $priority)
                                                    <option value="{{$priority}}" {{$coach_priority->contains($priority)?'selected':''}}>{{$priority}}</option>
                                                @endforeach
											</select>
										</div>
										<div class="col-md-12 mb-3">
											<label for="my_desc">Meine Beschreibung</label>
											<textarea class="form-control" id="description" name="description" rows="5">{{@$coach->description}}</textarea>
										</div>
										<div class="col-md-12 mb-3">
											<label for="club">Gemeinde/Verein (optional)</label>
											<input type="text" class="form-control" placeholder="Gemeinde/Verein" id="community" name="community" value="{{@$coach->community}}">
										</div>
										<p class="col-md-12 mb-3">Profilmedien (optional)</p>
										<div class="cus_upload_btn col-md-12 mb-5">
											<div class="file-upload">
                                                <input type="hidden" name="avatar" @if($coach && $coach->avatar)data-url="{{FileUploadHelper::getDocPath($coach->avatar, 'avatar')}}" value="{{$coach->avatar}}" @endif>
												<button type="button" class="btn modal_file_upload mr-2 ch_file_btn" data-type="avatar">Profilbild hochladen</button>
											</div>
                                            <div class="file-upload">
                                                <input type="hidden" name="video" @if($coach && $coach->video)data-url="{{FileUploadHelper::getDocPath($coach->video, 'video')}}" value="{{$coach->video}}" @endif>
    											<button type="button" class="btn modal_file_upload ch_file_btn" data-type="video">Vorstellungsvideo hochladen</button>
                                            </div>
										</div>
                                        <div class="cus_upload_btn col-md-12 mb-4">
                                            <div class="file-upload">
                                                <input type="hidden" name="banner" @if($coach && $coach->banner)data-url="{{FileUploadHelper::getDocPath($coach->banner, 'banner')}}" value="{{$coach->banner}}"@endif>
                                                <button type="button" class="btn modal_file_upload ch_file_btn" data-type="banner">Titelbild hochladen</button>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-5 term_wrapper">
                                            <div class="term_category">
                                                <label class="container123">Hiermit bestätige ich, dass ich der Rechteinhaber der hochgeladenen Medien bin.
                                                    <input type="checkbox" name="agree_copyright" value="1" {{@$coach->agree_copyright?'checked':''}}>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                        </div>
									</div>
								</div>
							</div>
                            <div class="tab-pane multi_step_tabone" id="tab3">
                                <h3 class="mb-4">Verfügbarkeit</h3>
                                <div class="priorities_desc">
                                    <div class="row">
                                        <p class="col-md-12 mb-1">Die Termine kannst du jederzeit in deinen Profileinstellungen ändern und pflegen.</p>
                                        <p class="col-md-12 mb-3">Verfügbarkeiten sind auch zu einem späteren Zeitpunkt über dein Profil aktualisierbar.</p>
                                        <p class="col-md-12 mb-4 mb-md-5 imp_notice"><span>Wichtig:</span> Sind keine Verfügbarkeiten eingetragen, ist eine Buchung nur durch eine aktive Terminanfrage möglich.</p>
                                    </div>
                                </div>
                                <!-- Availability -->
                                @include('admin.coaches._availability')
                                <!-- Availability End -->
                            </div>
							<div class="tab-pane multi_step_tabone" id="tab4">
								<h3 class="mb-4">Bankinformationen</h3>
                                <div class="personal_info_step">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="owner_name">Kontoinhaber</label>
                                            <input type="text" class="form-control" id="owner_name" name="owner_name" value="{{@$coach->owner_name}}">
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="iban">IBAN</label>
                                            <input type="text" class="form-control" id="iban" name="iban" value="{{@$coach->iban}}">
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="bic">BIC</label>
                                            <input type="text" class="form-control" id="bic" name="bic" value="{{@$coach->bic}}">
                                        </div>
                                    </div>
                                </div>
                                <h3 class="mb-4">Unternehmerinformationen</h3>
                                <div class="personal_info_step">
                                    <div class="row">
                                    </div>
                                </div>
                                <div class="personal_info_step">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <div class="ano_counselling rounded_cust_switch">
                                                <div class="ano_select">
                                                    <div class="selection_wrapper">
                                                        <!-- Rounded switch -->
                                                        <label class="switch">
                                                            <input type="checkbox" class="period_switch" value="1" id="is_commercial_radio" {{$coach?($coach->ust_id?'checked':''):'checked'}}>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>
                                                    <h6>Ich bin gewerblich tätig</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-3 commercial_div" style="{{$coach?(!$coach->ust_id?'display:none;':''):''}}">
                                            <div class="mb-3">
                                                <label for="coach_company">Firmenname</label>
                                                <input type="text" class="form-control" id="coach_company" name="coach_company" value="{{@$coach->coach_company}}" placeholder="Firmenname">
                                            </div>
                                            <label for="ust_id">USt-ID oder Steuernummer</label>
                                            <p class="tool_tip" data-toggle="tooltip" data-placement="right" title="Diese Nummer wird auf der Kundenrechnung angegeben."><i class="fa fa-question-circle" aria-hidden="true"></i></p>
                                            <input type="text" class="form-control" placeholder="UST-ID" id="ust_id" name="ust_id" value="{{@$coach->ust_id}}">
                                            <div class="file-upload btn-tooltip mb-3">
                                                <input type="hidden" class="do-not-ignore" name="ustid_doc" @if($coach && $coach->ustid_doc) data-url="{{FileUploadHelper::getDocPath($coach->ustid_doc, 'ustid_doc')}}" value="{{$coach->ustid_doc}}" @endif>
                                                <button type="button" class="btn blk_bor_btn mt-3 modal_file_upload" data-type="ustid_doc">Gewerbeanmeldung hochladen</button>
                                                <p class="tool_tip" data-toggle="tooltip" data-placement="right" title="Um die Auflagen der Bankenaufsicht zu erfüllen,ist die Bescheinigung der Gewerbetätigkeit erforderlich."><i class="fa fa-question-circle" aria-hidden="true"></i></p>
                                            </div>
                                            <div class="mb-3 term_category">
                                                <label class="container123">Hiermit bestätige ich, dass die verwendete Gewerbeanmeldung aktuell und gültig ist.
                                                    <input type="checkbox" name="agree_ustid" value="1" {{@$coach->agree_ustid?'checked':''}}>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h3 class="mb-4">Identität bestätigen</h3>
                                <div class="personal_info_step">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <div class="file-upload btn-tooltip">
                                                <input type="hidden" class="do-not-ignore" name="id_doc" @if($coach && $coach->id_doc)data-url="{{FileUploadHelper::getDocPath($coach->id_doc, 'id_doc')}}" value="{{$coach->id_doc}}" @endif>
                                                <button type="button" class="btn blk_bor_btn mt-3 modal_file_upload" data-type="id_doc">Ausweisdokument hochladen</button>
                                                <p class="tool_tip" data-toggle="tooltip" data-placement="right" title="Zur Registrierung ist ein Scan der Vorder- und Rückseite deines Personalausweises oder deines Reisepasses erforderlich."><i class="fa fa-question-circle" aria-hidden="true"></i></p>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mb-3 mt-3 term_wrapper">
                                            <div class="mb-3 term_category">
                                                <label class="container123">Ich habe die <a href="javascript:void()">Nutzungsbedingungen</a> gelesen und erkläre mich mit ihnen einverstanden.
                                                    <input type="checkbox" name="terms_condition" value="1" {{@$coach->terms_condition?'checked':''}}>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            <div class="mb-3 term_category">

                                                <label class="container123">Ich habe die <a href="javascript:void()">Hinweise zum Datenschutz</a> gelesen und erkläre mich mit ihnen einverstanden.
                                                    <input type="checkbox" name="privacy_policy" value="1" {{@$coach->privacy_policy?'checked':''}}>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            <div class="mb-3 term_category">
                                                <label class="container123">Ich habe die <a href="javascript:void()">Glaubeasgrundsätze</a> gelesen und erkläre mich mit ihnen einverstanden.
                                                    <input type="checkbox" name="agree_credentials" value="1" {{@$coach->agree_credentials?'checked':''}}>
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
								<li class="finish"><a href="javascript:;">Registrierung absenden</a></li>
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
    <script src="{{ asset('frontend/js/select2.min.js') }}"></script>
    <script src="{{ asset('frontend/js/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('frontend/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('frontend/js/dataTables.responsive.min.js') }}"></script>   	
  	<script src="{{ asset('frontend/js/dropzone.js') }}"></script>
    <script>
        Dropzone.autoDiscover = false;
        /*Dropzone.options.dropzone = false;
        Dropzone.options.dropzone = {
            paramName: "file", // The name that will be used to transfer the file
            maxFiles: 1,
            addRemoveLinks: true,
            maxFilesize: 2, // MB
            init: function() {
            },
        };*/
    </script>
    <script src="{{ asset('frontend/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('frontend/js/datepicker-de.js') }}"></script>
    <script src="{{ asset('frontend/js/bootstrap-slider.min.js') }}"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js" integrity="sha256-4iQZ6BVL4qNKlQ27TExEhBN1HFPvAvAMbFavKKosSWQ=" crossorigin="anonymous"></script> --}}
    <script src="{{ asset('backend/js/availability.js') }}"></script>
    <script src="{{ asset('frontend/js/custom/update_coach_profile.js') }}"></script>
  	<script>
        $(document).ready(function() {

            // Initialize availability module
            AvailabilityModule.initUpdateAvailability();

            // Init Validation
            UpdateCoachModule.initValidation('coachRegFrm');

            $('.add_period').click()

            // Hide unavailability part
            $(".unavailability_div").hide();

            // Bootstrap wizard
            $('#rootwizard').bootstrapWizard({
                onNext: function(tab, navigation, index, nextIndex) {
                    showNext();
                    return false;
                },
                onFinish: function () {
                    showNext();
                    return false;
                }
            });

            // Disable navigation on number clicks 
            /*$("#rootwizard .nav-pills a[data-toggle=tab]").on("click", function(e) {
                e.preventDefault();
                return false;
            });*/

            function showNext() {
                var index = $('#rootwizard').bootstrapWizard('currentIndex')+1;
                UpdateCoachModule.checkValidation('coachRegFrm', function(is_valid) {
                    if(is_valid)
                        updateUserDetails(index);
                });
            }

            function updateUserDetails (index) {
                var form_data = '';
                if(index==1) {
                    form_data = $('#tab1 :input').serialize();
                } else if(index==2) {
                    form_data = $('#tab2 :input').serialize();
                } else if(index==4) {
                    form_data = $('#tab4 :input').serialize();
                } else {
                    $('#rootwizard').bootstrapWizard('show', index);
                    return true;
                }
                form_data = form_data + '&_token='+$('input[name="_token"]').val();
                //$('input[name="id"]').val('8');
                var user_id = $('input[name="id"]').val();
                if(user_id=='') {
                    var api_url = '{{route('coach-register')}}';
                    var method  = 'POST';
                } else {
                    var api_url = baseUrl+'/coach/my-profile/update';
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
                        if(user_id=='')
                            $('input[name="id"]').val(data.id);
                        if(index<4)
                            $('#rootwizard').bootstrapWizard('show', index);
                        else
                            window.location.href = '{{route('coach-register/complete')}}';
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

            @if(@$coach && @$coach->companies->count()>0)
                // Reset company attr fields
                UpdateCoachModule.resetCompanyAttr();
            @else
                // Set first company add fields
                UpdateCoachModule.addNewCompany();
            @endif

        });

    </script>
    <script>
        function initMap() {
            UpdateCoachModule.initAutoComplete();
        }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBR5FirOPNOnP9WBqT0ZMBbzyQ8reeVLhI&libraries=places&callback=initMap">
    </script>
@endsection
