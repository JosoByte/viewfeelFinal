<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'App\Http\Controllers\Controller@getWelcomeIndex')->name('welcome');
Route::get('/checkuser', 'App\Http\Controllers\Controller@getCurrentUserDisplay')->name('getCurrentUserDisplay');
Route::get('/upload', 'App\Http\Controllers\Controller@getUploadIndex')->middleware('auth')->name('getUploadIndex');
Route::get('/user/{username}/{art}', 'App\Http\Controllers\Controller@getArtIndex')->middleware('auth')->name('getArtIndex');
Route::post('/uploadFile', 'App\Http\Controllers\Controller@uploadFile')->middleware('auth')->name('uploadFile');
Route::post('/sendComment', 'App\Http\Controllers\Controller@sendComment');
Route::get('/logoff', function () {
    Auth::logout();
    return redirect('/');
})->name('logoff');
Auth::routes();
Route::get('/testMail', 'App\Http\Controllers\Controller@sendEmail');
Route::get('/confirmMail/{user}/{token}', 'App\Http\Controllers\Controller@confirmEmail');
Route::resource('/password/reset', App\Http\Controllers\Auth\PasswordResetController::class);
Route::post('/storeReset', 'App\Http\Controllers\Auth\PasswordResetController@store')->name('storeReset');
Route::post('/updateLikes/{artistName}/{artIndex}', 'App\Http\Controllers\Controller@updateLikes');
Route::post('/updateProfile', 'App\Http\Controllers\Controller@updateProfile')->name('updateProfile');
Route::get('/delProfile', 'App\Http\Controllers\Controller@delProfile')->name('delProfile');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/user/{username}', 'App\Http\Controllers\Controller@getUserPage');
Route::get('/testFirebase', 'App\Http\Controllers\FirebaseController@testFirebase');
Route::post('login/{provider}/callback', 'Auth\LoginController@handleCallback');

//upload profile
