@extends('layouts.app')

@section('style_link')
<link rel="stylesheet" href="{{ asset('frontend/css/jquery-ui.min.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/availability.css') }}">
@endsection

@section('content')
    <div class="container-fluid ref_steps_container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12 col-lg-8">
                    <form action="get" id="coachRegFrm">
                        @csrf
                        <h3 class="mb-4">Als Berater registrieren</h3>
                        <p class="mb-5 montitalic font14">Nach deiner Registrierung kannst du alle erforderlichen Angaben für dein Profil ergänzen. Wenn alle Pflichtangaben vorliegen, wird dein Profil freigeschaltet.</p>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name">Vorname</label>
                                <input type="text" class="form-control" placeholder="Max" id="first_name" name="first_name">
                            </div>
                            <div class="col-md-6 pl-md-0 mb-3">
                                <label for="last_name">Nachname</label>
                                <input type="text" class="form-control" placeholder="Mustermann" id="last_name" name="last_name">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email">E-Mail Adresse</label>
                                <input type="email" class="form-control" placeholder="max.mustermann@mail.de" id="email" name="email">
                            </div>
                            <div class="col-md-6 pl-md-0 mb-3">
                                <label for="phone_number">Telefonnummer</label>
                                <input type="tel" class="form-control" placeholder="0123456789" id="phone_number" name="phone_number">
                            </div>
                            <div class="col-md-6 mb-3 mb-md-5">
                                <label for="password">
                                    Passwort festlegen 
                                    <p class="tool_tip" data-toggle="tooltip" data-placement="right" title="Mindestens acht Zeichen, davon mindestens einen Großbuchstaben und mindestens eine Zahl."><i class="fa fa-question-circle" aria-hidden="true"></i></p>
                                </label>
                                <input type="password" class="form-control" placeholder="********" id="password" name="password">
                            </div>
                            <div class="col-md-6 pl-md-0 mb-3 mb-md-5">
                                <label for="password_confirmation">Passwort bestätigen</label>
                                <input type="password" class="form-control" placeholder="********" id="password_confirmation" name="password_confirmation">
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
                            <div class="col-md-12 mb-3 mt-3 term_wrapper">
                                <div class="mb-3 term_category">
                                    <label class="container123">Ich habe die <a target="_blank" href="{{route('agb')}}">Nutzungsbedingungen</a> gelesen und erkläre mich mit ihnen einverstanden.
                                        <input type="checkbox" name="terms_condition" value="1">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="mb-3 term_category">
                                    <label class="container123">Ich habe die <a target="_blank" href="{{route('data-protection')}}">Hinweise zum Datenschutz</a> gelesen und erkläre mich mit ihnen einverstanden.
                                        <input type="checkbox" name="privacy_policy" value="1">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="mb-3 term_category">
                                    <label class="container123">Ich habe die <a target="_blank" href="{{route('faith-principles')}}">Glaubensgrundsätze</a> gelesen und erkläre mich mit ihnen einverstanden.
                                        <input type="checkbox" name="agree_credentials" value="1">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <ul class="col-md-12 pl-0 pr-0 pager wizard">
                            <li class="regbackbtn"><a href="javascript:history.back();">Zurück</a></li>
                            <input class="regsubmit" type="submit" value="Registrieren">
                            <div class="clearfix"></div>
                        </ul>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('frontend/js/jquery-ui.min.js') }}"></script>
<script>
    $(document).ready(function() {
        // Jquery Ui Tooltip
        $('[data-toggle="tooltip"]').tooltip({
            position: {'at': 'right+5 top-18'},
            tooltipClass: 'customtooltip'
        });

        // Jquery Ui datepicker
        $("#birth_date").datepicker({
            dateFormat: "yy-mm-dd",
            yearRange: "-100:-18",
            maxDate: "-18Y",
            changeYear:true,
            changeMonth:true,
            showMonthAfterYear: true,
        });

        $validator = $('#coachRegFrm').validate({
                        errorClass: "is-invalid",
                        rules:{
                            first_name:"required",
                            last_name: "required",
                            email:{
                                required:true,
                                remote: {
                                    url: baseUrl+'/user/unique-email-check/',
                                }
                            },
                            phone_number: "required",
                            password: {
                                required: true,
                                minlength: 8,
                                pwcheck: true
                            },
                            password_confirmation: {
                                equalTo: "#password"
                            },
                            birth_date: "required",
                            nationality: "required",
                            terms_condition: {
                                required: true,
                                minlength: 1
                            },
                            privacy_policy: {
                                required: true,
                                minlength: 1
                            },
                            agree_credentials: {
                                required: true,
                                minlength: 1
                            }
                        },
                        messages: {
                            email: {
                                remote: "Email is already taken"
                            },
                            password: {
                                pwcheck: "Password must include at least one capital letter and at least one number"
                            },
                            password_confirmation: {
                                equalTo: "Password confirmation does not match"
                            },
                        },
                        errorPlacement: function(error, element){
                            // Blank so that no error message is shown.
                            // All Error will be displayed via toastr
                        },
                        highlight: function(element) {
                            var element_name = $(element).attr('name');
                            if($(element).attr('type')=='checkbox') {
                                $('input[name="'+element_name+'"]').parent().addClass('text-danger');
                            }
                            else {
                                $(element).addClass('border border-danger');
                            }
                            $(element).parent().find('label').addClass('text-danger');
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
                        },
                        invalidHandler: function(form, validator) {
                            var errors = validator.numberOfInvalids();
                            if (errors) {                    
                                validator.focusInvalid();
                                var error_html   = '';
                                var required_err = false;
                                $.each(validator.errorMap,function(key, data) {
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
                        }, 
                        submitHandler: function(form) {
                            var form_data = $('#coachRegFrm :input').serialize();
                            showLoader();
                            $.ajax({
                                url: baseUrl+'/coach-register',
                                type: 'POST',
                                data: form_data,
                            })
                            .done(function(data) {
                                console.log("success");
                                if(data.success=="true")
                                    window.location.href = '{{route('coach-register/complete')}}';
                                else
                                    toastr.error(data.message);
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
                        },
                    });
    });
</script>
@endsection