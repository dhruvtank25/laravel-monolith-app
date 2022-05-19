@extends('layouts.app')

@section('style_link')
@endsection

@section('content')
    <div class="container-fluid register_container">
        <div class="container">
            <div class="row justify-content-center">
                <!-- <div class="col-md-5 col-lg-6 register_img">
                    <img class="img-fluid" src="img/register_element.png" alt="x" />
                </div> -->
                <div class="col-md-7 col-lg-6 register_frm req_new_pass_wrap">
                    <h3>Bestätige deine e-mail Adresse</h3>
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Ein neuer Bestätigungslink wurde an Ihre E-Mail-Adresse gesendet.') }}
                        </div>
                    @endif
                    {{ __('Um mit deiner Registrierung fortfahren zu können, ist die Bestätigung deiner e-mail-Adresse erforderlich. Hierzu haben wir dir einen Bestätigungslink an die von dir hinterlegte e-mail-Adresse gesendet.') }}

                    {{ __('Solltest du die Nachricht nicht erhalten haben,') }} <a href="{{ route('verification.resend') }}">{{ __('klicke hier um die Bestätigungsmail erneut anzufordern. ') }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection