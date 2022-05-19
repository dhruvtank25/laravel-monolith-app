<?php
/** ------------------------------------------
 *  User Routes
 *  ------------------------------------------
 */

// prefix :- The prefix method may be used to prefix each route in the group with a given URI. For example, you may want to prefix all route URIs within the group with user:- Matches The "/user" URL
// namespace :- Controllers Within The "App\Http\Controllers\User" Namespace

Route::put('user/my-profile/update', 'User\UserController@update')->middleware('auth.guard:user');

/** For SEO purpose */
Route::group(['prefix' => 'nutzer', 'middleware' => ['auth.guard:user', 'verified'], 'namespace'=>'User'], function() {
    // User/Dashboard
    Route::get('/','UserController@index')->name('user');
    Route::get('mein-profil', 'UserController@index')->name('user.my-profile');

    // Bookings
    Route::get('buchungen', 'AppointmentController@index')->name('user.bookings');

    // Coach Rating
    Route::get('bewertung/{appointment_id}', 'AppointmentController@rateCoach')->name('user.rate-coach');

    // User Call Summary
    Route::get('zusammenfassung', 'AppointmentController@call_summary')->name('user.call-summary');
});

Route::group(['prefix' => 'user', 'middleware' => ['auth.guard:user', 'verified'], 'namespace'=>'User'], function() {

    // User/Dashboard
    Route::get('/','UserController@index');
    Route::get('my-profile', 'UserController@index');
    Route::delete('my-profile','UserController@deleteProfile');
    
	// Bookings
    Route::get('bookings', 'AppointmentController@index');
	Route::get('next-appointment/datatables', 'AppointmentController@getNextAppointmentDatatables');
    Route::post('appointment/move', 'AppointmentController@moveAppointment');
    Route::post('appointment/cancel', 'AppointmentController@cancelAppointment');

    // Coach Rating
    Route::get('rate-coach/{appointment_id}', 'AppointmentController@rateCoach');
    Route::post('rate-coach', 'AppointmentController@addRating');

    // User Call Summary 
    Route::get('call-summary', 'AppointmentController@call_summary');


    // Messaging
    Route::get('/messages', 'MessageController@index');
    Route::get('/messages/unread', 'MessageController@ajaxUnreadMessage');
    Route::post('/messages/send', 'MessageController@ajaxSendMessage');
    Route::post('/messages/more', 'MessageController@ajaxMoreMessage');
    Route::post('/messages/makeseen', 'MessageController@ajaxMakeSeen');

    // Request Appointment
    Route::post('appointment-request/{id}/decline', 'AppointmentController@declineRequest');
    Route::post('appointment-request/{id}/accept', 'AppointmentController@acceptRequest');
    
});