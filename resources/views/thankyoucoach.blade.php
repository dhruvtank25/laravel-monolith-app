@extends('layouts.app')

@section('style_link')
@endsection

@section('content')
    <div class="container-fluid thankyou_container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-sm-12 col-md-12 col-lg-8 thank_container text-center">
                    <div class="thk_icon">
                        <i class="fa fa-check thankyou-tick" aria-hidden="true"></i>
                    </div>
                    <h2 class="col-12 section_head thankyouheading">Deine Registrierung war erfolgreich!</h2>
                    <p class="mb-4">Danke für deine Registrierung als Berater auf himmlischberaten.de. Wir haben dir eine e-mail gesendet in der du deine Registrierung durch „klick“ auf den link bestätigst. Anschließend kannst du mit der Erstellung deines Profils fortfahren.</p>
                    <p class="mb-4">Die E-Mail-Bestätigung ist nur mit dem selben Gerät möglich, das du auch für deine Registrierung verwendet hast. Dadurch können wir die Sicherheit deiner Daten gewährleisten.</p>
                    <p class="mb-5">Solltest du in der Zwischenzeit Fragen haben, kontaktiere uns unter <a href="mailto:kontakt@himmlischberaten.de">kontakt@himmlischberaten.de</a></p>
                    <a href="{{ route('home') }}" class="btn bck_hm_btn">Zurück zur Startseite</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
  $(document).ready(function() {
    $('body').addClass('bg_grey');
  });
</script>
@endsection
