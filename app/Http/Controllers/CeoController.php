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
use Excel;
use Config;

class CeoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('ceo');
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
                
        // Deo List
        $deolist = DB::table('users')
                  ->join('districts','districts.dist_code','=','users.dist_code')
                  ->where('users.role', '3')
                  ->where('users.state_id', $state_id)
                  ->select('users.name','districts.dist_name as dist_name','users.phone')
                  ->orderby('districts.dist_name')
                  ->limit(5)
                  ->get();
        
       //EVM And VVPAT
        $first_evm = strtotime(Config::get('constants.FIRST_RANDOMIZATION_EVM_DATE'));
        $second_evm = strtotime(Config::get('constants.SECOND_RANDOMIZATION_EVM_DATE'));
        $today = time();

        if($second_evm <= $today){
            $evmlist = DB::table('constituencies')
                        ->join('districts','districts.dist_code','=','constituencies.dist_code')
                        ->whereNotIn('constituencies.cons_code', function($query) use($state_id)
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
                        ->whereNotIn('constituencies.cons_code', function($query)  use($state_id)
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
                    ->groupBy('districts.dist_code', 'districts.dist_name')
                    ->orderBy('districts.dist_code')
                    ->limit(5)                    
                    ->get();
       
        // Electrol Rolls
        $voterlist = DB::table('districts')
                    ->leftjoin('voters_count', 'districts.dist_code', '=', 'voters_count.dist_code')
                    ->select('districts.dist_code', 'districts.dist_name',DB::raw('IFNULL(SUM(voters_count.total_voters), 0) as total'))
                    ->groupBy('districts.dist_code', 'districts.dist_name')
                    ->orderBy('districts.dist_code')
                    ->limit(5) 
                    ->get();


        return view('ceo/dashboard', [
           'deolist' => $deolist,
           'evmlist' => $evmlist,
           'stafflist' => $staff,
           'pollstationlist' => $pollstationlist,
           'voterlist' => $voterlist,
        ]);
    }

    public function pagenotfound(){
        $users = Auth::user();
        return view('ceo/pagenotfound');
    }

    public function pendingEvmVvpat()
    { 
        $user = Auth::user();
        $state_id = $user->state_id;
        $first_evm = strtotime(Config::get('constants.FIRST_RANDOMIZATION_EVM_DATE'));
        $second_evm = strtotime(Config::get('constants.SECOND_RANDOMIZATION_EVM_DATE'));
        $today = time();

        if($second_evm <= $today){
            $evmlist = DB::table('constituencies')
                        ->join('districts','districts.dist_code','=','constituencies.dist_code')
                        ->whereNotIn('constituencies.cons_code', function($query) use($state_id)
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

        return view('ceo/pending-evm-vvpat', [
        'evmlist' => $evmlist,
        ]);
    }

    public function distElectrolList(){
        $user = Auth::user();
        $state_id =  $user->state_id;
        $voterlist = DB::table('districts')
                    ->leftjoin('voters_count', 'districts.dist_code', '=', 'voters_count.dist_code')
                    ->select('districts.dist_code', 'districts.dist_name',DB::raw('IFNULL(SUM(voters_count.total_voters), 0) as total'))
                    ->groupBy('districts.dist_code', 'districts.dist_name')
                    ->orderBy('districts.dist_code')
                    ->get();

        return view('ceo/dist-electrollist', [
        'voterlist' => $voterlist,
        ]);
    }

    public function consElectrolList($dist_code){
        $user = Auth::user();
        $dist_code = eci_decrypt($dist_code);
        $state_id =  $user->state_id;
        $voterlist = DB::table('constituencies')
                    ->leftjoin('voters_count', 'constituencies.cons_code', '=', 'voters_count.cons_code')
                    ->select('constituencies.cons_code', 'constituencies.cons_name',DB::raw('IFNULL(SUM(voters_count.total_voters), 0) as total'))
                    ->where('constituencies.dist_code','=',$dist_code)
                    ->groupBy('constituencies.cons_code', 'constituencies.cons_name')
                    ->orderBy('constituencies.cons_code')
                     ->get();
       return view('ceo/cons-electrollist', [
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
                    ->leftjoin('voters', function($join) { 
                                                $join->on('voters.cons_code', '=', 'poll_booths.cons_code')
                                                ->on('voters.ps_id', '=', 'poll_booths.ps_id')
                                                ->on('voters.state_id', '=', 'poll_booths.state_id')
                                                ->on('voters.dist_code', '=', 'poll_booths.dist_code'); 
                                            })

                    ->select('poll_booths.ps_id', 'poll_booths.poll_building',DB::raw('IFNULL(COUNT(voters.id), 0) as total'))
                    ->groupBy('poll_booths.ps_id', 'poll_booths.poll_building')
                    ->orderBy('poll_booths.ps_id')
                    ->where('poll_booths.state_id',$state_id)
                    ->where('poll_booths.dist_code',$dist_code)
                    ->where('poll_booths.cons_code',$cons_code)
                    ->get();
        return view('ceo/ps-electrollist', [
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
        $voterlist = DB::table('voters')
                ->where('cons_code', $cons_code)
                ->where('state_id', $state_id)
                ->where('dist_code', $dist_code)
                ->where('ps_id', $ps_id)
                ->get();
        return view('ceo/electrollist', [
        'voterlist' => $voterlist,
        ]);

    }

    public function voterDetail($iCard)
    {
        $user = Auth::user();
        $iCardNo = eci_decrypt($iCard);
        $voterDetail = voter_details($iCardNo);
 
        if(@$voterDetail){

            $voterDetail = json_decode($voterDetail);
            $pollDayDetail = poll_booth_details($voterDetail->state_id, $voterDetail->dist_code, $voterDetail->cons_code, $voterDetail->ps_id);
            $pollDayDetail = json_decode($pollDayDetail);
            //dd($pollDayDetail);
        }else{
            $voterDetail = array();
        }
        //dd($voterDetail);
        return view('ceo/voter-detail', [
           'voterDetail' => $voterDetail,
           'pollDayDetail' => $pollDayDetail,
        ]);
    }


    public function nominationReceived()
    {
        $user = Auth::user();
        $state_id = $user->state_id;
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
        $getNomination = DB::table('users')
                         ->join('users_candidate_data', 'users.uid', '=', 'users_candidate_data.uid')
                         ->where('users.role', '15')
                         ->where('users.cons_code', $user->cons_code)
                         //->where('users_candidate_data.nominationDate', 'like', '%' . date("Y-m-d") . '%')
                         ->get();
        return view('ceo/nomination-received', [
           'getNomination' => $getNomination,
           'district' => $district,
           'encryptDist' => $encryptDist,
           'encryptCons' => $encryptCons,
           'constituency' => $constituency,
        ]);
    }


    public function nominationReceivedSearch(Request $request)
    {   
        $this->validate(
        $request, 
        [
          'cons_code' => 'required',
          'dist_code' => 'required'
        ],
        [
          'cons_code.required' => 'Please Select Constituency',
          'dist_code.required' => 'Please Select District'
        ]
        );

        $user = Auth::user();
        $encryptDist=$request->dist_code;
        $encryptCons=$request->cons_code;
        $cons_code_dcr = eci_decrypt($request->cons_code);
        $dist_code_dcr = eci_decrypt($request->dist_code);
        $state_id = $user->state_id;

        $district=DB::table('districts')
                 ->where('state_id', $state_id)
                 ->get();

     
        $constituency=DB::table('constituencies')
                    ->where('dist_code', $dist_code_dcr)
                    ->get();

       
        $getNomination = DB::table('users')
                         ->join('users_candidate_data', 'users.uid', '=', 'users_candidate_data.uid')
                         ->where('users.role', '15')
                         ->where('users.cons_code', $cons_code_dcr)
                         ->where('users.dist_code', $dist_code_dcr)
                         //->where('users_candidate_data.nominationDate', 'like', '%' . date("Y-m-d") . '%')
                         ->get();

        return view('ceo/nomination-received', [
           'getNomination' => $getNomination,
           'district' => $district,
           'encryptDist' => $encryptDist,
           'encryptCons' => $encryptCons,
           'constituency' => $constituency,
        ]);
    }


    public function nominationReceivedpost(Request $request)
    {
        $user = Auth::user();
        $cons_code = eci_decrypt($request->cons_code);
        $dist_code = eci_decrypt($request->dist_code);
        $getNomination = DB::table('users')
                         ->join('users_candidate_data', 'users.uid', '=', 'users_candidate_data.uid')
                         ->where('users.role', '15')
                         ->where('users.cons_code', $cons_code)
                         ->where('users.dist_code', $dist_code)
                         ->get();
        return view('ceo/nomination-received', [
           'getNomination' => $getNomination,
        ]);
    }

    public function candidateDetail($uid)
    {
        $user = Auth::user();
        $uidDcr=eci_decrypt($uid);

        $candidateDetail = DB::table('users')
                         ->join('users_candidate_data', 'users.uid', '=', 'users_candidate_data.uid')
                         ->join('districts', 'users.dist_code', '=', 'districts.dist_code')
                         ->leftJoin('symbols', 'users_candidate_data.cand_symbol', '=', 'symbols.symbol_no')
                         ->leftJoin('constituencies', 'users_candidate_data.cons_code', '=', 'constituencies.cons_code')
                         ->where('users.uid', $uidDcr)
                         ->first();

        return view('ceo/candidate-detail', [
           'candidateDetail' => $candidateDetail,
        ]);
    }

    public function nominationRejected()
    {
        $user = Auth::user();

        $state_id = $user->state_id;
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

        $getNomination = DB::table('users')
                         ->join('users_candidate_data', 'users.uid', '=', 'users_candidate_data.uid')
                         ->where('users.role', '15')
                         ->where('users.cons_code', $firstCons)
                         ->where('users_candidate_data.nominationStatus', 'R')
                         ->get();
        
        return view('ceo/nomination-rejected', [
           'getNomination' => $getNomination,
           'district' => $district,
           'encryptDist' => $encryptDist,
           'encryptCons' => $encryptCons,
           'constituency' => $constituency,
        ]);

    }

    public function nominationRejectedSearch(Request $request)
    {   

        $this->validate(
        $request, 
        [
          'cons_code' => 'required',
          'dist_code' => 'required'
        ],
        [
          'cons_code.required' => 'Please Select Constituency',
          'dist_code.required' => 'Please Select District'
        ]
        );
        $user = Auth::user();
        $encryptDist=$request->dist_code;
        $encryptCons=$request->cons_code;
        $cons_code_dcr = eci_decrypt($request->cons_code);
        $dist_code_dcr = eci_decrypt($request->dist_code);
        $state_id = $user->state_id;

        $district=DB::table('districts')
                 ->where('state_id', $state_id)
                 ->get();

        $constituency=DB::table('constituencies')
                    ->where('dist_code', $dist_code_dcr)
                    ->get();

        $getNomination = DB::table('users')
                         ->join('users_candidate_data', 'users.uid', '=', 'users_candidate_data.uid')
                         ->where('users.role', '15')
                         ->where('users.cons_code', $cons_code_dcr)
                         ->where('users.dist_code', $dist_code_dcr)
                         ->where('users_candidate_data.nominationStatus', 'R')
                         ->get();
        
        return view('ceo/nomination-rejected', [
           'getNomination' => $getNomination,
           'district' => $district,
           'encryptDist' => $encryptDist,
           'encryptCons' => $encryptCons,
           'constituency' => $constituency,
        ]);
    }

	  public function nominationWithdrawls()
    {
        $user = Auth::user();


        $state_id = $user->state_id;
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

        $getNomination = DB::table('users')
                       ->join('users_candidate_data', 'users.uid', '=', 'users_candidate_data.uid')
                       ->where('users.role', '15')
                       ->where('users.cons_code', $firstCons)
                       ->where('users_candidate_data.nominationStatus', 'W')
                       ->get();
        
        return view('ceo/nomination-withdrawls', [
          'getNomination' => $getNomination,
          'district' => $district,
          'encryptDist' => $encryptDist,
          'encryptCons' => $encryptCons,
          'constituency' => $constituency,
        ]);
    }

    public function nominationWithdrawlsSearch(Request $request)
    {   

        $this->validate(
        $request, 
        [
          'cons_code' => 'required',
          'dist_code' => 'required'
        ],
        [
          'cons_code.required' => 'Please Select Constituency',
          'dist_code.required' => 'Please Select District'
        ]
        );
        $user = Auth::user();
        $encryptDist=$request->dist_code;
        $encryptCons=$request->cons_code;
        $cons_code_dcr = eci_decrypt($request->cons_code);
        $dist_code_dcr = eci_decrypt($request->dist_code);
        $state_id = $user->state_id;

        $district=DB::table('districts')
                 ->where('state_id', $state_id)
                 ->get();

        $constituency=DB::table('constituencies')
                    ->where('dist_code', $dist_code_dcr)
                    ->get();



        $getNomination = DB::table('users')
                         ->join('users_candidate_data', 'users.uid', '=', 'users_candidate_data.uid')
                         ->where('users.role', '15')
                         ->where('users.cons_code', $cons_code_dcr)
                         ->where('users.dist_code', $dist_code_dcr)
                         ->where('users_candidate_data.nominationStatus', 'W')
                         ->get();
        
        return view('ceo/nomination-withdrawls', [
           'getNomination' => $getNomination,
           'district' => $district,
           'encryptDist' => $encryptDist,
           'encryptCons' => $encryptCons,
           'constituency' => $constituency,
        ]);
    }

	  public function candidateList()
    {


        $user = Auth::user();


        $state_id = $user->state_id;
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
        return view('ceo/candidate-list', [
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
        $state_id = $user->state_id;
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

    public function deoList(){
    //--Get DEO
        $user = Auth::user();
        $getdeolist = DB::table('users')->join('districts', 'users.dist_code', '=', 'districts.dist_code')->where('users.role',3)->get();
        return view('ceo/deo-list', [
          'getdeolist' => $getdeolist,
        ]);
    }

    //--Add Deo Form
    public function addDeo()
    { 
        return view('ceo/add_Deo');
    }

    
    //--Submit Add Deo Form
    public function addDeoSub(Request $request) {

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
                $name=(isset($row['name']))? $row['name'] : "";
                $email=(isset($row['email']))? $row['email'] : "";
                $phone=(isset($row['phone']))? $row['phone'] : "";
                $address=(isset($row['address']))? $row['address'] : "";
                $designation=(isset($row['designation']))? $row['designation'] : "";
                $organisation=(isset($row['organisation']))? $row['organisation'] : "";
                $password=(isset($row['password']))? $row['password'] : "";
                $uidDeo="DEO".$phone;
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
                            $matchDist = DB::table('users')->where('dist_code', $distCode)->where('state_id', $stateIdDeo)->where('role', 3)->get();
                            $distCount=count($matchDist);
                            //--(Update User)
                            if($distCount>0) {
                                foreach($matchDist as $matchDists){
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
                                        'uid' => $uidDeo,
                                        'name' => $name,
                                        'email' => $email,
                                        'phone' => $phone,
                                        'address' => $address,
                                        'designation' => $designation,
                                        'organisation' => $organisation,
                                        'password' => $hashPassword,
                                    );
                                    $updateUser = DB::table('users')->where('dist_code', $distCode)->where('state_id', $stateIdDeo)->where('role', 3)->update($upUserData);
                                }
                            }
                            //--(Insert New User)
                            else{
                                $dt = Carbon::now();
                                $timestamp=$dt->toDateString();
                                $newpassIns=rand(10,1000).time();
                                $hashPasswordins=Hash::make($newpassIns);
                                $insUserData = array(
                                    'uid' => $uidDeo,
                                    'dist_code' => $distCode,
                                    'name' => $name,
                                    'email' => $email,
                                    'phone' => $phone,
                                    'address' => $address,
                                    'designation' => $designation,
                                    'organisation' => $organisation,
                                    'role' => 3,
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
                Session::flash('reqErrDeo', $errorReqRow); 
            }
            
            $distError1 = array_filter($distError);
            if (!empty($distError1)) {
                $errorDstRow1=implode(',', $distError1);
                $errorDstRow="District code is missing on row ".$errorDstRow1;
              }else{
               $errorDstRow=""; 
            }
            if($errorDstRow!==""){
                \Session::flash('DistErrDeo', $errorDstRow);
            }

            $phoneError1 = array_filter($phoneError);
            if (!empty($phoneError1)) {
                $errorPhnRow1=implode(',', $phoneError1);
                $errorPhnRow="Phone number is invalid on row ".$errorPhnRow1;
              }else{
               $errorPhnRow=""; 
            }
            if($errorPhnRow!==""){
                \Session::flash('phoneErrDeo', $errorPhnRow);
            }
        });
        \Session::flash('addDeoSucc', 'DEO list updated successfully. '); 
        return Redirect::to('ceo/deo-list');

    }


//-- Add DEO form
    public function addDeoForm()
    { 
        $user = Auth::user();
        $stateId=Auth::user()->state_id;
        $distList = DB::table('districts')->where('state_id',$stateId)->where('status',1)->get();
        return view('ceo/addDeoForm', [
          'distList' => $distList,
        ]);
    }


//-- Add DEO Form--(Form Submit)
    public function addDeoFormSub(Request $request) {
        $user = Auth::user();
        $this->validate($request, [
            'deoName' => 'required',
            'deoDist' => 'required',
            'deoPhone' => 'required|size:10',
            //'deoofficePhone' => 'required',
            'deoEmail' => 'email',
            'deoPW' => 'required|min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%]).*$/',
            'deoCPW' => 'required|same:deoPW',
        ]);
        $phoneDeo=$request->deoPhone;
        $deoofficePhone=$request->deoofficePhone;
        $officeDeophone="DEO".$deoofficePhone;
        $uidDeo="DEO".$phoneDeo;
        $distDeo=$request->deoDist;
        $stateDeo=Auth::user()->state_id;
        $password=(isset($request['deoPW']))? $request['deoPW'] : "";
        $phoneRepeat=DB::table('users')
                    ->where('phone', $phoneDeo)
                    ->where('dist_code', '!=', $distDeo)
                    ->first();
        if(!empty($phoneRepeat)){
            \Session::flash('addDeoError', 'Phone number is already used. '); 
            return Redirect::to('ceo/addDeoForm');
        }
        else{
            $distRepeat = DB::table('users')
                        ->where('dist_code', $distDeo)
                        ->where('state_id', $stateDeo)
                        ->where('role', 3)
                        ->first();

            if(!empty($distRepeat)) {
                //--Genrate Password
                if($password!==""){
                    $upPass=Hash::make($password);
                }
                else{
                    if(($distRepeat->password)==""){
                        $randPass=rand(10,1000).time();
                        $upPass=Hash::make($randPass);
                    }
                    else{
                        $upPass=$distRepeat->password;
                    }
                }
                $upDeo = array(
                    'uid' => $uidDeo,
                    'name' => $request->deoName,
                    'email' => $request->deoEmail,
                    'phone' => $phoneDeo,
                    'office_phone' => $deoofficePhone,
                    'address' => $request->deoAddress,
                    'designation' => $request->deoDesignation,
                    'organisation' => $request->deoOrganization,
                    'password' => $upPass,
                );
                $updateDeo = DB::table('users')->where('dist_code', $distDeo)->where('state_id', $stateDeo)->where('role', 3)->update($upDeo);
                \Session::flash('addDeoSucc', 'DEO updated successfully. '); 
                return Redirect::to('ceo/deo-list');
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
                $addDeo = array(
                    'uid' => $uidDeo,
                    'dist_code' => $distDeo,
                    'name' => $request->deoName,
                    'email' => $request->deoEmail,
                    'phone' => $phoneDeo,
                    'office_phone' => $deoofficePhone,
                    'address' => $request->deoAddress,
                    'designation' => $request->deoDesignation,
                    'organisation' => $request->deoOrganization,
                    'role' => 3,
                    'password' => $addPass,
                    'state_id' => $stateDeo,
                    'updated_at' => $timestamp,
                ); 
                $addNewDeo = DB::table('users')->insert($addDeo);
                \Session::flash('addDeoSucc', 'DEO added successfully. '); 
                return Redirect::to('ceo/deo-list');
            }
        }
    }
    
    
    public function editDeo($uid)
    {
        $user = Auth::user();
        $uidDcrypt=eci_decrypt($uid);

        $getDeo = DB::table('users')
                 ->join('districts','districts.dist_code','=','users.dist_code')
                 ->where('uid', $uidDcrypt)
                 ->where('role', 3)
                 ->first();
        return view('ceo/edit_deo', [
            'getDeo' => $getDeo,
        ]);
    }


    public function editDeoSub(Request $request) {
        $user = Auth::user();
        $this->validate($request, [
            'deoNameEdit' => 'required',
            'deoDistEdit' => 'required',
            'deoPhoneEdit' => 'required|size:10',
            //'deoofficePhoneEdit' => 'required',
            'deoEmailEdit' => 'email',
            'deoPWEdit' => 'min:6',
        ]);

        $uidDcrypt=eci_decrypt($request->uidDeo);

        $passwordEdit=(isset($request['deoPWEdit']))? $request['deoPWEdit'] : "";
        if($passwordEdit!==""){
            $editPass=Hash::make($passwordEdit);
        }
        else{
            $editPass=$request->deoPWOld;
        }

        $distDeo=$request->deoDistEdit;
        $phoneEdit=$request->deoPhoneEdit;
        $uidEdit="DEO".$phoneEdit;

        $officephoneEdit=$request->deoofficePhoneEdit;
        $officeEdit="DEO".$officephoneEdit;

        $editDeo = array(
            'uid' => $uidEdit,
            'name' => $request->deoNameEdit,
            'email' => $request->deoEmailEdit,
            'phone' => $phoneEdit,
            'office_phone' => $officephoneEdit,
            'address' => $request->deoAddressEdit,
            'designation' => $request->deoDesignationEdit,
            'organisation' => $request->deoOrganizationEdit,
            'password' => $editPass,
        );

        $updateDeo = DB::table('users')
                    ->where('uid', $uidDcrypt)
                    ->where('dist_code', $distDeo)
                    ->where('role', 3)
                    ->update($editDeo);

        \Session::flash('addDeoSucc', 'DEO updated successfully. '); 
        return Redirect::to('ceo/deo-list');
    }

    public function deleteDeo(Request $request) {
        $user = Auth::user();
        $post = $request->all();
        $id=$post['id'];
        $uidDcrypt=eci_decrypt($id);

        $delCeo=DB::table('users')->where('uid', $uidDcrypt)->delete();
        if($request->ajax()){
            if($delCeo!==""){
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


//--Supervisor Booth Form
    public function supBooth()
    { 
        return view('ceo/supBooth');
    }

//--Supervisor Booth Form--(Form Submit)
    public function supBoothSub(Request $request)
    {
        Excel::load(Input::file('supViseBooth'), function ($reader) {
            foreach ($reader->toArray() as $row) {

                $distSup=trim($row['dist_code']);
                //$distSup = str_pad($distSup1, 3, '0', STR_PAD_LEFT);
                
                $consSup1=$row['cons_code'];
                $consSup = str_pad($consSup1, 3, '0', STR_PAD_LEFT);
                
                $uidSup=$row['uid'];

                $boothSup1 = trim($row['booth_alloted']);
                $boothSup  = explode(",", trim($boothSup1));
             
                foreach ($boothSup as $boothSups) {

                    $boothSthree = str_pad(trim($boothSups), 3, '0', STR_PAD_LEFT);
                    $bidSup = $distSup.$consSup.$boothSthree;

                    $upSupData = array(
                        'supervisior_uid' => trim($uidSup),   
                    );
                    $upSup = DB::table('poll_booths')->where('bid', $bidSup)->update($upSupData);
                }
            }
            Session::put('supBoothMsz', 'Supervisor Booth Is Updated Successfully.');
            return Redirect::to('ceo/supBooth')->send();
        });
    }

//--Update Booth Type
    public function upBoothType()
    { 
        return view('ceo/upBoothType');
    }

//--Update Booth Type--(Form Submit)
    public function upBoothTypeSub(Request $request) {

        Excel::load(Input::file('upBoothType'), function ($reader) {
            foreach ($reader->toArray() as $row) {


                $distBooth=$row['dist_code'];
                //$distBooth = str_pad($distBooth1, 3, '0', STR_PAD_LEFT);
                
                $consBooth1=$row['cons_code'];
                $consBooth = str_pad($consBooth1, 3, '0', STR_PAD_LEFT);
                
                $poolType=$row['poll_type'];

                $boothComma1=$row['booth_alloted'];
                $boothSep = explode(",", $boothComma1);
             
                foreach ($boothSep as $boothSeps) {

                    $boothSepThree = str_pad($boothSeps, 3, '0', STR_PAD_LEFT);

                    $bidBooth=$distBooth.$consBooth.$boothSepThree;

                    $upSupData = array(
                        'poll_type' => $poolType,   
                    );
                    $upSup = DB::table('poll_booths')->where('bid', $bidBooth)->update($upSupData); 
                }
            }
            Session::put('upBoothTypeMsz', 'Booth Type Is Updated Successfully.');
            return Redirect::to('ceo/upBoothType')->send();
        });
    }

    public function roList($uid){
        //--Get Ro
        $deoId=eci_decrypt($uid);
        $getdeoDetail = DB::table('users')
                      ->where('uid', $deoId)
                      ->where('role',3)
                      ->first();

        $getRoConst = DB::table('users')
                    ->join('constituencies', 'users.cons_code', '=', 'constituencies.cons_code')
                    ->where('users.dist_code',$getdeoDetail->dist_code)
                    ->where('users.role',4)
                    ->get();

        return view('ceo/ro-list', [
          'getRoConst' => $getRoConst,
        ]);
    }
        
    public function supervisorList($id)
    {
        $roId=eci_decrypt($id);
        $getRoDetail = DB::table('users')->join('constituencies', 'users.cons_code', '=', 'constituencies.cons_code')->where('uid', $roId)->where('role',4)->first();
        $supervisors = DB::table('users')->where('dist_code', $getRoDetail->dist_code)->where('cons_code', $getRoDetail->cons_code)->where('role', 5)->get();
        return view('ceo/supervisor-list', [
          'getRoDetail' => $getRoDetail,
          'supervisors' => $supervisors,
        ]);
    }

    public function supervisorDetail($uid)
    {
        $uid = eci_decrypt($uid);
        $polling_stations = DB::table('poll_booths')->where('supervisior_uid', $uid)->where('status', 1)->get();
        $svDetail = DB::table('users')->where('uid', $uid)->first();

        return view('ceo/supervisor-detail', [
         'polling_stations' => $polling_stations,
         'svDetail' => $svDetail,
        ]);
    } 
	
	public function politicalPartiesDistrictHead(){
        $user = Auth::user();
        return view('ceo/political-parties-district-head');
    }
	
	public function politicalPartiesStateHead(){
        $user = Auth::user();
        return view('ceo/political-parties-state-head');
    }	
	
	public function addPoliticalPartiesStateHead(){
        $user = Auth::user();
        return view('ceo/add-political-parties-state-head');
    }

    public function pMinus1Form(){
        $user = Auth::user();
        $stateCeo=Auth::user()->state_id;

        $DEOlist = DB::table('districts')->where('state_id',$stateCeo)->get();
        return view('ceo/p-1Form', [
          'DEOlist' => $DEOlist,
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
        return view('ceo/pol-1View', [
            'polMinus1View' => $polMinus1View,
        ]);
    }

    public function poMinus1View(){
        return view('ceo/pol-1View');  
    }


    public function distPollstationlist(){
        $user = Auth::user();
        $dist_code =  $user->dist_code;
        $state_id =  $user->state_id;

        $distPollstationlist = DB::table('districts')
                        ->leftjoin('poll_booths', 'districts.dist_code', '=', 'poll_booths.dist_code')
                        ->select('districts.dist_code', 'districts.dist_name',DB::raw('IFNULL(COUNT(poll_booths.poll_booth_id), 0) as total'))
                        ->groupBy('districts.dist_code', 'districts.dist_name')
                        ->orderBy('districts.dist_code')                   
                        ->get(  );

        return view('ceo/dist-pollstationlist', [
           'distPollstationlist' => $distPollstationlist,
        ]);
    }


    public function consPollstationlist($distCode){
        $user = Auth::user();
        $dist_code = eci_decrypt($distCode);
        $state_id = $user->state_id;
        $consPollstationlist = DB::table('constituencies')
                        ->leftjoin('poll_booths', 'constituencies.cons_code', '=', 'poll_booths.cons_code')
                        ->select('constituencies.cons_code', 'constituencies.cons_name',DB::raw('IFNULL(COUNT(poll_booths.poll_booth_id), 0) as total'))
                        ->where('constituencies.dist_code','=',$dist_code)
                        ->where('constituencies.state_id','=',$state_id)
                        ->groupBy('constituencies.cons_code', 'constituencies.cons_name')
                        ->orderBy('constituencies.cons_code')
                        ->get();

        return view('ceo/cons-pollstationlist', [
        'consPollstationlist' => $consPollstationlist,
        ]);
    }

    public function consPollingStaff($consCode){
        $user = Auth::user();
        $state_id = $user->state_id;
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
  
        return view('ceo/cons-polling-staff', [
            'pollstafflist' => $pollstafflist,
            'cons_code' => $consCode,
            'mapVisiblility' => $mapVisiblility,
        ]);
    }

    public function pollingStationsMap($consCode)
    {   
        $user = Auth::user();
        $state_id = $user->state_id;
        $consDcrypt=eci_decrypt($consCode);
        $polling_stations = DB::table('users')
                            ->join('poll_booths', 'users.uid', '=', 'poll_booths.supervisior_uid')
                            ->where('users.state_id', $state_id)
                            ->where('users.cons_code', $consDcrypt)
                            ->get();
        //dd($polling_stations);
        return view('ceo/polling-stations-map', [
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
        return view('ceo/polling-detail', [
           'polling_detail' => $polling_detail,
           'polling_facility' => $polling_facility,
        ]);
    }

    public function electoralRolls(){
      $user = Auth::user();
      $distCeo = Auth::user()->dist_code;
      $stateCeo = Auth::user()->state_id;

      $constituency = DB::table('constituencies')
                  ->where('dist_code', $distCeo)
                  ->where('state_id', $stateCeo)
                  ->get();
      return view('ceo/electoral-rolls', [
          'constituency' => $constituency,
      ]);
    }


    public function electoralRollsSubmit(Request $request) 
    {
        $post = $request->all();
        $districtIdEnc=$_GET['dist_code'];
        $distDcrypt=eci_decrypt($districtIdEnc);

        $consIdEnc=$_GET['cons_code'];
        $consDcrypt=eci_decrypt($consIdEnc);

        $psEnc=$_GET['ps_id'];
        $psDcrypt=eci_decrypt($psEnc);

        $user = Auth::user();
        $stateCeo = Auth::user()->state_id;
        
        //die;
        $poll_station = DB::table('poll_booths')
                ->where('dist_code', $distDcrypt)
                ->where('cons_code', $consDcrypt)
                ->where('state_id', $stateCeo)
                ->select('ps_id','poll_building') 
                ->orderby('ps_id')
                ->get();

        $votersList = DB::table('voters')
                ->where('dist_code', $distDcrypt)
                ->where('cons_code', $consDcrypt)
                ->where('state_id', $stateCeo)
                ->where('ps_id', $psDcrypt)
                ->get();
        if(@$voterlist){

        }else{
            $votersListAPI = app('App\Http\Controllers\CronjobController')->get_voter_list($stateCeo,$distDcrypt,$consDcrypt,$psDcrypt);
            //dd($votersListAPI);
            $votersList = json_decode($votersListAPI);
        }

        return view('ceo/electoral-rolls', [
        'poll_station' => $poll_station,
        'votersList' => $votersList,
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
        return view('ceo/booth-awareness-group', [
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
        return view('ceo/polling-parties-details', [
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

        
        return view('ceo/booth-photos', [
           'state_id' => $state_id,
           'dist_code' => $dist_code,
           'cons_code' => $cons_code,
           'ps_id' => $ps_id,
       ]);
    }


    public function pollingStations() {
        $user = Auth::user();
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

        // $pollStation = DB::table('users')
        //              ->join('poll_booths', 'users.uid', '=', 'poll_booths.supervisior_uid')
        //              ->join('constituencies', 'users.cons_code', '=', 'constituencies.cons_code')
        //              ->where('users.dist_code', $firstDist)
        //              ->where('users.cons_code', $firstCons)
        //              ->where('users.state_id', $state_id)
        //              ->get();



        $pollStation = DB::table('poll_booths')
                     ->leftjoin('users', 'users.uid', '=', 'poll_booths.supervisior_uid')
                     ->join('constituencies', 'poll_booths.cons_code', '=', 'constituencies.cons_code')
                     ->where('poll_booths.dist_code', $firstDist)
                     ->where('poll_booths.cons_code', $firstCons)
                     ->where('poll_booths.state_id', $state_id)
                     ->get();



        $mapVisible = DB::table('users')
                    ->join('poll_booths', 'users.uid', '=', 'poll_booths.supervisior_uid')
                    ->where('users.state_id', $state_id)
                    ->where('users.dist_code', $firstDist)
                    ->where('users.cons_code', $firstCons)
                    ->first();

        if(!empty($mapVisible)){
            $mapVisiblility=1;
        }else{
            $mapVisiblility=0;
        }

      return view('ceo/polling-stations', [
        'pollStation' => $pollStation,
        'district' => $district,
        'constituency' => $constituency,
        'encryptDist' => $encryptDist,
        'encryptCons' => $encryptCons,
        'mapVisiblility' => $mapVisiblility,
      ]);
    }

    public function ceoPollStationSub(Request $request) {
        $user = Auth::user();
        $this->validate($request, [
            'dist_code' => 'required',
            'cons_code' => 'required',
        ]);
        $encryptDist=$request->dist_code;
        $encryptCons=$request->cons_code;
        $consCode=eci_decrypt($request->cons_code);
        $distCode=eci_decrypt($request->dist_code);
        $state_id = $user->state_id;

        $pollStation = DB::table('poll_booths')
                     ->leftjoin('users', 'users.uid', '=', 'poll_booths.supervisior_uid')
                     ->join('constituencies', 'poll_booths.cons_code', '=', 'constituencies.cons_code')
                     ->where('poll_booths.dist_code', $distCode)
                     ->where('poll_booths.cons_code', $consCode)
                     ->where('poll_booths.state_id', $state_id)
                     ->get();


        $district=DB::table('districts')
                 ->where('state_id', $state_id)
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
        return view('ceo/polling-stations', [
            'pollStation' => $pollStation,
            'district' => $district,
            'constituency' => $constituency,
            'encryptDist' => $encryptDist,
            'encryptCons' => $encryptCons,
            'mapVisiblility' => $mapVisiblility,
        ]);
    }


    public function getPollingCons(Request $request) {
        $user = Auth::user();
        $state_id = $user->state_id;
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

    public function getpslist(Request $request) {
        $user = Auth::user();
        //$dist_code = Auth::user()->dist_code;
        
        $state_id = Auth::user()->state_id;
        $post = $request->all();
        $cons_code=$_POST['cons_code'];
        $cons_code=eci_decrypt($cons_code);

        $dist_code=$_POST['dist_code'];
        $dist_code=eci_decrypt($dist_code);

        if($cons_code=="" || $dist_code==""){
          if($request->ajax()){
            $statusResult='0';
            return response()->json([
              'statusResult' => $statusResult,
            ]);
          }
        }
        else {
            $pslist = DB::table('poll_booths')
                          ->where('state_id', $state_id)
                          ->where('dist_code', $dist_code)
                          ->where('cons_code', $cons_code)->get();

            foreach ($pslist as $psid) {
              $ps_id[] = eci_encrypt($psid->ps_id);
            }
            if($request->ajax()){
                $statusResult = '1';
                return response()->json([
                    'pslist' => $pslist,
                    'ps_id' => $ps_id,
                    'statusResult' => $statusResult,
                ]);
            }
        }
    }
	
	//-- Poll day report
    public function pollDayReport() {
		$user = Auth::user();
		$state_id = $user->state_id;
		$district=DB::table('districts')
						->where('state_id', $state_id)
						->get();
		return view('ceo/poll-day', [
			'district' => $district,
		]);
    }


  public function pollDay(Request $request) {
		$this->validate($request, [
		'dist_code' => 'required',
		'cons_code' => 'required',
		]);
		$user = Auth::user();
		$stateID=Auth::user()->state_id;
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
		
		return view('ceo/poll-day', [
            'pollDayDetail' => $pollDayDetail,
            'district' => $district,
            'constituency' => $constituency,
            'encryptDist' => $encryptDist,
            'encryptCons' => $encryptCons,
        ]);
    }


    public function prePollArrangement() {
        $user = Auth::user();
        $state_id = $user->state_id;
        $district=DB::table('districts')
                        ->where('state_id', $state_id)
                        ->get();
        return view('ceo/pre-poll-arrangement', [
            'district' => $district,
        ]);
    }


    public function prePollSub(Request $request) {
        $user = Auth::user();
        $this->validate($request, [
        'dist_code' => 'required',
        'cons_code' => 'required',
        ]);
        
        $encryptDist=$request->dist_code;
        $encryptCons=$request->cons_code;
        $districtCode=eci_decrypt($request->dist_code);
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

        $district=DB::table('districts')
                    ->where('state_id', $state)
                    ->get();

        $constituency=DB::table('constituencies')
                    ->where('dist_code', $districtCode)
                    ->get();

        return view('ceo/pre-poll-arrangement', [
            'prePollSec' => $prePollSec,
            'prePollTans' => $prePollTans,
            'district' => $district,
            'constituency' => $constituency,
            'encryptDist' => $encryptDist,
            'encryptCons' => $encryptCons,
        ]);
    }


    public function suvidha(){
      $user = Auth::user();
      $state_id = $user->state_id;

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
      return view('ceo/suvidha', [
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
      $state_id = $user->state_id;

      $district=DB::table('districts')
               ->where('state_id', $state_id)
               ->get();

      $constituency=DB::table('constituencies')
               ->where('dist_code', $dist_code)
               ->get();

      $getdata = get_suvidha_data($state_id,$dist_code,$cons_code);
      $getparty = get_party_list();

      return view('ceo/suvidha', [
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
      return view('ceo/suvidha-detail', [
         'getdata' => $getdata,
     ]);
  }

    public function pollPercentage() {
        $user = Auth::user();
        $state_id = $user->state_id;

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

        return view('ceo/poll-percentage', [
          'district' => $district,
          'constituency' => $constituency,
          'encryptDist' => $encryptDist,
          'encryptCons' => $encryptCons,
          'pollpercentages' => $pollpercentages,
          'polltiming' => $timeslot,
        ]);
    }

  public function lawOrder()
    {
        $user = Auth::user();
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

        $laworderlist = DB::table('pro_law_order')
                      ->join('poll_booths','poll_booths.bid','pro_law_order.bid')
                        ->join('users','poll_booths.supervisior_uid','users.uid')
                      
                      ->join('users_pollday', 'pro_law_order.uid','users_pollday.uid')
                      ->select('poll_booths.bid','poll_booths.poll_building','poll_booths.poll_building_detail','poll_booths.ps_id','users_pollday.name as pro_name', 'users_pollday.phone as pro_number', 'users.name as sup_name', 'users.phone as sup_num', 'pro_law_order.comment', 'pro_law_order.action_from', 'pro_law_order.action_to')
                      ->where('pro_law_order.dist_code', $firstDist)
                      ->where('pro_law_order.cons_code', $firstCons)
                      ->get();
                      //dd($laworderlist);
        return view('ceo/law-order', [
            'laworderlist' => $laworderlist,
            'district' => $district,
            'constituency' => $constituency,
            'encryptDist' => $encryptDist,
            'encryptCons' => $encryptCons,
        ]);
    }

  public function lawOrderSub(Request $request) {
    $user = Auth::user();
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
    $state=$user->state_id;
    $district=DB::table('districts')
             ->where('state_id', $state)
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

    return view('ceo/law-order', [
      'district' => $district,
      'constituency' => $constituency,
      'encryptDist' => $encryptDist,
      'encryptCons' => $encryptCons,
      'laworderlist' => $laworderlist,
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
        $state_id = $user->state_id;
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

        return view('ceo/poll-percentage', [
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
        $stateID=Auth::user()->state_id;
        $bid=eci_decrypt($bid);
        $pollpercentageDetail = DB::table('poll_booths')
                            ->leftjoin('pro_polling_percentage', 'poll_booths.bid', '=', 'pro_polling_percentage.bid')
                            ->where('poll_booths.state_id', $stateID)
                            ->where('poll_booths.bid', $bid)
                            ->first();

        return view('ceo/polling-percentage-detail', [
          'pollpercentageDetail' => $pollpercentageDetail,
        ]);
    }

    public function electionObservers() {
        $user = Auth::user();
        $state_id = $user->state_id;
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

        return view('ceo/election-observers', [
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
        $state_id = $user->state_id;
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

        return view('ceo/election-observers', [
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
        return view('ceo/observer-profile', [
           'observerDetail' => $observerDetail,
        ]);
    }

    public function p1Scrutiny(){
        $user = Auth::user();
        $state_id = $user->state_id;
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

        return view('ceo/p1-scrutiny', [
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
        $state_id = $user->state_id;

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

        return view('ceo/p1-scrutiny', [
          'district' => $district,
          'constituency' => $constituency,
          'encryptDist' => $encryptDist,
          'encryptCons' => $encryptCons,
          'scrutinyReport' => $scrutinyReport,
        ]);
    }

    
    public function p1ConsolidatedReport(){
        $user = Auth::user();
        $state_id = $user->state_id;

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

        return view('ceo/p1-consolidated-report', [
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
        $state_id = $user->state_id;

        $district=DB::table('districts')
                 ->where('state_id', $state_id)
                 ->get();

        $constituency=DB::table('constituencies')
                     ->where('dist_code', $dist_code)
                     ->get();

        $consReport = DB::table('ro_consolidated_report')
                ->where('cons_code', $cons_code)
                ->first();

        return view('ceo/p1-consolidated-report', [
          'district' => $district,
          'constituency' => $constituency,
          'encryptDist' => $encryptDist,
          'encryptCons' => $encryptCons,
          'consReport' => $consReport,
        ]);
    }


    public function evmVvpat(){
        $user = Auth::user();
        $state_id = $user->state_id;
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

            return view('ceo/evm-vvpat', [
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
            
            return view('ceo/evm-vvpat', [
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
        $state_id = $user->state_id;

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

            return view('ceo/evm-vvpat', [
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

            return view('ceo/evm-vvpatII', [
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


    public function pollingStaff() {
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
        $polling_users = DB::table('users_pollday')                            
                       ->join('constituencies', function($join) { 
                            $join->on('constituencies.cons_code', '=', 'users_pollday.cons_code')
                                 ->on('constituencies.state_id', '=', 'users_pollday.state_id')
                                 ->on('constituencies.dist_code', '=', 'users_pollday.dist_code'); 
                            }
                        )
                       ->where('users_pollday.state_id', $state)
                       ->where('users_pollday.dist_code', $distFirst)
                       ->where('users_pollday.cons_code', $consFirst)
                       ->get();

        return view('ceo/polling-staff', [
            'district' => $district,
            'constituency' => $constituency,
            'encryptDist' => $encryptDist,
            'encryptCons' => $encryptCons,
            'polling_users' => $polling_users,
            'pollType' => $pollType,
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
    return view('ceo/complaint', [
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
    
    return view('ceo/complaint-detail', [
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
    return view('ceo/information', [
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
    return view('ceo/suggestion', [
           'complaints' => $coms,
        ]);
  }


    public function pollingStaffSub(Request $request) {
        $user = Auth::user();
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
        $state=$user->state_id;
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
            return view('ceo/polling-staff2', [
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
            return view('ceo/polling-staff3', [
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

            return view('ceo/polling-staff', [
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


  public function dispatchCollectionCenter() {
    $user = Auth::user();
    $state=$user->state_id;

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

    return view('ceo/dispatch-collection-center', [
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
    $state=$user->state_id;
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

    return view('ceo/dispatch-collection-center', [
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
    $state=$user->state_id;

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

    return view('ceo/postal-ballot', [
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

    $state=$user->state_id;

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

    return view('ceo/postal-ballot', [
      'district' => $district,
      'constituency' => $constituency,
      'encryptDist' => $encryptDist,
      'encryptCons' => $encryptCons,
      'postBallot' => $postBallot,
    ]);
  }
  
  public function policeData(){
	$user = Auth::user();
	$state_id = $user->state_id;
	
    $district=DB::table('districts')
                ->where('state_id', $state_id)
                ->get();
	
	$districtFirst=DB::table('districts')
	 ->where('state_id', $state_id)
	 ->first();

    $firstDist=$districtFirst->dist_code;
	$encryptDist=eci_encrypt($firstDist);
	
	$policeData = DB::table('deo_police_data')
                                    ->join('districts', 'deo_police_data.dist_code','districts.dist_code')
                                    ->where('deo_police_data.state_id', $state_id)
                                    ->get();
	
	return view('ceo/police-data', [
      'policeData' => $policeData,
	  'district' => $district,
	  'encryptDist' => $encryptDist,
    ]);
  }
  
  public function voterSlipData(){
        $user = Auth::user();
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
		
		$voterslipData = DB::table('ro_voter_slips_data')
                                    ->join('constituencies', 'ro_voter_slips_data.cons_code','constituencies.cons_code')
									->where('ro_voter_slips_data.state_id', $state_id)
                                    ->get();
									
		return view('ceo/voter-slip-data', [
		  'district' => $district,
		  'constituency' => $constituency,
          'encryptDist' => $encryptDist,
          'encryptCons' => $encryptCons,
		  'voterslipData' => $voterslipData,
		]);
    }

  public function evmMalfunction() {
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

    return view('ceo/evm-malfunction', [
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
    $state=$user->state_id;

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

    return view('ceo/evm-malfunction', [
        'district' => $district,
        'constituency' => $constituency,
        'encryptDist' => $encryptDist,
        'encryptCons' => $encryptCons,
        'mallfunctions' => $mallfunctions,
        'mallfunctions_resolve' => $mallfunctions_resolve,
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

    return view('ceo/pwd-voters', [
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

    return view('ceo/pwd-voters', [
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
		

        $encryptDist=eci_encrypt($firstDist);
        $encryptCons=eci_encrypt($firstCons);
		
		
		
		$voterslipData = DB::table('ro_voter_slips_data')
                                    ->join('constituencies', 'ro_voter_slips_data.cons_code','constituencies.cons_code')
                                    ->where('ro_voter_slips_data.dist_code', $dist_code)
									->where('ro_voter_slips_data.state_id', $state_id)
									->where('ro_voter_slips_data.cons_code', $cons_code)
                                    ->get();

      return view('ceo/voter-slip-data', [
        'district' => $district,
        'constituency' => $constituency,
        'encryptDist' => $encryptDist,
        'encryptCons' => $encryptCons,
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
		
      return view('ceo/police-data', [
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
        return view('ceo/facilities', [
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

      return view('ceo/video-recording', [
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

      return view('ceo/video-recording', [
        'district' => $district,
        'constituency' => $constituency,
        'encryptDist' => $encryptDist,
        'encryptCons' => $encryptCons,
        'proVideo' => $proVideo,
      ]);
    }
}


