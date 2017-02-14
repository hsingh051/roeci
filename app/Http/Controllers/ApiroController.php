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

class ApiroController extends Controller 
{

    /* List of supervisor with respect to ro */

    public function supervisorlist(Request $request)
    {
        $dist_code = $request->dist_code;
        $cons_code = $request->cons_code;
        $supervisors = DB::table('users')->where('dist_code', $dist_code)->where('cons_code', $cons_code)->where('role', 5)->get();
        if($supervisors->count()) {
            foreach($supervisors as $supervisorlist){
                $supervisor['user_id'] = $supervisorlist->uid;
                $supervisor['name'] = $supervisorlist->name;
                $supervisor['address'] = $supervisorlist->address;
                $supervisor['phone'] = $supervisorlist->phone;
                $supervisor['designation'] = $supervisorlist->designation;
                $supervisor['department'] = $supervisorlist->organisation;
                $return_array[] = $supervisor;
            }
            $return = array('result' => $return_array,'status_code'=>200);
			exit(json_encode($return));
            
        }
        else{
            $return = array('result' => 'No relevant data found','status_code'=>406);
			exit(json_encode($return));
        }
    }

    /* List of polling station with respect to supervisor */

    public function sup_ps_list(Request $request)
    {
        $user_id = $request->user_id;
        $pollingstations = DB::table('poll_booths')->join('users', 'poll_booths.supervisior_uid','users.uid')->where('supervisior_uid', $user_id)->where('status', 1)->get();
        if($pollingstations->count()) {
            foreach($pollingstations as $pslist){
                $pollingstation['booth_id'] = $pslist->bid;
                $pollingstation['locality'] = $pslist->locality;
                $pollingstation['poll_type'] = $pslist->poll_type;
                $pollingstation['supervisor_name'] = $pslist->name;
                $pollingstation['supervisor_number'] = $pslist->phone;
                $pollingstation['poll_building'] = $pslist->poll_building.' '.$pslist->poll_building_detail;
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

    /* List of polling station with polling type to supervisor */

    public function polling_station_list(Request $request)
    {
        $pollingstations = DB::table('users')->join('poll_booths', 'users.uid', '=', 'poll_booths.supervisior_uid')->where('users.dist_code', $request->dist_code)->where('users.cons_code', $request->cons_code)->where('role', 5)->get();
        if($pollingstations->count()) 
        { 
            foreach($pollingstations as $pslist)
            {
                $pollingstation['booth_id'] = $pslist->bid;
                $pollingstation['name'] = $pslist->poll_building.' '.$pslist->poll_building_detail;
                $pollingstation['poll_areas'] = $pslist->poll_areas;
                $pollingstation['poll_type'] = $pslist->poll_type;
                $pollingstation['latitude'] = $pslist->latitude;
                $pollingstation['longitude'] = $pslist->longitude;
                //$pollingstation['poll_building'] = $pslist->poll_building;
                $return_array[] = $pollingstation;
            }
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
        else
        {
            $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }
    }

    public function evmfirstrandomization(Request $request)
    {
        $first_evm_date = Config::get('constants.FIRST_RANDOMIZATION_EVM_DATE');
        $time_now=mktime(date('h')+5,date('i')+30,date('s'));
        $current_date = date('Y-m-d', $time_now);
        if(strtotime($first_evm_date) > strtotime($current_date)) 
        {
            $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }
        else
        {    
            $getfirstrandomisation = DB::table('randomization_evm_first')->join('constituencies', 'randomization_evm_first.cons_code','constituencies.cons_code')->where('randomization_evm_first.cons_code', $request->cons_code)->get();
            $return_cu = array();
            $return_bu = array();
            $return_vv = array();
            foreach($getfirstrandomisation as $getdata)
            {
                if($getdata->unit_type == 'CONTROL')
                {
                    $culist['cons_name'] = $getdata->cons_name;
                    $culist['unit_id'] = $getdata->unit_id;
                    $culist['manufacturer'] = $getdata->manufacturer;
                    $return_cu[] = $culist;
                }
                elseif($getdata->unit_type == 'BALLOT')
                {
                    $bulist['cons_name'] = $getdata->cons_name;
                    $bulist['unit_id'] = $getdata->unit_id;
                    $bulist['manufacturer'] = $getdata->manufacturer;
                    $return_bu[] = $bulist;
                }
                elseif($getdata->unit_type == 'VVPAT')
                {
                    $vvlist['cons_name'] = $getdata->cons_name;
                    $vvlist['unit_id'] = $getdata->unit_id;
                    $vvlist['manufacturer'] = $getdata->manufacturer;
                    $return_vv[] = $vvlist;
                }
            }
            $return_array['CONTROL'] = $return_cu;
            $return_array['BALLOT'] = $return_bu;
            $return_array['VVPAT'] = $return_vv;

            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
    }

    public function evmsecondrandomization(Request $request)
    {
        $second_evm_date = Config::get('constants.SECOND_RANDOMIZATION_EVM_DATE');
        $time_now=mktime(date('h')+5,date('i')+30,date('s'));
        $current_date = date('Y-m-d', $time_now);
        if(strtotime($second_evm_date) > strtotime($current_date)) 
        {
            $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }
        else
        {
            $getsecondrandomisation = DB::table('randomization_evm_second')->join('constituencies', 'randomization_evm_second.cons_code','constituencies.cons_code')->join('poll_booths', 'randomization_evm_second.bid','poll_booths.bid')->where('randomization_evm_second.cons_code', $request->cons_code)->get();
            foreach($getsecondrandomisation as $getdata){
                $evmlist['cons_name'] = $getdata->cons_name;
                $evmlist['ps_name'] = $getdata->poll_building.' '.$getdata->poll_building_detail;
                $evmlist['cu'] = $getdata->cu;
                $evmlist['bu'] = $getdata->bu1;
                $evmlist['vvpat'] = $getdata->vvpat;
                $return_array[] = $evmlist;
            }

            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
    }

    public function staff_firstrandomization(Request $request)
    {
        $first_staf_date = Config::get('constants.FIRST_RANDOMIZATION_STAFF_DATE');
        $time_now=mktime(date('h')+5,date('i')+30,date('s'));
        $current_date = date('Y-m-d', $time_now);
        if (strtotime($first_staf_date) > strtotime($current_date)) {
            $return = array('result' => 'First randomization is due on '.$first_staf_date,'status_code'=>406);
            exit(json_encode($return));
        }
        else{
            $state_id = $request->state_id;
            $getfirstrandomisation = DB::table('users_pollday')->where('state_id', $state_id)->where('dist_code', $request->dist_code)->where('cons_code', $request->cons_code)->get();
            if(@$getfirstrandomisation){
                foreach($getfirstrandomisation as $getdata){

                    $list['ref_no'] = $getdata->ref_no;
                    $list['user_id'] = $getdata->uid;
                    $list['name'] = $getdata->name;
                    $list['phone'] = $getdata->phone;
                    $list['designation'] = $getdata->designation;
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

    public function staff_secondrandomization(Request $request)
    {
        $second_staf_date = Config::get('constants.SECOND_RANDOMIZATION_STAFF_DATE');
        $time_now=mktime(date('h')+5,date('i')+30,date('s'));
        $current_date = date('Y-m-d', $time_now);
        if (strtotime($second_staf_date) > strtotime($current_date)) {
            $return = array('result' => 'First randomization is due on '.$second_staf_date,'status_code'=>406);
            exit(json_encode($return));
        }
        else{
            $state_id = $request->state_id;
            $getfirstrandomisation = DB::table('users_pollday')->where('state_id', $state_id)->where('dist_code', $request->dist_code)->where('cons_code', $request->cons_code)->get();
            if($getfirstrandomisation->count()){
                foreach($getfirstrandomisation as $getdata){

                    $list['ref_no'] = $getdata->ref_no;
                    $list['user_id'] = $getdata->uid;
                    $list['name'] = $getdata->name;
                    $list['phone'] = $getdata->phone;
                    $list['designation'] = $getdata->designation;
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

    public function polling_staff(Request $request)
    {
        $third_staf_date = Config::get('constants.THIRD_RANDOMIZATION_STAFF_DATE');
        $time_now=mktime(date('h')+5,date('i')+30,date('s'));
        $current_date = date('Y-m-d', $time_now);
        if (strtotime($third_staf_date) > strtotime($current_date)) {
            $return = array('result' => 'Polling staff is due on '.$third_staf_date,'status_code'=>406);
            exit(json_encode($return));
        }
        else{
            $state_id = $request->state_id;
            $ps_list = DB::table('poll_booths')->join('users', 'poll_booths.supervisior_uid', 'users.uid')->where('poll_booths.state_id', $state_id)->where('poll_booths.dist_code', $request->dist_code)->where('poll_booths.cons_code', $request->cons_code)->get();
            if($ps_list->count()){
                foreach($ps_list as $getdata){

                    $list['bid'] = $getdata->bid;
                    $list['ps_name'] = $getdata->poll_building.' '.$getdata->poll_building_detail;
                    $list['supervisor_name'] = $getdata->name;
                    $list['supervisor_number'] = $getdata->phone;
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


    public function polling_staff_detail(Request $request){
        $bid = $request->bid;
        $getpspro = DB::table('randomization_staff_third')->join('users_pollday', 'randomization_staff_third.uid','users_pollday.uid')->where('randomization_staff_third.uid', 'like', 'PRO%')->where('bid',$bid)->first();
        if($getpspro){
            $getpsblo = DB::table('randomization_staff_third')->join('users_pollday', 'randomization_staff_third.uid','users_pollday.uid')->where('randomization_staff_third.uid', 'like', 'BLO%')->where('bid',$bid)->first();
            if(@$getpsblo){
                $return_array['BLO Name'] = $getpsblo->name;
                $return_array['BLO Phone'] = $getpsblo->phone;
            }
            $getpsapro = DB::table('randomization_staff_third')->join('users_pollday', 'randomization_staff_third.uid','users_pollday.uid')->where('randomization_staff_third.uid', 'like', 'APR%')->where('bid',$bid)->first();
            if(@$getpsapro){
                $return_array['APRO Name'] = $getpsapro->name;
                $return_array['APRO Phone'] = $getpsapro->phone;
            }
            $getpspoo = DB::table('randomization_staff_third')->join('users_pollday', 'randomization_staff_third.uid','users_pollday.uid')->where('randomization_staff_third.uid', 'like', 'POO%')->where('bid',$bid)->get();
            if($getpspoo->count()){
                foreach($getpspoo as $getdata){
                    $poolist['PO Name'] = $getdata->name;
                    $poolist['PO Phone'] = $getdata->phone;
                    $poo_array[] = $poolist;
                }
            }else{
                $poo_array = array();
            }
            $return_array['Party Number'] = $getpspro->party_no;
            $return_array['PRO Name'] = $getpspro->name;
            $return_array['PRO Phone'] = $getpspro->phone;
            $return_array['PO List'] = $poo_array;
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
        else{
            $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }
       
    }

    public function training_list(Request $request){
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
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
        else{
            $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }
    }

    public function dashboard_ro_pollday(Request $request){
        $mallfunctions = DB::table('poll_booths')
                      ->join('pro_evm_malfunctioning','poll_booths.bid', 'pro_evm_malfunctioning.bid')
                      ->join('users_pollday','pro_evm_malfunctioning.uid', 'users_pollday.uid')
                      ->where('pro_evm_malfunctioning.state_id', $request->state_id)
                      ->where('pro_evm_malfunctioning.dist_code', $request->dist_code)
                      ->where('pro_evm_malfunctioning.cons_code', $request->cons_code)
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
            $mallfunction_array['poll_building'] = "";
            $mallfunction_array['bid'] = "";
            $mallfunction_array['ps_id'] = "";
            $mallfunction_array['name'] = "";
            $return_evm_status['data'][] = $mallfunction_array;
        }

        $mallfunctions_resolve = DB::table('poll_booths')
                      ->join('pro_evm_malfunctioning','poll_booths.bid', 'pro_evm_malfunctioning.bid')
                      ->join('users_pollday','pro_evm_malfunctioning.uid', 'users_pollday.uid')
                      ->select('poll_booths.bid','poll_booths.poll_building','poll_booths.poll_building_detail','poll_booths.ps_id','users_pollday.name','pro_evm_malfunctioning.id')
                      ->where('pro_evm_malfunctioning.state_id', $request->state_id)
                      ->where('pro_evm_malfunctioning.dist_code', $request->dist_code)
                      ->where('pro_evm_malfunctioning.cons_code', $request->cons_code)
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
            $law_order_array['poll_building'] = "";
            $law_order_array['bid'] = "";
            $law_order_array['ps_id'] = "";
            $law_order_array['name'] = "";
            $return_law_status['data'][] = $law_order_array;
        }

        $return_array['evm_mallfunction'] = $return_evm_status;
        $return_array['evm_mallfunction_resolve'] = $resolve_mallfunction_array;
        $return_array['law_order'] = $return_law_status;
        $return = array('result' => $return_array,'status_code'=>200);
        exit(json_encode($return));
    }

    public function dashboard_mallfunction_detail(Request $request){
        $data = DB::table('pro_evm_malfunctioning')->join('users_pollday', 'pro_evm_malfunctioning.uid','users_pollday.uid')->join('poll_booths', 'pro_evm_malfunctioning.bid','poll_booths.bid')->where('pro_evm_malfunctioning.bid', $request->bid)->where('pro_evm_malfunctioning.status', 0)->first();
        if($data){
            $return_array['bid'] = $data->bid;
            $return_array['malfunctioning_from'] = $data->malfunctioning_from;
            $return_array['malfunctioning_to'] = $data->malfunctioning_to;
            $return_array['PRO'] = $data->name;
            $return_array['NUMBER'] = $data->phone;
            $return_array['bu'] = $data->bu;
            $return_array['cu'] = $data->cu;
            $return_array['ps_name'] = $data->poll_building.' '.$data->poll_building_detail;
            $return_array['comment'] = $data->comment;
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
        else{
            $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }
       
    }

    public function reolved_mallfunction_detail(Request $request){
        $data = DB::table('pro_evm_malfunctioning')
                ->join('users_pollday', 'pro_evm_malfunctioning.uid','users_pollday.uid')
                ->join('poll_booths', 'pro_evm_malfunctioning.bid','poll_booths.bid')
                ->join('users', 'poll_booths.supervisior_uid','users.uid')
                ->select('poll_booths.bid','pro_evm_malfunctioning.malfunctioning_from as malfunctioning_from','pro_evm_malfunctioning.malfunctioning_to as malfunctioning_to','users_pollday.name as PRO_name','users_pollday.phone as PRO_number','users.name as SUP_name','users.phone as SUP_number','pro_evm_malfunctioning.bu','pro_evm_malfunctioning.cu','pro_evm_malfunctioning.comment','pro_evm_malfunctioning.reply','poll_booths.poll_building','poll_booths.poll_building_detail')
                ->where('pro_evm_malfunctioning.id', $request->id)
                ->where('pro_evm_malfunctioning.status', 1)->first();
        if($data){
            $return_array['bid'] = $data->bid;
            $return_array['malfunctioning_from'] = $data->malfunctioning_from;
            $return_array['malfunctioning_to'] = $data->malfunctioning_to;
            $return_array['PRO'] = $data->PRO_name;
            $return_array['PRO_NUMBER'] = $data->PRO_number;
            $return_array['Supervisor'] = $data->SUP_name;
            $return_array['SUP_NUMBER'] = $data->SUP_number;
            $return_array['bu'] = $data->bu;
            $return_array['cu'] = $data->cu;
            $return_array['ps_name'] = $data->poll_building.' '.$data->poll_building_detail;
            $return_array['comment'] = $data->comment;
            $return_array['action'] = $data->reply;
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
        else{
            $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }
       
    }

    public function dashboard_laworder_detail(Request $request){
        $data = DB::table('pro_law_order')->join('users_pollday', 'pro_law_order.uid','users_pollday.uid')->join('poll_booths', 'pro_law_order.bid','poll_booths.bid')->where('pro_law_order.id', $request->id)->where('pro_law_order.status', 0)->first();
        if($data){
            $return_array['bid'] = $data->bid;
            $return_array['action_from'] = $data->action_from;
            $return_array['action_to'] = $data->action_to;
            $return_array['PRO'] = $data->name;
            $return_array['NUMBER'] = $data->phone;
            $return_array['ps_name'] = $data->poll_building.' '.$data->poll_building_detail;
            $return_array['comment'] = $data->comment;
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
        else{
            $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }
       
    }

    public function dashboard_ro_pollday_activity(Request $request){
        $key_name = $request->key_name;
        $state_id = $request->state_id;
        $dist_code = $request->dist_code;
        $cons_code = $request->cons_code;
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
                    if($booth_detail->$key_name != null){
                        $comment = json_decode($booth_detail->$key_name);
                        $data_array['comment'] = $comment->comment;
                        $data_array['activity_time'] = $comment->activity_time;
                    }
                    else{
                        $data_array['comment'] = 0;
                        $data_array['activity_time'] = 0;
                    }
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

    public function poll_nextday_report(Request $request){
        $consolidated = DB::table('ro_consolidated_report')
                ->where('state_id', $request->state_id)
                ->where('dist_code', $request->dist_code)
                ->where('cons_code', $request->cons_code)->first();
        if($consolidated){
            $return_array['consolidated'] = 'Yes';
        }
        else{
            $return_array['consolidated'] = 'No';
        }
        $scrutiny = DB::table('ro_report')
                ->where('state_id', $request->state_id)
                ->where('dist_code', $request->dist_code)
                ->where('cons_code', $request->cons_code)
                ->where('doc_type', 'SCRUTINY')->first();
        if($scrutiny){
            $return_array['scrutiny'] = 'Yes';
            $return_array['scrutiny_file'] = url('/').'/files/'.$scrutiny->doc_name;
        }
        else{
            $return_array['scrutiny'] = 'No';
            $return_array['scrutiny_file'] = "";
        }
        $return_array['state_id'] = $request->state_id;
        $return_array['dist_code'] = $request->dist_code;
        $return_array['cons_code'] = $request->cons_code;
        $return = array('result' => $return_array,'status_code'=>200);
        exit(json_encode($return));
       
    }

    public function consolidated_detail(Request $request){
        $consolidated = DB::table('ro_consolidated_report')
                ->where('state_id', $request->state_id)
                ->where('dist_code', $request->dist_code)
                ->where('cons_code', $request->cons_code)->first();
        if($consolidated){
            $return_array['interruption'] = $consolidated->interruption;
            $return_array['vitiation_evm_unlawfully'] = $consolidated->vitiation_evm_unlawfully;
            $return_array['votes_unlawfully'] = $consolidated->votes_unlawfully;
            $return_array['booth_capturing'] = $consolidated->booth_capturing;
            $return_array['serious_complaint'] = $consolidated->serious_complaint;
            $return_array['violence_law_order'] = $consolidated->violence_law_order;
            $return_array['mistake_irregularities'] = $consolidated->mistake_irregularities;
            $return_array['weather_conditions'] = $consolidated->weather_conditions;
            $return_array['poll_percentage'] = $consolidated->poll_percentage;
            $return_array['pre_scrutiny'] = $consolidated->pre_scrutiny;
            $return_array['recommendations_repoll'] = $consolidated->recommendations_repoll;
            $return_array['remarks'] = $consolidated->remarks;
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
        else{
            $return = array('result' => 'No relavent data found','status_code'=>406);
            exit(json_encode($return));
        }
    }

    public function add_voters_ballot(Request $request){
        $current_time = current_datetime();
        $state_id = $request->state_id;
        $dist_code = $request->dist_code;
        $cons_code = $request->cons_code;
        $army_voters_male = $request->army_voters_male;
        $army_voters_female = $request->army_voters_female;
        $edc_voters_male = $request->edc_voters_male;
        $edc_voters_female = $request->edc_voters_female;
        $datas = array(
                'state_id' => $state_id,
                'dist_code' => $dist_code,
                'cons_code' => $cons_code,
                'army_voters_male' => $army_voters_male,
                'army_voters_female' => $army_voters_female,
                'edc_voters_male' => $edc_voters_male,
                'edc_voters_female' => $edc_voters_female,
                'updated_at' => $current_time,
            );
        $check = DB::table('voters_ballot')
                ->where('state_id', $state_id)
                ->where('dist_code', $dist_code)
                ->where('cons_code', $cons_code)->first();
        if($check){
            $update = DB::table('voters_ballot')
                        ->where('state_id', $state_id)
                        ->where('dist_code', $dist_code)
                        ->where('cons_code', $cons_code)
                        ->update($datas);
            if($update>0){
                $return = array('result' => 'Succefully Updated','status_code'=>200);
            }
            else{
                $return = array('result' => 'Please try after some time','status_code'=>406);
            }
        }
        else{
            $insert = DB::table('voters_ballot')->insert($datas);
            if($insert>0){
                $return = array('result' => 'Succefully Updated','status_code'=>200);
            }
            else{
                $return = array('result' => 'Please try after some time','status_code'=>406);
            }
        }
        exit(json_encode($return));
    }

    public function view_voters_ballot(Request $request){
        $state_id = $request->state_id;
        $dist_code = $request->dist_code;
        $cons_code = $request->cons_code;
        $data = DB::table('voters_ballot')
                ->join('constituencies', 'voters_ballot.cons_code','constituencies.cons_code')
                ->where('voters_ballot.state_id', $state_id)
                ->where('voters_ballot.dist_code', $dist_code)
                ->where('voters_ballot.cons_code', $cons_code)->first();
        if($data){
            $army_voters_male = $data->army_voters_male;
            $army_voters_female = $data->army_voters_female;
            $edc_voters_male = $data->edc_voters_male;
            $edc_voters_female = $data->edc_voters_female;
            $total_army_voters = $army_voters_male + $army_voters_female;
            $total_edc_voters = $edc_voters_male + $edc_voters_female;
            $total_female_voters = $edc_voters_female + $army_voters_female;
            $total_male_voters = $army_voters_male + $edc_voters_male;
            $total_ballot_voters = $total_army_voters + $total_edc_voters;
            $return_array['state_id'] = $data->state_id;
            $return_array['dist_code'] = $data->dist_code;
            $return_array['cons_code'] = $data->cons_code;
            $return_array['army_voters_male'] = $data->army_voters_male;
            $return_array['army_voters_female'] = $data->army_voters_female;
            $return_array['total_army_voters'] = $total_army_voters;
            $return_array['edc_voters_male'] = $data->edc_voters_male;
            $return_array['edc_voters_female'] = $data->edc_voters_female;
            $return_array['total_edc_voters'] = $total_edc_voters;
            $return_array['total_female_voters'] = $total_female_voters;
            $return_array['total_male_voters'] = $total_male_voters;
            $return_array['total_ballot_voters'] = $total_ballot_voters;
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
        else{
            $return = array('result' => 'No relavent data found','status_code'=>406);
            exit(json_encode($return));
        }
    }

    public function pwd_request_list(Request $request){
        $request_lists = DB::table('ro_pwd_request')
                ->where('state_id', $request->state_id)
                ->where('dist_code', $request->dist_code)
                ->where('cons_code', $request->cons_code)->get();
        if($request_lists->count()){
            foreach($request_lists as $request_list){
                $list['pwd_id'] = $request_list->pwd_id;
                $list['epic_no'] = $request_list->epic_no;
                $return_array[] = $list;
            }
            $return = array('result' => $return_array,'status_code'=>200);
        }
        else{
            $return = array('result' => 'No relavent data found','status_code'=>406);
        }
        exit(json_encode($return));
    }

    public function pwd_request_detail(Request $request){
        $request_detail = DB::table('ro_pwd_request')
                ->where('pwd_id', $request->pwd_id)->first();
        if($request_detail){
            $json_data = get_pwd_data($request_detail->epic_no);
            $data = json_decode($json_data);
            $cons_code = $data->ac_no;
            $ps_id = $data->part_no;
            $getpsdata = DB::table('poll_booths')
                            ->where('state_id', 53)
                            ->where('dist_code', 11)
                            ->where('cons_code', $cons_code)
                            ->where('ps_id', $ps_id)->first();
            $return_array['name'] = $data->Fm_NameEn.' '.$data->LastNameEn;
            $return_array['phone'] = $request_detail->pwd_phone;
            $return_array['address'] = $request_detail->pwd_address;
            $return_array['epic_no'] = $request_detail->epic_no;
            $return_array['disability_type'] = $request_detail->type;
            $return_array['ps_name'] = $getpsdata->poll_building.' '.$getpsdata->poll_building_detail;
            $return_array['ps_area'] = $getpsdata->poll_areas;
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
        else{
            $return = array('result' => 'No relavent data found','status_code'=>406);
            exit(json_encode($return));
        }
    }

    public function nominationReceived(Request $request){

        $getNominations = DB::table('users')
                         ->join('users_candidate_data', 'users.uid', '=', 'users_candidate_data.uid')
                         ->where('users.role', '15')
                         ->where('users.state_id', $request->state_id);
                         //->where('users.dist_code', $request->dist_code)
                         //->where('users.cons_code', $request->cons_code)
        if(@$request->dist_code){
           $getNominations = $getNominations->where('users.dist_code',  $request->dist_code);
        }

        if(@$request->cons_code){
           $getNominations = $getNominations->where('users.cons_code',  $request->cons_code);
        }

        if(@$request->fromdate){
           $getNominations = $getNominations->where('users_candidate_data.nominationDate', '>=', date("Y-m-d",strtotime($request->fromdate)));
        }
        if(@$request->todate){
           $getNominations = $getNominations->where('users_candidate_data.nominationDate', '<=', date("Y-m-d",strtotime($request->todate)));
        }
        $getNomination =  $getNominations->get();
        
                
        if($getNomination->count()){
            foreach($getNomination as $data){
                $candidate['uid'] = $data->uid;
                $candidate['name'] = $data->name;
                $candidate['party'] = $data->cand_party;
                $candidate['image'] = URL('/')."/images/candidate/profilePicture/".$data->profile_pic;
                $return_array[] = $candidate;
            }
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
        else{
            $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }
    }

    public function nominationRejected(Request $request){

        $getNominations = DB::table('users')
                         ->join('users_candidate_data', 'users.uid', '=', 'users_candidate_data.uid')
                         ->where('users.role', '15')
                         ->where('users_candidate_data.nominationStatus', 'R')
                         ->where('users.state_id', $request->state_id);
                         //->where('users.dist_code', $request->dist_code)
                         //->where('users.cons_code', $request->cons_code)
        if(@$request->date){
           $getNominations = $getNominations->where('users_candidate_data.nominationDate', 'like', '%' . date("Y-m-d",strtotime($request->date)) . '%');
        }
        $getNomination =  $getNominations->get();
        
                
        if($getNomination->count()){
            foreach($getNomination as $data){
                $candidate['uid'] = $data->uid;
                $candidate['name'] = $data->name;
                $candidate['party'] = $data->cand_party;
                $candidate['image'] = URL('/')."/images/candidate/profilePicture/".$data->profile_pic;
                $return_array[] = $candidate;
            }
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
        else{
            $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }
    }
    
    public function nominationWithdrawls(Request $request){

        $getNominations = DB::table('users')
                         ->join('users_candidate_data', 'users.uid', '=', 'users_candidate_data.uid')
                         ->where('users.role', '15')
                         ->where('users_candidate_data.nominationStatus', 'W')
                         ->where('users.state_id', $request->state_id);
                         //->where('users.dist_code', $request->dist_code)
                         //->where('users.cons_code', $request->cons_code)
        if(@$request->date){
           $getNominations = $getNominations->where('users_candidate_data.nominationDate', 'like', '%' . date("Y-m-d",strtotime($request->date)) . '%');
        }
        $getNomination =  $getNominations->get();
        
                
        if($getNomination->count()){
            foreach($getNomination as $data){
                $candidate['uid'] = $data->uid;
                $candidate['name'] = $data->name;
                $candidate['party'] = $data->cand_party;
                $candidate['image'] = URL('/')."/images/candidate/profilePicture/".$data->profile_pic;
                $return_array[] = $candidate;
            }
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
        else{
            $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }
    }


    public function nominationFinallist(Request $request){

        $getNominations = DB::table('users')
                         ->join('users_candidate_data', 'users.uid', '=', 'users_candidate_data.uid')
                         ->where('users.role', '15')
                         ->where('users_candidate_data.nominationStatus', 'N')
                         ->where('users.state_id', $request->state_id);
                         //->where('users.dist_code', $request->dist_code)
                         //->where('users.cons_code', $request->cons_code)
        if(@$request->date){
           $getNominations = $getNominations->where('users_candidate_data.nominationDate', 'like', '%' . date("Y-m-d",strtotime($request->date)) . '%');
        }
        $getNomination =  $getNominations->get();
        
                
        if($getNomination->count()){
            foreach($getNomination as $data){
                $candidate['uid'] = $data->uid;
                $candidate['name'] = $data->name;
                $candidate['party'] = $data->cand_party;
                $candidate['image'] = URL('/')."/images/candidate/profilePicture/".$data->profile_pic;
                $return_array[] = $candidate;
            }
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
        else{
            $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }
    }

    public function candidateDetail(Request $request)
    {
        $user = Auth::user();
        $candidateDetail = DB::table('users')->join('users_candidate_data', 'users.uid', '=', 'users_candidate_data.uid')->leftJoin('symbols', 'users_candidate_data.cand_symbol', '=', 'symbols.symbol_no')->where('users.uid',$request->uid)->first();

        if($candidateDetail){
            
            //$data = json_decode($candidateDetail);
            // echo "<pre>";
            // print_r($candidateDetail);
            // echo "</pre>";
            
            $return_array['name'] = $candidateDetail->name;
            $return_array['father_name'] = $candidateDetail->guardian_name;
            $return_array['phone'] = $candidateDetail->phone;
            $return_array['address'] = $candidateDetail->address;
            $return_array['epicno'] = $candidateDetail->cand_epicno;
            if(@$candidateDetail->profile_pic){
                $return_array['profile_pic'] = URL('/').'/candidate/profilePicture/'.$candidateDetail->profile_pic;
            }else{
                $return_array['profile_pic'] = "NULL";   
            }
            
            if(@$candidateDetail->symbol_pic){
                $return_array['symbol_pic'] = URL('/').'/'.$candidateDetail->symbol_pic;
            }else{
                $return_array['symbol_pic'] = "NULL";    
            }
            $return_array['symbol_name'] = $candidateDetail->symbol_des;
            $return_array['party_name'] = $candidateDetail->cand_party;
            $return_array['age'] = $candidateDetail->cand_age;
            $return_array['gender'] = $candidateDetail->cand_sex;
            $return_array['nominationDate'] = date("Y-m-d H:i:s",strtotime($candidateDetail->nominationDate));
            $return_array['nominationstatus'] = $candidateDetail->nominationStatus;

            if($return_array['nominationstatus'] == "R"){
                 $return_array['rejectionReason'] = $candidateDetail->rejectionReason; 
                 $return_array['rejection_date'] = date("Y-m-d H:i:s",strtotime($candidateDetail->rejection_date));    
            }elseif($return_array['nominationstatus'] == "W"){
                 $return_array['withdraw_refno'] = $candidateDetail->withdraw_refno; 
                 $return_array['withdrawal_date'] = date("Y-m-d H:i:s",strtotime($candidateDetail->withdrawal_date));    
            }
            
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
        else{
            $return = array('result' => 'No relavent data found','status_code'=>406);
            exit(json_encode($return));
        }
    }


    function get_complaints(Request $request){

        $complaints = get_complaints($request->state_id, $request->dist_code, $request->cons_code);
        return $complaints;

    }

}