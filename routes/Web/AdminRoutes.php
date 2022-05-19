<?php
/** ------------------------------------------
 *  Admin Routes
 *  ------------------------------------------
 */

// prefix :- The prefix method may be used to prefix each route in the group with a given URI. For example, you may want to prefix all route URIs within the group with admin:- Matches The "/admin" URL
// namespace :- Controllers Within The "App\Http\Controllers\Admin" Namespace

Route::get('shadowlogin/{user}/{id}','Auth\LoginController@shadowLogin');
Route::group(['prefix' => 'admin', 'middleware' => ['auth.guard:admin'], 'namespace'=>'Admin'], function() {

	//Dashboard
	Route::get('/','DashboardController@index')->name('admin');
	Route::get('dashboard', ['as' => 'admin.dashboard', 'uses' => 'DashboardController@index']);

    // Countries
    Route::resource('countries', 'CountryController');
    Route::get('countries/sync', 'CountryController@sync');

    // Categories
    Route::post('categories/ckeditor/images', 'CategoryController@uploadCkeditorImage');
    Route::resource('categories', 'CategoryController');

    // Companies
    Route::resource('companies', 'CompanyController');

    // Reviews
    Route::get('reviews/datatables', 'ReviewController@getDatatables');
    Route::resource('reviews', 'ReviewController');

    // Faqs
    Route::resource('faqs', 'FAQController');

    // What Coach Says
    Route::resource('coach-says', 'CoachSayController');

	// Users
	Route::get('users/datatables/{usertype?}','UserController@getDatatables')->name('datatables.userdataTables');
    Route::get('users/update-status/{id}', 'UserController@updateStatus');
	Route::resource('users', 'UserController');

    // Guests
    Route::get('guests', 'UserController@indexGuests');
    Route::get('guests/{id}', 'UserController@show');

    /** COACH START **/
    Route::get('coaches', 'UserController@indexCoaches');
    Route::get('coaches/create', 'UserController@create');
    Route::get('coaches/{id}', 'UserController@show');
    Route::get('coaches/{id}/edit', 'UserController@edit');

    // Availabilties
    Route::get('coaches/availability/{userid}', 'AvailabilityController@index');
    //Route::post('coaches/availability/{userid}', 'AvailabilityController@store');
    Route::post('availability', 'AvailabilityController@store');
    Route::post('availability/get-slots', 'AvailabilityController@getDateSlots');
    Route::get('availability/get-statuses', 'AvailabilityController@getAvailabilityStatuses');

    // Unavailabilities
    Route::post('coaches/unavailabilities', 'AvailabilityController@storeUnavailability');
    Route::delete('coaches/unavailabilities/{coach_id}', 'AvailabilityController@destroyUnavailability');
    Route::get('coaches/unavailabilities/{coach_id}', 'AvailabilityController@getUnavailabilityDataTables');

    // Appointment (Schedule)
    Route::get('appointment', 'AppointmentController@index');
    Route::get('appointment/datatables', 'AppointmentController@getDatatables');
    Route::get('appointment/{id}', 'AppointmentController@show');
    Route::post('appointment/add-note/{id}', 'AppointmentController@addNote');
    Route::get('coaches/schedule/events', 'AppointmentController@fullCalendarData');
    Route::post('coaches/schedule/new', 'AppointmentController@store');
    Route::get('coaches/schedule/{userid}', 'AppointmentController@schedule');
    Route::get('scheduler', 'AppointmentController@scheduler');

    // Transactions
    Route::get('user-transactions/datatables/{id}', 'UserController@getTransactionDataTables');
    Route::get('appointment-transactions/datatables/{id}', 'AppointmentController@getTransactionDataTables');

    /** COACH END **/

    // Messaging
    Route::get('messages', 'MessageController@index');
    Route::post('new-conversation', 'MessageController@storeConversation');
    Route::post('upload-attachments', 'MessageController@uploadAttachments');
    Route::post('messages/makeseen/{thread_id}', 'MessageController@markThreadSeen');
    Route::get('messages/unread-thread', 'MessageController@getUnreadThreads');
    /*Route::get('/messages', 'MessageController@index');
    Route::get('/messages/unread', 'MessageController@ajaxUnreadMessage');
    Route::post('/messages/send', 'MessageController@ajaxSendMessage');
    Route::post('/messages/more', 'MessageController@ajaxMoreMessage');
    Route::post('/messages/makeseen', 'MessageController@ajaxMakeSeen');*/


});