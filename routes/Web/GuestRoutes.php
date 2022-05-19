<?php
/** ------------------------------------------
 *  Guest Routes
 *  ------------------------------------------
 */

// prefix :- The prefix method may be used to prefix each route in the group with a given URI. For example, you may want to prefix all route URIs within the group with user:- Matches The "/user" URL
// namespace :- Controllers Within The "App\Http\Controllers\User" Namespace

Route::group(['prefix' => 'gast_nutzer', 'middleware' => ['auth.guard:guest_user'], 'namespace'=>'Guest'], function() {
    // Guest Bookings
    Route::get('/','AppointmentController@index')->name('guest_user');

    // Bookings
    Route::get('buchungen', 'AppointmentController@index')->name('guest_user.bookings');

    // Coach Rating
    Route::get('bewertung/{appointment_id}', 'AppointmentController@rateCoach')->name('guest_user.rate-coach');

    // Guest Call Summary
    Route::get('zusammenfassung', 'AppointmentController@call_summary')->name('guest_user.call-summary');
});

Route::group(['prefix' => 'guest_user', 'middleware' => ['auth.guard:guest_user'], 'namespace'=>'Guest'], function() {

    // Guest Bookings
    Route::get('/','AppointmentController@index');
    Route::get('bookings', 'AppointmentController@index');
    Route::post('appointment/move', 'AppointmentController@moveAppointment');
    Route::post('appointment/cancel', 'AppointmentController@cancelAppointment');

    // Coach Rating
    Route::get('rate-coach/{appointment_id}', 'AppointmentController@rateCoach');
    Route::post('rate-coach', 'AppointmentController@addRating');
    
});