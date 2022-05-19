<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'Auth\LoginController@showLoginForm');
Route::get('login/{guard?}', 'Auth\LoginController@showLoginForm')->name('login');
Route::get('logout/{guard?}','Auth\LoginController@logout');

// Guest login
Route::get('guest-login/{userId}', 'Auth\LoginController@guestLogin')->middleware('guest')->name('guest.login');

Auth::routes(['verify' => true]);

// Password Reset Custom name routes
Route::get('passwort-vergessen', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::get('passwort-vergessen/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');

require __DIR__ . '/Web/AdminRoutes.php';
require __DIR__ . '/Web/FrontRoutes.php';
require __DIR__ . '/Web/UserRoutes.php';
require __DIR__ . '/Web/CoachRoutes.php';
require __DIR__ . '/Web/GuestRoutes.php';