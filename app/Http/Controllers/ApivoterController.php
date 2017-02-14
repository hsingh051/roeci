<?php

namespace App\Http\Controllers;

use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Image;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class ApivoterController extends Controller 
{
    
    public function voter_detail(Request $request)
    {
        $voter_number = $request->voter_number;
        ////die;
        if(!isset($voter_number) || $voter_number == "")            
        {               
            $return = array('result' => 'Please enter your voter number','status_code'=>204);
            exit(json_encode($return));         
        
        }
        //$getdata = DB::table('voters')->join('states', 'voters.state_id', 'states.StateID')->join('districts', 'voters.dist_code', 'districts.dist_code')->join('constituencies', 'voters.cons_code', 'constituencies.cons_code')->where('idcardNo',$request->voter_number)->first();
        
        // For Ceo Punjab
        //$getdata = json_decode(voter_details($voter_number));
        
        // For ECI
        //voter_data($voter_number);
        $getdata = json_decode(voter_data($voter_number));
        //dd($getdata);
        if(@$getdata){
            
            // die;
            $state_id = "53";

            if((@$getdata->response->numFound) && ($getdata->response->numFound==1)){
                // echo "<pre>";
                // print_r($getdata->response->docs[0]);
                // echo "</pre>";
                // die;   
                $getdata = $getdata->response->docs[0];

                $distic = DB::table('constituencies')
                           ->where('state_id', $state_id)
                           ->where('cons_code', $getdata->ac_no)
                           ->select('dist_code')
                           ->first();

                $getdata->dist_code = $distic->dist_code;
                $getdata->cons_code = $getdata->ac_no;
                $getdata->ps_id = $getdata->part_no;
                //$getstate->StateName = $getdata->state;
                $getstate = DB::table('states')->where('StateID',$state_id)->first();

                $getdata->SLNOINPART = $getdata->slno_inpart;
                // echo "<pre>";
                // print_r($getdata);
                // echo "</pre>";
                // die;
                
            }
            
            if(@$getdata->ST_Code){
                $getstate = DB::table('states')->where('st_code',$getdata->ST_Code)->first();
                //dd($getstate);
                

                //$state_id = "53";
            }

            if(@$getdata->state_id){
                $getstate = DB::table('states')->where('StateID',$getdata->state_id)->first();
                
            }

            $getdistrict = DB::table('districts')->where('state_id',$state_id)->where('dist_code',$getdata->dist_code)->first();
            $getcons = DB::table('constituencies')->where('state_id',$state_id)->where('cons_code',$getdata->cons_code)->first();
            if(@$getdata->PART_NO){
                $getpsdetail = DB::table('poll_booths')->where('state_id',$state_id)->where('cons_code',$getdata->cons_code)->where('ps_id',$getdata->PART_NO)->first();
            }

            if(@$getdata->ps_id){
                $getpsdetail = DB::table('poll_booths')->where('state_id',$state_id)->where('cons_code',$getdata->cons_code)->where('ps_id',$getdata->ps_id)->first();
            }

            if(@$getdata->IDCARD_NO){
                $return_array['idcardNo'] = $getdata->IDCARD_NO;
            }

            if(@$getdata->idcardNo){
                $return_array['idcardNo'] = $getdata->idcardNo;
            }

            if(@$getdata->epic_no){
                $return_array['idcardNo'] = $getdata->epic_no;
            }

            

            if(@$getdata->Fm_NameEn){
                $return_array['name'] = $getdata->Fm_NameEn.' '.$getdata->LastNameEn;
            }

            if(@$getdata->fm_nameEn){
                $return_array['name'] = $getdata->fm_nameEn.' '.$getdata->LastNameEn;
            }

            if(@$getdata->name){
                $return_array['name'] = $getdata->name;
            }
            
            if(@$getdata->SEX){
                if($getdata->SEX == 'M'){
                    $gender = "Male";
                }
                else{
                    $gender = "Female";
                }
            }
            if(@$getdata->sex){
                if($getdata->sex == 'M'){
                    $gender = "Male";
                }
                else{
                    $gender = "Female";
                }
            }


            if(@$getdata->GENDER){
                if($getdata->GENDER == 'M'){
                    $gender = "Male";
                }
                else{
                    $gender = "Female";
                }
            }
            if(@$getdata->gender){
                if($getdata->gender == 'M'){
                    $gender = "Male";
                }
                else{
                    $gender = "Female";
                }
            }



            $return_array['gender'] = $gender;
            if(@$getdata->RLN_TYPE){
                if($getdata->RLN_TYPE == 'H'){
                    $relation_type = "Husband";
                }
                else{
                    $relation_type = "Father";   
                }
            }
            if(@$getdata->rlnType){
                if($getdata->rlnType == 'H'){
                    $relation_type = "Husband";
                }
                else{
                    $relation_type = "Father";   
                }
            }
            if(@$getdata->rln_type){
                if($getdata->rln_type == 'H'){
                    $relation_type = "Husband";
                }
                else{
                    $relation_type = "Father";   
                }
            }



            $return_array['relation_type'] =  $relation_type;
            if(@$getdata->Rln_Fm_NmEn){
                $return_array['relation_name'] = $getdata->Rln_Fm_NmEn.' '.$getdata->Rln_L_NmEn;
            }
            if(@$getdata->rln_Fm_NmEn){
                $return_array['relation_name'] = $getdata->rln_Fm_NmEn.' '.$getdata->rln_L_NmEn;
            }


            if(@$getdata->rln_name){
                $return_array['relation_name'] = $getdata->rln_name;
            }

            if(@$getdata->DOB){
            	
            	$month = date("m",strtotime($getdata->DOB));
            	$day = date("d",strtotime($getdata->DOB));
            	if($month == "12" && $day=="31"){
            		$return_array['dob'] = date('Y-m-d', strtotime($getdata->DOB));
            	}else{
            		$d = date("Y-m-d",strtotime($getdata->DOB));
                	$return_array['dob'] = date('Y-m-d', strtotime($d . ' +1 day'));
					$return_array['dob'] = date('Y-m-d', strtotime($d));	
            	}
            	
            }

            if(@$getdata->dob){
                //die($getdata->dob);
                $month = date("m",strtotime($getdata->dob));
            	$day = date("d",strtotime($getdata->dob));
            	if($month == "12" && $day=="31"){
            		$return_array['dob'] = date('Y-m-d', strtotime($getdata->dob));
            	}else{
            		$d = date("Y-m-d",strtotime($getdata->dob));
                	$return_array['dob'] = date('Y-m-d', strtotime($d . ' +1 day'));
					$return_array['dob'] = date('Y-m-d', strtotime($d));	
            	}
            }

            if(@$getdata->AGE){
                $return_array['age'] = $getdata->AGE;    
            }
            if(@$getdata->age){
                $return_array['age'] = $getdata->age;    
            }
            
            if(@$getdata->HOUSE_NO){
                $return_array['house_no'] = $getdata->HOUSE_NO;
            }

            if(@$getdata->house_no){
                $return_array['house_no'] = $getdata->house_no;
            }

            $return_array['cons_name'] = $getcons->cons_name;
            $return_array['dist_name'] = $getdistrict->dist_name;
            $return_array['state_name'] = $getstate->StateName;
            $return_array['ps_name'] = $getpsdetail->poll_building.' '.$getpsdetail->poll_building_detail;
            $return_array['latitude'] =  $getpsdetail->latitude;
            $return_array['longitude'] = $getpsdetail->longitude;
            $return_array['state_id'] = $state_id;
            $return_array['dist_code'] = $getdata->dist_code;
            $return_array['cons_code'] = $getdata->cons_code;

            $return_array['serial_no'] = $getdata->SLNOINPART;
            

            if(@$getdata->PART_NO){
                $return_array['ps_id'] = $getdata->PART_NO;
                $getdata->PART_NO = $return_array['ps_id'];
            }
            if(@$getdata->ps_id){
                $return_array['ps_id'] = $getdata->ps_id;
                $getdata->PART_NO = $return_array['ps_id'];
            }
            $return_array['ps_image'] = get_aws_images_url()."poll_booths/".str_pad($state_id, 3, '0', STR_PAD_LEFT).'/'.str_pad($getdata->dist_code, 3, '0', STR_PAD_LEFT).'/AC'.str_pad($getdata->cons_code, 3, '0', STR_PAD_LEFT).'/'.str_pad($getdata->PART_NO, 3, '0', STR_PAD_LEFT).'.jpg';
            
            // echo "<pre>";
            // print_r($return_array);
            // echo "</pre>";
            // die;

            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
        /*Change if ECI data is running properly */
        /*if(@$getdata->response->numFound)
        {
            $getstate = DB::table('states')->where('st_code',$getdata->ST_Code)->first();
            $state_id = $getstate->StateID;
            $getdistrict = DB::table('constituencies')->where('state_id',$state_id)->where('cons_code',$getdata->AC_NO)->first();
            $dist_code = $getdistrict->dist_code;
            $cons_code = $getdata->AC_NO;
            $ps_id = $getdata->PART_NO;
            // $bid = str_pad($getdata->dist_code, 2, '0', STR_PAD_LEFT).str_pad($getdata->cons_code, 3, '0', STR_PAD_LEFT).str_pad($getdata->ps_id, 3, '0', STR_PAD_LEFT);
            // $getps = DB::table('poll_booths')->where('bid',$bid)->first();
            $return_array['idcardNo'] = $getdata->response->docs[0]->epic_no;
            $return_array['name'] = $getdata->response->docs[0]->name;
            //$return_array['LastName'] = $getdata->LastNameEn;
            if($getdata->response->docs[0]->gender == 'M'){
                $gender = "Male";
            }
            else{
                $gender = "Female";
            }
            $return_array['gender'] = $gender;
            if($getdata->response->docs[0]->rln_type == 'H'){
                $relation_type = "Husband";
            }
            else{
                $relation_type = "Father";   
            }
            $return_array['relation_type'] =  $relation_type;
            $return_array['relation_name'] = $getdata->response->docs[0]->rln_name;
            //$return_array['relation_last_name'] = $getdata->response->docs[0]->gender;
            //$return_array['mobileno'] = $getdata->response->docs[0]->gender;
            $return_array['dob'] = date("Y-m-d",strtotime($getdata->response->docs[0]->dob));
            $return_array['age'] = $getdata->response->docs[0]->age;
            $return_array['house_no'] = $getdata->response->docs[0]->house_no;
            //$return_array['address'] = $getdata->response->docs[0]->gender;
            //$return_array['village'] = $getdata->response->docs[0]->gender;
            $return_array['cons_name'] = $getdata->response->docs[0]->ac_name;
            $return_array['dist_name'] = $getdata->response->docs[0]->district;
            $return_array['state_name'] = $getdata->response->docs[0]->state;
            $return_array['ps_name'] = $getdata->response->docs[0]->ps_name;
            //$return_array['poll_areas'] = $getdata->response->docs[0]->gender;
            $latitudelong =  explode(",",$getdata->response->docs[0]->ps_lat_long);
            $return_array['latitude'] = trim($latitudelong[0]);
            $return_array['longitude'] = trim($latitudelong[1]);
            

            $return_array['ps_image'] = get_aws_images_url()."poll_booths/".str_pad($state_id, 3, '0', STR_PAD_LEFT).'/'.str_pad($dist_code, 3, '0', STR_PAD_LEFT).'/AC'.str_pad($cons_code, 3, '0', STR_PAD_LEFT).'/'.str_pad($ps_id, 3, '0', STR_PAD_LEFT).'.jpg';
            
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }*/
        else{
            $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }
    }

    public function know_voter_data(Request $request){
        $voter_number = $request->voter_number;
        if(!isset($voter_number) || $voter_number == "")            
        {               
            $return = array('result' => 'Please enter your voter number','status_code'=>204);
            exit(json_encode($return));         
        
        }
       //getdata = DB::table('voters')->where('idcardNo',$request->voter_number)->first();
        $getdata = json_decode(voter_details($voter_number));

        // echo "<pre>";
        // print_r($getdata);
        // die;
        
        if(@$getdata->ST_Code){
           //$getdata->ST_Code = $getdata->ST_Code;
            
            $getstate = DB::table('states')->where('st_code',$getdata->ST_Code)->first();
            
            
            $state_id = $getstate->StateID;
            //dd($state_id);
            $getceo = DB::table('users')->where('state_id',$state_id)->where('role', 2)->first();
            //dd($getceo);
            $getdistrict = DB::table('constituencies')->where('state_id',$state_id)->where('cons_code',$getdata->AC_NO)->first();

            $getdeo = DB::table('users')->where('dist_code',$getdistrict->dist_code)->where('role', 3)->first();
            $getro = DB::table('users')->where('cons_code',$getdata->AC_NO)->where('role', 4)->first();
            $pollingstations = DB::table('poll_booths')
                            ->join('users','poll_booths.blo_uid','users.uid')
                            ->where('poll_booths.state_id', $state_id)
                            ->where('poll_booths.dist_code', $getdistrict->dist_code)
                            ->where('poll_booths.cons_code', $getdata->AC_NO)
                            ->where('poll_booths.ps_id', $getdata->PART_NO)
                            ->first();
            if(@$getceo){
                $return_array['ceo_name'] = $getceo->name;
                $return_array['ceo_address'] = $getceo->address;
                $return_array['ceo_phone'] = $getceo->office_phone;
            }
            else{
                $return_array['ceo_name'] = '';
                $return_array['ceo_address'] = '';
                $return_array['ceo_phone'] = '';
            }
            if(@$getdeo){
                $return_array['deo_name'] = $getdeo->name;
                $return_array['deo_address'] = $getdeo->address;
                $return_array['deo_phone'] = $getdeo->office_phone;
            }
            else{
                $return_array['deo_name'] = '';
                $return_array['deo_address'] = '';
                $return_array['deo_phone'] = '';
            }
            if(@$getro){
                $return_array['ro_name'] = $getro->name;
                $return_array['ro_address'] = $getro->address;
                $return_array['ro_phone'] = $getro->phone;
            }
            else{
                $return_array['ro_name'] = '';
                $return_array['ro_address'] = '';
                $return_array['ro_phone'] = '';
            }
            if(@$pollingstations){
                $return_array['blo_name'] = $pollingstations->name;
                $return_array['blo_address'] = $pollingstations->address;
                $return_array['blo_phone'] = $pollingstations->phone;
            }
            else{
                $return_array['blo_name'] = '';
                $return_array['blo_address'] = '';
                $return_array['blo_phone'] = '';
            }
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
        else{
            $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }
    }

    public function voters_token(Request $request)
    {
        $deviceToken = $request->deviceToken;
        $deviceType = $request->deviceType;
        $gettoken = DB::table('voters_token')->where('token','=',$deviceToken)->first();
        if($gettoken){
             $deviceData = array(
                'type' => $deviceType,
            );
            $deviceupdate = DB::table('voters_token')->where('token', $deviceToken)->update($deviceData);
        }
        else{
            $deviceData = array(
                'token' => $deviceToken,
                'type' => $deviceType,
            );
            $insertdevicetoken = DB::table('voters_token')->insert($deviceData);
        }
        $return = array('result' => 'Successfull','status_code'=>200);
        exit(json_encode($return));
    }

    public function voters_alert_detail(Request $request)
    {
        $msgid = $request->msgid;
        if(!isset($msgid) || $msgid == "")            
        {               
            $return = array('result' => 'Please enter message Id','status_code'=>204);
            exit(json_encode($return));         
        
        }
        $getmsg = DB::table('voters_alert')->where('id',$msgid)->first();
        if($getmsg){
            $return_array['subject'] = $getmsg->sub;
            $return_array['description'] = $getmsg->description;
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
        else{
            $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return)); 
        }
    }

    public function voters_alert_list()
    {
        $getmsg = DB::table('voters_alert')->where('status',1)->orderBy('id', 'desc')->get();
        if($getmsg->count()){
            foreach($getmsg as $msg){
                $rolist['subject'] = $msg->sub;
                $rolist['description'] = $msg->description;
                $return_array[] = $rolist;
            }
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
        else{
            $return = array('result' => 'There are no alerts.','status_code'=>406);
            exit(json_encode($return)); 
        }
    }

    public function voters_video_list()
    {
        $getvideos = DB::table('voters_video')->where('status',1)->get();
        if($getvideos->count()){
            foreach($getvideos as $video){
                $videolist['video_name'] = $video->video_name;
                $videolist['video_url'] = $video->video_url;
                $return_array[] = $videolist;
            }
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
        else{
            $return = array('result' => 'There are no alerts.','status_code'=>406);
            exit(json_encode($return)); 
        }
    }

    public function pwd_voters_search(Request $request)
    {
        $epic_no = $request->epic_no;
        $json_data = get_pwd_data($epic_no);
        if(!empty($json_data)){
            $data = json_decode($json_data);
            $cons_code = $data->ac_no;
            if($cons_code == '60' || $cons_code == '61' || $cons_code == '62' || $cons_code == '63'|| $cons_code == '64'|| $cons_code == '65'|| $cons_code == '66'){
                $return_array['epic_no'] = $data->IDCARD_NO;
                $return_array['house_no'] = $data->HOUSE_NO;
                $return_array['gender'] = $data->SEX;
                $return_array['dob'] = $data->dob;
                $return_array['name'] = $data->Fm_NameEn.' '.$data->LastNameEn;
                $return_array['relation_name'] = $data->Rln_Fm_NmEn.' '.$data->RLn_L_NmEn;
                $return_array['relation_type'] = $data->RLN_TYPE;
                $return_array['ps_id'] = $data->part_no;
                $return_array['cons_code'] = $cons_code;
                $return = array('result' => $return_array,'status_code'=>200);
            }
            else{
                $return = array('result' => 'Sorry for inconvenience, This is the pilot project for Vidhan Sabha Elections in Ludhiana, Punjab for 2017 Elections. To place your request please contact your DEO/RO/BLO','status_code'=>406);
            }
        }
        else{
            $return = array('result' => 'Sorry for inconvenience, you are not a registered PWD voter with us. To register as a PWD voter, please contact to your DEO/RO/BLO','status_code'=>406);
        }
        exit(json_encode($return));
    }

    public function pwd_request(Request $request)
    {
        $current_time = current_datetime();
        $epic_no = $request->epic_no;
        $phone =  $request->phone;
        $address =  $request->address;
        $name = $request->name;
        $gender = $request->gender;
        $cons_code = $request->cons_code;
        $ps_id = $request->ps_id;
        $state_id = 53;
        $dist_code = 11;
        $disability_type = $request->disability_type;
        $facility_required = $request->facility_required;
        $check = DB::table('ro_pwd_request')->where('pwd_phone',$phone)->where('epic_no',$epic_no)->first();
        if($check){
            $return = array('result' => 'You have already sent request for this EPIC Number','status_code'=>406);
        }
        else{
            $datas = array(
                    'state_id' => $state_id,
                    'dist_code' => $dist_code,
                    'cons_code' => $cons_code,
                    'epic_no' => $epic_no,
                    'pwd_phone' => $phone,
                    'pwd_address' => $address,
                    'type' => $disability_type,
                    'facility_required' => $facility_required,
                    'updated_at' => $current_time,
                );
            $i = DB::table('ro_pwd_request')->insert($datas);
            if($i>0){
                $getnotdata = DB::table('poll_booths')
                          ->join('users','poll_booths.cons_code', 'users.cons_code')
                          ->join('users_token','users.phone', 'users.phone')
                          ->where('poll_booths.state_id', 53)
                          ->where('poll_booths.dist_code', 11)
                          ->where('poll_booths.cons_code', $cons_code)
                          ->where('users.role', '4')
                          ->where('poll_booths.ps_id', $ps_id)->first();
                if($getnotdata){
                    $ps_name = $getnotdata->poll_building.' '.$getnotdata->poll_building_detail;
                    $token = $getnotdata->token;
                    $tokentype = $getnotdata->type;
                    pwdnotification($epic_no,$phone,$name,$ps_name,$disability_type,$gender,$token,$tokentype);
                }
                $return = array('result' => 'Your request has been received, your RO will get in touch with you shortly','status_code'=>200);
            }
            else{
                $return = array('result' => 'Please try again','status_code'=>406);
            }
        }
        exit(json_encode($return));
    }

    public function alertmessage()
    {
        $candidate_msg = 'To be finalized on 21st January, 2017';
        $result_msg = 'To be announced on 11th March, 2017';
        $return_array['candidate_msg'] =$candidate_msg;
        $return_array['result_msg'] = $result_msg;
        $return = array('result' => $return_array,'status_code'=>200);
        exit(json_encode($return));
    }

    public function polling_station_percentage(Request $request){
        $bid = str_pad($request->dist_code, 2, '0', STR_PAD_LEFT).str_pad($request->cons_code, 3, '0', STR_PAD_LEFT).str_pad($request->ps_id, 3, '0', STR_PAD_LEFT);
        $polling_stations = DB::table('pro_polling_percentage')
                            ->where('dist_code', $request->dist_code)
                            ->where('cons_code', $request->cons_code)
                            ->where('bid', $bid)
                            ->first();
        if($polling_stations) {
            $return_array['percentage_8'] = json_decode($polling_stations->percentage_8);
            $return_array['percentage_10'] = json_decode($polling_stations->percentage_10);
            $return_array['percentage_12'] = json_decode($polling_stations->percentage_12);
            $return_array['percentage_14'] = json_decode($polling_stations->percentage_14);
            $return_array['percentage_16'] = json_decode($polling_stations->percentage_16);
            $return_array['percentage_18'] = json_decode($polling_stations->percentage_18);
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
            
        }
        else{
            $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }
    }

}