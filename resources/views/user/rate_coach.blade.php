@extends('layouts.app')

@section('style_link')
    <link rel="stylesheet" href="{{ asset('frontend/css/jquery.rateyo.min.css') }}">
@endsection

@section('content')
	<div class="container-fluid contact_container">
		<div class="container">
			<div class="row">
				<div class="col-md-12 col-lg-7 register_frm">
					<h2 class="section_head mb-4">Bewerte deinen Berater</h2>
					<p class="promise_data mb-2">Vielen Dank, dass du himmlischberaten.de gewählt hast.</p>
					<p class="promise_data mb-5">Hier hast du die Gelegenheit, deinen Berater zu bewerten.</p>
                    <div id="thankyouDiv" style="display:none;">
                        <h3>Your rating has been submitted succesfully</h3>
                    </div>
					<form action="get" id="ratingFrm">
                        @csrf
                        <input type="hidden" id="appointment_id" value="{{$appointment->id}}">
						<div class="personal_info_step">
							<div class="row">
								<div class="col-md-12 mb-5 profile_details">
									<div class="round_circle" style="background-image: url({{ FileUploadHelper::getDocPath($coach->avatar, 'avatar') }})"></div>
									<div class="coach_profile_name">
										<h2>{{$coach->first_name.' '.$coach->last_name}}</h2>
                                        @if(count($coach_companies)>0)
										  <h5>{{ $coach_companies[0]->pivot->designation }} bei {{ strtolower($coach_companies[0]->name)=='other'?$coach_companies[0]->pivot->company_name:$coach_companies[0]->name }}</h5>
                                        @endif
									</div>
								</div>
								<div class="col-md-12 mb-4">
									<label for="my_desc">Deine Bewertung</label>
									<div id="rateYo" class="mb-3 coachrating_wrapper" style="padding-left:0"></div>
									<textarea class="form-control" id="my_desc" rows="5"></textarea>
									<p class="select_coach_avail mt-3">Bitte beachte: Deine Bewertung wird im Profil deines Beraters veröffentlicht.</p>
								</div>
							</div>
							<button type="button" id="saveReviewBtn" class="btn orange_background_btn">Absenden</button>
                            <button type="button" id="cancelReviewBtn" class="btn orange_background_btn blk_btn pull-right">Abbrechen</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- life counselling -->
	<div class="container-fluid life_counsel_wrapper">
		<div class="container text-center">
			<div class="row justify-content-center">
				<div class="col-sm-12 col-md-8">
					<h4>Mit himmlischberaten.de schaffen wir einen einfachen und schnellen Zugang zu professioneller Lebensberatung auf christlicher Basis.</h4>
					<h4>Niemand, der Hilfe sucht, bleibt mit seinen Herausforderungen alleine.</h4>
				</div>
			</div>
		</div>
	</div>
	<!-- life counselling end-->
@endsection
  
@section('scripts')
    <script src="{{ asset('frontend/js/jquery.rateyo.min.js') }}"></script>
    <script>
        $(document).ready(function() {
    	    
            $("#rateYo").rateYo({
    		    rating: 1,
    		    //starWidth: "24px",
    		    halfStar: true,
                //precision: 0.5
                ratedFill:"#84D9E5"
    	    });

            $("#saveReviewBtn").click(function(event) {
                var review = $("#my_desc").val();
                var rating = $("#rateYo").rateYo("rating");
                if(review=='') {
                    toastr.error('Please add review to submit!');
                    return false;
                }
                showLoader();
                $.ajax({
                    url: baseUrl+'/rate-coach',
                    type: 'POST',
                    data: {
                            _token: $('input[name="_token"]').val(),
                            appointment_id: $("#appointment_id").val(),
                            comment: review,
                            rating: rating
                        },
                })
                .done(function(data) {
                    console.log("success");
                    if(data.success=='true') {
                        toastr.success('Thank for your rating!');
                        $("#ratingFrm").hide();
                        $("#thankyouDiv").show();
                        var summary_url = "{{$summary_url}}";
                        location.replace(summary_url);
                        //location.replace(baseUrl+'/call-summary');
                    } else {
                        toastr.error(data.message);
                    }
                })
                .fail(function(error) {
                    console.log("error");
                    toastr.error('Some unexpected error occured!');
                    console.log(error);
                })
                .always(function() {
                    console.log("complete");
                    hideLoader();
                });
            });

            $("#cancelReviewBtn").click(function(event) {
                location.reload(true);
            });

        });
    </script>
@endsection