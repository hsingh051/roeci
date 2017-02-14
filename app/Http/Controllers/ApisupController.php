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

class ApisupController extends Controller 
{
    public function evm_laworder_count(Request $request){
        $getevm = DB::table('poll_booths')->join('pro_evm_malfunctioning', 'poll_booths.bid','pro_evm_malfunctioning.bid')->where('poll_booths.supervisior_uid', $request->user_id)->where('poll_booths.cons_code', $request->cons_code)->select(DB::raw('count(*) as badgecount, poll_booths.bid'))->groupBy('bid')->get();
        $getlaw = DB::table('poll_booths')->join('pro_law_order', 'poll_booths.bid','pro_law_order.bid')->where('poll_booths.supervisior_uid', $request->user_id)->where('poll_booths.cons_code', $request->cons_code)->get();
        $evmcount = count($getevm);
        $lawcount = count($getlaw);
        $return_array['evmcount'] = $evmcount;
        $return_array['lawcount'] = $lawcount;
        $return = array('result' => $return_array,'status_code'=>200);
        exit(json_encode($return));
    }

    public function evm_malfunction_list(Request $request){
        $getevm = DB::table('poll_booths')->join('pro_evm_malfunctioning', 'poll_booths.bid','pro_evm_malfunctioning.bid')->where('poll_booths.supervisior_uid', $request->user_id)->where('poll_booths.cons_code', $request->cons_code)->select(DB::raw('poll_booths.bid'))->groupBy('bid')->get();
        print_r($getevm);
        die();
        $return_array['evmcount'] = $evmcount;
        $return_array['lawcount'] = $lawcount;
        $return = array('result' => $return_array,'status_code'=>200);
        exit(json_encode($return));
    }

    public function supervisor_detail(Request $request){
        $state_id = $request->state_id;
        $getceo = DB::table('users')->where('state_id',$state_id)->where('role','2')->first();
        $getdeo = DB::table('users')->where('dist_code',$request->dist_code)->where('role','3')->first();
        $getro = DB::table('users')->where('cons_code',$request->cons_code)->where('dist_code',$request->dist_code)->where('role','4')->first();
        $return_array['ceoname'] = $getceo->name;
        $return_array['ceophone'] = $getceo->phone;
        $return_array['deoname'] = $getdeo->name;
        $return_array['deophone'] = $getdeo->phone;
        $return_array['roname'] = $getro->name;
        $return_array['rophone'] = $getro->phone;
        $return = array('result' => $return_array,'status_code'=>200);
        exit(json_encode($return));
    }

    public function supervisor_ps_list(Request $request){
        $getpslist = DB::table('poll_booths')->where('cons_code',$request->cons_code)->where('dist_code',$request->dist_code)->where('supervisior_uid',$request->user_id)->get();
        if($getpslist->count()) {
            foreach($getpslist as $getdata){
                $pslist['bid'] = $getdata->bid;
                $pslist['ps_name'] = $getdata->poll_building.' '.$getdata->poll_building_detail;
                $pslist['latitude'] = $getdata->latitude;
                $pslist['longitude'] = $getdata->longitude;
                $pslist['longitude'] = $getdata->longitude;
                $pslist['poll_type'] = $getdata->poll_type;
                $return_array[] = $pslist;
            }
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
        else{
            $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }
    }

    public function ps_detail(Request $request){
        $getpsdetail = DB::table('poll_booths')->where('bid',$request->bid)->first();
        if($getpsdetail){
            $getpsro = DB::table('users')->join('poll_booths', 'users.cons_code','poll_booths.cons_code')->join('constituencies', 'users.cons_code','constituencies.cons_code')->where('users.cons_code',$getpsdetail->cons_code)->where('role','4')->first();
            $getpssup = DB::table('poll_booths')->join('users', 'poll_booths.supervisior_uid','users.uid')->where('poll_booths.bid',$request->bid)->where('role','5')->first();
            $getpsblo = DB::table('poll_booths')->join('users', 'poll_booths.blo_uid','users.uid')->where('poll_booths.bid',$request->bid)->where('role','7')->first();
            $getpspro = DB::table('randomization_staff_third')->join('users_pollday', 'randomization_staff_third.uid','users_pollday.uid')->where('randomization_staff_third.uid', 'like', 'PRO%')->where('bid',$request->bid)->first();
            if(@$getpspro){
                $return_array['pro_name'] = $getpspro->name;
                $return_array['pro_phone'] = $getpspro->phone;
            }
            else{
                $return_array['pro_name'] = "";
                $return_array['pro_phone'] = "";
            }
            if(@$getpsblo){
                $return_array['blo_name'] = $getpsblo->name;
                $return_array['blo_phone'] = $getpsblo->phone;
            }
            else{
                $return_array['blo_name'] = "";
                $return_array['blo_phone'] = "";
            }
            $getpsapro = DB::table('randomization_staff_third')->join('users_pollday', 'randomization_staff_third.uid','users_pollday.uid')->where('randomization_staff_third.uid', 'like', 'APR%')->where('bid',$request->bid)->first();
            if(@$getpsapro){
                $return_array['apro_name'] = $getpsapro->name;
                $return_array['apro_phone'] = $getpsapro->phone;
            }
            else{
                $return_array['apro_name'] = "";
                $return_array['apro_phone'] = "";
            }
            $getpspoo = DB::table('randomization_staff_third')->join('users_pollday', 'randomization_staff_third.uid','users_pollday.uid')->where('randomization_staff_third.uid', 'like', 'POO%')->where('bid',$request->bid)->get();
            foreach($getpspoo as $getdata){
                $poolist['name'] = $getdata->name;
                $poolist['phone'] = $getdata->phone;
                $poolist['designation'] = $getdata->designation;
                $poo_array[] = $poolist;
            }
            if (empty($poo_array)) {
               $poo_array = array();
            }
            $getawarnessgroup = DB::table('booth_awareness_groups')->where('bid',$request->bid)->get();
            foreach($getawarnessgroup as $getgroup){
                $grouplist['name'] = $getgroup->name;
                $grouplist['phone'] = $getgroup->phone;
                $group_array[] = $grouplist;
            }
            if (empty($group_array)) {
               $group_array = array();
            }
            $return_array['ps_id'] = $getpsdetail->ps_id;
            $return_array['bid'] = $getpsdetail->bid;
            $return_array['ro_name'] = $getpsro->name;
            $return_array['ro_phone'] = $getpsro->phone;
            $return_array['sup_name'] = $getpssup->name;
            $return_array['sup_phone'] = $getpssup->phone;
            $return_array['cons_name'] = $getpsro->cons_name;
            $return_array['ps_name'] = $getpsdetail->poll_building.' '.$getpsdetail->poll_building_detail;
            $return_array['poll_type'] = $getpsdetail->poll_type;
            $return_array['latitude'] = $getpsdetail->latitude;
            $return_array['longitude'] = $getpsdetail->longitude;
            $return_array['poll_areas'] = $getpsdetail->poll_areas;
            $return_array['ps_image'] = get_aws_images_url()."poll_booths/".str_pad($request->state_id, 3, '0', STR_PAD_LEFT).'/'.str_pad($getpsdetail->dist_code, 3, '0', STR_PAD_LEFT).'/AC'.str_pad($getpsdetail->cons_code, 3, '0', STR_PAD_LEFT).'/'.str_pad($getpsdetail->ps_id, 3, '0', STR_PAD_LEFT).'.jpg';
            $return_array['poo_list'] = $poo_array;
            $return_array['awareness_group'] = $group_array;
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
        else{
            $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }
    }

    public function poll_day_status(Request $request){
        $getdata = DB::table('pro_activity_pollday')->where('bid', $request->bid)->where('cons_code', $request->cons_code)->where('dist_code', $request->dist_code)->first();
        if($getdata){
            $return_array['setup_pollbooth'] = json_decode($getdata->setup_pollbooth);
            $return_array['mock_poll'] = json_decode($getdata->mock_poll);
            $return_array['evm_reset'] = json_decode($getdata->evm_reset);
            $return_array['poll_start'] = json_decode($getdata->poll_start);
            $return_array['handbook_annexure_13'] = json_decode($getdata->handbook_annexure_13);
            $return_array['queue_status_17'] = json_decode($getdata->queue_status_17);
            $return_array['poll_end_time'] = json_decode($getdata->poll_end_time);
            $return_array['poll_end_button'] = json_decode($getdata->poll_end_button);
            $return_array['turn_off_evm'] = json_decode($getdata->turn_off_evm);
            $return_array['lock_seal_evm'] = json_decode($getdata->lock_seal_evm);
            $return_array['handbook_annexure_19'] = json_decode($getdata->handbook_annexure_19);
            $return_array['election_material'] = json_decode($getdata->election_material);
            $return_array['polled_evm'] = json_decode($getdata->polled_evm);
           // $return_array['request_pickup'] = $getdata->request_pickup;
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
        else{
            $return_array['setup_pollbooth'] = null;
            $return_array['mock_poll'] = null;
            $return_array['evm_reset'] = null;
            $return_array['poll_start'] = null;
            $return_array['handbook_annexure_13'] = null;
            $return_array['queue_status_17'] = null;
            $return_array['poll_end_time'] = null;
            $return_array['poll_end_button'] = null;
            $return_array['turn_off_evm'] = null;
            $return_array['lock_seal_evm'] = null;
            $return_array['handbook_annexure_19'] = null;
            $return_array['election_material'] = null;
            $return_array['polled_evm'] = null;
           // $return_array['request_pickup'] = 0;
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
       
    }

    public function evm_vvpat_list(Request $request){
        $getdata = DB::table('poll_booths')->join('randomization_evm_second', 'poll_booths.bid', 'randomization_evm_second.bid')->where('poll_booths.supervisior_uid', $request->user_id)->where('poll_booths.cons_code', $request->cons_code)->where('poll_booths.dist_code', $request->dist_code)->get();
        if($getdata->count()){
            foreach($getdata as $data){
                $evm_list['bid'] = $data->bid;
                $evm_list['ps_name'] = $data->poll_building.' '.$data->poll_building_detail;
                $evm_list['cu'] = $data->cu;
                $evm_list['bu1'] = $data->bu1;
                $evm_list['vvpat'] = $data->vvpat;
                $return_array[] = $evm_list;
            }
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
        else{
           $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }
       
    }

    public function pro_request_list(Request $request){
        $getdata = DB::table('poll_booths')->join('pro_activity_pollday', 'poll_booths.bid', 'pro_activity_pollday.bid')->join('users_pollday', 'pro_activity_pollday.uid', 'users_pollday.uid')->where('poll_booths.supervisior_uid', $request->user_id)->where('poll_booths.cons_code', $request->cons_code)->where('poll_booths.dist_code', $request->dist_code)->where('pro_activity_pollday.request_pickup', 1)->get();
        if($getdata->count()){
            foreach($getdata as $data){
                $request_list['ps_name'] = $data->poll_building.' '.$data->poll_building_detail;
                $request_list['name'] = $data->name;
                $return_array[] = $request_list;
            }
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
        else{
           $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }
       
    }

    public function pollbeforeday(Request $request){

        $polling_stations = DB::table('poll_booths')
                            ->leftjoin('pro_activity_before', 'poll_booths.bid', '=', 'pro_activity_before.bid')
                            ->where('poll_booths.state_id', $request->state_id)
                            ->where('poll_booths.dist_code', $request->dist_code)
                            ->where('poll_booths.cons_code', $request->cons_code)
                            ->where('poll_booths.supervisior_uid', $request->user_id)
                            ->select('poll_booths.bid','poll_booths.poll_building','poll_booths.poll_building_detail','pro_activity_before.election_material','pro_activity_before.party_reached','pro_activity_before.evm_received')
                            ->orderby('poll_booths.ps_id')
                            ->get();
        //print_r($polling_stations);
        if($polling_stations->count()) { 
            foreach($polling_stations as $pslist){

                $group['booth_id'] = $pslist->bid;
                $group['name'] = $pslist->poll_building.' '.$pslist->poll_building_detail;
                $group['election_material'] = $pslist->election_material;
                $group['party_reached'] = $pslist->party_reached;
                $group['evm_received'] = $pslist->evm_received;
                //$group['poll_building'] = $pslist->poll_building;
                $return_array[] = $group;
            }

            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
            
        }
        else{
            $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }
    }

   /* public function election_material_receive_report(Request $request){
        $state_id = get_state_id();
        $getdata = DB::table('poll_booths')->where('supervisior_uid', $request->user_id)->where('state_id', $state_id)->where('dist_code', $request->dist_code)->where('cons_code', $request->cons_code)->get();
        if(@$getdata){
            foreach($getdata as $data){
                $getmaterial_report = DB::table('pro_activity_before')->where('bid', $data->bid)->where('dist_code', $data->dist_code)->where('cons_code', $data->cons_code)->first();
                $material['ps_name'] = $data->poll_building;
                if(@$getmaterial_report){
                    $material['election_material'] = $getmaterial_report->election_material;
                }
                else{
                    $material['election_material'] = 0;
                }
                $return_array[] = $material;
            }
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
        else{
           $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }
       
    }

    public function election_material_return_report(Request $request){
        $state_id = get_state_id();
        $getdata = DB::table('poll_booths')->where('supervisior_uid', $request->user_id)->where('state_id', $state_id)->where('dist_code', $request->dist_code)->where('cons_code', $request->cons_code)->get();
        if(@$getdata){
            foreach($getdata as $data){
                $getmaterial_report = DB::table('pro_activity_pollday')->where('bid', $data->bid)->where('dist_code', $data->dist_code)->where('cons_code', $data->cons_code)->first();
                $material['ps_name'] = $data->poll_building;
                if(@$getmaterial_report){
                    $material['election_material'] = $getmaterial_report->election_material;
                }
                else{
                    $material['election_material'] = 0;
                }
                $return_array[] = $material;
            }
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
        else{
           $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }
       
    }*/

    public function polling_percentage(Request $request){
        $current_time = current_hour();
        $polling_stations = DB::table('poll_booths')
                            ->where('dist_code', $request->dist_code)
                            ->where('cons_code', $request->cons_code)
                            ->where('supervisior_uid', $request->user_id)
                            ->get();
        if($polling_stations->count()) {
            foreach($polling_stations as $pslist){
                $group = array();
                $group['bid'] = $pslist->bid;
                $group['ps_name'] = $pslist->poll_building.' '.$pslist->poll_building_detail;
                $group['poll_building'] = $pslist->poll_building.' '.$pslist->poll_building_detail;
                $getdata = DB::table('pro_polling_percentage')->where('bid', $pslist->bid)->first();
                if($getdata){
                    if($current_time>=8 && $current_time<10){
                        if(!is_null($getdata->percentage_8)){
                            $percentage_data = json_decode($getdata->percentage_8);
                            $group['percentage'] = $percentage_data->percentage;
                            $group['male'] = $percentage_data->male;
                            $group['female'] = $percentage_data->female;
                            $group['tg'] = $percentage_data->tg;
                            $group['queue'] = $percentage_data->queue;
                        }
                        else{
                            $group['percentage'] = '-';
                            $group['male'] = '-';
                            $group['female'] = '-';
                            $group['tg'] = '-';
                            $group['queue'] = '-';
                        }
                    }
                    elseif($current_time>=10 && $current_time<12){
                        if(!is_null($getdata->percentage_10)){
                            $percentage_data = json_decode($getdata->percentage_10);
                            $group['percentage'] = $percentage_data->percentage;
                            $group['male'] = $percentage_data->male;
                            $group['female'] = $percentage_data->female;
                            $group['tg'] = $percentage_data->tg;
                            $group['queue'] = $percentage_data->queue;
                        }
                        else{
                            $group['percentage'] = '-';
                            $group['male'] = '-';
                            $group['female'] = '-';
                            $group['tg'] = '-';
                            $group['queue'] = '-';
                        } 
                    }
                    elseif($current_time>=12 && $current_time<14){
                        if(!is_null($getdata->percentage_12)){
                            $percentage_data = json_decode($getdata->percentage_12);
                            $group['percentage'] = $percentage_data->percentage;
                            $group['male'] = $percentage_data->male;
                            $group['female'] = $percentage_data->female;
                            $group['tg'] = $percentage_data->tg;
                            $group['queue'] = $percentage_data->queue;
                        }
                        else{
                            $group['percentage'] = '-';
                            $group['male'] = '-';
                            $group['female'] = '-';
                            $group['tg'] = '-';
                            $group['queue'] = '-';
                        }
                    }
                    elseif($current_time>=14 && $current_time<16){
                        if(!is_null($getdata->percentage_14)){
                            $percentage_data = json_decode($getdata->percentage_14);
                            $group['percentage'] = $percentage_data->percentage;
                            $group['male'] = $percentage_data->male;
                            $group['female'] = $percentage_data->female;
                            $group['tg'] = $percentage_data->tg;
                            $group['queue'] = $percentage_data->queue;
                        }
                        else{
                            $group['percentage'] = '-';
                            $group['male'] = '-';
                            $group['female'] = '-';
                            $group['tg'] = '-';
                            $group['queue'] = '-';
                        }
                    }
                    elseif($current_time>=16 && $current_time<18){
                        if(!is_null($getdata->percentage_16)){
                            $percentage_data = json_decode($getdata->percentage_16);
                            $group['percentage'] = $percentage_data->percentage;
                            $group['male'] = $percentage_data->male;
                            $group['female'] = $percentage_data->female;
                            $group['tg'] = $percentage_data->tg;
                            $group['queue'] = $percentage_data->queue;
                        }
                        else{
                            $group['percentage'] = '-';
                            $group['male'] = '-';
                            $group['female'] = '-';
                            $group['tg'] = '-';
                            $group['queue'] = '-';
                        }
                    }
                    else{
                        if(!is_null($getdata->percentage_18)){
                            $percentage_data = json_decode($getdata->percentage_18);
                            $group['percentage'] = $percentage_data->percentage;
                            $group['male'] = $percentage_data->male;
                            $group['female'] = $percentage_data->female;
                            $group['tg'] = $percentage_data->tg;
                            $group['queue'] = $percentage_data->queue;
                        }
                        else{
                            $group['percentage'] = '-';
                            $group['male'] = '-';
                            $group['female'] = '-';
                            $group['tg'] = '-';
                            $group['queue'] = '-';
                        }
                    }
                }
                else{
                    $group['percentage'] = '-';
                    $group['male'] = '-';
                    $group['female'] = '-';
                    $group['tg'] = '-';
                    $group['queue'] = '-';
                }
                $return_array[] = $group;
            }
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
            
        }
        else{
            $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }
    }

    public function polling_percentage_timing(Request $request){
        $polling_stations = DB::table('poll_booths')
                            //->where('poll_booths.state_id', $request->state_id)
                            ->where('poll_booths.dist_code', $request->dist_code)
                            ->where('poll_booths.cons_code', $request->cons_code)
                            ->where('supervisior_uid', $request->user_id)
                            ->get();
        $timing = $request->polling_timing;
        if($polling_stations->count()) {
            foreach($polling_stations as $pslist){
                $group = array();
                $group['bid'] = $pslist->bid;
                $group['ps_name'] = $pslist->poll_building.' '.$pslist->poll_building_detail;
                $group['poll_building'] = $pslist->poll_building.' '.$pslist->poll_building_detail;
                $getdata = DB::table('pro_polling_percentage')->where('bid', $pslist->bid)->first();
                if($getdata){
                    $data = $getdata->$timing;
                    if(!is_null($data)){
                        $percentage_data = json_decode($data);
                        $group['percentage'] = $percentage_data->percentage;
                        $group['male'] = $percentage_data->male;
                        $group['female'] = $percentage_data->female;
                        $group['tg'] = $percentage_data->tg;
                        $group['queue'] = $percentage_data->queue;
                    }
                    else{
                        $group['percentage'] = '-';
                        $group['male'] = '-';
                        $group['female'] = '-';
                        $group['tg'] = '-';
                        $group['queue'] = '-';
                    }
                  
                }
                else{
                    $group['percentage'] = '-';
                    $group['male'] = '-';
                    $group['female'] = '-';
                    $group['tg'] = '-';
                    $group['queue'] = '-';
                }
                $return_array[] = $group;
            }
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
            
        }
        else{
            $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }
    }

    public function route_plan(Request $request){
        $getdata = DB::table('route_plan')
            ->where('state_id', $request->state_id)
            ->where('uid', $request->user_id)
            ->where('cons_code', $request->cons_code)
            ->where('dist_code', $request->dist_code)->first();
        if($getdata){
            $aws_s3_files_url = Config::get('constants.AWS_FILES_URL');
            $return_array['file'] = $aws_s3_files_url.$getdata->doc_name;
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
        else{
           
            $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }
       
    }

    public function training_list(Request $request){
        $getrodata = DB::table('training_ro')
            ->where('state_id', $request->state_id)
            ->where('cons_code', $request->cons_code)
            ->where('dist_code', $request->dist_code)->get();
        if($getrodata->count()){
            foreach($getrodata as $data){
                $training_list['name'] = $data->name;
                $training_list['date'] = $data->date;
                $training_list['from_time'] = $data->from_time;
                $training_list['to_time'] = $data->to_time;
                $training_list['location'] = $data->location;
                $return_array[] = $training_list;
           }
        }
        $getdeodata = DB::table('training_deo')
            ->where('state_id', $request->state_id)
            ->where('dist_code', $request->dist_code)->get();
        if($getdeodata->count()){
            foreach($getdeodata as $data){
                $training_list['name'] = $data->name;
                $training_list['date'] = $data->date;
                $training_list['from_time'] = $data->from_time;
                $training_list['to_time'] = $data->to_time;
                $training_list['location'] = $data->location;
                $return_array[] = $training_list;
           }
        }
        if(!empty($return_array)){
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
        else{
            $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }
    }

    public function dashboard_pollday(Request $request){
        $mallfunctions = DB::table('poll_booths')
                      ->join('pro_evm_malfunctioning','poll_booths.bid', 'pro_evm_malfunctioning.bid')
                      ->join('users_pollday','pro_evm_malfunctioning.uid', 'users_pollday.uid')
                      ->where('pro_evm_malfunctioning.state_id', $request->state_id)
                      ->where('pro_evm_malfunctioning.dist_code', $request->dist_code)
                      ->where('pro_evm_malfunctioning.cons_code', $request->cons_code)
                      ->where('poll_booths.supervisior_uid', $request->user_id)
                      ->where('pro_evm_malfunctioning.status', 0)
                      ->get();
        if($mallfunctions->count()){
            $return_evm_status['tag'] = 1;
            foreach ($mallfunctions as $mallfunction) {
                $mallfunction_array['poll_building'] = $mallfunction->poll_building.' '.$mallfunction->poll_building_detail;
                $mallfunction_array['bid'] = $mallfunction->bid;
                $mallfunction_array['ps_id'] = $mallfunction->ps_id;
                $mallfunction_array['name'] = $mallfunction->name;
                $bind_mallfunction_array[] = $mallfunction_array;
            }
            $return_evm_status['count'] = $mallfunctions->count();
            $return_evm_status['data'] = $bind_mallfunction_array;
        }
        else{
            $return_evm_status['count'] = 0;
            $return_evm_status['tag'] = 0;
            $msg['poll_building'] = "";
            $msg['bid'] = "";
            $msg['ps_id'] = "";
            $msg['name'] = "";
            $return_evm_status['data'][] = $msg;
        }
        $mallfunctions_resolve = DB::table('poll_booths')
                      ->join('pro_evm_malfunctioning','poll_booths.bid', 'pro_evm_malfunctioning.bid')
                      ->join('users_pollday','pro_evm_malfunctioning.uid', 'users_pollday.uid')
                      ->select('poll_booths.bid','poll_booths.poll_building','poll_booths.poll_building_detail','poll_booths.ps_id','users_pollday.name','pro_evm_malfunctioning.id')
                      ->where('pro_evm_malfunctioning.state_id', $request->state_id)
                      ->where('pro_evm_malfunctioning.dist_code', $request->dist_code)
                      ->where('pro_evm_malfunctioning.cons_code', $request->cons_code)
                      ->where('poll_booths.supervisior_uid', $request->user_id)
                      ->where('pro_evm_malfunctioning.status', 1)
                      ->get();
        if($mallfunctions_resolve->count()){
            foreach ($mallfunctions_resolve as $mallfunction_resolve) {
                $mallfunction_arrays['id'] = $mallfunction_resolve->id;
                $mallfunction_arrays['poll_building'] = $mallfunction_resolve->poll_building.' '.$mallfunction_resolve->poll_building_detail;
                $mallfunction_arrays['bid'] = $mallfunction_resolve->bid;
                $mallfunction_arrays['ps_id'] = $mallfunction_resolve->ps_id;
                $mallfunction_arrays['name'] = $mallfunction_resolve->name;
                $resolve_mallfunction_array[] = $mallfunction_arrays;
            }
        }
        else{
            $resolve_mallfunction_array = null;
        }
        $law_orders = DB::table('poll_booths')
                      ->join('pro_law_order','poll_booths.bid', 'pro_law_order.bid')
                      ->join('users_pollday','pro_law_order.uid', 'users_pollday.uid')
                      ->select('poll_booths.bid','poll_booths.poll_building','poll_booths.poll_building_detail','poll_booths.ps_id','users_pollday.name','pro_law_order.id')
                      ->where('pro_law_order.state_id', $request->state_id)
                      ->where('pro_law_order.dist_code', $request->dist_code)
                      ->where('pro_law_order.cons_code', $request->cons_code)
                      ->where('poll_booths.supervisior_uid', $request->user_id)
                      ->get();
        if($law_orders->count()){
            $return_law_status['tag'] = 1;
            foreach ($law_orders as $law_order) {
                $law_order_array['id'] = $law_order->id;
                $law_order_array['poll_building'] = $law_order->poll_building.' '.$law_order->poll_building_detail;
                $law_order_array['bid'] = $law_order->bid;
                $law_order_array['ps_id'] = $law_order->ps_id;
                $law_order_array['name'] = $law_order->name;
                $bind_law_array[] = $law_order_array;
            }
            $return_law_status['count'] = $law_orders->count();
            $return_law_status['data'] = $bind_law_array;
        }
        else{
            $return_law_status['count'] = 0;
            $return_law_status['tag'] = 0;
            $msg['poll_building'] = "";
            $msg['bid'] = "";
            $msg['ps_id'] = "";
            $msg['name'] = "";
            $return_law_status['data'][] = $msg;
        }

        $return_array['evm_mallfunction'] = $return_evm_status;
        $return_array['evm_mallfunction_resolve'] = $resolve_mallfunction_array;
        $return_array['law_order'] = $return_law_status;
        $return = array('result' => $return_array,'status_code'=>200);
        exit(json_encode($return));
    }

    public function dashboard_pollday_activity(Request $request){
        $key_name = $request->key_name;
        $state_id = $request->state_id;
        $dist_code = $request->dist_code;
        $cons_code = $request->cons_code;
        $user_id = $request->user_id;
        $selected_key_value = DB::table('poll_booths')
            ->whereNotIn('bid', function($query) use($state_id, $dist_code, $cons_code, $key_name)
            {
                $query->select('bid')
                      ->from('pro_activity_pollday')
                      ->where('state_id','=',$state_id)
                      ->where('dist_code','=',$dist_code)
                      ->where('cons_code','=',$cons_code)
                      ->where($key_name, 'like', '%"comment_status":"no"%');
            })
            ->select('bid','poll_building','poll_building_detail','ps_id')
            ->where('state_id','=',$state_id)
            ->where('dist_code','=',$dist_code)
            ->where('cons_code','=',$cons_code)
            ->where('supervisior_uid','=',$user_id)
            ->get();
        if($selected_key_value->count()){
            foreach ($selected_key_value as $data) {
                $booth_detail = DB::table('pro_activity_pollday')
                                ->where('state_id','=',$state_id)
                                ->where('dist_code','=',$dist_code)
                                ->where('cons_code','=',$cons_code)
                                ->where('bid','=',$data->bid)
                                ->first();
                $data_array['poll_building'] = $data->poll_building.' '.$data->poll_building_detail;
                $data_array['bid'] = $data->bid;
                $data_array['ps_id'] = $data->ps_id;
                if($booth_detail){
                    $comment = json_decode($booth_detail->$key_name);
                    $data_array['comment'] = $comment->comment;
                    $data_array['activity_time'] = $comment->activity_time;
                }
                else{
                    $data_array['comment'] = 0;
                    $data_array['activity_time'] = 0;
                }
                $return_array[] = $data_array;
            }
            $return = array('result' => $return_array,'status_code'=>200);
        }
        else{
            $return = array('result' => 'No relavent data found','status_code'=>406);
        }
        exit(json_encode($return));
    }

    public function reserve_list_bu(Request $request){
        $state_id = $request->state_id;
        $dist_code = $request->dist_code;
        $cons_code = $request->cons_code;
        $data = DB::table('randomization_evm_reserved')
            // ->whereNotIn('unit_id', function($query) use($state_id, $dist_code, $cons_code)
            // {
            //     $query->select('bu1')
            //           ->from('randomization_evm_second')
            //           ->where('state_id','=',$state_id)
            //           ->where('dist_code','=',$dist_code)
            //           ->where('cons_code','=',$cons_code);
            // })
            ->select('unit_id')
            ->where('state_id','=',$state_id)
            ->where('dist_code','=',$dist_code)
            ->where('cons_code','=',$cons_code)
            ->where('unit_type','=','BALLOT')
            ->where('status','=',1)
            ->get();
        if($data->count()){
            foreach ($data as $cu_list) {
                $data_array['bu'] = $cu_list->unit_id;
                $return_array[] = $data_array;
            }
            $return = array('result' => $return_array,'status_code'=>200);
        }
        else{
            $return = array('result' => 'No relavent data found','status_code'=>406);
        }
        exit(json_encode($return));
    }

    public function reserve_list_cu(Request $request){
        $state_id = $request->state_id;
        $dist_code = $request->dist_code;
        $cons_code = $request->cons_code;
        $data = DB::table('randomization_evm_reserved')
            // ->whereNotIn('unit_id', function($query) use($state_id, $dist_code, $cons_code)
            // {
            //     $query->select('cu')
            //           ->from('randomization_evm_second')
            //           ->where('state_id','=',$state_id)
            //           ->where('dist_code','=',$dist_code)
            //           ->where('cons_code','=',$cons_code);
            // })
            ->select('unit_id')
            ->where('state_id','=',$state_id)
            ->where('dist_code','=',$dist_code)
            ->where('cons_code','=',$cons_code)
            ->where('unit_type','=','CONTROL')
            ->where('status','=',1)
            ->get();
        if($data->count()){
            foreach ($data as $cu_list) {
                $data_array['cu'] = $cu_list->unit_id;
                $return_array[] = $data_array;
            }
            $return = array('result' => $return_array,'status_code'=>200);
        }
        else{
            $return = array('result' => 'No relavent data found','status_code'=>406);
        }
        exit(json_encode($return));
    }

    public function evm_mallfunction_action(Request $request){
        $bid = $request->bid;
        $action_type = $request->action_type;
        $comment = $request->comment;
        if($action_type == 0){
            $mallfunction_data = array(
                'status' => 1, 
                'reply' => $comment,
            );
            $mallfunction_action = DB::table('pro_evm_malfunctioning')
                        ->where('bid', $bid)
                        ->where('status', 0)
                        ->update($mallfunction_data);
            if($mallfunction_action>0){
                $return = array('result' => "updated successfully.",'status_code'=>200);
            }
            else{
                $return = array('result' => 'No relavent data found','status_code'=>406);
            }
        }
        else{
            $old_cu = $request->old_cu;
            $old_bu = $request->old_bu;
            $new_cu = $request->new_cu;
            $new_bu = $request->new_bu;
            $old_bu_data = array(
                'status' => 0,
            );
            $update_bu = DB::table('randomization_evm_reserved')
                        ->where('unit_type', 'BALLOT')
                        ->where('unit_id', $new_bu)
                        ->update($old_bu_data);
            $old_cu_data = array(
                'status' => 0,
            );
            $update_cu = DB::table('randomization_evm_reserved')
                        ->where('unit_type', 'CONTROL')
                        ->where('unit_id', $new_cu)
                        ->update($old_cu_data);
            $bu_cu_data = array(
                'cu' => $new_cu,
                'bu1' => $new_bu,
            );
            $update_data = DB::table('randomization_evm_second')
                        ->where('bid', $bid)
                        ->update($bu_cu_data);
            $mallfunction_data = array(
                'status' => 1,
                'reply' => $comment,
            );
            $mallfunction_action = DB::table('pro_evm_malfunctioning')
                        ->where('bid', $bid)
                        ->where('status', 0)
                        ->update($mallfunction_data);
            if($mallfunction_action>0){
                $return = array('result' => "updated successfully.",'status_code'=>200);
            }
            else{
                $return = array('result' => 'No relavent data found','status_code'=>406);
            }
        }
        exit(json_encode($return));
    }
    
}