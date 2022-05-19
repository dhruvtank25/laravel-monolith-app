<?php
/** ------------------------------------------
 *  Coach Routes
 *  ------------------------------------------
 */

// prefix :- The prefix method may be used to prefix each route in the group with a given URI. For example, you may want to prefix all route URIs within the group with coach:- Matches The "/coach" URL
// namespace :- Controllers Within The "App\Http\Controllers\Coach" Namespace

Route::put('coach/my-profile/update', 'Coach\UserController@update')->middleware('auth.guard:coach');

/** For SEO purpose */
Route::group(['prefix' => 'berater', 'middleware' => ['auth.guard:coach', 'verified:coach'], 'namespace'=>'Coach'], function() {
    //Dashboard
    Route::get('/', 'UserController@dashboard')->name('coach');
    // Users
    Route::get('mein-profil', 'UserController@index')->name('coach.my-profile');
    Route::get('buchungen', 'AppointmentController@index')->name('coach.bookings');
    Route::get('blog', 'UserController@blog')->name('coach.blog');
    // Availabilties
    Route::get('verfuegbarkeit', 'AvailabilityController@index')->name('coach.availabilites');
    // Messages
    Route::get('mitteilungen', 'MessageController@index')->name('coach.messages');
});

Route::group(['prefix' => 'coach', 'middleware' => ['auth.guard:coach', 'verified:coach'], 'namespace'=>'Coach'], function() {

    //Dashboard
    Route::get('/', 'UserController@dashboard');
    Route::get('dashboard', ['as' => 'coach.dashboard', 'uses' => 'UserController@dashboard']);

    // Users
    Route::get('my-profile', 'UserController@index');
    Route::get('bookings', 'AppointmentController@index');
    Route::get('blog', 'UserController@blog');
    Route::delete('my-profile','UserController@deleteProfile');

    // Availabilties
    Route::get('availabilites', 'AvailabilityController@index');
    Route::post('availability', 'AvailabilityController@store');
    Route::post('availability/get-slots', 'AvailabilityController@getDateSlots');
    Route::get('availability/get-statuses', 'AvailabilityController@getAvailabilityStatuses');

    // Unavailabilities
    Route::post('coaches/unavailabilities', 'AvailabilityController@storeUnavailability');
    Route::delete('coaches/unavailabilities/{coach_id}', 'AvailabilityController@destroyUnavailability');
    Route::get('coaches/unavailabilities/{coach_id}', 'AvailabilityController@getUnavailabilityDataTables');

    // Appointments
    Route::get('datatables/appointments/{type}', 'AppointmentController@getDatatables');
    Route::post('appointment/cancel', 'AppointmentController@cancelAppointment');
    Route::get('appointment/download/{appointment_id}', 'AppointmentController@downloadInvoice');

    // Request Appointment
    Route::post('appointment-request/{id}/decline', 'AppointmentController@declineRequest');
    Route::post('appointment-request/{id}/accept', 'AppointmentController@acceptRequest');

    // Messages
    Route::get('messages', 'MessageController@index');
    Route::post('new-conversation', 'MessageController@storeConversation');
    Route::post('upload-attachments', 'MessageController@uploadAttachments');
    Route::post('messages/makeseen/{thread_id}', 'MessageController@markThreadSeen');
    Route::get('messages/unread-thread', 'MessageController@getUnreadThreads');
    
});