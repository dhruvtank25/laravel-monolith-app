@extends('layouts.app')

@section('style_link')
    <link rel="stylesheet" href="{{ asset('frontend/css/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/dropzone.css') }}">
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
                        <div class="row">
                            <div class=" dropzone-previews">
                                <div class=" dz-message"></div>
                            </div>
                            
                        </div>
                        <div class="fallback">
                            <input name="file" type="file" />
                        </div>
                    </form>
                    <button type="button" id="cancel_upload_btn" class="btn stop_modal_btn mt-5">Abbrechen</button>
                    <button type="button" id="save_file_btn" class="btn choose_modal_btn mt-5">Speichern</button>
                </div>
                  <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                  </div> -->
            </div>
       </div>
    </div>
    <!-- Modal end-->

    <!-- my profile tabs -->
    <div class="container-fluid ch_profile_tabs_wrap">
        <div class="container">
            <ul class="row nav nav-pills ch_profile_tabs mb-5 text-center" id="pills-tab" role="tablist">
                <li class="nav-item col-md-3 pl-md-1 pr-md-1 pl-0 pr-0 mb-1 mb-md-0">
                    <a class="nav-link ch_profile_pill_head active" id="pills-personinfo-tab" data-toggle="pill" href="#pills-personinfo" role="tab" aria-controls="pills-personinfo" aria-selected="true">Persönliche Informationen</a>
                </li>
                <li class="nav-item col-md-2 pl-md-1 pr-md-1 pl-0 pr-0 mb-1 mb-md-0">
                    <a class="nav-link ch_profile_pill_head" id="pills-companies-tab" data-toggle="pill" href="#pills-companies" role="tab" aria-controls="pills-companies" aria-selected="true">Unternehmen</a>
                </li>
                <li class="nav-item col-md-2 pl-md-1 pr-md-1 pl-0 pr-0 mb-1 mb-md-0">
                    <a class="nav-link ch_profile_pill_head" id="pills-bnkinfo-tab" data-toggle="pill" href="#pills-bnkinfo" role="tab" aria-controls="pills-bnkinfo" aria-selected="false">Bank Informationen</a>
                </li>
                <li class="nav-item col-md-3 pl-md-1 pr-md-1 pl-0 pr-0 mb-1 mb-md-0">
                    <a class="nav-link ch_profile_pill_head" id="pills-edu-tab" data-toggle="pill" href="#pills-edu" role="tab" aria-controls="pills-edu" aria-selected="false">Ausbildung & Kentnisse</a>
                </li>
                <li class="nav-item col-md-2 pl-md-1 pr-md-1 pl-0 pr-0 mb-1 mb-md-0">
                    <a class="nav-link ch_profile_pill_head" id="pills-ischem-tab" data-toggle="pill" href="#pills-ischem" role="tab" aria-controls="pills-ischem" aria-selected="false">Profil Iöschen</a>
                </li>
            </ul>
            <form action="get" id="userProfileFrm">
                <div class="tab-content ch_profile_tab_data" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-personinfo" role="tabpanel" aria-labelledby="pills-personinfo-tab">
                        <div class="row">
                            <h5 class="col-12 ch_tabdata_head">Persönliche Informationen</h5>
                            <div class="col-md-12 col-lg-8 personal_info_step">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="first_name">Vorname</label>
                                        <input type="text" class="form-control" placeholder="Vorname" id="first_name" name="first_name" value="{{$coach->first_name}}">
                                    </div>
                                    <div class="col-md-6 pl-md-0 mb-3">
                                        <label for="last_name">Nachname</label>
                                        <input type="text" class="form-control" placeholder="Nachname" id="last_name" name="last_name" value="{{$coach->last_name}}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email">E-Mail Adresse</label>
                                        <input type="email" class="form-control" placeholder="E-Mail Adresse" id="email" name="email" value="{{$coach->email}}">
                                    </div>
                                    <div class="col-md-6 pl-md-0 mb-3">
                                        <label for="phone_number">Telefonnummer</label>
                                        <input type="tel" class="form-control" placeholder="Telefonnummer" id="phone_number" name="phone_number" value="{{$coach->phone_number}}">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="birth_date">Geburtstag</label>
                                        <input type="text" class="form-control" id="birth_date" name="birth_date" autocomplete="off" placeholder="Geburtsdatum auswählen" value="{{$coach->birth_date->format('Y-m-d')}}">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="nationality">Nationalität</label>
                                        <select name="nationality" id="nationality" class="form-control">
                                            <option value="">---Nationalität wählen---</option>
                                            @foreach ($countries as $country)
                                                <option value="{{$country->code}}" {{$coach->nationality==$country->code?'selected':''}}>{{$country->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <h5 class="col-12 ch_tabdata_head">Passwort ändern <p class="tool_tip" data-toggle="tooltip" data-placement="right" title="Mindestens acht Zeichen, davon mindestens einen Großbuchstaben und mindestens eine Zahl."><i class="fa fa-question-circle font20" aria-hidden="true"></i></p></h5>
                            <div class="col-md-12 col-lg-8 personal_info_step">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="password">Neues Passwort</label>
                                        <input type="password" class="form-control" placeholder="********" id="password" name="password">
                                    </div>
                                    <div class="col-md-6 pl-md-0 mb-3">
                                        <label for="password_confirmation">Neues Passwort wiederholen</label>
                                        <input type="password" class="form-control" placeholder="********" id="password_confirmation" name="password_confirmation">
                                    </div>
                                    <div class="col-md-6 mb-3 mb-md-5">
                                        <label for="old_password">Altes Passwort eingeben</label>
                                        <input type="password" class="form-control" placeholder="********" id="old_password" name="old_password">
                                    </div>
                                    {{-- <div class="col-md-6 mb-3 mb-md-5">
                                        <button type="button" class="btn blk_bor_btn mt-2 mt-lg-4">Passwort ändern</button>
                                    </div> --}}
                                </div>
                            </div>
                            <h5 class="col-12 ch_tabdata_head mb-2">Identität bestätigen</h5>
                            <div class="col-md-12 col-lg-8 personal_info_step">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <div class="file-upload btn-tooltip">
                                            <input type="hidden" class="do-not-ignore" name="id_doc" data-url="{{FileUploadHelper::getMultipleDocPath($coach->id_doc, 'id_doc')}}" value="{{$coach->id_doc}}">
                                            <button type="button" class="btn blk_bor_btn mt-3 modal_file_upload" data-type="id_doc">Ausweisdokument hochladen</button>
                                            <p class="tool_tip" data-toggle="tooltip" data-placement="right" title="Zur Registrierung ist ein Scan der Vorder- und Rückseite deines Personalausweises oder deines Reisepasses erforderlich."><i class="fa fa-question-circle font20" aria-hidden="true"></i></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Futher stuffs not required in this tab -->
                            <div class="col-md-12 col-lg-8">
                                <button type="button" class="btn blk_bor_btn pull-left">Abbrechen</button>
                                <button type="button" class="btn orange_background_btn pull-right updateBtn">Speichern</button>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade show" id="pills-companies" role="tabpanel" aria-labelledby="pills-companies-tab">
                        <h5 class="col-12 ch_tabdata_head">Unternehmerinformationen</h5>
                        <div class="col-md-12 col-lg-8 personal_info_step">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="coach_company">Firmenname</label>
                                    <input type="text" class="form-control" id="coach_company" name="coach_company" value="{{$coach->coach_company}}" placeholder="Firmenname">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3 addressautocomplete">
                                    <label for="autocomplete_loc">Straße + Hausnummer, Ort</label>
                                    <input type="text" class="form-control" id="autocomplete_loc" placeholder="Musterstraße 14, Musterstadt" autocomplete="false" value="{{$coach->street.' '.$coach->place}}">
                                    <input type="hidden" class="do-not-ignore" name="latitude" id="latitude" value="{{$coach->latitude}}">
                                    <input type="hidden" class="do-not-ignore" name="longitude" id="longitude" value="{{$coach->longitude}}">
                                </div>
                                <div class="col-md-12 mt-5 mb-2">
                                    <h5 class="montbold">Anschrift nicht gefunden? Hier manuell korrigieren:</h5>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="street">Straße + Hausnummer</label>
                                    <input type="text" class="form-control" placeholder="Musterstraße 14" id="street" name="street" value="{{$coach->street}}">
                                </div>
                                {{-- <div class="col-md-4 pl-md-0 mb-3">
                                    <label for="house_no">Hausnummer</label>
                                    <input type="text" class="form-control" placeholder="Hausnummer" id="house_no" name="house_no" value="{{$coach->house_no}}">
                                </div> --}}
                                <div class="col-md-4 mb-3">
                                    <label for="post_code">Postleitzahl</label>
                                    <input type="text" class="form-control" placeholder="12345" id="post_code" name="post_code" value="{{$coach->post_code}}" readonly>
                                </div>
                                <div class="col-md-8 pl-md-0 mb-3">
                                    <label for="place">Ort</label>
                                    <input type="text" class="form-control" placeholder="Musterstadt" id="place" name="place" value="{{$coach->place}}">
                                </div>
                                <div class="col-md-12 mb-4">
                                    <label>Land</label>
                                    <input type="text" class="form-control" name="country" id="country" value="{{ $coach->country }}" readonly>
                                    <input type="hidden" class="do-not-ignore" name="country_code" id="country_code" value="{{$coach->country_code}}">
                                </div>
                            </div>
                            <div class="row">
                                @if($coach->person_type=='business' || !$coach->mango_user_id || ($coach->mango_user_id && $coach->is_commercial))
                                    <!-- Company/Person Type --> 
                                    <div class="col-md-4 know_category">
                                       <input class="css-checkbox" type="radio" name="person_type" value="soletrader" id="person_soletrader" {{$coach->person_type=='soletrader'?'checked':''}}>
                                       <label class="css-label tabheading" for="person_soletrader">Einzelunternehmer</label>
                                    </div>
                                    <div class="col-md-4 know_category">  
                                        <input class="css-checkbox " type="radio" name="person_type" value="business" id="person_business" {{$coach->person_type=='business'?'checked':''}}>
                                        <label class="css-label tabheading" for="person_business">Kapitalgesellschaft</label>
                                    </div>
                                @endif
                                
                                <!-- UST ID/TAX NUMBER -->
                                <div class="col-md-12">
                                    <div class="mb-3 mt-3 know_category">
                                        <label class="container123 tabheading">Ich nutze die Kleinunternehmerregelung <p class="tool_tip" data-toggle="tooltip" data-placement="right" title="Bei Auswahl wird keine Umsatzsteuer auf der Rechnung ausgewiesen."><i class="fa fa-question-circle font20" aria-hidden="true"></i></p>
                                            <input type="checkbox" name="small_business" value="1" {{($coach->small_business || !$coach->is_commercial)?'checked':''}}>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="tax_number">
                                        Steuernummer
                                        <p class="tool_tip" data-toggle="tooltip" data-placement="right" title="Diese Nummer wird auf der Kundenrechnung angegeben."><i class="fa fa-question-circle font20" aria-hidden="true"></i></p>
                                    </label>
                                    <input type="text" class="form-control" placeholder="Steuernummer" id="tax_number" name="tax_number" value="{{$coach->tax_number}}">
                                </div>
                                <div class="col-md-2">
                                    <label class="oder">oder</label>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="ust_id">
                                        USt-ID
                                        <p class="tool_tip" data-toggle="tooltip" data-placement="right" title="Diese Nummer wird auf der Kundenrechnung angegeben."><i class="fa fa-question-circle font20" aria-hidden="true"></i></p>
                                    </label>
                                    <input type="text" class="form-control" placeholder="UST-ID" id="ust_id" name="ust_id" value="{{$coach->ust_id}}">
                                </div>

                                <!-- SoleTrader -->
                                <div class="col-md-12 soletrader_container enizelunternehmer_data {{$coach->person_type!='soletrader' && $coach->person_type!=null?'hidden':''}}">
                                    @if(!$coach->mango_user_id)
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <div class="ano_counselling rounded_cust_switch">
                                                <div class="ano_select">
                                                    <div class="selection_wrapper">
                                                        <!-- Rounded switch -->
                                                        <label class="switch">
                                                            <input type="checkbox" class="period_switch" name="is_commercial" value="1" id="is_commercial" {{($coach->is_commercial)?'checked':''}}>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>
                                                    <h6>Gewerbliche Tätigkeit <p class="tool_tip" data-toggle="tooltip" data-placement="right" title="Sollten Sie keine gewerbliche Tätigkeit ausüben, bitte die Kleinunternehmerregelung auswählen, damit keine Umsatzsteuer auf der Rechnung ausgewiesen wird, sofern dies für Sie zutreffend ist."><i class="fa fa-question-circle font20" aria-hidden="true"></i></p></h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    {{-- <div class="mb-3 mt-3 know_category">
                                        <label class="container123 tabheading">Ich nutze die Kleinunternehmerregelung
                                            <input type="checkbox" name="small_business"value="1" checked="">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="street">Steuernummer</label>
                                            <input type="text" class="form-control" placeholder="Steuernummer" id="tax_number_soletrader" name="tax_number">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="oder">oder</label>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label>
                                                USt-ID
                                                <p class="tool_tip" data-toggle="tooltip" data-placement="right" title="Diese Nummer wird auf der Kundenrechnung angegeben."><i class="fa fa-question-circle font20" aria-hidden="true"></i></p>
                                            </label>
                                            <input type="text" class="form-control" placeholder="UST-ID" id="ust_id_soletrader" name="ust_id" value="{{$coach->ust_id}}">
                                        </div>
                                        <div class="col-md-12 file-upload btn-tooltip mb-3">
                                            <input type="hidden" name="ustid_doc">
                                            <button type="button" class="btn blk_bor_btn mt-3 modal_file_upload" data-type="ustid_doc">Gewerbeanmeldung hochladen</button>
                                            <p class="tool_tip" data-toggle="tooltip" data-placement="right" title="Um die Auflagen der Bankenaufsicht zu erfüllen,ist die Bescheinigung der Gewerbetätigkeit erforderlich."><i class="fa fa-question-circle font20" aria-hidden="true"></i></p>
                                        </div>
                                        <div class="col-md-12 mb-3 term_category">
                                            <label class="container123">Hiermit bestätige ich, dass die verwendete Gewerbeanmeldung aktuell und gültig ist.
                                                <input type="checkbox" name="agree_ustid" value="1" checked="">
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </div> --}}
                                </div>
                                <!-- Business -->
                                <div class="col-md-12 business_container kapitalgesellschaft  {{$coach->person_type!='business'?'hidden':''}}">
                                    <div class="row"> 
                                        <div class="col-md-8 mb-4 mt-4">
                                            <label>Unternehmensform</label>
                                            <select name="company_type" id="input" class="form-control">
                                               <option value="llc" {{$coach->company_type=='llc'?'selected':''}}>Gesellschaft mit beschränkter Haftung (GmbH)</option>
                                               <option value="enterpreneur" {{$coach->company_type=='enterpreneur'?'selected':''}}>Unternehmergesellschaft UG (haftungsbeschränkt)</option>
                                               <option value="joint stock" {{$coach->company_type=='joint stock'?'selected':''}}>Aktiengesellschaft (AG)</option>
                                               <option value="open trading" {{$coach->company_type=='open trading'?'selected':''}}>Offene Handelsgesellschaft (OHG)</option>
                                               <option value="limited partnership" {{$coach->company_type=='limited partnership'?'selected':''}}>Kommanditgesellschaft (KG)</option>
                                               <option value="gmbh" {{$coach->company_type=='gmbh'?'selected':''}}>GmbH & Co. KG</option>
                                            </select>
                                        </div>
                                        {{-- <div class="col-md-8 mb-3 mt-3 know_category">
                                            <label class="container123 tabheading">Ich nutze die Kleinunternehmerregelung
                                                <input type="checkbox" name="small_business"value="1" checked="">
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                        <div class="col-md-12"></div>
                                        <div class="col-md-4 mb-3">
                                            <label for="street">Steuernummer</label>
                                            <input type="text" class="form-control" placeholder="Steuernummer" id="tax_number_business" name="tax_number">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="oder">oder</label>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                          <label>
                                                USt-ID
                                                <p class="tool_tip" data-toggle="tooltip" data-placement="right" title="Diese Nummer wird auf der Kundenrechnung angegeben."><i class="fa fa-question-circle font20" aria-hidden="true"></i></p>
                                            </label>
                                            <input type="text" class="form-control" placeholder="UST-ID" id="ust_id_business" name="ust_id" value="{{$coach->ust_id}}">
                                        </div> --}}
                                        <div class="col-md-12">
                                            <label for="company_number">Handelsregisternummer <p class="tool_tip" data-toggle="tooltip" data-placement="right" title="Z.B. HRA123456 (ohne Leerzeichen)"><i class="fa fa-question-circle font20" aria-hidden="true"></i></p></label>
                                            <input type="text" class="form-control" placeholder="Handelsregisternummer" name="company_number" id="company_number" value="{{$coach->company_number}}">
                                        </div>
                                        <div id="shareholder_skeleton" class='d-none'>
                                            <div class="nomines_section">
                                                <h5 class="col-md-12 mt-5 mb-3">Gesetzlicher Vertreter <span class="nominee_no"></span></h5>
                                                <div class="row m-md-0">
                                                    <div class="col-md-6 mt-2 mb-2">
                                                        <label>Vorname</label>
                                                        <input type="text" class="form-control shareholder_firstname"  placeholder="Vorname">
                                                    </div>
                                                     <div class="col-md-6 mt-2 mb-2">
                                                        <label>Nachname</label>
                                                        <input type="text" class="form-control shareholder_lastname" placeholder="Nachname">
                                                    </div>
                                                    <div class="col-md-12 mt-2 mb-2">
                                                        <label>Straße + Hausnummer</label>
                                                        <input type="text" class="form-control shareholder_address" placeholder="Musterstraße 14">
                                                    </div>
                                                    <div class="col-md-4 mt-2 mb-3">
                                                        <label>Postleitzahl</label>
                                                        <input type="text" class="form-control shareholder_postcode" placeholder="12345">
                                                    </div>
                                                    <div class="col-md-8 mt-2 mb-2">
                                                        <label>Ort</label>
                                                        <input type="text" class="form-control shareholder_place" placeholder="Musterstadt">
                                                    </div>
                                                    <div class="col-md-6 mt-2 mb-2">
                                                        <label>Land</label>
                                                        <select class="form-control shareholder_country">
                                                            <option value="">---Nationalität wählen---</option>
                                                            @foreach ($countries as $country)
                                                                <option value="{{$country->code}}">{{$country->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6 mt-2 mb-2">
                                                        <label>Nationalität</label>
                                                        <select class="form-control shareholder_nationality">
                                                            <option value="">---Nationalität wählen---</option>
                                                            @foreach ($countries as $country)
                                                                <option value="{{$country->code}}">{{$country->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12 mt-2 mb-2">
                                                        <label>Geburtstag</label>
                                                        <input type="text" class="form-control shareholder_birthdate" placeholder="Geburtstag">
                                                    </div>
                                                    <div class="col-md-6 mt-2 mb-2">
                                                        <label>Geburtsort</label>
                                                        <input type="text" class="form-control shareholder_birthplace" placeholder="Geburtsort">
                                                    </div>
                                                    <div class="col-md-6 mt-2 mb-2">
                                                        <label>Geburtsland</label>
                                                        <select class="form-control shareholder_birthcountry">
                                                            <option value="">---Nationalität wählen---</option>
                                                            @foreach ($countries as $country)
                                                                <option value="{{$country->code}}">{{$country->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mt-2 mb-4">
                                                    <button type="button" class="btn orange_background_btn delete_nomine">Löschen</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="shareloder_div">
                                            <!-- Check for shareholder already saved in DB  -->
                                            @foreach($coach->shareholders as $shareholder)
                                                <div class="nomines_section">
                                                    <h5 class="col-md-12 mt-5 mb-3">Gesetzlicher Vertreter <span class="nominee_no"></span></h5>
                                                    <div class="row m-md-0">
                                                        <div class="col-md-6 mt-2 mb-2">
                                                            <label>Vorname</label>
                                                            <input type="text" class="form-control shareholder_firstname"  placeholder="Vorname" value="{{$shareholder->first_name}}">
                                                        </div>
                                                         <div class="col-md-6 mt-2 mb-2">
                                                            <label>Nachname</label>
                                                            <input type="text" class="form-control shareholder_lastname" placeholder="Nachname" value="{{$shareholder->last_name}}">
                                                        </div>
                                                        <div class="col-md-12 mt-2 mb-2">
                                                            <label>Straße + Hausnummer</label>
                                                            <input type="text" class="form-control shareholder_address" placeholder="Musterstraße 14" value="{{$shareholder->street}}">
                                                        </div>
                                                        <div class="col-md-4 mt-2 mb-3">
                                                            <label>Postleitzahl</label>
                                                            <input type="text" class="form-control shareholder_postcode" placeholder="12345" value='{{$shareholder->post_code}}'>
                                                        </div>
                                                        <div class="col-md-8 mt-2 mb-2">
                                                            <label>Ort</label>
                                                            <input type="text" class="form-control shareholder_place" placeholder="Musterstadt" value="{{$shareholder->place}}">
                                                        </div>
                                                        <div class="col-md-6 mt-2 mb-2">
                                                            <label>Land</label>
                                                            <select class="form-control shareholder_country">
                                                                <option value="">---Nationalität wählen---</option>
                                                                @foreach ($countries as $country)
                                                                    <option value="{{$country->code}}" {{$country->code==$shareholder->country?'selected':''}}>{{$country->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 mt-2 mb-2">
                                                            <label>Nationalität</label>
                                                            <select class="form-control shareholder_nationality">
                                                                <option value="">---Nationalität wählen---</option>
                                                                @foreach ($countries as $country)
                                                                    <option value="{{$country->code}}" {{$country->code==$shareholder->nationality?'selected':''}}>{{$country->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-12 mt-2 mb-2">
                                                            <label>Geburtstag</label>
                                                            <input type="text" class="form-control shareholder_birthdate" placeholder="Geburtstag" value="{{$shareholder->birth_date->format('Y-m-d')}}">
                                                        </div>
                                                        <div class="col-md-6 mt-2 mb-2">
                                                            <label>Geburtsort</label>
                                                            <input type="text" class="form-control shareholder_birthplace" placeholder="Geburtsort" value="{{$shareholder->birth_place}}">
                                                        </div>
                                                        <div class="col-md-6 mt-2 mb-2">
                                                            <label>Geburtsland</label>
                                                            <select class="form-control shareholder_birthcountry">
                                                                <option value="">---Nationalität wählen---</option>
                                                                @foreach ($countries as $country)
                                                                    <option value="{{$country->code}}" {{$country->code==$shareholder->birth_land?'selected':''}}>{{$country->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mt-2 mb-4">
                                                        <button type="button" class="btn orange_background_btn delete_nomine">Löschen</button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="add_nomines_section mt-3 mb-4 col-md-12">
                                           <button class="btn addnominesbtn">+ Weiterer Vertreter</button>
                                        </div> 
                                    </div>
                                    <div class="row">
                                        {{-- <div class="col-md-12 file-upload btn-tooltip mb-3">
                                            <input type="hidden" name="ustid_doc">
                                            <button type="button" class="btn blk_bor_btn mt-3 modal_file_upload" data-type="ustid_doc">Gewerbeanmeldung hochladen</button>
                                            <p class="tool_tip" data-toggle="tooltip" data-placement="right" title="Um die Auflagen der Bankenaufsicht zu erfüllen,ist die Bescheinigung der Gewerbetätigkeit erforderlich."><i class="fa fa-question-circle font20" aria-hidden="true"></i></p>
                                        </div> --}}
                                        <div class="col-md-12 file-upload btn-tooltip mb-3">
                                            <input type="hidden" name="commercial_doc" data-url="{{FileUploadHelper::getMultipleDocPath($coach->commercial_doc, 'commercial_doc')}}" value="{{$coach->commercial_doc}}">
                                            <button type="button" class="btn blk_bor_btn mt-3 modal_file_upload" data-type="commercial_doc">Gesellschaftervertrag hochladen</button>
                                            <p class="tool_tip" data-toggle="tooltip" data-placement="right" title="Um die Auflagen der Bankenaufsicht zu erfüllen,ist die Bescheinigung der Gewerbetätigkeit erforderlich."><i class="fa fa-question-circle font20" aria-hidden="true"></i></p>
                                        </div>
                                        {{-- <div class="col-md-12 mb-3 term_category">
                                            <label class="container123">Hiermit bestätige ich, dass die verwendete Dokumente aktuell und gültig ist.
                                                <input type="checkbox" name="agree_ustid" value="1" checked="">
                                                <span class="checkmark"></span>
                                            </label>
                                        </div> --}}
                                    </div>
                                </div>
                                <!-- UST ID/REGISTRATION PROOF DOC -->
                                <div id="ust-div" style="{{ ($coach->is_commercial || $coach->person_type!='soletrader')?'':'display: none;' }}">
                                    <div class="col-md-12 file-upload btn-tooltip mb-3">
                                        <input type="hidden" class="do-not-ignore" name="ustid_doc" data-url="{{FileUploadHelper::getMultipleDocPath($coach->ustid_doc, 'ustid_doc')}}" value="{{$coach->ustid_doc}}">
                                        <button type="button" class="btn blk_bor_btn mt-3 modal_file_upload reg_proof_btn" data-type="ustid_doc">
                                            {{$coach->person_type=='soletrader'?'Gewerbeanmeldung hochladen':'Handelsregisterauszug hochladen'}}
                                        </button>
                                        <p class="tool_tip" data-toggle="tooltip" data-placement="right" title="Um die Auflagen der Bankenaufsicht zu erfüllen, ist die Bescheinigung der Gewerbetätigkeit erforderlich"><i class="fa fa-question-circle font20" aria-hidden="true"></i></p>
                                    </div>
                                    <div class="col-md-12 mb-3 term_category">
                                        <label class="container123">Hiermit bestätige ich, dass die verwendeten Dokumente aktuell und gültig sind.
                                            <input type="checkbox" name="agree_ustid" value="1" checked="">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                                <!-- Impressum -->
                                <div class="col-md-12 mb-3">
                                    <label for="coach_company">
                                        Impressum
                                        <p class="tool_tip" data-toggle="tooltip" data-placement="right" title="Die Angabe eines Impressums ist Voraussetzung, damit dein Profil veröffentlicht werden kann."><i class="fa fa-question-circle font20" aria-hidden="true"></i></p>
                                    </label>
                                    <textarea name="impressum" id="impressum" class="form-control" rows="3">{{$coach->impressum}}</textarea>
                                </div>
                                <h5 class="col-12 ch_tabdata_head">Deine Beratung</h5>
                                <div class="col-md-12 personal_info_step">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <div class="ano_counselling rounded_cust_switch">
                                                <div class="ano_select">
                                                    <div class="selection_wrapper">
                                                        <!-- Rounded switch -->
                                                        <label class="switch">
                                                            <input type="checkbox" class="period_switch" name="online_coaching" value="1" id="online_coaching" {{($coach->coaching_method=='both' || $coach->coaching_method=='online')?'checked':''}}>
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
                                                            <input type="checkbox" class="period_switch" name="offline_coaching" value="1" id="offline_coaching" {{($coach->coaching_method=='both' || $coach->coaching_method=='offline')?'checked':''}}>
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
                                                            <input type="checkbox" class="period_switch" name="show_on_map" value="1" {{$coach->show_on_map?'checked':''}}>
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
                                                            <input type="checkbox" class="period_switch offline_advise" id="different_work" name="different_work" value="1" {{$coach->different_work?'checked':''}} {{($coach->coaching_method!='both' && $coach->coaching_method!='offline')?'disabled':''}}>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>
                                                    <h6>Abweichende Adresse für Offline-Beratung</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row different_work_div" style="{{$coach->different_work?'display:flex;':''}}">
                                        <div class="col-md-12 mb-3 addressautocomplete">
                                            <label for="diff_autocomplete_loc">Straße + Hausnummer, Ort</label>
                                            <input type="text" class="form-control" id="diff_autocomplete_loc" placeholder="Musterstraße 14, Musterstadt" value="{{$coach->different_work?$coach->work_street.' '.$coach->work_place:''}}" autocomplete="false">
                                            <input type="hidden" class="do-not-ignore" name="work_latitude" id="work_latitude" value="{{ $coach->different_work?$coach->work_latitude:'' }}">
                                            <input type="hidden" class="do-not-ignore" name="work_longitude" id="work_longitude" value="{{ $coach->different_work?$coach->work_longitude:'' }}">
                                        </div>
                                        <div class="col-md-12 mt-5 mb-2">
                                            <h5 class="montbold">Anschrift nicht gefunden? Hier manuell korrigieren:</h5>
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <label for="work_street">Straße + Hausnummer</label>
                                            <input type="text" class="form-control" placeholder="Musterstraße 14" id="work_street" name="work_street" value="{{ $coach->different_work?$coach->work_street:'' }}">
                                        </div>
                                        {{-- <div class="col-md-4 pl-md-0 mb-3">
                                            <label for="work_house_no">Hausnummer</label>
                                            <input type="text" class="form-control" placeholder="Hausnummer" id="work_house_no" name="work_house_no" value="{{ $coach->work_house_no }}">
                                        </div> --}}
                                        <div class="col-md-4 mb-3 mb-md-5">
                                            <label for="work_post_code">Postleitzahl</label>
                                            <input type="text" class="form-control" placeholder="12345" id="work_post_code" name="work_post_code" value="{{ $coach->different_work?$coach->work_post_code:'' }}" readonly>
                                        </div>
                                        <div class="col-md-8 pl-md-0 mb-4 mb-md-5">
                                            <label for="work_place">Ort</label>
                                            <input type="text" class="form-control" placeholder="Musterstadt" id="work_place" name="work_place" value="{{ $coach->different_work?$coach->work_place:'' }}">
                                        </div>
                                        <div class="col-md-12 mb-4">
                                            <label>Land</label>
                                            <input type="text" class="form-control" name="work_country" id="work_country" value="{{ $coach->different_work?$coach->work_country:'' }}" readonly>
                                            <input type="hidden" class="do-not-ignore" name="work_country_code" id="work_country_code" value="{{ $coach->different_work?$coach->work_country_code:'' }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 mb-5 consulting_price">
                                            <h6 class="consult_crg">Beratungspreis pro Stunde:</h6>
                                            <input type="text" class="form-control" id="price_per_hour" name="price_per_hour" value="{{$coach->price_per_hour}}">
                                            <h6>€</h6>
                                            <p class="tool_tip" data-toggle="tooltip" data-placement="right" title="Der Betrag ist brutto anzugeben (ggf. inkl. Mehrwertsteuer). Eine Stunde entspricht 60 Minuten."><i class="fa fa-question-circle font20" aria-hidden="true"></i></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="button" class="btn blk_bor_btn pull-left">Abbrechen</button>
                                    <button type="button" class="btn orange_background_btn pull-right updateBtn">Speichern</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-bnkinfo" role="tabpanel" aria-labelledby="pills-bnkinfo-tab">
                        <div class="row">
                            <h5 class="col-12 ch_tabdata_head">Bankinformationen</h5>
                            <div class="col-md-12 col-lg-8 personal_info_step">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="owner_name">Accountinhaber</label>
                                        <input type="text" class="form-control" id="owner_name" name="owner_name" value="{{$coach->owner_name}}">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="iban">IBAN</label>
                                        <input type="text" class="form-control" id="iban" name="iban" value="{{$coach->iban}}">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="bic">BIC</label>
                                        <input type="text" class="form-control" id="bic" name="bic" value="{{$coach->bic}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-8">
                                <button type="button" class="btn blk_bor_btn pull-left">Abbrechen</button>
                                <button type="button" class="btn orange_background_btn pull-right updateBtn">Speichern</button>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pills-edu" role="tabpanel" aria-labelledby="pills-edu-tab">
                        <div class="row">
                            <h5 class="col-12 ch_tabdata_head">Ausbildung</h5>
                            <div class="col-md-12 col-lg-8 personal_info_step">
                                <div id="company_skeleton" class="d-none">
                                    <div class="row company_content">
                                        <div class="col-md-6 mb-3 reg_single_select">
                                            <select class="basic-select2 company_id" >
                                                <option value="">Ausbildungsträger</option>
                                                @foreach ($companies as $company)
                                                    {{-- @if(strtolower($company->name)=='other') --}}
                                                    @if($company->id==1)
                                                        @php 
                                                            $other_id = $company->id;
                                                            $other_name = $company->name;
                                                        @endphp
                                                    @else
                                                        <option value="{{$company->id}}">{{$company->name}}</option>
                                                    @endif
                                                @endforeach
                                                <option value="{{isset($other_id)?$other_id:1}}">{{$other_name}}</option>
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
                                    @foreach ($coach->companies as $coach_company)
                                        @php
                                            $company_pivot = $coach_company->pivot;
                                            $is_other = false;
                                            if(strtolower($coach_company->name)=='sonstige') {
                                                $is_other = true;
                                            }
                                        @endphp
                                        <div class="row company_content">
                                            <div class="col-md-6 mb-3 reg_single_select">
                                                <select class="basic-select2 company_id" >
                                                    <option value="" >Ausbildungsträger</option>
                                                    @foreach ($companies as $company)
                                                        {{-- @if(strtolower($company->name)=='Sonstige') --}}
                                                        @if($company->id==1)
                                                            @php 
                                                                $other_id = $company->id;
                                                                $other_name = $company->name;
                                                            @endphp
                                                        @else
                                                            <option value="{{$company->id}}" {{$coach_company->id==$company->id?'selected':''}}>{{$company->name}}</option>
                                                        @endif
                                                    @endforeach
                                                    <option value="{{isset($other_id)?$other_id:1}}" {{$coach_company->id==$other_id?'selected':''}}>Sonstige</option>
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
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mb-4 pb-4">
                                        <button type="button" class="btn blk_bor_btn mr-3 add_company">+ Weitere Ausbildung</button>
                                    </div>
                                </div>
                            </div>
                            <h5 class="col-12 ch_tabdata_head">Kenntnisse</h5>
                            <div class="col-md-12 col-lg-8 personal_info_step">
                                <div class="row">
                                    <p class="col-md-12 mb-3">Kategorien</p>
                                    @foreach ($categories as $category)
                                        <div class="col-md-6 mb-2 know_category">
                                            <label class="container123">{{$category->title}}
                                                <input type="checkbox" name="categories[]" value="{{$category->id}}" {{$coach_cat->contains($category->id)?'checked':''}}>
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                    @endforeach
                                    <div class="lang_multi_select col-md-12 mb-3">
                                        <label class="mb-2 mt-1">Sprachen <p class="tool_tip" data-toggle="tooltip" data-placement="right" title='Zusätzliche Sprache manuell eintragen und mit "Enter" bestätigen.'><i class="fa fa-question-circle font20" aria-hidden="true"></i></p></label>
                                        <input type="hidden" class="tagselect_val" value="{{$coach_lang}}">
                                        <select class="multi-select do-not-ignore" multiple="multiple" id="language" name="language[]">
                                            @foreach ($languages as $language)
                                                <option value="{{$language}}">{{$language}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="lang_multi_select col-md-12 mb-3">
                                        <label class="mb-2">Deine Schwerpunkte <p class="tool_tip" data-toggle="tooltip" data-placement="right" title='Zusätzliche Schwerpunkte manuell eintragen und mit "Enter" bestätigen.'><i class="fa fa-question-circle font20" aria-hidden="true"></i></p></label>
                                        <div class="priorities_desc">
                                            <p class="mb-1">Du kannst Schwerpunkte festlegen, in denen du besondere Kompetenzen bzw.</p>
                                            <p class="mb-1">Erfahrungen darstellst. Schwerpunkte schärfen dein Profil und erleichtern es</p>
                                            <p class="mb-2">Kunden, dich als  Experten für seine Herausforderungen zu finden.</p>
                                        </div>
                                        <input type="hidden" class="tagselect_val" value="{{$coach_priority}}">
                                        <select class="multi-select do-not-ignore" multiple="multiple" id="priorities" name="priorities[]">
                                            @foreach ($priorities as $priority)
                                                <option value="{{$priority}}">{{$priority}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="my_desc">Meine Beschreibung</label>
                                        <textarea class="form-control" id="description" name="description" rows="5">{{$coach->description}}</textarea>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="club">Gemeinde/Verein (optional)</label>
                                        <input type="text" class="form-control" placeholder="Gemeinde/Verein" id="community" name="community" value="{{$coach->community}}">
                                    </div>
                                    <p class="col-md-12 mb-3">Profilmedien (optional)</p>
                                    <div class="cus_upload_btn col-md-12 mb-5">
                                        <div class="file-upload">
                                            <input type="hidden" name="avatar" data-url="{{FileUploadHelper::getDocPath($coach->getOriginal('avatar'), 'avatar')}}" value="{{$coach->getOriginal('avatar')}}">
                                            <button type="button" class="btn modal_file_upload mr-2 ch_file_btn" data-type="avatar">Profilbild hochladen</button>
                                        </div>
                                        <div class="file-upload">
                                            <input type="hidden" name="video" data-url="{{FileUploadHelper::getDocPath($coach->video, 'video')}}" value="{{$coach->video}}">
                                            <button type="button" class="btn modal_file_upload ch_file_btn" data-type="video">Vorstellungsvideo hochladen</button>
                                        </div>
                                    </div>
                                    <div class="cus_upload_btn col-md-12 mb-4">
                                        <div class="file-upload">
                                            <input type="hidden" name="banner" data-url="{{FileUploadHelper::getDocPath($coach->getOriginal('banner'), 'banner')}}" value="{{$coach->getOriginal('banner')}}">
                                            <button type="button" class="btn modal_file_upload ch_file_btn" data-type="banner">Titelbild hochladen</button>
                                        </div><br>
                                        <small style="font-size:11px;margin-left:10px;margin-top:5px;"><strong>[Empfohlene Bildgröße: 1920x350 Pixel.]</strong></small>
                                    </div>
                                    <div class="col-md-12 mb-5 term_wrapper">
                                        <div class="term_category">
                                            <label class="container123">Hiermit bestätige ich, dass ich der Rechteinhaber der hochgeladenen Medien bin.
                                                <input type="checkbox" name="agree_copyright" value="1" {{$coach->agree_copyright?'checked':''}}>
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-lg-8">
                                <button type="button" class="btn blk_bor_btn pull-left">Abbrechen</button>
                                <button type="button" class="btn orange_background_btn pull-right updateBtn">Speichern</button>
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
@endsection

@section('scripts')  
    <script src="{{ asset('frontend/js/select2.min.js') }}"></script>
    <script src="{{ asset('frontend/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('frontend/js/dropzone.js') }}"></script>
    <script>
        Dropzone.autoDiscover = false;
    </script>
    <script src="{{ asset('frontend/js/custom/update_coach_profile.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Init Validation
            UpdateCoachModule.initValidation('userProfileFrm');

            // Reset Shareholder attr fields
            UpdateCoachModule.resetShareholderAttr();
            
            // Reset company attr fields
            UpdateCoachModule.resetCompanyAttr();

            // Update Profile (except last tab)
            $(".updateBtn").click(function(event) {
                var parent_id = $($(this).closest('.tab-pane')).attr('id');
                UpdateCoachModule.checkValidation('userProfileFrm', function(is_valid) {
                    if(is_valid)
                        updateUserDetails(parent_id);
                });
            });

            function updateUserDetails (tabId) {
                var form_data = $('#'+tabId+' :input').serialize();
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
                        toastr.success('Profile updated succesfully');
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

            // Delete profile
            $(".deleteBtn").click(function(event) {
                UpdateCoachModule.checkValidation('userProfileFrm', function(is_valid) {
                    if(is_valid) {
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
        function initMap() {
            UpdateCoachModule.initAutoComplete();
        }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBR5FirOPNOnP9WBqT0ZMBbzyQ8reeVLhI&libraries=places&callback=initMap">
    </script>
@endsection
