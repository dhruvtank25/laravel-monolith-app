@extends('layouts.app')

@section('content')
	
	<div class="container-fluid thankyou_container">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-sm-12 col-md-12 col-lg-8 thank_container text-center">
					<div class="thk_icon">
						<i class="fa fa-check thankyou-tick" aria-hidden="true"></i>
					</div>
					<h2 class="col-12 section_head">Deine Buchung war erfolgreich!</h2>
					<p class="mb-4">Danke für deine Buchung auf himmlischberaten.de In Kürze erhältst du eine E-Mail mit den Buchungsdetails und weiteren Informationen.</p>
					<p class="mb-5">Solltest du in der Zwischenzeit Fragen haben, kontaktiere uns unter <a href="mailto:kontakt@himmlischberaten.de" class="link">kontakt@himmlischberaten.de</a></p>
                    <a href="{{ route('home') }}" class="btn bck_hm_btn">Zurück zur Startseite</a>
				</div>
			</div>
		</div>
	</div>
	
@endsection

@section('scripts')
@endsection
