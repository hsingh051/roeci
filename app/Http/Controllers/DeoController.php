<?php
namespace App\Http\Controllers;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use DB;
use Config;
use Excel;

class DeoController extends Controller {
  /**
  * Create a new controller instance.
  *
  * @return void
  */
  public function __construct()
  {
    //dd("sdfsd");
    $this->middleware('auth');
    $this->middleware('deo');

  }

  /**
  * Show the application dashboard.
  *
  * @return \Illuminate\Http\Response
  */

  public function dashboard(){
    $user = Auth::user();
    $state_id =  $user->state_id;
    $dist_code =  $user->dist_code;
    $rolist = DB::table('users')
                  ->join('constituencies','constituencies.cons_code','=','users.cons_code')
                  ->where('users.role', '4')
                  ->where('users.state_id', $state_id)
                  ->where('users.dist_code', $user->dist_code)
                  ->select('users.name','constituencies.cons_name as cons_name','users.phone')
                  ->orderby('constituencies.cons_name')
                  ->limit(5)
                  ->get();
        //EVM And VVPAT
    $first_evm = strtotime(Config::get('constants.FIRST_RANDOMIZATION_EVM_DATE'));
    $second_evm = strtotime(Config::get('constants.SECOND_RANDOMIZATION_EVM_DATE'));
    $today = time();
    if($second_evm <= $today){
      $evmlist = DB::table('constituencies')
                        // ->whereNotIn('constituencies.cons_code', function($query) use ($state_id, $dist_code)
                        // {
                        //     $query->select('cons_code')
                        //           ->from('randomization_evm_second')
                        //           ->where('randomization_evm_second.state_id','=',$state_id)
                        //           ->where('randomization_evm_second.dist_code','=',$dist_code)
                        //           ->groupBy('randomization_evm_second.cons_code');
                        // })
                        ->select('constituencies.cons_name')
                        ->where('constituencies.state_id','=',$state_id)
                        ->where('constituencies.dist_code','=',$dist_code)
                        ->whereIn('constituencies.cons_code', ['35', '60','63','64'])
                        ->limit(5)
                        ->get(); 
    }elseif($first_evm <= $today){
      $evmlist = DB::table('constituencies')
                          // ->whereNotIn('constituencies.cons_code', function($query)  use ($state_id, $dist_code)
                          // {
                          //     $query->select('cons_code')
                          //           ->from('randomization_evm_first')
                          //           ->where('randomization_evm_first.state_id','=',$state_id)
                          //           ->where('randomization_evm_first.dist_code','=',$dist_code)
                          //           ->groupBy('randomization_evm_first.cons_code');
                          // })
                          ->select('constituencies.cons_name')
                          ->where('constituencies.state_id','=',$state_id)
                          ->where('constituencies.dist_code','=',$dist_code)
                          ->whereIn('constituencies.cons_code', ['35', '60','63','64'])
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
                        ->whereNotIn('constituencies.cons_code', function($query) use ($state_id, $dist_code)
                        {
                            $query->select('cons_code')
                                  ->from('randomization_staff_third')
                                  ->where('randomization_staff_third.state_id','=',$state_id)
                                  ->where('randomization_staff_third.dist_code','=',$dist_code)
                                  ->groupBy('cons_code');
                        })
                        ->select('constituencies.cons_name')
                        ->where('constituencies.state_id','=',$state_id)
                        ->where('constituencies.dist_code','=',$dist_code)
                        ->limit(5)
                        ->get(); 
    }elseif($second_staff <= $today){
      $staff = DB::table('constituencies')
                        // ->whereNotIn('constituencies.cons_code', function($query) use ($state_id, $dist_code)
                        // {
                        //     $query->select('constituencies.cons_name')
                        //           ->from('randomization_staff_second')
                        //           ->where('randomization_staff_second.state_id','=',$state_id)
                        //           ->where('randomization_staff_second.dist_code','=',$dist_code)
                        //           ->groupBy('randomization_staff_second.cons_code');
                        // })
                        ->select('constituencies.cons_name')
                        ->where('constituencies.state_id','=',$state_id)
                        ->where('constituencies.dist_code','=',$dist_code)
                        ->whereIn('constituencies.cons_code', ['35', '60','63','64'])
                        ->limit(5)
                        ->get();  
    }elseif($first_staff <= $today){
              
      $staff = DB::table('constituencies')
                        ->whereNotIn('constituencies.cons_code', function($query) use ($state_id, $dist_code)
                        {
                            $query->select('constituencies.cons_name')
                                  ->from('users_pollday')
                                  ->where('users_pollday.state_id','=',$state_id)
                                  ->where('users_pollday.dist_code','=',$dist_code)
                                  ->groupBy('users_pollday.cons_code');
                        })
                        ->select('constituencies.cons_name')
                        ->where('constituencies.state_id','=',$state_id)
                        ->where('constituencies.dist_code','=',$dist_code)
                        ->limit(5)
                        ->get();     
    }else{
      $staff = array();
    }
    // Polling Station
    $pollstationlist = DB::table('constituencies')
                    ->leftjoin('poll_booths', 'constituencies.cons_code', '=', 'poll_booths.cons_code')
                    ->select('constituencies.cons_code', 'constituencies.cons_name',DB::raw('IFNULL(COUNT(poll_booths.poll_booth_id), 0) as total'))
                    ->where('constituencies.dist_code','=',$dist_code)
                    ->groupBy('constituencies.cons_code', 'constituencies.cons_name')
                    ->orderBy('constituencies.cons_code')
                    ->limit(5)                    
                    ->get();
        // Electrol Rolls
    $voterlist = DB::table('constituencies')
                    ->leftjoin('voters_count', 'constituencies.cons_code', '=', 'voters_count.cons_code')
                    ->select('constituencies.cons_code', 'constituencies.cons_name',DB::raw('IFNULL(SUM(voters_count.total_voters), 0) as total'))
                    ->where('constituencies.dist_code','=',$dist_code)
                    ->groupBy('constituencies.cons_code', 'constituencies.cons_name')
                    ->orderBy('constituencies.cons_code')
                    ->limit(5) 
                    ->get();
    return view('deo/dashboard', [
           'rolist' => $rolist,
           'evmlist' => $evmlist,
           'stafflist' => $staff,
           'pollstationlist' => $pollstationlist,
           'voterlist' => $voterlist,
        ]);
  }

  public function pagenotfound(){
    $users = Auth::user();
    return view('deo/pagenotfound');
  }

  public function consElectrolList(){
    $user = Auth::user();
    $dist_code = $user->dist_code;
    $state_id =  $user->state_id;
    $voterlist = DB::table('constituencies')
                    ->leftjoin('voters_count', 'constituencies.cons_code', '=', 'voters_count.cons_code')
                    ->select('constituencies.cons_code', 'constituencies.cons_name',DB::raw('IFNULL(SUM(voters_count.total_voters), 0) as total'))
                    ->groupBy('constituencies.cons_code', 'constituencies.cons_name')
                    ->orderBy('constituencies.cons_code')
                    ->where('constituencies.state_id',$state_id)
                    ->where('constituencies.dist_code',$dist_code)
                    ->get();
    return view('deo/cons-electrollist', [
      'voterlist' => $voterlist,
      'dist_code' => $dist_code,
    ]);

  }

  public function psElectrolList($cons_code, Request $request){
    $user = Auth::user();
    $dist_code = $user->dist_code;
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
    return view('deo/ps-electrollist', [
      'psvoterlist' => $psvoterlist,
      'state_id' => $state_id,
      'dist_code' => $dist_code,
      'cons_code' => $cons_code,
    ]);
  }

  public function electrolList($ps_id, Request $request){
    $user = Auth::user();
    $cons_code = eci_decrypt($request->cons_code);
    $dist_code = $user->dist_code;
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
      $votersList = json_decode($votersListAPI);
    }
    return view('deo/electrollist', [
      'votersList' => $votersList,
    ]);
  }

  public function voterDetails($iCard){
    $user = Auth::user();
    $iCardNo=eci_decrypt($iCard);
    $voterDetail= DB::table('voters') 
                        ->join('constituencies', function($join) { $join->on('voters.cons_code', '=', 'constituencies.cons_code')->on('voters.state_id', '=', 'constituencies.state_id')->on('voters.dist_code', '=', 'constituencies.dist_code'); })
                        ->join('poll_booths', function($join) { $join->on('voters.ps_id', '=', 'poll_booths.ps_id')->on('voters.cons_code', '=', 'poll_booths.cons_code')->on('voters.state_id', '=', 'poll_booths.state_id')->on('voters.dist_code', '=', 'poll_booths.dist_code'); })
                        ->where('idcardNo', $iCardNo)->first();
    return view('deo/voter-details', [
      'voterDetail' => $voterDetail,
    ]);
  }
  
  public function roList(){
    $user = Auth::user();
    $getRoConst = DB::table('users')
                  ->join('constituencies', 'users.cons_code', '=', 'constituencies.cons_code')
                  ->where('users.dist_code',$user->dist_code)
                  ->where('users.role',4)
                  ->orderby('constituencies.cons_code')
                  ->get();
    return view('deo/ro-list', [
      'getRoConst' => $getRoConst,
    ]);
  }

//--Add New Ro
  public function addRo(){
    $user = Auth::user();
    return view('deo/add-ro');
  }

  public function addRoSub(Request $request){
    $user = Auth::user();
    $this->validate(
    $request, [
      'roCons' => 'required', 
      'roName' => 'required', 
      'roPhone' => 'required|size:10', 
      'roPhoneOffice' => 'size:10',
      'faxRo' => 'max:12',
    ],
    [
      'roCons.required' => 'Please select Constituency', 
      'roName.required' => 'RO Name is missing', 
      'roPhone.required' => 'RO contact is missing', 
      'roPhone.size' => 'Phone Number must be 10 digits',
      'roPhoneOffice.size' => 'Phone Number must be 10 digits',
      'faxRo.max' => 'Fax may not be greater then 12 digits',
    ]
    );
    $phoneRo=$request->roPhone;
    $uidRo="ROR".$phoneRo;
    $stateRo=Auth::user()->state_id;
    $distRo=Auth::user()->dist_code;
    $consRo=$request->roCons;
    $pwRo= rand(11111111,99999999);
    $dt = Carbon::now();
    $timestamp=$dt->toDateString();
    $roRepeat = DB::table('users')
                ->where('state_id', $stateRo)
                ->where('dist_code', $distRo)
                ->where('cons_code', $consRo)
                ->where('role', 4)
                ->get();
    $roRepeatCount=count($roRepeat);
    if($roRepeatCount>0){
      $upRo = array(
        'name' => $request->roName,
        'phone' => $phoneRo,
        'office_phone' => $request->roPhoneOffice,
        'fax' => $request->faxRo,
        'designation' => $request->designationRo,
        'uid' => $uidRo,
      );
      $updateRo = DB::table('users')
                  ->where('state_id', $stateRo)
                  ->where('dist_code', $distRo)
                  ->where('cons_code', $consRo)
                  ->where('role', 4)
                  ->update($upRo);
      if($updateRo>0){
        Session::flash('roSucc', 'RO updated successfully.'); 
        Session::flash('alert-class', 'alert-success');
        return Redirect::to('deo/ro-list');
      }else{
        Session::flash('roErr', 'Please try again.'); 
        Session::flash('alert-class', 'alert-danger');
        return Redirect::to('deo/add-ro');
      }
    }
    else{
      $addRo = array(
        'cons_code' => $request->roCons,
        'name' => $request->roName,
        'phone' => $phoneRo,
        'office_phone' => $request->roPhoneOffice,
        'fax' => $request->faxRo,
        'designation' => $request->designationRo,
        'uid' => $uidRo,
        'role' => '4',
        'dist_code' => $distRo,
        'state_id' => $stateRo,
        'address' => "",
        'password' => Hash::make($pwRo),
        'updated_at' => $timestamp,
      );  
      $addRos = DB::table('users')->insert($addRo);
      if($addRos>0){
        Session::flash('roSucc', 'RO added successfully.'); 
        Session::flash('alert-class', 'alert-success');
        return Redirect::to('deo/ro-list');
      }else{
        Session::flash('roErr', 'Please try again.'); 
        Session::flash('alert-class', 'alert-danger');
        return Redirect::to('deo/add-ro');
      }
    }
  }

  public function editRo($uid){
    $user = Auth::user();
    $uidDcrypt=eci_decrypt($uid);
    $RoDetail = DB::table('users')
              ->where('dist_code',$user->dist_code)
              ->where('uid',$uidDcrypt)
              ->where('role',4)
              ->first();

    return view('deo/editRo', [
      'RoDetail' => $RoDetail,
      'uid' => $uid,
    ]);
  }

  
  public function editRoSub(Request $request){
    $user = Auth::user();
    $newPhone=$request->roPhoneEdit;
    $oldPhone=$request->oldPhoneRo;
    $oldUidEnc=$request->uidRo;
    $oldUid=eci_decrypt($request->uidRo);
    if($newPhone==$oldPhone){
      $this->validate(
        $request, [ 
          'roNameEdit' => 'required', 
          'roPhoneEdit' => 'required|size:10',
          'roPhoneOfficeEdit' => 'size:10',
          'faxRoEdit' => 'max:12',
        ],
        [
          'roNameEdit.required' => 'RO Name is missing', 
          'roPhoneEdit.required' => 'RO contact is missing', 
          'roPhoneEdit.size' => 'Phone Number must be 10 digits',
          'roPhoneOfficeEdit.size' => 'Phone Number must be 10 digits',
          'faxRoEdit.max' => 'Fax may not be greater then 12 digits',
        ]
      );
    }
    else {
      $this->validate(
        $request, [ 
          'roNameEdit' => 'required', 
          'roPhoneEdit' => 'required|size:10|unique:users,phone',
          'roPhoneOfficeEdit' => 'size:10',
          'faxRoEdit' => 'max:12',
        ],
        [
          'roNameEdit.required' => 'RO Name is missing', 
          'roPhoneEdit.required' => 'RO contact is missing', 
          'roPhoneEdit.size' => 'Phone Number must be 10 digits',
          'roPhoneEdit.unique' => 'This Phone Number is already used',
          'roPhoneOfficeEdit.size' => 'Phone Number must be 10 digits',
          'faxRoEdit.max' => 'Fax may not be greater then 12 digits',
        ]
      );
    }
    $phoneRo=$newPhone;
    $newUid="ROR".$phoneRo;
    $stateRo=Auth::user()->state_id;
    $distRo=Auth::user()->dist_code;
    $dt = Carbon::now();
    $timestamp=$dt->toDateString();
    $upRo = array(
      'name' => $request->roNameEdit,
      'phone' => $phoneRo,
      'office_phone' => $request->roPhoneOfficeEdit,
      'fax' => $request->faxRoEdit,
      'designation' => $request->designationRoEdit,
      'uid' => $newUid,
    );
    $updateRo = DB::table('users')
                ->where('uid', $oldUid)
                ->where('role', 4)
                ->update($upRo);

    if($updateRo!==""){
      Session::flash('roSucc', 'RO updated successfully.'); 
      Session::flash('alert-class', 'alert-success');
      return Redirect::to('deo/ro-list');
    }else{
      Session::flash('roSucc', 'Please try again.'); 
      Session::flash('alert-class', 'alert-danger');
      return Redirect::to('deo/ro-list');
    }
  }
  
  public function delRo(Request $request){
    $user = Auth::user();
    $post = $request->all();
    $id=$post['id'];
    $uidDcrypt=eci_decrypt($id);
    $stateRo=Auth::user()->state_id;
    $distRo=Auth::user()->dist_code;
    $delRo=DB::table('users')
                    ->where('uid', $uidDcrypt)
                    ->where('state_id', $stateRo)
                    ->where('dist_code', $distRo)
                    ->where('role', '4')
                    ->delete();
    if($request->ajax()){
      if($delRo!==""){
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

  public function addRoCsv(){
    return view('deo/add-ro-csv');
  }

  public function addRoCsvSub(Request $request){
    $this->validate($request, [
      'roCsvData' => 'required',
    ]);
    $user = Auth::user();
    $this->stateRoCsv1=Auth::user()->state_id;
    $this->distRoCsv1=Auth::user()->dist_code;
    Excel::load(Input::file('roCsvData'), function ($reader) {
      $errRepeat=array();
      $errEmpty=array();
      $validPhone=array();
      $xyz=2;
      foreach ($reader->toArray() as $row) {
        $pwRoCsv= rand(11111111,99999999);
        $dtCsv = Carbon::now();
        $timestampCsv=$dtCsv->toDateString();
        $stateRoCsv=$this->stateRoCsv1;
        $distRoCsv=$this->distRoCsv1;
        $nameRoCsv=trim($row['name']);
        $consRoCsv=trim($row['ac_no']);
        $desigRoCsv=trim($row['designation']);
        $orgaRoCsv=trim($row['organisation']);
        $officePhoneRoCsv=trim($row['office_phone']);
        $phoneRoCsv=trim($row['phone']);
        $faxRoCsv=trim($row['fax']);
        $uidRoCsv="ROR".$phoneRoCsv;
        if(!empty($nameRoCsv) && !empty($consRoCsv) && !empty($phoneRoCsv)){
          if (preg_match('/^\d{10}$/', $phoneRoCsv)) {
            $roRepeat = DB::table('users')
                        ->where('state_id', $stateRoCsv)
                        ->where('dist_code', $distRoCsv)
                        ->where('cons_code', $consRoCsv)
                        ->where('role', 4)
                        ->first();
            if(!empty($roRepeat)){
              if(($roRepeat->phone)==($phoneRoCsv)){
                $upRoCsv = array(
                  'name' => $nameRoCsv,
                  'phone' => $phoneRoCsv,
                  'office_phone' => $officePhoneRoCsv,
                  'fax' => $faxRoCsv,
                  'designation' => $desigRoCsv,
                  'organisation' => $orgaRoCsv,
                  'uid' => $uidRoCsv,
                  'password' => Hash::make($pwRoCsv),
                );
                $updateRo = DB::table('users')
                            ->where('state_id', $stateRoCsv)
                            ->where('dist_code', $distRoCsv)
                            ->where('cons_code', $consRoCsv)
                            ->where('role', 4)
                            ->update($upRoCsv);
              }
              else {
                $checkPhone = DB::table('users')->where('phone', $phoneRoCsv)->get();
                $checkPhoneCount=count($checkPhone);
                if($checkPhoneCount>0){
                  $errRepeat[]=$xyz;
                }
                else{
                  $upRoCsv = array(
                    'name' => $nameRoCsv,
                    'phone' => $phoneRoCsv,
                    'office_phone' => $officePhoneRoCsv,
                    'fax' => $faxRoCsv,
                    'designation' => $desigRoCsv,
                    'organisation' => $orgaRoCsv,
                    'uid' => $uidRoCsv,
                    'password' => Hash::make($pwRoCsv),
                  );
                  $updateRo = DB::table('users')
                              ->where('state_id', $stateRoCsv)
                              ->where('dist_code', $distRoCsv)
                              ->where('cons_code', $consRoCsv)
                              ->where('role', 4)
                              ->update($upRoCsv);
                }
              }
            }
            else {
              $roCsvChk = DB::table('users')->where('phone', $phoneRoCsv)->get();
              $countRoCsv=count($roCsvChk);
              if($countRoCsv>0){
                $errRepeat[]=$xyz;
              }
              else{
                $addRoCsv = array(
                  'cons_code' => $consRoCsv,
                  'name' => $nameRoCsv,
                  'phone' => $phoneRoCsv,
                  'office_phone' => $officePhoneRoCsv,
                  'fax' => $faxRoCsv,
                  'designation' => $desigRoCsv,
                  'organisation' => $orgaRoCsv,
                  'uid' => $uidRoCsv,
                  'role' => '4',
                  'dist_code' => $distRoCsv,
                  'state_id' => $stateRoCsv,
                  'address' => "",
                  'password' => Hash::make($pwRoCsv),
                  'updated_at' => $timestampCsv,
                );  
                $addRoCsvs = DB::table('users')->insert($addRoCsv);
              }
            }
          }
          else {
            $validPhone[]=$xyz;
          }
        }
        else{
          $errEmpty[]=$xyz;
        }
      $xyz++;
      }
      //--Both Fields Rewuired
      $emptyError1 = array_filter($errEmpty);
      if (!empty($emptyError1)) {
        $emptyValue1=implode(',', $emptyError1);
        $emptyValue="on row ".$emptyValue1;
      }else{
        $emptyValue="";
      }
      if(!empty($emptyValue)){
        Session::flash('requireErrRo', 'Please fill all fields '.$emptyValue); 
        Session::flash('alert-class', 'alert-danger');
      }
      //--Phone Number Repeat
      $errRepeat1 = array_filter($errRepeat);
      if (!empty($errRepeat1)) {
        $errRptComma=implode(',', $errRepeat1);
        $errRptVal="on row ".$errRptComma;
      }else{
        $errRptVal="";
      }
      if(!empty($errRptVal)){
        Session::flash('repeatErrRo', 'Phone Number Is Already Used on rows '.$errRptVal); 
        Session::flash('alert-class', 'alert-danger');
      }

      //--Valid Phone Number
      $errValidPhn1 = array_filter($validPhone);
      if (!empty($errValidPhn1)) {
        $validPhnComma=implode(',', $errValidPhn1);
        $validPhoneRows="on row ".$validPhnComma;
      }else{
        $validPhoneRows="";
      }
      if(!empty($validPhoneRows)){
        Session::flash('validPhoneRo', 'Phone number is not valid on rows '.$validPhoneRows); 
        Session::flash('alert-class', 'alert-danger');
      }
    });
    Session::flash('roSucc', 'ROs Added Successfully.'); 
    Session::flash('alert-class', 'alert-success');
    return Redirect::to('deo/ro-list');
  }

//--Receaved Nominations
  public function nominationsReceived() {
    $user = Auth::user();
    $distCode =Auth::user()->dist_code;
    $constituency=DB::table('constituencies')
                    ->where('dist_code', $distCode)
                    ->get();
    $constituencyFirst=DB::table('constituencies')
                    ->where('dist_code', $distCode)
                    ->first();
    $firstCons=$constituencyFirst->cons_code;
    $encryptCons=eci_encrypt($constituencyFirst->cons_code);

    $getNomination = DB::table('users')
                         ->join('users_candidate_data', 'users.uid', '=', 'users_candidate_data.uid')
                         ->where('users.role', '15')
                         ->where('users.cons_code', $firstCons)
                         //->where('users_candidate_data.nominationDate', 'like', '%' . date("Y-m-d") . '%')
                         ->get();
    return view('deo/nomination-received', [
        'constituency' => $constituency,
        'encryptCons' => $encryptCons,
        'getNomination' => $getNomination,
    ]);
  }

  public function nominationReceived(Request $request){
    $this->validate(
      $request, 
        ['cons_code' => 'required'],
        ['cons_code.required' => 'Please Select Constituency']
    );
    $user = Auth::user();
    $encryptCons=$request->cons_code;
    $consCode=eci_decrypt($request->cons_code);
    $distCode =Auth::user()->dist_code;
    $constituency=DB::table('constituencies')
                    ->where('dist_code', $distCode)
                    ->get();
    $getNomination = DB::table('users')
                         ->join('users_candidate_data', 'users.uid', '=', 'users_candidate_data.uid')
                         ->where('users.role', '15')
                         ->where('users.cons_code', $consCode)
                         //->where('users_candidate_data.nominationDate', 'like', '%' . date("Y-m-d") . '%')
                     ->get();
    return view('deo/nomination-received', [
      'getNomination' => $getNomination,
      'constituency' => $constituency,
       'encryptCons' => $encryptCons, 
    ]);
  }
  
  public function nominationRejected(){
    $user = Auth::user();
    $distCode =Auth::user()->dist_code;
    $constituency=DB::table('constituencies')
                    ->where('dist_code', $distCode)
                    ->get();
    $constituencyFirst=DB::table('constituencies')
                    ->where('dist_code', $distCode)
                    ->first();
    $firstCons=$constituencyFirst->cons_code;
    $encryptCons=eci_encrypt($constituencyFirst->cons_code);
    $getNomination = DB::table('users')
                         ->join('users_candidate_data', 'users.uid', '=', 'users_candidate_data.uid')
                         ->where('users.role', '15')
                         ->where('users.cons_code', $firstCons)
                         ->where('users_candidate_data.nominationStatus', 'R')
                         ->get();
    return view('deo/nomination-rejected', [
      'getNomination' => $getNomination,
      'encryptCons' => $encryptCons,
        'constituency' => $constituency,
    ]);
  }
  
  public function nominationRejectedSub(Request $request){
    $this->validate(
      $request, 
      ['cons_code' => 'required'],
      ['cons_code.required' => 'Please Select Constituency']
    );
    $user = Auth::user();
    $encryptCons=$request->cons_code;
    $consCode=eci_decrypt($request->cons_code);
    $distCode =Auth::user()->dist_code;
    $constituency=DB::table('constituencies')
                   ->where('dist_code', $distCode)
                   ->get();
    $getNomination = DB::table('users')
                         ->join('users_candidate_data', 'users.uid', '=', 'users_candidate_data.uid')
                         ->where('users.role', '15')
                         ->where('users.cons_code', $consCode)
                         ->where('users_candidate_data.nominationStatus', 'R')
                         ->get();
    return view('deo/nomination-rejected', [
      'getNomination' => $getNomination,
      'encryptCons' => $encryptCons,
      'constituency' => $constituency,
    ]);
  }

  public function nominationWithdrawls(){
    $user = Auth::user();
    $distCode =Auth::user()->dist_code;
    $constituency=DB::table('constituencies')
                  ->where('dist_code', $distCode)
                  ->get();
    $constituencyFirst=DB::table('constituencies')
                  ->where('dist_code', $distCode)
                  ->first();
    $firstCons=$constituencyFirst->cons_code;
    $encryptCons=eci_encrypt($constituencyFirst->cons_code);
    $getNomination = DB::table('users')
                       ->join('users_candidate_data', 'users.uid', '=', 'users_candidate_data.uid')
                       ->where('users.role', '15')
                       ->where('users.cons_code', $firstCons)
                       ->where('users_candidate_data.nominationStatus', 'W')
                       ->get();
      
    return view('deo/nomination-withdrawls', [
      'getNomination' => $getNomination,
      'encryptCons' => $encryptCons,
      'constituency' => $constituency,
    ]);
  }

  public function nominationWithdrawlSub(Request $request){
    $this->validate(
      $request, 
      ['cons_code' => 'required'],
      ['cons_code.required' => 'Please Select Constituency']
    );
    $user = Auth::user();
    $distCode =Auth::user()->dist_code;
    $constituency=DB::table('constituencies')
                  ->where('dist_code', $distCode)
                  ->get();
    $encryptCons=$request->cons_code;
    $consCode=eci_decrypt($request->cons_code);
    $getNomination = DB::table('users')
                       ->join('users_candidate_data', 'users.uid', '=', 'users_candidate_data.uid')
                       ->where('users.role', '15')
                       ->where('users.cons_code', $consCode)
                       ->where('users_candidate_data.nominationStatus', 'W')
                       ->get();
    return view('deo/nomination-withdrawls', [
      'getNomination' => $getNomination,
      'encryptCons' => $encryptCons,
      'constituency' => $constituency,
    ]);
  }

  public function candidateList(){
    $user = Auth::user();
    $distCode =Auth::user()->dist_code;
    $constituency=DB::table('constituencies')
                ->where('dist_code', $distCode)
                ->get();

    $constituencyFirst=DB::table('constituencies')
                ->where('dist_code', $distCode)
                ->first();

    $firstCons=$constituencyFirst->cons_code;
    $encryptCons=eci_encrypt($constituencyFirst->cons_code);
    $getNomination = DB::table('users')
                         ->join('users_candidate_data', 'users.uid', '=', 'users_candidate_data.uid')
                         ->where('users.role', '15')
                         ->where('users.cons_code', $firstCons)
                         ->where('users_candidate_data.nominationStatus', 'N')
                         ->get();
      
    return view('deo/candidate-list', [
      'getNomination' => $getNomination,
      'encryptCons' => $encryptCons,
      'constituency' => $constituency,
    ]);
  }

  public function candidateaffidavit($cand_sl_no,$cons_code){
    $state_id = 's19';
    $affidavit = get_candidate_affidavit($cons_code,$cand_sl_no,$state_id);
    $pdf_decoded = base64_decode ($affidavit[0]->AffidavitImages);
    header('Content-Type: application/pdf');
    echo $pdf_decoded;
  }

  public function candidateListSub(Request $request){
    $this->validate(
      $request, 
      ['cons_code' => 'required'],
      ['cons_code.required' => 'Please Select Constituency']
    );
    $user = Auth::user();
    $distCode =Auth::user()->dist_code;
    $constituency=DB::table('constituencies')
                ->where('dist_code', $distCode)
                ->get();

    $encryptCons=$request->cons_code;
    $consCode=eci_decrypt($request->cons_code);
    $getNomination = DB::table('users')
                     ->join('users_candidate_data', 'users.uid', '=', 'users_candidate_data.uid')
                     ->where('users.role', '15')
                     ->where('users.cons_code', $consCode)
                     ->where('users_candidate_data.nominationStatus', 'N')
                     ->get();

    return view('deo/candidate-list', [
      'getNomination' => $getNomination,
      'encryptCons' => $encryptCons,
      'constituency' => $constituency,
    ]);
  }

  public function candidateDetail($uid){
    $user = Auth::user();
    $uidDcr=eci_decrypt($uid);
    $candidateDetail = DB::table('users')
                      ->join('users_candidate_data', 'users.uid', '=', 'users_candidate_data.uid')
                      ->join('districts', 'users.dist_code', '=', 'districts.dist_code')
                      ->leftJoin('symbols', 'users_candidate_data.cand_symbol', '=', 'symbols.symbol_no')
                      ->leftJoin('constituencies', 'users_candidate_data.cons_code', '=', 'constituencies.cons_code')
                      ->where('users.uid', $uidDcr)
                      ->first();

    return view('deo/candidate-detail', [
       'candidateDetail' => $candidateDetail,
    ]);
  }

  public function supervisorList($id){
    $roId=eci_decrypt($id);
    $getRoDetail = DB::table('users')->join('constituencies', 'users.cons_code', '=', 'constituencies.cons_code')->where('uid', $roId)->where('role',4)->first();
    $supervisors = DB::table('users')->where('dist_code', $getRoDetail->dist_code)->where('cons_code', $getRoDetail->cons_code)->where('role', 5)->get();
    return view('deo/supervisor-list', [
      'getRoDetail' => $getRoDetail,
      'supervisors' => $supervisors,
    ]);
  }

  public function supervisorDetail($uid){
    $uid = eci_decrypt($uid);
    $polling_stations = DB::table('poll_booths')->where('supervisior_uid', $uid)->where('status', 1)->get();
    $svDetail = DB::table('users')->where('uid', $uid)->first();

    return view('deo/supervisor-detail', [
       'polling_stations' => $polling_stations,
       'svDetail' => $svDetail,
    ]);
  }     
  
  public function electoralRolls(){
    $user = Auth::user();
    $distCode = Auth::user()->dist_code;
    $stateCode = Auth::user()->state_id;
    $constituency = DB::table('constituencies')
                    ->where('dist_code', $distCode)
                    ->where('state_id', $stateCode)
                    ->get();
    $constituencyFirst = DB::table('constituencies')
                    ->where('dist_code', $distCode)
                    ->where('state_id', $stateCode)
                    ->first();
    $firstCons=$constituencyFirst->cons_code;
    $encConsCode=eci_encrypt($constituencyFirst->cons_code);
    $pslist = DB::table('poll_booths')
              ->where('state_id', $stateCode)
              ->where('dist_code', $distCode)
              ->where('cons_code', $firstCons)->get();
    $pslistFirst = DB::table('poll_booths')
              ->where('state_id', $stateCode)
              ->where('dist_code', $distCode)
              ->where('cons_code', $firstCons)->first();
    $psFirst=$pslistFirst->ps_id;
    $encPsId=eci_encrypt($pslistFirst->ps_id);
    $votersList = DB::table('voters')
                  ->where('dist_code', $distCode)
                  ->where('cons_code', $firstCons)
                  ->where('state_id', $stateCode)
                  ->where('ps_id', $psFirst)
                  ->orderby('slnoinpart')
                  ->get();
    if($votersList->count()>=1){
    }else{

      $votersListAPI = app('App\Http\Controllers\CronjobController')->get_voter_list($stateCode,$distCode,$firstCons,$psFirst);
      $votersList = json_decode($votersListAPI);
    }

    return view('deo/electoral-rolls', [
      'votersList' => $votersList,
      'constituency' => $constituency,
      'encPsId' => $encPsId,
      'encConsCode' => $encConsCode,
      'pslist' => $pslist,
    ]);
  }

  public function getpslist(Request $request) {
    $user = Auth::user();
    $dist_code = Auth::user()->dist_code;
    $state_id = Auth::user()->state_id;
    $post = $request->all();
    $cons_code=$_POST['cons_code'];
    if($cons_code==""){
      if($request->ajax()){
        $statusResult[] = array(
          'statusResponce'  => '0'
        );
        return response()->json([
          'statusResult' => $statusResult,
        ]);
      }
    }
    else{
      $cons_code=eci_decrypt($cons_code);
      $pslist = DB::table('poll_booths')
                        ->where('state_id', $state_id)
                        ->where('dist_code', $dist_code)
                        ->where('cons_code', $cons_code)->get();
      foreach ($pslist as $psid) {
        $ps_id[] = eci_encrypt($psid->ps_id);
      }
      if($request->ajax()){
        $statusResult[] = array(
          'statusResponce'  => '1'
        );
        return response()->json([
          'pslist' => $pslist,
          'ps_id' => $ps_id,
          'statusResult' => $statusResult,
        ]);
      }
    }
  }

    //--View Electoral Rolls
  public function electoralRollsSubmit(Request $request) {
    $this->validate(
      $request, [
        'ps_id' => 'required',
          'cons_code' => 'required',
      ],
      [
        'ps_id.required' => 'Please select Polling Station',
        'cons_code.required' => 'Please select Constituency',
      ]
    );
    $user = Auth::user();
    $dist_code = Auth::user()->dist_code;
    $state_id = Auth::user()->state_id;
    $psId=$request->ps_id;
    $psId=eci_decrypt($psId);
    $consRo = $request->cons_code;
    $cons_code=eci_decrypt($consRo);
    $constituency = DB::table('constituencies')
                  ->where('dist_code', $dist_code)
                  ->where('state_id', $state_id)
                  ->get();
    $pslist = DB::table('poll_booths')
                ->where('state_id', $state_id)
                ->where('dist_code', $dist_code)
                ->where('cons_code', $cons_code)
                ->get();

    $encPsId=$request->ps_id;
    $encConsCode=$request->cons_code;
    $votersList = DB::table('voters')
                ->where('dist_code', $dist_code)
                ->where('cons_code', $cons_code)
                ->where('state_id', $state_id)
                ->where('ps_id', $psId)
                ->orderby('slnoinpart')
                ->get();

    if($votersList->count()>=1){
    }else{
      $votersListAPI = app('App\Http\Controllers\CronjobController')->get_voter_list($state_id,$dist_code,$cons_code,$psId);
          //dd($votersListAPI);
      $votersList = json_decode($votersListAPI);
    }
    return view('deo/electoral-rolls', [
      'votersList' => $votersList,
      'constituency' => $constituency,
      'pslist' => $pslist,
      'encPsId' => $encPsId,
      'encConsCode' => $encConsCode,
    ]);
  }

    public function addElectoral(){
      $user = Auth::user();
      return view('deo/add-electoral-rolls');
    }

    public function addElectoralSub(Request $request){
      $user = Auth::user();
      $this->stateElec=get_state_id();
      $this->distElec=Auth::user()->dist_code;
      Excel::load(Input::file('electoralRollCsv'), function ($reader) { 
        $abc=2;
        $icardErr=array();
        $emptyErrElc=array();
        foreach ($reader->toArray() as $row) {
          $elecState=$this->stateElec;
          $elecDist=$this->distElec;
          $consCodeElec=(isset($row['ac_no']))? $row['ac_no'] : "";
          $consNameElec=$row['ac_name_en'];
          $acPartNo=$row['part_no'];
          $acPartName=$row['part_name_en'];
          $slnoinpart=$row['slnoinpart'];
          $houseNo=$row['house_no'];
          $sectionNo=$row['section_no'];
          $sectionName=$row['section_name_en'];
          $idcardNo=$row['idcard_no'];
          $sex=$row['sex'];
          $dob=$row['dob'];
          $age=$row['age'];
          $fName=$row['fm_nameen'];
          $lName=$row['lastnameen'];
          $rlnType=$row['rln_type'];
          $rlnFname=$row['rln_fm_nmen'];
          $rlnLname=$row['rln_l_nmen'];
          $mobileno=$row['mobileno'];
          if($consCodeElec!==""){
            if(!empty($fName) && !empty($idcardNo)){
              $voterRep = DB::table('voters')->where('idcardNo', $idcardNo)->get();
              $countIcard=count($voterRep);
              if($countIcard==0){
                $addobserver = array(
                  'state_id' => $elecState,
                  'dist_code' => $elecDist,
                  'cons_code' => $consCodeElec,
                  'cons_name' => $consNameElec,
                  'ps_id' => $acPartNo,
                  'part_name' => $acPartName,
                  'slnoinpart' => $slnoinpart,
                  'house_no' => $houseNo,
                  'section_no' => $sectionNo,
                  'section_name' => $sectionName,
                  'idcardNo' => $idcardNo,
                  'sex' => $sex,
                  'dob' => $dob,
                  'age' => $age,
                  'fm_nameEn' => $fName,
                  'LastNameEn' => $lName,
                  'rlnType' => $rlnType,
                  'rln_Fm_NmEn' => $rlnFname,
                  'rln_L_NmEn' => $rlnLname,
                  'mobileno' => $mobileno,
                );  
                $addObs = DB::table('voters')->insert($addobserver);
              }else{
                $icardErr[]=$abc;
              }
            }else{
              $emptyErrElc[]=$abc;
            }
          }
        $abc++;
        }

        //--Both Fields Rewuired
        $emptyErrElc1 = array_filter($emptyErrElc);
        if (!empty($emptyErrElc1)) {
          $emptyelecVal1=implode(',', $emptyErrElc1);
          $empErr="on row ".$emptyelecVal1;
        }else{
          $empErr="";
        }
        if(!empty($empErr)){
          Session::flash('empElecErr', 'First name or id-card number is missing '.$empErr); 
          Session::flash('alert-class', 'alert-danger');
        }

        //--Icard no repeat
        $icardErr1 = array_filter($icardErr);
        if (!empty($icardErr1)) {
          $valIcardErr1=implode(',', $icardErr1);
          $iCardMsz="on row ".$valIcardErr1;
        }else{
          $iCardMsz="";
        }
        if(!empty($iCardMsz)){
          Session::flash('iCardElecErr', 'Icard number is already used '.$iCardMsz); 
          Session::flash('alert-class', 'alert-danger');
        }
      });
      Session::flash('voterMsz', 'Electoral Added Successfully.'); 
      Session::flash('alert-class', 'alert-danger');
      return Redirect::to('deo/electoral-rolls');
    }

    public function evmVvpat(){
        $user = Auth::user();
        $first_evm_date = Config::get('constants.FIRST_RANDOMIZATION_EVM_DATE');
        $time_now=mktime(date('h')+5,date('i')+30,date('s'));
        $current_date = date('Y-m-d', $time_now);
        if (strtotime($first_evm_date) > strtotime($current_date)) {
            \Session::flash('message', 'Too be announced soon.');
            return view('deo/evm-vvpat');
        }
        else{
            $second_evm_date = Config::get('constants.SECOND_RANDOMIZATION_EVM_DATE');
            if (strtotime($second_evm_date) > strtotime($current_date)) {
                $visibile = 0;
            }
            else{
                $visibile = 1;
            }
            $getConst = DB::table('constituencies')
                      ->where('dist_code',$user->dist_code)
                      ->get();

            $getConstFirst = DB::table('constituencies')
                      ->where('dist_code',$user->dist_code)
                      ->first();

            $firstConsCode=$getConstFirst->cons_code;
            $encryptCons=eci_encrypt($getConstFirst->cons_code);
            $selectedRand=eci_encrypt('1');
            $getfirstrandomisation = DB::table('randomization_evm_first')
                                   ->join('constituencies', 'randomization_evm_first.cons_code','constituencies.cons_code')
                                   ->where('randomization_evm_first.dist_code', $user->dist_code)
                                   ->where('randomization_evm_first.cons_code', $firstConsCode)
                                   ->get();
          
            return view('deo/evm-vvpat', [
              'visibile' => $visibile,
              'getConst' => $getConst,
              'getfirstrandomisation' => $getfirstrandomisation,
              'encryptCons' => $encryptCons,
              'selectedRand' => $selectedRand,
            ]);
        }
    }

    public function searchevmVvpat(Request $request){
        $user = Auth::user();
        $this->validate(
        $request, [
          'rand_id' => 'required',
          'cons_code' => 'required',
        ],
        [
          'rand_id.required' => 'Please select randomization',
          'cons_code.required' => 'Please select constituency',
        ]
        );
        $cons_code = eci_decrypt($request->cons_code);
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
        $getConst = DB::table('constituencies')->where('dist_code',$user->dist_code)->get();
        if($rand_id == 1){
            $getfirstrandomisation = DB::table('randomization_evm_first')
                                   ->join('constituencies', 'randomization_evm_first.cons_code','constituencies.cons_code')
                                   ->where('randomization_evm_first.dist_code', $user->dist_code)
                                   ->where('randomization_evm_first.cons_code', $cons_code)
                                   ->get();

            return view('deo/search-evm-vvpat', [
              'visibile' => $visibile,
              'getConst' => $getConst,
              'getfirstrandomisation' => $getfirstrandomisation,
            ]);
        }
        else{
            $getsecondrandomisation = DB::table('randomization_evm_second')
                                    ->join('constituencies', 'randomization_evm_second.cons_code','constituencies.cons_code')
                                    ->join('poll_booths', 'randomization_evm_second.bid','poll_booths.bid')
                                    ->where('randomization_evm_second.dist_code', $user->dist_code)
                                    ->where('randomization_evm_second.cons_code', $cons_code)
                                    ->get();

            return view('deo/evm-vvpatII', [
              'visibile' => $visibile,
              'getConst' => $getConst,
              'getsecondrandomisation' => $getsecondrandomisation,
            ]);
        }
    }

    public function addevmVvpat(){
      return view('deo/add-evm_vvpat');
    }

    public function randomFirstSub(Request $request){
      $user = Auth::user();
      Session::put('selectRand', $request->selectRand);
      $this->validate(
      $request, [
        'firstRandomization' => 'required|mimes:csv,txt',
      ],
      [
        'firstRandomization.required' => 'This field is required',
        'firstRandomization.mimes' => 'Please add a CSV file',
      ]
      );
      $this->stateRandom=Auth::user()->state_id;
      $this->distRandom=Auth::user()->dist_code;

      Excel::load(Input::file('firstRandomization'), function ($reader) {
        $errempty=array();
        $errRepeat=array();
        $xyz=2;
        foreach ($reader->toArray() as $row) {
          $stateFirstRandom=$this->stateRandom;
          $distFirstRandom=$this->distRandom;

          $consCode=trim($row['ac_no']);
          $unitType1=trim($row['unit_type']);
          $unitType=strtoupper($unitType1);
          $unitId=trim($row['unit_id']);
          $manufacturer=trim($row['manufacturer']);
          $boxNo=trim($row['box_no']);
          $status=trim($row['status']);

          if(!empty($consCode) && !empty($unitType) && !empty($unitId)){
            $checkRandRepeat = DB::table('randomization_evm_first')->where('unit_type', $unitType)->where('unit_id', $unitId)->first();
            if(!empty($checkRandRepeat)){
              $errRepeat[]=$xyz;
            }
            else{
              $addFirstRand = array(
                'state_id' => $stateFirstRandom,
                'dist_code' => $distFirstRandom,
                'cons_code' => $consCode,
                'unit_type' => $unitType,
                'unit_id' => $unitId,
                'manufacturer' => $manufacturer,
                'box_no' => $boxNo,
                'role' => $status,
              );  
              $firstRand = DB::table('randomization_evm_first')->insert($addFirstRand);
            }
          }
          else {
            $errempty[]=$xyz;
          }
        $xyz++;
        }

        //--Empty Error
        $emptyErr1 = array_filter($errempty);
        if (!empty($emptyErr1)) {
          $valemptyErr1=implode(',', $emptyErr1);
          $emptyMsz=$valemptyErr1;
        }else{
          $emptyMsz="";
        }
        if(!empty($emptyMsz)){
          Session::flash('emptyRandMsz', 'Acc number, Unit type or Unit id is missing on row '.$emptyMsz); 
          Session::flash('alert-class', 'alert-danger');
        }

        //--Empty Error
        $repeatErr1 = array_filter($errRepeat);
        if (!empty($repeatErr1)) {
          $valrepeatErr1=implode(',', $repeatErr1);
          $repeatMsz=$valrepeatErr1;
        }else{
          $repeatMsz="";
        }
        if(!empty($repeatMsz)){
          Session::flash('repeatRandMsz', 'Unit id already used on row '.$repeatMsz); 
          Session::flash('alert-class', 'alert-danger');
        }
      });
      Session::flash('firstRandSuccess', 'Randomization Added Successfully.'); 
      Session::flash('alert-class', 'alert-success');
      return Redirect::to('deo/evm-vvpat');
    }


    public function randomSecondSub(Request $request){
      $user = Auth::user();
      Session::put('selectRand', $request->selectRand);
      $this->validate(
      $request, [
        'secondRandomization' => 'required|mimes:csv,txt',
      ],
      [
        'secondRandomization.required' => 'This field is required',
        'secondRandomization.mimes' => 'Please add a CSV file',
      ]
      );
      $this->stateRandomSecond=Auth::user()->state_id;
      $this->distRandomSecond=Auth::user()->dist_code;
      Excel::load(Input::file('secondRandomization'), function ($reader) {
        $jkl=2;
        $errEmpty=array();
        foreach ($reader->toArray() as $row) {
          $consRand=trim($row['ac_no']);
          $psNoRand=trim($row['ps_no']);
          $locationRand=trim($row['locn_type']);
          $cuRand=trim($row['cu']);
          $bu1Rand=trim($row['bu1']);
          $bu2Rand=trim($row['bu2']);
          $bu3Rand=trim($row['bu3']);
          $bu4Rand=trim($row['bu4']);
          $vvpatRand=trim($row['vvpat']);
          $randSecondState=$this->stateRandomSecond;
          $randSecondDist=$this->distRandomSecond;

          //--BID
          $consThree = str_pad($consRand, 3, '0', STR_PAD_LEFT);
          $psNumThree = str_pad($psNoRand, 3, '0', STR_PAD_LEFT);
          $bidSecondRand=$randSecondDist.$consThree.$psNumThree;

          if(!empty($consRand) && !empty($psNoRand) && !empty($cuRand) && !empty($bu1Rand)){
            $secondRandRepeat = DB::table('randomization_evm_second')
                                ->where('bid', $bidSecondRand)
                                ->first();
            if(!empty($secondRandRepeat)){
              $upSecondRand = array(
                'loc_type' => $locationRand,
                'cu' => $cuRand,
                'bu1' => $bu1Rand,
                'bu2' => $bu2Rand,
                'bu3' => $bu3Rand,
                'bu4' => $bu4Rand,
                'vvpat' => $vvpatRand,
              );
              $updateSecondRand = DB::table('randomization_evm_second')
                                  ->where('bid', $bidSecondRand)
                                  ->update($upSecondRand);
            }
            else {
              $addScndRand = array(
                'state_id' => $randSecondState,
                'dist_code' => $randSecondDist,
                'cons_code' => $consRand,
                'ps_id' => $psNoRand,
                'bid' => $bidSecondRand,
                'loc_type' => $locationRand,
                'cu' => $cuRand,
                'bu1' => $bu1Rand,
                'bu2' => $bu2Rand,
                'bu3' => $bu3Rand,
                'bu4' => $bu4Rand,
                'vvpat' => $vvpatRand,
              );  
              $addSecondRand = DB::table('randomization_evm_second')
                              ->insert($addScndRand);
            }
          }
          else{
            $errEmpty[]=$jkl;
          }
        $jkl++;
        }

        //--Empty Error
        $emptyErr1 = array_filter($errEmpty);
        if (!empty($emptyErr1)) {
          $valemptyErr1=implode(',', $emptyErr1);
          $emptyMsz=$valemptyErr1;
        }else{
          $emptyMsz="";
        }
        if(!empty($emptyMsz)){
          Session::flash('emptySecondRandMsz', 'Acc-num, PS-no., CU or BU1 is missing on row '.$repeatMsz); 
        }
      });
      Session::flash('firstRandSuccess', 'Randomization Added Successfully.'); 
      Session::flash('alert-class', 'alert-success');
      return Redirect::to('deo/evm-vvpat');
    }


    public function politicalPartiesDistrictHead(){
      $user = Auth::user();
      $stateCode=Auth::user()->state_id;
      $distCode=Auth::user()->dist_code;
      $polPartyList = DB::table('users')
                      ->join('political_parties', 'users.ppid', '=', 'political_parties.ppid')
                      ->where('state_id', $stateCode)
                      ->where('dist_code', $distCode)
                      ->where('role', '9')
                      ->get();
      $allPolParty = DB::table('political_parties')->get();
      return view('deo/political-parties-district-head', [
        'polPartyList' => $polPartyList,
        'allPolParty' => $allPolParty,
      ]);
    }



    public function addPoliticalPartiesDistrictHead(){
      $user = Auth::user();
      $polParty = DB::table('political_parties')->get();
      return view('deo/add-political-parties-district-head', [
      'polParty' => $polParty,
      ]);
    }

    
    public function addPolPartDistHeadSubmit(Request $request){
      $user = Auth::user();
      $stateCode=Auth::user()->state_id;
      $distCode=Auth::user()->dist_code;
      $dt = Carbon::now();
      $timestamp=$dt->toDateString();

      $this->validate($request, [
        'distHeadName' => 'required',
        'primaryMob' => 'required|size:10',
        'partyName' => 'required',
        'secondaryMob' => 'size:10',
        'officeNumber' => 'size:10',
        'ppEmail' => 'email',
      ]);
      $ppidDcrypt=eci_decrypt($request->partyName);
      $uidPPdistHead="PDH".($request->primaryMob);
      $checkRepeat= DB::table('users')->where('uid', $uidPPdistHead)->where('role', '9')->get();
      $countRepeat=count($checkRepeat);
      if($countRepeat>0){
        Session::flash('polDistHeadMsz', 'Phone number is Already used.'); 
        Session::flash('alert-class', 'alert-danger');
        return Redirect::to('deo/add-political-parties-district-head');
      }
      else{
        $addPP = array(
          'uid' => $uidPPdistHead,
          'role' => '9',
          'ppid' => $ppidDcrypt,
          'office_address' => $request->officeAddress,
          'office_phone' => $request->officeNumber,
          'name' => $request->distHeadName,
          'phone' => $request->primaryMob,
          'sphone' => $request->secondaryMob,
          'email' => $request->ppEmail,
          'state_id' => $stateCode,
          'dist_code' => $distCode,
          'password' => '',
          'address' => '',
          'updated_at' => $timestamp,
        ); 
        $addppDistHead = DB::table('users')->insert($addPP);
        if($addppDistHead>0){
          Session::flash('polDistHeadSucc', 'District head added successfully.'); 
          Session::flash('alert-class', 'alert-success');
          return Redirect::to('deo/political-parties-district-head');
        }else{
          Session::flash('polDistHeadMsz', 'Please try again.'); 
          Session::flash('alert-class', 'alert-danger');
          return Redirect::to('deo/add-political-parties-district-head');
        }
      }
    }


    public function editPPdistHead($uid){
      $uidDcrypt=eci_decrypt($uid);
      $polPartyDetail = DB::table('users')
                      ->where('role', '9')
                      ->where('uid', $uidDcrypt)
                      ->first();
      $polParty=DB::table('political_parties')->get();


      return view('deo/edit-political-parties-district-head', [
        'polPartyDetail' => $polPartyDetail,
        'polParty' => $polParty,
      ]);
    }


    public function editPPdistHeadSubmit(Request $request){
      $user = Auth::user();
      $stateCode=Auth::user()->state_id;
      $distCode=Auth::user()->dist_code;
      $dt = Carbon::now();
      $timestamp=$dt->toDateString();

      $this->validate($request, [
        'editDistHeadName' => 'required',
        'editPrimaryMob' => 'required|size:10',
        'editPartyName' => 'required',
        'editSecondaryMob' => 'size:10',
        'editOfficeNumber' => 'size:10',
        'editPpEmail' => 'email',
      ]);
      $ppidDcryptEdit=eci_decrypt($request->editPartyName);
      $uidPPdistHeadEdit="PDH".($request->oldPrimaryMob);
      $uidEnc=eci_encrypt($uidPPdistHeadEdit);

      $uidPPdistHeadNew="PDH".($request->editPrimaryMob);
      $oldMobPrimary=$request->oldPrimaryMob;
      $newMobPrimary=$request->editPrimaryMob;

      if($oldMobPrimary==$newMobPrimary){
        $upPPdistHead = array(
          'ppid' => $ppidDcryptEdit,
          'office_address' => $request->editOfficeAddress,
          'office_phone' => $request->editOfficeNumber,
          'name' => $request->editDistHeadName,
          'sphone' => $request->editSecondaryMob,
          'email' => $request->editPpEmail,
          'updated_at' => $timestamp,
        );
        $updatePPdistHead = DB::table('users')
                            ->where('uid', $uidPPdistHeadEdit)
                            ->where('state_id', $stateCode)
                            ->where('dist_code', $distCode)
                            ->where('role', '9')
                            ->update($upPPdistHead);

        if($updatePPdistHead!==""){
          Session::flash('polDistHeadSucc', 'District head Updated successfully.'); 
          Session::flash('alert-class', 'alert-success');
          return Redirect::to('deo/political-parties-district-head');
        }else{
          Session::flash('editPolDistHeadMsz', 'Please try again.'); 
          Session::flash('alert-class', 'alert-danger');
          return Redirect::to('deo/editPPdistHead/'.$uidEnc);
        }
      }
      else {
        $repeatPhone=DB::table('users')
                      ->where('uid', $uidPPdistHeadNew)
                      ->where('state_id', $stateCode)
                      ->where('dist_code', $distCode)
                      ->where('role', '9')
                      ->get();
        $getCount=count($repeatPhone);

        if($getCount>0){
          Session::flash('editPolDistHeadMsz', 'Phone number is Already used.'); 
          Session::flash('alert-class', 'alert-danger');
          return Redirect::to('deo/editPPdistHead/'.$uidEnc);
        }
        else{
          $upDistHead = array(
            'uid' => $uidPPdistHeadNew,
            'phone' => $request->editPrimaryMob,
            'ppid' => $ppidDcryptEdit,
            'office_address' => $request->editOfficeAddress,
            'office_phone' => $request->editOfficeNumber,
            'name' => $request->editDistHeadName,
            'sphone' => $request->editSecondaryMob,
            'email' => $request->editPpEmail,
            'updated_at' => $timestamp,
          );
          $updateDistHead = DB::table('users')
                              ->where('uid', $uidPPdistHeadEdit)
                              ->where('state_id', $stateCode)
                              ->where('dist_code', $distCode)
                              ->where('role', '9')
                              ->update($upDistHead);

          if($updateDistHead!==""){
            Session::flash('polDistHeadSucc', 'District head Updated successfully.'); 
            Session::flash('alert-class', 'alert-success');
            return Redirect::to('deo/political-parties-district-head');
          }else{
            Session::flash('editPolDistHeadMsz', 'Please try again.'); 
            Session::flash('alert-class', 'alert-danger');
            return Redirect::to('deo/editPPdistHead/'.$uidEnc);
          }
        }
      }
    }

    public function delPPdistHead($uid){
      $user = Auth::user();
      $uidDcryp=eci_decrypt($uid);
      $stateCode=Auth::user()->state_id;
      $distCode=Auth::user()->dist_code;

      $delPPdistHead=DB::table('users')
                    ->where('uid', $uidDcryp)
                    ->where('state_id', $stateCode)
                    ->where('dist_code', $distCode)
                    ->where('role', '9')
                    ->delete();
      if($delPPdistHead>0){
        Session::flash('polDistHeadSucc', 'District head deleted successfully.'); 
        Session::flash('alert-class', 'alert-success');
        return Redirect::to('deo/political-parties-district-head');
      }else{
        Session::flash('polDistHeadSucc', 'Please try again.'); 
        Session::flash('alert-class', 'alert-danger');
        return Redirect::to('deo/political-parties-district-head');
      }
    }

    public function voterDetail($iCard){
        $user = Auth::user();
        $iCardNo=eci_decrypt($iCard);
        $voterDetail = voter_details($iCardNo);
        if(@$voterDetail){
            $voterDetail = json_decode($voterDetail);
            $pollDayDetail = poll_booth_details($voterDetail->state_id, $voterDetail->dist_code, $voterDetail->cons_code, $voterDetail->ps_id);
            $pollDayDetail = json_decode($pollDayDetail);
        }else{
            $voterDetail = array();
        }
        return view('deo/voter-detail', [
           'voterDetail' => $voterDetail,
           'pollDayDetail' => $pollDayDetail,
        ]);
        // $voterDetail = json_decode(voter_details($iCardNo));
        // if(@$voterDetail->Fm_NameEn){
        //     $voterDetail->fm_nameEn = $voterDetail->Fm_NameEn;
        //     $voterDetail->LastNameEn = $voterDetail->LastNameEn;
        //     $voterDetail->dob = date("F d, Y",strtotime($voterDetail->DOB));
        //     $voterDetail->age = $voterDetail->AGE;
        //     $voterDetail->cons_code = $voterDetail->AC_NO;
        //     $cons = DB::table('constituencies')
        //                 ->where('cons_code',$voterDetail->AC_NO) 
        //                 ->where('dist_code',$user->dist_code)
        //                 ->first(); 
        //     $voterDetail->cons_name = $cons->cons_name;
            
        //     $cons = DB::table('poll_booths')
        //                 ->where('cons_code',$voterDetail->AC_NO) 
        //                 ->where('dist_code',$user->dist_code)
        //                 ->where('ps_id',$user->PART_NO)
        //                 ->first(); 
        //     //dd($voterDetail);

        // }
        // // dd($voterDetail);
        // // dd(voter_details($iCardNo));
        // // $voterDetail = DB::table('voters') 
        // //                ->join('constituencies', function($join) { $join->on('voters.cons_code', '=', 'constituencies.cons_code')->on('voters.state_id', '=', 'constituencies.state_id')->on('voters.dist_code', '=', 'constituencies.dist_code'); })->where('idcardNo', $iCardNo)->first();
        // return view('deo/voter-detail', [
        //    'voterDetail' => $voterDetail,
        // ]);
    }


    public function electionObservers() {
        $user = Auth::user();
        $state_id = $user->state_id;
        $distCode= $user->dist_code;
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

        return view('deo/election-observers', [
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
         return view('deo/observer-profile', [
           'observerdata' => $observerdata,
        ]);
    }


  //--Add New Observer
    public function addNewObserver() {
      $user = Auth::user();
      return view('deo/add-new-observer');
    }


  //--Add New Observer--(Form Submit)
    public function addObserverSubmit(Request $request) {
      $user = Auth::user();
      $this->validate($request, [
          'obsName' => 'required',
          'obsEmail' => 'required|email',
          'obsPhone' => 'required|size:10',
          'obsPic' => 'required',
          'obAddress' => 'required',
          'obType' => 'required',
      ]);
      $dt = Carbon::now();
      $timestamp=$dt->toDateString();
      $dist_code=Auth::user()->dist_code;
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
      $addobserver = array(
          'name' => $request->obsName,
          'email' => $request->obsEmail,
          'phone' => $request->obsPhone,
          'type' => $request->obType,
          'address' => $request->obAddress,
          'profile_image' => $picObs,
          'uid' => $uidObs,
          'dist_code' => $dist_code,
          'updated_at' => $timestamp,
      );  
      $addObs = DB::table('observer')->insert($addobserver);
      if($addObs>0) {
        Session::flash('obsSuccess', 'Observer Added Successfully.'); 
        Session::flash('alert-class', 'alert-danger');
        return Redirect::to('deo/election-observers');
      }
    }

  //--Edit Observer
    public function editObserver($uid) {
      $uidDcrypt=eci_decrypt($uid);
      $editObs = DB::table('observer')->where('uid', $uidDcrypt)->first();
      return view('deo/edit-observer', [
         'editObs' => $editObs,
      ]);
    }

  //--Update Observer
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
        ]);
      }else{
        $this->validate($request, [
            'obsNameEdit' => 'required',
            'obsEmailEdit' => 'required|email',
            'obsPhoneEdit' => 'required|size:10',
            'obAddressEdit' => 'required',
            'obTypeEdit' => 'required',
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
        'type' => $request->obTypeEdit,
        'address' => $request->obAddressEdit,
        'profile_image' => $proPicObs,
        'uid' => $uidObsEdit,
        'updated_at' => $timestamp,
      );

      $upObserver = DB::table('observer')->where('uid', $oldUidDcrypt)->update($upObs);
      if($upObserver!=="") {
        Session::flash('obsSuccess', 'Observer Updated Successfully.'); 
        Session::flash('alert-class', 'alert-danger');
        return Redirect::to('deo/election-observers');
      }
    }


  //--Booth Awareness Groups List
    public function boothAwareList() {
      $user = Auth::user();
      $distCode = Auth::user()->dist_code;

      $boothAwareList = DB::table('booth_awareness_groups')
                      ->join('districts', 'booth_awareness_groups.dist_code', '=', 'districts.dist_code')
                      ->join('constituencies', 'booth_awareness_groups.cons_code', '=', 'constituencies.cons_code')
                      ->where('booth_awareness_groups.dist_code',$distCode)
                      ->get();
      return view('deo/booth-aware-list', [
         'boothAwareList' => $boothAwareList,
      ]);
    }

  //--Add Booth Awareness group
    public function addBoothAware() {
      return view('deo/add-booth-aware');
    }


  //--Add Booth Awareness group--(Form Submit)
    public function addBoothAwareSub(Request $request) {
      $this->validate(
      $request, [
        'boothAwareName' => 'required',
        'boothAwarePhone' => 'required|size:10',
        'boothAwareAddress' => 'required',
        'boothAwarePollNum' => 'required|max:3',
        'boothAwareCons' => 'required',
      ],
      [
        'boothAwareName.required' => 'Please add name of group',
        'boothAwarePhone.required' => 'Please add phone number',
        'boothAwarePhone.size' => 'Phone number must be 10 characters',
        'boothAwareAddress.required' => 'Please add address of group',
        'boothAwarePollNum.required' => 'Please add Polling Station Number',
        'boothAwarePollNum.max' => 'Polling Station Number may not be greater than 3 characters',
        'boothAwareCons.required' => 'Please select constituency',
      ]
      );
      $user = Auth::user();
      $state=Auth::user()->state_id;
      $dist = Auth::user()->dist_code;
      $consCode=$request->boothAwareCons;
      $pollNum=$request->boothAwarePollNum;
      $mobileBoothAware=$request->boothAwarePhone;

    //--Trimmed Cons Code
      $consCodeT=trim($consCode);
      $consCodeThree = str_pad($consCodeT, 3, '0', STR_PAD_LEFT);
      
    //--Trimmed Dist Code
      $distTrim=trim($dist);
      
    //--Trimmed Booth Number
      $pollNumT=trim($pollNum);
      $pollNumThree = str_pad($pollNumT, 3, '0', STR_PAD_LEFT);
     
    //--BID
      $bidBoothAware=$distTrim.$consCodeThree.$pollNumThree;

      $chkRepeat = DB::table('booth_awareness_groups')->where('bid',$bidBoothAware)->where('dist_code',$distTrim)->where('phone',$mobileBoothAware)->where('cons_code',$consCodeT)->get();
      $countRepeat=count($chkRepeat);
      if($countRepeat>0){
        $upBoothAware = array(
          'name' => $request->boothAwareName,
          'designation' => $request->boothAwareDesig,
          'organisation' => $request->boothAwareOrg,
          'address' => $request->boothAwareAddress,
        );
        $upBoothAws = DB::table('booth_awareness_groups')->where('bid',$bidBoothAware)->where('dist_code',$distTrim)->where('phone',$mobileBoothAware)->where('cons_code',$consCodeT)->update($upBoothAware);
        if($upBoothAws>0){
          Session::flash('boothAware', 'Group Updated Successfully.'); 
          Session::flash('alert-class', 'alert-success');
          return Redirect::to('deo/booth-aware-list');
        }
        else{
          Session::flash('boothAwareErr', 'Please Try Again.'); 
          Session::flash('alert-class', 'alert-danger');
          return Redirect::to('deo/add-booth-aware');
        }
      }
      else{
        $addGroup = array(
          'name' => $request->boothAwareName,
          'designation' => $request->boothAwareDesig,
          'organisation' => $request->boothAwareOrg,
          'address' => $request->boothAwareAddress,
          'phone' => $mobileBoothAware,
          'state_id' => $state,
          'dist_code' => $distTrim,
          'cons_code' => $consCodeT,
          'ps_id' => $request->boothAwarePollNum,
          'bid' => $bidBoothAware,
        );  
        $addAwareGroup = DB::table('booth_awareness_groups')->insert($addGroup);
        if($addAwareGroup>0){
          Session::flash('boothAware', 'Group Added Successfully.'); 
          Session::flash('alert-class', 'alert-danger');
          return Redirect::to('deo/booth-aware-list');
        }
        else{
          Session::flash('boothAwareErr', 'Please Try Again.'); 
          Session::flash('alert-class', 'alert-danger');
          return Redirect::to('deo/add-booth-aware');
        }
      }
    }

  //-- Import CSV booth aware
    public function boothAwareCsv() {
      return view('deo/booth-aware-csv');
    }

  //-- Import CSV booth aware--(Form Submit)
    public function boothAwareCsvSub(Request $request) {
      $this->validate(
      $request, [
        'boothAwareCsv' => 'required|mimes:csv,txt',
      ],
      [
        'boothAwareCsv.required' => 'This field is required',
        'boothAwareCsv.mimes' => 'Please add a CSV file',
      ]
      );
      $user = Auth::user();
      $this->state=Auth::user()->state_id;
      $this->dist =Auth::user()->dist_code;

      Excel::load(Input::file('boothAwareCsv'), function ($reader) { 
        foreach ($reader->toArray() as $row) {
          $stateIdBoothAw=$this->state;
          $distCodeBoothAw=$this->dist;
          $accNoBoothAw=$row['ac_no'];
          $partNoBoothAw=$row['part_no'];
          $mobNoBoothAw=$row['mobile_no'];
          $nameBoothAw=$row['name'];
          $desgBoothAw=$row['designation'];
          $addrBoothAw=$row['address'];
          $orgBoothAw=$row['organisation'];

        //--Trimmed Cons Code
          $accNoT=trim($accNoBoothAw);
          $accNoThree = str_pad($accNoT, 3, '0', STR_PAD_LEFT);
          
        //--Trimmed Dist Code
          $distfinal=trim($distCodeBoothAw);
          
        //--Trimmed Booth Number
          $partNoT=trim($partNoBoothAw);
          $partNoThree = str_pad($partNoT, 3, '0', STR_PAD_LEFT);
         
        //--BID
          $bidBoothAwareCsv=$distfinal.$accNoThree.$partNoThree;
          $chkRepeatCsv = DB::table('booth_awareness_groups')
                        ->where('bid',$bidBoothAwareCsv)
                        ->where('dist_code',$distfinal)
                        ->where('phone',$mobNoBoothAw)
                        ->where('cons_code',$accNoT)
                        ->get();

          $countRepeatCsv=count($chkRepeatCsv);
          if($countRepeatCsv>0){
            $upBoothcsv = array(
              'name' => $nameBoothAw,
              'designation' => $desgBoothAw,
              'organisation' => $orgBoothAw,
              'address' => $addrBoothAw,
            );
            $upBoothCsv = DB::table('booth_awareness_groups')
                        ->where('bid',$bidBoothAwareCsv)
                        ->where('dist_code',$distfinal)
                        ->where('phone',$mobNoBoothAw)
                        ->where('cons_code',$accNoT)
                        ->update($upBoothcsv);
          }
          else {
            $addGroup = array(
              'name' => $nameBoothAw,
              'designation' => $desgBoothAw,
              'organisation' => $orgBoothAw,
              'address' => $addrBoothAw,
              'phone' => $mobNoBoothAw,
              'state_id' => $stateIdBoothAw,
              'dist_code' => $distCodeBoothAw,
              'cons_code' => $accNoBoothAw,
              'ps_id' => $partNoBoothAw,
              'bid' => $bidBoothAwareCsv,
            );
            $addAwareCsv = DB::table('booth_awareness_groups')
                         ->insert($addGroup);
          }
        }
        Session::flash('boothAware', 'Group Added Successfully.'); 
        Session::flash('alert-class', 'alert-success');
        return Redirect::to('deo/booth-aware-list');
      });
    }


  //-- Polling Stations
    public function pollingStations() {
      $user = Auth::user();
      $distCode =Auth::user()->dist_code;
      $constituency=DB::table('constituencies')
                    ->where('dist_code', $distCode)
                    ->get();

      $constituencyFirst=DB::table('constituencies')
                    ->where('dist_code', $distCode)
                    ->first();


      $firstCons=$constituencyFirst->cons_code;
      $encryptCons=eci_encrypt($constituencyFirst->cons_code);

      $pollStation = DB::table('users')
                    ->join('poll_booths', 'users.uid', '=', 'poll_booths.supervisior_uid')
                    ->join('constituencies', 'users.cons_code', '=', 'constituencies.cons_code')
                    ->where('users.dist_code', $distCode)
                    ->where('users.cons_code', $firstCons)
                    ->get();
          
      return view('deo/polling-stations', [
        'constituency' => $constituency,
        'encryptCons' => $encryptCons,
        'pollStation' => $pollStation,
      ]);
    }


    public function consPollStationSub(Request $request) {
      $this->validate(
      $request, [
        'cons_code' => 'required', 
      ],
      [
        'cons_code.required' => 'Please select constituency', 
      ]
      );
      $user = Auth::user();
      $distCode =Auth::user()->dist_code;
      $encryptCons=$request->cons_code;
      $consCode=eci_decrypt($request->cons_code);

      $pollStation = DB::table('users')
                    ->join('poll_booths', 'users.uid', '=', 'poll_booths.supervisior_uid')
                    ->join('constituencies', 'users.cons_code', '=', 'constituencies.cons_code')
                    ->where('users.dist_code', $distCode)
                    ->where('users.cons_code', $consCode)
                    ->get();

      $constituency=DB::table('constituencies')
                    ->where('dist_code', $distCode)
                    ->get();

      return view('deo/polling-stations', [
      'pollStation' => $pollStation,
      'constituency' => $constituency,
      'encryptCons' => $encryptCons,
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
        return view('deo/polling-detail', [
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
        return view('deo/booth-awareness-group', [
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
        //$dist_code = ltrim($dist_code1, '0');
        $cons_code = substr($poll_booth_id,2,3);
        //$cons_code = ltrim($cons_code1, '0')
        $ps_id = substr($poll_booth_id,5,7);
        //$ps_id = ltrim($ps_id1, '0');
        // $polling_detail = DB::table('poll_booths')
        //                    ->where('bid', $poll_booth_id)
        //                    ->first();
        return view('deo/booth-photos', [
           'state_id' => $state_id,
           'dist_code' => $dist_code,
           'cons_code' => $cons_code,
           'ps_id' => $ps_id,
       ]);
    }

  //--Add Polling Station
    public function addPollingStation() {
      $user = Auth::user();
      $stateId=Auth::user()->state_id;
      $distCode=Auth::user()->dist_code;

      $consDetail = DB::table('constituencies')->where('dist_code',$distCode)->get();
      $distDetail = DB::table('districts')->where('dist_code',$distCode)->first();
      return view('deo/add-pollStation', [
        'consDetail' => $consDetail,
        'distDetail' => $distDetail,
      ]);
    }


    public function addPolStationSubmit(Request $request) {
      $user = Auth::user();
      $distCode=Auth::user()->dist_code;
      $this->validate(
      $request, [
        'psConstituency' => 'required',
        'psLatPollStation' => 'required',
        'psLongPollStation' => 'required',
        'psBoothNum' => 'required|max:3',
        'psLocality' => 'required',
        'psPollBuilding' => 'required', 
        'psAreaPollStation' => 'required',
        'psSepEnterExit' => 'required',
        'psPollingArea' => 'required',
        'psVotersType' => 'required', 
        'psTotalVoters' => 'required|max:3',
        'psMaxDistence' => 'required',
      ],
      [
        'psConstituency.required' => 'Please select constituency',
        'psLatPollStation.required' => 'Please add Polling Station Latitude',
        'psLongPollStation.required' => 'Please add Polling Station Longitude',
        'psBoothNum.required' => 'Please add Booth Number',
        'psBoothNum.max' => 'Booth Number may not be greater than 3 characters.',
        'psLocality.required' => 'Please add Locality',
        'psPollBuilding.required' => 'Please add Poll Building',
        'psAreaPollStation.required' => 'Please add Polling Station area',
        'psSepEnterExit.required' => 'Separate Entrance and Exit field is required',
        'psPollingArea.required' => 'Please add Area of Polling Station',
        'psVotersType.required' => 'Whether For field is required',
        'psTotalVoters.required' => 'Please add Total No. Of Voters',
        'psTotalVoters.max' => 'Total No. Of Voters may not be greater than 3 characters.',
        'psMaxDistence.required' => 'Please add Maximum Distence',
      ]
      );
      //--Trimmed Cons Code
      $psConsCode1=eci_decrypt($request->psConstituency);
      $psConsCode2=trim($psConsCode1);
      $psConsCode = str_pad($psConsCode2, 3, '0', STR_PAD_LEFT);
      
      //--Trimmed Booth Number
      $boothNum1=$request->psBoothNum;
      $boothNum2=trim($boothNum1);
      $boothNum = str_pad($boothNum2, 3, '0', STR_PAD_LEFT);
      
      //--BID
      $bid=$distCode.$psConsCode.$boothNum;

      $existBid = DB::table('poll_booths')->where('bid', $bid)->get();
      $existBidCount=count($existBid);
      if($existBidCount>0){
          Session::put('addPollErr', 'Booth Number Is Already Used.');
          return Redirect::to('deo/addPollingStation');
      }else{
          $addPoll = array(
              'booth_no' => $boothNum2,
              'locality' => $request->psLocality,
              'poll_building' => $request->psPollBuilding,
              'area' => $request->psAreaPollStation,
              'separate_entrance' => $request->psSepEnterExit,
              'poll_areas' => $request->psPollingArea,
              'latitude' => $request->psLatPollStation,
              'longitude' => $request->psLongPollStation,
              'voters_type' => $request->psVotersType,
              'total_voters' => $request->psTotalVoters,
              'max_distance' => $request->psMaxDistence,
              'dist_code' => $distCode,
              'cons_code' => $psConsCode2,
              'bid' => $bid,
              'ps_id' => $boothNum2,
              'status' => 1,
              'remarks' => $request->psRemarks, 
          );     
          $addPollStation = DB::table('poll_booths')->insert($addPoll); 
          if($addPollStation>0) {
              Session::flash('addPollSucc', 'Polling Station Added Successfully.'); 
              Session::flash('alert-class', 'alert-success');
              return Redirect::to('deo/pollingStations');
          }else{
              Session::flash('addPollErr', 'Please Try Again'); 
              Session::flash('alert-class', 'alert-danger');
              return Redirect::to('deo/addPollingStation');
          }
      }
    }

    public function polingCsvForm() {
      $user = Auth::user();
      return view('deo/polingCsvForm');
    }

    //--Add Polling Station CSV--(Form Submit)
    public function polStationExcelSubmit(Request $request) {
      $this->validate(
      $request, [
        'psExcelPollStation' => 'required|mimes:csv,txt',
      ],
      [
        'psExcelPollStation.required' => 'This field is required',
        'psExcelPollStation.mimes' => 'Please add a CSV file',
      ]
      );

        function count_digit($number) {
          return strlen($number);
        }
        //--UID
        // $uidSV1=$request->uidSVexcel;
        // $uidSV=eci_decrypt($uidSV1);
        // $this->uidSvExcel=$uidSV;
        // $getSvDetails = DB::table('users')->where('uid', $uidSV)->first();

        //--Dist Code
        $this->distCode=Auth::user()->dist_code;
        Excel::load(Input::file('psExcelPollStation'), function ($reader) {

            //$uidSupervisor=$this->uidSvExcel;
            $distCodeSupervisor=$this->distCode;

            $abc=2;
            $emptyError=array();
            $maxDigitError=array();
            $numericError=array();
            $existingBid=array();
            $existingBidRow=array();
            $maxDigitErrorRow=array();
            $numericErrorRow=array();

            foreach ($reader->toArray() as $row) {
                $consCode=$row['ac_no'];
                $boothNumber=$row['booth_number'];
                $locality=$row['locality'];
                $poll_building=$row['poll_building'];
                $pollStationArea=$row['pollling_station_area'];
                $latitude=$row['latitude'];
                $longitude=$row['longitude'];
                $sepEnterExit=$row['separate_entrance_and_exit'];
                $pollAreas=$row['polling_areas'];
                $voterType=$row['voters_type'];
                $totalVoter=$row['total_voters'];
                $maxDistance=$row['maximum_distance'];
                $remarks=$row['remarks'];

                if(!empty($consCode) && !empty($boothNumber) && !empty($locality) && !empty($poll_building) && !empty($pollStationArea) && !empty($sepEnterExit) && !empty($pollAreas) && !empty($voterType) && !empty($totalVoter) && !empty($maxDistance)){

                    if(is_numeric($boothNumber) && is_numeric($totalVoter)){
            
                        $boothNumberDigits = count_digit($boothNumber);
                        if($boothNumberDigits<=3){

                            //--Trimmed Cons Code
                            $svConsCode1=trim($consCode);
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
                                    'cons_code' => $consCode,
                                    'bid' => $bidSupervisor,
                                    'ps_id' => $boothNumPS2,
                                    'latitude' => $latitude,
                                    'longitude' => $longitude,
                                    //'supervisior_uid' => $uidSupervisor,
                                    'status' => 1,
                                    'remarks' => $remarks,
                                );     
                                $addPollingSv = DB::table('poll_booths')->insert($addPolling); 
                            }
                            else{
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
            return Redirect::to('deo/pollingStations');
        }else{
            Session::flash('addPollSucc', 'Polling station added successfully.'); 
            Session::flash('alert-class', 'alert-success'); 
            return Redirect::to('deo/pollingStations'); 
        } 
    }

    public function constituencySelection() {
      $user = Auth::user();
      $distDeo=Auth::user()->dist_code;
      $stateDeo=Auth::user()->state_id;
      $consSelection = DB::table('constituencies')->where('state_id', $stateDeo)->where('dist_code', $distDeo)->get();
      return view('deo/select-constituency', [
        'consSelection' => $consSelection,
      ]);
    }


  //-- Pol-1 day
    public function polMinus1day(Request $request) {
      $user = Auth::user();
       $this->validate(
      $request, [
        'constituency' => 'required', 
      ],
      [
        'constituency.required' => 'Please select constituency', 
      ]
      );
      $consCode=eci_decrypt($request->constituency);
      $distDeo=Auth::user()->dist_code;

      // $polMinus1day = DB::table('pro_activity_before')->join('poll_booths', 'pro_activity_before.bid', '=', 'poll_booths.bid')->where('pro_activity_before.dist_code', $distDeo)->get();
      $polMinus1day = DB::table('poll_booths')
                            ->leftjoin('pro_activity_before', 'poll_booths.bid', '=', 'pro_activity_before.bid')
                            ->where('poll_booths.dist_code', $distDeo)
                            ->where('poll_booths.cons_code', $consCode)
                            ->select('poll_booths.poll_building', 'pro_activity_before.*')
                            ->get();
      return view('deo/pol-1day', [
      'polMinus1day' => $polMinus1day,
      ]);
    }


  //--Tranings List
    public function traningList() {
      $user = Auth::user();
      $traningList = DB::table('training_deo')->get();
      return view('deo/traning-list', [
      'traningList' => $traningList,
      ]);
    }
    
  //-- Add Traning
    public function addTraning() { 
      $user = Auth::user();
      return view('deo/add-traning');
    }
    
  //-- Add Traning--(Form Submit)
    public function addTraningSub(Request $request) {
      $user = Auth::user();
      $this->validate(
      $request, [
        'traningLabel' => 'required',
        'traningDate' => 'required',
        'traningTimeFrom' => 'required',
        'traningTimeTo' => 'required',
        'traningVenue' => 'required', 
      ],
      [
        'traningLabel.required' => 'Please add label for Training',
        'traningDate.required' => 'Please add Date for Training',
        'traningTimeFrom.required' => 'Please add starting time of Training',
        'traningTimeTo.required' => 'Please add end time of Training',
        'traningVenue.required' => 'Please add venue for Training',
      ]
      );
      $uidDeo=Auth::user()->uid;
      $distCodeDeo = Auth::user()->dist_code;
      $statIdDeo = Auth::user()->state_id;

      $addTraning = array(
        'uid' => $uidDeo,
        'dist_code' => $distCodeDeo,
        'state_id' => $statIdDeo,
        'name' => $request->traningLabel,
        'date' => $request->traningDate,
        'from_time' => $request->traningTimeFrom,
        'to_time' => $request->traningTimeTo,
        'location' => $request->traningVenue,
      );  
      $addTranings = DB::table('training_deo')->insert($addTraning);
      if($addTranings>0){
        Session::flash('traningSucc', 'Traning added successfully.'); 
        Session::flash('alert-class', 'alert-danger');
        return Redirect::to('deo/traning-list');
      }else{
        Session::flash('traningErr', 'Please try again.'); 
        Session::flash('alert-class', 'alert-danger');
        return Redirect::to('deo/add-traning');
      }
    }


  //-- Edit Traning
    public function editTraning($id) {
      $user = Auth::user();
      $idTraning=eci_decrypt($id);

      $traningDetail = DB::table('training_deo')->where('id', $idTraning)->first();
      return view('deo/edit-traning', [
      'traningDetail' => $traningDetail,
      ]);
    }

  //-- Edit Traning--(Form Submit)
    public function editTraningSub(Request $request) {
      $user = Auth::user();
      $this->validate(
      $request, [
        'editTraningLabel' => 'required',
        'editTraningDate' => 'required',
        'editTraningTimeFrom' => 'required',
        'editTraningTimeTo' => 'required',
        'editTraningVenue' => 'required', 
      ],
      [
        'editTraningLabel.required' => 'Please add label for Training',
        'editTraningDate.required' => 'Please add Date for Training',
        'editTraningTimeFrom.required' => 'Please add starting time of Training',
        'editTraningTimeTo.required' => 'Please add end time of Training',
        'editTraningVenue.required' => 'Please add venue for Training',
      ]
      );
      $trId=$request->idTraningHidden;
      $dcryptTraningId=eci_decrypt($trId);
      $upTraning = array(
        'name' => $request->editTraningLabel,
        'date' => $request->editTraningDate,
        'from_time' => $request->editTraningTimeFrom,
        'to_time' => $request->editTraningTimeTo,
        'location' => $request->editTraningVenue,
      );  
      $upTranings = DB::table('training_deo')->where('id', $dcryptTraningId)->update($upTraning);
      if($upTranings!==""){
        Session::flash('traningSucc', 'Traning Updated successfully.'); 
        Session::flash('alert-class', 'alert-success');
        return Redirect::to('deo/traning-list');
      }else{
        Session::flash('traningErrEdit', 'Please try again.'); 
        Session::flash('alert-class', 'alert-danger');
        return Redirect::to('deo/edit-traning/'.$trId);
      }
    }

  //--Delete Traning
    public function deleteTraning(Request $request) {
      $user = Auth::user();
      $post = $request->all();
      $id=$post['id'];
      $idTraningDel=eci_decrypt($id);
      $delTraning=DB::table('training_deo')->where('id', $idTraningDel)->delete();

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


  //--Pre Poll Arrangement
    // public function prePollArrangement() {
    //   $user = Auth::user();
    //   $uidDeo=Auth::user()->uid;
    //   $psWises = DB::table('pre_poll_arrangement_deo')->where('uid',$uidDeo)->where('doc_type',"PSWISE")->get();
    //   $fdPlans = DB::table('pre_poll_arrangement_deo')->where('uid',$uidDeo)->where('doc_type',"FDPLAN")->get();
    //   return view('deo/pre-poll-arrangement', [
    //     'psWises' => $psWises,
    //     'fdPlans' => $fdPlans,
    //   ]);
    // }

    // public function prePollSub(Request $request) {
    //   $user = Auth::user(); 
    //   $this->validate($request, [
    //     'pollStWise' => 'required',
    //     'forceDepPlan' => 'required',
    //   ]);
    //   $uidDeo=Auth::user()->uid;
    //   $stateDeo=get_state_id();
    //   $distDeo=Auth::user()->dist_code;

    // //--PSWISE
    //   $pollStWise= (isset($request['pollStWise']))? $request['pollStWise'] : "";
    //   if($pollStWise!==""){
    //     $filesPs = Input::file('pollStWise');
    //     $destinationPs = 'files';
    //     $filenamePs = $filesPs->getClientOriginalName();
    //     $randomPs=rand(10,999999).time();
    //     $filenamePsN = $randomPs.$filenamePs;
    //     $upload_successPs= $filesPs->move($destinationPs, $filenamePsN);
    //     $psFileName = $filenamePsN;
    //   }
    //   else{
    //     $psFileName="";
    //   }
    //   $psPlanRep = DB::table('pre_poll_arrangement_deo')->where('uid',$uidDeo)->where('doc_type',"PSWISE")->get();
    //   $countPs=count($psPlanRep);
    //   if($countPs>0){
    //     $upPs = array(
    //     'doc_name' => $psFileName,
    //     );
    //     $upPsPlan = DB::table('pre_poll_arrangement_deo')->where('uid', $uidDeo)->where('doc_type',"PSWISE")->update($upPs);
    //   }
    //   else{
    //     $addPs = array(
    //       'uid' => $uidDeo,
    //       'state_id' => $stateDeo,
    //       'dist_code' => $distDeo,
    //       'doc_name' => $psFileName,
    //       'doc_type' => "PSWISE",
    //     );
    //     $addPss = DB::table('pre_poll_arrangement_deo')->insert($addPs);
    //   }

    // //--FDPLAN
    //   $forceDepPlan= (isset($request['forceDepPlan']))? $request['forceDepPlan'] : "";
    //   if($forceDepPlan!==""){
    //     $filesFd = Input::file('forceDepPlan');
    //     $destinationFd = 'files';
    //     $filenameFd = $filesFd->getClientOriginalName();
    //     $randomFd=rand(10,999999).time();
    //     $filenameFds = $randomFd.$filenameFd;
    //     $upload_successFd= $filesFd->move($destinationFd, $filenameFds);
    //     $fdFileName = $filenameFds;
    //   }
    //   else{
    //     $fdFileName="";
    //   }
    //   $transPlanRe = DB::table('pre_poll_arrangement_deo')->where('uid',$uidDeo)->where('doc_type',"FDPLAN")->get();
    //   $countTrans=count($transPlanRe);
    //   if($countTrans>0){
    //     $upTrns = array(
    //     'doc_name' => $fdFileName,
    //   );
    //   $upTrnsPlan = DB::table('pre_poll_arrangement_deo')->where('uid', $uidDeo)->where('doc_type',"FDPLAN")->update($upTrns); 
    //   }
    //   else{
    //     $addTrns = array(
    //     'uid' => $uidDeo,
    //     'state_id' => $stateDeo,
    //     'dist_code' => $distDeo,
    //     'doc_name' => $fdFileName,
    //     'doc_type' => "FDPLAN",
    //     );
    //     $addTrans = DB::table('pre_poll_arrangement_deo')->insert($addTrns);
    //   }
    //   Session::flash('prePollMszDeo', 'Pre-Poll Arrangement added successfully.'); 
    //   Session::flash('alert-class', 'alert-danger'); 
    //   return Redirect::to('deo/pre-poll-arrangement');
    // }


    public function prePollArrangement() {
        $user = Auth::user();
        $state_id = $user->state_id;
        $distCode = $user->dist_code;
        $constituency=DB::table('constituencies')
                        ->where('state_id', $state_id)
                        ->where('dist_code', $distCode)
                        ->get();


        $constituencyFirst=DB::table('constituencies')
                        ->where('state_id', $state_id)
                        ->where('dist_code', $distCode)
                        ->first();

        $firstCons=$constituencyFirst->cons_code;
        $encryptCons=eci_encrypt($constituencyFirst->cons_code);




        $prePollSec=DB::table('pre_poll_arrangement_ro')
                 ->where('state_id', $state_id)
                 ->where('dist_code', $distCode)
                 ->where('cons_code', $firstCons)
                 ->where('doc_type', 'SEC')
                 ->first();

        $prePollTans=DB::table('pre_poll_arrangement_ro')
                 ->where('state_id', $state_id)
                 ->where('dist_code', $distCode)
                 ->where('cons_code', $firstCons)
                 ->where('doc_type', 'TRANS')
                 ->first();

        return view('deo/pre-poll-arrangement', [
            'constituency' => $constituency,
            'encryptCons' => $encryptCons,
            'prePollSec' => $prePollSec,
            'prePollTans' => $prePollTans,
        ]);
    }


    public function prePollSub(Request $request) {
        $user = Auth::user();
        $this->validate(
        $request, [
          'cons_code' => 'required', 
        ],
        [
          'cons_code.required' => 'Please select constituency', 
        ]
        );
        $encryptCons=$request->cons_code;
        $districtCode=$user->dist_code;
        $consituency=eci_decrypt($request->cons_code);
        $state=$user->state_id;

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

        $constituency=DB::table('constituencies')
                    ->where('dist_code', $districtCode)
                    ->get();

        return view('deo/pre-poll-arrangement', [
            'prePollSec' => $prePollSec,
            'prePollTans' => $prePollTans,
            'constituency' => $constituency,
            'encryptCons' => $encryptCons,
        ]);
    }



    public function pollingStationsMap()
    {
        $user = Auth::user();
        $polling_stations = DB::table('users')
                            ->join('poll_booths', 'users.uid', '=', 'poll_booths.supervisior_uid')
                            ->where('users.dist_code', $user->dist_code)
                            //->where('users.cons_code', $user->cons_code)
                            ->where('role', 5)
                            ->get();
        //dd($polling_stations);
        return view('deo/polling-stations-map', [
           'polling_stations' => $polling_stations,
       ]);
    }
	
    public function voterAlerts() {
      $user = Auth::user();
      return view('deo/voter-alerts');
    }

    public function alerts() {
      $user = Auth::user();
      return view('deo/alerts');
    }

    public function loginMediaAlerts(Request $request) {
      $user = Auth::user();
      $state_id = $user->state_id;
      $dist_code = $user->dist_code;
      $mediadata = DB::table('users_media')
                    ->where('state_id',$state_id)
                    ->where('dist_code','5')
                    ->get();
                    //dd($mediadata);
      foreach ($mediadata as $data) {
        $user = "01synergy";
        $password = "01@Synergy";
        $msisdn = $data->phone;
        $sid = "SYNRGY";
        $pw = get_gen_password();
        $genpassword = md5($pw);
        $datas = array(
          'password' => $genpassword,
          );
        $update = DB::table('users_media')->where('id',$data->id)->update($datas);
        if($update){
          $username = $data->phone;
          $msg = "Download ECI360 App! To proceed further, here are your Login details: Username - ".$username." Password - ".$pw."";
          $msg = urlencode($msg);
          $fl = "0";
          $gwid = "2";
          $ch =
          curl_init("http://cloud.smsindiahub.in/vendorsms/pushsms.aspx?user=".$user."&password=".$password."&msisdn=".$msisdn."&sid=".$sid."&msg=".$msg."&fl=".$fl."&gwid=".$gwid);
           curl_setopt($ch, CURLOPT_HEADER, 0);
           curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
           $output = curl_exec($ch);
           curl_close($ch);
        }
      }

      return Redirect::to('deo/alerts');
    }

    public function loginCandidateAlerts(Request $request) {
      $user = Auth::user();
      $state_id = $user->state_id;
      $dist_code = $user->dist_code;
      $candidatedata = DB::table('users')
                    ->where('state_id',$state_id)
                    ->where('dist_code',$dist_code)
                    ->where('role','15')
                    ->where('phone','9464529625')
                    ->get();
                    //dd($candidatedata);
      foreach ($candidatedata as $data) {
        $user = "01synergy";
        $password = "01@Synergy";
        $msisdn = $data->phone;
        $sid = "SYNRGY";
        $pw = get_gen_password();
        $genpassword = Hash::make($pw);
        $datas = array(
          'password' => $genpassword,
          );
        $update = DB::table('users')->where('id',$data->id)->update($datas);
        if($update){
          //dd($data);
          $username = $data->phone;
          $msg = "Download ECI360 App! To proceed further, here are your Login details: Username - ".$username." Password - ".$pw."";
          $msg = urlencode($msg);
          $fl = "0";
          $gwid = "2";
          $ch =
          curl_init("http://cloud.smsindiahub.in/vendorsms/pushsms.aspx?user=".$user."&password=".$password."&msisdn=".$msisdn."&sid=".$sid."&msg=".$msg."&fl=".$fl."&gwid=".$gwid);
           curl_setopt($ch, CURLOPT_HEADER, 0);
           curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
           $output = curl_exec($ch);
           curl_close($ch);
        }
      }

      return Redirect::to('deo/alerts');
    }

    public function sendvoterAlerts(Request $request) {
      $user = Auth::user();
      $this->validate(
      $request, [
        'msg' => 'required',
        'subject' => 'required', 
      ],
      [
        'msg.required' => 'Please add Message for voter alert',
        'subject.required' => 'Please add Subject for voter alert', 
      ]
      );
      $sub = $request->subject;
      $data = array(
        'sub' => $request->subject,
        'description' => $request->msg,
        'status' => 1,
      );
      $i = DB::table('voters_alert')->insert($data);
      if($i>0){
        $msgid = DB::getPdo()->lastInsertId();
        $gettoken = DB::table('voters_token')
                            ->get();
        foreach ($gettoken as $tokens) {
            $token = $tokens->token;
            $tokentype = $tokens->type;
            voternotification($token,$msgid,$sub,$tokentype);
        }
      }
      return Redirect::to('deo/voter-alerts');
    }
	
    public function pollingStaff()
    {
        $user = Auth::user();
        $polling_users = DB::table('users_pollday')
                          ->join('constituencies', function($join) { 
                                    $join->on('constituencies.cons_code', '=', 'users_pollday.cons_code')
                                          ->on('constituencies.state_id', '=', 'users_pollday.state_id')
                                          ->on('constituencies.dist_code', '=', 'users_pollday.dist_code'); 
                                      }
                                )
                         ->where('users_pollday.state_id', $user->state_id)
                         ->where('users_pollday.dist_code', $user->dist_code)
                         ->orderby('name')
                         ->get();
        return view('deo/polling-staff', [
            'polling_users' => $polling_users,
        ]);
    }


    public function pollingStaffType(Request $request){
        $user = Auth::user();
        $type = $request->staff_type;
        if($type == "second"){
            $polling_users = DB::table('users_pollday')
                             ->join('randomization_staff_second','randomization_staff_second.uid','=','users_pollday.uid')
                             ->join('constituencies', function($join) { 
                                    $join->on('constituencies.cons_code', '=', 'randomization_staff_second.cons_code')
                                          ->on('constituencies.state_id', '=', 'randomization_staff_second.state_id')
                                          ->on('constituencies.dist_code', '=', 'randomization_staff_second.dist_code'); 
                                      }
                                )
                             ->where('randomization_staff_second.state_id', $user->state_id)
                             ->where('randomization_staff_second.dist_code', $user->dist_code)
                             ->get();
            //dd($polling_users);
            return view('deo/polling-staff2', [
                'polling_users' => $polling_users,
            ]); 
        }elseif($type == "third"){
            $polling_users = DB::table('poll_booths')
                             ->join('randomization_staff_third','randomization_staff_third.bid','=','poll_booths.bid')
                             ->where('randomization_staff_third.state_id', $user->state_id)
                             ->where('randomization_staff_third.dist_code', $user->dist_code)
                             ->select(DB::raw('randomization_staff_third.party_no'))
                             ->groupBy('randomization_staff_third.party_no')
                             //->groupBy('randomization_staff_third.party_no')
                             ->get();

            if($polling_users->count()){
                $i=0;
                foreach ($polling_users as $value) {
                    # code...

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
                             ->where('randomization_staff_third.state_id', $user->state_id)
                             ->where('randomization_staff_third.dist_code', $user->dist_code)
                             ->select('randomization_staff_third.bid','poll_booths.ps_id','poll_booths.poll_building','users_pollday.name','users_pollday.elect_duty','users_pollday.phone','users.name as supervisor_name','users.phone as supervisor_phone','users.phone as supervisor_phone','constituencies.cons_name')
                             ->get();
                    //dd($staff);
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
            
            return view('deo/polling-staff3', [
                'polling_staff' => $polling_staff,
            ]);
        }else{
            $polling_users = DB::table('users_pollday')                            
                             ->join('constituencies', function($join) { 
                                    $join->on('constituencies.cons_code', '=', 'users_pollday.cons_code')
                                          ->on('constituencies.state_id', '=', 'users_pollday.state_id')
                                          ->on('constituencies.dist_code', '=', 'users_pollday.dist_code'); 
                                      }
                                )
                             ->where('users_pollday.state_id', $user->state_id)
                             ->where('users_pollday.dist_code', $user->dist_code)
                             ->get();
          //  dd($polling_users);
            return view('deo/polling-staff', [
                'polling_users' => $polling_users,
            ]); 
        }
        
    }
    public function pendingPollingStaff(){
        $user = Auth::user();
        $state_id =  $user->state_id;
        $dist_code =  $user->dist_code;
                
        //Polling Staff
        $first_staff = strtotime(Config::get('constants.FIRST_RANDOMIZATION_STAFF_DATE'));
        $second_staff = strtotime(Config::get('constants.SECOND_RANDOMIZATION_STAFF_DATE'));
        $third_staff = strtotime(Config::get('constants.THIRD_RANDOMIZATION_STAFF_DATE'));
        $today = time();
        if($third_staff <= $today){
            
            $staff = DB::table('constituencies')
                        ->whereNotIn('constituencies.cons_code', function($query) use ($state_id, $dist_code)
                        {
                            $query->select('cons_code')
                                  ->from('randomization_staff_third')
                                  ->where('randomization_staff_third.state_id','=',$state_id)
                                  ->where('randomization_staff_third.dist_code','=',$dist_code)
                                  ->groupBy('cons_code');
                        })
                        ->select('constituencies.cons_name')
                        ->where('constituencies.state_id','=',$state_id)
                        ->where('constituencies.dist_code','=',$dist_code)
                        ->get(); 
        }elseif($second_staff <= $today){
                $staff = DB::table('constituencies')
                        ->whereNotIn('constituencies.cons_code', function($query) use ($state_id, $dist_code)
                        {
                            $query->select('constituencies.cons_name')
                                  ->from('randomization_staff_second')
                                  ->where('randomization_staff_second.state_id','=',$state_id)
                                  ->where('randomization_staff_second.dist_code','=',$dist_code)
                                  ->groupBy('randomization_staff_second.cons_code');
                        })
                        ->select('constituencies.cons_name','districts.dist_name')
                        ->where('constituencies.state_id','=',$state_id)
                        ->where('constituencies.dist_code','=',$dist_code)
                        ->get();  
        }elseif($first_staff <= $today){
              
               $staff = DB::table('constituencies')
                        ->whereNotIn('constituencies.cons_code', function($query) use ($state_id, $dist_code)
                        {
                            $query->select('constituencies.cons_name')
                                  ->from('users_pollday')
                                  ->where('users_pollday.state_id','=',$state_id)
                                  ->where('users_pollday.dist_code','=',$dist_code)
                                  ->groupBy('users_pollday.cons_code');
                        })
                        ->select('constituencies.cons_name')
                        ->where('constituencies.state_id','=',$state_id)
                        ->where('constituencies.dist_code','=',$dist_code)
                        ->get();     
        }else{
            $staff = array();
        }

        return view('deo/pending-stafflist', [
           'stafflist' => $staff,
        ]);
    }

    public function pollstationlist(){
      $user = Auth::user();
      $state_id =  $user->state_id;
      $dist_code =  $user->dist_code;
                
      $pollstationlist = DB::table('constituencies')
                    ->leftjoin('poll_booths', 'constituencies.cons_code', '=', 'poll_booths.cons_code')
                    ->select('constituencies.cons_code', 'constituencies.cons_name',DB::raw('IFNULL(COUNT(poll_booths.poll_booth_id), 0) as total'))
                    ->where('constituencies.dist_code','=',$dist_code)
                    ->groupBy('constituencies.cons_code', 'constituencies.cons_name')
                    ->orderBy('constituencies.cons_code')
                    ->get();
        
        return view('deo/pollstationlist', [
           'pollstationlist' => $pollstationlist,
        ]);

    }
    
     public function consPollingStaff($id){
      $user = Auth::user();
      $state_id =  $user->state_id;
      $dist_code =  $user->dist_code;
      $cons_code = eci_decrypt($id);       
      $pollstationlist = DB::table('poll_booths')->join('users', 'poll_booths.supervisior_uid', '=', 'users.uid')->where('users.dist_code', $user->dist_code)->where('users.cons_code', $cons_code)->get();
      return view('deo/cons-polling-staff', [
        'pollstationlist' => $pollstationlist,
      ]);

    }
    
  
  public function addpollingStaff()
    {
        $user = Auth::user();
        return view('deo/add-polling-staff');
    //return view('deo/add-polling-staff-2');
    //return view('deo/add-polling-staff-3');
    }


    public function addPollingstaffexcel(Request $request) 
  {
    Session::put('selectPollRand', $request->selectPollRand);
    // $file = Input::file('addPollingstaff');
    // $extension = $file->getClientOriginalExtension();
    // if($extension!="csv")
    // {
    //   $this->validate($request, 
    //   [
    //     'addPollingstaff' => 'required|in:csv'
    //     //'addPollingstaff.required' => 'Er, you forgot your email address!',
    //   ]);
    // }

    $this->validate(
    $request, [
      //'addPollingstaff' => 'required|mimes:csv',
    ],
    [
      'addPollingstaff.required' => 'This field is required',
      'addPollingstaff.mimes' => 'Please add a CSV file',
    ]
    );

    Excel::load(Input::file('addPollingstaff'), function ($reader) 
    { 
      $results = $reader->all();
      foreach ($reader->toArray() as $row) 
      {
        $user = Auth::user();
        $details=json_decode($user);
        $userid=$details->id;
        $dist_code=$details->dist_code;
        $state_id=$details->state_id;
        //$state_id=$user->state_id;
        $sno=trim($row['sno']);
        $cons_code=trim($row['ac_no']);
        $emp_id=trim($row['emp_id']);
        $ref_no=trim($row['ref_no']); 
        $name=strtoupper($row['name']);
        $designation=strtoupper($row['designation']);
        $department=strtoupper($row['department']);
        if(@$row['mobile']){
          $mobile=trim($row['mobile']);
          $users = DB::table('users_pollday')->where('phone', $mobile)->get();
          $count = count($users);
        }else{
          $mobile="";
          $mobile=trim($row['mobile']);
          $users = DB::table('users_pollday')->where('ref_no', $ref_no)->get();
          $count = count($users);
        }
        
        $mobile_get="";
       
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
        
		    $password="123456";
        if($count==0)
        {
          $addPolluser = array(
            'emp_id' => $emp_id,
            'ref_no' => $ref_no,
            'data_entry_date' => $date,
            'otp_time' => $otp_time,
            'reset_time' => $otp_time,
            'uid' => $uid,
            'sno' => $sno,
            'elect_duty' => $class,
            'phone' => $mobile,
			      'password' => bcrypt($password),
            'name' => $name,
            'designation' => $designation,
            'department' => $department,
            'state_id' => $state_id,
            'dist_code' => $dist_code,
            'cons_code' => $cons_code,
            'role' => $role,
            'mobile_otp' => "",
            'reset_otp' => ""
          );
          
          $distic = DB::table('constituencies')->where('state_id', $state_id)->where('cons_code', $cons_code)->select('dist_code')->first();
          $dist_code1 = $distic->dist_code;

          if($dist_code1==$dist_code)
          {
            $addPollUser = DB::table('users_pollday')->insert($addPolluser); 
          }
        }
		else
		{
			$addPolluser = array(
            'emp_id' => $emp_id,
            'ref_no' => $ref_no,
            'data_entry_date' => $date,
            'otp_time' => $otp_time,
            'reset_time' => $otp_time,
            'uid' => $uid,
            'sno' => $sno,
            'elect_duty' => $class,
            'phone' => $mobile,
			      //'password' => bcrypt($password),
            'name' => $name,
            'designation' => $designation,
            'department' => $department,
            'state_id' => $state_id,
            'dist_code' => $dist_code,
            'cons_code' => $cons_code,
            'role' => $role,
            'mobile_otp' => "",
            'reset_otp' => ""
          );
          
          $distic = DB::table('constituencies')->where('state_id', $state_id)->where('cons_code', $cons_code)->select('dist_code')->first();
          $dist_code1 = $distic->dist_code;

          if($dist_code1==$dist_code)
          {
            if(@$mobile){
              $addPollUser = DB::table('users_pollday')->where('phone', $mobile)->update($addPolluser);
            }else{
              $addPollUser = DB::table('users_pollday')->where('ref_no', $ref_no)->update($addPolluser);
            }
            
          }
		}
		}
        });
        Session::flash('addPollinguser', 'Polling user imported successfully.'); 
        return Redirect::to('deo/polling-staff');
    }



    public function addPollingstaffexcel2(Request $request) 
  {
    Session::put('selectPollRand', $request->selectPollRand);
    // $file = Input::file('addPollingstaffexcel2');
    // $extension = $file->getClientOriginalExtension();
    // if($extension!="csv")
    // {
    //   $this->validate($request, [
    //     'addPollingstaffexcel2' => 'required|in:csv'
    //   ]);
    // }
    
    $this->validate(
    $request, [
      //'addPollingstaffexcel2' => 'required|mimes:csv,txt',
      'addPollingstaffexcel2' => 'required',
    ],
    [
      'addPollingstaffexcel2.required' => 'This field is required',
      'addPollingstaffexcel2.mimes' => 'Please add a CSV file',
    ]
    );

        Excel::load(Input::file('addPollingstaffexcel2'), function ($reader) 
    { 
      $results = $reader->all();
            foreach ($reader->toArray() as $row) 
      {
        $user = Auth::user();
        $details=json_decode($user);
        $userid=$details->id;
        $dist_code=$details->dist_code;
        $cons_code=$row['ac_no'];
        
        //$state_id="53";
        $state_id=$details->state_id;
        $emp_id=trim($row['emp_id']);
        $party_no=trim($row['party_no']);
        $ref_no=trim($row['ref_no']);
        $slno=trim($row['s_no']); 
        $name=strtoupper($row['name']);
        $designation=strtoupper($row['designation']);
        $department=strtoupper($row['department']);
        $mobile = trim($row['mobile']);
        $mobile_get = "";

        if(@$mobile){
         
          $users = DB::table('users_pollday')->where('phone', $mobile)->get();
          $count = count($users);
        }else{
           //die("sdfsfd");
          $users = array();
          $count = count($users);
        }
        //die($count);
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
        //die($emp_id);
        $users_count = DB::table('users_pollday')->where('emp_id', $emp_id)->where('ref_no', $ref_no)->get();
        $count_dup = count($users_count);
		    //dd($count_dup);
        if($count_dup==1)
        {
          //echo "fsd";
          //die($count_dup);
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
            'rand_second' =>1,
            'mobile_otp' => "",
            'password' => "293cbaccf73d7575bbed3ecee5ec87d4",
            'reset_otp' => ""
          );
          //dd( $addPolluser);
          $distic = DB::table('constituencies')->where('state_id', $state_id)->where('cons_code', $cons_code)->select('dist_code')->first();
          $dist_code1 = $distic->dist_code;

          if($dist_code1==$dist_code)
          {
            $addPollUser = DB::table('users_pollday')->where('emp_id', $emp_id)->where('ref_no', $ref_no)->update($addPolluser);
            $addrandomization_staff_second = array(
              'emp_id' => $emp_id,
              'ref_no' => $ref_no,
              'uid' => $uid,
              'party_no' => $party_no,
              'cons_code' => $cons_code,
              'dist_code' => $dist_code,
              'state_id' => $state_id,
              'slno' => $slno
            );
            //dd($addrandomization_staff_second);
            $random_second_count = DB::table('randomization_staff_second')->where('emp_id', $emp_id)->where('ref_no', $ref_no)->get();
            $random_second_count1 = count($random_second_count);
            if($random_second_count1==0)
            {
              $addrandomization_staff_second1 = DB::table('randomization_staff_second')->insert($addrandomization_staff_second);
            }
            else
            {
              $addrandomization_staff_second = DB::table('randomization_staff_second')->where('emp_id', $emp_id)->where('ref_no', $ref_no)->update($addrandomization_staff_second);
            }
          } 
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
              'rand_second' =>1,
              'role' => $role,
              'mobile_otp' => "",
              'password' => "293cbaccf73d7575bbed3ecee5ec87d4",
              'reset_otp' => ""
            );
            
            $distic = DB::table('constituencies')->where('state_id', $state_id)->where('cons_code', $cons_code)->select('dist_code')->first();
            $dist_code1 = $distic->dist_code;

            if($dist_code1==$dist_code)
            {
              $addPollUser = DB::table('users_pollday')->insert($addPolluser);

              $addrandomization_staff_second = array(
                'emp_id' => $emp_id,
                'ref_no' => $ref_no,
                'uid' => $uid,
                'party_no' => $party_no,
                'cons_code' => $cons_code,
                'dist_code' => $dist_code,
                'state_id' => $state_id,
                'slno' => $slno
              );
              
              $random_second_count = DB::table('randomization_staff_second')->where('emp_id', $emp_id)->where('ref_no', $ref_no)->get();
              $random_second_count1 = count($random_second_count);
              if($random_second_count1==0)
              {
                $addrandomization_staff_second1 = DB::table('randomization_staff_second')->insert($addrandomization_staff_second);
              }
              else
              {
                $addrandomization_staff_second = DB::table('randomization_staff_second')->where('emp_id', $emp_id)->where('ref_no', $ref_no)->update($addrandomization_staff_second);
              }
            }
          }
			      
        }
            }
        });
        Session::flash('addPollinguser', 'Polling user imported successfully.'); 
        return Redirect::to('deo/polling-staff');
    }
  
  public function addPollingstaffexcel3(Request $request) 
  { 
    Session::put('selectPollRand', $request->selectPollRand);
    // $file = Input::file('addPollingstaffexcel3');
    // $extension = $file->getClientOriginalExtension();
    // if($extension!="csv")
    // {
    //   $this->validate($request, [
    //     'addPollingstaffexcel3' => 'required|in:csv'
    //   ]);
    // }
    $this->validate(
    $request, [
    'addPollingstaffexcel3' => 'required|mimes:csv,txt',
    ],
    [
    'addPollingstaffexcel3.required' => 'This field is required',
    'addPollingstaffexcel3.mimes' => 'Please add a CSV file',
    ]
    );
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
        $cons_code=$row['ac_no'];
        $state_id=$details->state_id;
        $dist_code1=str_pad($dist_code, 3, '0', STR_PAD_LEFT);
        $cons_code1=str_pad($cons_code, 3, '0', STR_PAD_LEFT);
        $polling_station_no1=str_pad($polling_station_no, 3, '0', STR_PAD_LEFT);
        $bid=$dist_code.$cons_code1.$polling_station_no1;
        //die();
        
        $users_count = DB::table('randomization_staff_second')->where('cons_code', $cons_code)->where('dist_code', $dist_code)->where('party_no', $party_no)->get();
        $count_dup = count($users_count);

        
        //die();
        
        
        if($count_dup>=1){

          foreach ($users_count as $valuestaff) {
            # code...
            $uid=$valuestaff->uid;
            $users_third = DB::table('randomization_staff_third')->where('uid', $uid)->where('bid', $bid)->first();
            $count_third = count($users_third);
                if($count_third==0){
                    $addrandomization_staff_third = array(
                      'party_no' => $party_no,
                      'state_id' => $state_id,
                      'dist_code' => $dist_code,
                      'cons_code' => $cons_code,
                      'uid' => $uid,
                      'bid' => $bid,
                      'polling_station' => $name_of_polling_station,
                    );
                    
                    $distic = DB::table('constituencies')->where('state_id', $state_id)->where('cons_code', $cons_code)->select('dist_code')->first();
                    $dist_code1 = $distic->dist_code;

                    if($dist_code1==$dist_code)
                    {
                      $addrandomization_staff_third = DB::table('randomization_staff_third')->insert($addrandomization_staff_third);
                    }
                }
                else{
                      $addrandomization_staff_third = array(
                        'party_no' => $party_no,
                        'state_id' => $state_id,
                        'dist_code' => $dist_code,
                        'cons_code' => $cons_code,
                        'uid' => $uid,
                        'bid' => $bid,
                        'polling_station' => $name_of_polling_station,
                      );
                      
                      $distic = DB::table('constituencies')->where('state_id', $state_id)->where('cons_code', $cons_code)->select('dist_code')->first();
                      $dist_code1 = $distic->dist_code;

                      if($dist_code1==$dist_code)
                      {
                        $addrandomization_staff_third = DB::table('randomization_staff_third')->where('uid', $uid)->where('bid', $bid)->update($addrandomization_staff_third);
                      } 
                }
          }
                
        }
      }
        });
        Session::flash('addPollinguser', 'Polling user imported successfully.'); 
        return Redirect::to('deo/polling-staff');
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
        return view('deo/polling-parties-details', [
            'polling_users' => $return_array,
            'poo_array' => $poo_array,
            'poll_booths' => $poll_booths,
            'visibile' => $visibile,
        ]);
    }

    public function webCastingUpdate() {
      $user = Auth::user();
      return view('deo/update-webCasting');
    }

    public function webCastingSub(Request $request) {
      $this->validate(
      $request, [
        'webCasting' => 'required|mimes:csv,txt',
      ],
      [
        'webCasting.required' => 'This field is required',
        'webCasting.mimes' => 'Please add a CSV file',
      ]
      );
      $user = Auth::user();
      Excel::load(Input::file('webCasting'), function ($reader) {

        $xyz=2;
        $emptyErr=array();
        foreach ($reader->toArray() as $row) {
          $consCode=$row['ac_no'];
          $partNum=$row['part_no'];

          if(!empty($consCode) && !empty($partNum)){
            $upWebCast = array(
              'web_casting' => 1,
            );
            $updateWebCast = DB::table('poll_booths')
                      ->where('cons_code', $consCode)
                      ->where('ps_id', $partNum)
                      ->update($upWebCast);
          }else{
            $emptyErr[]=$xyz;
          }
        $xyz++;
        }

        $emptyErr1 = array_filter($emptyErr);
        if (!empty($emptyErr1)) {
            $errorEmptyRow1=implode(',', $emptyErr1);
            $errorEmptyRow="Acc_NO or Part_NO is missing on row ".$errorEmptyRow1;
        }else{
           $errorEmptyRow=""; 
        }
        if($errorEmptyRow!==""){
          Session::flash('webCastingErr', $errorEmptyRow);
        }
      });
      Session::flash('webCastingSuccess', 'Web Casting updated successfully.');
      return Redirect::to('deo/webCastingUpdate');
    }
	
	//-- Poll day report
    public function pollDayReport() {
      $user = Auth::user();
      $distCode =Auth::user()->dist_code;
      $stateID=Auth::user()->state_id;
      $constituency=DB::table('constituencies')
                    ->where('dist_code', $distCode)
                    ->get();


      $constituencyFirst=DB::table('constituencies')
                    ->where('dist_code', $distCode)
                    ->first();

      $firstCons=$constituencyFirst->cons_code;
      $encryptCons=eci_encrypt($constituencyFirst->cons_code); 

      $pollDayDetail = DB::table('poll_booths')
                     ->leftjoin('pro_activity_pollday', 'poll_booths.bid', '=', 'pro_activity_pollday.bid')
                     ->where('poll_booths.state_id', $stateID)
                     ->where('poll_booths.dist_code', $distCode)
                     ->where('poll_booths.cons_code', $firstCons)
                     ->get();

      return view('deo/poll-day', [
        'constituency' => $constituency,
        'encryptCons' => $encryptCons,
        'pollDayDetail' => $pollDayDetail,
      ]);
    }


    public function pollDay(Request $request) {
      $this->validate(
      $request, [
        'cons_code' => 'required', 
      ],
      [
        'cons_code.required' => 'Please select constituency', 
      ]
      );
  		$user = Auth::user();
  		$stateID=Auth::user()->state_id;
  		$distCode =Auth::user()->dist_code;
  		$encryptCons=$request->cons_code;
  		$consCode=eci_decrypt($request->cons_code);
  		$pollDayDetail = DB::table('poll_booths')
  							->leftjoin('pro_activity_pollday', 'poll_booths.bid', '=', 'pro_activity_pollday.bid')
                              ->where('poll_booths.state_id', $stateID)
                              ->where('poll_booths.dist_code', $distCode)
                              ->where('poll_booths.cons_code', $consCode)
                              ->get();
  		
  		$constituency=DB::table('constituencies')
                      ->where('dist_code', $distCode)
                      ->get();
  		
  		return view('deo/poll-day', [
        'pollDayDetail' => $pollDayDetail,
  		  'constituency' => $constituency,
  			'encryptCons' => $encryptCons,
      ]);
    }
	

	public function complaint(){
    $coms = array();
	  $user = Auth::user();
    $complaints = get_complaints($user->state_id, $user->dist_code);
    if(@$complaints){
		$i = 0;
		$nature = get_com_nature();
    foreach ($complaints as $value) {
			if(($value->InformationType_ID == "1") &&( $value->CTBLBTypeID =="2")){
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
    return view('deo/complaint', [
           'complaints' => $coms,
        ]);
    
	}

	public function information(){
	 $coms = array();
    $user = Auth::user();
    $complaints = get_complaints($user->state_id, $user->dist_code);
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
    return view('deo/information', [
           'complaints' => $coms,
        ]);
	}
	
	public function suggestion(){
	 $coms = array();
    $user = Auth::user();
    $complaints = get_complaints($user->state_id, $user->dist_code);
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
    return view('deo/suggestion', [
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
    
    return view('deo/complaint-detail', [
           'details' => $details,
        ]);
  }


  public function suvidha(){
    $user = Auth::user();
    $state_id = $user->state_id;
    $dist_code = $user->dist_code;

    $constituency = DB::table('constituencies')
                    ->where('dist_code', $dist_code)
                    ->get();

    $constituencyFirst = DB::table('constituencies')
                    ->where('dist_code', $dist_code)
                    ->first();
    $cons_code=$constituencyFirst->cons_code;
    $encCons=eci_encrypt($constituencyFirst->cons_code);

    $getdata = get_suvidha_data($state_id,$dist_code,$cons_code);
    $getparty = get_party_list();
  
    return view('deo/suvidha', [
      'getdata' => $getdata,
      'getparty' => $getparty,
      'constituency' => $constituency,
      'encCons' => $encCons,
    ]);
  }

  
  public function suvidhaSub(Request $request) {
    
    $this->validate($request, [
      'cons_code' => 'required',
    ]);
    $user = Auth::user();
    $state_id = $user->state_id;
    $dist_code = $user->dist_code;
    $encCons=$request->cons_code;
    $cons_code=eci_decrypt($request->cons_code);

    $constituency = DB::table('constituencies')
                    ->where('dist_code', $dist_code)
                    ->get();

    $getdata = get_suvidha_data($state_id,$dist_code,$cons_code);
    $getparty = get_party_list();

    return view('deo/suvidha', [
      'getdata' => $getdata,
      'getparty' => $getparty,
      'constituency' => $constituency,
      'encCons' => $encCons,
    ]);

  }


  public function suvidhaDetail($sid){
    $id=eci_decrypt($sid);
      $getdata = get_suvidha_detail($id);
      return view('deo/suvidha-detail', [
         'getdata' => $getdata,
     ]);
  }


  public function p1Scrutiny(){
    $user = Auth::user();
    $state_id = $user->state_id;
    $distCode = $user->dist_code;
    $constituency=DB::table('constituencies')
                    ->where('state_id', $state_id)
                    ->where('dist_code', $distCode)
                    ->get();

    $constituencyFirst=DB::table('constituencies')
                    ->where('state_id', $state_id)
                    ->where('dist_code', $distCode)
                    ->first();

    $firstCons=$constituencyFirst->cons_code;
    $encryptCons=eci_encrypt($constituencyFirst->cons_code);
    $scrutinyReport = DB::table('ro_report')
                    ->where('state_id', $state_id)
                    ->where('dist_code', $distCode)
                    ->where('cons_code', $firstCons)
                    ->where('doc_type', "SCRUTINY")
                    ->first();

    return view('deo/p1-scrutiny', [
        'constituency' => $constituency,
        'encryptCons' => $encryptCons,
        'scrutinyReport' => $scrutinyReport,
    ]);
  }

  public function p1ScrutinySearch(Request $request) {
    $this->validate(
    $request, [
      'cons_code' => 'required',
    ],
    [
      'cons_code.required' => 'Please select constituency',
    ]
    );
    $user = Auth::user();
    $state_id = $user->state_id;
    $distCode = $user->dist_code;
    $encryptCons=$request->cons_code;
    $dcryptCons=eci_decrypt($request->cons_code);

    $constituency=DB::table('constituencies')
                    ->where('state_id', $state_id)
                    ->where('dist_code', $distCode)
                    ->get();

    $scrutinyReport = DB::table('ro_report')
                    ->where('state_id', $state_id)
                    ->where('dist_code', $distCode)
                    ->where('cons_code', $dcryptCons)
                    ->where('doc_type', "SCRUTINY")
                    ->first();

    return view('deo/p1-scrutiny', [
        'constituency' => $constituency,
        'encryptCons' => $encryptCons,
        'scrutinyReport' => $scrutinyReport,
    ]);
  }


  public function p1ConsolidatedReport(){
    $user = Auth::user();
    $state_id = $user->state_id;
    $distCode = $user->dist_code;

    $constituency=DB::table('constituencies')
                    ->where('state_id', $state_id)
                    ->where('dist_code', $distCode)
                    ->get();
    $constituencyFirst=DB::table('constituencies')
                    ->where('state_id', $state_id)
                    ->where('dist_code', $distCode)
                    ->first();
    $firstCons=$constituencyFirst->cons_code;
    $encryptCons=eci_encrypt($constituencyFirst->cons_code);
    $consReport = DB::table('ro_consolidated_report')
                ->where('cons_code', $firstCons)
                ->first();

    return view('deo/p1-consolidated-report', [
        'constituency' => $constituency,
        'encryptCons' => $encryptCons,
        'consReport' => $consReport,
    ]);
  }


  public function p1ConsolidatedReportSearch(Request $request) {
    $user = Auth::user();
    $this->validate(
    $request, [
      'cons_code' => 'required',
    ],
    [
      'cons_code.required' => 'Please select constituency',
    ]
    );
    $state_id = $user->state_id;
    $distCode = $user->dist_code;
    $encryptCons = $request->cons_code;
    $consCode=eci_decrypt($request->cons_code);
    $constituency=DB::table('constituencies')
                 ->where('state_id', $state_id)
                 ->where('dist_code', $distCode)
                 ->get();

    $consReport = DB::table('ro_consolidated_report')
                ->where('cons_code', $consCode)
                ->first();

    return view('deo/p1-consolidated-report', [
        'constituency' => $constituency,
        'encryptCons' => $encryptCons,
        'consReport' => $consReport,
    ]);
  }


  public function pollPercentage() {
    $user = Auth::user();
    $stateID=Auth::user()->state_id;
    $distCode=Auth::user()->dist_code;

    $constituency=DB::table('constituencies')
                    ->where('state_id', $stateID)
                    ->where('dist_code', $distCode)
                    ->get();

    $constituencyFirst=DB::table('constituencies')
                    ->where('state_id', $stateID)
                    ->where('dist_code', $distCode)
                    ->first();

    $consCode=$constituencyFirst->cons_code;
    $encryptCons=eci_encrypt($constituencyFirst->cons_code);

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
                        
    return view('deo/poll-percentage', [
      'pollpercentages' => $pollpercentages,
      'polltiming' => $timeslot,
      'constituency' => $constituency,
      'encryptCons' => $encryptCons,
    ]);
  }


  public function pollPercentagetiming(Request $request) {
    $this->validate(
    $request, [
      'polltiming' => 'required',
      'cons_code' => 'required',
    ],
    [
      'polltiming.required' => 'Please select time',
      'cons_code.required' => 'Please select constituency',
    ]
    );
    $user = Auth::user();
    $stateID=Auth::user()->state_id;
    $distCode=Auth::user()->dist_code;
    $constituency=DB::table('constituencies')
                ->where('state_id', $stateID)
                ->where('dist_code', $distCode)
                ->get();

    $encryptCons=$request->cons_code;
    $consCode=eci_decrypt($request->cons_code);
    $polltiming = $request->polltiming;
    $pollpercentages = DB::table('poll_booths')
                        ->leftjoin('pro_polling_percentage', 'poll_booths.bid', '=', 'pro_polling_percentage.bid')
                        ->where('poll_booths.state_id', $stateID)
                        ->where('poll_booths.dist_code', $distCode)
                        ->where('poll_booths.cons_code', $consCode)
                         ->select('pro_polling_percentage.'.$polltiming,'poll_booths.poll_building','poll_booths.bid')
                        ->get();

    return view('deo/poll-percentage', [
      'pollpercentages' => $pollpercentages,
      'polltiming' => $polltiming,
      'constituency' => $constituency,
      'encryptCons' => $encryptCons,
    ]);
  }

  public function pollingPercentageDetail($bid) {
    $user = Auth::user();
    $stateID=Auth::user()->state_id;
    $distCode=Auth::user()->dist_code;
    $bid=eci_decrypt($bid);
    $pollpercentageDetail = DB::table('poll_booths')
                        ->leftjoin('pro_polling_percentage', 'poll_booths.bid', '=', 'pro_polling_percentage.bid')
                        ->where('poll_booths.state_id', $stateID)
                        ->where('poll_booths.dist_code', $distCode)
                        ->where('poll_booths.bid', $bid)
                        ->first();

    return view('deo/polling-percentage-detail', [
      'pollpercentageDetail' => $pollpercentageDetail,
    ]);
  }
  
public function policeData(){
		$uidDeo=Auth::user()->uid;
        $distCodeDeo = Auth::user()->dist_code;
        $statIdDeo = Auth::user()->state_id;
		
		$policeData = DB::table('deo_police_data')
                                    ->where('state_id', $statIdDeo)
									->where('dist_code', $distCodeDeo)
                                    ->get();
									
		return view('deo/add-police-data', [
		  'policeData' => $policeData,
		]);
  }
	
  public function addPoliceData(Request $request) {
      $user = Auth::user();
      $this->validate(
      $request, [
        'nbw_total' => 'required',
        'nbw_resolved' => 'required',
        'nbw_pending' => 'required',
        'arm_total' => 'required',
        'arm_resolved' => 'required',
		    'arm_pending' => 'required',		
      ],
      [
        'nbw_total.required' => 'Please add total value for NBW',
        'nbw_resolved.required' => 'Please add resolved value for NBW',
        'nbw_pending.required' => 'Please add pending value for NBW',
        'arm_total.required' => 'Please add total value for Arms & Amination',
        'arm_resolved.required' => 'Please add resolved value for Arms & Amination',
		    'arm_pending.required' => 'Please add pending value for Arms & Amination',
      ]
      );
      $uidDeo=Auth::user()->uid;
      $distCodeDeo = Auth::user()->dist_code;
      $statIdDeo = Auth::user()->state_id;

      $addPoliceData = array(
        'uid' => $uidDeo,
        'dist_code' => $distCodeDeo,
        'state_id' => $statIdDeo,
        'nbw_total' => $request->nbw_total,
        'nbw_resolved' => $request->nbw_resolved,
        'nbw_pending' => $request->nbw_pending,
        'arm_total' => $request->arm_total,
        'arm_resolved' => $request->arm_resolved,
    		'arm_pending' => $request->arm_pending,
    		'updated_at' => date("Y-m-d h:i:s"),
      );  
	  
	  $countPoliceData = DB::table('deo_police_data')
                ->where('dist_code', $distCodeDeo)
                ->where('state_id', $statIdDeo)
                ->first();
	  $countPoliceData = count($countPoliceData);
	  if($countPoliceData>0){
		   $updatePolicedata = DB::table('deo_police_data')
                  ->where('state_id', $statIdDeo)
                  ->where('dist_code', $distCodeDeo)
                  ->update($addPoliceData);
	  }
	  else
	  {
		 $addPoliceData = DB::table('deo_police_data')->insert($addPoliceData); 
	  }
	  
	  Session::flash('PolicedataSucc', 'Police data added successfully.'); 
	  return Redirect::to('deo/police-data');
      
    }
	
	public function voterSlipData(){
        $uidDeo=Auth::user()->uid;
        $distCodeDeo = Auth::user()->dist_code;
        $statIdDeo = Auth::user()->state_id;
    
    $constituency=DB::table('constituencies')
                    ->where('dist_code', $distCodeDeo)
                    ->get();

    $constituencyFirst=DB::table('constituencies')
                      ->where('dist_code', $distCodeDeo)
                      ->first();


    $firstCons=$constituencyFirst->cons_code;
    $encryptCons=eci_encrypt($constituencyFirst->cons_code);
    
    $voterslipData = DB::table('ro_voter_slips_data')
                                    ->join('constituencies', 'ro_voter_slips_data.cons_code','constituencies.cons_code')
                                    ->where('ro_voter_slips_data.dist_code', $distCodeDeo)
                                    ->where('ro_voter_slips_data.state_id', $statIdDeo)
                                    ->get();
                  
    return view('deo/voter-slip-data', [
      'voterslipData' => $voterslipData,
      'constituency' => $constituency,
      'encryptCons' => $encryptCons,
    ]);
    }


  public function dispatchCollectionCenter() {
    $user = Auth::user();
    $stateID=Auth::user()->state_id;
    $dist_code = $user->dist_code;
    
    $constituency=DB::table('constituencies')
            ->where('state_id', $stateID)
            ->where('dist_code', $dist_code)
            ->get();

    $constituencyFirst=DB::table('constituencies')
            ->where('state_id', $stateID)
            ->where('dist_code', $dist_code)
            ->first();

    $cons_code=$constituencyFirst->cons_code;
    $encryptCons=eci_encrypt($constituencyFirst->cons_code);
    $consTypeEnc=eci_encrypt("DISPATCH");
    $centerDetail = DB::table('dispatch_collection_center')
                    ->where('dist_code', $dist_code)
                    ->where('cons_code', $cons_code)
                    ->first();

    return view('deo/dispatch-collection-center', [
       'centerDetail' => $centerDetail,
       'consTypeEnc' => $consTypeEnc,
       'encryptCons' => $encryptCons,
       'constituency' => $constituency,
    ]);
  }

  public function dispatchCollectionCenterSub(Request $request) {
    $user = Auth::user();
    $this->validate(
      $request, [
        'centerType' => 'required',
        'consCode' => 'required',
      ],
      [
        'centerType.required' => 'This field is required',
        'consCode.required' => 'This field is required',
      ]
    );
    $stateID=Auth::user()->state_id;
    $dist_code = $user->dist_code;
    $cons_code = eci_decrypt($request->consCode);
    $encryptCons=$request->consCode;
    $consTypeEnc=$request->centerType;
    $constituency=DB::table('constituencies')
                 ->where('state_id', $stateID)
                 ->where('dist_code', $dist_code)
                 ->get();

    $centerDetail = DB::table('dispatch_collection_center')
                  ->where('dist_code', $dist_code)
                  ->where('cons_code', $cons_code)
                  ->first();

    return view('deo/dispatch-collection-center', [
      'centerDetail' => $centerDetail,
      'consTypeEnc' => $consTypeEnc,
      'encryptCons' => $encryptCons,
      'constituency' => $constituency,
    ]);
  }

  public function postalBallot() {
    $user = Auth::user();
    $stateID=Auth::user()->state_id;
    $dist_code = $user->dist_code;
    
    $constituency=DB::table('constituencies')
            ->where('state_id', $stateID)
            ->where('dist_code', $dist_code)
            ->get();

    $constituencyFirst=DB::table('constituencies')
            ->where('state_id', $stateID)
            ->where('dist_code', $dist_code)
            ->first();

    $cons_code=$constituencyFirst->cons_code;
    $encryptCons=eci_encrypt($constituencyFirst->cons_code);

    $postBallot = DB::table('voters_ballot')
                    ->where('state_id', $stateID)
                    ->where('dist_code', $dist_code)
                    ->where('cons_code', $cons_code)
                    ->first();

    return view('deo/postal-ballot', [
       'postBallot' => $postBallot,
       'constituency' => $constituency,
       'encryptCons' => $encryptCons,
    ]);
  }


  public function postalBallotSub(Request $request) {
    $user = Auth::user();
    $this->validate(
    $request, [
      'consCode' => 'required',
    ],
    [
      'consCode.required' => 'Please select constituency',
    ]
    );
    $stateID=Auth::user()->state_id;
    $dist_code = $user->dist_code;
    $constituency=DB::table('constituencies')
                 ->where('state_id', $stateID)
                 ->where('dist_code', $dist_code)
                 ->get();

    $cons_code=eci_decrypt($request->consCode);
    $encryptCons=$request->consCode;
    $postBallot = DB::table('voters_ballot')
                ->where('state_id', $stateID)
                ->where('dist_code', $dist_code)
                ->where('cons_code', $cons_code)
                ->first();

    return view('deo/postal-ballot', [
       'postBallot' => $postBallot,
       'constituency' => $constituency,
       'encryptCons' => $encryptCons,
    ]);
  }

  public function lawOrder()
    {
        $user = Auth::user();
        $state_id = $user->state_id;
        $dist_code = $user->dist_code;
        $constituency = DB::table('constituencies')
                      ->where('dist_code', $dist_code)
                      ->where('state_id', $state_id)
                      ->get();

        $constituencyFirst = DB::table('constituencies')
                           ->where('dist_code', $dist_code)
                           ->where('state_id', $state_id)
                           ->first();

        $firstCons=$constituencyFirst->cons_code;
        $encConsCode=eci_encrypt($constituencyFirst->cons_code);

        $laworderlist = DB::table('pro_law_order')
                      ->join('poll_booths','poll_booths.bid','pro_law_order.bid')
                        ->join('users','poll_booths.supervisior_uid','users.uid')
                      
                      ->join('users_pollday', 'pro_law_order.uid','users_pollday.uid')
                      ->select('poll_booths.bid','poll_booths.poll_building','poll_booths.poll_building_detail','poll_booths.ps_id','users_pollday.name as pro_name', 'users_pollday.phone as pro_number', 'users.name as sup_name', 'users.phone as sup_num', 'pro_law_order.comment', 'pro_law_order.action_from', 'pro_law_order.action_to')
                      ->where('pro_law_order.cons_code', $firstCons)
                      ->get();
                      //dd($laworderlist);
        return view('deo/law-order', [
           'laworderlist' => $laworderlist,
           'constituency' => $constituency,
            'encConsCode' => $encConsCode,
        ]);
    }

    public function lawOrderSub(Request $request) {
    $user = Auth::user();
    $this->validate(
      $request, [
        'cons_code' => 'required',
      ],
      [
        'cons_code.required' => 'This field is required',
      ]
    );
    $state_id = $user->state_id;
    $dist_code = $user->dist_code;
    $constituency = DB::table('constituencies')
                  ->where('dist_code', $dist_code)
                  ->where('state_id', $state_id)
                  ->get();

    $dcryptCons=eci_decrypt($request->cons_code);
    $encConsCode=$request->cons_code;
    $laworderlist = DB::table('pro_law_order')
                      ->join('poll_booths','poll_booths.bid','pro_law_order.bid')
                        ->join('users','poll_booths.supervisior_uid','users.uid')
                      
                      ->join('users_pollday', 'pro_law_order.uid','users_pollday.uid')
                      ->select('poll_booths.bid','poll_booths.poll_building','poll_booths.poll_building_detail','poll_booths.ps_id','users_pollday.name as pro_name', 'users_pollday.phone as pro_number', 'users.name as sup_name', 'users.phone as sup_num', 'pro_law_order.comment', 'pro_law_order.action_from', 'pro_law_order.action_to')
                      ->where('pro_law_order.cons_code', $dcryptCons)
                      ->get();

    return view('deo/law-order', [
      'constituency' => $constituency,
      'encConsCode' => $encConsCode,
    'laworderlist' => $laworderlist,
    ]);
  }

  public function evmMalfunction() {
    $user = Auth::user();
    $state_id = $user->state_id;
    $dist_code = $user->dist_code;
    $constituency = DB::table('constituencies')
                  ->where('dist_code', $dist_code)
                  ->where('state_id', $state_id)
                  ->get();

    $constituencyFirst = DB::table('constituencies')
                       ->where('dist_code', $dist_code)
                       ->where('state_id', $state_id)
                       ->first();

    $firstCons=$constituencyFirst->cons_code;
    $encConsCode=eci_encrypt($constituencyFirst->cons_code);
    $mallfunctions = DB::table('poll_booths')
                  ->join('pro_evm_malfunctioning','poll_booths.bid', 'pro_evm_malfunctioning.bid')
                  ->join('users_pollday','pro_evm_malfunctioning.uid', 'users_pollday.uid')
                  ->where('pro_evm_malfunctioning.state_id', $state_id)
                  ->where('pro_evm_malfunctioning.dist_code', $dist_code)
                  ->where('pro_evm_malfunctioning.cons_code', $firstCons)
                  ->where('pro_evm_malfunctioning.status', 0)
                  ->get();

    $mallfunctions_resolve = DB::table('poll_booths')
                           ->join('pro_evm_malfunctioning','poll_booths.bid', 'pro_evm_malfunctioning.bid')
                           ->join('users_pollday','pro_evm_malfunctioning.uid', 'users_pollday.uid')
                           ->select('poll_booths.bid','poll_booths.poll_building','poll_booths.poll_building_detail','poll_booths.ps_id','users_pollday.name','pro_evm_malfunctioning.id','pro_evm_malfunctioning.reply')
                           ->where('pro_evm_malfunctioning.state_id', $state_id)
                           ->where('pro_evm_malfunctioning.dist_code', $dist_code)
                           ->where('pro_evm_malfunctioning.cons_code', $firstCons)
                           ->where('pro_evm_malfunctioning.status', 1)
                           ->get();

    return view('deo/evm-malfunction', [
      'constituency' => $constituency,
      'encConsCode' => $encConsCode,
      'mallfunctions' => $mallfunctions,
      'mallfunctions_resolve' => $mallfunctions_resolve,
    ]);
  }


  public function evmMalfunctionSub(Request $request) {
    $user = Auth::user();
    $this->validate(
      $request, [
        'cons_code' => 'required',
      ],
      [
        'cons_code.required' => 'This field is required',
      ]
    );
    $state_id = $user->state_id;
    $dist_code = $user->dist_code;
    $constituency = DB::table('constituencies')
                  ->where('dist_code', $dist_code)
                  ->where('state_id', $state_id)
                  ->get();

    $dcryptCons=eci_decrypt($request->cons_code);
    $encConsCode=$request->cons_code;
    $mallfunctions = DB::table('poll_booths')
                  ->join('pro_evm_malfunctioning','poll_booths.bid', 'pro_evm_malfunctioning.bid')
                  ->join('users_pollday','pro_evm_malfunctioning.uid', 'users_pollday.uid')
                  ->where('pro_evm_malfunctioning.state_id', $state_id)
                  ->where('pro_evm_malfunctioning.dist_code', $dist_code)
                  ->where('pro_evm_malfunctioning.cons_code', $dcryptCons)
                  ->where('pro_evm_malfunctioning.status', 0)
                  ->get();

    $mallfunctions_resolve = DB::table('poll_booths')
                           ->join('pro_evm_malfunctioning','poll_booths.bid', 'pro_evm_malfunctioning.bid')
                           ->join('users_pollday','pro_evm_malfunctioning.uid', 'users_pollday.uid')
                           ->select('poll_booths.bid','poll_booths.poll_building','poll_booths.poll_building_detail','poll_booths.ps_id','users_pollday.name','pro_evm_malfunctioning.id','pro_evm_malfunctioning.reply')
                           ->where('pro_evm_malfunctioning.state_id', $state_id)
                           ->where('pro_evm_malfunctioning.dist_code', $dist_code)
                           ->where('pro_evm_malfunctioning.cons_code', $dcryptCons)
                           ->where('pro_evm_malfunctioning.status', 1)
                           ->get();

    return view('deo/evm-malfunction', [
      'constituency' => $constituency,
      'encConsCode' => $encConsCode,
      'mallfunctions' => $mallfunctions,
      'mallfunctions_resolve' => $mallfunctions_resolve,
    ]);
  }


  public function pwdVoters(){
    $user = Auth::user();
    $state_id = $user->state_id;
    $dist_code = $user->dist_code;
    $constituency = DB::table('constituencies')
                  ->where('dist_code', $dist_code)
                  ->where('state_id', $state_id)
                  ->get();

    $constituencyFirst = DB::table('constituencies')
                       ->where('dist_code', $dist_code)
                       ->where('state_id', $state_id)
                       ->first();

    $firstCons=$constituencyFirst->cons_code;
    $encConsCode=eci_encrypt($constituencyFirst->cons_code);
    $getPwd=getPwdVoter($firstCons);
    $getPwdVoter  = json_decode($getPwd);

    return view('deo/pwd-voters', [
      'constituency' => $constituency,
      'encConsCode' => $encConsCode,
      'getPwdVoter' => $getPwdVoter,
    ]);
  }


  public function pwdVoterSub(Request $request) {
    $user = Auth::user();
    $this->validate(
      $request, [
        'cons_code' => 'required',
      ],
      [
        'cons_code.required' => 'This field is required',
      ]
    );
    $state_id = $user->state_id;
    $dist_code = $user->dist_code;
    $constituency = DB::table('constituencies')
                  ->where('dist_code', $dist_code)
                  ->where('state_id', $state_id)
                  ->get();

    $dcryptCons=eci_decrypt($request->cons_code);
    $encConsCode=$request->cons_code;
    $getPwd=getPwdVoter($dcryptCons);
    $getPwdVoter  = json_decode($getPwd);

    return view('deo/pwd-voters', [
      'constituency' => $constituency,
      'encConsCode' => $encConsCode,
      'getPwdVoter' => $getPwdVoter,
    ]);
  }


  public function videoRecording(){
    $user = Auth::user();
    $state_id = $user->state_id;
    $dist_code = $user->dist_code;
    $constituency = DB::table('constituencies')
                  ->where('dist_code', $dist_code)
                  ->where('state_id', $state_id)
                  ->get();

    $constituencyFirst = DB::table('constituencies')
                       ->where('dist_code', $dist_code)
                       ->where('state_id', $state_id)
                       ->first();

    $firstCons=$constituencyFirst->cons_code;
    $encConsCode=eci_encrypt($constituencyFirst->cons_code);
    $proVideo = DB::table('pro_videography')
                  ->where('state_id', $state_id)
                  ->where('dist_code', $dist_code)
                  ->where('cons_code', $firstCons)
                  ->get();

    return view('deo/video-recording', [
      'proVideo' => $proVideo,
      'encConsCode' => $encConsCode,
      'constituency' => $constituency,
    ]);
  }



  
   public function videoRecordingSub(Request $request) {
    $user = Auth::user();
    $this->validate(
      $request, [
        'cons_code' => 'required',
      ],
      [
        'cons_code.required' => 'This field is required',
      ]
    );
    $state_id = $user->state_id;
    $dist_code = $user->dist_code;
    $constituency = DB::table('constituencies')
                  ->where('dist_code', $dist_code)
                  ->where('state_id', $state_id)
                  ->get();

    $dcryptCons=eci_decrypt($request->cons_code);
    $encConsCode=$request->cons_code;
    $proVideo = DB::table('pro_videography')
                  ->where('state_id', $state_id)
                  ->where('dist_code', $dist_code)
                  ->where('cons_code', $dcryptCons)
                  ->get();

    return view('deo/video-recording', [
      'proVideo' => $proVideo,
      'encConsCode' => $encConsCode,
      'constituency' => $constituency,
    ]);

  }


  public function addAfterPollVideo(){
    return view('deo/add-afterPoll-video');
  }


  
  public function addAfterPollVideoSub (Request $request) {
    $user = Auth::user();
    $this->validate(
      $request, [
        'consCode' => 'required',
        'videoUrl' => 'required|url',
        'description' => 'required',
      ],
      [
        'consCode.required' => 'This field is required',
        'videoUrl.required' => 'This field is required',
        'videoUrl.url' => 'Please add a valid URL',
        'description.required' => 'This field is required',
      ]
    );

    $state_id = $user->state_id;
    $dist_code = $user->dist_code;
    $consCode=$request->consCode;
    $videoUrl=$request->videoUrl;
    $description=$request->description;
    $dt = Carbon::now();
    $timestamp=$dt->toDateString();

    $addVideo = array(
      'state_id' => $state_id,
      'dist_code' => $dist_code,
      'cons_code' => $consCode,
      'videoUrl' => $videoUrl,
      'videoDescription' => $description,
      'updated_at' => $timestamp,
    );  
    $video = DB::table('pro_videography')->insert($addVideo);
    if($video>0){
      Session::flash('videoMsz', 'Video added successfully'); 
      Session::flash('alert-class', 'alert-success');
      return Redirect::to('deo/video-recording');
    }
    else{
      Session::flash('videoMsz', 'Please try again.'); 
      Session::flash('alert-class', 'alert-danger');
      return Redirect::to('deo/video-recording');
    }
  }


 public function voterslipDataResult(Request $request) 
    {
      $this->validate(
      $request, [
        'cons_code' => 'required', 
      ],
      [
        'cons_code.required' => 'Please select constituency', 
      ]
      );
      $user = Auth::user();
      $distCode =Auth::user()->dist_code;
      $encryptCons=$request->cons_code;
      $consCode=eci_decrypt($request->cons_code);
	  
  	  $uidDeo=Auth::user()->uid;
  	  $distCodeDeo = Auth::user()->dist_code;
  	  $statIdDeo = Auth::user()->state_id;
  		
  	  $constituency=DB::table('constituencies')
  					->where('dist_code', $distCodeDeo)
  					->get();
  	  $constituencyFirst=DB::table('constituencies')
  		->where('dist_code', $distCodeDeo)
  		->first();


  	  $firstCons=$constituencyFirst->cons_code;
  	  $encryptCons=eci_encrypt($constituencyFirst->cons_code);
  		
  	  $voterslipData = DB::table('ro_voter_slips_data')
                                      ->join('constituencies', 'ro_voter_slips_data.cons_code','constituencies.cons_code')
                                      ->where('ro_voter_slips_data.dist_code', $distCodeDeo)
  									->where('ro_voter_slips_data.state_id', $statIdDeo)
  									->where('ro_voter_slips_data.cons_code', $consCode)
                                      ->get();
  									
  	  return view('deo/voter-slip-data', [
  		  'voterslipData' => $voterslipData,
  		  'constituency' => $constituency,
  		  'encryptCons' => $encryptCons,
  	  ]);
    }

   
    public function addPatwariCsv(){
      return view('deo/add-patwari-csv');
    }


    public function addPatwariCsvSub(Request $request) {
      Excel::load(Input::file('patwariSheet'), function ($reader) {
          foreach ($reader->toArray() as $row) {
              $patwariName=trim($row['name']);
              $patwariPhone=trim($row['phone_number']);
              $patwariPhoneTrim = ltrim($patwariPhone, '0');
              $patwariDistict=trim($row['distict_code']);
              $patwariCons=trim($row['ac_no']);
              $patwariPollBooth=trim($row['poll_booth']);
              $patwariState="53";
              $uid="PAT".$patwariPhoneTrim;
              $role="8";
              $password=Hash::make("01Synergy");

              $dt = Carbon::now();
              $timestamp=$dt->toDateString();
            
              $findPat=DB::table('users')
                           ->where('role', $role)
                           ->where('phone', $patwariPhone)
                           ->first();

              if(!empty($findPat)){

                  $upUser = array(
                    'uid' => $uid,
                    'name' => $patwariName,
                    'phone' => $patwariPhoneTrim,
                  );
                  $updateUser = DB::table('users')
                              ->where('dist_code', $patwariDistict)
                              ->where('cons_code', $patwariCons)
                              ->where('role', $role)
                              ->where('uid', $patwariPhoneTrim)
                              ->update($upUser);
              }
              else{
                  $addPat = array(
                    'role' => $role,
                    'state_id' => $patwariState,
                    'dist_code' => $patwariDistict,
                    'cons_code' => $patwariCons,
                    'uid' => $uid,
                    'name' => $patwariName,
                    'phone' => $patwariPhoneTrim,
                    'password' => $password,
                    'updated_at' => $timestamp,
                  );  
                  $addPatwari = DB::table('users')->insert($addPat);
              }

              $patwariConsThree = str_pad($patwariCons, 3, '0', STR_PAD_LEFT);
              $patwariPollBoothSep = explode(",", $patwariPollBooth);
              foreach ($patwariPollBoothSep as $patwariPollBoothSeps) {
                  $patwariPollBoothThree = str_pad($patwariPollBoothSeps, 3, '0', STR_PAD_LEFT);
                  $bidPatwari=$patwariDistict.$patwariConsThree.$patwariPollBoothThree;
                  $findPatBid=DB::table('poll_booth_patwari')
                          ->where('bid', $bidPatwari)
                          ->first();

                  if(!empty($findPatBid)){
                    $upUser = array(
                      'uid' => $uid,
                    );
                    $updateUser = DB::table('poll_booth_patwari')
                                ->where('bid', $bidPatwari)
                                ->update($upUser);
                  }
                  else{
                    $addPatBid = array(
                      'bid' => $bidPatwari,
                      'uid' => $uid,
                    );  
                    $addPatwariBid = DB::table('poll_booth_patwari')->insert($addPatBid);

                  }
              }
          }
          Session::put('addPatwari', 'Patwari added Successfully.');
          return Redirect::to('deo/add-patwari-csv')->send();
      });
    }

    public function facilities($bid){
        $user = Auth::user();
        $bid=eci_decrypt($bid);
        $polling_facility = DB::table('poll_booths_web')
                           ->where('bid', $bid)
                           ->first();
                          // dd($polling_facility);
        return view('deo/facilities', [
           'polling_facility' => $polling_facility,
        ]);
    }

   
}

