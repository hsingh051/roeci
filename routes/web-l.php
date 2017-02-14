<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::post('/postlogin', 'HomeController@postlogin');

Auth::routes();

Route::get('/', function () {
    return view('home');
});

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('/otpverification', 'HomeController@otpverification');

Route::post('/verifyloginotp', 'HomeController@verifyloginotp');

Route::post('/resendotp', 'HomeController@resendotp');

Route::get('/forget', 'HomeController@forget');

Route::post('/resetnumber', 'HomeController@resetnumber');

Route::get('/pwdotpverification', 'HomeController@pwdotpverification');

Route::post('/enterotp', 'HomeController@enterotp');

Route::get('/reset', 'HomeController@reset');

Route::post('/resendotppwd', 'HomeController@resendotppwd');

Route::post('/resetpassword', 'HomeController@resetpassword');

Route::get('/', 'HomeController@index');

Route::get('/home', 'HomeController@index');

Route::get('/test/addpro', 'TestController@addpro');
Route::get('/test/addbooth', 'TestController@addbooth');
Route::get('/test/addboothvote', 'TestController@addboothvote');
Route::get('/test/addboothdetails', 'TestController@addboothdetails');
Route::post('/test/loginpro', 'TestController@loginpro');

Auth::routes();
/*With auth login*/
Route::group(['prefix' => 'eci', 'as' => 'eci::', 'middleware' => ['web', 'auth']], function(){
    Route::get('/dashboard', 'EciController@dashboard');
    Route::get('/', 'EciController@dashboard');
});

Route::group(['prefix' => 'ceo', 'as' => 'ceo::', 'middleware' => ['web', 'auth']], function(){
    Route::get('/dashboard', 'CeoController@dashboard');
    Route::get('/', 'CeoController@dashboard');
    Route::get('/deoList', 'CeoController@deoList');
    Route::get('/addDeo', 'CeoController@addDeo');
});

Route::group(['prefix' => 'deo', 'as' => 'deo::', 'middleware' => ['web', 'auth']], function(){
    Route::get('/dashboard', 'DeoController@dashboard');
    Route::get('/', 'DeoController@dashboard');
});

Route::group(['prefix' => 'ro', 'as' => 'ro::', 'middleware' => ['web', 'auth']], function(){
    Route::get('/dashboard', 'RoController@dashboard');
    Route::get('/', 'RoController@dashboard');
	Route::get('/electoral-rolls', 'RoController@electoralRolls');
	Route::get('/nomination-received', 'RoController@nominationReceived');
	Route::get('/new-nomination', 'RoController@newNomination');
	Route::get('/nomination-rejected', 'RoController@nominationRejected');
	Route::get('/nomination-withdrawls', 'RoController@nominationWithdrawls');
	Route::get('/candidate-list', 'RoController@candidateList');
	Route::get('/send-notice-candidate', 'RoController@sendNoticeCandidate');
	Route::get('/allot-symbols', 'RoController@allotSymbols');
	Route::get('/candidate-detail', 'RoController@candidateDetail');
	Route::get('/evm-vvpat', 'RoController@evmVvpat');
	Route::get('/polling-staff', 'RoController@pollingStaff');
	Route::get('/polling-parties-details', 'RoController@pollingPartiesDetails');
	Route::get('/training', 'RoController@training');
	Route::get('/election-material', 'RoController@electionMaterial');

});