@extends('layouts.app')

@section('style_link')
@endsection

@section('content')
    <div class="greybg container-fluid">
        <div class="usercompleteregistration-wrapper">
            <h3>Deine Daten</h3>
            <p class="mt-md-3">Danke für deine Buchung bei himmlischberaten.de.</p>
            <div class="personalregistrationinfo mt-md-3 ">
                @csrf
                <input type="hidden" id="coach_id" value="{{ $appointment['coach_id'] }}">
                <input type="hidden" id="category_id" value="{{ $appointment['category_id'] }}">
                <input type="hidden" id="start" value="{{ $appointment['start'] }}">
                <input type="hidden" id="end" value="{{ $appointment['end'] }}">
                <input type="hidden" id="mode" value="{{ $appointment['mode'] }}">
                <input type="hidden" id="notes" value="{{ $appointment['notes'] }}">
                @php    
                    $app_user   = $appointment->user;
                    $app_coach  = $appointment->coach;
                    $cost_calc  = $appointment->formatted_cost_calculation;
                @endphp

                <div class="row">
                    <div class="col-md-12 mb-md-3 mt-md-3 reginfo">
                        <div class="row">
                            <div class="col-md-6 mb-6 mb-md-2">
                                <strong for="cvv">Buchnungsnummer: {{$appointment->id}}</strong>
                            </div>
                            <div class="col-md-6 mb-6 mb-md-2">
                            </div>
                            <div class="col-md-6 mb-6 mb-md-2">
                                <strong>Rechnungsadresse:</strong>
                                <p>{{$app_user->first_name.' '.$app_user->last_name}}<br>
                                {{$app_user->street.' '.$app_user->house_no}}<br>
                                {{$app_user->post_code.' '.$app_user->place}}
                            </div>
                            <div class="col-md-6 mb-6 mb-md-2">
                                 <strong >Zahlungsmethode:</strong> Kreditkarte
                            </div>
                            <div class="col-md-6 mb-6 mb-md-2">
                                <strong >Dein Beratungstermin:</strong>
                                <p>Datum: {{$appointment->start->format('d.m.Y')}}</p>
                                <p>Startzeit: {{$appointment->start->format('H:i')}} Uhr</p>
                                <p>Dauer: {{$appointment->start->diffInMinutes($appointment->end)}} Minuten</p>
                                <p>Ort: {{ucwords($appointment->mode)}}</p>
                                @if($appointment->mode=='offline')
                                    <p>{{$app_coach->work_street.' '.$app_coach->work_house_no}}</p>
                                    <p>{{$app_coach->work_post_code.' '.$app_coach->work_place}}</p>
                                @endif
                            </div>
                            <div class="col-md-6 mb-6 mb-md-2">
                                <strong >Dein Berater:</strong> <a class="vwprf_btn" href="{{route('coach-detail', ['name' => $app_coach->first_name.'-'.$app_coach->last_name,'id' => $app_coach->id])}}" class="popuplink">Zum Profil</a>
                                <p>{{$app_coach->coach_company}}</p>
                                <p>{{$app_coach->first_name.' '.$app_coach->last_name}}</p>
                                <p>{{$app_coach->work_street.' '.$app_coach->work_house_no}}</p>
                                <p>{{$app_coach->work_post_code.' '.$app_coach->work_place}}</p>
                              
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-md-3 mt-md-3 reginfo">
                        <table width="100%" class="bluetable">
                            <thead>
                                <tr>
                                    <th>Nr</th>
                                    <th>Bezeichnung</th>
                                    <th>Dauer</th>
                                    <th>Preis/Stunde</th>
                                    <th>Gesamtpreis</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>{{$appointment->categories->title}}</td>
                                    <td>{{$cost_calc['duration_min']}} Minuten</td>
                                    @if($app_coach->small_business)
                                        <td>{{$cost_calc['price_per_hour']}} €</td>
                                        <td>{{$cost_calc['gross_cost']}} €</td>
                                    @else
                                        <td>{{$cost_calc['net_per_hr']}} €</td>
                                        <td>{{$cost_calc['final_net_cost']}} €</td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>USt</td>
                                    @if($app_coach->small_business)
                                        <td>0%</td>
                                        <td>0,00 €</td>
                                    @else
                                        <td>{{$cost_calc['vat_percent']}}%</td>
                                        <td>{{$cost_calc['final_vat_cost']}} €</td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td class="montbold"><strong>Gesamtpreis.</strong></td>
                                    <td class="montbold"><strong>{{$cost_calc['gross_cost']}} €</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($app_coach->small_business)
                    <p class="mt-md-4">Gemäß §19 UStG enthält der Rechnungsbetrag keine Umsatzsteuer.</p>
                @endif
                <p class="mb-md-1">Der Rechnungsbetrag wird deinem Zahlungsmittel belastet. Im Login-Bereich kannst du deine Beratung jederzeit bearbeiten.</p>
                <p class="mb-md-4"">Das Widerrufsrecht ist ausgeschlossen. Es gelten die Stornobedingungen deines Beraters.</p>
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
                <div class="usertc-wrapper">
                    <a class="bckbtn" href="javascript:history.back();">Zurück</a>
                    <button class="orange_background_btn mb-md-5" id="makePaymentBtn">Kostenpflichtig buchen</button>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('frontend/js/custom/card_payment.js')}}"></script>
    <script>
        $(document).ready(function() {
            $("#makePaymentBtn").click(function(event) {
                if(!$("#terms_condition").is(':checked')) {
                    toastr.error('You need to accept the terms and condition to proceed!');
                } else if (!$("#privacy_policy").is(':checked')) {
                    toastr.error('You need to accept the privacy policy to proceed!');
                } else {
                    payIn("{{ $appointment->id }}");
                }
            });
        });
    </script>
@endsection
