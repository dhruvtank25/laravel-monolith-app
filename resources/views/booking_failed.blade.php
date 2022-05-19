@extends('layouts.app')

@section('content')
	
	<div class="container-fluid thankyou_container">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-sm-12 col-md-12 col-lg-8 thank_container text-center">
					<div class="thk_icon">
						<i class="fa fa-times thankyou-tick" aria-hidden="true"></i>
					</div>
					<h2 class="col-12 section_head">Deine Buchung war erfolglos!</h2>
					<p class="mb-4">
						<h5 class="trans_id">Transaktions-ID# : {{$transaction->transaction_id}} </h5>
						<h5 class="red_text mb-4">Zahlung fehlgeschlagen mit Fehler {{$transaction->result_code}}:{{$transaction->result_message}}.</h5>
						<hr>
						<h5 class="red_text">Ihre Zahlung ist möglicherweise aus einem der folgenden Gründe fehlgeschlagen:</h5>
						<ol class="text-left payment_fail_reason">
							<li>Die Zahlung wurde von Ihrer Bank oder Ihrem Kreditkartenanbieter abgelehnt</li>
							<li>Die angegebene Kreditkarte ist abgelaufen</li>
							<li>Kreditkarte hat nicht genügend Guthaben</li>
							<li>Die angegebene Kreditkartennummer ist ungültig</li>
							<li>Der angegebene Kreditkartentyp unterscheidet sich von den Kreditkartendaten</li>
						</ol>
					</p>
					<p class="mb-5 text-left thcontact_text">Für Fragen kontaktieren Sie uns bitte unter <a href="mailto:kontakt@himmlischberaten.de" class="link">kontakt@himmlischberaten.de</a></p>
                    <a href="{{ route('home') }}" class="btn bck_hm_btn">Zurück zur Startseite</a>
				</div>
			</div>
		</div>
	</div>
	
@endsection

@section('scripts')
@endsection
