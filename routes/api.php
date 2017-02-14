<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

/* Pre login api's*/

Route::post('/login', 'ApiController@login')->middleware('mobileapi');
Route::post('/verifyloginotp', 'ApiController@verifyloginotp')->middleware('mobileapi');
Route::post('/resendotp', 'ApiController@resendotp')->middleware('mobileapi');
Route::post('/forgetpassword', 'ApiController@forgetpassword')->middleware('mobileapi');
Route::post('/verifyresetotp', 'ApiController@verifyresetotp')->middleware('mobileapi');
Route::post('/resendresetotp', 'ApiController@resendresetotp')->middleware('mobileapi');
Route::post('/resetpassword', 'ApiController@resetpassword')->middleware('mobileapi');
Route::post('/districtlist', 'ApiController@districtlist')/*->middleware('mobileapi')*/;
Route::post('/dashboard_pollbeforeday', 'ApiController@dashboard_pollbeforeday');
Route::get('/eci_date_setting', 'ApiController@eci_date_setting');
Route::get('/login_alert_msg', 'ApiController@login_alert_msg');
Route::get('/mcc_files', 'ApiController@mcc_files');
Route::get('/send_welcome_msg', 'ApiController@send_welcome_msg');
Route::get('/android_version', 'ApiController@android_version');
Route::get('/ios_version', 'ApiController@ios_version');
Route::get('/eci/statelist', 'ApieciController@statelist');
Route::post('/eci/districtlist', 'ApieciController@districtlist');
Route::post('/eci/constituencieslist', 'ApieciController@constituencieslist');

Route::post('/eciComplaint', 'ApieciController@eciComplaint');

Route::group(['prefix' => 'eci', 'as' => 'eci::'/*, 'middleware' => ['mobileapi']*/], function(){
    
    Route::get('/ceolist', 'ApieciController@ceolist');
	Route::post('/rodetail', 'ApiceoController@rodetail');
	Route::post('/observerlist', 'ApieciController@observerlist');
	Route::post('/polling_station_list', 'ApieciController@polling_station_list');
	Route::post('/polling_station_list_type', 'ApieciController@polling_station_list_type');
	Route::post('/ps_awareness_group', 'ApieciController@ps_awareness_group');
	Route::post('/pollbeforeday', 'ApieciController@pollbeforeday');
	Route::post('/pollday', 'ApieciController@pollday');
	Route::post('/polling_percentage', 'ApieciController@polling_percentage');
	Route::post('/polling_percentage_timing', 'ApieciController@polling_percentage_timing');
	Route::post('/polling_station_percentage', 'ApieciController@polling_station_percentage');
	Route::post('/electoral_epic', 'ApieciController@electoral_epic');
	Route::post('/electoral_ps', 'ApieciController@electoral_ps');
	Route::post('/electoral_ps_detail', 'ApieciController@electoral_ps_detail');
	Route::post('/poll_day_report', 'ApieciController@poll_day_report');
	
	Route::post('/eciComplaints', 'ApieciController@eciComplaints');
	Route::post('/eciComplaintsGet', 'ApieciController@eciComplaintsGet');
});

Route::group(['prefix' => 'ceo', 'as' => 'ceo::'/*, 'middleware' => ['mobileapi']*/], function(){
    
    Route::get('/deolist', 'ApiceoController@deolist');
    Route::post('/observerlist', 'ApiceoController@observerlist');
	Route::post('/rodetail', 'ApiceoController@rodetail');
	Route::post('/polling_station_list', 'ApiceoController@polling_station_list');
	Route::post('/ps_awareness_group', 'ApiceoController@ps_awareness_group');
	Route::post('/pollbeforeday', 'ApiceoController@pollbeforeday');
	Route::post('/dashboard_ceo_pollday', 'ApiceoController@dashboard_ceo_pollday');
	Route::post('/dashboard_ceo_pollday_activity', 'ApiceoController@dashboard_ceo_pollday_activity');
	Route::get('/getcommlist', 'ApiceoController@getcommlist');
	Route::post('/getcommdetails', 'ApiceoController@getcommdetails');
});

Route::group(['prefix' => 'deo', 'as' => 'deo::'/*, 'middleware' => ['mobileapi']*/], function(){
    
    Route::post('/rolist', 'ApideoController@rolist');
	Route::post('/rodetail', 'ApideoController@rodetail');
	Route::post('/evmfirstrandomization', 'ApideoController@evmfirstrandomization');
	Route::post('/evmsecondrandomization', 'ApideoController@evmsecondrandomization');
	Route::post('/observerlist', 'ApideoController@observerlist');
	Route::post('/dashboard_deo_pollday', 'ApideoController@dashboard_deo_pollday');
	Route::post('/dashboard_deo_pollday_activity', 'ApideoController@dashboard_deo_pollday_activity');
	Route::get('/getcommlist', 'ApideoController@getcommlist');

});

Route::group(['prefix' => 'ro', 'as' => 'ro::'/*, 'middleware' => ['mobileapi']*/], function(){
    
    Route::post('/supervisorlist', 'ApiroController@supervisorlist');
	Route::post('/sup_ps_list', 'ApiroController@sup_ps_list');
	Route::post('/polling_station_list', 'ApiroController@polling_station_list');
	Route::post('/evmfirstrandomization', 'ApiroController@evmfirstrandomization');
	Route::post('/evmsecondrandomization', 'ApiroController@evmsecondrandomization');
	Route::post('/staff_firstrandomization', 'ApiroController@staff_firstrandomization');
	Route::post('/polling_staff', 'ApiroController@polling_staff');
	Route::post('/polling_staff_detail', 'ApiroController@polling_staff_detail');
	Route::post('/training_list', 'ApiroController@training_list');
	Route::post('/dashboard_ro_pollday', 'ApiroController@dashboard_ro_pollday');
	Route::post('/dashboard_mallfunction_detail', 'ApiroController@dashboard_mallfunction_detail');
	Route::post('/dashboard_laworder_detail', 'ApiroController@dashboard_laworder_detail');
	Route::post('/dashboard_ro_pollday_activity', 'ApiroController@dashboard_ro_pollday_activity');
	Route::post('/poll_nextday_report', 'ApiroController@poll_nextday_report');
	Route::post('/consolidated_detail', 'ApiroController@consolidated_detail');
	Route::post('/reolved_mallfunction_detail', 'ApiroController@reolved_mallfunction_detail');
	Route::post('/add_voters_ballot', 'ApiroController@add_voters_ballot');
	Route::post('/view_voters_ballot', 'ApiroController@view_voters_ballot');
	Route::post('/pwd_request_list', 'ApiroController@pwd_request_list');
	Route::post('/pwd_request_detail', 'ApiroController@pwd_request_detail');
	Route::post('/nominationReceived', 'ApiroController@nominationReceived');
	Route::post('/nominationRejected', 'ApiroController@nominationRejected');
	Route::post('/nominationWithdrawls', 'ApiroController@nominationWithdrawls');
	Route::post('/nominationFinallist', 'ApiroController@nominationFinallist');
	Route::post('/candidateDetail', 'ApiroController@candidateDetail');
	Route::post('/get_complaints', 'ApiroController@get_complaints');

});


Route::group(['prefix' => 'sup', 'as' => 'sup::'/*, 'middleware' => ['mobileapi']*/], function(){
    
    Route::post('/evm_laworder_count', 'ApisupController@evm_laworder_count');
	Route::post('/evm_malfunction_list', 'ApisupController@evm_malfunction_list');
	Route::post('/supervisor_detail', 'ApisupController@supervisor_detail');
	Route::post('/supervisor_ps_list', 'ApisupController@supervisor_ps_list');
	Route::post('/ps_detail', 'ApisupController@ps_detail');
	Route::post('/poll_day_status', 'ApisupController@poll_day_status');
	Route::post('/evm_vvpat_list', 'ApisupController@evm_vvpat_list');
	Route::post('/pro_request_list', 'ApisupController@pro_request_list');
	Route::post('/election_material_receive_report', 'ApisupController@election_material_receive_report');
	Route::post('/election_material_return_report', 'ApisupController@election_material_return_report');
	Route::post('/pollbeforeday', 'ApisupController@pollbeforeday');
	Route::post('/polling_percentage', 'ApisupController@polling_percentage');
	Route::post('/polling_percentage_timing', 'ApisupController@polling_percentage_timing');
	Route::post('/route_plan', 'ApisupController@route_plan');
    Route::post('/training_list', 'ApisupController@training_list');
	Route::post('/dashboard_pollday', 'ApisupController@dashboard_pollday');
	Route::post('/dashboard_pollday_activity', 'ApisupController@dashboard_pollday_activity');
   	Route::post('/reserve_list_cu', 'ApisupController@reserve_list_cu');
	Route::post('/reserve_list_bu', 'ApisupController@reserve_list_bu');
	Route::post('/evm_mallfunction_action', 'ApisupController@evm_mallfunction_action');

});

Route::group(['prefix' => 'pro', 'as' => 'pro::'/*, 'middleware' => ['mobileapi']*/], function(){
    
    Route::post('/polling_station', 'ApiproController@polling_station');
	Route::post('/ps_evm', 'ApiproController@ps_evm');
	Route::post('/collection_dispatch_center', 'ApiproController@collection_dispatch_center');
	Route::post('/poll_before_activity', 'ApiproController@poll_before_activity');
	Route::post('/poll_before_activity_update', 'ApiproController@poll_before_activity_update');
	Route::post('/poll_day_activity', 'ApiproController@poll_day_activity');
	Route::post('/poll_day_activity_update', 'ApiproController@poll_day_activity_update');
	Route::post('/evm_malfunctioning', 'ApiproController@evm_malfunctioning');
	Route::post('/poll_day_percentage_detail', 'ApiproController@poll_day_percentage_detail');
	Route::post('/poll_day_percentage', 'ApiproController@poll_day_percentage');
	Route::post('/law_order_status', 'ApiproController@law_order_status');
	Route::post('/law_order', 'ApiproController@law_order');
	Route::post('/poll_day_request', 'ApiproController@poll_day_request');
	Route::post('/evm_malfunctioning_status', 'ApiproController@evm_malfunctioning_status');
   	Route::post('/polic_personnel', 'ApiproController@polic_personnel');
	Route::post('/law_order_list', 'ApiproController@law_order_list');
	/* Route::post('/poll_after_activity', 'ApiproController@poll_after_activity');
	Route::post('/poll_after_activity_update', 'ApiproController@poll_after_activity_update');*/

});


Route::group(['prefix' => 'voter', 'as' => 'voter::'/*, 'middleware' => ['mobileapi']*/], function(){
    
    Route::post('/voter_detail', 'ApivoterController@voter_detail');
	Route::post('/know_voter_data', 'ApivoterController@know_voter_data');
    Route::post('/voters_token', 'ApivoterController@voters_token');
	Route::post('/voters_alert_detail', 'ApivoterController@voters_alert_detail');
	Route::get('/voters_alert_list', 'ApivoterController@voters_alert_list');
	Route::post('/pwd_voters_search', 'ApivoterController@pwd_voters_search');
    Route::post('/pwd_request', 'ApivoterController@pwd_request');
    Route::get('/voters_video_list', 'ApivoterController@voters_video_list');
    Route::get('/alertmessage', 'ApivoterController@alertmessage');
    Route::post('/polling_station_percentage', 'ApivoterController@polling_station_percentage');

});

Route::group(['prefix' => 'candidate', 'as' => 'candidate::'/*, 'middleware' => ['mobileapi']*/], function(){
    
    Route::post('/ps_list', 'ApicandidateController@ps_list');
    Route::post('/evm_vvpat_list', 'ApicandidateController@evm_vvpat_list');
    Route::post('/counting_center', 'ApicandidateController@counting_center');
    Route::post('/candidate_profile', 'ApicandidateController@candidate_profile');
    Route::post('/candidate_alert', 'ApicandidateController@candidate_alert');
    Route::get('/checklogin_candidate', 'ApicandidateController@checklogin_candidate');

});

Route::group(['prefix' => 'sweep', 'as' => 'sweep::'/*, 'middleware' => ['mobileapi']*/], function(){
	Route::post('/get_events', 'ApisweepController@get_events');
	Route::post('/get_events_detail', 'ApisweepController@get_events_detail');
	Route::post('/districtlist', 'ApisweepController@districtlist');
});

Route::group(['prefix' => 'pat', 'as' => 'pat::'/*, 'middleware' => ['mobileapi']*/], function(){
	Route::post('/polling_stations', 'ApipatController@polling_stations');
	Route::post('/add_time_queue', 'ApipatController@add_time_queue');
	Route::post('/get_time_queue', 'ApipatController@get_time_queue');
});

Route::group(['prefix' => 'media', 'as' => 'media::'/*, 'middleware' => ['mobileapi']*/], function(){
	Route::post('/polling_station_list', 'ApimediaController@polling_station_list');
	Route::post('/candidate_list', 'ApimediaController@candidate_list');
	Route::post('/counting_center', 'ApimediaController@counting_center');
	Route::get('/media_cons', 'ApimediaController@media_cons');
});
Route::get('/test',function(Request $request){
	// $headers = array();
	// foreach ($_SERVER as $key => $value) {
	//     if (strpos($key, 'HTTP_') === 0) {
	//         $headers[str_replace(' ', '', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))))] = $value;
	//     }
	// }
	// print_r($headers);

	if (isset($_SERVER['HTTP_AUTHORIZATION'])) {

		$shared_key = "rHu[q#74FM";
		$time = $_SERVER['REQUEST_TIME'];
		//$authorization = explode(':',base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
		$username = "9463243034";
		$password = "prelogin";
		$new_key = $password.":".$shared_key.":".$username.":".$time;
		echo $encode = base64_encode($new_key);
		die();
		//echo $encode = $_SERVER['HTTP_AUTHORIZATION'];

		// $aa = base64_decode($encode);
		// print_r($aa);
		// die();

       //  if (strpos(strtolower($_SERVER['HTTP_AUTHORIZATION']),'basic')===0)
       //    $dd = explode(':',base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
      	// print_r($dd);
      	$_SERVER['REQUEST_TIME'];
	}

});
