<?php

/** ------------------------------------------
 *  Front Routes
 *  ------------------------------------------
 */
 
Route::group(['namespace'=>'Front'], function() {

	// Comming Soon
	//Route::get('/', 'HomeController@commingSoon');
	//Route::get('/imprint', 'HomeController@commingSoonImpressum')->name('impressum');

	// Home
	Route::get('/',  'HomeController@index')->name('home');
	Route::get('/home',  'HomeController@index');

	// User
	Route::get('user/unique-email-check', 'UserController@checkEmailExists');
	Route::get('user/unique-username-check', 'UserController@checkUsernameExists');
	Route::get('nutzer-registrierung', 'UserController@create')->name('user-register');
	Route::get('gast-registrierung', 'UserController@guestCreate')->name('guest-register');
	Route::post('user-register', 'UserController@store');
	Route::get('user-register/complete', 'UserController@complete')->name('user-register/complete');
	
	// Coach
	Route::get('berater-werden', 'CoachController@index')->name('become-a-coach');
	Route::get('berater-registrierung', 'CoachController@create')->name('coach-register');
	Route::post('coach-register', 'CoachController@store');
	Route::post('upload-documents', 'CoachController@uploadDocuments');
	Route::post('delete-documents', 'CoachController@deleteDocuments');
	Route::get('coach-register/complete', 'CoachController@complete')->name('coach-register/complete');
	Route::get('get_all_coaches/locations', 'CoachController@getALLCoachesLocations');

	// Request Appointment
	Route::post('request-appointment', 'CoachController@requestAppointment');

	// Bookings
	Route::get('beratung/{url_slug?}', 'CoachController@search')->name('coach-search');
	Route::get('berater-detail/{name}/{id}', 'CoachController@coachDetail')->name('coach-detail');
	Route::get('coach-detail/{id}', 'CoachController@show');
	Route::get('coach-available-statuses', 'AvailabilityController@getAvailabilityStatuses');
	Route::get('coach-available-slot', 'AvailabilityController@getAvailableSlots');
	Route::post('book-coach', 'CoachController@bookSlot');
	Route::get('book-coach', 'CoachController@bookSlot')->name('book-coach');

	// Payments
	Route::get('create-card-registration', 'PaymentController@createCardRegistration');
	Route::get('card-registration-response', 'PaymentController@cardRegistrationResponse');
	Route::post('update-card-registration', 'PaymentController@updateCardRegistration');
	Route::get('buchung-uebersicht/{id}', 'PaymentController@bookingSummary')->name('booking-summary');
	Route::post('make-payment', 'PaymentController@initiateDirectCardPayIn');
	Route::get('zahlung-ergebnis', 'PaymentController@paymentResult')->name('payment-result');
	Route::get('zahlung-erfolgreich', 'PaymentController@paymentSuccess')->name('payment-success');
	Route::get('zahlung-fehlgeschlagen', 'PaymentController@paymentFailed')->name('payment-failed');

	// Availabilties
	Route::get('availabilites', 'AvailabilityController@index');
	Route::post('availability', 'AvailabilityController@store');
	Route::post('availability/get-slots', 'AvailabilityController@getDateSlots');
	Route::get('availability/get-statuses', 'AvailabilityController@getAvailabilityStatuses');

	// Unavailabilities
	Route::post('coaches/unavailabilities', 'AvailabilityController@storeUnavailability');
	Route::delete('coaches/unavailabilities/{coach_id}', 'AvailabilityController@destroyUnavailability');
	Route::get('coaches/unavailabilities/{coach_id}', 'AvailabilityController@getUnavailabilityDataTables');

	// Contact US
	Route::get('kontakt', 'HomeController@contactUs')->name('contact-us');
	Route::post('kontakt', 'HomeController@postContactUs')->name('contact-us');
	Route::get('kontakt-bestaetigung', 'HomeController@contactThankYou')->name('thankyou-contact');
	
	// Other static pages
	Route::get('ueber-uns', 'HomeController@aboutUs')->name('about-us');
	Route::get('faq', 'HomeController@faq')->name('faq');
	Route::get('impressum', 'HomeController@imprint')->name('imprint');
	Route::get('fuer-organisationen', 'HomeController@organisation')->name('organisation');
	Route::get('agb', 'HomeController@agb')->name('agb'); //Terms and Condition
	Route::get('datenschutz', 'HomeController@dataProtection')->name('data-protection');
	Route::get('glaubensrichtlinie', 'HomeController@faithPrinciple')->name('faith-principles');
	Route::get('newsletter', 'HomeController@newsletter')->name('newsletter');
	
	// Video calling
	Route::get('video-call/{appointment_id}', 'CallController@show');
	Route::get('video-call/summary/{appointment_id}', 'CallController@summary');
	Route::post('save-call-log', 'CallController@storeLog');

	// Mangopay Hooks
	Route::get('initiate-hook', 'PaymentController@intiatehook');
	Route::get('mangopay-hook', 'PaymentController@hook');
	
	// Test Mail Previews
	Route::get('appointment-booked-mail', function () {
		$appointment = App\Models\Appointment::find(33);
		$transaction = App\Models\Transaction::find(1);
		return event(new \App\Events\AppointmentBookedEvent($appointment, $transaction));
	    //return new App\Mail\AppointmentBooked($appointment, $transaction);
	});

	Route::get('appointment-cancelled-mail', function () {
		$appointment = App\Models\Appointment::find(59);
		return event(new \App\Events\AppointmentCancelledEvent($appointment));
	});

	Route::get('appmnt-request-accepted', function() {
		$appRequest = App\Models\AppointmentRequest::find(1);
		return new App\Mail\AppmntRequestAccepted($appRequest);
	});

	Route::get('reminder-user-email', function() {
		$appointment = App\Models\Appointment::find(59);
		// tommorrow, today
		return new App\Mail\notifyUpcommingSessionUserMail($appointment, 'today');
	});

	Route::get('reminder-coach-email', function() {
		$appointment = App\Models\Appointment::find(59);
		return new App\Mail\notifyUpcommingSessionCoachMail($appointment, 'tommorrow');
	});

	// Trigger Artisan Command
	
	Route::get('set-appointment-completed', function() {
		$exitCode = Artisan::call('appointment:setCompleted');
	});

	Route::get('process-payments', function() {
		$exitCode = Artisan::call('payment:process');
	});

	Route::get('process-refunds', function() {
		$exitCode = Artisan::call('refund:process');
	});

	Route::get('validate-kyc', function() {
		$exitCode = Artisan::call('kyc:validate');
	});

	Route::get('declare-ubo', function() {
		$exitCode = Artisan::call('declare:ubo');
	});

	Route::get('notifyupcomingsession', function () {
		$exitCode = Artisan::call('run:notifyupcomingsession');
	});

});
