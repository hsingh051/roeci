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
use Mail;


class CronjobController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
        //$this->middleware('ro');
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

    public function updatevoterfields(){

        for ($i=53; $i < 400 ; $i++) { 
            $query = "Select IDCARD_NO, ORG_LIST_NO, CNG_LIST_NO, LastNameEn, RLn_L_NmEn, FM_NAME_V1, LASTNAME_V1, RLN_FM_NM_V1, RLN_L_NM_V1, SLNOINPART from AC_057.dbo.AC057PART".str_pad($i, 3, '0', STR_PAD_LEFT);   
            $hash = base64_encode($query);
            $url = "http://104.238.103.23:90/api/ecivoterapi/".$hash;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch); 
            $return = json_decode($result);
            // echo "<pre>";
            // print_r($return);
            // echo "</pre>";
            // die("here");
            foreach ($return as $value1) {
            
                $editvoter = array(
                    'LastNameEn' => $value1->LastNameEn,
                    'RLn_L_NmEn' => $value1->RLn_L_NmEn,
                    'FM_NAME_V1' => $value1->FM_NAME_V1,
                    'LASTNAME_V1' => $value1->LASTNAME_V1,
                    'RLN_FM_NM_V1' => $value1->RLN_FM_NM_V1,
                    'RLN_L_NM_V1' => $value1->RLN_L_NM_V1,
                    'ORG_LIST_NO' => $value1->ORG_LIST_NO,
                    'CNG_LIST_NO' => $value1->CNG_LIST_NO,
                    'slnoinpart' => $value1->SLNOINPART,
                    
                );
                echo $value1->IDCARD_NO;
                //print_r($editvoter);
                $updateDeo = DB::table('voters')
                            ->where('idcardNo', $value1->IDCARD_NO)
                            ->update($editvoter);
            }

            
        }
            die("here");
    }

    public function updatevoterdata($ac_no){
        $state_id = "53";
        
        $distic = DB::table('constituencies')
                   ->where('state_id', $state_id)
                   ->where('cons_code', $ac_no)
                   ->select('dist_code')
                   ->first();
        $dist_code = $distic->dist_code;
        //dd($dist_code);
        
        
            # code...
            
        $db1 = "AC_".str_pad($ac_no, 3, '0', STR_PAD_LEFT);
        for ($i=25; $i <400 ; $i++) { 
            $db2 = "AC".str_pad($ac_no, 3, '0', STR_PAD_LEFT);
            $part = str_pad($i, 3, '0', STR_PAD_LEFT);
            $query = "Select ccode, AC_NO, PART_NO, SLNOINPART, HOUSE_NO, SECTION_NO, FM_NAME_V1, LASTNAME_V1, RLN_TYPE, RLN_FM_NM_V1, RLN_L_NM_V1, IDCARD_NO, STATUSTYPE, SEX, AGE, ORG_LIST_NO, CNG_LIST_NO, Fm_NameEn, Rln_Fm_NmEn, dob, LastNameEn, RLn_L_NmEn, Mobileno from ".$db1.".dbo.".$db2."PART".$part;
            $hash = base64_encode($query);
            //$url = "http://104.238.103.23:90/api/eciapi/".$hash;
            $fields = array(
                'id' => urlencode($hash),
            );
            $fields_string = "";
            foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
            rtrim($fields_string, '&');                    
            $url = "http://104.238.103.23:90/api/ecivoterapi/";                    
            $ch = curl_init();                   
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string); 
            $result = curl_exec($ch); 
            $return = json_decode($result);
            //dd($return);
            //print_r($fields);
            if(@$return){
                foreach ($return as $val) {                    
                    $distRepeat = DB::table('voters')
                                  ->where('idcardNo',$val->IDCARD_NO)
                                  ->first();
                    if(@$distRepeat){
                        echo $val->IDCARD_NO."<br>";
                        // die;
                    }else{
                        //dd($val);
                        $insUserData = array(
                                        'state_id' => $state_id,
                                        'dist_code' => $dist_code,
                                        'cons_code' => $ac_no,
                                        'ccode' => $val->ccode,
                                        'ps_id' => $val->PART_NO,
                                        'slnoinpart' =>$val->SLNOINPART,
                                        'house_no' => $val->HOUSE_NO,
                                        'section_no' => $val->SECTION_NO,
                                        'idcardNo' => $val->IDCARD_NO,
                                        'ORG_LIST_NO' => $val->ORG_LIST_NO,
                                        'sex' => $val->SEX,
                                        'CNG_LIST_NO' => $val->CNG_LIST_NO,
                                        'dob' => date("Y-m-d",strtotime($val->dob)),
                                        'LastNameEn' => $val->LastNameEn,
                                        'age' => $val->AGE,
                                        'RLn_L_NmEn' => $val->RLn_L_NmEn,
                                        'fm_nameEn' => $val->Fm_NameEn,
                                        'FM_NAME_V1' => $val->FM_NAME_V1,
                                        'rlnType' => $val->RLN_TYPE,
                                        'LASTNAME_V1' => $val->LASTNAME_V1,
                                        'rln_Fm_NmEn' => $val->Rln_Fm_NmEn,
                                        'RLN_FM_NM_V1' => $val->RLN_FM_NM_V1,
                                        'mobileno' => $val->Mobileno,
                                        'RLN_L_NM_V1' => $val->RLN_L_NM_V1,
                                        //'status_type' => $val->STATUSTYPE
                                    ); 
                        //dd($insUserData);
                        $insertUser = DB::table('voters')->insert($insUserData);
                        //die;
                    }
                }
            }
        }
            // echo "<pre>";
            // print_r($return);
            // echo "</pre>";
            // die("yes");
                
            
        

        die("yes");

    }
    
    public function updatepwd(){
        $query = "Select * from consolidated_data.dbo.pwdvoters";
        $hash = base64_encode($query);
        $url = "http://104.238.103.23:90/api/ecivoterapi/".$hash;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch); 
        $return = json_decode($result);

        echo "<pre>";
        print_r($return);
        echo "</pre>";
        die;

    }

    function get_pwd_data($epic_no){
        
        $query = "Select * from consolidated_data.dbo.pwdvoters where IDCARD_NO = '".$epic_no."'";
        //die;
        $hash = base64_encode($query);
        $url = "http://104.238.103.23:90/api/ecivoterapi/".$hash;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch); 
        @$return  = json_decode($result);
        if(@$return){
            return json_encode($return[0]);
        }else{
           $return = array();
           return $return; 
        }
        
    }

    public function newpartlist(){
        //$query = "Select * from ecicontroltable.dbo.newpartlist where AC_No between 57 and 70 order by AC_No asc, Part_No asc";
        $query = "Select poll_booth.ST_CODE, poll_booth.AC_No, poll_booth.Part_No, poll_booth.Part_Name_V1, poll_booth.Part_Name_EN, poll_booth.FVT_Type, poll_booth.PSbuilding_detail, building.PSBuilding_Name_v1, building.PSBuilding_Name_En, building.LONGITUDE, building.LATITUDE, building.contact, building.internet, poll_booth.ccode,maps.SDFE, maps.ODFE, maps.RF, maps.DWF, maps.EF, maps.FF, maps.TF, maps.WRA  from ecicontroltable.dbo.newpartlist as poll_booth inner join ecicontroltable.dbo.psbuildings as building on poll_booth.PSBuildings_ID=building.PSBuildings_ID left join DEDUPLICATION.dbo.Nazari_Maps as maps on poll_booth.AC_No = maps.AC_No and poll_booth.Part_No = maps.PART_NO where poll_booth.AC_No=35 order by poll_booth.Part_No";
        $hash = base64_encode($query);

        $fields = array(
                'id' => urlencode($hash),
            );
        $fields_string = "";
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');                    
        $url = "http://104.238.103.23:90/api/ecivoterapi/";                    
        $ch = curl_init();                   
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string); 
        $result = curl_exec($ch); 
        $return = json_decode($result);
        // echo "<pre>";
        // print_r($return);
        // die;
        if(@$return){
            foreach ($return as $pollval) {
                
                $state_id = "";
                $ac_no = $pollval->AC_No; 

                if($pollval->ST_CODE == "S19"){
                    $state_id = "53";
                }
                $distic = DB::table('constituencies')
                   ->where('state_id', $state_id)
                   ->where('cons_code', $ac_no)
                   ->select('dist_code')
                   ->first();
                $dist_code = $distic->dist_code;

                $svDistCode = trim($dist_code);
                $svConsCode = str_pad($ac_no, 3, '0', STR_PAD_LEFT);
                $boothNum = str_pad($pollval->Part_No, 3, '0', STR_PAD_LEFT);

                $bid = $svDistCode.$svConsCode.$boothNum;

                // if($pollval->FVT_Type == "V"){
                //     $poll_type = "Vulnerable";
                // }elseif($pollval->FVT_Type == "N"){
                //     $poll_type = "Notified";
                // }elseif($pollval->FVT_Type == "A"){
                //     $poll_type = "Auxiliary";
                // }elseif($pollval->FVT_Type == "C"){
                //     $poll_type = "Critical";
                // }elseif($pollval->FVT_Type == "M"){
                //     $poll_type = "Model";
                // }else{
                    $poll_type = "Notified";
                //}

                $polldata = array(
                                    'state_id' => $state_id,
                                    'dist_code' => $dist_code,
                                    'cons_code' => $ac_no,
                                    'booth_no' => $pollval->Part_No,
                                    'ps_id' => $pollval->Part_No,
                                    'locality' => $pollval->Part_Name_EN,
                                    'poll_building' => $pollval->PSBuilding_Name_En,
                                    'poll_building_detail' => $pollval->PSbuilding_detail,
                                    'part_name_v1' => $pollval->Part_Name_V1,
                                    'psbuilding_name_v1' => $pollval->PSbuilding_detail,
                                    'latitude' => $pollval->LATITUDE,
                                    'longitude' => $pollval->LONGITUDE,
                                    'fvt_type' => $pollval->FVT_Type,
                                    'poll_type' => $poll_type,
                                    'ccode' => $pollval->ccode,
                                    'internet' => $pollval->internet,
                                    'sdfe' => $pollval->SDFE,
                                    'odfe' => $pollval->ODFE,
                                    'rf' => $pollval->RF,
                                    'dwf' => $pollval->DWF,
                                    'ef' => $pollval->EF,
                                    'ff' => $pollval->FF,
                                    'tf' => $pollval->TF,
                                    'wra' => $pollval->WRA,
                                    'contact' => $pollval->contact,
                                    //'area' => $pollStationArea,
                                    //'separate_entrance' => $sepEnterExit,
                                    //'poll_areas' => $pollAreas,
                                    //'voters_type' => $voterType,
                                    //'total_voters' => $totalVoter,
                                    //'max_distance' => $maxDistance,
                                    //'supervisior_uid' => $uidSupervisor,
                                    'status' => 1,
                                    //'remarks' => $remarks,
                                );
                $distRepeat = DB::table('poll_booths')
                                  ->where('state_id',$state_id)
                                  ->where('bid',$bid)
                                  ->first();

                if(@$distRepeat){
                   $addPollStation = DB::table('poll_booths')->where('state_id',"=",$state_id)->where('bid',"=",$bid)->update($polldata); 
                   echo "update </br>";
                   //dd($polldata);
                }else{
                   $polldata['bid'] =  $bid;
                   $addPollStation = DB::table('poll_booths')->insert($polldata); 
                   echo "Add </br>";
                   //dd($polldata);
                }

                    
                }

                
            //$addPollStation = DB::table('poll_booths')->insert($polldata); 
            }
        

        // echo "<pre>";
        // print_r($return);
        // echo "</pre>";
        die("Done");

    }
    public function psbuildings(){
        $query = "Select * from ecicontroltable.dbo.psbuildings where AC_No between 57 and 70 order by AC_No asc, PSBuildings_No asc";
        $hash = base64_encode($query);
        $url = "http://104.238.103.23:90/api/eciapi/".$hash;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch); 
        $return = json_decode($result);

        echo "<pre>";
        print_r($return);
        echo "</pre>";
        die;

    }

    public function Nazari_Maps(){
        $query = "Select top 5 * from DEDUPLICATION.dbo.Nazari_Maps where AC_No=57 order by AC_No asc, PART_NO asc ";
        $hash = base64_encode($query);
        $url = "http://104.238.103.23:90/api/ecivoterapi/".$hash;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch); 
        $return = json_decode($result);

        echo "<pre>";
        print_r($return);
        echo "</pre>";
        die;

    }
    public function evmdatafirst($state_id,$ac_no){
        // $st_code = "S19";
        // $ac_no   = "1";
        // $authCode = "";
        $state = DB::table('states')
                   ->where('StateID', $state_id)
                   ->select('st_code')
                   ->first();
        $distic = DB::table('constituencies')
                   ->where('state_id', $state_id)
                   ->where('cons_code', $ac_no)
                   ->select('dist_code')
                   ->first();

        //dd($distic);
        if(@$state){
            $st_code =  $state->st_code;
            $dist_code = $distic->dist_code;
            $url = "http://104.238.103.23:90/api/EVMFirstRand/".$st_code."/".$ac_no;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url); 
            //curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
            //curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);    
            $result = curl_exec($ch);
            $return = json_decode(json_decode($result));
            //dd($return);
            if(@$return){
                foreach ($return as $key => $value) {

                    $addFirstRand = array(
                        'state_id' => $state_id,
                        'dist_code' => $dist_code,
                        'cons_code' => $value->ac_no,
                        'unit_type' => strtoupper($value->UnitType),
                        'unit_id' => $value->ID,
                        'manufacturer' => strtoupper($value->manufacturer_Name),
                        'box_no' => $value->Box_No,
                        'role' => $value->Status,
                      ); 
                    
                    $checkRandRepeat = DB::table('randomization_evm_first')
                                       ->where('unit_type', strtoupper($value->UnitType))
                                       ->where('unit_id', $value->ID)
                                       ->first();

                    if(@$checkRandRepeat){
                        dd($checkRandRepeat);
                        $firstRand = DB::table('randomization_evm_first')->where('id','=',$checkRandRepeat->id)->update($addFirstRand); 
                    }else{
                        $firstRand = DB::table('randomization_evm_first')->insert($addFirstRand);      
                    }
                  
                }

            }
            die('Done');
        }
    }
    public function evmdatasecond($state_id,$ac_no){
        // $st_code = "S19";
        // $ac_no   = "1";
        // $authCode = "";
        $state = DB::table('states')
                   ->where('StateID', $state_id)
                   ->select('st_code')
                   ->first();
        $distic = DB::table('constituencies')
                   ->where('state_id', $state_id)
                   ->where('cons_code', $ac_no)
                   ->select('dist_code')
                   ->first();

        //dd($distic);
        if(@$state){
            $st_code =  $state->st_code;
            $dist_code = $distic->dist_code;
            $url = "http://104.238.103.23:90/api/EVMSecondRand/".$st_code."/".$ac_no;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url); 
            //curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
            //curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);    
            $result = curl_exec($ch);
            $return = json_decode(json_decode($result));
            
            if(@$return){
                foreach ($return as $key => $value) {
                    if($value->Status == "In the Polling"){
                        if(@$value->PS_NO){
                            $bid = $dist_code.str_pad($value->ac_no, 3, '0', STR_PAD_LEFT).str_pad($value->PS_NO, 3, '0', STR_PAD_LEFT);
                        }else{
                            $bid = "";
                        }
                        $addFirstRand = array(
                            'state_id' => $state_id,
                            'dist_code' => $dist_code,
                            'cons_code' => $value->ac_no,
                            'ps_id' => $value->PS_NO,
                            'bid' => $bid,
                            'loc_type' => $value->LOCN_TYPE,
                            'manufacturer_Name' => $value->manufacturer_Name,
                            'status' => $value->Status,
                          ); 
                        if($value->UnitType == "Ballot"){
                            $addFirstRand['bu1'] = $value->ID;
                        }else if($value->UnitType == "Control"){
                            $addFirstRand['cu'] = $value->ID;
                        }
                        
                        $checkRandRepeat = DB::table('randomization_evm_second')
                                           ->where('bid', $bid)
                                           ->first();
                        if(@$checkRandRepeat){
                            //dd($checkRandRepeat);
                            $firstRand = DB::table('randomization_evm_second')->where('bid','=',$bid)->update($addFirstRand); 
                        }else{
                            $firstRand = DB::table('randomization_evm_second')->insert($addFirstRand);      
                        }
                    }else{
                        $addFirstRand = array(
                        'state_id' => $state_id,
                        'dist_code' => $dist_code,
                        'cons_code' => $value->ac_no,
                        'unit_type' => strtoupper($value->UnitType),
                        'unit_id' => $value->ID,
                        'manufacturer' => strtoupper($value->manufacturer_Name),
                        'role' => $value->Status,
                      ); 
                    
                    $checkRandRepeat = DB::table('randomization_evm_reserved')
                                       ->where('unit_type', strtoupper($value->UnitType))
                                       ->where('unit_id', $value->ID)
                                       ->first();

                    if(@$checkRandRepeat){
                        //dd($checkRandRepeat);
                        $firstRand = DB::table('randomization_evm_reserved')->where('id','=',$checkRandRepeat->id)->update($addFirstRand); 
                    }else{
                        $firstRand = DB::table('randomization_evm_reserved')->insert($addFirstRand);      
                    }
                    }
                    
                  
                }

            }
            echo "<pre>";
            print_r($return);
            die('Done');
        }
    }



    public static function get_voter_list($state_id,$dist_code,$cons_code,$part_no){
        //die("dfsd");
        // $state_id = "53";
        // $dist_code = "11";
        // $cons_code = "57";
        // $part_no = "1";

        $db1 = "AC_".str_pad($cons_code, 3, '0', STR_PAD_LEFT);
        $db2 = "AC".str_pad($cons_code, 3, '0', STR_PAD_LEFT);
        $part = str_pad($part_no, 3, '0', STR_PAD_LEFT);

        $query = "Select ccode, AC_NO, PART_NO, SLNOINPART, HOUSE_NO, SECTION_NO, FM_NAME_V1, LASTNAME_V1, RLN_TYPE, RLN_FM_NM_V1, RLN_L_NM_V1, IDCARD_NO, STATUSTYPE, SEX, AGE, ORG_LIST_NO, CNG_LIST_NO, Fm_NameEn, Rln_Fm_NmEn, dob, LastNameEn, RLn_L_NmEn, Mobileno from ".$db1.".dbo.".$db2."PART".$part." order by SLNOINPART asc";
        
        $hash = base64_encode($query);
        //$url = "http://104.238.103.23:90/api/eciapi/".$hash;
        $fields = array(
            'id' => urlencode($hash),
        );
        $fields_string = "";
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');                    
        $url = "http://104.238.103.23:90/api/ecivoterapi/";                    
        $ch = curl_init();                   
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string); 
        $result = curl_exec($ch); 

        $return = json_decode($result);

        if(@$return){
            return $result;
        }else{
            $result = array();
            return $result;
        }

        // echo "<pre>";
        // print_r($return);
        // echo "</pre>";
        // die;

    }


    function voterDetail($epic_no){

            $epic_no = eci_decrypt($epic_no);

            $query = "Select * from Search_db.dbo.".substr($epic_no, 0, 2)." where IDCARD_NO='".$epic_no."'";
            $hash = base64_encode($query);
            //$url = "http://104.238.103.23:90/api/eciapi/".$hash;
            $fields = array(
                'id' => urlencode($hash),
            );
            $fields_string = "";
            foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
            rtrim($fields_string, '&');                    
            $url = "http://104.238.103.23:90/api/ecivoterapi/";                    
            $ch = curl_init();                   
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string); 
            $result = curl_exec($ch);
            $return = json_decode($result);
            if(@$return){
                //dd(json_encode($return[0]));
                return json_encode($return[0]);
            }else{
                $return = array();
                return json_encode($return);
            }
            // echo "<pre>";
            // print_r($return);
            // echo "</pre>";
            // die;
    }


    function get_nominations(){
		
        $query = "Select  * from ceopunjab.dbo.Nominations";
            $hash = base64_encode($query);
			
            //$url = "http://104.238.103.23:90/api/eciapi/".$hash;
            $fields = array(
                'id' => urlencode($hash),
            );
            $fields_string = "";
            foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
            rtrim($fields_string, '&');                    
            $url = "http://104.238.103.23:90/api/ecivoterapi/";                    
            $ch = curl_init();                   
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string); 
            $result = curl_exec($ch);
            $return = json_decode($result);
            //dd($return);
			// echo "<pre>";
			// print_r($return);
			// echo "</pre>";
			// die();
			foreach($return as $return1)
			{
				$RowID = $return1->RowID;
				$Nomination_SRNO = $return1->Nomination_SRNO;
				$CAND_MobileNo = $return1->CAND_MobileNo;
				$Cand_name = $return1->Cand_name;
				$Cand_fName = $return1->Cand_fName;
				$cand_party = $return1->cand_party;
				$cand_Age = $return1->cand_Age;
				$cand_Sex = $return1->cand_Sex;
				$CAND_EPICNO = $return1->CAND_EPICNO;
				$CAND_ADDRESS = $return1->CAND_ADDRESS;
				$cand_photo = $return1->cand_photo;
				$FORM2B_ID = $return1->FORM2B_ID;
				$Form26_ID = $return1->Form26_ID;
				$Nomination_DATE = $return1->Nomination_DATE;
                if(@$return1->Withdrawal_DATE){
                    $Withdrawal_DATE = date("Y-m-d H:i:s",strtotime($return1->Withdrawal_DATE));
                }else{
                    $Withdrawal_DATE = "";    
                }
                if(@$return1->Rejection_DATE){
                    $Rejection_DATE = date("Y-m-d H:i:s",strtotime($return1->Rejection_DATE));
                }else{
                    $Rejection_DATE = "";    
                }
				
				$CAND_AC = $return1->CAND_AC;
				$Cand_SRNO = $return1->Cand_SRNO;
				$CAND_STATUS = $return1->CAND_STATUS;
				$CAND_SYMBOL = $return1->CAND_SYMBOL;
				$SYMBOL_IMAGE = $return1->SYMBOL_IMAGE;
				$REJECTION_REASON = $return1->REJECTION_REASON;
				$Withdraw_RefNo = $return1->Withdraw_RefNo;
				$Recognisedbyparty = $return1->Recognisedbyparty;
                $CAND_SYMBOL_NAME = $return1->CAND_SYMBOL_NAME;
				$CAND_SYMBOL_NAME_PB = $return1->CAND_SYMBOL_NAME_PB;
				$Cand_name_Pb = $return1->Cand_name_Pb;
				$Cand_fName_Pb = $return1->Cand_fName_Pb;
				$cand_party_Pb = $return1->cand_party_Pb;
				$CAND_ADDRESS_Pb = $return1->CAND_ADDRESS_Pb;
				$Symbol1 = $return1->Symbol1;
				$Symbol2 = $return1->Symbol2;
				$Symbol3 = $return1->Symbol3;
                $CAND_MAIN_SUB = $return1->CAND_MAIN_SUB;
				$FROMAB_RECV = $return1->FROMAB_RECV;
				//die();
				$uidNomination = "CND".$CAND_MobileNo;
				$state_id = "53";
				$role = "15";
				$distic = DB::table('constituencies')->where('state_id', $state_id)->where('cons_code', $CAND_AC)->select('dist_code')
							->first();
				$dist_code = $distic->dist_code;
				$password = "123456";
				//die();
                if(($CAND_MobileNo != '9815077782') && ($CAND_MobileNo != '9814643678')){
    				if(@$return){
                        
    					$timestamp = date("Y-m-d");
    					$get_nominations = DB::table('users')->where('role', '15')->where('phone', $CAND_MobileNo)->get();
    					$get_nominations_count = count($get_nominations);
    					if($get_nominations_count==0){
    						$addNom = array(
    							'uid' => $uidNomination,
    							'role' => $role,
    							'state_id' => $state_id,
    							'dist_code' => $dist_code,
    							'cons_code' => $CAND_AC,
    							'name' => $Cand_name,
    							'phone' => $CAND_MobileNo,
    							'password' => Hash::make($password),
    							'address' => $CAND_ADDRESS,
    							'updated_at' => $timestamp,
    						);  
    						$nomination = DB::table('users')->insert($addNom);
    						echo "Add <br>";
    					}
    					else{
    						$addNom = array(
    							'uid' => $uidNomination,
    							'role' => $role,
    							'state_id' => $state_id,
    							'dist_code' => $dist_code,
    							'cons_code' => $CAND_AC,
    							'name' => $Cand_name,
    							'phone' => $CAND_MobileNo,
    							'address' => $CAND_ADDRESS,
    							'updated_at' => $timestamp,
    						);  
    						$nomination = DB::table('users')->where('phone', $CAND_MobileNo)->update($addNom);
    						echo "Update <br>";
    					}
    					
    					$nomdata = array(
    							'uid' => $uidNomination,
                                //'cand_fname' => $Cand_fName,
                                'cand_age' => $cand_Age,
                                'cand_sex' => $cand_Sex,
                                'cand_epicno' => $CAND_EPICNO,
                                'profile_pic'=> "",
    							'cand_symbol' => $CAND_SYMBOL,
    							'cand_party' => $cand_party,
                                'guardian_name' => $Cand_fName,
                                'Recognisedbyparty' => $Recognisedbyparty,
                                'cand_symbol_name' => $CAND_SYMBOL_NAME,
                                'cand_symbol_name_pb' => $CAND_SYMBOL_NAME_PB,
                                'cand_name_pb' => $Cand_name_Pb,
                                'cand_fname_pb' => $Cand_fName_Pb,
                                'cand_party_pb' => $cand_party_Pb,
                                'cand_address_pb' => $CAND_ADDRESS_Pb,
                                'cons_code' => $CAND_AC,
                                'symbol1' => $Symbol1,
                                'symbol2' => $Symbol2,
                                'symbol3' => $Symbol3,
                                'cand_main_sub' => $CAND_MAIN_SUB,
                                'fromab_recv' => $FROMAB_RECV,
                                'serial_number' => $Nomination_SRNO,
                                'form2b_id' => $FORM2B_ID,
                                'form26_id' => $Form26_ID,
    							'nominationDate'=> date("Y-m-d H:i:s",strtotime($Nomination_DATE)),
                                'nominationStatus' => $CAND_STATUS,
                                'withdrawal_date' =>$Withdrawal_DATE,
                                'rejection_date' => $Rejection_DATE,
                                'rejectionReason' => $REJECTION_REASON,
                                'withdraw_refno' => $Withdraw_RefNo,
                                
    					);
                        //dd($nomdata);
    					if(@$cand_photo){
    						$output_file = 'images/candidate/profilePicture/'.$uidNomination.".jpg";
    						$cimage = base64_to_image($cand_photo, $output_file);
    						if(@$cimage){
    							$i = explode("/profilePicture/", $cimage);
    							$nomdata['profile_pic'] = $i[1];
    						}
    					}
    					$get_nominations_data = DB::table('users_candidate_data')->where('uid', $uidNomination)->get();
    					$get_nominations_data_count = count($get_nominations_data);
    					if($get_nominations_data_count==0){
    						$nominationdata = DB::table('users_candidate_data')->insert($nomdata);
    						echo "Add <br><br><br>";
    					}else{
    						$nominationdata = DB::table('users_candidate_data')->where('uid', $uidNomination)->update($nomdata);
    						echo "Update <br><br><br>";
    					}
    					
    				}
                }
				echo "<pre>";
				print_r($return1);
				echo "</pre>";
//				die();
			}
			
            /*&if(@$return){
                // dd(json_encode($return[0]));
                // return json_encode($return[0]);
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
                dd($addNom);
                $nomination = DB::table('users')->insert($addNom);

            }else{
                $return = array();
                return json_encode($return);
            } *///)
    }
	
	function get_cand_symbols(){
		
        $query = "Select * from ceopunjab.dbo.candsymbols";
        $hash = base64_encode($query);
		$fields = array(
                'id' => urlencode($hash),
            );
		$fields_string = "";
		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string, '&');                    
		$url = "http://104.238.103.23:90/api/ecivoterapi/";                    
		$ch = curl_init();                   
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		//curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string); 
		$result = curl_exec($ch);
		$return = json_decode($result);
		
		foreach($return as $val){
			
			
			
			
			$symboldata = array(
                        'symbol_no' => $val->SYMBOL_NO,
                        'symbol_id' => $val->ID,
                        'symbol_des' => $val->SYMBOL_DES,
                        'symbol_hdes' => $val->SYMBOL_HDES,
                        'symbol_hfocdes' => $val->SYMBOL_HFOCDES,
                        'free_reserve' => $val->FREE_RESERVE,
                        'symbol_pic' =>''
                      ); 
					  
			if(@$val->SYMBOL_PIC){
				$output_file = 'images/symbols/'.$val->SYMBOL_DES.".jpg";
				$symboldata['symbol_pic'] = base64_to_image($val->SYMBOL_PIC, $output_file);
			}
                    
			$checksymbol = DB::table('symbols')
							   ->where('symbol_id', $val->ID)
							   ->first();
			if(@$checksymbol){
				$firstRand = DB::table('symbols')->where('symbol_id','=',$val->ID)->update($symboldata); 
			}else{
				$firstRand = DB::table('symbols')->insert($symboldata);      
			}
			
			
			
		}
		die("Done");
	}

    function politicalparties(){

           

            $query = "Select * from ceopunjab.dbo.politicalparties";
            $hash = base64_encode($query);
            //$url = "http://104.238.103.23:90/api/eciapi/".$hash;
            $fields = array(
                'id' => urlencode($hash),
            );
            $fields_string = "";
            foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
            rtrim($fields_string, '&');                    
            $url = "http://104.238.103.23:90/api/ecivoterapi/";                    
            $ch = curl_init();                   
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string); 
            $result = curl_exec($ch);
            $return = json_decode($result);
            // if(@$return){
            //     //dd(json_encode($return[0]));
            //     return json_encode($return[0]);
            // }else{
            //     $return = array();
            //     return json_encode($return);
            // }
            echo "<pre>";
            print_r($return);
            echo "</pre>";
            die;
    }


    public static function get_complaints($state_id,$dist_code,$cons_code){
        if(@$cons_code){
            $url = "http://164.100.129.187/APP_suiT/getsamadhan_punjabac/ac/".$cons_code;
        }elseif (@$dist_code) {
            $url = "http://164.100.129.187/APP_suiT/getsamadhan_punjabdist/dist/".$dist_code;
        }else{
            $url = "http://164.100.129.187/APP_suiT/getsamadhan_punjab";
        }
        $ch = curl_init();                   
        curl_setopt($ch, CURLOPT_URL, $url);
        //curl_setopt($ch, CURLOPT_POST, true);
        //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string); 
        $result = curl_exec($ch);
        $return = json_decode($result);
        return $return; 

    }

    public static function get_complaint_detail($id){
        $url = "http://164.100.129.187/APP_suiT/getsamadhan_punjabComplain/ComplainNo/".$id;
        $ch = curl_init();                   
        curl_setopt($ch, CURLOPT_URL, $url);
        //curl_setopt($ch, CURLOPT_POST, true);
        //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string); 
        $result = curl_exec($ch);
        $return = json_decode($result);
        return $return; 

    }


    public static function get_suvidha_data($state_id,$dist_code,$cons_code){
        if(@$cons_code){
            $url = "http://164.100.129.187/APP_suiT/getSuv_punjabac/ac_no/".$cons_code;
        }elseif (@$dist_code) {
            $url = "http://164.100.129.187/APP_suiT/getSuv_punjabdist/dist/".$dist_code;
        }else{
            $url = "http://164.100.129.187/APP_suiT/getSuv_punjab";
        }

        $ch = curl_init();                   
        curl_setopt($ch, CURLOPT_URL, $url);
        //curl_setopt($ch, CURLOPT_POST, true);
        //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string); 
        $result = curl_exec($ch);
        $return = json_decode($result);
        // echo "<pre>";
        // print_r($return);
        // die;
        return $return;
    }

    public static function get_suvidha_detail($id){
        $url = "http://164.100.129.187/APP_suiT/getSuv_punjabApplicationID/ApplicationID/".$id;

        $ch = curl_init();                   
        curl_setopt($ch, CURLOPT_URL, $url);
        //curl_setopt($ch, CURLOPT_POST, true);
        //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string); 
        $result = curl_exec($ch);
        $return = json_decode($result);
        // echo "<pre>";
        // print_r($return);
        // die;
        return $return;
    }


    public static function getcommdetails($id,$no=NULL){
        if(@$no){
            $url = "http://104.238.103.23:90/api/contactAPI/".$id."/".$no;
        }else{
            $url = "http://104.238.103.23:90/api/contactAPI/".$id.'/1';
        }
                       
        $ch = curl_init();                   
        curl_setopt($ch, CURLOPT_URL, $url);
        //curl_setopt($ch, CURLOPT_POST, true);
        //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string); 
        $result = curl_exec($ch);
        $return = json_decode($result);
        
        return $return;
    }


     /* send welcome sms to Ro*/

    public static function send_welcome_msg(Request $request)
    {
        $dist_code = "11";
        $role = "4";
        $users = DB::table('users')->where('dist_code', $dist_code)->where('role', $role)->get();
        dd($users);
        //foreach($users as $users1)
       // {
          //  $name = $users1->name;
            //$phone = $users1->phone;
            //echo "<br>";
            
            /*$user = "01synergy";
            $password = "01@Synergy";
            $msisdn = "9464529625";
            $sid = "SMSHUB";
            $name = "Anurag Sharrma";
            $OTP = "6765R";
            $msg = "Welcome to RONET! It has been launched by Honourable Election Commission of India. Login to proceed further!";
            $msg = urlencode($msg);
            $fl = "0";
            $gwid = "2";
            $type = "txt";
            $ch =
            curl_init("http://cloud.smsindiahub.in/vendorsms/pushsms.aspx?user=".$user."&password=".$password."&ms
            isdn=".$msisdn."&sid=".$sid."&msg=".$msg."&fl=".$fl."&gwid=".$gwid."");
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            curl_close($ch);
            // Display MSGID of the successful sms push
            echo $output; */
       // }

            $user = "01synergy";
            $password = "01@Synergy";
            $msisdn = "919464529625";
            $sid = "ERONET";
            $msg = "Welcome to RONET! It has been launched by Honourable Election Commission of India. Login to proceed further!";
            $msg = urlencode($msg);
            $fl = "0";
            $gwid = "2";
            $ch =
            curl_init("http://cloud.smsindiahub.in/vendorsms/pushsms.aspx?user=".$user."&password=".$password."&ms
            isdn=".$msisdn."&sid=".$sid."&msg=".$msg."&fl=".$fl."&gwid=".$gwid."");
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            curl_close($ch);
            // Display MSGID of the successful sms push
            echo $output; 
        die();


      //  $msg = 'http://cloud.smsindiahub.in/vendorsms/pushsms.aspx?user=01synergy&password=01@Synergy&msisdn=919872922147&sid=SMSHUB&msg=test%20message&fl=1';
    }


    public static function get_poll_images($state_id,$dist_code,$cons_code,$part_no){
        //die("dfsd");
        // $state_id = "53";
        // $dist_code = "11";
        // $cons_code = "57";
        // $part_no = "1";

        $db1 = "AC_".str_pad($cons_code, 3, '0', STR_PAD_LEFT);
        $db2 = "AC".str_pad($cons_code, 3, '0', STR_PAD_LEFT);
        $part = str_pad($part_no, 3, '0', STR_PAD_LEFT);

        //$query = "Select top 5 * from DEDUPLICATION.dbo.Nazari_Maps";
        $query = "Select Image1,Image2,Image3,Image4,Image5,Image6,Image7 from DEDUPLICATION.dbo.Nazari_Maps where AC_NO='$cons_code' and PART_NO='$part_no'";
        
        $hash = base64_encode($query);
        //$url = "http://104.238.103.23:90/api/eciapi/".$hash;
        $fields = array(
            'id' => urlencode($hash),
        );
        $fields_string = "";
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        rtrim($fields_string, '&');                    
        $url = "http://104.238.103.23:90/api/ecivoterapi/";                    
        $ch = curl_init();                   
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string); 
        $result = curl_exec($ch); 

        $return = json_decode($result);
        
        if(@$return){
            return $result;
        }else{
            $result = array();
            return $result;
        }

        // echo "<pre>";
        // print_r($return);
        // echo "</pre>";
        // die;

    }


    function get_pun_voters(){

        $poll_station = DB::table('poll_booths')
                //->where('dist_code', $distRo)
                //->where('cons_code', $consRo)
                //->where('state_id', $stateRo)
                ->select('state_id','dist_code','cons_code','ps_id') 
                ->orderby('dist_code','cons_code'.'ps_id')
                ->get();
        foreach ($poll_station as $value) {



            $db1 = "AC_".str_pad($value->cons_code, 3, '0', STR_PAD_LEFT);
            $db2 = "AC".str_pad($value->cons_code, 3, '0', STR_PAD_LEFT);
            $part = str_pad($value->ps_id, 3, '0', STR_PAD_LEFT);
            $query = "Select AC_NO, PART_NO, SLNOINPART, IDCARD_NO, SEX, AGE, Fm_NameEn,  dob, LastNameEn, Mobileno from ".$db1.".dbo.".$db2."PART".$part." where AGE BETWEEN 18 and 35 order by SLNOINPART asc";
            $hash = base64_encode($query);
            //$url = "http://104.238.103.23:90/api/eciapi/".$hash;
            $fields = array(
                'id' => urlencode($hash),
            );
            $fields_string = "";
            foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
            rtrim($fields_string, '&');                    
            $url = "http://104.238.103.23:90/api/ecivoterapi/";                    
            $ch = curl_init();                   
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string); 
            $result = curl_exec($ch); 

            $return = json_decode($result);

            if(@$return){
                foreach ($return as $val1) {
                    $agevote = DB::table('age_voters')
                                    ->where('idcard_no',$val1->IDCARD_NO)
                                    ->get();
                    if($agevote->count()==0){
                        $vdata = array(
                            'ac_no' => $val1->AC_NO,
                            'part_no' => $val1->PART_NO,
                            'slnoinpart' => $val1->SLNOINPART,
                            'idcard_no' => $val1->IDCARD_NO,
                            'sex' => $val1->SEX,
                            'age' => $val1->AGE,
                            'fm_nameen' =>$val1->Fm_NameEn,
                            'dob' =>$val1->dob,
                            'lastnameen' =>$val1->LastNameEn,
                            'mobileno' =>$val1->Mobileno
                          ); 
                        //dd($vdata); 
                        $firstRand = DB::table('age_voters')->insert($vdata); 
                    }
                        
                }
            }

            

        }
        echo "<pre>";
        print_r($poll_station);
        die;
        // $db1 = "AC_".str_pad($cons_code, 3, '0', STR_PAD_LEFT);
        // $db2 = "AC".str_pad($cons_code, 3, '0', STR_PAD_LEFT);
        // $part = str_pad($part_no, 3, '0', STR_PAD_LEFT);

        // $query = "Select ccode, AC_NO, PART_NO, SLNOINPART, HOUSE_NO, SECTION_NO, FM_NAME_V1, LASTNAME_V1, RLN_TYPE, RLN_FM_NM_V1, RLN_L_NM_V1, IDCARD_NO, STATUSTYPE, SEX, AGE, ORG_LIST_NO, CNG_LIST_NO, Fm_NameEn, Rln_Fm_NmEn, dob, LastNameEn, RLn_L_NmEn, Mobileno from ".$db1.".dbo.".$db2."PART".$part." order by SLNOINPART asc";
        
        // $hash = base64_encode($query);
        // //$url = "http://104.238.103.23:90/api/eciapi/".$hash;
        // $fields = array(
        //     'id' => urlencode($hash),
        // );
        // $fields_string = "";
        // foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        // rtrim($fields_string, '&');                    
        // $url = "http://104.238.103.23:90/api/ecivoterapi/";                    
        // $ch = curl_init();                   
        // curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_POST, true);
        // //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string); 
        // $result = curl_exec($ch); 

        // $return = json_decode($result);
    }


    function get_blo(){
        $poll_station = DB::table('poll_booths')
                //->where('dist_code', $distRo)
                //->where('cons_code', $consRo)
                ->whereNull('blo_uid')
                ->select('state_id','dist_code','cons_code','ps_id') 
                ->orderby('cons_code')
                ->get();
        //dd($poll_station);
        foreach ($poll_station as $value) {
            // $value->dist_code = 11;
            // $value->cons_code = 63;
            // $value->ps_id = 1;
            echo $url = "http://104.238.103.23:90/api/contactAPI/11/".$value->cons_code.",".$value->ps_id;
            $ch = curl_init();                   
            curl_setopt($ch, CURLOPT_URL, $url);
            //curl_setopt($ch, CURLOPT_POST, true);
            //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
            //curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string); 
            $result = curl_exec($ch); 

            $return = json_decode($result);
            
            if(@$return){
                //dd($return);
                foreach ($return as  $val2) {
                    
                    if($val2->Designation == "BLO"){
                        //dd($val2);
                        $ph = substr($val2->MobileNo, -10);
                        $uidSup = trim("BLO".$ph);
                        $repeatNumber = DB::table('users')
                                        ->where('phone', $ph)
                                        ->where('role', '!=' , '7')
                                        ->get();

                        if($repeatNumber->count()==0){
                            $dt = Carbon::now();
                            $timestamp=$dt->toDateString();
                            $insUserData = array(
                                                'uid' => $uidSup,
                                                'dist_code' => $value->dist_code,
                                                'cons_code' => $value->cons_code,
                                                'name' => $val2->Name,
                                                'phone' => $ph,
                                                'role' => '7',
                                                'state_id' => '53',
                                                'updated_at' => $timestamp,
                                            );

                            $repeatNumber = DB::table('users')
                                            ->where('phone', $ph)
                                            ->where('role', '7')
                                            ->get();

                            if($repeatNumber->count()==0){
                                //die('d');
                                $newpassIns=rand(10,1000).time();
                                $hashPasswordins=Hash::make($newpassIns);                                
                                $insUserData['password'] = $hashPasswordins;
                                $insertUser = DB::table('users')->insert($insUserData);    
                            
                            }else{
                                //die('4');
                                $insertUser = DB::table('users')->where('uid',$uidSup)->update($insUserData); 
                            
                            }
                            $upPollBoothIns = array(
                                        'blo_uid' => $uidSup,
                                    ); 
                            $updatePollBoothIns = DB::table('poll_booths')
                                                    ->where('dist_code', $value->dist_code)
                                                    ->where('state_id', '53')
                                                    ->where('cons_code', $value->cons_code)
                                                    ->where('ps_id', $value->ps_id)
                                                    ->update($upPollBoothIns);
                            

                        }
                        //die;
                    }
                    else if($val2->Designation == "SHO"){
                        $ph = substr($val2->MobileNo, -10);
                        $cons_code  = str_pad($value->cons_code, 3, '0', STR_PAD_LEFT);
                        $part = str_pad($value->ps_id, 3, '0', STR_PAD_LEFT);
                        $bid = $value->dist_code.$cons_code.$part;
                        $booth = DB::table('polic_personnel')
                                ->where('bid',$bid)
                                ->get();
                        if($booth->count()==0){
                            $insUserData = array(
                                            'bid' => $bid,
                                            'dist_code' => $value->dist_code,
                                            'cons_code' => $value->cons_code,
                                            'sho_name' => $val2->Name,
                                            'sho_phone' => $ph,
                                            'state_id' => '53',
                                        );
                            $insertUser = DB::table('polic_personnel')->insert($insUserData); 
                        }
                    }
                }
            }
            // echo "<pre>";
            // print_r($return);
            // die;
        }

        die("Done");
    }

    function get_candidate_list($state_id, $cons_code){
        if($state_id=='53'){
            $state_code = "S19";

            $url = "http://164.100.128.74/WebApi/Service.svc/GetCandidatelistACCurrentElection?st_code=".$state_code."&ac_no=".$cons_code;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch); 
            $result = json_decode($result);
            // echo "<pre>";
            // print_r($result);
            // die;
            if($result[0]->ResponseMessage=="Success") {
                foreach($result as $candidate){
                    $rolist['CandidateName'] = $candidate->CandidateName;
                    $rolist['PartyName'] = $candidate->PartyName;
                    $rolist['cand_sl_no'] = $candidate->cand_sl_no;
                    
                    $return_array[] = $rolist;
                }
                $return = array('result' => $return_array,'status_code'=>200);
                exit(json_encode($return));
                
            }
            else{
                $return = array('result' => 'No relevant data found','status_code'=>406);
                exit(json_encode($return));
            }
        }
    }

    function get_candidate_list_s(){
            
            for($cons_code=1;$cons_code<=117;$cons_code++){
                $d = DB::table('constituencies')
                            ->where('cons_code',$cons_code)
                            ->select('dist_code')
                            ->first();
                $state_code = "S19";    
                $dist_code = $d->dist_code;
                $url = "http://164.100.128.74/WebApi/Service.svc/GetCandidatelistACCurrentElection?st_code=".$state_code."&ac_no=".$cons_code;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url); 
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $result = curl_exec($ch); 
                $result = json_decode($result);
               
                if(@$result[0]->CandidateName) {
                    foreach($result as $candidate){
                        if(@$candidate->CandidateName){     
                        $vdata = array(
                            'dist_code' => $dist_code,
                            'cons_code' => $cons_code,
                            'candidatename' => $candidate->CandidateName,
                            'partyname' => $candidate->PartyName,
                            'responsemessage' => $candidate->ResponseMessage,
                            'cand_sl_no' =>$candidate->cand_sl_no
                          );

                        
                        if(@$candidate->CandidateImage){

                            $candimg = base64_encode($dist_code.$cons_code.$candidate->cand_sl_no);

                            $cand_photo = $candidate->CandidateImage;
                            $output_file = 'images/candidate/profilePicture/'.$candimg.".jpg";
                            $cimage = base64_to_image($cand_photo, $output_file);
                            if(@$cimage){
                                $i = explode("/profilePicture/", $cimage);
                                $vdata['profile_pic'] = $i[1];
                            }
                        }
                        $c = DB::table('new_candidate')
                            ->where('dist_code',$dist_code)
                            ->where('cons_code',$cons_code)
                            ->where('cand_sl_no',$candidate->cand_sl_no)
                            ->get(); 
                        if($c->count()==0){
                            $firstRand = DB::table('new_candidate')->insert($vdata); 
                        }else{
                            $firstRand = DB::table('new_candidate')
                                         ->where('dist_code',$dist_code)
                                         ->where('cons_code',$cons_code)
                                         ->where('cand_sl_no',$candidate->cand_sl_no)
                                         ->update($vdata); 
                        }
                        //dd($vdata); 
                        
                    }
                }
                    
                    
                }
                
            }

            die('Done');
        
    }

    function delete_pollbefore($uid){
        $delRo=DB::table('pro_activity_before')
                    ->where('uid', $uid)
                    ->delete();
        echo "Done";
        die;
    }
    function delete_pollday($uid){
        $delRo=DB::table('pro_activity_pollday')
                    ->where('uid', $uid)
                    ->delete();
        echo "Done";
        die;
    }
    function delete_evm($uid){
        $delRo=DB::table('pro_evm_malfunctioning')
                    ->where('uid', $uid)
                    ->delete();
        echo "Done";
        die;
    }
    function delete_law($uid){
        $delRo=DB::table('pro_law_order')
                    ->where('uid', $uid)
                    ->delete();
        echo "Done";
        die;
    }
    function delete_percentage($uid){
        $delRo=DB::table('pro_polling_percentage')
                    ->where('uid', $uid)
                    ->delete();
        echo "Done";
        die;
    }
    function delete_alldetails($uid){
        $delRo=DB::table('pro_activity_before')
                    ->where('uid', $uid)
                    ->delete();
        $delRo=DB::table('pro_activity_pollday')
                    ->where('uid', $uid)
                    ->delete();
        $delRo=DB::table('pro_evm_malfunctioning')
                    ->where('uid', $uid)
                    ->delete();
        $delRo=DB::table('pro_law_order')
                    ->where('uid', $uid)
                    ->delete();
        $delRo=DB::table('pro_polling_percentage')
                    ->where('uid', $uid)
                    ->delete();
        echo "Done";
        die;
    }


    function addGeneralObserver(){
        $state_id="53";
        $url = "http://164.100.128.74/webapi/Service.svc/GetObserverGeneral/s19";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch); 
        $result = json_decode($result);
        if($result[0]->ResponseMessage=="Success") {
            foreach($result as $genralObs){ 
                $consCodeObs=$genralObs->AC_No;

                $phoneObs=$genralObs->Mobile_ObsDuty;
                $phoneObsPersonel=$genralObs->PersonalMobileNo;

                if(!empty($phoneObs)){
                    $phoneObsTrim = ltrim($phoneObs, '0');
                }else{
                    $phoneObsTrim = ltrim($phoneObsPersonel, '0');
                }

                $uidObs="OBS".$phoneObsTrim;
                $nameObs=$genralObs->OB_NAME;
                $responseObs=$genralObs->ResponseMessage;
                $typeObs="General Observer";
                $distic = DB::table('constituencies')
                            ->where('state_id', $state_id)
                            ->where('cons_code', $consCodeObs)
                            ->select('dist_code')
                            ->first();
                $distCodeObs=$distic->dist_code;
                $dt = Carbon::now();
                $timestamp=$dt->toDateString();
                if($responseObs=="Success"){
                    $generalObserverCheck = DB::table('observer')
                                          ->where('dist_code', $distCodeObs)
                                          ->where('cons_code', $consCodeObs)
                                          ->where('type', $typeObs)
                                          ->first();

                    if(!empty($generalObserverCheck)) {
                        $obsUpdate = array(
                            'uid' => $uidObs,
                            'name' => $nameObs,
                            'phone' => $phoneObsTrim,
                        );
                        $updateObs = DB::table('observer')
                                   ->where('dist_code', $distCodeObs)
                                   ->where('cons_code', $consCodeObs)
                                   ->where('type', $typeObs)
                                   ->update($obsUpdate);
                    }
                    else{
                        $obsAdd = array(
                            'state_id' => $state_id,
                            'dist_code' => $distCodeObs,
                            'cons_code' => $consCodeObs,
                            'email' => "",
                            'address' => "",
                            'profile_image' => "",
                            'type' => "General Observer",
                            'uid' => $uidObs,
                            'name' => $nameObs,
                            'phone' => $phoneObsTrim,
                            'updated_at' => $timestamp,  
                        );  
                        $addObs = DB::table('observer')
                                ->insert($obsAdd);
                    }
                }
            }
            echo "Done"; die;
        }
    }


    function addPoliceObserver(){
        $state_id="53";
        $url = "http://164.100.128.74/webapi/Service.svc/GetObserverPolice/s19";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch); 
        $result = json_decode($result);
        if($result[0]->ResponseMessage=="Success") {
            foreach($result as $policeObs){ 
                $consCodeObs=$policeObs->AC_No;

                $phoneObs=$policeObs->Mobile_ObsDuty;
                $phoneObsPersonel=$policeObs->PersonalMobileNo;

                if(!empty($phoneObs)){
                    $phoneObsTrim = ltrim($phoneObs, '0');
                }else{
                    $phoneObsTrim = ltrim($phoneObsPersonel, '0');
                }

                $uidObs="OBS".$phoneObsTrim;
                $nameObs=$policeObs->OB_NAME;
                $responseObs=$policeObs->ResponseMessage;
                $typeObs="Police Observer";
                $distic = DB::table('constituencies')
                            ->where('state_id', $state_id)
                            ->where('cons_code', $consCodeObs)
                            ->select('dist_code')
                            ->first();
                $distCodeObs=$distic->dist_code;
                $dt = Carbon::now();
                $timestamp=$dt->toDateString();
                if($responseObs=="Success"){
                    $generalObserverCheck = DB::table('observer')
                                          ->where('dist_code', $distCodeObs)
                                          ->where('cons_code', $consCodeObs)
                                          ->where('type', $typeObs)
                                          ->first();

                    if(!empty($generalObserverCheck)) {
                        $obsUpdate = array(
                            'uid' => $uidObs,
                            'name' => $nameObs,
                            'phone' => $phoneObsTrim,
                        );
                        $updateObs = DB::table('observer')
                                   ->where('dist_code', $distCodeObs)
                                   ->where('cons_code', $consCodeObs)
                                   ->where('type', $typeObs)
                                   ->update($obsUpdate);
                    }
                    else{
                        $obsAdd = array(
                            'state_id' => $state_id,
                            'dist_code' => $distCodeObs,
                            'cons_code' => $consCodeObs,
                            'email' => "",
                            'address' => "",
                            'profile_image' => "",
                            'type' => "Police Observer",
                            'uid' => $uidObs,
                            'name' => $nameObs,
                            'phone' => $phoneObsTrim,
                            'updated_at' => $timestamp,  
                        );  
                        $addObs = DB::table('observer')
                                ->insert($obsAdd);
                    }
                }
            }
            echo "Done"; die;
        }
    }



    function addExpenditureObserver(){
        $state_id="53";
        $url = "http://164.100.128.74/webapi/Service.svc/GetObserverExpenditure/s19";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch); 
        $result = json_decode($result);
        if($result[0]->ResponseMessage=="Success") {
            foreach($result as $expandObs){ 
                $consCodeObs=$expandObs->AC_No;

                $phoneObs=$expandObs->Mobile_ObsDuty;
                $phoneObsPersonel=$expandObs->PersonalMobileNo;

                if(!empty($phoneObs)){
                    $phoneObsTrim = ltrim($phoneObs, '0');
                }else{
                    $phoneObsTrim = ltrim($phoneObsPersonel, '0');
                }

                $uidObs="OBS".$phoneObsTrim;
                $nameObs=$expandObs->OB_NAME;
                $responseObs=$expandObs->ResponseMessage;
                $typeObs="Expenditure Observer";
                $distic = DB::table('constituencies')
                            ->where('state_id', $state_id)
                            ->where('cons_code', $consCodeObs)
                            ->select('dist_code')
                            ->first();
                $distCodeObs=$distic->dist_code;
                $dt = Carbon::now();
                $timestamp=$dt->toDateString();
                if($responseObs=="Success"){
                    $generalObserverCheck = DB::table('observer')
                                          ->where('dist_code', $distCodeObs)
                                          ->where('cons_code', $consCodeObs)
                                          ->where('type', $typeObs)
                                          ->first();

                    if(!empty($generalObserverCheck)) {
                        $obsUpdate = array(
                            'uid' => $uidObs,
                            'name' => $nameObs,
                            'phone' => $phoneObsTrim,
                        );
                        $updateObs = DB::table('observer')
                                   ->where('dist_code', $distCodeObs)
                                   ->where('cons_code', $consCodeObs)
                                   ->where('type', $typeObs)
                                   ->update($obsUpdate);
                    }
                    else{
                        $obsAdd = array(
                            'state_id' => $state_id,
                            'dist_code' => $distCodeObs,
                            'cons_code' => $consCodeObs,
                            'email' => "",
                            'address' => "",
                            'profile_image' => "",
                            'type' => "Expenditure Observer",
                            'uid' => $uidObs,
                            'name' => $nameObs,
                            'phone' => $phoneObsTrim,
                            'updated_at' => $timestamp,  
                        );  
                        $addObs = DB::table('observer')
                                ->insert($obsAdd);
                    }
                }
            }
            echo "Done"; die;
        }
    }


    function addAwarnessObserver(){
        $state_id="53";
        $url = "http://164.100.128.74/webapi/Service.svc/GetObserverAwarness/s19";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch); 
        $result = json_decode($result);
        if($result[0]->ResponseMessage=="Success") {
            foreach($result as $awareObs){ 
                $consCodeObs=$awareObs->AC_No;

                $phoneObs=$awareObs->Mobile_ObsDuty;
                $phoneObsPersonel=$awareObs->PersonalMobileNo;

                if(!empty($phoneObs)){
                    $phoneObsTrim = ltrim($phoneObs, '0');
                }else{
                    $phoneObsTrim = ltrim($phoneObsPersonel, '0');
                }

                $uidObs="OBS".$phoneObsTrim;
                $nameObs=$awareObs->OB_NAME;
                $responseObs=$awareObs->ResponseMessage;
                $typeObs="Awareness Observer";
                $distic = DB::table('constituencies')
                            ->where('state_id', $state_id)
                            ->where('cons_code', $consCodeObs)
                            ->select('dist_code')
                            ->first();
                $distCodeObs=$distic->dist_code;
                $dt = Carbon::now();
                $timestamp=$dt->toDateString();
                if($responseObs=="Success"){
                    $generalObserverCheck = DB::table('observer')
                                          ->where('dist_code', $distCodeObs)
                                          ->where('cons_code', $consCodeObs)
                                          ->where('type', $typeObs)
                                          ->first();

                    if(!empty($generalObserverCheck)) {
                        $obsUpdate = array(
                            'uid' => $uidObs,
                            'name' => $nameObs,
                            'phone' => $phoneObsTrim,
                        );
                        $updateObs = DB::table('observer')
                                   ->where('dist_code', $distCodeObs)
                                   ->where('cons_code', $consCodeObs)
                                   ->where('type', $typeObs)
                                   ->update($obsUpdate);
                    }
                    else{
                        $obsAdd = array(
                            'state_id' => $state_id,
                            'dist_code' => $distCodeObs,
                            'cons_code' => $consCodeObs,
                            'email' => "",
                            'address' => "",
                            'profile_image' => "",
                            'type' => "Awareness Observer",
                            'uid' => $uidObs,
                            'name' => $nameObs,
                            'phone' => $phoneObsTrim,
                            'updated_at' => $timestamp,  
                        );  
                        $addObs = DB::table('observer')
                                ->insert($obsAdd);
                    }
                }
            }
            echo "Done"; die;
        }
    }


    public static function getPwdVoter($cons_code){
        $query = "Select * from consolidated_data.dbo.pwdvoters where ac_no=".$cons_code;
        $hash = base64_encode($query);
        $url = "http://104.238.103.23:90/api/ecivoterapi/".$hash;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch); 
        $return  = json_decode($result);
        if(@$return){
            return $result;
        }else{
            $result = array();
            return $result;
        }
    }


    public function updatepro($ac_no,$newparty,$phone){

        $generalObserverCheck = DB::table('users_pollday')
                              ->where('uid', "PRO".$phone)
                              ->where('phone', $phone)
                              ->get();
        if($generalObserverCheck->count()==1){
            $obsAdd = array(
                            'uid' => "PRO".$phone,
                       );  
            $addObs = DB::table('randomization_staff_third')
                      ->where('party_no',$newparty)
                      ->where('uid', 'like', 'PRO%')
                      ->where('cons_code',$ac_no)
                      ->update($obsAdd);  
            die("Yes");  
        }
        
        die("No"); 
    }

}