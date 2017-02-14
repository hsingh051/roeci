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

class ApimediaController extends Controller 
{

    /* List of polling station */
    public function polling_station_list(Request $request)
    {
        $state_id = $request->state_id;
        $dist_code = $request->dist_code;
        $cons_code = $request->cons_code;
        $pollingstations = DB::table('poll_booths')
                            ->where('poll_booths.state_id', $state_id)
                            ->where('poll_booths.dist_code', $dist_code)
                            ->where('poll_booths.cons_code', $cons_code)
                            ->get();
        
        if($pollingstations->count()) { 
            foreach($pollingstations as $pslist){
                $getro = DB::table('users')->where('cons_code',$cons_code)->where('role', '4')->first();
                if(@$getro){
                    $pollingstation['ro_name'] = $getro->name;
                    $pollingstation['ro_address'] = $getro->address;
                    $pollingstation['ro_phone'] = $getro->phone;
                }
                else{
                    $pollingstation['ro_name'] = '';
                    $pollingstation['ro_address'] = '';
                    $pollingstation['ro_phone'] = '';
                }
                $getblo = DB::table('users')->where('uid',$pslist->blo_uid)->where('role', '7')->first();
                if(@$getblo){
                    $pollingstation['blo_name'] = $getblo->name;
                    $pollingstation['blo_address'] = $getblo->address;
                    $pollingstation['blo_phone'] = $getblo->phone;
                }
                else{
                    $pollingstation['blo_name'] = '';
                    $pollingstation['blo_address'] = '';
                    $pollingstation['blo_phone'] = '';
                }
                $pollingstation['ps_id'] = $pslist->ps_id;
                $pollingstation['booth_id'] = $pslist->bid;
                $pollingstation['name'] = $pslist->poll_building.' '.$pslist->poll_building_detail;
                $pollingstation['poll_areas'] = $pslist->poll_areas;
                $pollingstation['latitude'] = $pslist->latitude;
                $pollingstation['longitude'] = $pslist->longitude;
                $pollingstation['ps_image'] = get_aws_images_url()."poll_booths/".str_pad($state_id, 3, '0', STR_PAD_LEFT).'/'.str_pad($dist_code, 3, '0', STR_PAD_LEFT).'/AC'.str_pad($cons_code, 3, '0', STR_PAD_LEFT).'/'.str_pad($pslist->ps_id, 3, '0', STR_PAD_LEFT).'.jpg';
                //$pollingstation['poll_building'] = $pslist->poll_building;
                $return_array[] = $pollingstation;
            }
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
            
        }
        else{
            $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }
    }

    public function counting_center(Request $request)
    {
        $state_id = $request->state_id;
        $dist_code = $request->dist_code;
        $getdata = DB::table('counting_center')
                                ->where('state_id', $state_id)
                                ->where('dist_code', $request->dist_code)
                                ->get();
        if($getdata->count()) { 
            foreach($getdata as $data){
                $list['center_name'] = $data->center_name;
                $list['center_address'] = $data->center_address;
                $list['latitude'] = $data->latitude;
                $list['longitude'] = $data->longitude;
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

    public function candidate_list(Request $request)
    {
        $state_id = $request->state_id;
        $dist_code = $request->dist_code;
        $cons_code = $request->cons_code;
        $withdrawal_date = Config::get('constants.WIRHDRAWAL_CANDIDATE_START_DATE');
        $time_now=mktime(date('h')+5,date('i')+30,date('s'));
        $current_date = date('Y-m-d', $time_now);
        if (strtotime($withdrawal_date) > strtotime($current_date)) {
            $return = array('result' => 'To be finalized on 21st January, 2017','status_code'=>406);
            exit(json_encode($return));
        }
        else{
            $candidates = DB::table('users')
                                ->leftjoin('users_candidate_data','users.uid','users_candidate_data.uid')
                                ->leftjoin('symbols','users_candidate_data.cand_symbol','symbols.symbol_no')
                                ->where('users.state_id', $state_id)
                                ->where('users.dist_code', $dist_code)
                                ->where('users.cons_code', $cons_code)
                                ->where('users.role', '15')
                                ->get();
            if($candidates->count()) { 
                foreach($candidates as $candidate){
                    $list['uid'] = $candidate->uid;
                    $list['name'] = $candidate->name;
                    $list['email'] = $candidate->email;
                    $list['phone'] = $candidate->phone;
                    $list['address'] = $candidate->address;
                    $list['guardian_name'] = $candidate->guardian_name;
                    $list['party_name'] = $candidate->cand_party;
                    $list['party_symbol_name'] = $candidate->symbol_des;
                    $list['party_symbol_image'] = url('/').'/'.$candidate->symbol_pic;
                    $list['profile_pic'] = url('/').'/images/candidate/profilePicture/'.$candidate->profile_pic;
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

    public function media_cons()
    {
        $constituencies = DB::table('constituencies')
                   ->where('state_id', 53)
                   ->where('dist_code', 11)
                   ->whereIn('cons_code', [60, 63, 64])
                   ->get();
        if($constituencies->count()){
            foreach($constituencies as $cons){
                $dt = array();
                $dt['cons_code'] = $cons->cons_code;
                $dt['cons_name'] = $cons->cons_name;
                $return_array[] = $dt;
            }
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }else{
            $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }       
    }
        
}