<?php
namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use DB;
use Config;


class EciController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {   

        $this->middleware('auth');
        $this->middleware('eci');
        
        // if(Session::get('state_id') == "53"){
        //     $d = "eci";
        // }else{
        //     $d = "eci_".Session::get('state_id');
        // }
        // dd(Session::get('state_id'));
        // Config::set("database.connections.mysql", [
        //     "host" => "localhost",
        //     "database" => $d,
        //     "username" => "root",
        //     "password" => "01Synergy!@#",
        //     "driver"   =>'mysql',
        //     'charset' => 'utf8',
        //     'collation' => 'utf8_unicode_ci',
        //     'prefix' => '',
        //     'strict' => true,
        //     'engine' => null,
        // ]);
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

    public function beforeeci(){
        //dd(Session::get('state_id'));
        if(Session::get('state_id') == "53"){
                $d = "eci";
            }else{
                $d = "eci_".Session::get('state_id');
            }
        $state_id = Session::get('state_id');
        //dd($state_id);
        if(@$state_id){
            return $state_id;
        }else{
            return Redirect::to('/select_state');
        }
        
        //$this->state_id = $state_id;
            
            
    }

    public function dashboard(){
        //dd(Session::get('state_id'));    
        $state_id =  get_state_id();
        
        $user = Auth::user();
        
        
        // Ceo List
        $ceolist = DB::table('users')
                      ->join('states','states.StateID','=','users.state_id')
                      ->where('role', '2')
                      ->select('users.name','states.StateName as state_name','users.phone')
                      ->orderby('states.StateName')
                      ->limit(5)
                      ->get();

        //EVM And VVPAT
        $first_evm = strtotime(Config::get('constants.FIRST_RANDOMIZATION_EVM_DATE'));
        $second_evm = strtotime(Config::get('constants.SECOND_RANDOMIZATION_EVM_DATE'));
        $today = time();

        if($second_evm <= $today){
            $evmlist = DB::table('constituencies')
                        ->join('districts','districts.dist_code','=','constituencies.dist_code')
                        ->whereNotIn('constituencies.cons_code', function($query) use ($state_id)
                        {
                            $query->select('cons_code')
                                  ->from('randomization_evm_second')
                                  ->where('state_id','=',$state_id)
                                  ->groupBy('cons_code');
                        })
                        ->select('constituencies.cons_name','districts.dist_name')
                        ->where('constituencies.state_id','=',$state_id)
                        ->limit(5)
                        ->get(); 
        }elseif($first_evm <= $today){
            $evmlist = DB::table('constituencies')
                        ->join('districts','districts.dist_code','=','constituencies.dist_code')
                        ->whereNotIn('constituencies.cons_code', function($query) use($state_id)
                        {
                            $query->select('cons_code')
                                  ->from('randomization_evm_first')
                                  ->where('randomization_evm_first.state_id','=',$state_id)
                                  ->groupBy('randomization_evm_first.cons_code');
                        })
                        ->select('constituencies.cons_name','districts.dist_name')
                        ->where('constituencies.state_id','=',$state_id)
                        ->limit(5)
                        ->get();   
        }else{
            $evmlist = array();
        }

        //Polling Staff
        $first_staff = strtotime(Config::get('constants.FIRST_RANDOMIZATION_STAFF_DATE'));
        $second_staff = strtotime(Config::get('constants.SECOND_RANDOMIZATION_STAFF_DATE'));
        $third_staff = strtotime(Config::get('constants.THIRD_RANDOMIZATION_STAFF_DATE'));
        $today = time();
        if($third_staff <= $today){
            
            $staff = DB::table('constituencies')
                        ->join('districts','districts.dist_code','=','constituencies.dist_code')
                        ->whereNotIn('constituencies.cons_code', function($query) use($state_id)
                        {
                            $query->select('cons_code')
                                  ->from('randomization_staff_third')
                                  ->where('state_id','=',$state_id)
                                  ->groupBy('cons_code');
                        })
                        ->select('constituencies.cons_name','districts.dist_name')
                        ->where('constituencies.state_id','=',$state_id)
                        ->limit(5)
                        ->get(); 
        }elseif($second_staff <= $today){
                $staff = DB::table('constituencies')
                        ->join('districts','districts.dist_code','=','constituencies.dist_code')
                        ->whereNotIn('constituencies.cons_code', function($query) use($state_id)
                        {
                            $query->select('cons_code')
                                  ->from('randomization_staff_second')
                                  ->where('state_id','=',$state_id)
                                  ->groupBy('cons_code');
                        })
                        ->select('constituencies.cons_name','districts.dist_name')
                        ->where('constituencies.state_id','=',$state_id)
                        ->limit(5)
                        ->get();  
        }elseif($first_staff <= $today){
              
               $staff = DB::table('constituencies')
                        ->join('districts','districts.dist_code','=','constituencies.dist_code')
                        ->whereNotIn('constituencies.cons_code', function($query) use($state_id)
                        {
                            $query->select('cons_code')
                                  ->from('users_pollday')
                                  ->where('state_id','=',$state_id)
                                  ->groupBy('cons_code');
                        })
                        ->select('constituencies.cons_name','districts.dist_name')
                        ->where('constituencies.state_id','=',$state_id)
                        ->limit(5)
                        ->get();     
        }else{
            $staff = array();
        }


        // Polling Station
        $pollstationlist = DB::table('districts')
                    ->leftjoin('poll_booths', 'districts.dist_code', '=', 'poll_booths.dist_code')
                    ->select('districts.dist_code', 'districts.dist_name',DB::raw('IFNULL(COUNT(poll_booths.poll_booth_id), 0) as total'))
                    ->where('districts.state_id','=',$state_id)
                    ->groupBy('districts.dist_code', 'districts.dist_name')
                    ->orderBy('districts.dist_code')
                    ->limit(5)                    
                    ->get();
       
        // Electrol Rolls
        $voterlist = DB::table('districts')
                    ->leftjoin('voters_count', 'districts.dist_code', '=', 'voters_count.dist_code')
                    ->select('districts.dist_code', 'districts.dist_name',DB::raw('IFNULL(SUM(voters_count.total_voters), 0) as total'))
                    ->where('districts.state_id','=',$state_id)
                    ->groupBy('districts.dist_code', 'districts.dist_name')
                    ->orderBy('districts.dist_code')
                    ->limit(5) 
                    ->get();


        return view('eci/dashboard', [
           'ceolist' => $ceolist,
           'evmlist' => $evmlist,
           'stafflist' => $staff,
           'pollstationlist' => $pollstationlist,
           'voterlist' => $voterlist,
        ]);
    }

    public function pagenotfound(){
        $users = Auth::user();
        return view('eci/pagenotfound');
    }

     public function distElectrolList(){
        $user = Auth::user();
        $state_id =  get_state_id();
        $voterlist = DB::table('districts')
                    ->leftjoin('voters_count', 'districts.dist_code', '=', 'voters_count.dist_code')
                    ->select('districts.dist_code', 'districts.dist_name',DB::raw('IFNULL(SUM(voters_count.total_voters), 0) as total'))
                    ->where('districts.state_id','=',$state_id)
                    ->groupBy('districts.dist_code', 'districts.dist_name')
                    ->orderBy('districts.dist_code')
                    ->get();
        return view('eci/dist-electrollist', [
        'voterlist' => $voterlist,
        ]);
    }

    public function consElectrolList($dist_code){
        $user = Auth::user();
        $dist_code = eci_decrypt($dist_code);
        $state_id =  get_state_id();
        $voterlist = DB::table('constituencies')
                    ->leftjoin('voters_count', 'constituencies.cons_code', '=', 'voters_count.cons_code')
                    ->select('constituencies.cons_code', 'constituencies.cons_name',DB::raw('IFNULL(SUM(voters_count.total_voters), 0) as total'))
                    ->where('constituencies.dist_code','=',$dist_code)
                    ->groupBy('constituencies.cons_code', 'constituencies.cons_name')
                    ->orderBy('constituencies.cons_code')
                     ->get();
       return view('eci/cons-electrollist', [
        'voterlist' => $voterlist,
        'dist_code' => $dist_code,
        ]);
    }

    public function psElectrolList($cons_code, Request $request){
        $user = Auth::user();
        $dist_code = eci_decrypt($request->dist_code);
        $cons_code = eci_decrypt($cons_code);
        $state_id =  $user->state_id;

        
        $psvoterlist = DB::table('poll_booths')
                    ->leftjoin('voters_count', function($join) { 
                                                $join->on('voters_count.cons_code', '=', 'poll_booths.cons_code')
                                                ->on('voters_count.ps_id', '=', 'poll_booths.ps_id')
                                                ->on('voters_count.state_id', '=', 'poll_booths.state_id')
                                                ->on('voters_count.dist_code', '=', 'poll_booths.dist_code'); 
                                            })

                    ->select('poll_booths.ps_id', 'poll_booths.poll_building','voters_count.total_voters as total')
                    ->orderBy('poll_booths.ps_id')
                    ->where('poll_booths.state_id',$state_id)
                    ->where('poll_booths.dist_code',$dist_code)
                    ->where('poll_booths.cons_code',$cons_code)
                    ->get();
        return view('eci/ps-electrollist', [
        'psvoterlist' => $psvoterlist,
        'state_id' => $state_id,
        'dist_code' => $dist_code,
        'cons_code' => $cons_code,
        ]);

    }

    public function electrolList($ps_id, Request $request){
        $user = Auth::user();
        $cons_code = eci_decrypt($request->cons_code);
        $dist_code = eci_decrypt($request->dist_code);
        $ps_id = eci_decrypt($ps_id);
        $state_id =  $user->state_id;
        $votersList = DB::table('voters')
                ->where('cons_code', $cons_code)
                ->where('state_id', $state_id)
                ->where('dist_code', $dist_code)
                ->where('ps_id', $ps_id)
                ->get();

        if($votersList->count()>=1){
        }else{
           $votersListAPI = app('App\Http\Controllers\CronjobController')->get_voter_list($state_id,$dist_code,$cons_code,$ps_id);
          //dd($votersListAPI);
          $votersList = json_decode($votersListAPI);
        }
        return view('eci/electrollist', [
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
            $pollImages = app('App\Http\Controllers\CronjobController')->get_poll_images($voterDetail->state_id, $voterDetail->dist_code, $voterDetail->cons_code, $voterDetail->ps_id);
           $pollDayDetail = json_decode($pollDayDetail);
        }else{
            $voterDetail = array();
            $pollImages = array();
        }
        //dd($voterDetail);
        return view('eci/voter-detail', [
           'voterDetail' => $voterDetail,
           'images' => $pollImages,
           'pollDayDetail' => $pollDayDetail,
        ]);
    }
	
    public function ceolist()
    {
       
        $user = Auth::user();
        $ceolist = DB::table('users')
                      ->join('states','states.StateID','=','users.state_id')
                      ->where('role', '2')
                      ->select('users.name', 'users.uid', 'states.StateName as state_name','users.phone')
                      ->orderby('states.StateName')
                      ->get();
        return view('eci/ceo_list', [
           'ceolist' => $ceolist,
        ]);
    }


    public function addCeo()
    {
        $user = Auth::user();
        $stateList = DB::table('states')->get();
        return view('eci/add_ceo', [
        'stateList' => $stateList,
        ]);
    }


    public function addceoSub(Request $request) {
        $user = Auth::user();
        $this->validate($request, [
            'ceoName' => 'required',
            'ceoState' => 'required',
            'ceoPhone' => 'required|size:10',
            //'ceoofficePhone' => 'required',
            'ceoEmail' => 'email',
            'ceoPW' => 'min:6',
        ]);
        $phoneCeo=$request->ceoPhone;
        $ceoofficePhone=$request->ceoofficePhone;
        $uidCeo="CEO".$phoneCeo;
        $stateCeo=$request->ceoState;
        $password=(isset($request['ceoPW']))? $request['ceoPW'] : "";

        $stateRepeat = DB::table('users')->where('state_id', $stateCeo)->where('role', 2)->first();
        if(!empty($stateRepeat)) {

            //--Genrate Password
            if($password!==""){
                $upPass=Hash::make($password);
            }
            else{
                if(($stateRepeat->password)==""){
                    $randPass=rand(10,1000).time();
                    $upPass=Hash::make($randPass);
                }
                else{
                    $upPass=$stateRepeat->password;
                }
            }
            $upCeo = array(
                'uid' => $uidCeo,
                'name' => $request->ceoName,
                'email' => $request->ceoEmail,
                'phone' => $phoneCeo,
                'office_phone' => $ceoofficePhone,
                'address' => $request->ceoAddress,
                'designation' => $request->ceoDesignation,
                'organisation' => $request->ceoOrganization,
                'password' => $upPass,
            );
            $updateCeo = DB::table('users')->where('state_id', $stateCeo)->where('role', 2)->update($upCeo);
            \Session::flash('addCeoSucc', 'CEO updated successfully. '); 
            return Redirect::to('eci/ceo_list');
        }
        else {
            //--Genrate Password
            if($password!==""){
                $addPass=Hash::make($password);
            }
            else{
                $randPass=rand(10,1000).time();
                $addPass=Hash::make($randPass);
            }
            $dt = Carbon::now();
            $timestamp=$dt->toDateString();
            $addCeo = array(
                'uid' => $uidCeo,
                'name' => $request->ceoName,
                'email' => $request->ceoEmail,
                'phone' => $phoneCeo,
                'office_phone' => $ceoofficePhone,
                'address' => $request->ceoAddress,
                'designation' => $request->ceoDesignation,
                'organisation' => $request->ceoOrganization,
                'role' => 2,
                'password' => $addPass,
                'state_id' => $stateCeo,
                'updated_at' => $timestamp,
            ); 
            $addNewCeo = DB::table('users')->insert($addCeo);
            \Session::flash('addCeoSucc', 'CEO added successfully. '); 
            return Redirect::to('eci/ceo_list');
        }
    }


    public function editCeo($uid)
    {
        $user = Auth::user();
        $uidDcrypt=eci_decrypt($uid);

        $getCeo = DB::table('users')
                 ->join('states','states.StateID','=','users.state_id')
                 ->where('uid', $uidDcrypt)
                 ->where('role', 2)
                 ->first();
        return view('eci/edit_ceo', [
            'getCeo' => $getCeo,
        ]);
    }


    public function editCeoSub(Request $request) {
        $user = Auth::user();
        $this->validate($request, [
            'ceoNameEdit' => 'required',
            'ceoStateEdit' => 'required',
            'ceoPhoneEdit' => 'required|size:10',
            //'ceoofficePhoneEdit' => 'required',
            'ceoEmailEdit' => 'email',
            'ceoPWEdit' => 'min:6',
        ]);

        $uidDcrypt=eci_decrypt($request->uidCeo);
        $passwordEdit=(isset($request['ceoPWEdit']))? $request['ceoPWEdit'] : "";
        if($passwordEdit!==""){
            $editPass=Hash::make($passwordEdit);
        }
        else{
            $editPass=$request->ceoPWOld;
        }

        $stateCeo=$request->ceoStateEdit;
        $phoneEdit=$request->ceoPhoneEdit;
        $uidEdit="CEO".$phoneEdit;

        $ceoofficePhoneEdit=$request->ceoofficePhoneEdit;
        
        $editCeo = array(
            'uid' => $uidEdit,
            'name' => $request->ceoNameEdit,
            'email' => $request->ceoEmailEdit,
            'phone' => $request->ceoPhoneEdit,
            'office_phone' => $ceoofficePhoneEdit,
            'address' => $request->ceoAddressEdit,
            'designation' => $request->ceoDesignationEdit,
            'organisation' => $request->ceoOrganizationEdit,
            'password' => $editPass,
        );
        $updateCEO = DB::table('users')->where('uid', $uidDcrypt)->where('state_id', $stateCeo)->where('role', 2)->update($editCeo);
        \Session::flash('addCeoSucc', 'CEO updated successfully. '); 
        return Redirect::to('eci/ceo_list');
    }
    

    public function deleteCeo($uid) {
        $user = Auth::user();
        $uidDcrypt=eci_decrypt($uid);

        $delCeo=DB::table('users')->where('uid', $uidDcrypt)->delete();
        if($delCeo!=="") {
            \Session::flash('addCeoSucc', 'CEO Deleted successfully. ');
            return Redirect::to('eci/ceo_list');
        }
        else{
            \Session::flash('addCeoErr', 'Please try again. ');
            return Redirect::to('eci/ceo_list');
        }
    }


	public function PoliticalPartiesStateHead()
    {
       
        $user = Auth::user();
        return view('eci/political-parties-state-head');
    }
	
	public function politicalPartiesDistrictHead()
    {
       
        $user = Auth::user();
        return view('eci/political-parties-district-head');
    }


	public function evmVvpatPending()
    { 
        $user = Auth::user();
        $state_id =  get_state_id();
        //EVM And VVPAT
        $first_evm = strtotime(Config::get('constants.FIRST_RANDOMIZATION_EVM_DATE'));
        $second_evm = strtotime(Config::get('constants.SECOND_RANDOMIZATION_EVM_DATE'));
        $today = time();

        if($second_evm <= $today){
            $evmlist = DB::table('constituencies')
                        ->join('districts','districts.dist_code','=','constituencies.dist_code')
                        ->whereNotIn('constituencies.cons_code', function($query) use ($state_id)
                        {
                            $query->select('cons_code')
                                  ->from('randomization_evm_second')
                                  ->where('state_id','=',$state_id)
                                  ->groupBy('cons_code');
                        })
                        ->select('constituencies.cons_name','districts.dist_name')
                        ->where('constituencies.state_id','=',$state_id)
                        ->get(); 
        }elseif($first_evm <= $today){
            $evmlist = DB::table('constituencies')
                        ->join('districts','districts.dist_code','=','constituencies.dist_code')
                        ->whereNotIn('constituencies.cons_code', function($query) use($state_id)
                        {
                            $query->select('cons_code')
                                  ->from('randomization_evm_first')
                                  ->where('randomization_evm_first.state_id','=',$state_id)
                                  ->groupBy('randomization_evm_first.cons_code');
                        })
                        ->select('constituencies.cons_name','districts.dist_name')
                        ->where('constituencies.state_id','=',$state_id)
                        ->get();   
        }else{
            $evmlist = array();
        }

        return view('eci/evm-vvpat-pending', [
        'evmlist' => $evmlist,
        ]);
    }

    public function distPollstationlist(){
        $user = Auth::user();
        $dist_code =  $user->dist_code;
        $state_id =  get_state_id();

        $distPollstationlist = DB::table('districts')
                        ->leftjoin('poll_booths', 'districts.dist_code', '=', 'poll_booths.dist_code')
                        ->select('districts.dist_code', 'districts.dist_name',DB::raw('IFNULL(COUNT(poll_booths.poll_booth_id), 0) as total'))
                        ->groupBy('districts.dist_code', 'districts.dist_name')
                        ->orderBy('districts.dist_code')                   
                        ->get();
        return view('eci/dist-pollstationlist', [
           'distPollstationlist' => $distPollstationlist,
        ]);
    }

    /* candidate list */

    public function candidateList()
    {


        $user = Auth::user();


        $state_id =  get_state_id();
        $district=DB::table('districts')
                 ->where('state_id', $state_id)
                 ->get();

        $districtFirst=DB::table('districts')
                 ->where('state_id', $state_id)
                 ->first();

        $firstDistCode=$districtFirst->dist_code;
        $encryptDist=eci_encrypt($districtFirst->dist_code);

        $constituency=DB::table('constituencies')
                    ->where('dist_code', $firstDistCode)
                    ->get();

        $constituencyFirst=DB::table('constituencies')
                    ->where('dist_code', $firstDistCode)
                    ->first();

        $firstCons=$constituencyFirst->cons_code;
        $encryptCons=eci_encrypt($constituencyFirst->cons_code);
        $getNomination = DB::table('new_candidate')
                       ->where('new_candidate.dist_code', $firstDistCode)
                       ->where('new_candidate.cons_code', $firstCons)
                       ->get();
                       //dd($getNomination);
        return view('eci/candidate-list', [
          'getNomination' => $getNomination,
          'district' => $district,
          'encryptDist' => $encryptDist,
          'encryptCons' => $encryptCons,
          'constituency' => $constituency,
        ]);
        // $user = Auth::user();
        // return view('ceo/candidate-list');
    }

    public function candidateaffidavit($cand_sl_no,$cons_code){
        $state_id = 's19';
        $affidavit = get_candidate_affidavit($cons_code,$cand_sl_no,$state_id);
        $pdf_decoded = base64_decode ($affidavit[0]->AffidavitImages);
        header('Content-Type: application/pdf');
        echo $pdf_decoded;
    }

    public function candidateListSearch(Request $request)
    {
        $user = Auth::user();
        $this->validate($request, [
            'dist_code' => 'required',
            'cons_code' => 'required',
        ]);
        $state_id =  get_state_id();
        $cons_code = eci_decrypt($request->cons_code);
        $dist_code = eci_decrypt($request->dist_code);
        $user = Auth::user();
        $district=DB::table('districts')
                 ->where('state_id', $state_id)
                 ->get();
        $constituency=DB::table('constituencies')
                    ->where('dist_code', $dist_code)
                    ->get();

        $getNomination = DB::table('new_candidate')
                       ->where('new_candidate.dist_code', $dist_code)
                       ->where('new_candidate.cons_code', $cons_code)
                       ->get();
    
        return view('ceo/candidate-list', [
          'getNomination' => $getNomination,
          'district' => $district,
          'encryptDist' => $request->dist_code,
          'encryptCons' => $request->cons_code,
          'constituency' => $constituency,
        ]);
    }

    public function evmVvpat(){
        $user = Auth::user();
        $state_id = get_state_id();
        $district=DB::table('districts')
                 ->where('state_id', $state_id)
                 ->get();

        $districtFirst=DB::table('districts')
                 ->where('state_id', $state_id)
                 ->first();

        $firstDistCode=$districtFirst->dist_code;
        $encryptDist=eci_encrypt($districtFirst->dist_code);

        $constituency=DB::table('constituencies')
                    ->where('dist_code', $firstDistCode)
                    ->get();

        $constituencyFirst=DB::table('constituencies')
                    ->where('dist_code', $firstDistCode)
                    ->first();

        $encryptCons=eci_encrypt($constituencyFirst->cons_code);

        $selectedRand=eci_encrypt("1");

        $first_evm_date = Config::get('constants.FIRST_RANDOMIZATION_EVM_DATE');
        $time_now=mktime(date('h')+5,date('i')+30,date('s'));
        $current_date = date('Y-m-d', $time_now);
        if (strtotime($first_evm_date) > strtotime($current_date)) {
            \Session::flash('message', 'Too be announced soon.');

            return view('eci/evm-vvpat', [
                'district' => $district,
                'encryptDist' => $encryptDist,
                'encryptCons' => $encryptCons,
                'constituency' => $constituency,
                'selectedRand' => $selectedRand,
            ]);
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
                                   ->where('randomization_evm_first.dist_code', $firstDistCode)
                                   ->get();
            
            return view('eci/evm-vvpat', [
                'visibile' => 1,
                'getfirstrandomisation' => $getfirstrandomisation,
                'district' => $district,
                'encryptDist' => $encryptDist,
                'encryptCons' => $encryptCons,
                'constituency' => $constituency,
                'selectedRand' => $selectedRand,
            ]);
        }
    }

    public function evmVvpatSearch(Request $request) {   
        $this->validate(
        $request, 
        [
          'cons_code' => 'required',
          'dist_code' => 'required',
          'rand_id' => 'required'
        ],
        [
          'cons_code.required' => 'Please Select Constituency',
          'dist_code.required' => 'Please Select District',
          'rand_id.required' => 'Please Select Randomization'
        ]
        );

        $user = Auth::user();
        $encryptDist=$request->dist_code;
        $encryptCons=$request->cons_code;

        $cons_code_dcr = eci_decrypt($request->cons_code);
        $dist_code_dcr = eci_decrypt($request->dist_code);
        $state_id = get_state_id();

        $district=DB::table('districts')
                 ->where('state_id', $state_id)
                 ->get();

     
        $constituency=DB::table('constituencies')
                    ->where('dist_code', $dist_code_dcr)
                    ->get();

        $rand_id = eci_decrypt($request->rand_id);

        $selectedRand = $request->rand_id;


        $time_now=mktime(date('h')+5,date('i')+30,date('s'));
        $current_date = date('Y-m-d', $time_now);
        $second_evm_date = Config::get('constants.SECOND_RANDOMIZATION_EVM_DATE');
        if (strtotime($second_evm_date) > strtotime($current_date)) {
            $visibile = 0;
        }
        else{
            $visibile = 1;
        }
        $getConst = DB::table('constituencies')->where('dist_code',$user->dist_code)->get();

        if($rand_id == 1){
            $getfirstrandomisation = DB::table('randomization_evm_first')
                                   ->join('constituencies', 'randomization_evm_first.cons_code','constituencies.cons_code')
                                   ->where('randomization_evm_first.dist_code', $dist_code_dcr)
                                   ->where('randomization_evm_first.cons_code', $cons_code_dcr)
                                   ->get();

            return view('eci/evm-vvpat', [
                'visibile' => $visibile,
                'getfirstrandomisation' => $getfirstrandomisation,
                'district' => $district,
                'encryptDist' => $encryptDist,
                'encryptCons' => $encryptCons,
                'constituency' => $constituency,
                'selectedRand' => $selectedRand,
                
            ]);
        }
        else{
            $getsecondrandomisation = DB::table('randomization_evm_second')
                                    ->join('constituencies', 'randomization_evm_second.cons_code','constituencies.cons_code')
                                    ->join('poll_booths', 'randomization_evm_second.bid','poll_booths.bid')
                                    ->where('randomization_evm_second.dist_code', $dist_code_dcr)
                                    ->where('randomization_evm_second.cons_code', $cons_code_dcr)
                                    ->get();

            return view('eci/evm-vvpatII', [
                'visibile' => $visibile,
                'getsecondrandomisation' => $getsecondrandomisation,
                'district' => $district,
                'encryptDist' => $encryptDist,
                'encryptCons' => $encryptCons,
                'constituency' => $constituency,
                'selectedRand' => $selectedRand,
            ]);
        } 
    }

    public function consPollstationlist($distCode){
        $user = Auth::user();
        $dist_code = eci_decrypt($distCode);
        $state_id =  get_state_id();

        $consPollstationlist = DB::table('constituencies')
                        ->leftjoin('poll_booths', 'constituencies.cons_code', '=', 'poll_booths.cons_code')
                        ->select('constituencies.cons_code', 'constituencies.cons_name',DB::raw('IFNULL(COUNT(poll_booths.poll_booth_id), 0) as total'))
                        ->where('constituencies.dist_code','=',$dist_code)
                        ->where('constituencies.state_id','=',$state_id)
                        ->groupBy('constituencies.cons_code', 'constituencies.cons_name')
                        ->orderBy('constituencies.cons_code')
                        ->get();

        return view('eci/cons-pollstationlist', [
        'consPollstationlist' => $consPollstationlist,
        ]);
    }


    public function consPollingStaff($consCode){
        $user = Auth::user();
        $state_id = get_state_id();
        $cons_code = eci_decrypt($consCode);
        $pollstafflist = DB::table('poll_booths')->join('users', 'poll_booths.supervisior_uid', '=', 'users.uid')->where('users.state_id', $state_id)->where('users.cons_code', $cons_code)->get();

        $mapVisible = DB::table('users')
                    ->join('poll_booths', 'users.uid', '=', 'poll_booths.supervisior_uid')
                    ->where('users.state_id', $state_id)
                    ->where('users.cons_code', $cons_code)
                    ->first();
        if(!empty($mapVisible)){
            $mapVisiblility=1;
        }else{
            $mapVisiblility=0;
        }

        return view('eci/cons-polling-staff', [
            'pollstafflist' => $pollstafflist,
            'cons_code' => $consCode,
            'mapVisiblility' => $mapVisiblility,
        ]);
    }

    public function pollingStationsMap($consCode)
    {   
        $user = Auth::user();
        $state_id =  get_state_id();
        $consDcrypt=eci_decrypt($consCode);
        $polling_stations = DB::table('users')
                            ->join('poll_booths', 'users.uid', '=', 'poll_booths.supervisior_uid')
                            ->where('users.state_id', $state_id)
                            ->where('users.cons_code', $consDcrypt)
                            ->get();
        return view('eci/polling-stations-map', [
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
        return view('eci/polling-detail', [
           'polling_detail' => $polling_detail,
           'polling_facility' => $polling_facility,
       ]);
    }


    public function boothAwarenessGroup($bid)
    {
        $user = Auth::user();
        $state_id = $user->state_id;
        $poll_booth_id = eci_decrypt($bid);
        $polling_detail = DB::table('booth_awareness_groups')
                           ->where('bid', $poll_booth_id)
                           ->get();
        return view('eci/booth-awareness-group', [
           'polling_detail' => $polling_detail,
       ]);
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
        
        // $getpsblo = DB::table('randomization_staff_third')
        //             ->join('users_pollday', 'randomization_staff_third.uid','users_pollday.uid')
        //             ->where('randomization_staff_third.uid', 'like', 'BLO%')
        //             ->where('bid',$bid)
        //             ->first();


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
       
       //dd($return_array);
        return view('eci/polling-parties-details', [
            'polling_users' => $return_array,
            'poo_array' => $poo_array,
            'poll_booths' => $poll_booths,
            'visibile' => $visibile,
        ]);
    }


    public function boothPhotos($poll_booth_id)
    {
        $user = Auth::user();
        $state_id = $user->state_id;
        $poll_booth_id = eci_decrypt($poll_booth_id);
        $poll_booth_id = str_pad($poll_booth_id, 8, '0', STR_PAD_LEFT);
        $dist_code = substr($poll_booth_id,0,2);
        //$dist_code = ltrim($dist_code1, '0');
        $cons_code = substr($poll_booth_id,2,3);
        //$cons_code = ltrim($cons_code1, '0')
        $ps_id = substr($poll_booth_id,5,7);
        //$ps_id = ltrim($ps_id1, '0');
        // $polling_detail = DB::table('poll_booths')
        //                    ->where('bid', $poll_booth_id)
        //                    ->first();
        return view('eci/booth-photos', [
           'state_id' => $state_id,
           'dist_code' => $dist_code,
           'cons_code' => $cons_code,
           'ps_id' => $ps_id,
       ]);
    }

    public function pollingStations() {
      $user = Auth::user();
      $district=DB::table('districts')->get();
      return view('eci/polling-stations', [
        'district' => $district,
      ]);
    }

    public function getPollingCons(Request $request) {
        $user = Auth::user();
        $state_id = get_state_id();
        $post = $request->all();
        $distCode=$_POST['distCode'];
        $distCodeDcrypt=eci_decrypt($distCode);
        $consList = DB::table('constituencies')
                      ->where('state_id', $state_id)
                      ->where('dist_code', $distCodeDcrypt)
                      ->get();

        foreach ($consList as $consLists) {
          $consEncrypted[] = eci_encrypt($consLists->cons_code);
        }

        if($request->ajax()){
            return response()->json([
                'consList' => $consList,
                'consEncrypted' => $consEncrypted,
            ]);
        }  
    }


    public function eciPollStationSub(Request $request) {
        $user = Auth::user();
        $this->validate($request, [
            'dist_code' => 'required',
            'cons_code' => 'required',
        ]);
        $encryptDist=$request->dist_code;
        $encryptCons=$request->cons_code;
        $consCode=eci_decrypt($request->cons_code);
        $distCode=eci_decrypt($request->dist_code);
        $state_id = get_state_id();
        $pollStation = DB::table('users')
                     ->join('poll_booths', 'users.uid', '=', 'poll_booths.supervisior_uid')
                     ->join('constituencies', 'users.cons_code', '=', 'constituencies.cons_code')
                     ->where('users.dist_code', $distCode)
                     ->where('users.cons_code', $consCode)
                     ->where('users.state_id', $state_id)
                     ->get();

        $district=DB::table('districts')
                 ->get();

        $constituency=DB::table('constituencies')
                 ->where('dist_code', $distCode)
                 ->get();

        $mapVisible = DB::table('users')
                    ->join('poll_booths', 'users.uid', '=', 'poll_booths.supervisior_uid')
                    ->where('users.state_id', $state_id)
                    ->where('users.dist_code', $distCode)
                    ->where('users.cons_code', $consCode)
                    ->first();

        if(!empty($mapVisible)){
            $mapVisiblility=1;
        }else{
            $mapVisiblility=0;
        }
        return view('eci/polling-stations', [
            'pollStation' => $pollStation,
            'district' => $district,
            'constituency' => $constituency,
            'encryptDist' => $encryptDist,
            'encryptCons' => $encryptCons,
            'mapVisiblility' => $mapVisiblility,
        ]);
    }


	// public function pollingStaff()
 //    { 
 //        $user = Auth::user();
 //        return view('eci/polling-staff');
 //    }

	public function electoralRolls()
    {
        $user = Auth::user();

        $state_id = get_state_id();
        $district=DB::table('districts')
                        ->where('state_id', $state_id)
                        ->get();
        return view('eci/poll-day', [
            'district' => $district,
        ]);






        // $distRo = Auth::user()->dist_code;
        // $consRo = Auth::user()->cons_code;
        // $stateRo = get_state_id();

        // $poll_station = DB::table('poll_booths')
        //             ->where('dist_code', $distRo)
        //             ->where('cons_code', $consRo)
        //             ->where('state_id', $stateRo)
        //             ->select('ps_id','poll_building') 
        //             ->orderby('booth_no')
        //             ->get();

        // return view('eci/electoral-rolls', [
        //     'poll_station' => $poll_station,
        // ]);
    }

    public function pMinus1Form() {
        $user = Auth::user();
        $stateEci=get_state_id();
        $distlist = DB::table('districts')->where('state_id',$stateEci)->get();
        return view('eci/p-1form', [
        'distlist' => $distlist,
        ]);
    }


    public function getCons(Request $request) {
        $post = $request->all();
        $districtIdEnc=$_POST['districtID'];
        $distDcrypt=eci_decrypt($districtIdEnc);
        $consDetail = DB::table('constituencies')->where('dist_code', $distDcrypt)->get();
        if($request->ajax()){
            return response()->json([
            'consDetail' => $consDetail
            ]);
        }
    }


    public function pMinus1FormSubmit(Request $request) {
        $user = Auth::user();
        $this->validate($request, [
            'distCode' => 'required',
            'consCode' => 'required',
        ]);
        $distCode=eci_decrypt($request->distCode);
        $consCode=$request->consCode;
        $polMinus1View = DB::table('poll_booths')
                            ->leftjoin('pro_activity_before', 'poll_booths.bid', '=', 'pro_activity_before.bid')
                            ->where('poll_booths.dist_code', $distCode)
                            ->where('poll_booths.cons_code', $consCode)
                            ->select('poll_booths.poll_building', 'pro_activity_before.*')
                            ->get();
        return view('eci/pol-1View', [
            'polMinus1View' => $polMinus1View,
        ]);
    }

    public function poMinus1View(){
        return view('eci/pol-1View');  
    }


    public function electionObservers() {
        $user = Auth::user();
        $state_id = get_state_id();
        $district=DB::table('districts')
                 ->where('state_id', $state_id)
                 ->get();

        $districtFirst=DB::table('districts')
                      ->where('dist_code', '11')
                      ->first();

        $distFirst=$districtFirst->dist_code;
        $encryptDist=eci_encrypt($distFirst);

        $generalObserver = DB::table('observer')
                        ->join('constituencies','observer.cons_code','=','constituencies.cons_code')
                        ->where('observer.dist_code', '11')
                        ->where('observer.type', "General Observer")
                        ->orderby('constituencies.cons_code')
                        ->get();

        $expenditureObserver = DB::table('observer')
                        ->join('constituencies','observer.cons_code','=','constituencies.cons_code')
                        ->where('observer.dist_code', '11')
                        ->where('observer.type', "Expenditure Observer")
                        ->orderby('constituencies.cons_code')
                        ->get();

        $policeObserver = DB::table('observer')
                        ->join('constituencies','observer.cons_code','=','constituencies.cons_code')
                        ->where('observer.dist_code', '11')
                        ->where('observer.type', "Police Observer")
                        ->orderby('constituencies.cons_code')
                        ->get();

        $awarenessObserver = DB::table('observer')
                        ->join('constituencies','observer.cons_code','=','constituencies.cons_code')
                        ->where('observer.dist_code', '11')
                        ->where('observer.type', "Awareness Observer")
                        ->orderby('constituencies.cons_code')
                        ->get();

        return view('eci/election-observers', [
            'generalObserver' => $generalObserver,
            'expenditureObserver' => $expenditureObserver,
            'policeObserver' => $policeObserver,
            'awarenessObserver' => $awarenessObserver,
            'district' => $district,
            'encryptDist' => $encryptDist,
        ]);  
    }


    public function electionObserverSearch(Request $request) {
        $user = Auth::user();
        $this->validate($request, [
          'dist_code' => 'required',
        ]);
        $state_id = get_state_id();
        $encryptDist=$request->dist_code;
        $distCode=eci_decrypt($request->dist_code);
        $district=DB::table('districts')
                 ->where('state_id', $state_id)
                 ->get();

        $generalObserver = DB::table('observer')
                        ->join('constituencies','observer.cons_code','=','constituencies.cons_code')
                        ->where('observer.dist_code', $distCode)
                        ->where('observer.type', "General Observer")
                        ->orderby('constituencies.cons_code')
                        ->get();

        $expenditureObserver = DB::table('observer')
                        ->join('constituencies','observer.cons_code','=','constituencies.cons_code')
                        ->where('observer.dist_code', $distCode)
                        ->where('observer.type', "Expenditure Observer")
                        ->orderby('constituencies.cons_code')
                        ->get();

        $policeObserver = DB::table('observer')
                        ->join('constituencies','observer.cons_code','=','constituencies.cons_code')
                        ->where('observer.dist_code', $distCode)
                        ->where('observer.type', "Police Observer")
                        ->orderby('constituencies.cons_code')
                        ->get();

        $awarenessObserver = DB::table('observer')
                        ->join('constituencies','observer.cons_code','=','constituencies.cons_code')
                        ->where('observer.dist_code', $distCode)
                        ->where('observer.type', "Awareness Observer")
                        ->orderby('constituencies.cons_code')
                        ->get();

        return view('eci/election-observers', [
            'generalObserver' => $generalObserver,
            'expenditureObserver' => $expenditureObserver,
            'policeObserver' => $policeObserver,
            'awarenessObserver' => $awarenessObserver,
            'district' => $district,
            'encryptDist' => $encryptDist,
        ]);
    }


    public function observerProfile($id) {
        $user = Auth::user();
        $idDcr=eci_decrypt($id);
        $observerDetail = DB::table('observer')
                        ->where('observer.id', $idDcr)
                        ->first();
        return view('eci/observer-profile', [
           'observerDetail' => $observerDetail,
        ]);
    }


    //--Add New Observer
    public function addNewObserver() {
        $user = Auth::user();
        $stateEci=get_state_id();
        $distlist = DB::table('districts')
                  ->where('state_id',$stateEci)
                  ->get();
        return view('eci/add-new-observer', [
        'distlist' => $distlist,
        ]);
    }

    public function addObserverSubmit(Request $request) {
      $user = Auth::user();
      $stateEci=get_state_id();
      $this->validate($request, [
          'obsName' => 'required',
          'obsEmail' => 'email',
          'obsPhone' => 'required|size:10',
          'obsPic' => 'required',
          'obAddress' => 'required',
          'obType' => 'required',
          'obsDistrict' => 'required',
      ]);
      $dt = Carbon::now();
      $timestamp=$dt->toDateString();
      //$dist_code=Auth::user()->dist_code;
      $obsPhone=$request->obsPhone;
      $uidObs="OBS".$obsPhone;

    //--Profile Picture
      $obsImg= (isset($request['obsPic']))? $request['obsPic'] : "";
      if($obsImg!==""){
        $filesObs = Input::file('obsPic');
        $destinationObs = 'images/observer';
        $filenameObs = $filesObs->getClientOriginalName();
        $randomObs=time();
        $filenameObs = $randomObs.$filenameObs;
        $upload_successObs= $filesObs->move($destinationObs, $filenameObs);
        $picObs = $filenameObs;
      }
      else{ 
        $picObs="";
      }
      $distDcrypt=eci_decrypt($request->obsDistrict);
      $addobserver = array(
          'name' => $request->obsName,
          'email' => $request->obsEmail,
          'phone' => $request->obsPhone,
          'type' => $request->obType,
          'address' => $request->obAddress,
          'state_id' => $stateEci,
          'profile_image' => $picObs,
          'uid' => $uidObs,
          'dist_code' => $distDcrypt,
          'updated_at' => $timestamp,
      );

      $addObs = DB::table('observer')->insert($addobserver);
      if($addObs>0) {
        Session::flash('obsSuccess', 'Observer Added Successfully.'); 
        Session::flash('alert-class', 'alert-success');
        return Redirect::to('eci/election-observers');
      }
    }


    public function editObserver($uid) {
        $uidDcrypt=eci_decrypt($uid);
        $editObs = DB::table('observer')->where('uid', $uidDcrypt)->first();


        $stateEci=get_state_id();
        $distlist = DB::table('districts')
                  ->where('state_id',$stateEci)
                  ->get();
        return view('eci/edit-observer', [
        'editObs' => $editObs,
        'distlist' => $distlist,
        ]);
    }


    public function updateObserver(Request $request) {
      $user = Auth::user();
      $oldImg=(isset($request['obsPicOld']))? $request['obsPicOld'] : "";
      $newImg=(isset($request['obsPicNew']))? $request['obsPicNew'] : "";
      if($oldImg==""){
        $this->validate($request, [
            'obsNameEdit' => 'required',
            'obsEmailEdit' => 'required|email',
            'obsPhoneEdit' => 'required|size:10',
            'obsPicNew' => 'required',
            'obAddressEdit' => 'required',
            'obTypeEdit' => 'required',
            'obDistEdit' => 'required',
        ]);
      }else{
        $this->validate($request, [
            'obsNameEdit' => 'required',
            'obsEmailEdit' => 'required|email',
            'obsPhoneEdit' => 'required|size:10',
            'obAddressEdit' => 'required',
            'obTypeEdit' => 'required',
            'obDistEdit' => 'required',
        ]);
      }
      
      if($oldImg==""){
        if($newImg==""){
          $proPicObs="";
        }
        else{
          $filesObsEdit = Input::file('obsPicNew');
          $destinationObsEdit = 'images/observer';
          $fileObsEdit = $filesObsEdit->getClientOriginalName();
          $randomObs=time();
          $filenameObsNew = $randomObs.$fileObsEdit;
          $upload_successObsE= $filesObsEdit->move($destinationObsEdit, $filenameObsNew);
          $proPicObs = $filenameObsNew;
        }
      }
      else{
        $proPicObs=$oldImg;
      }

      $obDistUp=eci_decrypt($request->obDistEdit);
      $dt = Carbon::now();
      $timestamp=$dt->toDateString();
      $obsPhoneE=$request->obsPhoneEdit;
      $oldUid=$request->uidHide;
      $oldUidDcrypt=eci_decrypt($oldUid);
      $uidObsEdit="OBS".$obsPhoneE;
      $upObs = array(
        'name' => $request->obsNameEdit,
        'email' => $request->obsEmailEdit,
        'phone' => $request->obsPhoneEdit,
        'dist_code' => $obDistUp,
        'type' => $request->obTypeEdit,
        'address' => $request->obAddressEdit,
        'profile_image' => $proPicObs,
        'uid' => $uidObsEdit,
        'updated_at' => $timestamp,
      );

      $upObserver = DB::table('observer')->where('uid', $oldUidDcrypt)->update($upObs);
      if($upObserver!=="") {
        Session::flash('obsSuccess', 'Observer Updated Successfully.'); 
        Session::flash('alert-class', 'alert-success');
        return Redirect::to('eci/election-observers');
      }
    }


    public function delObserver($uid) {
        $user = Auth::user();
        $uidDcrypt=eci_decrypt($uid);

        $delObs=DB::table('observer')->where('uid', $uidDcrypt)->delete();
        if($delObs!=="") {
            \Session::flash('obsSuccess', 'Observer Deleted successfully. ');
            return Redirect::to('eci/election-observers');
        }
        else{
            \Session::flash('obsErr', 'Please try again. ');
            return Redirect::to('eci/observer-profile/'.$uid);
        }
    }


    
    public function stafflist() {
        $user = Auth::user();
        $state_id =  get_state_id();
        
        $first_staff = strtotime(Config::get('constants.FIRST_RANDOMIZATION_STAFF_DATE'));
        $second_staff = strtotime(Config::get('constants.SECOND_RANDOMIZATION_STAFF_DATE'));
        $third_staff = strtotime(Config::get('constants.THIRD_RANDOMIZATION_STAFF_DATE'));
        $today = time();
        if($third_staff <= $today){
            
            $staffList = DB::table('constituencies')
                        ->join('districts','districts.dist_code','=','constituencies.dist_code')
                        ->whereNotIn('constituencies.cons_code', function($query) use($state_id)
                        {
                            $query->select('cons_code')
                                  ->from('randomization_staff_third')
                                  ->where('state_id','=',$state_id)
                                  ->groupBy('cons_code');
                        })
                        ->select('constituencies.cons_name','districts.dist_name')
                        ->where('constituencies.state_id','=',$state_id)
                        ->limit(5)
                        ->get(); 
        }elseif($second_staff <= $today){
                $staffList = DB::table('constituencies')
                        ->join('districts','districts.dist_code','=','constituencies.dist_code')
                        ->whereNotIn('constituencies.cons_code', function($query) use($state_id)
                        {
                            $query->select('cons_code')
                                  ->from('randomization_staff_second')
                                  ->where('state_id','=',$state_id)
                                  ->groupBy('cons_code');
                        })
                        ->select('constituencies.cons_name','districts.dist_name')
                        ->where('constituencies.state_id','=',$state_id)
                        ->limit(5)
                        ->get();  
        }elseif($first_staff <= $today){
              
               $staffList = DB::table('constituencies')
                        ->join('districts','districts.dist_code','=','constituencies.dist_code')
                        ->whereNotIn('constituencies.cons_code', function($query) use($state_id)
                        {
                            $query->select('cons_code')
                                  ->from('users_pollday')
                                  ->where('state_id','=',$state_id)
                                  ->groupBy('cons_code');
                        })
                        ->select('constituencies.cons_name','districts.dist_name')
                        ->where('constituencies.state_id','=',$state_id)
                        ->get();     
        }else{
            $staffList = array();
        }
        return view('eci/staffList', [
           'stafflist' => $staffList,
        ]);
    }

	//-- Poll day report
    public function pollDayReport() {
		$user = Auth::user();
		$state_id = get_state_id();
		$district=DB::table('districts')
						->where('state_id', $state_id)
						->get();
		return view('eci/poll-day', [
			'district' => $district,
		]);
    }


    public function pollDay(Request $request) {
		$this->validate($request, [
		'dist_code' => 'required',
		'cons_code' => 'required',
		]);
		$user = Auth::user();
		$stateID=get_state_id();
		$encryptDist =$request->dist_code;
		$distCode =eci_decrypt($encryptDist);
		$encryptCons=$request->cons_code;
		$consCode=eci_decrypt($encryptCons);
		$pollDayDetail = DB::table('poll_booths')
							->leftjoin('pro_activity_pollday', 'poll_booths.bid', '=', 'pro_activity_pollday.bid')
                            ->where('poll_booths.state_id', $stateID)
                            ->where('poll_booths.dist_code', $distCode)
                            ->where('poll_booths.cons_code', $consCode)
                            ->get();
		$district=DB::table('districts')
					->where('state_id', $stateID)
					->get();
		$constituency=DB::table('constituencies')
                    ->where('dist_code', $distCode)
                    ->get();
		
		return view('eci/poll-day', [
            'pollDayDetail' => $pollDayDetail,
            'district' => $district,
            'constituency' => $constituency,
            'encryptDist' => $encryptDist,
            'encryptCons' => $encryptCons,
        ]);
    }


    
    public function prePollArrangement() {
        $user = Auth::user();
        $state_id = get_state_id();
        
        $district=DB::table('districts')
                        ->where('state_id', $state_id)
                        ->get();
        return view('eci/pre-poll-arrangement', [
            'district' => $district,
        ]);
    }

 
    public function prePollSub(Request $request) {
        $this->validate($request, [
        'dist_code' => 'required',
        'cons_code' => 'required',
        ]);

        $encryptDist=$request->dist_code;
        $encryptCons=$request->cons_code;
        $districtCode=eci_decrypt($request->dist_code);
        $consituency=eci_decrypt($request->cons_code);
        $state=get_state_id();

        $prePollSec=DB::table('pre_poll_arrangement_ro')
                 ->where('state_id', $state)
                 ->where('dist_code', $districtCode)
                 ->where('cons_code', $consituency)
                 ->where('doc_type', 'SEC')
                 ->first();

        $prePollTans=DB::table('pre_poll_arrangement_ro')
                 ->where('state_id', $state)
                 ->where('dist_code', $districtCode)
                 ->where('cons_code', $consituency)
                 ->where('doc_type', 'TRANS')
                 ->first();

        $district=DB::table('districts')
                    ->where('state_id', $state)
                    ->get();

        $constituency=DB::table('constituencies')
                    ->where('dist_code', $districtCode)
                    ->get();

        return view('eci/pre-poll-arrangement', [
            'prePollSec' => $prePollSec,
            'prePollTans' => $prePollTans,
            'district' => $district,
            'constituency' => $constituency,
            'encryptDist' => $encryptDist,
            'encryptCons' => $encryptCons,
        ]);
    }


    
    public function pollingStaff() {
        $user = Auth::user();
        $state=get_state_id();

        $district=DB::table('districts')
                    ->where('state_id', $state)
                    ->get();

        return view('eci/polling-staff', [
            'district' => $district,
        ]);
    }


    
    public function pollingStaffSub(Request $request) {
        $this->validate($request, [
        'dist_code' => 'required',
        'cons_code' => 'required',
        'poll_type' => 'required',
        ]);

        $pollType=$request->poll_type;
        $encryptDist=$request->dist_code;
        $encryptCons=$request->cons_code;
        $districtCode=eci_decrypt($request->dist_code);
        $consituency=eci_decrypt($request->cons_code);
        $state=get_state_id();

        $district=DB::table('districts')
                    ->where('state_id', $state)
                    ->get();

        $constituency=DB::table('constituencies')
                    ->where('dist_code', $districtCode)
                    ->get();

        $type = $pollType;
        if($type == "second"){
            $polling_users = DB::table('users_pollday')
                             ->join('randomization_staff_second','randomization_staff_second.uid','=','users_pollday.uid')
                             ->join('constituencies', function($join) { 
                                    $join->on('constituencies.cons_code', '=', 'randomization_staff_second.cons_code')
                                          ->on('constituencies.state_id', '=', 'randomization_staff_second.state_id')
                                          ->on('constituencies.dist_code', '=', 'randomization_staff_second.dist_code'); 
                                      }
                                )
                             ->where('randomization_staff_second.state_id', $state)
                             ->where('randomization_staff_second.dist_code', $districtCode)
                             ->where('randomization_staff_second.cons_code', $consituency)
                             ->get();
            //dd($polling_users);
            return view('eci/polling-staff2', [
                'district' => $district,
                'constituency' => $constituency,
                'encryptDist' => $encryptDist,
                'encryptCons' => $encryptCons,
                'polling_users' => $polling_users,
                'pollType' => $pollType,
            ]); 


        }elseif($type == "third"){ 
            $polling_users = DB::table('poll_booths')
                             ->join('randomization_staff_third','randomization_staff_third.bid','=','poll_booths.bid')
                             ->where('randomization_staff_third.state_id', $state)
                             ->where('randomization_staff_third.dist_code', $districtCode)
                             ->where('randomization_staff_third.cons_code', $consituency)
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
                             ->join('constituencies', function($join) { 
                                    $join->on('constituencies.cons_code', '=', 'randomization_staff_third.cons_code')
                                          ->on('constituencies.state_id', '=', 'randomization_staff_third.state_id')
                                          ->on('constituencies.dist_code', '=', 'randomization_staff_third.dist_code');
                                      }
                                )
                             ->where('randomization_staff_third.party_no', $value->party_no)
                             ->where('randomization_staff_third.state_id', $state)
                             ->where('randomization_staff_third.dist_code', $districtCode)
                             ->where('randomization_staff_third.cons_code', $consituency)
                             ->select('randomization_staff_third.bid','poll_booths.ps_id','poll_booths.poll_building','users_pollday.name','users_pollday.elect_duty','users_pollday.phone','users.name as supervisor_name','users.phone as supervisor_phone','users.phone as supervisor_phone','constituencies.cons_name')
                             ->get();

                    if($staff->count()){
                        $polling_staff[$i]['party_no'] =  $value->party_no;
                        $polling_staff[$i]['ps_id'] =  $staff[0]->ps_id;
                        $polling_staff[$i]['bid'] =  $staff[0]->bid;
                        $polling_staff[$i]['poll_building'] =  $staff[0]->poll_building;
                        $polling_staff[$i]['supervisor_name'] =  $staff[0]->supervisor_name;
                        $polling_staff[$i]['supervisor_phone'] =  $staff[0]->supervisor_phone;
                        $polling_staff[$i]['staff']    = $staff;
                        $polling_staff[$i]['cons_name']    =  $staff[0]->cons_name;
                    }
                $i++;
                }
            }
            else{
                $polling_staff = array();
            }
            return view('eci/polling-staff3', [
                'polling_staff' => $polling_staff,
                'district' => $district,
                'constituency' => $constituency,
                'encryptDist' => $encryptDist,
                'encryptCons' => $encryptCons,
                'pollType' => $pollType,
            ]);

        }else{
            $polling_users = DB::table('users_pollday')                            
                             ->join('constituencies', function($join) { 
                                    $join->on('constituencies.cons_code', '=', 'users_pollday.cons_code')
                                          ->on('constituencies.state_id', '=', 'users_pollday.state_id')
                                          ->on('constituencies.dist_code', '=', 'users_pollday.dist_code'); 
                                      }
                                )
                             ->where('users_pollday.state_id', $state)
                             ->where('users_pollday.dist_code', $districtCode)
                             ->where('users_pollday.cons_code', $consituency)
                             ->get();

            return view('eci/polling-staff', [
                'district' => $district,
                'constituency' => $constituency,
                'encryptDist' => $encryptDist,
                'encryptCons' => $encryptCons,
                'polling_users' => $polling_users,
                'pollType' => $pollType,
            ]);
        }
        // return view('eci/polling-staff', [
        //     'district' => $district,
        //     'constituency' => $constituency,
        //     'encryptDist' => $encryptDist,
        //     'encryptCons' => $encryptCons,
        //     'polling_users' => $polling_users,
        //     'pollType' => $pollType,
        // ]);

    }



    public function pollPercentage() {

        $user = Auth::user();
        $state_id = get_state_id();

        $district=DB::table('districts')
                ->where('state_id', $state_id)
                ->get();

        $districtFirst=DB::table('districts')
                     ->where('state_id', $state_id)
                     ->first();

        $dist_code=$districtFirst->dist_code;
        $encryptDist=eci_encrypt($districtFirst->dist_code);

        $constituency=DB::table('constituencies')
                   ->where('dist_code', $dist_code)
                   ->get();

        $consFirst=DB::table('constituencies')
                 ->where('dist_code', $dist_code)
                 ->first();

        $cons_code=$consFirst->cons_code;
        $encryptCons=eci_encrypt($consFirst->cons_code);


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
                         ->where('poll_booths.state_id', $state_id)
                         ->where('poll_booths.dist_code', $dist_code)
                         ->where('poll_booths.cons_code', $cons_code)
                         ->select('pro_polling_percentage.'.$timeslot,'poll_booths.poll_building','poll_booths.bid')
                         ->get();

        return view('eci/poll-percentage', [
          'district' => $district,
          'constituency' => $constituency,
          'encryptDist' => $encryptDist,
          'encryptCons' => $encryptCons,
          'pollpercentages' => $pollpercentages,
          'polltiming' => $timeslot,
        ]);
    }

    public function p1Scrutiny(){
        $user = Auth::user();
        $state_id = get_state_id();
        $district=DB::table('districts')
                 ->where('state_id', $state_id)
                 ->get();

        $districtFirst=DB::table('districts')
                      ->where('state_id', $state_id)
                      ->first();

        $dist_code=$districtFirst->dist_code;
        $encryptDist=eci_encrypt($districtFirst->dist_code);

        $constituency=DB::table('constituencies')
                     ->where('dist_code', $dist_code)
                     ->get();

        $consFirst=DB::table('constituencies')
                  ->where('dist_code', $dist_code)
                  ->first();

        $cons_code=$consFirst->cons_code;
        $encryptCons=eci_encrypt($consFirst->cons_code);

        $scrutinyReport = DB::table('ro_report')
                    ->where('state_id', $state_id)
                    ->where('dist_code', $dist_code)
                    ->where('cons_code', $cons_code)
                    ->where('doc_type', "SCRUTINY")
                    ->first();

        return view('eci/p1-scrutiny', [
          'district' => $district,
          'constituency' => $constituency,
          'encryptDist' => $encryptDist,
          'encryptCons' => $encryptCons,
          'scrutinyReport' => $scrutinyReport,
        ]);
    }

    public function p1ScrutinySearch(Request $request) {
        $user = Auth::user();
        $this->validate($request, [
          'dist_code' => 'required',
          'cons_code' => 'required',
        ]);

        $encryptDist=$request->dist_code;
        $encryptCons=$request->cons_code;
        $cons_code=eci_decrypt($request->cons_code);
        $dist_code=eci_decrypt($request->dist_code);
        $state_id = get_state_id();

        $district=DB::table('districts')
                 ->where('state_id', $state_id)
                 ->get();

        $constituency=DB::table('constituencies')
                     ->where('dist_code', $dist_code)
                     ->get();

        $scrutinyReport = DB::table('ro_report')
                    ->where('state_id', $state_id)
                    ->where('dist_code', $dist_code)
                    ->where('cons_code', $cons_code)
                    ->where('doc_type', "SCRUTINY")
                    ->first();

        return view('eci/p1-scrutiny', [
          'district' => $district,
          'constituency' => $constituency,
          'encryptDist' => $encryptDist,
          'encryptCons' => $encryptCons,
          'scrutinyReport' => $scrutinyReport,
        ]);
    }

    
    public function p1ConsolidatedReport(){
        $user = Auth::user();
        $state_id = get_state_id();

        $district=DB::table('districts')
                 ->where('state_id', $state_id)
                 ->get();

        $districtFirst=DB::table('districts')
                      ->where('state_id', $state_id)
                      ->first();

        $dist_code=$districtFirst->dist_code;
        $encryptDist=eci_encrypt($districtFirst->dist_code);

        $constituency=DB::table('constituencies')
                     ->where('dist_code', $dist_code)
                     ->get();

        $consFirst=DB::table('constituencies')
                  ->where('dist_code', $dist_code)
                  ->first();

        $cons_code=$consFirst->cons_code;
        $encryptCons=eci_encrypt($consFirst->cons_code);

        $consReport = DB::table('ro_consolidated_report')
                ->where('cons_code', $cons_code)
                ->first();

        return view('eci/p1-consolidated-report', [
          'district' => $district,
          'constituency' => $constituency,
          'encryptDist' => $encryptDist,
          'encryptCons' => $encryptCons,
          'consReport' => $consReport,
        ]);
    }


    public function p1ConsolidatedReportSearch(Request $request) {
        $user = Auth::user();
        $this->validate($request, [
          'dist_code' => 'required',
          'cons_code' => 'required',
        ]);

        $encryptDist=$request->dist_code;
        $encryptCons=$request->cons_code;
        $cons_code=eci_decrypt($request->cons_code);
        $dist_code=eci_decrypt($request->dist_code);
        $state_id = get_state_id();

        $district=DB::table('districts')
                 ->where('state_id', $state_id)
                 ->get();

        $constituency=DB::table('constituencies')
                     ->where('dist_code', $dist_code)
                     ->get();

        $consReport = DB::table('ro_consolidated_report')
                ->where('cons_code', $cons_code)
                ->first();

        return view('eci/p1-consolidated-report', [
          'district' => $district,
          'constituency' => $constituency,
          'encryptDist' => $encryptDist,
          'encryptCons' => $encryptCons,
          'consReport' => $consReport,
        ]);
    }
    
    public function pollPercentagetiming(Request $request) {
        $user = Auth::user();
        $this->validate($request, [
          'dist_code' => 'required',
          'cons_code' => 'required',
          'polltiming' => 'required',
        ]);

        $encryptDist=$request->dist_code;
        $encryptCons=$request->cons_code;
        $cons_code=eci_decrypt($request->cons_code);
        $dist_code=eci_decrypt($request->dist_code);
        $state_id = get_state_id();
        $district=DB::table('districts')
                 ->where('state_id', $state_id)
                 ->get();

        $constituency=DB::table('constituencies')
                    ->where('dist_code', $dist_code)
                    ->get();

        $polltiming = $request->polltiming;
        $pollpercentages = DB::table('poll_booths')
                         ->leftjoin('pro_polling_percentage', 'poll_booths.bid', '=', 'pro_polling_percentage.bid')
                         ->where('poll_booths.state_id', $state_id)
                         ->where('poll_booths.dist_code', $dist_code)
                         ->where('poll_booths.cons_code', $cons_code)
                         ->select('pro_polling_percentage.'.$polltiming,'poll_booths.poll_building','poll_booths.bid')
                         ->get();
        return view('eci/poll-percentage', [
            'district' => $district,
            'constituency' => $constituency,
            'encryptDist' => $encryptDist,
            'encryptCons' => $encryptCons,
            'pollpercentages' => $pollpercentages,
            'polltiming' => $polltiming,
        ]);
    }

    public function pollingPercentageDetail($bid) {
        $user = Auth::user();
        $stateID=get_state_id();
        $bid=eci_decrypt($bid);
        $pollpercentageDetail = DB::table('poll_booths')
                            ->leftjoin('pro_polling_percentage', 'poll_booths.bid', '=', 'pro_polling_percentage.bid')
                            ->where('poll_booths.state_id', $stateID)
                            ->where('poll_booths.bid', $bid)
                            ->first();

        return view('eci/polling-percentage-detail', [
          'pollpercentageDetail' => $pollpercentageDetail,
        ]);
    }

    public function complaint(){
    $coms = array();
    $user = Auth::user();
    $complaints = get_complaints($user->state_id);
    if(@$complaints){
    $i = 0;
    $nature = get_com_nature();
    foreach ($complaints as $value) {
      if(($value->InformationType_ID == "1") &&( $value->CTBLBTypeID =="1")){
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
    //$complaints = json_decode($complaints);
    //dd($coms);
    return view('eci/complaint', [
           'complaints' => $coms,
        ]);
    
  }

  public function complaintDetail($id){
    $user = Auth::user();
    $complaints = get_complaint_detail($id);

    //dd($complaints);
    $details = array();
    if(@$complaints){
      $i = 0;
      $nature = get_com_nature();
      $parties = get_party_list();
      
      
      $details['ComplainNo'] = $complaints[0]->ComplainNo;
      if($complaints[0]->ComplainantTypeID == 1){
        $details['ComplainantType'] = "Political Party";
        foreach($parties as $pp){
          if($pp['id'] == $complaints[0]->PartyIDOfComplainent){
            $details['PartyComplainent'] = $pp['name'];
          }
        }           
      }elseif($complaints[0]->ComplainantTypeID == 2){
        $details['ComplainantType'] = "Citizen";
      }elseif($complaints[0]->ComplainantTypeID == 3){
        $details['ComplainantType'] = "Contesting Candidate";
      }elseif($complaints[0]->ComplainantTypeID == 4){
        $details['ComplainantType'] = "Other";
      }
      
      $details['NameOfComplainent'] = $complaints[0]->NameOfComplainent;
      $details['MobilOfCompalinent'] = $complaints[0]->MobilOfCompalinent;
      if(@$details['EmailIDOfComplainent']){
        $details['EmailIDOfComplainent'] = $complaints[0]->EmailIDOfComplainent;
      }
      if(@$details['AddressOfComplainent']){
        $details['AddressOfComplainent'] = $complaints[0]->AddressOfComplainent;
      }
      
      if($complaints[0]->ComplainTypeID == 1){
        $details['type'] = "Poll Related";
        //$nature = get_com_nature($complaints->ComplainTypeID);
      }elseif($complaints[0]->ComplainTypeID == 2){
        $details['type'] = "Voters' List/Voters' Card Related";           
      }
      
      if(@$nature){
        foreach($nature as $val){
          if($val['id'] == $complaints[0]->ComplaiNature_ID){
            $details['nature'] = $val['name'];  
          }
          
        }
      }
      $details['ComplainDescription'] = $complaints[0]->ComplainDescription;
      $details['cdate'] = date("d F, Y",strtotime($complaints[0]->EntryDate));
      $details['status'] = $complaints[0]->Status;
      if(@$complaints->ComplainAgainstName[0]){
        $details['ComplainAgainstName'] = $complaints[0]->ComplainAgainstName;
      }
    }else{
      $details = array();
    }
    
    return view('eci/complaint-detail', [
           'details' => $details,
        ]);
    }

    public function information(){
   $coms = array();
    $user = Auth::user();
    $complaints = get_complaints($user->state_id);
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
    }else{
      $coms = array();
    }
    //$complaints = json_decode($complaints);
    //dd($coms);
    return view('eci/information', [
           'complaints' => $coms,
        ]);
    }

    public function suggestion(){
   $coms = array();
    $user = Auth::user();
    $complaints = get_complaints($user->state_id);
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
    //$complaints = json_decode($complaints);
    //dd($coms);
    return view('eci/suggestion', [
           'complaints' => $coms,
        ]);
  }


  public function lawOrder()
    {
        $user = Auth::user();
        $state_id = get_state_id();

        $district=DB::table('districts')
                 ->where('state_id', $state_id)
                 ->get();

        $districtFirst=DB::table('districts')
                 ->where('state_id', $state_id)
                 ->first();

        $firstDist=$districtFirst->dist_code;

        $constituency=DB::table('constituencies')
                    ->where('dist_code', $firstDist)
                    ->get();

        $constituencyFirst=DB::table('constituencies')
                    ->where('dist_code', $firstDist)
                    ->first();

        $firstCons=$constituencyFirst->cons_code;

        $encryptDist=eci_encrypt($firstDist);
        $encryptCons=eci_encrypt($firstCons);

        $laworderlist = DB::table('pro_law_order')
                      ->join('poll_booths','poll_booths.bid','pro_law_order.bid')
                        ->join('users','poll_booths.supervisior_uid','users.uid')
                      
                      ->join('users_pollday', 'pro_law_order.uid','users_pollday.uid')
                      ->select('poll_booths.bid','poll_booths.poll_building','poll_booths.poll_building_detail','poll_booths.ps_id','users_pollday.name as pro_name', 'users_pollday.phone as pro_number', 'users.name as sup_name', 'users.phone as sup_num', 'pro_law_order.comment', 'pro_law_order.action_from', 'pro_law_order.action_to')
                      ->where('pro_law_order.dist_code', $firstDist)
                      ->where('pro_law_order.cons_code', $firstCons)
                      ->get();
                      //dd($laworderlist);
        return view('eci/law-order', [
            'laworderlist' => $laworderlist,
            'district' => $district,
            'constituency' => $constituency,
            'encryptDist' => $encryptDist,
            'encryptCons' => $encryptCons,
        ]);
    }

  public function lawOrderSub(Request $request) {
    $user = Auth::user();
    $state_id = get_state_id();
    $this->validate(
      $request, [
        'cons_code' => 'required',
        'dist_code' => 'required',
      ],
      [
        'cons_code.required' => 'This field is required',
        'dist_code.required' => 'This field is required',
      ]
    );
    $encryptDist=$request->dist_code;
    $encryptCons=$request->cons_code;
    $districtCode=eci_decrypt($encryptDist);
    $consituency=eci_decrypt($encryptCons);
    $district=DB::table('districts')
             ->where('state_id', $state_id)
             ->get();

    $constituency=DB::table('constituencies')
                 ->where('dist_code', $districtCode)
                 ->get();
    $laworderlist = DB::table('pro_law_order')
                      ->join('poll_booths','poll_booths.bid','pro_law_order.bid')
                        ->join('users','poll_booths.supervisior_uid','users.uid')
                      
                      ->join('users_pollday', 'pro_law_order.uid','users_pollday.uid')
                      ->select('poll_booths.bid','poll_booths.poll_building','poll_booths.poll_building_detail','poll_booths.ps_id','users_pollday.name as pro_name', 'users_pollday.phone as pro_number', 'users.name as sup_name', 'users.phone as sup_num', 'pro_law_order.comment', 'pro_law_order.action_from', 'pro_law_order.action_to')
                      ->where('pro_law_order.dist_code', $districtCode)
                      ->where('pro_law_order.cons_code', $consituency)
                      ->get();

    return view('eci/law-order', [
      'district' => $district,
      'constituency' => $constituency,
      'encryptDist' => $encryptDist,
      'encryptCons' => $encryptCons,
      'laworderlist' => $laworderlist,
    ]);
  }




  public function dispatchCollectionCenter() {
    $user = Auth::user();
    $state=get_state_id();

    $district=DB::table('districts')
             ->where('state_id', $state)
             ->get();

    $districtFirst=DB::table('districts')
                  ->where('state_id', $state)
                  ->first();

    $distFirst=$districtFirst->dist_code;
    $encryptDist=eci_encrypt($distFirst);

    $constituency=DB::table('constituencies')
                 ->where('dist_code', $distFirst)
                 ->get();

    $constituencyFirst=DB::table('constituencies')
                      ->where('dist_code', $distFirst)
                      ->first();

    $consFirst=$constituencyFirst->cons_code;
    $encryptCons=eci_encrypt($constituencyFirst->cons_code);

    $consTypeEnc=eci_encrypt("DISPATCH");
    $centerDetail = DB::table('dispatch_collection_center')
                  ->where('dist_code', $distFirst)
                  ->where('cons_code', $consFirst)
                  ->first();

    return view('eci/dispatch-collection-center', [
      'district' => $district,
      'constituency' => $constituency,
      'encryptDist' => $encryptDist,
      'encryptCons' => $encryptCons,
      'centerDetail' => $centerDetail,
      'consTypeEnc' => $consTypeEnc,
    ]);
  }


  public function dispatchCollectionCenterSub(Request $request) {
    $user = Auth::user();
    $this->validate($request, [
        'dist_code' => 'required',
        'cons_code' => 'required',
        'centerType' => 'required',
    ]);

    $pollType=$request->poll_type;
    $encryptDist=$request->dist_code;
    $encryptCons=$request->cons_code;
    $districtCode=eci_decrypt($request->dist_code);
    $consituency=eci_decrypt($request->cons_code);
    $state=get_state_id();
    $consTypeEnc=$request->centerType;

    $district=DB::table('districts')
             ->where('state_id', $state)
             ->get();

    $constituency=DB::table('constituencies')
                 ->where('dist_code', $districtCode)
                 ->get();

    $centerDetail = DB::table('dispatch_collection_center')
                  ->where('dist_code', $districtCode)
                  ->where('cons_code', $consituency)
                  ->first();

    return view('eci/dispatch-collection-center', [
      'district' => $district,
      'constituency' => $constituency,
      'encryptDist' => $encryptDist,
      'encryptCons' => $encryptCons,
      'consTypeEnc' => $consTypeEnc,
      'centerDetail' => $centerDetail,
    ]);
  }


  public function postalBallot() {
    $user = Auth::user();
    $state=get_state_id();
    $district=DB::table('districts')
             ->where('state_id', $state)
             ->get();

    $districtFirst=DB::table('districts')
                  ->where('state_id', $state)
                  ->first();

    $distFirst=$districtFirst->dist_code;
    $encryptDist=eci_encrypt($distFirst);
    $constituency=DB::table('constituencies')
                 ->where('dist_code', $distFirst)
                 ->get();

    $constituencyFirst=DB::table('constituencies')
                      ->where('dist_code', $distFirst)
                      ->first();

    $consFirst=$constituencyFirst->cons_code;
    $encryptCons=eci_encrypt($constituencyFirst->cons_code);
    $postBallot = DB::table('voters_ballot')
                ->where('state_id', $state)
                ->where('dist_code', $distFirst)
                ->where('cons_code', $consFirst)
                ->first();

    return view('eci/postal-ballot', [
      'district' => $district,
      'constituency' => $constituency,
      'encryptDist' => $encryptDist,
      'encryptCons' => $encryptCons,
      'postBallot' => $postBallot,
    ]);
  }

  public function postalBallotSub(Request $request) {
    $user = Auth::user();
    $this->validate($request, [
        'dist_code' => 'required',
        'cons_code' => 'required',
    ]);

    $pollType=$request->poll_type;
    $encryptDist=$request->dist_code;
    $encryptCons=$request->cons_code;
    $districtCode=eci_decrypt($request->dist_code);
    $consituency=eci_decrypt($request->cons_code);
    $state=get_state_id();
    $district=DB::table('districts')
             ->where('state_id', $state)
             ->get();

    $constituency=DB::table('constituencies')
                 ->where('dist_code', $districtCode)
                 ->get();

    $postBallot = DB::table('voters_ballot')
                ->where('state_id', $state)
                ->where('dist_code', $districtCode)
                ->where('cons_code', $consituency)
                ->first();

    return view('eci/postal-ballot', [
      'district' => $district,
      'constituency' => $constituency,
      'encryptDist' => $encryptDist,
      'encryptCons' => $encryptCons,
      'postBallot' => $postBallot,
    ]);
  }


  public function evmMalfunction() {
    $user = Auth::user();
    $state=get_state_id();
    $pollType='first';
    $district=DB::table('districts')
                ->where('state_id', $state)
                ->get();

    $districtFirst=DB::table('districts')
                ->where('state_id', $state)
                ->first();

    $distFirst=$districtFirst->dist_code;
    $encryptDist=eci_encrypt($distFirst);
    $constituency=DB::table('constituencies')
                ->where('dist_code', $distFirst)
                ->get();

    $constituencyFirst=DB::table('constituencies')
                ->where('dist_code', $distFirst)
                ->first();

    $consFirst=$constituencyFirst->cons_code;
    $encryptCons=eci_encrypt($constituencyFirst->cons_code);
    $mallfunctions = DB::table('poll_booths')
                  ->join('pro_evm_malfunctioning','poll_booths.bid', 'pro_evm_malfunctioning.bid')
                  ->join('users_pollday','pro_evm_malfunctioning.uid', 'users_pollday.uid')
                  ->where('pro_evm_malfunctioning.state_id', $state)
                  ->where('pro_evm_malfunctioning.dist_code', $distFirst)
                  ->where('pro_evm_malfunctioning.cons_code', $consFirst)
                  ->where('pro_evm_malfunctioning.status', 0)
                  ->get();

    $mallfunctions_resolve = DB::table('poll_booths')
                           ->join('pro_evm_malfunctioning','poll_booths.bid', 'pro_evm_malfunctioning.bid')
                           ->join('users_pollday','pro_evm_malfunctioning.uid', 'users_pollday.uid')
                           ->select('poll_booths.bid','poll_booths.poll_building','poll_booths.poll_building_detail','poll_booths.ps_id','users_pollday.name','pro_evm_malfunctioning.id','pro_evm_malfunctioning.reply')
                           ->where('pro_evm_malfunctioning.state_id', $state)
                           ->where('pro_evm_malfunctioning.dist_code', $distFirst)
                           ->where('pro_evm_malfunctioning.cons_code', $consFirst)
                           ->where('pro_evm_malfunctioning.status', 1)
                           ->get();

    return view('eci/evm-malfunction', [
        'district' => $district,
        'constituency' => $constituency,
        'encryptDist' => $encryptDist,
        'encryptCons' => $encryptCons,
        'mallfunctions' => $mallfunctions,
        'mallfunctions_resolve' => $mallfunctions_resolve,
    ]);
  }


    public function evmMalfunctionSub(Request $request) {
        $user = Auth::user();
        $this->validate($request, [
            'dist_code' => 'required',
            'cons_code' => 'required',
        ]);
        $encryptDist=$request->dist_code;
        $encryptCons=$request->cons_code;
        $districtCode=eci_decrypt($request->dist_code);
        $consCode=eci_decrypt($request->cons_code);
        $state=get_state_id();

        $district=DB::table('districts')
                    ->where('state_id', $state)
                    ->get();

        $constituency=DB::table('constituencies')
                    ->where('dist_code', $districtCode)
                    ->get();

        $mallfunctions = DB::table('poll_booths')
                      ->join('pro_evm_malfunctioning','poll_booths.bid', 'pro_evm_malfunctioning.bid')
                      ->join('users_pollday','pro_evm_malfunctioning.uid', 'users_pollday.uid')
                      ->where('pro_evm_malfunctioning.state_id', $state)
                      ->where('pro_evm_malfunctioning.dist_code', $districtCode)
                      ->where('pro_evm_malfunctioning.cons_code', $consCode)
                      ->where('pro_evm_malfunctioning.status', 0)
                      ->get();

        $mallfunctions_resolve = DB::table('poll_booths')
                               ->join('pro_evm_malfunctioning','poll_booths.bid', 'pro_evm_malfunctioning.bid')
                               ->join('users_pollday','pro_evm_malfunctioning.uid', 'users_pollday.uid')
                               ->select('poll_booths.bid','poll_booths.poll_building','poll_booths.poll_building_detail','poll_booths.ps_id','users_pollday.name','pro_evm_malfunctioning.id','pro_evm_malfunctioning.reply')
                               ->where('pro_evm_malfunctioning.state_id', $state)
                               ->where('pro_evm_malfunctioning.dist_code', $districtCode)
                               ->where('pro_evm_malfunctioning.cons_code', $consCode)
                               ->where('pro_evm_malfunctioning.status', 1)
                               ->get();

        return view('eci/evm-malfunction', [
            'district' => $district,
            'constituency' => $constituency,
            'encryptDist' => $encryptDist,
            'encryptCons' => $encryptCons,
            'mallfunctions' => $mallfunctions,
            'mallfunctions_resolve' => $mallfunctions_resolve,
        ]);
    }

    public function supervisorDetail($uid) {
        $uid = eci_decrypt($uid);
        $polling_stations = DB::table('poll_booths')->where('supervisior_uid', $uid)->where('status', 1)->get();
        $svDetail = DB::table('users')->where('uid', $uid)->first();

        return view('eci/supervisor-detail', [
         'polling_stations' => $polling_stations,
         'svDetail' => $svDetail,
        ]);
    }


    public function suvidha(){
      $user = Auth::user();
      $state_id = get_state_id();

      $district=DB::table('districts')
                ->where('state_id', $state_id)
                ->get();

      $districtFirst=DB::table('districts')
                     ->where('state_id', $state_id)
                     ->first();

      $dist_code=$districtFirst->dist_code;
      $encryptDist=eci_encrypt($districtFirst->dist_code);

      $constituency=DB::table('constituencies')
                   ->where('dist_code', $dist_code)
                   ->get();

      $consFirst=DB::table('constituencies')
                 ->where('dist_code', $dist_code)
                 ->first();

      $cons_code=$consFirst->cons_code;
      $encryptCons=eci_encrypt($consFirst->cons_code);

      $getdata = get_suvidha_data($state_id,$dist_code,$cons_code);
      $getparty = get_party_list();

      return view('eci/suvidha', [
        'getdata' => $getdata,
        'getparty' => $getparty,
        'constituency' => $constituency,
        'encryptDist' => $encryptDist,
        'encryptCons' => $encryptCons,
        'district' => $district,
      ]);
    }



    public function suvidhaSub(Request $request) {
      $user = Auth::user();
      $this->validate($request, [
          'dist_code' => 'required',
          'cons_code' => 'required',
      ]);
      $encryptDist=$request->dist_code;
      $encryptCons=$request->cons_code;
      $cons_code=eci_decrypt($request->cons_code);
      $dist_code=eci_decrypt($request->dist_code);
      $state_id = get_state_id();

      $district=DB::table('districts')
               ->where('state_id', $state_id)
               ->get();

      $constituency=DB::table('constituencies')
               ->where('dist_code', $dist_code)
               ->get();

      $getdata = get_suvidha_data($state_id,$dist_code,$cons_code);
      $getparty = get_party_list();

      return view('eci/suvidha', [
          'district' => $district,
          'constituency' => $constituency,
          'encryptDist' => $encryptDist,
          'encryptCons' => $encryptCons,
          'getdata' => $getdata,
          'getparty' => $getparty,
      ]);
    }

    public function suvidhaDetail($sid){
        $id=eci_decrypt($sid);
        $getdata = get_suvidha_detail($id);
        return view('eci/suvidha-detail', [
            'getdata' => $getdata,
        ]);
   }


  public function policeData(){
	$user = Auth::user();
	$user = Auth::user();
	$state_id = get_state_id();
	$district=DB::table('districts')->get();
	$districtFirst=DB::table('districts')
                    ->where('state_id', $state_id)
                    ->first();

	$distFirst=$districtFirst->dist_code;
	$encryptDist=eci_encrypt($distFirst);
		
	$policeData = DB::table('deo_police_data')
                                    ->join('districts', 'deo_police_data.dist_code','districts.dist_code')
                                    ->where('deo_police_data.state_id', $state_id)
                                    ->get();
	
	return view('eci/police-data', [
      'policeData' => $policeData,
	  'district' => $district,
	  'encryptDist' => $encryptDist,
    ]);
  }
  
  public function voterSlipData(){
        $user = Auth::user();
		$user = Auth::user();
		$state_id = get_state_id();
        $district=DB::table('districts')
                 ->where('state_id', $state_id)
                 ->get();

        $districtFirst=DB::table('districts')
                 ->where('state_id', $state_id)
                 ->first();

        $firstDist=$districtFirst->dist_code;

        $constituency=DB::table('constituencies')
                    ->where('dist_code', $firstDist)
                    ->get();

        $constituencyFirst=DB::table('constituencies')
                    ->where('dist_code', $firstDist)
                    ->first();
		
		
        $firstCons=$constituencyFirst->cons_code;
		$encryptDist=eci_encrypt($firstDist);
        $encryptCons=eci_encrypt($firstCons);
		
		$district=DB::table('districts')->get();
		$voterslipData = DB::table('ro_voter_slips_data')
                                    ->join('constituencies', 'ro_voter_slips_data.cons_code','constituencies.cons_code')
									->where('ro_voter_slips_data.state_id', $state_id)
                                    ->get();
									
		return view('eci/voter-slip-data', [
		  'voterslipData' => $voterslipData,
		  'district' => $district,
		  'constituency' => $constituency,
		  'encryptDist' => $encryptDist,
          'encryptCons' => $encryptCons,
		]);
    }


    public function pwdVoters(){
        $user = Auth::user();
        $state=$user->state_id;
        $pollType='first';
        $district=DB::table('districts')
                    ->where('state_id', $state)
                    ->get();

        $districtFirst=DB::table('districts')
                    ->where('state_id', $state)
                    ->first();

        $distFirst=$districtFirst->dist_code;
        $encryptDist=eci_encrypt($distFirst);
        $constituency=DB::table('constituencies')
                    ->where('dist_code', $distFirst)
                    ->get();

        $constituencyFirst=DB::table('constituencies')
                    ->where('dist_code', $distFirst)
                    ->first();

        $consFirst=$constituencyFirst->cons_code;
        $encryptCons=eci_encrypt($constituencyFirst->cons_code);

        $getPwd=getPwdVoter($consFirst);
        $getPwdVoter  = json_decode($getPwd);

        return view('eci/pwd-voters', [
          'district' => $district,
          'constituency' => $constituency,
          'encryptDist' => $encryptDist,
          'encryptCons' => $encryptCons,
          'getPwdVoter' => $getPwdVoter,
        ]);

      }


      public function pwdVoterSub(Request $request) {
        $user = Auth::user();
        $this->validate($request, [
            'dist_code' => 'required',
            'cons_code' => 'required',
        ]);
        $encryptDist=$request->dist_code;
        $encryptCons=$request->cons_code;
        $districtCode=eci_decrypt($request->dist_code);
        $consCode=eci_decrypt($request->cons_code);
        $state=$user->state_id;

        $district=DB::table('districts')
                    ->where('state_id', $state)
                    ->get();

        $constituency=DB::table('constituencies')
                    ->where('dist_code', $districtCode)
                    ->get();

        $getPwd=getPwdVoter($consCode);
        $getPwdVoter  = json_decode($getPwd);

        return view('eci/pwd-voters', [
          'district' => $district,
          'constituency' => $constituency,
          'encryptDist' => $encryptDist,
          'encryptCons' => $encryptCons,
          'getPwdVoter' => $getPwdVoter,
        ]);
      }
	  
	  
	public function voterslipDataResult(Request $request) {
        $user = Auth::user();
        $this->validate($request, [
            'dist_code' => 'required',
            'cons_code' => 'required',
        ]);
        $state_id = $user->state_id;
		$dist_code=eci_decrypt($request->dist_code);
		$cons_code=eci_decrypt($request->cons_code);
		//die();
        $district=DB::table('districts')
                 ->where('state_id', $state_id)
                 ->get();

        $districtFirst=DB::table('districts')
                 ->where('state_id', $state_id)
                 ->first();

        $firstDist=$districtFirst->dist_code;

        $constituency=DB::table('constituencies')
                    ->where('dist_code', $dist_code)
                    ->get();

        $constituencyFirst=DB::table('constituencies')
                    ->where('dist_code', $dist_code)
                    ->first();

        $firstCons=$constituencyFirst->cons_code;
		

        $encryptDist=eci_encrypt($dist_code);
        $encryptCons=eci_encrypt($firstCons);
		
		
		
		$voterslipData = DB::table('ro_voter_slips_data')
                                    ->join('constituencies', 'ro_voter_slips_data.cons_code','constituencies.cons_code')
                                    ->where('ro_voter_slips_data.dist_code', $dist_code)
									->where('ro_voter_slips_data.state_id', $state_id)
									->where('ro_voter_slips_data.cons_code', $cons_code)
                                    ->get();

      return view('eci/voter-slip-data', [
        'district' => $district,
        'constituency' => $constituency,
        'encryptDist' => $dist_code,
        'encryptCons' => $cons_code,
		'voterslipData' => $voterslipData,
      ]);
    }
	
	
	public function policeDataResult(Request $request) {
        $user = Auth::user();
        $this->validate($request, [
            'dist_code' => 'required',
        ]);
        $state_id = $user->state_id;

        $district=DB::table('districts')
                 ->where('state_id', $state_id)
                 ->get();

        $districtFirst=DB::table('districts')
                 ->where('state_id', $state_id)
                 ->first();

        $firstDist=$districtFirst->dist_code;

        $constituency=DB::table('constituencies')
                    ->where('dist_code', $firstDist)
                    ->get();

        $constituencyFirst=DB::table('constituencies')
                    ->where('dist_code', $firstDist)
                    ->first();

        $firstCons=$constituencyFirst->cons_code;
        $encryptDist=eci_encrypt($firstDist);
        $encryptCons=eci_encrypt($firstCons);
		$dist_code=eci_decrypt($request->dist_code);
        
		$policeData = DB::table('deo_police_data')
                                    ->join('districts', 'deo_police_data.dist_code','districts.dist_code')
                                    ->where('deo_police_data.state_id', $state_id)
									->where('deo_police_data.dist_code', $dist_code)
                                    ->get();
		
      return view('eci/police-data', [
        'district' => $district,
        'encryptDist' => $encryptDist,
		'policeData' => $policeData,
      ]);
    }

    public function facilities($bid){
        $user = Auth::user();
        $bid=eci_decrypt($bid);
        $polling_facility = DB::table('poll_booths_web')
                           ->where('bid', $bid)
                           ->first();
                          // dd($polling_facility);
        return view('eci/facilities', [
           'polling_facility' => $polling_facility,
        ]);
    }


    public function videoRecording(){
      $user = Auth::user();
      $state=$user->state_id;
      $pollType='first';
      $district=DB::table('districts')
                ->where('state_id', $state)
                ->get();

      $districtFirst=DB::table('districts')
                ->where('state_id', $state)
                ->first();

      $distFirst=$districtFirst->dist_code;
      $encryptDist=eci_encrypt($distFirst);
      $constituency=DB::table('constituencies')
                ->where('dist_code', $distFirst)
                ->get();

      $constituencyFirst=DB::table('constituencies')
                ->where('dist_code', $distFirst)
                ->first();

      $consFirst=$constituencyFirst->cons_code;
      $encryptCons=eci_encrypt($constituencyFirst->cons_code);
      $getPwd=getPwdVoter($consFirst);
      $getPwdVoter  = json_decode($getPwd);

      $proVideo = DB::table('pro_videography')
                    ->where('state_id', $state)
                    ->where('dist_code', $distFirst)
                    ->where('cons_code', $consFirst)
                    ->get();

      return view('eci/video-recording', [
        'district' => $district,
        'constituency' => $constituency,
        'encryptDist' => $encryptDist,
        'encryptCons' => $encryptCons,
        'proVideo' => $proVideo,
      ]);
    }

    
    public function videoRecordingSub(Request $request) {
      $user = Auth::user();
      $this->validate($request, [
        'dist_code' => 'required',
        'cons_code' => 'required',
      ]);
      $encryptDist=$request->dist_code;
      $encryptCons=$request->cons_code;
      $districtCode=eci_decrypt($request->dist_code);
      $consCode=eci_decrypt($request->cons_code);
      $state=$user->state_id;

      $district=DB::table('districts')
               ->where('state_id', $state)
               ->get();

      $constituency=DB::table('constituencies')
                   ->where('dist_code', $districtCode)
                   ->get();

      $proVideo = DB::table('pro_videography')
                    ->where('state_id', $state)
                    ->where('dist_code', $districtCode)
                    ->where('cons_code', $consCode)
                    ->get();

      return view('eci/video-recording', [
        'district' => $district,
        'constituency' => $constituency,
        'encryptDist' => $encryptDist,
        'encryptCons' => $encryptCons,
        'proVideo' => $proVideo,
      ]);
    }

}




