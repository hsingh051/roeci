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

Route::post('/postlogin/', 'HomeController@postlogin');

Route::get('/', function () {
    return view('home');
});

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
Route::get('/otpverification', 'HomeController@otpverification');
Route::get('/select_state', 'HomeController@select_state');
Route::post('/select_state', 'HomeController@state');
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
Route::get('/pagenotfound', 'HomeController@pagenotfound');
Route::get('/api/pagenotfound', 'HomeController@pagenotfound');
Route::get('/test/addpro', 'TestController@addpro');
Route::get('/test/addbooth', 'TestController@addbooth');
Route::get('/test/addboothvote', 'TestController@addboothvote');
Route::get('/test/addboothdetails', 'TestController@addboothdetails');
Route::post('/test/loginpro', 'TestController@loginpro');

Auth::routes();
/*With auth login*/
Route::group(['prefix' => 'eci', 'as' => 'eci::', 'middleware' => ['web', 'auth']], function(){
	Route::get('/pagenotfound', 'EciController@pagenotfound');
    Route::get('/dashboard', 'EciController@dashboard');
    Route::get('/', 'EciController@dashboard');
    Route::get('/ceo_list', 'EciController@ceolist');
    Route::get('/add_ceo', 'EciController@addCeo');
    Route::post('/addceoSub', 'EciController@addceoSub');
    Route::get('/editCeo/{uid}', 'EciController@editCeo');
    Route::post('/editCeoSub', 'EciController@editCeoSub');
    Route::post('/deleteCeo/', 'EciController@deleteCeo');
    Route::get('/dist-electrollist', 'EciController@distElectrolList');
    Route::get('/cons-electrollist/{dist_code}', 'EciController@consElectrolList');
    Route::get('/ps-electrollist/{cons_code}', 'EciController@psElectrolList');
    Route::get('/electrollist/{ps_id}', 'EciController@electrolList');
    Route::get('/voter-detail/{iCard}', 'EciController@voterDetail');
	Route::get('/political-parties-district-head', 'EciController@politicalPartiesDistrictHead');
    Route::get('/political-parties-state-head', 'EciController@PoliticalPartiesStateHead');
	Route::get('/evm-vvpat-pending', 'EciController@evmVvpatPending');
	Route::get('/polling-staff', 'EciController@pollingStaff');
	Route::get('/polling-stations', 'EciController@pollingStations');
	Route::get('/electoral-rolls', 'EciController@electoralRolls');
	Route::get('/dist-pollstationlist', 'EciController@distPollstationlist');
	Route::get('/cons-pollstationlist/{distCode}', 'EciController@consPollstationlist');
	Route::get('/cons-polling-staff/{consCode}', 'EciController@consPollingStaff');
	Route::get('/polling-stations-map/{consCode}', 'EciController@pollingStationsMap');
	Route::get('/booth-awareness-group/{bid}', 'EciController@boothAwarenessGroup');
	Route::get('/stafflist', 'EciController@stafflist');
	Route::get('/candidate-list', 'EciController@candidateList');
	Route::get('/candidate-affidavit/{cand_sl_no}/{cons_code}', 'EciController@candidateaffidavit');
	Route::get('/candidate-list-search', 'EciController@candidateListSearch');
	Route::get('/complaint', 'EciController@complaint');
    Route::get('/complaint-detail/{id}', 'EciController@complaintDetail');
    Route::get('/information', 'EciController@information');
    Route::get('/suggestion', 'EciController@suggestion');
	//--P-1 day
    Route::get('/p-1Form', 'EciController@pMinus1Form');
    Route::post('/getCons', 'EciController@getCons');
    Route::get('/p-1Sub', 'EciController@pMinus1FormSubmit');
    Route::get('/pol-1View', 'EciController@poMinus1View');
	//poll day
	Route::get('/poll-day-report', 'EciController@pollDayReport');
	Route::get('/poll-day', 'EciController@pollDay');
    //-- Observer
    Route::get('/election-observers', 'EciController@electionObservers');
    Route::get('/electionObserverSearch', 'EciController@electionObserverSearch');
    Route::get('/observer-profile/{id}', 'EciController@observerProfile');
    Route::get('/p1-scrutiny', 'EciController@p1Scrutiny');
	Route::get('/p1-scrutiny-search', 'EciController@p1ScrutinySearch');
	Route::get('/p1-consolidated-report', 'EciController@p1ConsolidatedReport');
	Route::get('/p1ConsolidatedReportSearch', 'EciController@p1ConsolidatedReportSearch');
    Route::get('/add-new-observer', 'EciController@addNewObserver');
    Route::post('/addObserverSubmit', 'EciController@addObserverSubmit');
    Route::get('/edit-observer/{uid}', 'EciController@editObserver');
    Route::post('/updateObserver/', 'EciController@updateObserver');
    Route::get('/delete-observer/{uid}', 'EciController@delObserver');
    Route::get('/polling-detail/{ps_id}', 'EciController@pollingDetail');
    Route::get('/polling-parties-details/{bid}', 'EciController@pollingPartiesDetails');
    Route::get('/booth-photos/{bid}', 'EciController@boothPhotos');
    Route::post('/getPollingCons', 'EciController@getPollingCons');
    Route::get('/eciPollStationSub', 'EciController@eciPollStationSub');
    Route::get('/pre-poll-arrangement', 'EciController@prePollArrangement');
    Route::get('/prePollSub', 'EciController@prePollSub');
    Route::get('/polling-staff', 'EciController@pollingStaff');
    Route::get('/pollingStaffSub', 'EciController@pollingStaffSub');
    Route::get('/poll-percentage', 'EciController@pollPercentage');
	Route::get('/pollPercentagetiming', 'EciController@pollPercentagetiming');
	Route::get('/evm-vvpat', 'EciController@evmVvpat');
	Route::get('/evmVvpatSearch', 'EciController@evmVvpatSearch');
	Route::get('/polling-percentage-detail/{bid}', 'EciController@pollingPercentageDetail');
    //Route::get('/lawOrder', 'EciController@lawOrder');
    Route::get('/dispatch-collection-center', 'EciController@dispatchCollectionCenter');
    Route::get('/dispatchCollectionCenterSub', 'EciController@dispatchCollectionCenterSub');
    Route::get('/postal-ballot', 'EciController@postalBallot');
    Route::get('/postal-ballot-sub', 'EciController@postalBallotSub');
    Route::get('/evm-malfunction', 'EciController@evmMalfunction');
    Route::get('/evm-malfunction-sub', 'EciController@evmMalfunctionSub');
    Route::get('/supervisor-detail/{id}', 'EciController@supervisorDetail');

    Route::get('/suvidha', 'EciController@suvidha');
    Route::get('/suvidhaSub', 'EciController@suvidhaSub');
    Route::get('/suvidha-detail/{sid}', 'EciController@suvidhaDetail');

    Route::get('/pwd-voters', 'EciController@pwdVoters');
    Route::get('/pwd-voters-sub', 'EciController@pwdVoterSub');
	Route::get('/law-order', 'EciController@lawOrder');
    Route::get('/law-order-sub', 'EciController@lawOrderSub');
	
	Route::get('/police-data', 'EciController@policeData');
	Route::get('/voter-slip-data', 'EciController@voterSlipData');
	Route::get('/policeDataResult', 'EciController@policeDataResult');
	Route::get('/voterslipDataResult', 'EciController@voterslipDataResult');
    Route::get('/facilities/{bid}', 'EciController@facilities');

    Route::get('/video-recording', 'EciController@videoRecording');
    Route::get('/video-recording-sub', 'EciController@videoRecordingSub');

});

Route::group(['prefix' => 'ceo', 'as' => 'ceo::', 'middleware' => ['web', 'auth']], function(){
	Route::get('/pagenotfound', 'CeoController@pagenotfound');
    Route::get('/dashboard', 'CeoController@dashboard');
    Route::get('/', 'CeoController@dashboard');
    Route::get('/deoList', 'CeoController@deoList');
    Route::get('/political-parties-district-head', 'CeoController@politicalPartiesDistrictHead');
    Route::get('/political-parties-state-head', 'CeoController@PoliticalPartiesStateHead');
    Route::get('/add-political-parties-state-head', 'CeoController@addPoliticalPartiesStateHead');
    Route::get('/electoral-rolls', 'CeoController@electoralRolls');
    Route::get('/deo-list', 'CeoController@deoList');
    Route::get('/pending-evm-vvpat', 'CeoController@pendingEvmVvpat');
    Route::get('/dist-electrollist', 'CeoController@distElectrolList');
    Route::get('/cons-electrollist/{dist_code}', 'CeoController@consElectrolList');
    Route::get('/ps-electrollist/{cons_code}', 'CeoController@psElectrolList');
    Route::get('/electrollist/{ps_id}', 'CeoController@electrolList');
    Route::get('/voter-detail/{iCard}', 'CeoController@voterDetail');
    
    //--Add Users (Excel)
    Route::get('/addDeo', 'CeoController@addDeo');
    Route::post('/addDeoSub', 'CeoController@addDeoSub');
    Route::get('/editDeo/{uid}', 'CeoController@editDeo');
    Route::post('/editDeoSub', 'CeoController@editDeoSub');
    Route::post('/deleteDeo', 'CeoController@deleteDeo');
    //--Supervisor Booth (Excel)
    // Route::get('/supBooth', 'CeoController@supBooth');
    // Route::post('/supBoothSub', 'CeoController@supBoothSub');
    //--Update Booth Type (Excel)
    Route::get('/upBoothType', 'CeoController@upBoothType');
    Route::post('/upBoothTypeSub', 'CeoController@upBoothTypeSub');
    //--Add DEO form
    Route::get('/addDeoForm', 'CeoController@addDeoForm');
    Route::post('/addDeoFormSub', 'CeoController@addDeoFormSub');
    //--P-1 day
    Route::get('/p-1Form', 'CeoController@pMinus1Form');
    Route::post('/getCons', 'CeoController@getCons');
    Route::get('/p-1Sub', 'CeoController@pMinus1FormSubmit');
    Route::get('/pol-1View', 'CeoController@poMinus1View');
    Route::get('/dist-pollstationlist', 'CeoController@distPollstationlist');
    Route::get('/cons-pollstationlist/{distCode}', 'CeoController@consPollstationlist');
    Route::get('/cons-polling-staff/{id}', 'CeoController@consPollingStaff');
    Route::get('/polling-stations-map/{consCode}', 'CeoController@pollingStationsMap');
    Route::get('/polling-detail/{ps_id}', 'CeoController@pollingDetail');
	Route::post('/getpslist/', 'CeoController@getpslist');
	Route::get('/electoral-rolls-submit/', 'CeoController@electoralRollsSubmit');
	Route::get('/nomination-received', 'CeoController@nominationReceived');
	Route::post('/nomination-received', 'CeoController@nominationReceivedpost');
	Route::get('/candidate-detail/{uid}', 'CeoController@candidateDetail');
	Route::get('/nomination-rejected', 'CeoController@nominationRejected');
	Route::get('/nomination-withdrawls', 'CeoController@nominationWithdrawls');
	Route::get('/candidate-list', 'CeoController@candidateList');
	Route::get('/nomination-received-search', 'CeoController@nominationReceivedSearch');
	Route::get('/nomination-rejected-search', 'CeoController@nominationRejectedSearch');
	Route::get('/nomination-withdrawls-search', 'CeoController@nominationWithdrawlsSearch');
	Route::get('/candidate-list-search', 'CeoController@candidateListSearch');
	Route::get('/candidate-affidavit/{cand_sl_no}/{cons_code}', 'CeoController@candidateaffidavit');
	//poll day
	Route::get('/poll-day-report', 'CeoController@pollDayReport');
	Route::get('/poll-day', 'CeoController@pollDay');	
    Route::get('/booth-awareness-group/{bid}', 'CeoController@boothAwarenessGroup');
    Route::get('/polling-parties-details/{bid}', 'CeoController@pollingPartiesDetails');
    Route::get('/booth-photos/{bid}', 'CeoController@boothPhotos');
    Route::get('/polling-stations', 'CeoController@pollingStations');
    Route::get('/ceoPollStationSub', 'CeoController@ceoPollStationSub');
    Route::post('/getPollingCons', 'CeoController@getPollingCons');
    Route::get('/pre-poll-arrangement', 'CeoController@prePollArrangement');
    Route::get('/prePollSub', 'CeoController@prePollSub');
    Route::get('/complaint', 'CeoController@complaint');
    Route::get('/complaint-detail/{id}', 'CeoController@complaintDetail');
    Route::get('/information', 'CeoController@information');
    Route::get('/suggestion', 'CeoController@suggestion');
    Route::get('/suvidha', 'CeoController@suvidha');
	Route::get('/suvidhaSub', 'CeoController@suvidhaSub');
	Route::get('/suvidha-detail/{sid}', 'CeoController@suvidhaDetail');
	Route::get('/poll-percentage', 'CeoController@pollPercentage');
	Route::get('/pollPercentagetiming', 'CeoController@pollPercentagetiming');
	Route::get('/election-observers', 'CeoController@electionObservers');
	Route::get('/electionObserverSearch', 'CeoController@electionObserverSearch');
	Route::get('/observer-profile/{id}', 'CeoController@observerProfile');
	Route::get('/p1-scrutiny', 'CeoController@p1Scrutiny');
	Route::get('/p1-scrutiny-search', 'CeoController@p1ScrutinySearch');
	Route::get('/p1-consolidated-report', 'CeoController@p1ConsolidatedReport');
	Route::get('/p1ConsolidatedReportSearch', 'CeoController@p1ConsolidatedReportSearch');
	Route::get('/evm-vvpat', 'CeoController@evmVvpat');
	Route::get('/evmVvpatSearch', 'CeoController@evmVvpatSearch');
	Route::get('/polling-percentage-detail/{bid}', 'CeoController@pollingPercentageDetail');
	Route::get('/ro-list/{uid}', 'CeoController@roList');
	Route::get('/supervisor-list/{id}', 'CeoController@supervisorList');
	Route::get('/supervisor-detail/{uid}', 'CeoController@supervisorDetail');
	Route::get('/polling-staff', 'CeoController@pollingStaff');
    Route::get('/pollingStaffSub', 'CeoController@pollingStaffSub');
    Route::get('/dispatch-collection-center', 'CeoController@dispatchCollectionCenter');
    Route::get('/dispatchCollectionCenterSub', 'CeoController@dispatchCollectionCenterSub');
    Route::get('/postal-ballot', 'CeoController@postalBallot');
    Route::get('/postal-ballot-sub', 'CeoController@postalBallotSub');
    Route::get('/evm-malfunction', 'CeoController@evmMalfunction');
    Route::get('/evm-malfunction-sub', 'CeoController@evmMalfunctionSub');
    Route::get('/pwd-voters', 'CeoController@pwdVoters');
    Route::get('/pwd-voters-sub', 'CeoController@pwdVoterSub');
	Route::get('/law-order', 'CeoController@lawOrder');
    Route::get('/law-order-sub', 'CeoController@lawOrderSub');
	Route::get('/police-data', 'CeoController@policeData');
	Route::get('/voter-slip-data', 'CeoController@voterSlipData');
	Route::get('/policeDataResult', 'CeoController@policeDataResult');
	Route::get('/voterslipDataResult', 'CeoController@voterslipDataResult');
    Route::get('/facilities/{bid}', 'CeoController@facilities');

    Route::get('/video-recording', 'CeoController@videoRecording');
    Route::get('/video-recording-sub', 'CeoController@videoRecordingSub');
});

Route::group(['prefix' => 'deo', 'as' => 'deo::', 'middleware' => ['web', 'auth']], function(){
	Route::get('/pagenotfound', 'DeoController@pagenotfound');
    Route::get('/dashboard', 'DeoController@dashboard');
    Route::get('/', 'DeoController@dashboard');
    Route::get('/cons-electrollist', 'DeoController@consElectrolList');
    Route::get('/ps-electrollist/{cons_code}', 'DeoController@psElectrolList');
    Route::get('/electrollist/{ps_id}', 'DeoController@electrolList');
    Route::get('/voter-details/{iCard}', 'DeoController@voterDetails');
    /*Nomination */
    Route::get('/nominations-received', 'DeoController@nominationsReceived');
    Route::get('/nomination-received', 'DeoController@nominationReceived');
    Route::get('/nominationRejectedSub', 'DeoController@nominationRejectedSub');
    Route::get('/nominationWithdrawlSub', 'DeoController@nominationWithdrawlSub');
	Route::post('/nomination-received', 'DeoController@nominationReceivedpost');
	Route::get('/candidate-detail/{uid}', 'DeoController@candidateDetail');
	Route::get('/nomination-rejected', 'DeoController@nominationRejected');
	Route::get('/nomination-withdrawls', 'DeoController@nominationWithdrawls');
	Route::get('/candidate-list', 'DeoController@candidateList');
	Route::get('/candidateListSub', 'DeoController@candidateListSub');
	Route::get('/send-notice-candidate', 'DeoController@sendNoticeCandidate');
	Route::get('/allot-symbols', 'DeoController@allotSymbols');
	//Route::get('/candidate-detail/{uid}', 'DeoController@candidateDetail');
	Route::get('/ro-list', 'DeoController@roList');
	Route::get('/add-ro', 'DeoController@addRo');
	Route::post('/addRoSub/', 'DeoController@addRoSub');
	Route::get('/add-ro-csv', 'DeoController@addRoCsv');
	Route::post('/addRoCsvSub/', 'DeoController@addRoCsvSub');
	Route::get('/editRo/{uid}', 'DeoController@editRo');
	Route::post('/editRoSub/', 'DeoController@editRoSub');
	Route::post('/delRo', 'DeoController@delRo');
	Route::get('/polling-staff', 'DeoController@pollingStaff');
	Route::get('/cons-polling-staff/{id}', 'DeoController@consPollingStaff');
	Route::post('/polling-staff', 'DeoController@pollingStaffType');
	Route::get('/pending-stafflist', 'DeoController@pendingPollingStaff');
	Route::get('/add-polling-staff', 'DeoController@addpollingStaff');
    Route::post('/addPollingstaffexcel/', 'DeoController@addPollingstaffexcel');
    Route::post('/addPollingstaffexcel2/', 'DeoController@addPollingstaffexcel2');
    Route::post('/addPollingstaffexcel3/', 'DeoController@addPollingstaffexcel3');
    Route::get('/polling-parties-details/{bid}', 'DeoController@pollingPartiesDetails');
    Route::get('/booth-photos/{bid}', 'DeoController@boothPhotos');
    Route::get('/pollstationlist', 'DeoController@pollstationlist');
    //--Selected Ro Details
	Route::get('/ro-detail/{id}', 'DeoController@roDetail');
	Route::get('/electoral-rolls', 'DeoController@electoralRolls');
	Route::get('/electoral-rolls-submit', 'DeoController@electoralRollsSubmit');
	Route::get('/add-electoral', 'DeoController@addElectoral');
	Route::post('/addElectoralSub/', 'DeoController@addElectoralSub');

	Route::get('/evm-vvpat', 'DeoController@evmVvpat');

	Route::get('/add_evm-vvpat', 'DeoController@addevmVvpat');
	Route::post('/add_evm-vvpat', 'DeoController@add_evm_vvpat');
	Route::get('/candidate-affidavit/{cand_sl_no}/{cons_code}', 'DeoController@candidateaffidavit');
	
	Route::post('/randomFirstSub', 'DeoController@randomFirstSub');
	Route::post('/randomSecondSub', 'DeoController@randomSecondSub');
    Route::get('/search-evm-vvpat', 'DeoController@searchevmVvpat');
	Route::get('/supervisor-list/{id}', 'DeoController@supervisorList');
	Route::get('/supervisor-detail/{uid}', 'DeoController@supervisorDetail');
	Route::get('/political-parties-district-head', 'DeoController@politicalPartiesDistrictHead');
	Route::get('/add-political-parties-district-head', 'DeoController@addPoliticalPartiesDistrictHead');
	Route::get('/editPPdistHead/{uid}', 'DeoController@editPPdistHead');
	Route::post('/editPPdistHeadSubmit', 'DeoController@editPPdistHeadSubmit');
	Route::get('/delPPdistHead/{uid}', 'DeoController@delPPdistHead');
	Route::get('/election-observers', 'DeoController@electionObservers');
	Route::get('/observer-profile/{id}', 'DeoController@observerProfile');
	Route::get('/voter-detail/{iCard}', 'DeoController@voterDetail');
	Route::get('/add-new-observer', 'DeoController@addNewObserver');
	Route::post('/addObserverSubmit', 'DeoController@addObserverSubmit');
	Route::get('/edit-observer/{uid}', 'DeoController@editObserver');
	Route::post('/updateObserver/', 'DeoController@updateObserver');	
	Route::get('/booth-aware-list', 'DeoController@boothAwareList');
	Route::get('/add-booth-aware', 'DeoController@addBoothAware');
	Route::post('/addBoothAwareSub/', 'DeoController@addBoothAwareSub');
	Route::get('/booth-aware-csv', 'DeoController@boothAwareCsv');
	Route::post('/boothAwareCsvSub/', 'DeoController@boothAwareCsvSub');
	Route::get('/pollingStations', 'DeoController@pollingStations');
	Route::get('/polling-detail/{ps_id}', 'DeoController@pollingDetail');
	Route::get('/polling-stations-map', 'DeoController@pollingStationsMap');
	Route::get('/addPollingStation', 'DeoController@addPollingStation');
	Route::post('/addPolStationSubmit/', 'DeoController@addPolStationSubmit');
	Route::get('/polingCsvForm', 'DeoController@polingCsvForm');
	Route::post('/polStationExcelSubmit/', 'DeoController@polStationExcelSubmit');
	Route::post('/addPolPartDistHeadSubmit', 'DeoController@addPolPartDistHeadSubmit');
	//Route::get('/pol-1day', 'DeoController@polMinus1day');
	Route::get('/pol-1day', 'DeoController@constituencySelection');
	Route::get('/pol-1List/', 'DeoController@polMinus1day');
	Route::get('/poll-day-report', 'DeoController@pollDayReport');
	Route::get('/poll-day', 'DeoController@pollDay');
	Route::get('/traning-list', 'DeoController@traningList');
	Route::get('/add-traning', 'DeoController@addTraning');
	Route::post('/addTraningSub/', 'DeoController@addTraningSub');
	Route::get('/edit-traning/{id}', 'DeoController@editTraning');
	Route::post('/editTraningSub/', 'DeoController@editTraningSub');
	Route::post('/delete-traning', 'DeoController@deleteTraning');
	Route::get('/pre-poll-arrangement', 'DeoController@prePollArrangement');
	Route::get('/prePollSub', 'DeoController@prePollSub');
	Route::post('/getpslist/', 'DeoController@getpslist');
	Route::get('/voter-alerts/', 'DeoController@voterAlerts');
	Route::get('/alerts/', 'DeoController@alerts');
	Route::post('/login-media-alerts/', 'DeoController@loginMediaAlerts');
	Route::post('/login-candidate-alerts/', 'DeoController@loginCandidateAlerts');
	Route::post('/send-voter-alerts/', 'DeoController@sendvoterAlerts');
	Route::get('/booth-awareness-group/{bid}', 'DeoController@boothAwarenessGroup');
	//Route::get('/polling-detail/{ps_id}', 'DeoController@pollingDetail');
	Route::get('/booth-awareness-group/{bid}', 'DeoController@boothAwarenessGroup');
	Route::get('/polling-parties-details/{bid}', 'DeoController@pollingPartiesDetails');
	Route::get('/consPollStationSub', 'DeoController@consPollStationSub');
	Route::get('/webCastingUpdate', 'DeoController@webCastingUpdate');
	Route::post('/webCastingSub/', 'DeoController@webCastingSub');
	Route::get('/complaint', 'DeoController@complaint');
	Route::get('/information', 'DeoController@information');
	Route::get('/suggestion', 'DeoController@suggestion');
	Route::get('/complaint-detail/{id}', 'DeoController@complaintDetail');
	Route::get('/suvidha', 'DeoController@suvidha');
	Route::get('/suvidhaSub', 'DeoController@suvidhaSub');
	Route::get('/suvidha-detail/{sid}', 'DeoController@suvidhaDetail');
	Route::get('/p1-scrutiny', 'DeoController@p1Scrutiny');
	Route::get('/p1-scrutiny-search', 'DeoController@p1ScrutinySearch');
	Route::get('/p1-consolidated-report', 'DeoController@p1ConsolidatedReport');
	Route::get('/p1ConsolidatedReportSearch', 'DeoController@p1ConsolidatedReportSearch');
	Route::get('/poll-percentage', 'DeoController@pollPercentage');
	Route::get('/pollPercentagetiming', 'DeoController@pollPercentagetiming');
	Route::get('/polling-percentage-detail/{bid}', 'DeoController@pollingPercentageDetail');
    Route::get('/dispatch-collection-center', 'DeoController@dispatchCollectionCenter');
    Route::get('/dispatch-collection-center-sub', 'DeoController@dispatchCollectionCenterSub');
    Route::get('/postal-ballot', 'DeoController@postalBallot');
    Route::get('/postal-ballot-sub', 'DeoController@postalBallotSub');
    Route::get('/evm-malfunction', 'DeoController@evmMalfunction');
    Route::get('/evm-malfunction-sub', 'DeoController@evmMalfunctionSub');
    Route::get('/add-afterPoll-video', 'DeoController@addAfterPollVideo');
    Route::post('/add-afterPoll-video-sub', 'DeoController@addAfterPollVideoSub');
    Route::get('/law-order', 'DeoController@lawOrder');
    Route::get('/law-order-sub', 'DeoController@lawOrderSub');
    Route::get('/supervisor-detail/{uid}', 'DeoController@supervisorDetail');
    Route::get('/pwd-voters', 'DeoController@pwdVoters');
    Route::get('/pwd-voters-sub', 'DeoController@pwdVoterSub');
    Route::get('/video-recording', 'DeoController@videoRecording');
    Route::get('/video-recording-sub', 'DeoController@videoRecordingSub');
	Route::get('/police-data', 'DeoController@policeData');
	Route::post('/add-policedata', 'DeoController@addPoliceData');
	Route::get('/voter-slip-data', 'DeoController@voterSlipData');
    Route::get('/add-patwari-csv', 'DeoController@addPatwariCsv');
    Route::post('/add-patwari-csv-sub', 'DeoController@addPatwariCsvSub');
	Route::get('/voterslipDataResult', 'DeoController@voterslipDataResult');
    Route::get('/facilities/{bid}', 'DeoController@facilities');
});

Route::group(['prefix' => 'ro', 'as' => 'ro::', 'middleware' => ['web', 'auth']], function(){
	Route::get('/pagenotfound', 'RoController@pagenotfound');
    Route::get('/dashboard', 'RoController@dashboard');
    Route::get('/', 'RoController@dashboard');
	Route::get('/electoral-rolls', 'RoController@electoralRolls');
	Route::get('/electoral-rolls-submit', 'RoController@electoralRollsSubmit');
	Route::get('/voter-detail/{iCard}', 'RoController@voterDetail');
	Route::get('/new-nomination', 'RoController@newNomination');
	Route::post('/addNominationSub/', 'RoController@addNominationSub');
	Route::get('/nomination-received', 'RoController@nominationReceived');
	Route::get('/candidate-detail/{uid}', 'RoController@candidateDetail');
	Route::get('/nomination-rejected', 'RoController@nominationRejected');
	Route::get('/nomination-withdrawls', 'RoController@nominationWithdrawls');
	Route::get('/candidate-list', 'RoController@candidateList');
	Route::get('/send-notice-candidate', 'RoController@sendNoticeCandidate');
	Route::get('/allot-symbols', 'RoController@allotSymbols');
    Route::get('/supervisor-list', 'RoController@supervisorList');
    Route::get('/supervisor-detail/{uid}', 'RoController@supervisorDetail');
	Route::get('/supervisor-edit/{uid}', 'RoController@supervisorEdit');
	Route::get('/supervisorEdit/{uid}', 'RoController@supervisorEdit');
	Route::post('/upSupervisorSub/', 'RoController@upSupervisorSub');
	Route::get('/supervisorDel/{uid}', 'RoController@supervisorDel');
	Route::get('/add-supervisor/', 'RoController@addSupervisor');
	Route::get('/addSupervisorCsv', 'RoController@addSupervisorCsv');
	Route::post('/importSupCsv', 'RoController@importSupCsv');
	Route::get('/bloList', 'RoController@bloList');
	Route::get('/addBLOCsv', 'RoController@addBLOCsv');
	Route::post('/bloCsvSub', 'RoController@bloCsvSub');
	Route::post('/addSupevisorSub/', 'RoController@addSupevisorSub');
	Route::get('/addPollingStation/{uid}', 'RoController@addPollingStation');
	Route::post('/addPolStationSubmit/', 'RoController@addPolStationSubmit');
	Route::get('/polingCsvForm/{uid}', 'RoController@polingCsvForm');
	Route::post('/polStationExcelSubmit/', 'RoController@polStationExcelSubmit');
	Route::get('/uploadRootPlan/{uid}', 'RoController@uploadRootPlan');
	Route::post('/routePlanSub/', 'RoController@routePlanSub');
	Route::get('/pollBoothLatLong', 'RoController@pollBoothLatLong');
	Route::post('/updatePollBoothLatLong/', 'RoController@updatePollBoothLatLong');
	Route::get('/evm-vvpat', 'RoController@evmVvpat');
	Route::get('/search-evm-vvpat', 'RoController@searchevmVvpat');
	Route::get('/polling-staff', 'RoController@pollingStaff');
	Route::post('/polling-staff', 'RoController@pollingStaffType');
	Route::get('/add-polling-staff', 'RoController@addpollingStaff');
    Route::post('/addPollingstaffexcel/', 'RoController@addPollingstaffexcel');
    Route::post('/addPollingstaffexcel2/', 'RoController@addPollingstaffexcel2');
    Route::post('/addPollingstaffexcel3/', 'RoController@addPollingstaffexcel3');
    Route::post('/selectrandomizationForm/', 'RoController@selectrandomizationForm');
	Route::get('/polling-parties-details/{bid}', 'RoController@pollingPartiesDetails');
	Route::get('/training', 'RoController@training');
	Route::get('/add-training', 'RoController@addTraning');
	Route::post('/addTraningSub/', 'RoController@addTraningSub');
	Route::get('/edit-training/{id}', 'RoController@editTraning');
	Route::post('/editTraningSub/', 'RoController@editTraningSub');
	Route::post('/delete-traning/', 'RoController@deleteTraning');
	Route::get('/election-material', 'RoController@electionMaterial');
	Route::get('/polling-stations', 'RoController@pollingStations');
	Route::get('/polling-stations-map/{type?}', 'RoController@pollingStationsMap');
	Route::get('/polling-detail/{ps_id}', 'RoController@pollingDetail');
	Route::get('/pre-poll-arrangement', 'RoController@prePollArrangement');
	Route::post('/prePollSub/', 'RoController@prePollSub');
	Route::get('/poll-1day', 'RoController@poll1day');
	Route::get('/poll-day', 'RoController@pollDay');
	Route::get('/poll-percentage', 'RoController@pollPercentage');
	Route::get('/pollPercentagetiming', 'RoController@pollPercentagetiming');
	Route::get('/annexure-report', 'RoController@annexureReport');
	Route::post('/annexureReportSub', 'RoController@annexureReportSub');
	Route::get('/p1-scrutiny', 'RoController@p1Scrutiny');
	Route::post('/scrutinyReportSub', 'RoController@scrutinyReportSub');
	Route::get('/p1-consolidated-report-update', 'RoController@p1ConsolidatedReportUpdate');
	Route::get('/p1-consolidated-report-add', 'RoController@p1ConsolidatedReportAdd');
	Route::post('/addP1ConsReportSub/', 'RoController@addP1ConsReportSub');
	Route::post('/updateP1ConsReportSub/', 'RoController@updateP1ConsReportSub');
	Route::get('/polled-evm', 'RoController@polledEvm');
	Route::get('/election-observers', 'RoController@electionObservers');
	Route::get('/observer-profile/{uid}', 'RoController@observerProfile');
	Route::get('/p1-consolidated-report', 'RoController@p1ConsolidatedReport');
	//Route::get('/booth-awareness-group', 'RoController@boothAwarenessGroup');
	Route::get('/booth-photos/{bid}', 'RoController@boothPhotos');
	Route::get('/postal-ballot', 'RoController@postalBallot');
	Route::get('/booth-awareness-group/{bid}', 'RoController@boothAwarenessGroup');
	Route::get('/generate-voter-slip', 'RoController@generateVoterSlip');
	Route::post('/updatePollType/', 'RoController@updatePollType');
	Route::get('/uploadRoutePlan/{uid}', 'RoController@uploadRoutePlan');
	Route::post('/checkAssignedPoll', 'RoController@checkAssignedPoll');
	Route::get('/complaint', 'RoController@complaint');
	Route::get('/information', 'RoController@information');
	Route::get('/suggestion', 'RoController@suggestion');
	Route::get('/complaint-detail/{id}', 'RoController@complaintDetail');
	Route::get('/suvidha', 'RoController@suvidha');
	Route::get('/suvidha-detail/{sid}', 'RoController@suvidhaDetail');
	Route::get('/polling-percentage-detail/{bid}', 'RoController@pollingPercentageDetail');
	Route::post('/addPostalBallot', 'RoController@addPostalBallot');
	Route::get('/editPostBallot/{encBalId}', 'RoController@editPostBallot');
	Route::post('/updatePostalBallot', 'RoController@updatePostalBallot');
	Route::get('/candidate-affidavit/{cand_sl_no}/{cons_code}', 'RoController@candidateaffidavit');
	Route::get('/dispatch-collection-center', 'RoController@dispatchCollectionCenter');
	Route::get('/dispatch-collection-center-sub', 'RoController@dispatchCollectionCenterSub');
    Route::get('/law-order', 'RoController@lawOrder');
    Route::get('/evm-malfunction', 'RoController@evmMalfunction');
    Route::get('/pwd-voters', 'RoController@pwdVoters');
	
	Route::get('/voter-slip-data', 'RoController@voterSlipData');
	Route::post('/add-voter-slip-data', 'RoController@addvoterSlipData');
	
	Route::get('/facilities/{bid}', 'RoController@facilities');
	Route::get('/watch-live', 'RoController@watchLive');

});

Route::group(['prefix' => 'cron', 'as' => 'cron::', 'middleware' => ['web']], function(){
    Route::get('/updatevoterdata/{ac_no}', 'CronjobController@updatevoterdata');
    Route::get('/updatevoterfields', 'CronjobController@updatevoterfields');
    Route::get('/updatepwd', 'CronjobController@updatepwd');
    Route::get('/newpartlist', 'CronjobController@newpartlist');
    Route::get('/psbuildings', 'CronjobController@psbuildings');
    Route::get('/Nazari_Maps', 'CronjobController@Nazari_Maps');
    Route::get('/evmdatafirst/{state_id}/{ac_no}', 'CronjobController@evmdatafirst');
    Route::get('/evmdatasecond/{state_id}/{ac_no}', 'CronjobController@evmdatasecond');
    Route::get('/get_voter_list/{state_id}/{dist_code}/{cons_code}/{part_no}', 'CronjobController@get_voter_list');
	Route::get('/get_poll_images/{state_id}/{dist_code}/{cons_code}/{part_no}', 'CronjobController@get_poll_images');
    Route::get('/voterDetail/{idcardno}', 'CronjobController@voterDetail');
    Route::get('/get_pwd_data/{idcardno}', 'CronjobController@get_pwd_data');
    Route::get('/get_nominations', 'CronjobController@get_nominations');
    Route::get('/get_cand_symbols', 'CronjobController@get_cand_symbols');
    Route::get('/politicalparties', 'CronjobController@politicalparties');
    Route::get('/get_complaints/{state_id}/{dist_code?}/{cons_code?}', 'CronjobController@get_complaints');
	Route::get('/send_welcome_msg', 'CronjobController@send_welcome_msg');
	Route::get('/get_pun_voters', 'CronjobController@get_pun_voters');
	Route::get('/get_blo', 'CronjobController@get_blo');
	Route::get('/get_candidate_list/{state_id}/{cons_code}', 'CronjobController@get_candidate_list');
    Route::get('/get_candidate_list_s', 'CronjobController@get_candidate_list_s');
    Route::get('/delete_pollbefore/{uid}', 'CronjobController@delete_pollbefore');
    Route::get('/delete_pollday/{uid}', 'CronjobController@delete_pollday');
    Route::get('/delete_evm/{uid}', 'CronjobController@delete_evm');
    Route::get('/delete_law/{uid}', 'CronjobController@delete_law');
    Route::get('/delete_percentage/{uid}', 'CronjobController@delete_percentage');
	Route::get('/delete_alldetails/{uid}', 'CronjobController@delete_alldetails');

    Route::get('/add-general-observer', 'CronjobController@addGeneralObserver');
    Route::get('/add-police-observer', 'CronjobController@addPoliceObserver');
    Route::get('/add-expenditure-observer', 'CronjobController@addExpenditureObserver');
    Route::get('/add-awarness-observer', 'CronjobController@addAwarnessObserver');

    Route::get('/testingAPI', 'CronjobController@testingAPI');
    Route::get('/updatepro/{ac_no}/{newparty}/{phone}', 'CronjobController@updatepro');
    //Route::get('/voterlist', 'CronjobController@evmdatasecond');
    //Route::get('/', 'RoController@dashboard');
});



