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

Route::get('/', 'App\Http\Controllers\Controller@getWelcomeIndex')->middleware('confirmed')->name('welcome');
Route::get('/contacto', 'App\Http\Controllers\Controller@contactoIndex')->name('contacto');
Route::get('/checkuser', 'App\Http\Controllers\Controller@getCurrentUserDisplay')->name('getCurrentUserDisplay');
Route::get('/upload', 'App\Http\Controllers\Controller@getUploadIndex')->middleware('auth')->name('getUploadIndex');
Route::get('/user/{username}/{art}', 'App\Http\Controllers\Controller@getArtIndex')->middleware('auth')->name('getArtIndex');
Route::post('/uploadFile', 'App\Http\Controllers\Controller@uploadFile')->middleware('auth')->name('uploadFile');
Route::post('/uploadPin', 'App\Http\Controllers\Controller@uploadPin')->middleware('auth')->name('uploadPin');
Route::post('/sendComment', 'App\Http\Controllers\Controller@sendComment');
Route::get('/logoff', function () {
    Auth::logout();
    return redirect('/');
})->name('logoff');
Auth::routes();
Route::post('/contactEmail', 'App\Http\Controllers\Controller@contactEmail')->name("contactEmail");
Route::get('/checkRate', 'App\Http\Controllers\Controller@checkRate');
Route::post('/rate',  'App\Http\Controllers\Controller@rate');
Route::get('/testLine', 'App\Http\Controllers\Controller@testLine');
Route::get('/map', 'App\Http\Controllers\Controller@mapIndex')->name('map');
Route::post('/search', 'App\Http\Controllers\Controller@searchIndex')->name('search');
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
