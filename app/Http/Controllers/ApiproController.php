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

class ApiproController extends Controller 
{
    public function polling_station(Request $request){
        $state_id = $request->state_id;
        $prodetail = DB::table('randomization_staff_third')
                     ->join('poll_booths', 'randomization_staff_third.bid','poll_booths.bid')
                     ->join('users', 'poll_booths.supervisior_uid','users.uid')
                     ->where('randomization_staff_third.uid', $request->user_id)
                     ->first();

        // print_r($prodetail);
        // die;
        if($prodetail){
            $getpsro = DB::table('users')->join('poll_booths', 'users.cons_code','poll_booths.cons_code')->join('constituencies', 'users.cons_code','constituencies.cons_code')->where('users.cons_code',$prodetail->cons_code)->where('role','4')->first();
            $getpsblo = DB::table('poll_booths')->join('users', 'poll_booths.blo_uid','users.uid')->where('bid',$prodetail->bid)->first();
            if(@$getpsblo){
                $return_array['blo_name'] = $getpsblo->name;
                $return_array['blo_phone'] = $getpsblo->phone;
            }
            else{
                $return_array['blo_name'] = "";
                $return_array['blo_phone'] = "";
            }
            $getpsapro = DB::table('randomization_staff_third')->join('users_pollday', 'randomization_staff_third.uid','users_pollday.uid')->where('randomization_staff_third.uid', 'like', 'APR%')->where('bid',$prodetail->bid)->first();
            if(@$getpsapro){
                $return_array['apro_name'] = $getpsapro->name;
                $return_array['apro_phone'] = $getpsapro->phone;
            }
            else{
                $return_array['apro_name'] = "";
                $return_array['apro_phone'] = "";
            }
            $getpspoo = DB::table('randomization_staff_third')->join('users_pollday', 'randomization_staff_third.uid','users_pollday.uid')->where('randomization_staff_third.uid', 'like', 'POO%')->where('bid',$prodetail->bid)->get();
            $getawarnessgroup = DB::table('booth_awareness_groups')->where('bid',$prodetail->bid)->get();
            foreach($getpspoo as $getdata){
                $poolist['name'] = $getdata->name;
                $poolist['phone'] = $getdata->phone;
                //$poolist['designation'] = $getdata->designation;
                $poolist['designation'] = "PO";
                $poo_array[] = $poolist;
            }
            if (empty($poo_array)) {
               $poo_array = array();
            }
            foreach($getawarnessgroup as $getgroup){
                $grouplist['name'] = $getgroup->name;
                $grouplist['phone'] = $getgroup->phone;
                $group_array[] = $grouplist;
            }
            if (empty($group_array)) {
               $group_array = array();
            }
            $return_array['ps_id'] = $prodetail->ps_id;
            $return_array['bid'] = $prodetail->bid;
            $return_array['ro_name'] = $getpsro->name;
            $return_array['ro_phone'] = $getpsro->phone;
            $return_array['sup_name'] = $prodetail->name;
            $return_array['sup_phone'] = $prodetail->phone;
            $return_array['cons_name'] = $getpsro->cons_name;
            $return_array['ps_name'] = $prodetail->poll_building.' '.$prodetail->poll_building_detail;
            $return_array['poll_type'] = $prodetail->poll_type;
            $return_array['latitude'] = $prodetail->latitude;
            $return_array['longitude'] = $prodetail->longitude;
            $return_array['poll_areas'] = $prodetail->poll_areas;
            $return_array['ps_image'] = get_aws_images_url()."poll_booths/".str_pad($state_id, 3, '0', STR_PAD_LEFT).'/'.str_pad($prodetail->dist_code, 3, '0', STR_PAD_LEFT).'/AC'.str_pad($prodetail->cons_code, 3, '0', STR_PAD_LEFT).'/'.str_pad($prodetail->ps_id, 3, '0', STR_PAD_LEFT).'.jpg';
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

    public function ps_evm(Request $request){
        $evmdetail = DB::table('randomization_staff_third')->join('users_pollday', 'randomization_staff_third.uid','users_pollday.uid')->join('poll_booths', 'randomization_staff_third.bid','poll_booths.bid')->join('constituencies', 'poll_booths.cons_code','constituencies.cons_code')->join('randomization_evm_second', 'randomization_staff_third.bid','randomization_evm_second.bid')->where('randomization_staff_third.uid', $request->user_id)->first();
        if($evmdetail){
            $return_array['bid'] = $evmdetail->bid;
            $return_array['assign_to'] = $evmdetail->name;
            $return_array['cu'] = $evmdetail->cu;
            $return_array['bu'] = $evmdetail->bu1;
            $return_array['vvpat'] = $evmdetail->vvpat;
            $return_array['ps_name'] = $evmdetail->poll_building.' '.$evmdetail->poll_building_detail;
            $return_array['cons_name'] = $evmdetail->cons_name;
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
        else{
            $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }
       
    }

    public function collection_dispatch_center(Request $request){
        $cd_center = DB::table('dispatch_collection_center')->where('dist_code', $request->dist_code)->where('cons_code', $request->cons_code)->first();
        if($cd_center){
            $return_array['dispatch_center_name'] = $cd_center->dispatch_name;
            $return_array['dispatch_center_address'] = $cd_center->dispatch_address;
            $return_array['collection_center_name'] = $cd_center->collection_name;
            $return_array['collection_center_address'] = $cd_center->collection_address;
            $return_array['dispatch_latitude'] = $cd_center->dispatch_latitude;
            $return_array['dispatch_longitude'] = $cd_center->dispatch_longitude;
            $return_array['collection_latitude'] = $cd_center->collection_latitude;
            $return_array['collection_longitude'] = $cd_center->collection_longitude;
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
        else{
            $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }
       
    }

    public function polic_personnel(Request $request){
        $state_id = $request->state_id;
        $dist_code = $request->dist_code;
        $cons_code = $request->cons_code;
        $bid = $request->bid;
        $getdata = DB::table('polic_personnel')
                    ->where('state_id', $state_id)
                    ->where('dist_code', $dist_code)
                    ->where('cons_code', $cons_code)
                    ->where('bid', $bid)->first();
        if($getdata){
            $return_array['police_station'] = $getdata->police_station;
            $return_array['sho_name'] = $getdata->sho_name;
            $return_array['sho_phone'] = $getdata->sho_phone;
            $return_array['dsp_name'] = $getdata->dsp_name;
            $return_array['dsp_phone'] = $getdata->dsp_phone;
        }
        else{
            $return_array['police_station'] = '';
            $return_array['sho_name'] = '';
            $return_array['sho_phone'] = '';
            $return_array['dsp_name'] = '';
            $return_array['dsp_phone'] = '';
        }
        $return = array('result' => $return_array,'status_code'=>200);
        exit(json_encode($return));
        
    }

    public function poll_before_activity(Request $request){
        $getdata = DB::table('pro_activity_before')->where('uid', $request->user_id)->where('bid', $request->bid)->first();
        if($getdata){
            $return_array['election_material'] = $getdata->election_material;
            $return_array['party_reached'] = $getdata->party_reached;
            $return_array['evm_received'] = $getdata->evm_received;
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
        else{
            $return_array['election_material'] = 0;
            $return_array['party_reached'] = 0;
            $return_array['evm_received'] = 0;
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
       
    }

    public function poll_before_activity_update(Request $request){
        $current_time = current_datetime();
        $datas = array(
            'uid' => $request->user_id,
            'dist_code' => $request->dist_code,
            'cons_code' => $request->cons_code,
            'bid' => $request->bid,
            'election_material' => $request->election_material,
            'party_reached' => $request->party_reached,
            'evm_received' => $request->evm_received,
            'updated_at' => $current_time,
        );
        $getdata = DB::table('pro_activity_before')->where('uid', $request->user_id)->where('bid', $request->bid)->first();
        if($getdata){
            $i = DB::table('pro_activity_before')->where('uid', $request->user_id)->where('bid', $request->bid)->update($datas);
            if($i==0){
                $return = array('result' => "Please try again!",'status_code'=>406);
                exit(json_encode($return));
            }
        }
        else{
            $i = DB::table('pro_activity_before')->insert($datas);
            if($i==0){
                $return = array('result' => "Please try again!",'status_code'=>406);
                exit(json_encode($return));
            }
        }
        $getupdateddata = DB::table('pro_activity_before')->where('uid', $request->user_id)->where('bid', $request->bid)->first();
        $return_array['election_material'] = $getupdateddata->election_material;
        $return_array['party_reached'] = $getupdateddata->party_reached;
        $return_array['evm_received'] = $getupdateddata->evm_received;
        $return = array('result' => $return_array,'status_code'=>200);
        exit(json_encode($return));
       
    }

    public function poll_day_activity(Request $request){
        $getdata = DB::table('pro_activity_pollday')->where('uid', $request->user_id)->where('bid', $request->bid)->first();
        if($getdata){
            $return_array['setup_pollbooth'] = json_decode($getdata->setup_pollbooth);
            $return_array['mock_poll'] = json_decode($getdata->mock_poll);
            $return_array['agent_present'] = json_decode($getdata->agent_present);
            $return_array['evm_reset'] = json_decode($getdata->evm_reset);
            $return_array['poll_start'] = json_decode($getdata->poll_start);
            $return_array['handbook_annexure_13'] = json_decode($getdata->handbook_annexure_13);
            $return_array['queue_status_17'] = json_decode($getdata->queue_status_17);
            $return_array['tenders_voters'] = json_decode($getdata->tenders_voters);
            $return_array['poll_end_time'] = json_decode($getdata->poll_end_time);
            $return_array['poll_end_button'] = json_decode($getdata->poll_end_button);
            $return_array['turn_off_evm'] = json_decode($getdata->turn_off_evm);
            $return_array['lock_seal_evm'] = json_decode($getdata->lock_seal_evm);
            $return_array['handbook_annexure_19'] = json_decode($getdata->handbook_annexure_19);
            $return_array['request_pickup'] = $getdata->request_pickup;
            $return_array['polled_evm'] = json_decode($getdata->polled_evm);
            $return_array['election_material'] = json_decode($getdata->election_material);
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
        else{
            $return_array['setup_pollbooth'] = null;
            $return_array['mock_poll'] = null;
            $return_array['agent_present'] = null;
            $return_array['evm_reset'] = null;
            $return_array['poll_start'] = null;
            $return_array['handbook_annexure_13'] = null;
            $return_array['queue_status_17'] = null;
            $return_array['tenders_voters'] = null;
            $return_array['poll_end_time'] = null;
            $return_array['poll_end_button'] = null;
            $return_array['turn_off_evm'] = null;
            $return_array['lock_seal_evm'] = null;
            $return_array['handbook_annexure_19'] = null;
            $return_array['request_pickup'] = 0;
            $return_array['polled_evm'] = null;
            $return_array['election_material'] = null;
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
       
    }

    public function poll_day_activity_update(Request $request){
        $current_time = current_datetime();
        $key_name = $request->key_name;
        $array['status'] = $request->status;
        $array['activity_time'] = $current_time;
        $array['comment'] = $request->comment;
        if(!empty($request->comment)){
            $array['comment_status'] = "yes";
        }
        else{
            $array['comment_status'] = "no";
        }
        if($request->key_name == 'tenders_voters' || $request->key_name == 'agent_present'){
            $array['comment_status'] = "no";
        }
        $json_array = json_encode($array);
        $datas = array(
            'uid' => $request->user_id,
            'bid' => $request->bid,
            'dist_code' => $request->dist_code,
            'cons_code' => $request->cons_code,
            $key_name => $json_array,
        );
        $getdata = DB::table('pro_activity_pollday')->where('uid', $request->user_id)->where('bid', $request->bid)->first();
        if($getdata){
            $i = DB::table('pro_activity_pollday')->where('uid', $request->user_id)->where('bid', $request->bid)->update($datas);
            if($i>0){
                $return = array('result' => "Activity updated successfully",'status_code'=>200);
                exit(json_encode($return));
            }
            else{
                $return = array('result' => "Please try again!",'status_code'=>406);
                exit(json_encode($return));
            }
        }
        else{
            $i = DB::table('pro_activity_pollday')->insert($datas);
            if($i>0){
                $return = array('result' => "Activity updated successfully",'status_code'=>200);
                exit(json_encode($return));
            }
            else{
                $return = array('result' => "Please try again!",'status_code'=>406);
                exit(json_encode($return));
            }
        }
       
    }

    public function evm_malfunctioning_status(Request $request){
        $getdata = DB::table('pro_evm_malfunctioning')->where('uid', $request->user_id)->where('bid', $request->bid)->where('cu', $request->cu)->where('bu', $request->bu)->first();
        if($getdata){
            $return_array['comment'] = $getdata->comment;
            $return_array['malfunctioning_from'] = $getdata->malfunctioning_from;
            $return_array['malfunctioning_to'] = $getdata->malfunctioning_to;
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
        else{
            $return_array['comment'] = null;
            $return_array['malfunctioning_from'] = null;
            $return_array['malfunctioning_to'] = null;
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
       
    }

    public function evm_malfunctioning(Request $request){
        $key_name = $request->key_name;
        $current_time = current_datetime();
        $bid = $request->bid;
        $datas = array(
            'uid' => $request->user_id,
            'bid' => $bid,
            'dist_code' => $request->dist_code,
            'cons_code' => $request->cons_code,
            'cu' => $request->cu,
            'bu' => $request->bu,
            'comment' => $request->comment,
            'malfunctioning_from' => $request->malfunctioning_from,
            'malfunctioning_to' => $request->malfunctioning_to,
            'updated_at' => $current_time,
        );
        $getdata = DB::table('pro_evm_malfunctioning')->where('uid', $request->user_id)->where('bid', $bid)->where('cu', $request->cu)->where('bu', $request->bu)->first();
        $getupdatedata = DB::table('pro_evm_malfunctioning')->where('uid', $request->user_id)->where('bid', $bid)->where('cu', $request->cu)->where('bu', $request->bu)->first();
        $getsupervisor = DB::table('poll_booths')
                            ->join('users','poll_booths.supervisior_uid','users.uid')
                            ->where('poll_booths.bid', $bid)->first();
        if($getdata){
            $i = DB::table('pro_evm_malfunctioning')->where('uid', $request->user_id)->where('bid', $bid)->update($datas);
            if($i==0){
                $return = array('result' => "Please try again!",'status_code'=>406);
                exit(json_encode($return));
            }
        }
        else{
            $i = DB::table('pro_evm_malfunctioning')->insert($datas);
            if($i==0){
                $return = array('result' => "Please try again!",'status_code'=>406);
                exit(json_encode($return));
            }
            if($getsupervisor){
                $getro = DB::table('users')
                                ->where('cons_code', $getsupervisor->cons_code)
                                ->where('role', '4')
                                ->first();
                $getpro = DB::table('users_pollday')
                                ->where('uid', $request->user_id)
                                ->first();
                $msg = "EVM malfunction reported by ".$getpro->name." at ".$getsupervisor->poll_building;
                $phonenumber = $getsupervisor->phone;
                $user_role = $getsupervisor->role;
                pro_notification($phonenumber,$bid,$msg,$user_role);
                pro_notification($getro->phone,$bid,$msg,$getro->role);
            }
        }
        $return_array['comment'] = $getupdatedata->comment;
        $return_array['malfunctioning_from'] = $getupdatedata->malfunctioning_from;
        $return_array['malfunctioning_to'] = $getupdatedata->malfunctioning_to;
        $return = array('result' => $return_array,'status_code'=>200);
        exit(json_encode($return));
        
    }

    public function poll_day_percentage(Request $request){
        $dist_code = $request->dist_code;
        $cons_code = $request->cons_code;
        $bid = $request->bid;
        $queue = $request->queue;
        $male = $request->male;
        $female = $request->female;
        $tg = $request->tg;
        $ps_id = ltrim(substr($bid, -3), '0');
        $key_name = $request->key_name;
        $current_time = current_datetime();
        $gettotalcount = DB::table('voters_count')
                            ->where('dist_code',$dist_code)
                            ->where('cons_code',$cons_code)
                            ->where('ps_id',$ps_id)->first();
        $totalvoted = $male + $female + $tg;
        if($totalvoted > $gettotalcount->total_voters){
            $return = array('result' => 'Please check your count','status_code'=>204);
            exit(json_encode($return)); 
        }
        $percentage = ($totalvoted / $gettotalcount->total_voters) * 100;
        $percentage = (string)round($percentage,2);
        $array['percentage'] = $percentage;
        $array['queue'] = $queue;
        $array['activity_time'] = $current_time;
        $array['male'] = $male;
        $array['female'] = $female;
        $array['tg'] = $tg;
        $json_array = json_encode($array);
        $datas = array(
            'uid' => $request->user_id,
            'bid' => $bid,
            'dist_code' => $dist_code,
            'cons_code' => $cons_code,
            $key_name => $json_array,
            'updated_at' => $current_time,
        );
        $getdata = DB::table('pro_polling_percentage')->where('bid', $bid)->first();
        if($getdata){
            $i = DB::table('pro_polling_percentage')->where('uid', $request->user_id)->where('bid', $bid)->update($datas);
            if($i>0){
                $return = array('result' => "Activity updated successfully",'key_name' => $key_name,'status_code'=>200);
                exit(json_encode($return));
            }
            else{
                $return = array('result' => "Please try again!",'status_code'=>406);
                exit(json_encode($return));
            }
        }
        else{
            $i = DB::table('pro_polling_percentage')->insert($datas);
            if($i>0){
                $return = array('result' => "Activity updated successfully",'key_name' => $key_name,'status_code'=>200);
                exit(json_encode($return));
            }
            else{
                $return = array('result' => "Please try again!",'status_code'=>406);
                exit(json_encode($return));
            }
        }
       
    }

    public function poll_day_percentage_detail(Request $request){
        $getdata = DB::table('pro_polling_percentage')->where('uid', $request->user_id)->where('bid', $request->bid)->first();
        if($getdata){
            $return_array['percentage_8'] = json_decode($getdata->percentage_8);
            $return_array['percentage_10'] = json_decode($getdata->percentage_10);
            $return_array['percentage_12'] = json_decode($getdata->percentage_12);
            $return_array['percentage_14'] = json_decode($getdata->percentage_14);
            $return_array['percentage_16'] = json_decode($getdata->percentage_16);
            $return_array['percentage_18'] = json_decode($getdata->percentage_18);
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
        else{
            $return_array['percentage_8'] = null;
            $return_array['percentage_10'] = null;
            $return_array['percentage_12'] = null;
            $return_array['percentage_14'] = null;
            $return_array['percentage_16'] = null;
            $return_array['percentage_18'] = null;
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
       
    }

    public function law_order_status(Request $request){
        $getdata = DB::table('pro_law_order')->where('uid', $request->user_id)->where('bid', $request->bid)->orderby('id','DESC')->first();
        if($getdata){
            $return_array['action_from'] = $getdata->action_from;
            $return_array['action_to'] = $getdata->action_to;
            $return_array['comment'] = $getdata->comment;
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
        else{
            $return_array['action_from'] = null;
            $return_array['action_to'] = null;
            $return_array['comment'] = null;
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
       
    }

    public function law_order(Request $request){
        $current_time = current_datetime();
        $bid = $request->bid;
        $dist_code = $request->dist_code;
        $datas = array(
            'uid' => $request->user_id,
            'bid' => $request->bid,
            'dist_code' => $dist_code,
            'cons_code' => $request->cons_code,
            'comment' => $request->comment,
            'action_taken' => "No",
            'action_from' => $request->action_from,
            'action_to' => $request->action_to,
            'updated_at' => $current_time,
        );
        if($request->type == 'old'){
            $i = DB::table('pro_law_order')->where('uid', $request->user_id)->where('bid', $request->bid)->where('action_to', '=', "")->update($datas);
            if($i==0){
                $return = array('result' => "Please try again!",'status_code'=>406);
                exit(json_encode($return));
            }
        }
        else{
            $i = DB::table('pro_law_order')->insert($datas);
            if($i==0){
                $return = array('result' => "Please try again!",'status_code'=>406);
                exit(json_encode($return));
            }
            $getsupervisor = DB::table('poll_booths')
                            ->join('users','poll_booths.supervisior_uid','users.uid')
                            ->where('poll_booths.bid', $bid)->first();
            if($getsupervisor){
                $getro = DB::table('users')
                                ->where('cons_code', $getsupervisor->cons_code)
                                ->where('role', '4')
                                ->first();
                $getpro = DB::table('users_pollday')
                                ->where('uid', $request->user_id)
                                ->first();
                $var1 = $request->comment.',';
                $var2 = $getpro->name.'('.$getpro->phone.')';
                $var3 = $getsupervisor->poll_building;
                $msg = "Law & Order issue regarding ".$var1." has been reported by ".$var2." at ".$var3;
                $phonenumber = $getsupervisor->phone;
                $user_role = $getsupervisor->role;
                pro_notification($phonenumber,$bid,$msg,$user_role);
                pro_notification($getro->phone,$bid,$msg,$getro->role);
                //lawordersmsalert($msg,$dist_code);
            }
        }

        $getupdateddata = DB::table('pro_law_order')->where('uid', $request->user_id)->where('bid', $request->bid)->orderby('id','DESC')->first();
        $return_array['comment'] = $getupdateddata->comment;
        $return_array['action_from'] = $getupdateddata->action_from;
        $return_array['action_to'] = $getupdateddata->action_to;
        $return = array('result' => $return_array,'status_code'=>200);
        exit(json_encode($return));
    }

    public function law_order_list(Request $request){
        $getdata = DB::table('pro_law_order')
                    ->where('action_to', '!=' ,'')
                    ->where('action_from', '!=' ,'')
                    ->where('uid', $request->user_id)
                    ->where('bid', $request->bid)->get();
        if($getdata->count()){
            foreach ($getdata as $data) {
                $law_order['issue'] = $data->comment;
                $law_order['action_from'] = $data->action_from;
                $law_order['action_to'] = $data->action_to;
                $return_array[] = $law_order;
            }
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
        else{
           $return = array('result' => 'No relevant data','status_code'=>406);
            exit(json_encode($return));
        }
       
    }

    public function poll_day_request(Request $request){
        $key_name = $request->key_name;
        $datas = array(
            'uid' => $request->user_id,
            'bid' => $request->bid,
            'dist_code' => $request->dist_code,
            'cons_code' => $request->cons_code,
            $key_name => 1,
        );
        $getdata = DB::table('pro_activity_pollday')->where('uid', $request->user_id)->where('bid', $request->bid)->first();
        if($getdata){
            $i = DB::table('pro_activity_pollday')->where('uid', $request->user_id)->where('bid', $request->bid)->update($datas);
            if($i>0){
                $return = array('result' => "Activity updated successfully",'status_code'=>200);
                exit(json_encode($return));
            }
            else{
                $return = array('result' => "Please try again!",'status_code'=>406);
                exit(json_encode($return));
            }
        }
        else{
            $i = DB::table('pro_activity_pollday')->insert($datas);
            if($i>0){
                $return = array('result' => "Activity updated successfully",'status_code'=>200);
                exit(json_encode($return));
            }
            else{
                $return = array('result' => "Please try again!",'status_code'=>406);
                exit(json_encode($return));
            }
        }
       
    }

    /*public function poll_after_activity(Request $request){
        $getdata = DB::table('pro_activity_after')->where('uid', $request->user_id)->where('bid', $request->bid)->first();
        if($getdata){
            $return_array['scrutiny'] = $getdata->election_material;
            $return_array['consolidated'] = $getdata->party_reached;
            $return_array['annexutre_41'] = $getdata->evm_received;
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
        else{
            $return_array['scrutiny'] = 0;
            $return_array['consolidated'] = 0;
            $return_array['annexutre_41'] = 0;
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
       
    }

    public function poll_after_activity_update(Request $request){
        $current_time = current_datetime();
        $datas = array(
            'uid' => $request->user_id,
            'dist_code' => $request->dist_code,
            'cons_code' => $request->cons_code,
            'bid' => $request->bid,
            'scrutiny' => $request->scrutiny,
            'consolidated' => $request->consolidated,
            'annexutre_41' => $request->annexutre_41,
            'updated_at' => $current_time,
        );
        $getdata = DB::table('pro_activity_after')->where('uid', $request->user_id)->where('bid', $request->bid)->first();
        if($getdata){
            echo "ss";
            die();
            $i = DB::table('pro_activity_after')->where('uid', $request->user_id)->where('bid', $request->bid)->update($datas);
            if($i==0){
                $return = array('result' => "Please try again!",'status_code'=>406);
                exit(json_encode($return));
            }
        }
        else{
            $i = DB::table('pro_activity_after')->insert($datas);
            if($i==0){
                $return = array('result' => "Please try again!",'status_code'=>406);
                exit(json_encode($return));
            }
        }
        $getupdateddata = DB::table('pro_activity_after')->where('uid', $request->user_id)->where('bid', $request->bid)->first();
        $return_array['scrutiny'] = $getupdateddata->scrutiny;
        $return_array['consolidated'] = $getupdateddata->consolidated;
        $return_array['annexutre_41'] = $getupdateddata->annexutre_41;
        $return = array('result' => $return_array,'status_code'=>200);
        exit(json_encode($return));
       
    }*/

    function communicationplans(Request $request){
        
    }

    
}