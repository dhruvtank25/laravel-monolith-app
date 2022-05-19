@extends('layouts.app')

@section('content')
	
	<div class="container-fluid contact_container">
		<div class="container">
			<div class="row">
				<div class="col-md-12 col-lg-7 register_frm">
					<h2 class="section_head mb-4">Kontakt</h2>
					<p class="promise_data mb-5">Bei Fragen oder Feedback schreibe uns gerne eine Nachricht</p>
					<form action="post" id="contactFrm">
						@csrf
						<div class="personal_info_step">
							<div class="row">
								<div class="col-md-12 mb-3">
									<label for="name">Name</label>
									<input type="text" class="form-control" id="name" name="name" required>
								</div>
								<div class="col-md-12 mb-3">
									<label for="email">E-Mail</label>
									<input type="email" class="form-control" id="email" name="email" required>
								</div>
								<div class="col-md-12 mb-3">
									<label for="my_desc">Deine Nachricht</label>
									<textarea class="form-control" id="my_desc" rows="5" name="description" required></textarea>
								</div>
							</div>
							<button type="submit" class="btn orange_background_btn">Absenden</button>
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

  <!-- partner about end -->
@endsection

@section('scripts')
<script>
	$(document).ready(function() {
		$validator = $('#contactFrm').validate({
			submitHandler: function (form) {
				showLoader();
				$.ajax({
					url: '{{route('contact-us')}}',
					type: 'POST',
					data: $(form).serialize(),
				})
				.done(function(message) {
					console.log("success");
					$('#contactFrm')[0].reset();
					toastr.success(message);
					window.location.href = '{{route('thankyou-contact')}}';
				})
				.fail(function() {
					console.log("error");
					toastr.success('Something went wrong!');
				})
				.always(function() {
					console.log("complete");
					hideLoader();
				});
			}
		});
	});
</script>
@endsection