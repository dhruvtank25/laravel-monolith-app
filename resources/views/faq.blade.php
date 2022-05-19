@extends('layouts.app')

@section('content')
	
	<!-- faq banner -->
	<div class="container-fluid faq_banner_wrapper">
		<div class="container text-center">
			<div class="row justify-content-center">
				<div class="col-sm-12 col-md-8">
					<h3>Häufig gestellte Fragen</h3>
				</div>
			</div>
		</div>
	</div>
	<!-- faq banner end-->
	
	<!-- FAQ for Customers section -->
	<div class="container-fluid faq_coach_wrapper">
		<div class="container">
			<div class="row">
				<h3 class="col-12 section_head">Für Kunden</h3>
				<div class="col-md-12 accordion coach_faq_accord" id="forcustomer">
					@foreach ($user_faqs as $faq)
					    <div class="card">
							<div class="card-header" id="heading{{$faq->id}}">
							  	<h2 class="mb-0">
									<button class="btn btn-link {{ !$loop->first?'collapsed':'' }}" type="button" data-toggle="collapse" data-target="#customer{{$faq->id}}" aria-expanded="{{ $loop->first?'true':'false' }}" aria-controls="customer{{$faq->id}}">
								  	{{$faq->title}}
									</button>
							  	</h2>
							</div>
							<div id="customer{{$faq->id}}" class="collapse {{ $loop->first?'show':'' }}" aria-labelledby="heading{{$faq->id}}" data-parent="#forcustomer">
							  	<div class="card-body">
									{{$faq->description}}
							  	</div>
							</div>
					    </div>
					@endforeach
				</div>
			</div>
		</div>
	</div>
	<!-- FAQ for Customers section end -->
	
	
	<!-- FAQ for Consultants -->
	<div class="container-fluid faq_coach_wrapper">
		<div class="container">
			<div class="row">
				<h3 class="col-12 section_head">Für Berater</h3>
				<div class="col-md-12 accordion coach_faq_accord" id="forconsultant">
					@foreach ($coach_faqs as $faq)
						<div class="card">
							<div class="card-header" id="heading{{$faq->id}}">
							  <h2 class="mb-0">
								<button class="btn btn-link {{ !$loop->first?'collapsed':'' }}" type="button" data-toggle="collapse" data-target="#consultant{{$faq->id}}" aria-expanded="{{ $loop->first?'true':'false' }}" aria-controls="consultant{{$faq->id}}">
								  {{$faq->title}}
								</button>
							  </h2>
							</div>
							<div id="consultant{{$faq->id}}" class="collapse {{ $loop->first?'show':'' }}" aria-labelledby="heading{{$faq->id}}" data-parent="#forconsultant">
							  <div class="card-body">
								{{$faq->description}}
							  </div>
							</div>
						</div>
					@endforeach
				</div>
			</div>
		</div>
	</div>
	<!-- FAQ for Consultants end -->
	
	<!-- life counselling -->
	<div class="container-fluid life_counsel_wrapper">
		<div class="container text-center">
			<div class="row justify-content-center">
				<div class="col-sm-12 col-md-8">
					<h4>Mit himmlischberaten.de schaffen wir einen einfachen und schnellen Zugang zu professioneller Lebensberatung auf christlicher Basis.</h4>
					<h4>Niemand, der Hilfe sucht, bleibt mit seinen</h4>
				</div>
			</div>
		</div>
	</div>
	<!-- life counselling end-->
@endsection

@section('scripts')
@endsection