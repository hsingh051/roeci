<?php
namespace App\Http\Controllers;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use DB;
use Excel;
use Config;
use Aws\S3\S3Client;


class RoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('ro');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    protected function guard()
    {
        return Auth::guard();
    }

    public function dashboard(){
        $user = Auth::user();
        $state_id =  $user->state_id;
        $dist_code =  $user->dist_code;
        $cons_code = $user->cons_code;
        $supervisorlist = DB::table('users')                         
                          ->where('users.role', '5')
                          ->where('users.state_id', $state_id)
                          ->where('users.dist_code', $user->dist_code)
                          ->where('users.cons_code', $cons_code)
                          ->select('users.name','users.phone')
                          ->orderby('users.name')
                          ->limit(5)
                          ->get();
        //EVM And VVPAT
        $first_evm = strtotime(Config::get('constants.FIRST_RANDOMIZATION_EVM_DATE'));
        $second_evm = strtotime(Config::get('constants.SECOND_RANDOMIZATION_EVM_DATE'));
        $today = time();
        $secondlist = array();
        $firstlist = array();
        $evmlist = array('first'=>'Pending','second'=>'Pending');
        //if($second_evm <= $today){
            $secondlist = DB::table('randomization_evm_second')
                        ->select('randomization_evm_second.cons_code')
                        ->where('randomization_evm_second.state_id','=',$state_id)
                        ->where('randomization_evm_second.dist_code','=',$dist_code)
                        ->where('randomization_evm_second.cons_code','=',$cons_code)
                        ->count();
            if($secondlist >= 1){
               $evmlist['second'] = "Done";
            }

        //}
        //if($first_evm <= $today){
            $firstlist = DB::table('randomization_evm_first')
                          ->select('randomization_evm_first.cons_code')
                          ->where('randomization_evm_first.state_id','=',$state_id)
                          ->where('randomization_evm_first.dist_code','=',$dist_code)
                          ->where('randomization_evm_first.cons_code','=',$cons_code)
                          ->count();
            if($firstlist >= 1){
                $evmlist['first'] = "Done";
            }   
        //}
        //dd($evmlist);
        // Polling Stations
        $pollstationlist = DB::table('poll_booths')
                    ->select('poll_booths.poll_type',DB::raw('IFNULL(COUNT(poll_booths.poll_booth_id), 0) as total'))
                    ->where('poll_booths.state_id','=',$state_id)
                    ->where('poll_booths.dist_code','=',$dist_code)
                    ->where('poll_booths.cons_code','=',$cons_code)
                    ->groupBy('poll_booths.poll_type')
                    ->orderBy('poll_booths.poll_type')
                    ->limit(5)                    
                    ->get();
        $poll_types = array('Notified'=>0,'Vulnerable'=>0,'Auxiliary'=>0,'Critical'=>0, 'Model'=>0);
        foreach ($pollstationlist as $ty) {
            $poll_types[$ty->poll_type] = $ty->total;
        }
        // Electrol Rolls
        $voterlist = DB::table('poll_booths')
                    ->leftjoin('voters_count', function($join) { 
                                $join->on('voters_count.cons_code', '=', 'poll_booths.cons_code')
                                      ->on('voters_count.state_id', '=', 'poll_booths.state_id')
                                      ->on('voters_count.dist_code', '=', 'poll_booths.dist_code')
                                      ->on('voters_count.ps_id', '=', 'poll_booths.ps_id'); 
                                  }
                            )
                    ->select('poll_booths.ps_id', 'poll_booths.poll_building','voters_count.total_voters as total')
                    ->where('poll_booths.state_id','=',$state_id)
                    ->where('poll_booths.dist_code','=',$dist_code)
                    ->where('poll_booths.cons_code','=',$cons_code)
                    ->orderBy('poll_booths.ps_id')
                    ->limit(5) 
                    ->get();
        //dd($voterlist);
        // public function voterDetail($iCard){
        //     $user = Auth::user();
        //     $iCardNo=eci_decrypt($iCard);
     
        //     $voterDetail= DB::table('voters') 
        //                   ->join('constituencies', function($join) { 
        //                         $join->on('voters.cons_code', '=', 'constituencies.cons_code')
        //                               ->on('voters.state_id', '=', 'constituencies.state_id')
        //                               ->on('voters.dist_code', '=', 'constituencies.dist_code'); 
        //                           }
        //                     )
        //                   ->where('idcardNo', $iCardNo)->first();
        //     return view('ro/voter-detail', [
        //        'voterDetail' => $voterDetail,
        //     ]);
        // }
        //dd($poll_types);

        //EVM And VVPAT
        $first_staff = strtotime(Config::get('constants.FIRST_RANDOMIZATION_STAFF_DATE'));
        $second_staff = strtotime(Config::get('constants.SECOND_RANDOMIZATION_STAFF_DATE'));
        $third_staff = strtotime(Config::get('constants.THIRD_RANDOMIZATION_STAFF_DATE'));
        $today = time();
        $thirdlist = array();
        $secondlist = array();
        $firstlist = array();
        $stafflist = array('first'=>'Pending','second'=>'Pending','third'=>'Pending');
        //if($third_staff <= $today){
            $thirdlist = DB::table('randomization_staff_third')
                        ->select('randomization_staff_third.cons_code')
                        ->where('randomization_staff_third.state_id','=',$state_id)
                        ->where('randomization_staff_third.dist_code','=',$dist_code)
                        ->where('randomization_staff_third.cons_code','=',$cons_code)
                        ->count();
            if($thirdlist >= 1){
               $stafflist['third'] = "Done";
            }

        //}

        //if($second_staff <= $today){
            $secondlist = DB::table('randomization_staff_second')
                        ->select('randomization_staff_second.cons_code')
                        ->where('randomization_staff_second.state_id','=',$state_id)
                        ->where('randomization_staff_second.dist_code','=',$dist_code)
                        ->where('randomization_staff_second.cons_code','=',$cons_code)
                        ->count();
            if($secondlist >= 1){
               $stafflist['second'] = "Done";
            }

        //}
        //if($first_staff <= $today){
            $firstlist = DB::table('users_pollday')
                          ->select('users_pollday.cons_code')
                          ->where('users_pollday.state_id','=',$state_id)
                          ->where('users_pollday.dist_code','=',$dist_code)
                          ->where('users_pollday.cons_code','=',$cons_code)
                          ->count();
            if($firstlist >= 1){
                $stafflist['first'] = "Done";
            }   
        //}
        //dd($evmlist);
        return view('ro/dashboard', [
           'supervisorlist' => $supervisorlist,
           'evmlist' => $evmlist,
           'poll_types' => $poll_types,
           'stafflist' => $stafflist,
           'voterlist' => $voterlist,
        ]);
        return view('ro/dashboard');
    }

    public function pagenotfound(){
        $users = Auth::user();
        return view('ro/pagenotfound');
    }

	public function electoralRolls()
    {
        $user = Auth::user();
        $distRo = Auth::user()->dist_code;
        $consRo = Auth::user()->cons_code;
        $stateRo = Auth::user()->state_id;
        


        $poll_station = DB::table('poll_booths')
                    ->where('dist_code', $distRo)
                    ->where('cons_code', $consRo)
                    ->where('state_id', $stateRo)
                    ->select('ps_id','poll_building','poll_building_detail') 
                    ->orderby('ps_id')
                    ->get();
        //dd($poll_station);
        return view('ro/electoral-rolls', [
            'poll_station' => $poll_station,
        ]);
    }

    //--View Electoral Rolls
    public function electoralRollsSubmit(Request $request) { 
        $this->validate(
            $request, 
            ['pollStation' => 'required'],
            ['pollStation.required' => 'Please Select Polling Station.']
        );
        $user = Auth::user();
        $distRo = Auth::user()->dist_code;
        $consRo = Auth::user()->cons_code;
        $stateRo = Auth::user()->state_id;
        $psId = $request->pollStation;
        $poll_station = DB::table('poll_booths')
                ->where('dist_code', $distRo)
                ->where('cons_code', $consRo)
                ->where('state_id', $stateRo)
                ->select('ps_id','poll_building','poll_building_detail') 
                ->orderby('ps_id')
                ->get();

        $votersList = DB::table('voters')
                ->where('dist_code', $distRo)
                ->where('cons_code', $consRo)
                ->where('state_id', $stateRo)
                ->where('ps_id', $psId)
                ->get();
        if(@$voterlist){

        }else{
            $votersListAPI = app('App\Http\Controllers\CronjobController')->get_voter_list($stateRo,$distRo,$consRo,$psId);
            $votersList = json_decode($votersListAPI);
        }
        return view('ro/electoral-rolls', [
        'poll_station' => $poll_station,
        'votersList' => $votersList,
        ]);
    }

    public function voterDetail($iCard){
        $user = Auth::user();
        $iCardNo = eci_decrypt($iCard);
        $voterDetail = voter_details($iCardNo);
        
        if(@$voterDetail){

            $voterDetail = json_decode($voterDetail);
            $pollDayDetail = poll_booth_details($voterDetail->state_id, $voterDetail->dist_code, $voterDetail->cons_code, $voterDetail->ps_id);
            $pollDayDetail = json_decode($pollDayDetail);
        }else{
            $voterDetail = array();
        }
        //dd($voterDetail);
        return view('ro/voter-detail', [
           'voterDetail' => $voterDetail,
           'pollDayDetail' => $pollDayDetail,
        ]);
    }

	public function newNomination()
    {
        $user = Auth::user();
        return view('ro/new-nomination');
    }

//-- Add new nomination
    public function addNominationSub(Request $request) {
        $user = Auth::user();
        $NominationStatus=$request->cNominationStatus;
        if($NominationStatus==1){
            $this->validate($request, [
                'cName' => 'required',
                'fatherMotherHus' => 'required',
                'cState' => 'required',
                'citizenship' => 'required',
                'cDateOfBirth' => 'required',
                'cPartyType' => 'required',
                'cPartyName' => 'required',
                'cCategory' => 'required',
                'cSeralNumNomination' => 'required',
                'cNominationDate' => 'required',
                'cNominationTime' => 'required',
                'cNominationStatus' => 'required',
                'scrutinyDate' => 'required',
                'scrutinyTime' => 'required',
                'cEmail' => 'required|email',
                'cPhone' => 'required|size:10',
                'cConsCode' => 'required',
                'postAddress' => 'required',
                'cProfilePic' => 'required',
            ]);

        }else{
            $this->validate($request, [
                'cName' => 'required',
                'fatherMotherHus' => 'required',
                'cState' => 'required',
                'citizenship' => 'required',
                'cDateOfBirth' => 'required',
                'cPartyType' => 'required',
                'cPartyName' => 'required',
                'cCategory' => 'required',
                'cSeralNumNomination' => 'required',
                'cNominationDate' => 'required',
                'cNominationTime' => 'required',
                'cNominationStatus' => 'required',
                'cRejectedText' => 'required',
                'scrutinyDate' => 'required',
                'scrutinyTime' => 'required',
                'cEmail' => 'required|email',
                'cPhone' => 'required|size:10',
                'cConsCode' => 'required',
                'postAddress' => 'required',
                'cProfilePic' => 'required',
            ]);
        }
        $dt = Carbon::now();
        $timestamp=$dt->toDateString();
        $roleNomination=15;
        $uidNomination1="CND".($request->cPhone);
        $uidNomination=trim($uidNomination1);
        $distNomination=Auth::user()->dist_code;
        $pwNomination= rand(11111111,99999999);
        $addNom = array(
            'name' => $request->cName,
            'uid' => $uidNomination,
            'dist_code' => "$distNomination",
            'role' => "$roleNomination",
            'phone' => $request->cPhone,
            'password' => Hash::make($pwNomination),
            'address' => $request->postAddress,
            'updated_at' => $timestamp,
        );  
        $nomination = DB::table('users')->insert($addNom);
        if($nomination>0) {
            $idLast = DB::getPdo()->lastInsertId();
            $getLastUid = DB::table('users')->where('id', $idLast)->first();
            $lastUid=$getLastUid->uid;
            $cProfilePic= (isset($request['cProfilePic']))? $request['cProfilePic'] : "";
            $cSymbol1= (isset($request['symbol1']))? $request['symbol1'] : "";
            $cSymbol2= (isset($request['symbol2']))? $request['symbol2'] : "";
            $cSymbol3= (isset($request['symbol3']))? $request['symbol3'] : "";
           
            //--Profile Picture
                if($cProfilePic!==""){
                    $filesProfile = Input::file('cProfilePic');
                    $destinationProfile = 'images/candidate/profilePicture';
                    $filenamePro = $filesProfile->getClientOriginalName();
                    $randomPro=rand(10,999999).time().rand(10,999999);
                    $filenameNewP = $randomPro.$filenamePro;
                    $upload_successP= $filesProfile->move($destinationProfile, $filenameNewP);
                    $profilePicC = $filenameNewP;
                }
                else{
                    $profilePicC="";
                }

            //--Symbol 1
                if($cSymbol1!==""){
                    $filesS1 = Input::file('symbol1');
                    $destinationS1 = 'images/candidate/symbol';
                    $filenameS1 = $filesS1->getClientOriginalName();
                    $randomS1=rand(10,999999).time().rand(10,999999);
                    $filenameNewS1 = $randomS1.$filenameS1;
                    $upload_successS1= $filesS1->move($destinationS1, $filenameNewS1);
                    $symbol1c = $filenameNewS1;
                }
                else{
                    $symbol1c="";
                }

            //--Symbol 2
                if($cSymbol2!==""){
                    $filesS2 = Input::file('symbol2');
                    $destinationS2 = 'images/candidate/symbol';
                    $filenameS2 = $filesS2->getClientOriginalName();
                    $randomS2=rand(10,999999).time().rand(10,999999);
                    $filenameNewS2 = $randomS2.$filenameS2;
                    $upload_successS2= $filesS2->move($destinationS2, $filenameNewS2);
                    $symbol2c = $filenameNewS2;
                }
                else{
                    $symbol2c="";
                }

            //--Symbol 3
                if($cSymbol3!==""){
                    $filesS3 = Input::file('symbol3');
                    $destinationS3 = 'images/candidate/symbol';
                    $filenameS3 = $filesS3->getClientOriginalName();
                    $randomS3=rand(10,999999).time().rand(10,999999);
                    $filenameNewS3 = $randomS3.$filenameS3;
                    $upload_successS3= $filesS3->move($destinationS3, $filenameNewS3);
                    $symbol3c = $filenameNewS3;
                }
                else{
                    $symbol3c="";
                }
           
            $insertNom = array(
                'uid' => $lastUid,
                'guardian_name' => $request->fatherMotherHus,
                'stateId' =>  $request->cState,
                'citizenship' => $request->citizenship,
                'date_of_birth' => $request->cDateOfBirth,
                'party_type' => $request->cPartyType,
                'party_name' => $request->cPartyName,
                'cons_code' => $request->cConsCode,
                'profile_pic' => $profilePicC,
                'symbol1' => $symbol1c,
                'symbol2' => $symbol2c,
                'symbol3' => $symbol3c,
                'category' => $request->cCategory,
                'serial_number' => $request->cSeralNumNomination,
                'nominationDate' => $request->cNominationDate,
                'nominationTime' => $request->cNominationTime,
                'nominationStatus' => $request->cNominationStatus,
                'rejectionReason' => $request->cRejectedText,
                'scrutiny_date' => $request->scrutinyDate,
                'scrutiny_time' => $request->scrutinyTime,
            );
   
            $insertNom = DB::table('users_candidate_data')->insert($insertNom);

            if($insertNom>0){
                Session::flash('nominationSucc', 'Nomination Added Successfully.'); 
                Session::flash('alert-class', 'alert-success');
                return Redirect::to('ro/nomination-received');
            }
        }
    }

//--Receaved Nominations
    public function nominationReceived()
    {
        $user = Auth::user();
        $getNomination = DB::table('users')
                         ->join('users_candidate_data', 'users.uid', '=', 'users_candidate_data.uid')
                         ->where('users.role', '15')
                         ->where('users.cons_code', $user->cons_code)
                         //->where('users_candidate_data.nominationDate', 'like', '%' . date("Y-m-d") . '%')
                         ->get();
        return view('ro/nomination-received', [
           'getNomination' => $getNomination,
        ]);
    }

    public function nominationReceivedpost(Request $request)
    {
        $user = Auth::user();
        $cons_code = $user->cons_code;
        $this->validate($request, [
            'date' => 'required|date',
        ]);
        $user = Auth::user();
        $getNomination = DB::table('users')
                         ->join('users_candidate_data', 'users.uid', '=', 'users_candidate_data.uid')
                         ->where('users.role', '15')
                         ->where('users.cons_code', $cons_code)
                         ->where('users_candidate_data.nominationDate', 'like', '%' . date("Y-m-d",strtotime($request->date)) . '%')
                         ->get();
        return view('ro/nomination-received', [
           'getNomination' => $getNomination,
        ]);
    }


//-- Selected Candidate Detail
    public function candidateDetail($uid)
    {
        $user = Auth::user();
        $uidDcr=eci_decrypt($uid);
        $candidateDetail = DB::table('users')->join('users_candidate_data', 'users.uid', '=', 'users_candidate_data.uid')->join('districts', 'users.dist_code', '=', 'districts.dist_code')->leftJoin('symbols', 'users_candidate_data.cand_symbol', '=', 'symbols.symbol_no')->leftJoin('constituencies', 'users_candidate_data.cons_code', '=', 'constituencies.cons_code')->where('users.uid', $uidDcr)->first();
        return view('ro/candidate-detail', [
           'candidateDetail' => $candidateDetail,
        ]);
    }


	public function nominationRejected()
    {
        $user = Auth::user();
        $getNomination = get_candidate_list($user->state_id,$user->cons_code,'R');
        //dd($getNomination);
        return view('ro/nomination-rejected', [
           'getNomination' => $getNomination,
        ]);

    }
	public function nominationWithdrawls()
    {
        $user = Auth::user();
        // $getNomination = DB::table('users')
        //                  ->join('users_candidate_data', 'users.uid', '=', 'users_candidate_data.uid')
        //                  ->where('users.role', '15')
        //                  ->where('users.cons_code', $user->cons_code)
        //                  ->where('users_candidate_data.nominationStatus', 'W')
        //                  ->get();

        $getNomination = get_candidate_list($user->state_id,$user->cons_code,'W');

        return view('ro/nomination-withdrawls', [
           'getNomination' => $getNomination,
        ]);
    }
	public function candidateList()
    {
        $user = Auth::user();
        $getNomination = DB::table('users')
                         ->join('users_candidate_data', 'users.uid', '=', 'users_candidate_data.uid')
                         ->where('users.role', '15')
                         ->where('users.cons_code', $user->cons_code)
                         ->where('users_candidate_data.nominationStatus', 'N')
                         ->get();
        
        return view('ro/candidate-list', [
           'getNomination' => $getNomination,
        ]);
    }

    public function candidateaffidavit($cand_sl_no,$cons_code){
        $state_id = 's19';
        $affidavit = get_candidate_affidavit($cons_code,$cand_sl_no,$state_id);
        $pdf_decoded = base64_decode ($affidavit[0]->AffidavitImages);
        header('Content-Type: application/pdf');
        echo $pdf_decoded;
    }

	public function sendNoticeCandidate()
    {
        $user = Auth::user();
        return view('ro/send-notice-candidate');
    }
	public function allotSymbols()
    {
        $user = Auth::user();
        return view('ro/allot-symbols');
    }
	
	public function evmVvpat()
    {
        $user = Auth::user();
        $first_evm_date = Config::get('constants.FIRST_RANDOMIZATION_EVM_DATE');
        $time_now=mktime(date('h')+5,date('i')+30,date('s'));
        $current_date = date('Y-m-d', $time_now);
        if (strtotime($first_evm_date) > strtotime($current_date)) {
            \Session::flash('message', 'Too be announced soon.');
            return view('ro/evm-vvpat');
        }
        else{
            $second_evm_date = Config::get('constants.SECOND_RANDOMIZATION_EVM_DATE');
            if (strtotime($second_evm_date) > strtotime($current_date)) {
                $visibile = 0;
            }
            else{
                $visibile = 1;
            }
            $getfirstrandomisation = DB::table('randomization_evm_first')
                                     ->join('constituencies', 'randomization_evm_first.cons_code','constituencies.cons_code')
                                     ->where('randomization_evm_first.dist_code', $user->dist_code)
                                     ->where('randomization_evm_first.cons_code', $user->cons_code)
                                     ->get();
            $getConst = DB::table('constituencies')->where('dist_code',$user->dist_code)->get();
            return view('ro/evm-vvpat', [
              'visibile' => $visibile,
              'getConst' => $getConst,
              'getfirstrandomisation' => $getfirstrandomisation,
            ]);
        }
    }

    public function searchevmVvpat(Request $request){
        $user = Auth::user();
        $this->validate($request, [
            'rand_id' => 'required',
        ]);
        $cons_code = $user->cons_code;
        $rand_id = eci_decrypt($request->rand_id);
        $time_now=mktime(date('h')+5,date('i')+30,date('s'));
        $current_date = date('Y-m-d', $time_now);
        $second_evm_date = Config::get('constants.SECOND_RANDOMIZATION_EVM_DATE');
        if (strtotime($second_evm_date) > strtotime($current_date)) {
            $visibile = 0;
        }
        else{
            $visibile = 1;
        }
        if($rand_id == 1){
            $getfirstrandomisation = DB::table('randomization_evm_first')->join('constituencies', 'randomization_evm_first.cons_code','constituencies.cons_code')->where('randomization_evm_first.dist_code', $user->dist_code)->where('randomization_evm_first.cons_code', $cons_code)->get();
            return view('ro/search-evm-vvpat', [
              'visibile' => $visibile,
              'getfirstrandomisation' => $getfirstrandomisation,
            ]);
        }
        else{
            $getsecondrandomisation = DB::table('randomization_evm_second')->join('constituencies', 'randomization_evm_second.cons_code','constituencies.cons_code')->join('poll_booths', 'randomization_evm_second.bid','poll_booths.bid')->where('randomization_evm_second.dist_code', $user->dist_code)->where('randomization_evm_second.cons_code', $cons_code)->get();
            return view('ro/evm-vvpatII', [
              'visibile' => $visibile,
              'getsecondrandomisation' => $getsecondrandomisation,
            ]);
        }
    }

	public function pollingStaff()
    {
        $user = Auth::user();
        $polling_users = DB::table('users_pollday')
                         ->where('state_id', $user->state_id)
                         ->where('dist_code', $user->dist_code)
                         ->where('cons_code', $user->cons_code)
                         ->orderby('name')
                         ->get();
        return view('ro/polling-staff', [
            'polling_users' => $polling_users,
        ]);
    }

    public function pollingStaffType(Request $request){
        $user = Auth::user();
        $type = $request->staff_type;
        if($type == "second"){
            $polling_users = DB::table('users_pollday')
                             ->join('randomization_staff_second','randomization_staff_second.uid','=','users_pollday.uid')
                             ->where('randomization_staff_second.state_id', $user->state_id)
                             ->where('randomization_staff_second.dist_code', $user->dist_code)
                             ->where('randomization_staff_second.cons_code', $user->cons_code)
                             ->orderby('randomization_staff_second.party_no')
                             ->get();
            return view('ro/polling-staff2', [
                'polling_users' => $polling_users,
            ]); 
        }elseif($type == "third"){
            $polling_users = DB::table('poll_booths')
                             ->join('randomization_staff_third','randomization_staff_third.bid','=','poll_booths.bid')
                             ->where('randomization_staff_third.state_id', $user->state_id)
                             ->where('randomization_staff_third.dist_code', $user->dist_code)
                             ->where('randomization_staff_third.cons_code', $user->cons_code)
                             ->select(DB::raw('randomization_staff_third.party_no'))
                             ->groupBy('randomization_staff_third.party_no')
                             //->groupBy('randomization_staff_third.party_no')
                             ->get();
            if($polling_users->count()){
                $i=0;
                foreach ($polling_users as $value) {
                    $staff = DB::table('randomization_staff_third')
                             ->join('users_pollday','users_pollday.uid','=','randomization_staff_third.uid')
                             ->join('poll_booths','poll_booths.bid','=','randomization_staff_third.bid')
                             ->join('users','users.uid','=','poll_booths.supervisior_uid')
                             ->where('randomization_staff_third.party_no', $value->party_no)
                             ->where('randomization_staff_third.state_id', $user->state_id)
                             ->where('randomization_staff_third.dist_code', $user->dist_code)
                             ->where('randomization_staff_third.cons_code', $user->cons_code)
                             ->select('randomization_staff_third.bid','poll_booths.ps_id','poll_booths.poll_building','users_pollday.name','users_pollday.elect_duty','users_pollday.phone','users.name as supervisor_name','users.phone as supervisor_phone')
                             ->get();
                    if($staff->count()){
                        $polling_staff[$i]['party_no'] =  $value->party_no;
                        $polling_staff[$i]['ps_id'] =  $staff[0]->ps_id;
                        $polling_staff[$i]['bid'] =  $staff[0]->bid;
                        $polling_staff[$i]['poll_building'] =  $staff[0]->poll_building;
                        $polling_staff[$i]['supervisor_name'] =  $staff[0]->supervisor_name;
                        $polling_staff[$i]['supervisor_phone'] =  $staff[0]->supervisor_phone;
                        $polling_staff[$i]['staff']    = $staff;
                    }
                    
                    $i++;
                }
            }
            else{
                $polling_staff = array();
            }
            return view('ro/polling-staff3', [
                'polling_staff' => $polling_staff,
            ]);
        }else{
            $polling_users = DB::table('users_pollday')
                             ->where('state_id', $user->state_id)
                             ->where('dist_code', $user->dist_code)
                             ->where('cons_code', $user->cons_code)
                             ->get();

            return view('ro/polling-staff', [
                'polling_users' => $polling_users,
            ]); 
        }
        
    }
	
	public function addpollingStaff()
    {
        $user = Auth::user();
        return view('ro/add-polling-staff');
		//return view('ro/add-polling-staff-2');
		//return view('ro/add-polling-staff-3');
    }
	
	public function selectrandomizationForm(Request $request)
    {
        $user = Auth::user();
        $post = $request->all();
		$pollingFormname=$post['pollingFormname'];
		if($pollingFormname==1)
		{
			return view('ro/add-polling-staff');
		}
		elseif($pollingFormname==2)
		{
			return view('ro/add-polling-staff-2');
		}
		elseif($pollingFormname==3)
		{
			return view('ro/add-polling-staff-3');
		}
		else
		{
			return view('ro/add-polling-staff');
		}
    }
	
	
	public function pollingPartiesDetails($bid)
    {
        $user = Auth::user();
        $return_array = array();
        $poo_array = array();
        $time_now=mktime(date('h')+5,date('i')+30,date('s'));
        $current_date = date('Y-m-d', $time_now);
        $third_randomisation_date = Config::get('constants.THIRD_RANDOMIZATION_STAFF_DATE');
        if (strtotime($third_randomisation_date) > strtotime($current_date)) {
            $visibile = 0;
        }
        else{
            $visibile = 1;
        }
        $bid = eci_decrypt($bid);
        $poo_array = array();
        $poll_booths = array();
	    $poll_booths = DB::table('poll_booths')
	    				->select('poll_building')
		   	            ->where('bid',$bid)
		   	            ->first();
		//dd($poll_booths);
	    $getpspro = DB::table('randomization_staff_third')
	   	            ->join('users_pollday', 'randomization_staff_third.uid','users_pollday.uid')
	   	            ->where('randomization_staff_third.uid', 'like', 'PRO%')
	   	            ->where('bid',$bid)
	   	            ->first();
	   	if(@$getpspro){
	   		$return_array['pro_name'] = $getpspro->name;
	       	$return_array['pro_phone'] = $getpspro->phone;
	   	}
	   	
	   	$getpsblo = DB::table('poll_booths')
	   	            ->join('users', 'poll_booths.blo_uid','users.uid')
	   	            ->where('poll_booths.bid',$bid)
	   	            ->first();

	   	if(@$getpsblo){
	   		$return_array['blo_name'] = $getpsblo->name;
	       	$return_array['blo_phone'] = $getpsblo->phone;
	   	}

	   	$getpsapro = DB::table('randomization_staff_third')
	   	             ->join('users_pollday', 'randomization_staff_third.uid','users_pollday.uid')
	   	             ->where('randomization_staff_third.uid', 'like', 'APR%')
	   	             ->where('bid',$bid)
	   	             ->first();
	   	if(@$getpsapro){
	   		$return_array['apro_name'] = $getpsapro->name;
	      	$return_array['apro_phone'] = $getpsapro->phone;
	   	}

	   	$getpspoo = DB::table('randomization_staff_third')->join('users_pollday', 'randomization_staff_third.uid','users_pollday.uid')->where('randomization_staff_third.uid', 'like', 'POO%')->where('bid',$bid)->get();
	   	foreach($getpspoo as $getdata){
            $poolist['name'] = $getdata->name;
            $poolist['phone'] = $getdata->phone;
            $poolist['designation'] = $getdata->designation;
            $poo_array[] = $poolist;
        }
        return view('ro/polling-parties-details', [
            'polling_users' => $return_array,
            'poo_array' => $poo_array,
            'poll_booths' => $poll_booths,
            'visibile' => $visibile,
        ]);
    }

    //--Traning List
	public function training()
    {
        $user = Auth::user();
        $traningList = DB::table('training_ro')->get();
        return view('ro/training', [
        'traningList' => $traningList,
        ]);
    }

    //-- Add Traning
    public function addTraning()
    {
        $user = Auth::user();
        $user = Auth::user();
        return view('ro/add-training');
    }


    //-- Add Traning--(Form Submit)
    public function addTraningSub(Request $request) {
        $user = Auth::user();
        $this->validate(
        $request, [
            'traningLabel' => 'required',
            'traningDate' => 'required|date',
            'traningTimeFrom' => 'required|date_format:H:i',
            'traningTimeTo' => 'required|date_format:H:i',
            'traningVenue' => 'required',
            'traningType' => 'required',
        ],
        [
            'traningLabel.required' => 'Please add label for Training',
            'traningDate.required' => 'Please add Date of Training',
            'traningDate.date' => 'Date format is not valid',
            'traningTimeFrom.required' => 'Please add starting Time of Training',
            'traningTimeFrom.date_format' => 'Time format is not valid',
            'traningTimeTo.required' => 'Please add end Time of Training',
            'traningTimeTo.date_format' => 'Time format is not valid',
            'traningVenue.required' => 'Please add venue for Training',
            'traningType.required' => 'Please select type of Training', 
        ]
        );
        $uidRo=Auth::user()->uid;
        $distCodeRo = Auth::user()->dist_code;
        $consCodeRo = Auth::user()->cons_code;
        $statIdRo = Auth::user()->state_id;
        $addTraning = array(
            'uid' => $uidRo,
            'dist_code' => $distCodeRo,
            'cons_code' => $consCodeRo,
            'state_id' => $statIdRo,
            'name' => $request->traningLabel,
            'date' => $request->traningDate,
            'from_time' => $request->traningTimeFrom,
            'to_time' => $request->traningTimeTo,
            'type' => $request->traningType,
            'location' => $request->traningVenue,
        );  
        $addTranings = DB::table('training_ro')->insert($addTraning);
            if($addTranings>0){
            Session::flash('traningSuccRo', 'Training added successfully.'); 
            Session::flash('alert-class', 'alert-success');
            return Redirect::to('ro/training');
        }else{
            Session::flash('traningErrRo', 'Please try again.'); 
            Session::flash('alert-class', 'alert-danger');
            return Redirect::to('ro/add-training');
        }
    }

    //-- Edit Traning
    public function editTraning($id) {
        $user = Auth::user();
        $idTraning=eci_decrypt($id);
        $traningDetail = DB::table('training_ro')->where('id', $idTraning)->first();
        return view('ro/edit-training', [
        'traningDetail' => $traningDetail,
        ]);
    }

    //-- Edit Traning--(Form Submit)
    public function editTraningSub(Request $request) {
        $user = Auth::user();
        $this->validate(
        $request, [
            'editTraningLabel' => 'required',
            'editTraningDate' => 'required|date',
            'editTraningTimeFrom' => 'required|date_format:H:i',
            'editTraningTimeTo' => 'required|date_format:H:i',
            'editTraningVenue' => 'required',
            'editTraningType' => 'required',
        ],
        [
            'editTraningLabel.required' => 'Please add label for Training',
            'editTraningDate.required' => 'Please add Date of Training',
            'editTraningDate.date' => 'Date format is not valid',
            'editTraningTimeFrom.required' => 'Please add starting Time of Training',
            'editTraningTimeFrom.date_format' => 'Time format is not valid',
            'editTraningTimeTo.required' => 'Please add end Time of Training',
            'editTraningTimeTo.date_format' => 'Time format is not valid',
            'editTraningVenue.required' => 'Please add venue for Training',
            'editTraningType.required' => 'Please select type of Training', 
        ]
        );
        $trId=$request->idTraningHidden;
        $dcryptTraningId=eci_decrypt($trId);
        $upTraning = array(
            'name' => $request->editTraningLabel,
            'date' => $request->editTraningDate,
            'from_time' => $request->editTraningTimeFrom,
            'to_time' => $request->editTraningTimeTo,
            'type' => $request->editTraningType,
            'location' => $request->editTraningVenue,
        );  
        $upTranings = DB::table('training_ro')->where('id', $dcryptTraningId)->update($upTraning);
        if($upTranings!==""){
            Session::flash('traningSuccRo', 'Training Updated successfully.'); 
            Session::flash('alert-class', 'alert-success');
            return Redirect::to('ro/training');
        }else{
            Session::flash('traningErrEditRo', 'Please try again.'); 
            Session::flash('alert-class', 'alert-danger');
            return Redirect::to('ro/edit-training/'.$trId);
        }
    }

    //--Delete Traning
    public function deleteTraning(Request $request) {
      $user = Auth::user();
      $post = $request->all();
      $id=$post['id'];
      $idTraningDel=eci_decrypt($id);
      $delTraning=DB::table('training_ro')->where('id', $idTraningDel)->delete();
      if($request->ajax()){
       if($delTraning!==""){
          return response()->json([
              'delresponse' => 1,
          ]);
        }else{
          return response()->json([
              'delresponse' => 0,
          ]);
        }
      }

    }

	public function electionMaterial()
    {
        $user = Auth::user();
        return view('ro/election-material');
    }

    public function bloList()
    {
        $user = Auth::user();
        $BLOs = DB::table('users')->where('dist_code', $user->dist_code)->where('cons_code', $user->cons_code)->where('role', '7')->get();
        return view('ro/bloList', [
            'BLOs' => $BLOs,
        ]);
    }
    
    public function addBLOCsv()
    { 
        return view('ro/addBLOCsv');
    }


    public function bloCsvSub(Request $request) {
        $this->validate($request, [
            'filename' => 'required|mimes:csv,txt',
        ]);
        Excel::load(Input::file('filename'), function ($reader) {
            $z=2;
            $reqireError=array();
            $distError=array();
            $phoneError=array();
            $phoneNumRepeat=array();
            foreach ($reader->toArray() as $row) {  
                $distCode=(isset($row['dist_code']))? $row['dist_code'] : "";
                $consCode=(isset($row['ac_no']))? $row['ac_no'] : "";
                $name=(isset($row['name']))? $row['name'] : "";
                $email=(isset($row['email']))? $row['email'] : "";
                $phone1=(isset($row['mobileno']))? $row['mobileno'] : "";
                $phone=trim($phone1);
                $address=(isset($row['address']))? $row['address'] : "";
                $designation=(isset($row['desigantion']))? $row['desigantion'] : "";
                $organisation=(isset($row['organisation']))? $row['organisation'] : "";
                $password=(isset($row['password']))? $row['password'] : "";
                $psIdBlo=(isset($row['part_no']))? $row['part_no'] : "";
                $uidSup=trim("BLO".$phone);
                $stateIdDeo=Auth::user()->state_id;

                //--(if 1 start)
                if( !empty($name) && !empty($phone) && !empty($psIdBlo)){

                    //--(if 2 start)
                    if($distCode==""){
                        $distError[]=$z;
                    }
                    else{
                        $phoneChk=$phone;
                        $repeatNumber = DB::table('users')
                                        ->where('phone', $phone)
                                        ->where('role', '!=' , '7')->first();
                        if(!empty($repeatNumber)){
                            $phoneNumRepeat[]=$z;
                        }else{
                            if (preg_match('/^\d{10}$/', $phoneChk)) {
                                $matchSup = DB::table('users')->where('uid',$uidSup)->get();
                                $SupCount=count($matchSup);

                                //--(Update User)
                                if($SupCount>0) {
                                    foreach($matchSup as $matchSups){
                                        if(!empty($password)){
                                            $hashPassword=Hash::make($password);
                                        }
                                        else{
                                            // $oldPassWord=$matchSups->password;
                                           // $oldPassWord=$matchSups->password;
                                            $oldPassWord=(isset($matchSups->password))? $matchSups->password : "";

                                            if($oldPassWord==""){
                                                $newpass=rand(10,1000).time();
                                                $hashPassword=Hash::make($newpass);
                                            }
                                            else{
                                              $hashPassword=$oldPassWord;
                                            }
                                        }
                                        $upUserData = array(
                                            'dist_code' => $distCode,
                                            'cons_code' => $consCode,
                                            'uid' => $uidSup,
                                            'name' => $name,
                                            'email' => $email,
                                            'phone' => $phone,
                                            'address' => $address,
                                            'designation' => $designation,
                                            'organisation' => $organisation,
                                            'password' => $hashPassword,
                                        );
                                        $updateUser = DB::table('users')
                                                    ->where('dist_code', $distCode)
                                                    ->where('state_id', $stateIdDeo)
                                                    ->where('cons_code', $consCode)
                                                    ->where('phone', $phone)
                                                    ->where('role', '7')->update($upUserData);

                                        $upPollBooth = array(
                                        'blo_uid' => $uidSup,
                                        ); 
                                        $updatePollBooth = DB::table('poll_booths')
                                                    ->where('dist_code', $distCode)
                                                    ->where('state_id', $stateIdDeo)
                                                    ->where('cons_code', $consCode)
                                                    ->where('ps_id', $psIdBlo)
                                                    ->update($upPollBooth);

                                    }
                                }
                                //--(Insert New User)
                                else{
                                    $dt = Carbon::now();
                                    $timestamp=$dt->toDateString();
                                    $newpassIns=rand(10,1000).time();
                                    $hashPasswordins=Hash::make($newpassIns);
                                    $insUserData = array(
                                        'uid' => $uidSup,
                                        'dist_code' => $distCode,
                                        'cons_code' => $consCode,
                                        'name' => $name,
                                        'email' => $email,
                                        'phone' => $phone,
                                        'address' => $address,
                                        'designation' => $designation,
                                        'organisation' => $organisation,
                                        'role' => '7',
                                        'password' => $hashPasswordins,
                                        'state_id' => $stateIdDeo,
                                        'updated_at' => $timestamp,
                                    ); 
                                    $insertUser = DB::table('users')->insert($insUserData);
                                    $upPollBoothIns = array(
                                        'blo_uid' => $uidSup,
                                    ); 
                                    $updatePollBoothIns = DB::table('poll_booths')
                                                    ->where('dist_code', $distCode)
                                                    ->where('state_id', $stateIdDeo)
                                                    ->where('cons_code', $consCode)
                                                    ->where('ps_id', $psIdBlo)
                                                    ->update($upPollBoothIns);
                                }
                            }
                            else {
                                $phoneError[]=$z;
                            }
                        }  
                    }
                }
                else{
                    $reqireError[]=$z;  
                }
            $z++;
            }

            $reqireError1 = array_filter($reqireError);
            if (!empty($reqireError1)) {
                $errorReqRow1=implode(',', $reqireError1);
                $errorReqRow="Name, phone number or PsId is missing on row ".$errorReqRow1;
            }else{
               $errorReqRow=""; 
            }
            if($errorReqRow!==""){
                Session::flash('bloError1', $errorReqRow); 
            }
            $distError1 = array_filter($distError);
            if (!empty($distError1)) {
                $errorDstRow1=implode(',', $distError1);
                $errorDstRow="District code is missing on row ".$errorDstRow1;
              }else{
               $errorDstRow=""; 
            }
            if($errorDstRow!==""){
                \Session::flash('bloError2', $errorDstRow);
            }

            $phoneError1 = array_filter($phoneError);
            if (!empty($phoneError1)) {
                $errorPhnRow1=implode(',', $phoneError1);
                $errorPhnRow="Phone number is invalid on row ".$errorPhnRow1;
              }else{
               $errorPhnRow=""; 
            }
            if($errorPhnRow!==""){
                \Session::flash('bloError3', $errorPhnRow);
            }
            $phoneErrorRep1 = array_filter($phoneNumRepeat);
            if (!empty($phoneErrorRep1)) {
                $errorPhnRowRep1=implode(',', $phoneErrorRep1);
                $errorPhnRowRep="Phone number is already used on row ".$errorPhnRowRep1;
              }else{
               $errorPhnRowRep=""; 
            }
            if($errorPhnRowRep!==""){
                \Session::flash('bloError4', $errorPhnRowRep);
            }
        });
        Session::flash('bloSucc', 'BLOs Added Successfully.'); 
        Session::flash('alert-class', 'alert-success');
        return Redirect::to('ro/bloList');

    }

    public function supervisorList()
    {
        $user = Auth::user();
        $supervisors = DB::table('users')->where('dist_code', $user->dist_code)->where('cons_code', $user->cons_code)->where('role', 5)->get();
        return view('ro/supervisor-list', [
            'supervisors' => $supervisors,
        ]);
    }


    public function supervisorDetail($uid)
    {
       $uid = eci_decrypt($uid);
       $polling_stations = DB::table('poll_booths')->where('supervisior_uid', $uid)->where('status', 1)->get();
       $svDetail = DB::table('users')->where('uid', $uid)->first();

       return view('ro/supervisor-detail', [
           'polling_stations' => $polling_stations,
           'svDetail' => $svDetail,
       ]);
    }


    //--Add Spervisor
	public function addSupervisor()
    {
        $user = Auth::user();
        return view('ro/add-supervisor');
    }

    public function addSupervisorCsv()
    { 
        return view('ro/addSupervisorCsv');
    }

    public function importSupCsv(Request $request) {
        $this->validate($request, [
            'filename' => 'required|mimes:csv,txt',
        ]);
        Excel::load(Input::file('filename'), function ($reader) {
            $z=2;
            $reqireError=array();
            $distError=array();
            $phoneError=array();
            foreach ($reader->toArray() as $row) {  
                $distCode=(isset($row['distcode']))? $row['distcode'] : "";
                $consCode=(isset($row['conscode']))? $row['conscode'] : "";
                $name=(isset($row['name']))? $row['name'] : "";
                $email=(isset($row['email']))? $row['email'] : "";
                $phone=(isset($row['phone']))? $row['phone'] : "";
                $address=(isset($row['address']))? $row['address'] : "";
                $designation=(isset($row['designation']))? $row['designation'] : "";
                $organisation=(isset($row['organisation']))? $row['organisation'] : "";
                $password=(isset($row['password']))? $row['password'] : "";
                $uidSup="SUP".$phone;
                $stateIdDeo=Auth::user()->state_id;

                //--(if 1 start)
                if( !empty($name) && !empty($phone)){

                    //--(if 2 start)
                    if($distCode==""){
                        $distError[]=$z;
                    }
                    else{
                        $phoneChk=$phone;
                        if (preg_match('/^\d{10}$/', $phoneChk)) {
                            $matchSup = DB::table('users')
                                        ->where('dist_code', $distCode)
                                        ->where('state_id', $stateIdDeo)
                                        ->where('cons_code', $consCode)
                                        ->where('phone', $phone)
                                        ->where('role', 5)->get();
                            $SupCount=count($matchSup);
                            //--(Update User)
                            if($SupCount>0) {
                                foreach($matchSup as $matchSups){
                                    if(!empty($password)){
                                        $hashPassword=Hash::make($password);

                                    }
                                    else{
                                        $oldPassWord=$matchDists->password;
                                        if($oldPassWord==""){
                                            $newpass=rand(10,1000).time();
                                            $hashPassword=Hash::make($newpass);
                                        }
                                        else{
                                          $hashPassword=$oldPassWord;
                                        }
                                    }
                                    $upUserData = array(
                                        'dist_code' => $distCode,
                                        'cons_code' => $consCode,
                                        'uid' => $uidSup,
                                        'name' => $name,
                                        'email' => $email,
                                        'phone' => $phone,
                                        'address' => $address,
                                        'designation' => $designation,
                                        'organisation' => $organisation,
                                        'password' => $hashPassword,
                                    );
                                    $updateUser = DB::table('users')
                                                ->where('dist_code', $distCode)
                                                ->where('state_id', $stateIdDeo)
                                                ->where('cons_code', $consCode)
                                                ->where('phone', $phone)
                                                ->where('role', 5)->update($upUserData);
                                }
                            }
                            //--(Insert New User)
                            else{
                                $dt = Carbon::now();
                                $timestamp=$dt->toDateString();
                                $newpassIns=rand(10,1000).time();
                                $hashPasswordins=Hash::make($newpassIns);
                                $insUserData = array(
                                    'uid' => $uidSup,
                                    'dist_code' => $distCode,
                                    'cons_code' => $consCode,
                                    'name' => $name,
                                    'email' => $email,
                                    'phone' => $phone,
                                    'address' => $address,
                                    'designation' => $designation,
                                    'organisation' => $organisation,
                                    'role' => 5,
                                    'password' => $hashPasswordins,
                                    'state_id' => $stateIdDeo,
                                    'updated_at' => $timestamp,
                                ); 
                                $insertUser = DB::table('users')->insert($insUserData);
                            }
                        }
                        else {
                            $phoneError[]=$z;
                        }    
                    }
                }
                else{
                    $reqireError[]=$z;  
                }
            $z++;
            }

            $reqireError1 = array_filter($reqireError);
            if (!empty($reqireError1)) {
                $errorReqRow1=implode(',', $reqireError1);
                $errorReqRow="Name or phone number is missing on row ".$errorReqRow1;
            }else{
               $errorReqRow=""; 
            }
            if($errorReqRow!==""){
                Session::flash('supvError', $errorReqRow); 
            }
            $distError1 = array_filter($distError);
            if (!empty($distError1)) {
                $errorDstRow1=implode(',', $distError1);
                $errorDstRow="District code is missing on row ".$errorDstRow1;
              }else{
               $errorDstRow=""; 
            }
            if($errorDstRow!==""){
                \Session::flash('supvError', $errorDstRow);
            }

            $phoneError1 = array_filter($phoneError);
            if (!empty($phoneError1)) {
                $errorPhnRow1=implode(',', $phoneError1);
                $errorPhnRow="Phone number is invalid on row ".$errorPhnRow1;
              }else{
               $errorPhnRow=""; 
            }
            if($errorPhnRow!==""){
                \Session::flash('supvError', $errorPhnRow);
            }
        });
        Session::flash('supvSucc', 'Supervisor Added Successfully.'); 
        Session::flash('alert-class', 'alert-success');
        return Redirect::to('ro/supervisor-list');
    }

    //--Add Supervisor--(Form Submit)
    public function addSupevisorSub(Request $request) {
        $user = Auth::user();
        $this->validate(
        $request, [
            'supvName' => 'required',
            'supvPhone' => 'required|unique:users,phone|size:10',
            //'supvDesig' => 'required', 
            //'supvDpt' => 'required',
            //'supvPass' => 'required|min:6',
            'supvPass' => 'required|min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%]).*$/',
            'supvCpass' => 'required|same:supvPass',
        ],
        [
            'supvName.required' => 'Sector Officer name is missing',
            'supvPhone.required' => 'Sector Officer Phone Number is missing',
            'supvPhone.unique' => 'This Phone Number is already used.',
            'supvPhone.size' => 'Please add 10 digit Phone Number.',
            //'supvDesig.required' => 'Sector Officer Designation is missing',
            //'supvDpt.required' => 'Sector Officer Department is missing',
            'supvPass.required' => 'Sector Officer password is missing',
            'supvPass.min' => 'Sector Officer password must be at least 6 characters.',
            'supvPass.regex' => 'Passwords must contain atleast one special character',
            'supvCpass.required' => 'Sector Officer Confirm Password is missing',
            'supvCpass.same' => 'The Password and Confirm Password must match.',
        ]
        );
        $phoneSup=$request->supvPhone;
        $supUid="SUP".$phoneSup;
        $passwordSup=$request->supvPass;
        $dt = Carbon::now();
        $timestamp=$dt->toDateString();
        $distCode = Auth::user()->dist_code;
        $consCode = Auth::user()->cons_code;
        $addSup = array(
            'name' => $request->supvName,
            'phone' => $request->supvPhone,
            'designation' => $request->supvDesig,
            'organisation' => $request->supvDpt,
            'password' => Hash::make($passwordSup),
            'role' => 5,
            'uid' => $supUid,
            'address' => '',
            'updated_at' =>$timestamp,
            'dist_code' =>$distCode,
            'cons_code' =>$consCode,
        );  
        $addSupVisor = DB::table('users')->insert($addSup); 

        if($addSupVisor>0) {
            Session::flash('supvSucc', 'Supervisor Added Successfully.'); 
            Session::flash('alert-class', 'alert-success');
            return Redirect::to('ro/supervisor-list');
        }else{
            Session::flash('supvError', 'Please Try Again.'); 
            Session::flash('alert-class', 'alert-danger');
            return Redirect::to('ro/add-supervisor');
        }
    }

    //--Edit Supervisor
    public function supervisorEdit($uid)
    {   
        $user = Auth::user();
        $consCode = Auth::user()->cons_code;
        $uidDcr = eci_decrypt($uid);
        $editSupv = DB::table('users')
                    ->where('uid', $uidDcr)
                    ->first();

        $assignedPollStation = DB::table('poll_booths')
                     ->where('supervisior_uid', $uidDcr)
                     ->where('status', 1)
                     ->get();
        //dd($assignedPollStation);

        $pollStation = DB::table('poll_booths')
                     ->where('cons_code', $consCode)
                     ->where('status', 1)
                     ->get();  

        return view('ro/edit-supervisor', [
           'editSupv' => $editSupv,
           'assignedPollStation' => $assignedPollStation,
           'pollStation' => $pollStation,
           'supervisorUID' => $uid,
        ]);
    }


    public function checkAssignedPoll(Request $request) {
        $user = Auth::user();
        $consCode = Auth::user()->cons_code;
        $post = $request->all();
        $pollStationId=eci_decrypt($post['pollAjaxID']);
        $supUid=eci_decrypt($post['supAjaxID']);
        $checkPoll = DB::table('poll_booths')
                     ->where('cons_code', $consCode)
                     ->where('poll_booth_id', $pollStationId)
                     ->where('supervisior_uid', $supUid)
                     ->first();

        if(!empty($checkPoll)){
            $repPollRes[] = array(
            'repPollStatus'  => 0,
            );
            return response()->json([
            'repPollRes' => $repPollRes,
            ]);
        }
        else{
            $checkRepeat = DB::table('poll_booths')
                     ->where('cons_code', $consCode)
                     ->where('poll_booth_id', $pollStationId)
                     ->first();
            if(!empty($checkRepeat)){
                $uidSup=$checkRepeat->supervisior_uid;
                if(!empty($uidSup)){
                   
                    $repPollRes[] = array(
                    'repPollStatus'  => 1,
                    );
                    return response()->json([
                    'repPollRes' => $repPollRes,
                    ]);

                }else{
                    $repPollRes[] = array(
                    'repPollStatus'  => 0,
                    );
                    return response()->json([
                    'repPollRes' => $repPollRes,
                    ]);
                }  
            }
        }
    }


    //--Edit Supervisor--(Form Submit)
    public function upSupervisorSub(Request $request) {
        $user = Auth::user();
        $post = $request->all();
        $phoneSupUp=$post['svPhoneUp'];
        $phoneSupUpOld=$post['svPhoneHidden'];
        if($phoneSupUp==$phoneSupUpOld){
            $this->validate($request, [
                'svNameUp' => 'required',
                'svPhoneUp' => 'required|size:10',
                //'svDesigUp' => 'required', 
                //'svDptUp' => 'required',
                'svPollStations' => 'required',
            ]);
        }
        else
		{
            $this->validate($request, [
                'svNameUp' => 'required',
                'svPhoneUp' => 'required|unique:users,phone|size:10',
                //'svDesigUp' => 'required', 
                //'svDptUp' => 'required',
                'svPollStations' => 'required',
            ]);
        }

        $test=$request->svPollStations;
        foreach($test as $tests){
            eci_decrypt($tests);
        }

        $supUid=eci_decrypt($request->supervisorId);
        $phoneSupUp=$request->svPhoneUp;
        $newSupUidUp="SUP".$phoneSupUp;
        $removePoll = DB::table('poll_booths')
                     ->where('supervisior_uid', $supUid)
                     ->where('status', 1)
                     ->get();

        foreach($removePoll as $removePolls){
            $BoothId=$removePolls->poll_booth_id;
             $remPoll = array(
                'supervisior_uid' => '',
            );
            $remPollAssign = DB::table('poll_booths')
                            ->where('poll_booth_id', $BoothId)
                            ->update($remPoll);
        }
        $pollId=$request->svPollStations;
        foreach($pollId as $value){ 
            $pollBoothId=eci_decrypt($value);
            $upPoll = array(
                'supervisior_uid' => $newSupUidUp,
            );
            $upPollAssign = DB::table('poll_booths')
                            ->where('poll_booth_id', $pollBoothId)
                            ->update($upPoll);
        }  
        $dtUp = Carbon::now();
        $timestampUp=$dtUp->toDateString();
        $uidEncripted=$post['uidSVup'];
        $uidSv=eci_decrypt($uidEncripted);
        $upSup = array(
            'name' => $post['svNameUp'],
            'phone' => $post['svPhoneUp'],
            'uid' => $newSupUidUp,
            'designation' => $post['svDesigUp'],
            'organisation' => $post['svDptUp'],
            'updated_at' =>$timestampUp,
        );
        $upSVisor = DB::table('users')->where('uid', $uidSv)->update($upSup);
        if($upSVisor!=="") {
            Session::flash('supvSucc', 'Supervisor Updated Successfully.'); 
            Session::flash('alert-class', 'alert-success');
            return Redirect::to('ro/supervisor-list');
        }else{
            Session::flash('supvUpError', 'Please Try Again.'); 
            Session::flash('alert-class', 'alert-danger');
            return Redirect::to('ro/supervisor-list');
        }
    }


    public function supervisorDel($uid) {
        $user = Auth::user();
        $consCode = Auth::user()->cons_code;
        $uidDcr = eci_decrypt($uid);

        $delTraning=DB::table('users')
                   ->where('uid', $uidDcr)
                   ->where('role', '5')
                   ->delete();
        if($delTraning>0){
            $upPollStation = array(
                'supervisior_uid' => '',
            );
            $updatePollStation = DB::table('poll_booths')
                               ->where('supervisior_uid', $uidDcr)
                               ->update($upPollStation);
                               
            if($updatePollStation!==""){
                Session::flash('supvSucc', 'Sector Officer deleted successfully.'); 
                Session::flash('alert-class', 'alert-success');
                return Redirect::to('ro/supervisor-list');
            }
            else{
                Session::flash('supvSucc', 'Please Try Again.'); 
                Session::flash('alert-class', 'alert-danger');
                return Redirect::to('ro/supervisor-list');
            } 
        }
        else {
            Session::flash('supvSucc', 'Please Try Again.'); 
            Session::flash('alert-class', 'alert-danger');
            return Redirect::to('ro/supervisor-list');
        }
    }


    //--Add Pooling Station
    public function addPollingStation($uid)
    {
        $user = Auth::user();
        $uidDcrpt=eci_decrypt($uid);
        $sVisorDetail = DB::table('users')->join('constituencies', 'users.cons_code', '=', 'constituencies.cons_code')->join('districts', 'users.dist_code', '=', 'districts.dist_code')->where('users.uid',$uidDcrpt)->first();

        return view('ro/add-PollingStation', [
           'sVisorDetail' => $sVisorDetail,
        ]);
    }

    //--Add Polling Station Submit
    public function addPolStationSubmit(Request $request) {
        $this->validate($request, [
            'svBoothNum' => 'required|max:3',
            'svLocality' => 'required',
            'svPollBuilding' => 'required', 
            'svAreaPollStation' => 'required',
            'svSepEnterExit' => 'required',
            'svPollingArea' => 'required',
            'svVotersType' => 'required', 
            'svTotalVoters' => 'required|max:3',
            'svMaxDistence' => 'required',
        ]);

        //--Uid
        $svUid=eci_decrypt($request->uidSV);

        //--Trimmed Cons Code
        $svConsCode1=eci_decrypt($request->consSV);
        $svConsCode2=trim($svConsCode1);
        $svConsCode = str_pad($svConsCode2, 3, '0', STR_PAD_LEFT);
        
        //--Trimmed Dist Code
        $svDistCode1=eci_decrypt($request->distSV);
        $svDistCode=trim($svDistCode1);
        
        //--Trimmed Booth Number
        $boothNum1=$request->svBoothNum;
        $boothNum2=trim($boothNum1);
        $boothNum = str_pad($boothNum2, 3, '0', STR_PAD_LEFT);
        
        //--BID
        $bid=$svDistCode.$svConsCode.$boothNum;
        $existBid = DB::table('poll_booths')->where('bid', $bid)->get();
        $existBidCount=count($existBid);
        if($existBidCount>0){
            Session::put('addPollErr', 'Booth Number Is Already Used.');
            return Redirect::to('ro/addPollingStation/'.$request->uidSV);
        }else{
            $addPoll = array(
                'booth_no' => $boothNum2,
                'locality' => $request->svLocality,
                'poll_building' => $request->svPollBuilding,
                'area' => $request->svAreaPollStation,
                'separate_entrance' => $request->svSepEnterExit,
                'poll_areas' => $request->svPollingArea,
                'voters_type' => $request->svVotersType,
                'total_voters' => $request->svTotalVoters,
                'max_distance' => $request->svMaxDistence,
                'dist_code' => $svDistCode,
                'cons_code' => $svConsCode2,
                'bid' => $bid,
                'ps_id' => $boothNum2,
                'supervisior_uid' => $svUid,
                'status' => 1,
                'remarks' => $request->svRemarks, 
            ); 
            $addPollStation = DB::table('poll_booths')->insert($addPoll); 
            if($addPollStation>0) {
                Session::flash('addPollSucc', 'Polling Station Added Successfully.'); 
                Session::flash('alert-class', 'alert-success');
                return Redirect::to('ro/supervisor-detail/'.$request->uidSV);
            }else{
                Session::flash('addPollErr', 'Please Try Again'); 
                Session::flash('alert-class', 'alert-danger');
                return Redirect::to('ro/addPollingStation/'.$request->uidSV);
            }
        }
    }

    //--Add Polling Station CSV Form
    public function polingCsvForm($uid)
    {
        $uidDcrpyt=eci_decrypt($uid);
        $svDetails = DB::table('users')->join('constituencies', 'users.cons_code', '=', 'constituencies.cons_code')->join('districts', 'users.dist_code', '=', 'districts.dist_code')->where('users.uid',$uidDcrpyt)->first();
        return view('ro/polingCsvForm', [
           'svDetails' => $svDetails,
        ]);

    }

    //--Add Polling Station CSV--(Form Submit)
    public function polStationExcelSubmit(Request $request) {
        function count_digit($number) {
          return strlen($number);
        }
        //--UID
        $uidSV1=$request->uidSVexcel;
        $uidSV=eci_decrypt($uidSV1);
        $this->uidSvExcel=$uidSV;
        $getSvDetails = DB::table('users')->where('uid', $uidSV)->first();

        //--Dist Code
        $this->distCode=$getSvDetails->dist_code;

        //--Cons Code
        $this->consCode=$getSvDetails->cons_code;
        Excel::load(Input::file('svExcelPollStation'), function ($reader) {
            $uidSupervisor=$this->uidSvExcel;
            $distCodeSupervisor=$this->distCode;
            $consCodeSupervisor=$this->consCode;
            $abc=2;
            $emptyError=array();
            $maxDigitError=array();
            $numericError=array();
            $existingBid=array();
            $existingBidRow=array();
            $maxDigitErrorRow=array();
            $numericErrorRow=array();
            foreach ($reader->toArray() as $row) {
                $boothNumber=$row['booth_number'];
                $locality=$row['locality'];
                $poll_building=$row['poll_building'];
                $pollStationArea=$row['pollling_station_area'];
                $sepEnterExit=$row['separate_entrance_and_exit'];
                $pollAreas=$row['polling_areas'];
                $voterType=$row['voters_type'];
                $totalVoter=$row['total_voters'];
                $maxDistance=$row['maximum_distance'];
                $remarks=$row['remarks'];

                if(!empty($boothNumber) && !empty($locality) && !empty($poll_building) && !empty($pollStationArea) && !empty($sepEnterExit) && !empty($pollAreas) && !empty($voterType) && !empty($totalVoter) && !empty($maxDistance)){

                    if(is_numeric($boothNumber) && is_numeric($totalVoter)){
            
                        $boothNumberDigits = count_digit($boothNumber);
                        if($boothNumberDigits<=3){

                            //--Trimmed Cons Code
                            $svConsCode1=trim($consCodeSupervisor);
                            $svConsCode = str_pad($svConsCode1, 3, '0', STR_PAD_LEFT);
                           
                            //--Trimmed Dist Code
                            $svDistCode=trim($distCodeSupervisor);
                            
                            //--Trimmed Booth Number
                            $boothNumPS1=$boothNumber;
                            $boothNumPS2=trim($boothNumPS1);
                            $boothNumPS = str_pad($boothNumPS2, 3, '0', STR_PAD_LEFT);
                            
                            //--BID
                            $bidSupervisor=$svDistCode.$svConsCode.$boothNumPS;

                            $existingBID = DB::table('poll_booths')->where('bid', $bidSupervisor)->get();
                            $existingCount=count($existingBID);
                            if($existingCount==0){

                                $addPolling = array(
                                    'booth_no' => $boothNumPS2,
                                    'locality' => $locality,
                                    'poll_building' => $poll_building,
                                    'area' => $pollStationArea,
                                    'separate_entrance' => $sepEnterExit,
                                    'poll_areas' => $pollAreas,
                                    'voters_type' => $voterType,
                                    'total_voters' => $totalVoter,
                                    'max_distance' => $maxDistance,
                                    'dist_code' => $distCodeSupervisor,
                                    'cons_code' => $consCodeSupervisor,
                                    'bid' => $bidSupervisor,
                                    'ps_id' => $boothNumPS2,
                                    'supervisior_uid' => $uidSupervisor,
                                    'status' => 1,
                                    'remarks' => $remarks,
                                );     
                                $addPollingSv = DB::table('poll_booths')->insert($addPolling); 

                            }else{
                                $existingBidRow[]=$abc;
                                $existingBid[]=$boothNumber;  
                            }
                        
                        }else{
                            $maxDigitErrorRow[]=$abc;
                            $maxDigitError[]=$boothNumber;
                        }

                    }else{
                        $numericErrorRow[]=$abc;
                        $numericError[]=$boothNumber;
                    }
                    
                }else{
                    $emptyError[]=$abc;
                }
            $abc++;
            }

        $psPath="";   
        //--Bid Repeat Error.
            $existingBidRow1 = array_filter($existingBidRow);
            if (!empty($existingBidRow1)) {
                $errorBidsRow1=implode(',', $existingBidRow1);
                $errorBidsRow="on row ".$errorBidsRow1;
            }else{
               $errorBidsRow=""; 
            }

            $existingBid1 = array_filter($existingBid);
            if (!empty($existingBid1)) {
                $errorBids1=implode(',', $existingBid1);
                $errorBids="Booth numbers ".$errorBids1." are already used";
            }else{
                $errorBids="";
            }

            if(!empty($errorBids) && !empty($errorBidsRow)){
                $psPath=($psPath+1);
                Session::flash('bidsRepeatErr', $errorBids.' '.$errorBidsRow); 
                Session::flash('alert-class', 'alert-danger');
            }

        //--Max Digit Error
            $maxDigitErrorRow1 = array_filter($maxDigitErrorRow);
            if (!empty($maxDigitErrorRow1)) {
                $maxDigitRow1=implode(',', $maxDigitErrorRow1);
                $maxDigitRow="on row ".$maxDigitRow1;
            }else{
                $maxDigitRow="";
            }

            $maxDigitError1 = array_filter($maxDigitError);
            if (!empty($maxDigitError1)) {
                $maxDigit1=implode(',', $maxDigitError1);
                $maxDigit="where booth numbers are ".$maxDigit1;  
            }else{
                $maxDigit=""; 
            }

            if(!empty($maxDigitRow) && !empty($maxDigit)){
                $psPath=($psPath+1);
                Session::flash('maxDigitErr', 'You can not add more then three digits for booth number '.$maxDigitRow.' '.$maxDigit); 
                Session::flash('alert-class', 'alert-danger');
            }

        //--Numeric Value Error
            $numericErrorRow1 = array_filter($numericErrorRow);
            if (!empty($numericErrorRow1)) {
                $numericRow1=implode(',', $numericErrorRow1);
                $numericRow="on row ".$numericRow1;
            }else{
                $numericRow="";
            }

            $numericError1 = array_filter($numericError);
            if (!empty($numericError1)) {
                $numeric1=implode(',', $numericError1);
                $numeric="Where booth numbers are ".$numeric1;
            }else{
                $numeric="";
            }

            if(!empty($numericRow) && !empty($numeric)){
                $psPath=($psPath+1);

                Session::flash('numericErr', 'Please add numeric value for booth number or total voters '.$numericRow.' '.$numeric); 
                Session::flash('alert-class', 'alert-danger');
            }

        //--Both Fields Rewuired
            $emptyError1 = array_filter($emptyError);
            if (!empty($emptyError1)) {
                $emptyValue1=implode(',', $emptyError1);
                $emptyValue="on row ".$emptyValue1;
            }else{
                $emptyValue="";
            }
            if(!empty($emptyValue)){
                $psPath=($psPath+1);
                Session::flash('requireErr', 'Please fill all fields '.$emptyValue); 
                Session::flash('alert-class', 'alert-danger');
            }
            $this->passPS=$psPath;

        });
        $passPath=$this->passPS;
        if($passPath>0){
            return Redirect::to('ro/polingCsvForm/'.$uidSV1);
        }else{
            Session::flash('addPollSucc', 'Polling station added successfully.'); 
            Session::flash('alert-class', 'alert-success'); 
            return Redirect::to('ro/supervisor-detail/'.$uidSV1);  
        } 
    }

	public function pollingStations()
    {
        $user = Auth::user();
        $polling_stations = DB::table('users')->join('poll_booths', 'users.uid', '=', 'poll_booths.supervisior_uid')->where('users.dist_code', $user->dist_code)->where('users.cons_code', $user->cons_code)->where('role', 5)->get();
        return view('ro/polling-stations', [
           'polling_stations' => $polling_stations,
       ]);
    }

    //--Add Root Plan
    public function uploadRoutePlan($uid)
    {
        $user = Auth::user();
        $uidRoot=eci_decrypt($uid);
        $supDetail = DB::table('users')->where('uid',$uidRoot)->first();
        $addedRootPlan = DB::table('route_plan')->where('uid',$uidRoot)->get();
        return view('ro/routePlanForm', [
           'supDetail' => $supDetail,
           'addedRootPlan' => $addedRootPlan,
        ]);
    }

    //--Add Root Plan--(Form Submit)
    public function routePlanSub(Request $request) {
        $user = Auth::user();
        $uidRootP=$request->uidRootPlan;
        $uidDecRoot=eci_decrypt($uidRootP);
        $this->validate($request, [
            'rootPlanPdf' => 'required|mimes:pdf',
        ]);

        //--Upload Document
        $chkPdf= (isset($request['rootPlanPdf']))? $request['rootPlanPdf'] : "";
        if($chkPdf!==""){
            $filesRoot = Input::file('rootPlanPdf');
            //$destinationRoot = 'route_plan';
            $filenameRoot = $filesRoot->getClientOriginalName();
            $randomRoot=time().rand(10,999999);
            $filenameNewRoot = $randomRoot.$filenameRoot;
            
            $s3 = S3Client::factory();
            $bucket = getenv('S3_BUCKET')?: die('No "S3_BUCKET" config var in found in env!');
            $upload = $s3->upload($bucket, $filenameNewRoot, fopen($_FILES['rootPlanPdf']['tmp_name'], 'rb'), 'public-read');
            
            // $filesRoot = Input::file('rootPlanPdf');
            // $destinationRoot = 'route_plan';
            // $filenameRoot = $filesRoot->getClientOriginalName();
            // $randomRoot=time().rand(10,999999);
            // $filenameNewRoot = $randomRoot.$filenameRoot;
            // $upload_successRoot= $filesRoot->move($destinationRoot, $filenameNewRoot);

            $pdfName = $filenameNewRoot;
        }
        else{
            $pdfName="";
        }

        $chkRootRep = DB::table('route_plan')->where('uid',$uidDecRoot)->get();
        $countRepPlan=count($chkRootRep);
        if($countRepPlan>0){
            $upRoot = array(
                'doc_name' => $pdfName,
            );
            $upRootPlan = DB::table('route_plan')->where('uid', $uidDecRoot)->update($upRoot);
            if($upRootPlan>0){
                Session::flash('rootPlanSucc', 'Route plan is updated successfully.'); 
                Session::flash('alert-class', 'alert-success'); 
                return Redirect::to('ro/uploadRoutePlan/'.$uidRootP);
            }
            else{
                Session::flash('rootPlanErr', 'Please try again.'); 
                Session::flash('alert-class', 'alert-danger'); 
                return Redirect::to('ro/uploadRoutePlan/'.$uidRootP); 
            }
        }
        else {
            $svDetails = DB::table('users')->where('uid',$uidDecRoot)->first();
            $distSv=$svDetails->dist_code;
            $consSv=$svDetails->cons_code;
            $stateSv=$svDetails->state_id;
            $addNewPlan = array(
            'uid' => $uidDecRoot,
            'state_id' => $stateSv,
            'dist_code' => $distSv,
            'cons_code' => $consSv,
            'doc_name' => $pdfName,
            );
            $addPlan = DB::table('route_plan')->insert($addNewPlan);
            if($addPlan>0){
                Session::flash('rootPlanSucc', 'Route plan is added successfully.'); 
                Session::flash('alert-class', 'alert-success'); 
                return Redirect::to('ro/uploadRoutePlan/'.$uidRootP);
            }else{
                Session::flash('rootPlanErr', 'Please try again.'); 
                Session::flash('alert-class', 'alert-danger'); 
                return Redirect::to('ro/uploadRoutePlan/'.$uidRootP); 
            }
        }
    }

	public function pollingStationsMap($type = NULL)
    {
        $user = Auth::user();
        $polling_stations1 = DB::table('users')
                            ->join('poll_booths', 'users.uid', '=', 'poll_booths.supervisior_uid')
                            ->where('users.dist_code', $user->dist_code)
                            ->where('users.cons_code', $user->cons_code)
                            ->where('role', 5);
        if(@$type){
            $polling_stations1 = $polling_stations1->where('poll_type', $type);   
        }
        $polling_stations =  $polling_stations1->get();
        return view('ro/polling-stations-map', [
           'polling_stations' => $polling_stations,
       ]);
    }
	public function pollingDetail($poll_booth_id)
    {
        $user = Auth::user();
        $poll_booth_id = eci_decrypt($poll_booth_id);
        $polling_detail = DB::table('poll_booths')
                           ->where('poll_booth_id', $poll_booth_id)
                           ->first();
       $polling_facility = DB::table('poll_booths_web')
                           ->where('bid', $polling_detail->bid)
                           ->first();
        return view('ro/polling-detail', [
           'polling_detail' => $polling_detail,
           'polling_facility' => $polling_facility,
       ]);
    }

    //--Add Pre Poll Arrangement
	public function prePollArrangement()
    {    
        $user = Auth::user();
        $uidRo=Auth::user()->uid;
        $secPlan = DB::table('pre_poll_arrangement_ro')->where('uid',$uidRo)->where('doc_type',"SEC")->get();
        $transPlan = DB::table('pre_poll_arrangement_ro')->where('uid',$uidRo)->where('doc_type',"TRANS")->get();

        $consMap = DB::table('pre_poll_arrangement_ro')->where('uid',$uidRo)->where('doc_type',"CONSMAP")->get();

        return view('ro/pre-poll-arrangement', [
           'secPlan' => $secPlan,
           'transPlan' => $transPlan,
           'consMap' => $consMap,
        ]);
    }


    public function prePollSub(Request $request) {
        $user = Auth::user(); 
        $this->validate($request, [
            'sectorPlan' => 'max:10000|mimes:pdf',
            'transRoutePlan' => 'max:10000|mimes:csv,xls,xlsx,txt',
            'consMap' => 'max:10000|mimes:pdf',
        ]);
        if(!empty($request->sectorPlan) || !empty($request->transRoutePlan) || !empty($request->consMap)){

            $uidRo = Auth::user()->uid;
            $stateRo = Auth::user()->state_id;
            $distRo = Auth::user()->dist_code;
            $consRo = Auth::user()->cons_code;

            //--Sec Plan
                $sectorPlan= (isset($request['sectorPlan']))? $request['sectorPlan'] : "";
                if($sectorPlan!==""){
                    // $this->validate($request, [
                    //     'sectorPlan' => 'required|max:10000|mimes:pdf',
                    // ]);
                    $filesSec = Input::file('sectorPlan');
                    $destinationSec = 'files';
                    $filenameSec = $filesSec->getClientOriginalName();
                    $randomSec=rand(10,999999).time();
                    $filenameSecN = $randomSec.$filenameSec;
                    
                    $s3 = S3Client::factory();
                    $bucket = getenv('S3_BUCKET')?: die('No "S3_BUCKET" config var in found in env!');
                    $upload = $s3->upload($bucket, $filenameSecN, fopen($_FILES['sectorPlan']['tmp_name'], 'rb'), 'public-read');

                    //$upload_successSec= $filesSec->move($destinationSec, $filenameSecN);
                    $secFileName = $filenameSecN;
                    $secPlanRep = DB::table('pre_poll_arrangement_ro')->where('uid',$uidRo)->where('doc_type',"SEC")->get();
                    
                    $countSecs=count($secPlanRep);
                    if($countSecs>0){
                        $upSec = array(
                            'doc_name' => $secFileName,
                        );
                        $upSecPlan = DB::table('pre_poll_arrangement_ro')->where('uid', $uidRo)->where('doc_type',"SEC")->update($upSec);
                        Session::flash('prePollMsz', 'Pre-Poll Arrangement updated successfully.'); 
                        Session::flash('alert-class', 'alert-success');
                    }
                    else{
                        $addSec = array(
                            'uid' => $uidRo,
                            'state_id' => $stateRo,
                            'dist_code' => $distRo,
                            'cons_code' => $consRo,
                            'doc_name' => $secFileName,
                            'doc_type' => "SEC",
                        );
                        $addSecs = DB::table('pre_poll_arrangement_ro')->insert($addSec);
                        Session::flash('prePollMsz', 'Pre-Poll Arrangement added successfully.'); 
                        Session::flash('alert-class', 'alert-success');
                    }
                }
                

            //--Trans Plan
                $transRoutePlan= (isset($request['transRoutePlan']))? $request['transRoutePlan'] : "";
                if($transRoutePlan!==""){
                    // $this->validate($request, [
                    //     'transRoutePlan' => 'required|max:10000|mimes:csv,xls,xlsx',
                    // ]);
                    $filesTrans = Input::file('transRoutePlan');
                    $destinationTrs = 'files';
                    $filenameT = $filesTrans->getClientOriginalName();
                    $randomT=rand(10,999999).time();
                    $filenameTrs = $randomT.$filenameT;
                    
                    $s3 = S3Client::factory();
                    $bucket = getenv('S3_BUCKET')?: die('No "S3_BUCKET" config var in found in env!');
                    $upload = $s3->upload($bucket, $filenameTrs, fopen($_FILES['transRoutePlan']['tmp_name'], 'rb'), 'public-read');
                    //$upload_successT= $filesTrans->move($destinationTrs, $filenameTrs);
                    $trnsFileName = $filenameTrs;
                    $transPlanRe = DB::table('pre_poll_arrangement_ro')->where('uid',$uidRo)->where('doc_type',"TRANS")->get();

                    $countTrans=count($transPlanRe);
                    if($countTrans>0){
                        $upTrns = array(
                        'doc_name' => $trnsFileName,
                        );
                        $upTrnsPlan = DB::table('pre_poll_arrangement_ro')->where('uid', $uidRo)->where('doc_type',"TRANS")->update($upTrns); 
                        Session::flash('prePollMsz', 'Pre-Poll Arrangement updated successfully.'); 
                        Session::flash('alert-class', 'alert-success');
                    }
                    else{
                        $addTrns = array(
                            'uid' => $uidRo,
                            'state_id' => $stateRo,
                            'dist_code' => $distRo,
                            'cons_code' => $consRo,
                            'doc_name' => $trnsFileName,
                            'doc_type' => "TRANS",
                        );
                        $addTrans = DB::table('pre_poll_arrangement_ro')->insert($addTrns);
                        Session::flash('prePollMsz', 'Pre-Poll Arrangement added successfully.'); 
                        Session::flash('alert-class', 'alert-success');
                    }
                }
             

                 //--Cons Plan
                $consMap= (isset($request['consMap']))? $request['consMap'] : "";
                if($consMap!==""){
                    // $this->validate($request, [
                    //     'consMap' => 'required|max:10000|mimes:pdf',
                    // ]);
                    $filesCons = Input::file('consMap');
                    $destinationCons = 'files';
                    $filenameCons = $filesCons->getClientOriginalName();
                    $randomSec=rand(10,999999).time();
                    $filenameConsN = $randomSec.$filenameCons;
                    
                    $s3 = S3Client::factory();
                    $bucket = getenv('S3_BUCKET')?: die('No "S3_BUCKET" config var in found in env!');
                    $upload = $s3->upload($bucket, $filenameConsN, fopen($_FILES['consMap']['tmp_name'], 'rb'), 'public-read');

                    //$upload_successCons= $filesCons->move($destinationCons, $filenameConsN);
                    $consFileName = $filenameConsN;

                    $consPlanRep = DB::table('pre_poll_arrangement_ro')->where('uid',$uidRo)->where('doc_type',"CONSMAP")->get();
                    
                    $countCons=count($consPlanRep);
                    if($countCons>0){
                        $upCons = array(
                            'doc_name' => $consFileName,
                        );
                        $upConsPlan = DB::table('pre_poll_arrangement_ro')->where('uid', $uidRo)->where('doc_type',"CONSMAP")->update($upCons);
                        Session::flash('prePollMsz', 'Pre-Poll Arrangement updated successfully.'); 
                        Session::flash('alert-class', 'alert-success');
                    }
                    else{
                        $addSec = array(
                            'uid' => $uidRo,
                            'state_id' => $stateRo,
                            'dist_code' => $distRo,
                            'cons_code' => $consRo,
                            'doc_name' => $consFileName,
                            'doc_type' => "CONSMAP",
                        );
                        $addSecs = DB::table('pre_poll_arrangement_ro')->insert($addSec);
                        Session::flash('prePollMsz', 'Pre-Poll Arrangement added successfully.'); 
                        Session::flash('alert-class', 'alert-success');
                    }
                }
            return Redirect::to('ro/pre-poll-arrangement');
        }
        else {
            Session::flash('prePollMsz', 'Please upload atleast one file..'); 
            Session::flash('alert-class', 'alert-danger');
            return Redirect::to('ro/pre-poll-arrangement');
        }
    }
    

	public function pollDay() {
        $user = Auth::user();
        $stateID=Auth::user()->state_id;
        $distCode=Auth::user()->dist_code;
        $consCode=Auth::user()->cons_code;

        // $pollDayDetail = DB::table('pro_activity_pollday')
        //                     ->join('poll_booths','poll_booths.cons_code','=','pro_activity_pollday.cons_code')
        //                     ->where('poll_booths.state_id', $stateID)
        //                     ->where('poll_booths.dist_code', $distCode)
        //                     ->where('poll_booths.cons_code', $consCode)
        //                     ->select('poll_booths.poll_building','poll_booths.bid', 'pro_activity_pollday.*')
        //                     ->get();

        $pollDayDetail = DB::table('poll_booths')
							->leftjoin('pro_activity_pollday', 'poll_booths.bid', '=', 'pro_activity_pollday.bid')
                            ->where('poll_booths.state_id', $stateID)
                            ->where('poll_booths.dist_code', $distCode)
                            ->where('poll_booths.cons_code', $consCode)
                            ->get();

         return view('ro/poll-day', [
           'pollDayDetail' => $pollDayDetail,
        ]);
    }

    public function pollPercentage() {
        $user = Auth::user();
        $stateID=Auth::user()->state_id;
        $distCode=Auth::user()->dist_code;
        $consCode=Auth::user()->cons_code;
        $current_time = current_hour();
        if($current_time>=1 && $current_time<10){
            $timeslot = 'percentage_8';
        }
        elseif($current_time>=10 && $current_time<12){
            $timeslot = 'percentage_10';
        }
        elseif($current_time>=12 && $current_time<14){
            $timeslot = 'percentage_12';
        }
        elseif($current_time>=14 && $current_time<16){
            $timeslot = 'percentage_14';
        }
        elseif($current_time>=16 && $current_time<18){
            $timeslot = 'percentage_16';
        }
        else{
            $timeslot = 'percentage_18';
        }
        $pollpercentages = DB::table('poll_booths')
                            ->leftjoin('pro_polling_percentage', 'poll_booths.bid', '=', 'pro_polling_percentage.bid')
                            ->where('poll_booths.state_id', $stateID)
                            ->where('poll_booths.dist_code', $distCode)
                            ->where('poll_booths.cons_code', $consCode)
                            ->select('pro_polling_percentage.'.$timeslot,'poll_booths.poll_building','poll_booths.bid')
                            ->get();
        return view('ro/poll-percentage', [
           'pollpercentages' => $pollpercentages,
           'polltiming' => $timeslot,
        ]);
    }

    public function pollPercentagetiming(Request $request) {
        $this->validate($request, [
            'polltiming' => 'required',
        ]);
        $user = Auth::user();
        $stateID=Auth::user()->state_id;
        $distCode=Auth::user()->dist_code;
        $consCode=Auth::user()->cons_code;
        $polltiming = $request->polltiming;
        $pollpercentages = DB::table('poll_booths')
                            ->leftjoin('pro_polling_percentage', 'poll_booths.bid', '=', 'pro_polling_percentage.bid')
                            ->where('poll_booths.state_id', $stateID)
                            ->where('poll_booths.dist_code', $distCode)
                            ->where('poll_booths.cons_code', $consCode)
                            ->select('pro_polling_percentage.'.$polltiming,'poll_booths.poll_building','poll_booths.bid')
                            ->get();
        return view('ro/poll-percentage', [
           'pollpercentages' => $pollpercentages,
           'polltiming' => $polltiming,
        ]);
    }



    public function pollingPercentageDetail($bid) {
        $user = Auth::user();
        $stateID=Auth::user()->state_id;
        $distCode=Auth::user()->dist_code;
        $consCode=Auth::user()->cons_code;
        $bid=eci_decrypt($bid);
        $pollpercentageDetail = DB::table('poll_booths')
                            ->leftjoin('pro_polling_percentage', 'poll_booths.bid', '=', 'pro_polling_percentage.bid')
                            ->where('poll_booths.state_id', $stateID)
                            ->where('poll_booths.dist_code', $distCode)
                            ->where('poll_booths.cons_code', $consCode)
                            ->where('poll_booths.bid', $bid)
                            ->first();

        return view('ro/polling-percentage-detail', [
          'pollpercentageDetail' => $pollpercentageDetail,
        ]);
    }


	public function annexureReport() {
        $user = Auth::user();
        $uidRo=Auth::user()->uid;
        $stateID=Auth::user()->state_id;
        $distCode=Auth::user()->dist_code;
        $consCode=Auth::user()->cons_code;
        $annexureReport = DB::table('ro_report')->where('uid', $uidRo)->where('state_id', $stateID)->where('dist_code', $distCode)->where('cons_code', $consCode)->where('doc_type', "ANNEXURE")->first();
        return view('ro/annexure-report', [
           'annexureReport' => $annexureReport,
        ]);
    }


    public function annexureReportSub(Request $request) {
        $this->validate($request, [
            'consolidated' => 'required',
        ]);
        $uidRo=Auth::user()->uid;
        $stateID=Auth::user()->state_id;
        $distCode=Auth::user()->dist_code;
        $consCode=Auth::user()->cons_code;
        $filesAnex = Input::file('consolidated');
        $destinationAnex = 'files';
        $filenameAnex = $filesAnex->getClientOriginalName();
        $randomSec=rand(10,999999).time();
        $filenameAnexN = $randomSec.$filenameAnex;

        $s3 = S3Client::factory();
        $bucket = getenv('S3_BUCKET')?: die('No "S3_BUCKET" config var in found in env!');
        $upload = $s3->upload($bucket, $filenameAnexN, fopen($_FILES['consolidated']['tmp_name'], 'rb'), 'public-read');

        //$upload_success= $filesAnex->move($destinationAnex, $filenameAnexN);
        $AnexFileName = $filenameAnexN;

        $findRepeat = DB::table('ro_report')->where('uid', $uidRo)->where('state_id', $stateID)->where('dist_code', $distCode)->where('cons_code', $consCode)->where('doc_type', "ANNEXURE")->first();
        if(!empty($findRepeat)){
            $annexReport = array(
                'doc_name' => $AnexFileName,
            );
            $annexReportUpdate = DB::table('ro_report')->where('uid', $uidRo)->where('state_id', $stateID)->where('dist_code', $distCode)->where('cons_code', $consCode)->where('doc_type', "ANNEXURE")->update($annexReport); 

            if($annexReportUpdate>0){
                Session::flash('anexMessage', 'Report updated successfully.'); 
                Session::flash('alert-class', 'alert-success'); 
                return Redirect::to('ro/annexure-report');
            }else{
                Session::flash('anexMessage', 'Please try again.'); 
                Session::flash('alert-class', 'alert-success'); 
                return Redirect::to('ro/annexure-report');
            }
        }
        else{
            $addAnnex = array(
                'uid' => $uidRo,
                'state_id' => $stateID,
                'dist_code' => $distCode,
                'cons_code' => $consCode,
                'doc_name' => $AnexFileName,
                'doc_type' => "ANNEXURE",
            );
            $addAnnexReport = DB::table('ro_report')->insert($addAnnex);
            if($addAnnexReport>0){
                Session::flash('anexMessage', 'Report updated successfully.'); 
                Session::flash('alert-class', 'alert-success'); 
                return Redirect::to('ro/annexure-report');
            }else{
                Session::flash('anexMessage', 'Please try again.'); 
                Session::flash('alert-class', 'alert-success'); 
                return Redirect::to('ro/annexure-report');
            } 
        }
    }


	public function polledEvm()
    {
        $user = Auth::user();
        return view('ro/polled-evm');
    }

	public function electionObservers()
    {
        $user = Auth::user();
        $state_id = $user->state_id;
        $distCode= $user->dist_code;
        $consCode=Auth::user()->cons_code;
        $generalObserver = DB::table('observer')
                        ->join('constituencies','observer.cons_code','=','constituencies.cons_code')
                        ->where('observer.dist_code', $distCode)
                        ->where('observer.cons_code', $consCode)
                        ->where('observer.type', "General Observer")
                        ->orderby('constituencies.cons_code')
                        ->get();

        $expenditureObserver = DB::table('observer')
                        ->join('constituencies','observer.cons_code','=','constituencies.cons_code')
                        ->where('observer.dist_code', $distCode)
                        ->where('observer.cons_code', $consCode)
                        ->where('observer.type', "Expenditure Observer")
                        ->orderby('constituencies.cons_code')
                        ->get();

        $policeObserver = DB::table('observer')
                        ->join('constituencies','observer.cons_code','=','constituencies.cons_code')
                        ->where('observer.dist_code', $distCode)
                        ->where('observer.cons_code', $consCode)
                        ->where('observer.type', "Police Observer")
                        ->orderby('constituencies.cons_code')
                        ->get();

        $awarenessObserver = DB::table('observer')
                        ->join('constituencies','observer.cons_code','=','constituencies.cons_code')
                        ->where('observer.dist_code', $distCode)
                        ->where('observer.cons_code', $consCode)
                        ->where('observer.type', "Awareness Observer")
                        ->orderby('constituencies.cons_code')
                        ->get();

        return view('ro/election-observers', [
           'generalObserver' => $generalObserver,
           'expenditureObserver' => $expenditureObserver,
           'policeObserver' => $policeObserver,
           'awarenessObserver' => $awarenessObserver,
        ]);
    }
    
	public function observerProfile($id) {
        $user = Auth::user();
        $observerdata = DB::table('observer')
                        ->where('observer.id', eci_decrypt($id))
                        ->first();
        return view('ro/observer-profile', [
           'observerdata' => $observerdata,
        ]);
    }

	public function p1Scrutiny()
    {
        $user = Auth::user();
        $uidRo=Auth::user()->uid;
        $stateID=Auth::user()->state_id;
        $distCode=Auth::user()->dist_code;
        $consCode=Auth::user()->cons_code;
        $scrutinyReport = DB::table('ro_report')->where('uid', $uidRo)->where('state_id', $stateID)->where('dist_code', $distCode)->where('cons_code', $consCode)->where('doc_type', "SCRUTINY")->first();
        return view('ro/p1-scrutiny', [
           'scrutinyReport' => $scrutinyReport,
        ]);
    }

    public function scrutinyReportSub(Request $request) {
        $this->validate($request, [
            'scrutiny' => 'required|mimes:xls,xlsx',
        ]);
        $uidRo=Auth::user()->uid;
        $stateID=Auth::user()->state_id;
        $distCode=Auth::user()->dist_code;
        $consCode=Auth::user()->cons_code;
        $filesAnex = Input::file('scrutiny');
        $destinationAnex = 'files';
        $filenameAnex = $filesAnex->getClientOriginalName();
        $randomSec=rand(10,999999).time();
        $filenameAnexN = $randomSec.$filenameAnex;

        $s3 = S3Client::factory();
        $bucket = getenv('S3_BUCKET')?: die('No "S3_BUCKET" config var in found in env!');
        $upload = $s3->upload($bucket, $filenameAnexN, fopen($_FILES['scrutiny']['tmp_name'], 'rb'), 'public-read');


        //$upload_success= $filesAnex->move($destinationAnex, $filenameAnexN);
        $AnexFileName = $filenameAnexN;
        $findRepeat = DB::table('ro_report')->where('uid', $uidRo)->where('state_id', $stateID)->where('dist_code', $distCode)->where('cons_code', $consCode)->where('doc_type', "SCRUTINY")->first();
        if(!empty($findRepeat)){
            $annexReport = array(
                'doc_name' => $AnexFileName,
            );
            $annexReportUpdate = DB::table('ro_report')->where('uid', $uidRo)->where('state_id', $stateID)->where('dist_code', $distCode)->where('cons_code', $consCode)->where('doc_type', "SCRUTINY")->update($annexReport); 

            if($annexReportUpdate>0){
                Session::flash('anexMessage', 'Report updated successfully.'); 
                Session::flash('alert-class', 'alert-success'); 
                return Redirect::to('ro/p1-scrutiny');
            }else{
                Session::flash('anexMessage', 'Please try again.'); 
                Session::flash('alert-class', 'alert-success'); 
                return Redirect::to('ro/p1-scrutiny');
            }
        }
        else{
            $addAnnex = array(
                'uid' => $uidRo,
                'state_id' => $stateID,
                'dist_code' => $distCode,
                'cons_code' => $consCode,
                'doc_name' => $AnexFileName,
                'doc_type' => "SCRUTINY",
            );
            $addAnnexReport = DB::table('ro_report')->insert($addAnnex);
            if($addAnnexReport>0){
                Session::flash('anexMessage', 'Report updated successfully.'); 
                Session::flash('alert-class', 'alert-success'); 
                return Redirect::to('ro/p1-scrutiny');
            }else{
                Session::flash('anexMessage', 'Please try again.'); 
                Session::flash('alert-class', 'alert-success'); 
                return Redirect::to('ro/p1-scrutiny');
            } 
        }
    }

    public function p1ConsolidatedReportUpdate() {
        $user = Auth::user();
        $uidRo=Auth::user()->uid;
        $upConsReport = DB::table('ro_consolidated_report')->where('uid', $uidRo)->first();
        return view('ro/p1-consolidated-report-update', [
           'upConsReport' => $upConsReport,
        ]);
    }

    public function updateP1ConsReportSub(Request $request) {
        $user = Auth::user();
        $this->validate(
            $request, 
            [
                'weatherUp' => 'required',
                'pollPercentageUp' => 'required|numeric|between:0,100',
                'poleInterruptionUp' => 'required',
                'unlawfullEvmUp' => 'required',
                'unlawfullVoterUp' => 'required',
                'boothCapturingUp' => 'required',
                'seriousCompUp' => 'required',
                'violenceUp' => 'required',
                'mistakeUp' => 'required',
                'pre_scrutinyUp' => 'required',
                'recommendations_repollUp' => 'required',
            ],
            [
                'weatherUp.required' => 'Please Add Weather Conditions',
                'pollPercentageUp.required' => 'Please Add Poll Percentage',
                'pollPercentageUp.between' => 'Please Add Value Between 0 and 100',
                'pollPercentageUp.numeric' => 'Please Add Numeric Value',
            ]
        );
        $uIdRo=Auth::user()->uid;
        $stateIdRo=Auth::user()->state_id;
        $distRo=Auth::user()->dist_code;
        $consRo=Auth::user()->cons_code;
        $dt = Carbon::now();
        $timestamp=$dt->toDateString();
        $upP1Cons = array(
            'uid' => $uIdRo,
            'state_id' => $stateIdRo,
            'dist_code' => $distRo,
            'cons_code' => $consRo,
            'interruption' => $request->poleInterruptionUp,
            'vitiation_evm_unlawfully' => $request->unlawfullEvmUp,
            'votes_unlawfully' => $request->unlawfullVoterUp,
            'booth_capturing' => $request->boothCapturingUp,
            'serious_complaint' => $request->seriousCompUp,
            'violence_law_order' => $request->violenceUp,
            'mistake_irregularities' => $request->mistakeUp,
            'weather_conditions' => $request->weatherUp,
            'poll_percentage' => $request->pollPercentageUp,
            'pre_scrutiny' => $request->pre_scrutinyUp,
            'recommendations_repoll' => $request->recommendations_repollUp,
            'remarks' => $request->remarksUp,
            'updated_at' => $timestamp,  
        );
        $upP1ConsReport = DB::table('ro_consolidated_report')->where('uid', $uIdRo)->update($upP1Cons);
        if($upP1ConsReport!==""){
            Session::flash('addP1ConsMsz', 'Report Updated successfully.'); 
            Session::flash('alert-class', 'alert-success'); 
            return Redirect::to('ro/p1-consolidated-report');
        }else{
            Session::flash('addP1ConsMsz', 'Please try again.'); 
            Session::flash('alert-class', 'alert-danger'); 
            return Redirect::to('ro/p1-consolidated-report');
        }
    }

    
	public function p1ConsolidatedReport() {
        $user = Auth::user();
        $uidRo=Auth::user()->uid;
        $consReport = DB::table('ro_consolidated_report')->where('uid', $uidRo)->first();
        if(!empty($consReport)){
            return view('ro/p1-consolidated-report', [
                'count' => 1,
                'consReport' => $consReport,
            ]);
        }else{
            return view('ro/p1-consolidated-report', [
                'count' => 0,
            ]);
        }
    }


    public function addP1ConsReportSub(Request $request) {
        $user = Auth::user();
        $this->validate(
        $request, 
        [
            'weather' => 'required',
            'pollPercentage' => 'required|integer|between:0,100',
            'poleInterruption' => 'required',
            'unlawfullEvm' => 'required',
            'unlawfullVoter' => 'required',
            'boothCapturing' => 'required',
            'seriousComp' => 'required',
            'violence' => 'required',
            'mistake' => 'required',
            'pre_scrutiny' => 'required',
            'recommendations_repoll' => 'required',
        ],
        [
            'weather.required' => 'Please Add Weather Conditions',
            'pollPercentage.required' => 'Please Add Poll Percentage',
            'pollPercentage.between' => 'Please Add Value Between 0 and 100',
            'pollPercentage.integer' => 'Please Add Numeric Value',
        ]
        );
        $uIdRo=Auth::user()->uid;
        $stateIdRo=Auth::user()->state_id; 
        $distRo=Auth::user()->dist_code;
        $consRo=Auth::user()->cons_code;
        $dt = Carbon::now();
        $timestamp=$dt->toDateString();
        $addP1Cons = array(
            'uid' => $uIdRo,
            'state_id' => $stateIdRo,
            'dist_code' => $distRo,
            'cons_code' => $consRo,
            'interruption' => $request->poleInterruption,
            'vitiation_evm_unlawfully' => $request->unlawfullEvm,
            'votes_unlawfully' => $request->unlawfullVoter,
            'booth_capturing' => $request->boothCapturing,
            'serious_complaint' => $request->seriousComp,
            'violence_law_order' => $request->violence,
            'mistake_irregularities' => $request->mistake,
            'weather_conditions' => $request->weather,
            'poll_percentage' => $request->pollPercentage,
            'pre_scrutiny' => $request->pre_scrutiny,
            'recommendations_repoll' => $request->recommendations_repoll,
            'remarks' => $request->remarks,
            'updated_at' => $timestamp,  
        );
        $addP1ConsReport = DB::table('ro_consolidated_report')->insert($addP1Cons);
        if($addP1ConsReport>0){
            Session::flash('addP1ConsMsz', 'Report added successfully.'); 
            Session::flash('alert-class', 'alert-success'); 
            return Redirect::to('ro/p1-consolidated-report');
        }else{
            Session::flash('addP1ConsMsz', 'Please try again.'); 
            Session::flash('alert-class', 'alert-danger'); 
            return Redirect::to('ro/p1-consolidated-report');
        }
    }


    public function p1ConsolidatedReportAdd()
    {
        $user = Auth::user();
        return view('ro/p1-consolidated-report-add');
    }

	public function boothAwarenessGroup($bid)
    {
        $user = Auth::user();
        $state_id = $user->state_id;
        $poll_booth_id = eci_decrypt($bid);
        $polling_detail = DB::table('booth_awareness_groups')
                           ->where('bid', $poll_booth_id)
                           ->get();
        return view('ro/booth-awareness-group', [
           'polling_detail' => $polling_detail,
       ]);
    }
	
	public function boothPhotos($poll_booth_id)
    {
        $user = Auth::user();
        $state_id = $user->state_id;
        $poll_booth_id = eci_decrypt($poll_booth_id);
        $poll_booth_id = str_pad($poll_booth_id, 8, '0', STR_PAD_LEFT);
        $dist_code = substr($poll_booth_id,0,2);
        //dd($dist_code);
        //$dist_code = ltrim($dist_code1, '0');
        $cons_code = substr($poll_booth_id,2,3);
        //$cons_code = ltrim($cons_code1, '0')
        $ps_id = substr($poll_booth_id,5,7);
        //$ps_id = ltrim($ps_id1, '0');
        // $polling_detail = DB::table('poll_booths')
        //                    ->where('bid', $poll_booth_id)
        //                    ->first();
        $pollImages = app('App\Http\Controllers\CronjobController')->get_poll_images($state_id,$dist_code,$cons_code,$ps_id);
         //$votersListAPI = app('App\Http\Controllers\CronjobController')->get_poll_images($state_id,$dist_code,$cons_code,$ps_id);
        $pollImages1 = json_decode($pollImages);
        return view('ro/booth-photos', [
           'images' => $pollImages1,
       ]);
    }


    //-- Update Polling Booth Lat-Long
    public function pollBoothLatLong()
    {
        $user = Auth::user();
        return view('ro/pollBooth-LatLong');
    }

    //-- Update Polling Booth Lat-Long--(Form Submit)
    public function updatePollBoothLatLong(Request $request) {
        Excel::load(Input::file('pBoothLatLongExcel'), function ($reader) { 
            foreach ($reader->toArray() as $row) {
                $distCode=11;
                $boothNo=$row['part_no'];
                $consCode=$row['ac_no']; 
                $lat=$row['latitude'];
                $long=$row['longitude'];
                //--Genrate BID
                $consCode1=trim($consCode);
                $consCodeNew = str_pad($consCode1, 3, '0', STR_PAD_LEFT);
                $boothNo1=trim($boothNo);
                $boothNoNew = str_pad($boothNo1, 3, '0', STR_PAD_LEFT);
                $bidLatLong=$distCode.$consCodeNew.$boothNoNew;

                $upLatLong = array(
                    'latitude' => $lat,
                    'longitude' => $long,
                );
                $upPollBoothLatLong = DB::table('poll_booths')->where('bid', $bidLatLong)->update($upLatLong); 
            }
        });
        Session::flash('latLongUpErr', 'Lat-Long Updated Successfully.'); 
        Session::flash('alert-class', 'alert-success'); 
        return Redirect::to('ro/pollBoothLatLong');
    }
	
	public function addPollingstaffexcel(Request $request) 
	{
        $this->validate($request, [
            'addPollingstaff' => 'required|mimes:csv,txt'
            //'addPollingstaff.required' => 'Er, you forgot your email address!',
        ]);
		$file = Input::file('addPollingstaff');
		$extension = $file->getClientOriginalExtension();
		// if($extension!="csv")
		// {
		// 	$this->validate($request, [
		// 		'addPollingstaff' => 'required|in:csv'
		// 		//'addPollingstaff.required' => 'Er, you forgot your email address!',
		// 	]);
		// }

        Excel::load(Input::file('addPollingstaff'), function ($reader) 
		{ 
			$results = $reader->all();
            foreach ($reader->toArray() as $row) 
			{
				$user = Auth::user();
				$details=json_decode($user);
				$userid=$details->id;
				$dist_code=$details->dist_code;
				$cons_code=$details->cons_code;
				$state_id=$details->state_id;;
				//$state_id=$user->state_id;
                $emp_id=trim($row['emp_id']);
                $ref_no=trim($row['ref_no']); 
                $name=strtoupper($row['name']);
                $designation=strtoupper($row['designation']);
				$department=strtoupper($row['department']);
				$mobile=trim($row['mobile']);
				$mobile_get="";
				$users = DB::table('users_pollday')->where('phone', $mobile)->get();
				$count = count($users);
                $class=trim(strtoupper($row['class_pro_apro_blo_or_po']));
				//die();
				if($class=="PO")
				{
					$class1="POO";
					$uid=$class1.$mobile;
				}
				elseif($class=="APRO")
				{
					$class1="APR";
					$uid=$class1.$mobile;
				}
				else
				{
					$uid=$class.$mobile;
				}
				
				date_default_timezone_set('Asia/Calcutta'); 
				$date = date("Y-m-d");
				$otp_time = date('Y-m-d h:i:s ');
				if($class=="CEO")
				{
					$role="2";
				}
				elseif($class=="DEO")
				{
					$role="3";
				}
				elseif($class=="ROR")
				{
					$role="4";
				}
				elseif($class=="SUP")
				{
					$role="5";
				}
				elseif($class=="PRO")
				{
					$role="6";
				}
				elseif($class=="BLO")
				{
					$role="7";
				}
				elseif($class=="ECI")
				{
					$role="1";
				}
				elseif($class=="CND")
				{
					$role="15";
				}
				elseif($class=="PSH")
				{
					$role="8";
				}
				elseif($class=="PDH")
				{
					$role="9";
				}
				elseif($class=="PO")
				{
					$role="10";
				}
				elseif($class=="APRO")
				{
					$role="11";
				}
				else
				{
					$role="";
				}
				
                if($count==0)
				{
					$addPolluser = array(
						'emp_id' => $emp_id,
						'ref_no' => $ref_no,
						'data_entry_date' => $date,
						'otp_time' => $otp_time,
						'reset_time' => $otp_time,
						'uid' => $uid,
						'elect_duty' => $class,
						'phone' => $mobile,
						'name' => $name,
						'designation' => $designation,
						'department' => $department,
						'state_id' => $state_id,
						'dist_code' => $dist_code,
						'cons_code' => $cons_code,
						'role' => $role,
						'mobile_otp' => "",
						'password' => "",
						'reset_otp' => ""
					);
					$addPollUser = DB::table('users_pollday')->insert($addPolluser); 
				}
            }
        });
        Session::flash('addPollinguser', 'Polling user imported successfully.'); 
        return Redirect::to('ro/polling-staff');
    }
	
	public function addPollingstaffexcel2(Request $request) 
	{
        $this->validate($request, [
            'addPollingstaffexcel2' => 'required|mimes:csv,txt'
        ]);
		$file = Input::file('addPollingstaffexcel2');
		$extension = $file->getClientOriginalExtension();
		// if($extension!="csv")
		// {
		// 	$this->validate($request, [
		// 		'addPollingstaffexcel2' => 'required|mimes:csv,txt'
		// 	]);
		// }
		
        Excel::load(Input::file('addPollingstaffexcel2'), function ($reader) 
		{ 
			$results = $reader->all();
            foreach ($reader->toArray() as $row) 
			{
				$user = Auth::user();
				$details=json_decode($user);
				$userid=$details->id;
				$dist_code=$details->dist_code;
				$cons_code=$details->cons_code;
				
				//$state_id="53";
				$state_id=$details->state_id;
                $emp_id=trim($row['emp_id']);
				$party_no=trim($row['party_no']);
                $ref_no=trim($row['ref_no']); 
                $name=strtoupper($row['name']);
                $designation=strtoupper($row['designation']);
				$department=strtoupper($row['department']);
				$mobile=trim($row['mobile']);
				$mobile_get="";
				$users = DB::table('users_pollday')->where('phone', $mobile)->get();
				$count = count($users);
                $class=trim(strtoupper($row['class_pro_apro_blo_or_po']));
				if($class=="PO")
				{
					$class1="POO";
					$uid=$class1.$mobile;
				}
				elseif($class=="APRO")
				{
					$class1="APR";
					$uid=$class1.$mobile;
				}
				else
				{
					$uid=$class.$mobile;
				}
				
				date_default_timezone_set('Asia/Calcutta'); 
				$date = date("Y-m-d");
				$otp_time = date('Y-m-d h:i:s ');
				if($class=="CEO")
				{
					$role="2";
				}
				elseif($class=="DEO")
				{
					$role="3";
				}
				elseif($class=="ROR")
				{
					$role="4";
				}
				elseif($class=="SUP")
				{
					$role="5";
				}
				elseif($class=="PRO")
				{
					$role="6";
				}
				elseif($class=="BLO")
				{
					$role="7";
				}
				elseif($class=="ECI")
				{
					$role="1";
				}
				elseif($class=="CND")
				{
					$role="15";
				}
				elseif($class=="PSH")
				{
					$role="8";
				}
				elseif($class=="PDH")
				{
					$role="9";
				}
				elseif($class=="PO")
				{
					$role="10";
				}
				elseif($class=="APRO")
				{
					$role="11";
				}
				else
				{
					$role="";
				}
				
				$users_count = DB::table('users_pollday')->where('emp_id', $emp_id)->where('ref_no', $ref_no)->where('phone', $mobile)->get();
				$count_dup = count($users_count);

				if($count_dup==1)
				{
					$addPolluser = array(
						'emp_id' => $emp_id,
						'ref_no' => $ref_no,
						'data_entry_date' => $date,
						'otp_time' => $otp_time,
						'reset_time' => $otp_time,
						//'uid' => $uid,
						'elect_duty' => $class,
						'phone' => $mobile,
						'name' => $name,
						'designation' => $designation,
						'department' => $department,
						'state_id' => $state_id,
						'dist_code' => $dist_code,
						'cons_code' => $cons_code,
						'role' => $role,
						'mobile_otp' => "",
						'password' => "",
						'reset_otp' => ""
					);
					$addPollUser = DB::table('users_pollday')->where('emp_id', $emp_id)->where('ref_no', $ref_no)->where('phone', $mobile)->update($addPolluser);
				}
				else
				{
					if($count==0)
					{
						$addPolluser = array(
							'emp_id' => $emp_id,
							'ref_no' => $ref_no,
							'data_entry_date' => $date,
							'otp_time' => $otp_time,
							'reset_time' => $otp_time,
							'uid' => $uid,
							'elect_duty' => $class,
							'phone' => $mobile,
							'name' => $name,
							'designation' => $designation,
							'department' => $department,
							'state_id' => $state_id,
							'dist_code' => $dist_code,
							'cons_code' => $cons_code,
							'role' => $role,
							'mobile_otp' => "",
							'password' => "",
							'reset_otp' => ""
						);
						
						$addPollUser = DB::table('users_pollday')->insert($addPolluser);
						$addrandomization_staff_second = array(
							'emp_id' => $emp_id,
							'ref_no' => $ref_no,
							'uid' => $uid,
							'party_no' => $party_no,
							'cons_code' => $cons_code,
							'dist_code' => $dist_code,
							'state_id' => $state_id
						);
						$addrandomization_staff_second = DB::table('randomization_staff_second')->insert($addrandomization_staff_second);
					}
				}
            }
        });
        Session::flash('addPollinguser', 'Polling user imported successfully.'); 
        return Redirect::to('ro/polling-staff');
    }
	
	public function addPollingstaffexcel3(Request $request) 
	{	
        $this->validate($request, [
            'addPollingstaffexcel3' => 'required|mimes:csv,txt'
        ]);
		$file = Input::file('addPollingstaffexcel3');
		$extension = $file->getClientOriginalExtension();
		// if($extension!="csv")
		// {
		// 	$this->validate($request, [
		// 		'addPollingstaffexcel3' => 'required|in:csv'
		// 	]);
		// }
        Excel::load(Input::file('addPollingstaffexcel3'), function ($reader) 
		{ 
			$results = $reader->all();
            foreach ($reader->toArray() as $row) 
			{
				//$emp_id=trim($row['emp_id']);
				$party_no=trim($row['party_no']);
				$polling_station_no=trim($row['polling_station_no']);
				$name_of_polling_station=$row['name_of_polling_station'];
				$user = Auth::user();
				$details=json_decode($user);
				$userid=$details->id;
				$dist_code=$details->dist_code;
				$cons_code=$details->cons_code;
				$state_id=$details->state_id;
				$dist_code1=str_pad($dist_code, 3, '0', STR_PAD_LEFT);
				$cons_code1=str_pad($cons_code, 3, '0', STR_PAD_LEFT);
				$polling_station_no1=str_pad($polling_station_no, 3, '0', STR_PAD_LEFT);
				$bid=$dist_code.$cons_code1.$polling_station_no1;
				$users_count = DB::table('randomization_staff_second')->where('cons_code', $cons_code)->where('dist_code', $dist_code)->where('party_no', $party_no)->first();
				$count_dup = count($users_count);
				$uid=$users_count->uid;
				//die();
				$users_third = DB::table('randomization_staff_third')->where('uid', $uid)->where('bid', $bid)->first();
				$count_third = count($users_third);
				
				if($count_dup==1)
				{
					if($count_third==0)
					{
						$addrandomization_staff_third = array(
							'party_no' => $party_no,
							'uid' => $uid,
							'bid' => $bid,
							'polling_station' => $name_of_polling_station,
						);
						
						$addrandomization_staff_third = DB::table('randomization_staff_third')->insert($addrandomization_staff_third);
					}
				}
			}
        });
        Session::flash('addPollinguser', 'Polling user imported successfully.'); 
        return Redirect::to('ro/polling-staff');
    }

    public function poll1day() {
        $user = Auth::user();
        $polling_stations = DB::table('poll_booths')
                            ->join('constituencies','constituencies.cons_code','=','poll_booths.cons_code')
                            ->leftjoin('pro_activity_before', 'poll_booths.bid', '=', 'pro_activity_before.bid')
                            ->where('poll_booths.dist_code', $user->dist_code)
                            ->where('poll_booths.cons_code', $user->cons_code)
                            ->select('constituencies.cons_name','poll_booths.poll_building', 'pro_activity_before.*')
                            ->get();
        return view('ro/poll-1day', [
           'polling_stations' => $polling_stations,
       ]);
    }

	public function postalBallot() {
        $user = Auth::user();
        $distCode=$user->dist_code;
        $consCode=$user->cons_code;
        $stateID=$user->state_id;

        $postBallot = DB::table('voters_ballot')
                        ->where('state_id', $stateID)
                        ->where('dist_code', $distCode)
                        ->where('cons_code', $consCode)
                        ->first();
        return view('ro/postal-ballot', [
           'postBallot' => $postBallot,
        ]);
    }


    public function addPostalBallot(Request $request) {
        $user = Auth::user();
        $distCode=$user->dist_code;
        $consCode=$user->cons_code;
        $stateID=$user->state_id;
        $this->validate(
        $request, [
            'armyMaleVoter' => 'required|numeric',
            'armyFemaleVoter' => 'required|numeric',
            'edcMaleVoter' => 'required|numeric',
            'edcFemaleVoter' => 'required|numeric',
        ],
        [
            'armyMaleVoter.required' => 'This field is required',
            'armyFemaleVoter.required' => 'This field is required',
            'edcMaleVoter.required' => 'This field is required',
            'edcFemaleVoter.required' => 'This field is required',
            'armyMaleVoter.numeric' => 'Please add numeric value',
            'armyFemaleVoter.numeric' => 'Please add numeric value',
            'edcMaleVoter.numeric' => 'Please add numeric value',
            'edcFemaleVoter.numeric' => 'Please add numeric value',
        ]
        );
        $armyMale=$request->armyMaleVoter;
        $armyFemale=$request->armyFemaleVoter;
        $edcMale=$request->edcMaleVoter;
        $edcFemale=$request->edcFemaleVoter;
        $dt = Carbon::now();
        $timestamp=$dt->toDateString();
        $checkBallot = DB::table('voters_ballot')
                     ->where('state_id', $stateID)
                     ->where('dist_code', $distCode)
                     ->where('cons_code', $consCode)
                     ->first();

        $addBallot = array(
            'state_id' => $stateID,
            'dist_code' => $distCode,
            'cons_code' => $consCode,
            'army_voters_male' => $armyMale,
            'army_voters_female' => $armyFemale,
            'edc_voters_male' => $edcMale,
            'edc_voters_female' => $edcFemale,
            'updated_at' => $timestamp,
        );  
        $ballotInsert = DB::table('voters_ballot')->insert($addBallot);
        if($ballotInsert>0){
            Session::flash('postBallotSuccess', 'Postal Ballot Added Successfully'); 
            Session::flash('alert-class', 'alert-success');
            return Redirect::to('ro/postal-ballot'); 
        }else{
            Session::flash('postBallotSuccess', 'Please try again'); 
            Session::flash('alert-class', 'alert-danger');
            return Redirect::to('ro/postal-ballot');
        }
    }

    
    public function editPostBallot($encBalId){
        $user = Auth::user();
        $distCode=$user->dist_code;
        $consCode=$user->cons_code;
        $stateID=$user->state_id;
        $ballotId=eci_decrypt($encBalId);
        $postBallotEdit = DB::table('voters_ballot')
                        ->where('state_id', $stateID)
                        ->where('dist_code', $distCode)
                        ->where('cons_code', $consCode)
                        ->where('ballot_id', $ballotId)
                        ->first();
        
        return view('ro/edit-postal-ballot', [
           'postBallotEdit' => $postBallotEdit,
           'encBalId' => $encBalId,
        ]);
    }


    public function updatePostalBallot(Request $request) {
        $user = Auth::user();
        $distCode=$user->dist_code;
        $consCode=$user->cons_code;
        $stateID=$user->state_id;
        $this->validate(
        $request, [
            'armyMaleVoterEdit' => 'required|numeric',
            'armyFemaleVoterEdit' => 'required|numeric',
            'edcMaleVoterEdit' => 'required|numeric',
            'edcFemaleVoterEdit' => 'required|numeric',
        ],
        [
            'armyMaleVoterEdit.required' => 'This field is required',
            'armyFemaleVoterEdit.required' => 'This field is required',
            'edcMaleVoterEdit.required' => 'This field is required',
            'edcFemaleVoterEdit.required' => 'This field is required',
            'armyMaleVoterEdit.numeric' => 'Please add numeric value',
            'armyFemaleVoterEdit.numeric' => 'Please add numeric value',
            'edcMaleVoterEdit.numeric' => 'Please add numeric value',
            'edcFemaleVoterEdit.numeric' => 'Please add numeric value',
        ]
        );
        $armyMaleEdit=$request->armyMaleVoterEdit;
        $armyFemaleEdit=$request->armyFemaleVoterEdit;
        $edcMaleEdit=$request->edcMaleVoterEdit;
        $edcFemaleEdit=$request->edcFemaleVoterEdit;
        $ballotId=eci_decrypt($request->ballotId);
        $upPollBallot = array(
            'army_voters_male' => $armyMaleEdit,
            'army_voters_female' => $armyFemaleEdit,
            'edc_voters_male' => $edcMaleEdit,
            'edc_voters_female' => $edcFemaleEdit,
        );  
        $upPollBallots = DB::table('voters_ballot')
                      ->where('state_id', $stateID)
                      ->where('dist_code', $distCode)
                      ->where('cons_code', $consCode)
                      ->where('ballot_id', $ballotId)
                      ->update($upPollBallot);
        if($upPollBallots!==""){
            Session::flash('postBallotSuccess', 'Postal Ballot Updated Successfully'); 
            Session::flash('alert-class', 'alert-success');
            return Redirect::to('ro/postal-ballot');
        }else{
            Session::flash('postBallotFail', 'Please try again'); 
            Session::flash('alert-class', 'alert-danger');
            return Redirect::to('ro/editPostBallot/'.$request->ballotId);
        }
    }


	public function generateVoterSlip()
    {
        $user = Auth::user();
        return view('ro/generate-voter-slip');
    }

    public function updatePollType(Request $request) {
        $user = Auth::user();
        $post = $request->all();
        $pollId=eci_decrypt($post['pollIdAjax']);
        $pollType=$post['typeAjax'];
        $upPollBooth = array(
            'poll_type' => $pollType,  
        );  
        $upPollBooths = DB::table('poll_booths')
                    ->where('poll_booth_id', $pollId)
                    ->update($upPollBooth);

        if($request->ajax()){
            if($upPollBooths!==""){
                $updatePoll[] = array(
                  'upPollStatus'  => 1,
                );
                return response()->json([
                    'updatePoll' => $updatePoll,
                ]);
            }
            else{
                $updatePoll[] = array(
                              'upPollStatus'  => 0,
                            );
                return response()->json([
                    'updatePoll' => $updatePoll,
                ]);
            }
        }  
    }
	
    public function complaint(){
        $user = Auth::user();
        $coms = array();
        $complaints = get_complaints($user->state_id, $user->dist_code,$user->cons_code);
        if(@$complaints){
    		$i = 0;
    		$nature = get_com_nature();
            foreach ($complaints as $value) {

    			if(($value->InformationType_ID == "1") &&( $value->CTBLBTypeID =="3")){
    				if($value->ComplainTypeID == 1){
    					$coms[$i]['type'] = "Poll Related";
    					//$nature = get_com_nature($value->ComplainTypeID);
    				}elseif($value->ComplainTypeID == 2){
    					$coms[$i]['type'] = "Voters' List/Voters' Card Related";	
    				}
    				if(@$nature){
    					foreach($nature as $val){
    						if($val['id'] == $value->ComplaiNature_ID){
    							$coms[$i]['nature'] = $val['name'];	

                                // echo $val['name'];
                                // echo "<br>";
    						}
    					}
    				}
    				$coms[$i]['comdate'] = date("d F, Y",strtotime($value->EntryDate));
    				$coms[$i]['status'] = $value->Status;
    				$coms[$i]['complainno'] = $value->ComplainNo;
          			//$coms[$i] = $value;    
            		$i++;
    			}
    		}
        }else{
          $coms = array();
        }
      
        return view('ro/complaint', [
            'complaints' => $coms,
        ]);
	}

	public function information(){
    	$coms = array();
        $user = Auth::user();
        $complaints = get_complaints($user->state_id, $user->dist_code,$user->cons_code);
        if(@$complaints){
    		$i = 0;
    		$nature = get_com_nature();
            foreach ($complaints as $value) {
    			if($value->InformationType_ID == "2"){
    				if($value->ComplainTypeID == 1){
    					$coms[$i]['type'] = "Poll Related";
    					//$nature = get_com_nature($value->ComplainTypeID);
    				}elseif($value->ComplainTypeID == 2){
    					$coms[$i]['type'] = "Voters' List/Voters' Card Related";	
    				}
    				if(@$nature){
    					foreach($nature as $val){
    						if($val['id'] == $value->ComplaiNature_ID){
    							$coms[$i]['nature'] = $val['name'];	
    						}
    					}
    				}
    				$coms[$i]['comdate'] = date("d F, Y",strtotime($value->EntryDate));
    				$coms[$i]['status'] = $value->Status;
    				$coms[$i]['complainno'] = $value->ComplainNo;
          			//$coms[$i] = $value;    
            		$i++;
    			}
    		}
        }
        else {
          $coms = array();
        }
        //$complaints = json_decode($complaints);
        //dd($coms);
        return view('ro/information', [
           'complaints' => $coms,
        ]);
	}

	
	public function suggestion(){
        $user = Auth::user();
    	$coms = array();
        $complaints = get_complaints($user->state_id, $user->dist_code,$user->cons_code);
        if(@$complaints){
    		$i = 0;
    		$nature = get_com_nature();
    		foreach ($complaints as $value) {
    			if($value->InformationType_ID == "3"){
    				if($value->ComplainTypeID == 1){
    					$coms[$i]['type'] = "Poll Related";
    					//$nature = get_com_nature($value->ComplainTypeID);
    				}elseif($value->ComplainTypeID == 2){
    					$coms[$i]['type'] = "Voters' List/Voters' Card Related";
    				}
    				if(@$nature){
    					foreach($nature as $val){
    						if($val['id'] == $value->ComplaiNature_ID){
    							$coms[$i]['nature'] = $val['name'];	
    						}	
    					}
    				}
    				$coms[$i]['comdate'] = date("d F, Y",strtotime($value->EntryDate));
    				$coms[$i]['status'] = $value->Status;
    				$coms[$i]['complainno'] = $value->ComplainNo;
          			//$coms[$i] = $value;    
            		$i++;
    			}
    		}
        }else{
          $coms = array();
        }
        return view('ro/suggestion', [
           'complaints' => $coms,
        ]);
	}
	
	public function complaintDetail($id){
		$user = Auth::user();
		$details = array();
		$complaints = get_complaints($user->state_id, $user->dist_code,$user->cons_code);
		if(@$complaints){
			$i = 0;
			$nature = get_com_nature();
			$parties = get_party_list();
			foreach ($complaints as $value) {
				if($value->ComplainNo == $id){
					$details['ComplainNo'] = $value->ComplainNo;
					if($value->ComplainantTypeID == 1){
						$details['ComplainantType'] = "Political Party";
						foreach($parties as $pp){
							if($pp['id'] == $value->PartyIDOfComplainent){
								$details['PartyComplainent'] = $pp['name'];
							}
						}						
					}elseif($value->ComplainantTypeID == 2){
						$details['ComplainantType'] = "Citizen";
					}elseif($value->ComplainantTypeID == 3){
						$details['ComplainantType'] = "Contesting Candidate";
					}elseif($value->ComplainantTypeID == 4){
						$details['ComplainantType'] = "Other";
					}
					$details['NameOfComplainent'] = $value->NameOfComplainent;
					$details['MobilOfCompalinent'] = $value->MobilOfCompalinent;
					if(@$details['EmailIDOfComplainent']){
						$details['EmailIDOfComplainent'] = $value->EmailIDOfComplainent;
					}
					if(@$details['AddressOfComplainent']){
						$details['AddressOfComplainent'] = $value->AddressOfComplainent;
					}
					
					if($value->ComplainTypeID == 1){
						$details['type'] = "Poll Related";
						//$nature = get_com_nature($value->ComplainTypeID);
					}elseif($value->ComplainTypeID == 2){
						$details['type'] = "Voters' List/Voters' Card Related";	
					}
					if(@$nature){
						foreach($nature as $val){
							if($val['id'] == $value->ComplaiNature_ID){
								$details['nature'] = $val['name'];	
							}
						}
					}
					$details['ComplainDescription'] = $value->ComplainDescription;
					$details['cdate'] = date("d F, Y",strtotime($value->EntryDate));
					$details['status'] = $value->Status;
					if(@$value->ComplainAgainstName){
						$details['ComplainAgainstName'] = $value->ComplainAgainstName;
					}	
				}
			}
		}else{
			$details = array();
		}
		return view('ro/complaint-detail', [
           'details' => $details,
        ]);
	}

    public function suvidha(){
        $user = Auth::user();
        $state_id = $user->state_id;
        $dist_code = $user->dist_code;
        $cons_code = $user->cons_code;
        $getdata = get_suvidha_data($state_id,$dist_code,$cons_code);
        $getparty = get_party_list();
        return view('ro/suvidha', [
           'getdata' => $getdata,
           'getparty' => $getparty,
       ]);
    }

    public function suvidhaDetail($sid){
        $id=eci_decrypt($sid);
        $getdata = get_suvidha_detail($id);
        return view('ro/suvidha-detail', [
           'getdata' => $getdata,
       ]);
    }



    public function dispatchCollectionCenter() {
        $user = Auth::user();
        $dist_code = $user->dist_code;
        $cons_code = $user->cons_code;
        $consTypeEnc=eci_encrypt("DISPATCH");
        $centerDetail = DB::table('dispatch_collection_center')
                        ->where('dist_code', $dist_code)
                        ->where('cons_code', $cons_code)
                        ->first();

        return view('ro/dispatch-collection-center', [
           'centerDetail' => $centerDetail,
           'consTypeEnc' => $consTypeEnc,
        ]);
    }


    public function dispatchCollectionCenterSub(Request $request) {
        $user = Auth::user();
        $this->validate(
        $request, [
            'centerType' => 'required',
        ],
        [
            'centerType.required' => 'This field is required',
        ]
        );
        $dist_code = $user->dist_code;
        $cons_code = $user->cons_code;
        $consTypeEnc=$request->centerType;
        $centerDetail = DB::table('dispatch_collection_center')
                        ->where('dist_code', $dist_code)
                        ->where('cons_code', $cons_code)
                        ->first();

        return view('ro/dispatch-collection-center', [
           'centerDetail' => $centerDetail,
           'consTypeEnc' => $consTypeEnc,
        ]);
    }


    public function lawOrder()
    {
        $user = Auth::user();
        $state_id =  $user->state_id;
        $dist_code =  $user->dist_code;
        $cons_code = $user->cons_code;

        $laworderlist = DB::table('pro_law_order')
                      ->join('poll_booths','poll_booths.bid','pro_law_order.bid')
                        ->join('users','poll_booths.supervisior_uid','users.uid')
                      
                      ->join('users_pollday', 'pro_law_order.uid','users_pollday.uid')
                      ->select('poll_booths.bid','poll_booths.poll_building','poll_booths.poll_building_detail','poll_booths.ps_id','users_pollday.name as pro_name', 'users_pollday.phone as pro_number', 'users.name as sup_name', 'users.phone as sup_num', 'pro_law_order.comment', 'pro_law_order.action_from', 'pro_law_order.action_to')
                      ->where('pro_law_order.cons_code', $cons_code)
                      ->get();
                      //dd($laworderlist);
        return view('ro/law-order', [
           'laworderlist' => $laworderlist,
        ]);
        //return view('ro/law-order');
    }
	
	// public function voterSlipData(){
 //        return view('ro/voter-slip-data');
 //    }

    
    public function evmMalfunction(){
        $user = Auth::user();
        $state_id = $user->state_id;
        $dist_code = $user->dist_code;
        $cons_code = $user->cons_code;
        
        $mallfunctions = DB::table('poll_booths')
                      ->join('pro_evm_malfunctioning','poll_booths.bid', 'pro_evm_malfunctioning.bid')
                      ->join('users_pollday','pro_evm_malfunctioning.uid', 'users_pollday.uid')
                      ->where('pro_evm_malfunctioning.state_id', $state_id)
                      ->where('pro_evm_malfunctioning.dist_code', $dist_code)
                      ->where('pro_evm_malfunctioning.cons_code', $cons_code)
                      ->where('pro_evm_malfunctioning.status', 0)
                      ->get();

        $mallfunctions_resolve = DB::table('poll_booths')
                               ->join('pro_evm_malfunctioning','poll_booths.bid', 'pro_evm_malfunctioning.bid')
                               ->join('users_pollday','pro_evm_malfunctioning.uid', 'users_pollday.uid')
                               ->select('poll_booths.bid','poll_booths.poll_building','poll_booths.poll_building_detail','poll_booths.ps_id','users_pollday.name','pro_evm_malfunctioning.id','pro_evm_malfunctioning.reply')
                               ->where('pro_evm_malfunctioning.state_id', $state_id)
                               ->where('pro_evm_malfunctioning.dist_code', $dist_code)
                               ->where('pro_evm_malfunctioning.cons_code', $cons_code)
                               ->where('pro_evm_malfunctioning.status', 1)
                               ->get();

        return view('ro/evm-malfunction', [
           'mallfunctions' => $mallfunctions,
           'mallfunctions_resolve' => $mallfunctions_resolve,
        ]);  
    }
	
	public function voterSlipData(){
        $user = Auth::user();
	    $state_id = $user->state_id;
	    $dist_code = $user->dist_code;
	    $cons_code = $user->cons_code;
		
		$voterslipData = DB::table('ro_voter_slips_data')
                                    ->where('state_id', $state_id)
									->where('dist_code', $dist_code)
									->where('cons_code', $cons_code)
                                    ->get();
									
		return view('ro/voter-slip-data', [
		  'voterslipData' => $voterslipData,
		]);
    }
	
	
	public function addvoterSlipData(Request $request) {
      $user = Auth::user();
	  $state_id = $user->state_id;
	  $dist_code = $user->dist_code;
	  $cons_code = $user->cons_code;
		
      $this->validate(
      $request, [
        'date1' => 'required',
        'total_voter_slip1' => 'required',
        'slip_distributed1' => 'required',
        'slip_pending1' => 'required',
		// 'date2' => 'required',
  //       'total_voter_slip2' => 'required',
  //       'slip_distributed2' => 'required',
  //       'slip_pending2' => 'required',
		// 'date3' => 'required',
  //       'total_voter_slip3' => 'required',
  //       'slip_distributed3' => 'required',
  //       'slip_pending3' => 'required',
      ],
      [
        'date1.required' => 'Please select date',
        'total_voter_slip1.required' => 'Please enter value for total voter slips',
        'slip_distributed1.required' => 'Please enter value for number of slips distributed',
        'slip_pending1.required' => 'Please enter value for number of slips pending',
		'date2.required' => 'Please select date',
        'total_voter_slip2.required' => 'Please enter value for total voter slips',
        'slip_distributed2.required' => 'Please enter value for number of slips distributed',
        'slip_pending2.required' => 'Please enter value for number of slips pending',
		'date3.required' => 'Please select date',
        'total_voter_slip3.required' => 'Please enter value for total voter slips',
        'slip_distributed3.required' => 'Please enter value for number of slips distributed',
        'slip_pending3.required' => 'Please enter value for number of slips pending',
      ]
      );
      $uidDeo=Auth::user()->uid;
      $distCodeDeo = Auth::user()->dist_code;
      $statIdDeo = Auth::user()->state_id;

      $addVoterSlipData = array(
		'state_id' => $state_id,
		'dist_code' => $dist_code,
		'cons_code' => $cons_code,
        'date1' => $request->date1,
        'total_voter_slip1' => $request->total_voter_slip1,
        'slip_distributed1' => $request->slip_distributed1,
        'slip_pending1' => $request->slip_pending1,
        'date2' => $request->date2,
        'total_voter_slip2' => $request->total_voter_slip2,
		'slip_distributed2' => $request->slip_distributed2,
		'slip_pending2' => $request->slip_pending2,
        'date3' => $request->date3,
        'total_voter_slip3' => $request->total_voter_slip3,
        'slip_distributed3' => $request->slip_distributed3,
		'slip_pending3' => $request->slip_pending3,
		'updated_at' => date("Y-m-d h:i:s"),
      );  
	  
	  $countVoterSlipData = DB::table('ro_voter_slips_data')
                ->where('state_id', $state_id)
                ->where('dist_code', $dist_code)
				->where('cons_code', $cons_code)
                ->first();
	  $countVoterSlipData = count($countVoterSlipData);
	  if($countVoterSlipData>0)
	  {
		$updateVoterSlipData = DB::table('ro_voter_slips_data')
                  ->where('state_id', $state_id)
                  ->where('dist_code', $dist_code)
				  ->where('cons_code', $cons_code)
                  ->update($addVoterSlipData);
	  }
	  else
	  {
		$addVoterSlipData = DB::table('ro_voter_slips_data')->insert($addVoterSlipData); 
	  }
	  Session::flash('VoterdataSucc', 'Voter slip data added successfully.'); 
	  return Redirect::to('ro/voter-slip-data');
      
    }
	
	public function pwdVoters(){
        $user = Auth::user();
	    $state_id = $user->state_id;
	    $dist_code = $user->dist_code;
	    $cons_code = $user->cons_code;
        $getPwd=getPwdVoter($cons_code);
        $getPwdVoter  = json_decode($getPwd);
        return view('ro/pwd-voters', [
          'getPwdVoter' => $getPwdVoter,
        ]);
	}
	
	public function facilities($bid){
        $user = Auth::user();
        $bid=eci_encrypt("bid");
        $polling_facility = DB::table('poll_booths_web')
                           ->where('bid', $bid)
                           ->first();
                          // dd($polling_facility);
        return view('ro/facilities', [
           'polling_facility' => $polling_facility,
        ]);
    }
	
	public function watchLive(){
      return view('ro/watch-live');
    }
    
}


