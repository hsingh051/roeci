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
use Config; 

class ApicandidateController extends Controller 
{

    public function ps_list(Request $request)
    {
        $state_id = $request->state_id;
        $dist_code = $request->dist_code;
        $cons_code = $request->cons_code;
        $pollingstations_list = DB::table('poll_booths')
                                ->where('state_id',$state_id)
                                ->where('dist_code',$dist_code)
                                ->where('cons_code',$cons_code)->get();
        if($pollingstations_list->count()) {
            foreach($pollingstations_list as $list){
                $getro = DB::table('users')->where('cons_code',$cons_code)->where('role', '4')->first();
                if(@$getro){
                    $ps_list['ro_name'] = $getro->name;
                    $ps_list['ro_address'] = $getro->address;
                    $ps_list['ro_phone'] = $getro->phone;
                }
                else{
                    $ps_list['ro_name'] = '';
                    $ps_list['ro_address'] = '';
                    $ps_list['ro_phone'] = '';
                }
                $getblo = DB::table('users')->where('uid',$list->blo_uid)->where('role', '7')->first();
                if(@$getblo){
                    $ps_list['blo_name'] = $getblo->name;
                    $ps_list['blo_address'] = $getblo->address;
                    $ps_list['blo_phone'] = $getblo->phone;
                }
                else{
                    $ps_list['blo_name'] = '';
                    $ps_list['blo_address'] = '';
                    $ps_list['blo_phone'] = '';
                }
                $ps_list['bid'] = $list->bid;
                $ps_list['ps_id'] = $list->ps_id;
                $ps_list['ps_name'] = $list->poll_building.' '.$list->poll_building_detail;
                $ps_list['poll_areas'] = $list->poll_areas;
                $ps_list['latitude'] = $list->latitude;
                $ps_list['longitude'] = $list->longitude;
                $ps_list['ps_image'] = get_aws_images_url()."poll_booths/".str_pad($state_id, 3, '0', STR_PAD_LEFT).'/'.str_pad($dist_code, 3, '0', STR_PAD_LEFT).'/AC'.str_pad($cons_code, 3, '0', STR_PAD_LEFT).'/'.str_pad($list->ps_id, 3, '0', STR_PAD_LEFT).'.jpg';
                $return_array[] = $ps_list;
            }
            $return = array('result' => $return_array,'status_code'=>200);
			exit(json_encode($return));
            
        }
        else{
            $return = array('result' => 'No relevant data found','status_code'=>406);
			exit(json_encode($return));
        }
    }

    public function evm_vvpat_list(Request $request)
    {
        $state_id = $request->state_id;
        $dist_code = $request->dist_code;
        $cons_code = $request->cons_code;
        $evm_vvpat_date = Config::get('constants.SECOND_RANDOMIZATION_EVM_DATE');
        $time_now=mktime(date('h')+5,date('i')+30,date('s'));
        $current_date = date('Y-m-d', $time_now);
        if (strtotime($evm_vvpat_date) > strtotime($current_date)) {
            $return = array('result' => 'EVM randomization is due on '.$evm_vvpat_date,'status_code'=>406);
            exit(json_encode($return));
        }
        else{
            $getdata = DB::table('poll_booths')
                                    ->leftjoin('randomization_evm_second', 'poll_booths.bid','randomization_evm_second.bid')
                                    ->where('poll_booths.state_id', $state_id)
                                    ->where('poll_booths.dist_code', $dist_code)
                                    ->where('poll_booths.cons_code', $cons_code)->get();
            if($getdata->count()){
                foreach($getdata as $data){
                    $list['ps_name'] = $data->poll_building.' '.$data->poll_building_detail;
                    $list['cu'] = $data->cu;
                    $list['bu'] = $data->bu1;
                    $list['vvpat'] = $data->vvpat;
                    $return_array[] = $list;
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

    public function counting_center(Request $request)
    {
        $state_id = $request->state_id;
        $dist_code = $request->dist_code;
        $cons_code = $request->cons_code;
        $getdata = DB::table('counting_center')
                                ->where('state_id', $state_id)
                                ->where('dist_code', $request->dist_code)
                                ->where('cons_code', $request->cons_code)
                                ->first();
        if($getdata){
            $list['center_name'] = $getdata->center_name;
            $list['center_address'] = $getdata->center_address;
            $list['latitude'] = $getdata->latitude;
            $list['longitude'] = $getdata->longitude;
            $return_array = $list;
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
        else{
            $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }
        
    }

    public function checklogin_candidate()
    {
        $withdrawal_date = Config::get('constants.WIRHDRAWAL_CANDIDATE_START_DATE');
        $time_now=mktime(date('h')+5,date('i')+30,date('s'));
        $current_date = date('Y-m-d', $time_now);
        if (strtotime($withdrawal_date) > strtotime($current_date)) {
            $return = array('result' => 'Candidates can login on '.$withdrawal_date.', once list of contesting candidates is finalized','status_code'=>406);
            exit(json_encode($return));
        }
        else{
            $return = array('result' => 'You can login now','status_code'=>200);
            exit(json_encode($return));
        }
        
    }

    public function candidate_profile(Request $request)
    {
        $state_id = $request->state_id;
        $dist_code = $request->dist_code;
        $cons_code = $request->cons_code;
        $uid = $request->user_id;
        $getdata = DB::table('users')
                    ->leftjoin('users_candidate_data', 'users.uid','users_candidate_data.uid')
                    ->where('users.state_id', $state_id)
                    ->where('users.dist_code', $dist_code)
                    ->where('users.cons_code', $cons_code)
                    ->where('users.uid', $uid)
                    ->first();
        if($getdata){
            $list['uid'] = $getdata->uid;
            $list['name'] = $getdata->name;
            $list['email'] = $getdata->email;
            $list['phone'] = $getdata->phone;
            $list['state_code'] = "S19";
            $list['cons_code'] = $cons_code;
            $list['cand_sl_no'] = $getdata->cand_sl_no;
            // $list['address'] = $getdata->address;
            // $list['guardian_name'] = $getdata->guardian_name;
            $list['party_name'] = $getdata->cand_party;
            // $list['party_symbol_name'] = $getdata->symbol_des;
            // $list['party_symbol_image'] = url('/').'/'.$getdata->symbol_pic;
            $list['profile_pic'] = url('/').'/images/candidate/profilePicture/'.$getdata->profile_pic;
            $return_array = $list;
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
        else{
            $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }
        
    }

    public function candidate_alert(Request $request)
    {
        $state_id = $request->state_id;
        $dist_code = $request->dist_code;
        $cons_code = $request->cons_code;
        $getdata = DB::table('candidate_alert')
                                ->where('state_id', $state_id)
                                ->where('dist_code', $dist_code)
                                ->where('cons_code', $cons_code)->get();
        if($getdata->count()){
            foreach($getdata as $data){
                $list['sub'] = $data->sub;
                $list['description'] = $data->description;
                $return_array[] = $list;
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