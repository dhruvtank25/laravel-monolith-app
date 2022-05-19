@extends('layouts.app')

@section('style_link')
@endsection

@section('content')
    <div class="container-fluid thankyou_container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-sm-12 col-md-12 col-lg-8 thank_container text-center">
                    <div class="thk_icon">
                        <i class="fa fa-times thankyou-tick" aria-hidden="true"></i>
                    </div>
                    <h2 class="col-12 section_head thankyouheading">Buchung fehlgeschlagen!</h2>
                    <p class="mb-4">Leider ist der Termin nicht mehr verfügbar. Buche einfach<br> im Profil deines Beraters einen freien Termin<br> oder stelle einue neue Terminanfrage</p>
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
