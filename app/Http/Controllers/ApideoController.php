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

class ApideoController extends Controller 
{

    /* List of RO along with dist code */

    public function rolist(Request $request)
    {
        $getRoConsts = DB::table('users')->join('constituencies', 'users.cons_code', '=', 'constituencies.cons_code')->where('users.dist_code',$request->dist_code)->where('users.role',4)->get();
        if($getRoConsts->count()) {
            foreach($getRoConsts as $getRoConst){
                $rolist['user_id'] = $getRoConst->uid;
                $rolist['name'] = $getRoConst->name;
                $rolist['cons_code'] = $getRoConst->cons_code;
                $rolist['constituency_name'] = $getRoConst->cons_name;
                $rolist['designation'] = $getRoConst->designation;
                $rolist['department'] = $getRoConst->organisation;
                $rolist['phone'] = $getRoConst->phone;
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

    public function rodetail(Request $request)
    {
        $getsupervisors = DB::table('users')->join('poll_booths', 'users.uid', '=', 'poll_booths.supervisior_uid')->where('users.cons_code',$request->cons_code)->where('role',5)->get();
        if($getsupervisors->count()) {
            foreach($getsupervisors as $getsupervisor){
                $rolist['user_id'] = $getsupervisor->uid;
                $rolist['name'] = $getsupervisor->name;
                $rolist['dist_code'] = $getsupervisor->dist_code;
                $rolist['cons_code'] = $getsupervisor->cons_code;
                $rolist['poll_building'] = $getsupervisor->poll_building.' '.$getsupervisor->poll_building_detail;
                $rolist['designation'] = $getsupervisor->designation;
                $rolist['department'] = $getsupervisor->organisation;
                $rolist['phone'] = $getsupervisor->phone;
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

    public function evmfirstrandomization(Request $request)
    {
        $first_evm_date = Config::get('constants.FIRST_RANDOMIZATION_EVM_DATE');
        $time_now=mktime(date('h')+5,date('i')+30,date('s'));
        $current_date = date('Y-m-d', $time_now);
        if (strtotime($first_evm_date) > strtotime($current_date)) {
            $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }
        else{
            
            $getfirstrandomisation = DB::table('randomization_evm_first')->join('constituencies', 'randomization_evm_first.cons_code','constituencies.cons_code')->where('randomization_evm_first.dist_code', $request->dist_code)->get();
            $return_cu = array();
            $return_bu = array();
            $return_vv = array();
            foreach($getfirstrandomisation as $getdata){

                if($getdata->unit_type == 'CONTROL'){
                    $culist['cons_name'] = $getdata->cons_name;
                    $culist['unit_id'] = $getdata->unit_id;
                    $culist['manufacturer'] = $getdata->manufacturer;
                    $return_cu[] = $culist;
                }
                elseif($getdata->unit_type == 'BALLOT'){
                    $bulist['cons_name'] = $getdata->cons_name;
                    $bulist['unit_id'] = $getdata->unit_id;
                    $bulist['manufacturer'] = $getdata->manufacturer;
                    $return_bu[] = $bulist;
                }
                elseif($getdata->unit_type == 'VVPAT'){
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
        if (strtotime($second_evm_date) > strtotime($current_date)) {
            $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }
        else{
            
            $getsecondrandomisation = DB::table('randomization_evm_second')->join('constituencies', 'randomization_evm_second.cons_code','constituencies.cons_code')->join('poll_booths', 'randomization_evm_second.bid','poll_booths.bid')->where('randomization_evm_second.dist_code', $request->dist_code)->get();
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

    public function observerlist(Request $request)
    {
        $getobservers = DB::table('observer')->where('dist_code',$request->dist_code)->get();
        if(!is_null($getobservers)) {
            foreach($getobservers as $getobserver){
                $obslist['uid'] = $getobserver->uid;
                $obslist['name'] = $getobserver->name;
                $obslist['email'] = $getobserver->email;
                $obslist['phone'] = $getobserver->phone;
                $obslist['address'] = $getobserver->address;
                $obslist['type'] = $getobserver->type;
                $obslist['profile_image'] = url('/').'/images/observer/'.$getobserver->profile_image;
                $return_array[] = $obslist;
            }
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
            
        }
        else{
            $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }
    }

    public function dashboard_deo_pollday(Request $request){
        $mallfunctions = DB::table('pro_evm_malfunctioning')
                      ->join('constituencies','pro_evm_malfunctioning.cons_code', 'constituencies.cons_code')
                      ->select('pro_evm_malfunctioning.cons_code', 'constituencies.cons_name', DB::raw('count(*) as total'))
                      ->where('pro_evm_malfunctioning.state_id', $request->state_id)
                      ->where('pro_evm_malfunctioning.dist_code', $request->dist_code)
                      ->where('pro_evm_malfunctioning.status', 0)
                      ->groupBy('pro_evm_malfunctioning.cons_code', 'constituencies.cons_name')
                      ->get();
        $mallfunctionscount = DB::table('pro_evm_malfunctioning')
                                ->where('dist_code', $request->dist_code)
                                ->where('state_id', $request->state_id)->count();
        if($mallfunctions->count()){
            $return_evm_status['tag'] = 1;
            foreach ($mallfunctions as $mallfunction) {
                $mallfunction_array['code'] = $mallfunction->cons_code;
                $mallfunction_array['name'] = $mallfunction->cons_name;
                $mallfunction_array['total'] = $mallfunction->total;
                $bind_mallfunction_array[] = $mallfunction_array;
            }
            $return_evm_status['count'] = $mallfunctionscount;
            $return_evm_status['data'] = $bind_mallfunction_array;
        }
        else{
            $return_evm_status['count'] = 0;
            $return_evm_status['tag'] = 0;
            $mallfunction_array['code'] = "";
            $mallfunction_array['name'] = "";
            $mallfunction_array['total'] = "";
            $return_evm_status['data'][] = $mallfunction_array;
        }

        $mallfunctions_resolve = DB::table('pro_evm_malfunctioning')
                      ->join('constituencies','pro_evm_malfunctioning.cons_code', 'constituencies.cons_code')
                      ->select('pro_evm_malfunctioning.cons_code', 'constituencies.cons_name', DB::raw('count(*) as total'))
                      ->where('pro_evm_malfunctioning.state_id', $request->state_id)
                      ->where('pro_evm_malfunctioning.dist_code', $request->dist_code)
                      ->where('pro_evm_malfunctioning.status', 0)
                      ->groupBy('pro_evm_malfunctioning.cons_code', 'constituencies.cons_name')
                      ->get();
        $mallfunctions_resolvecount = DB::table('pro_evm_malfunctioning')->where('state_id', $request->state_id)->where('dist_code', $request->dist_code)->where('status', 1)->count();
        if($mallfunctions_resolve->count()){
            $return_evm_rseolve_status['tag'] = 1;
            foreach ($mallfunctions_resolve as $mallfunction_resolve) {
                $mallfunction_rseolve_array['code'] = $mallfunction_resolve->cons_code;
                $mallfunction_rseolve_array['name'] = $mallfunction_resolve->cons_name;
                $mallfunction_rseolve_array['total'] = $mallfunction_resolve->total;
                $bind_mallfunction_rseolve_array[] = $mallfunction_rseolve_array;
            }
            $return_evm_rseolve_status['count'] = $mallfunctions_resolvecount;
            $return_evm_rseolve_status['data'] = $bind_mallfunction_rseolve_array;
        }
        else{
            $return_evm_rseolve_status['count'] = 0;
            $return_evm_rseolve_status['tag'] = 0;
            $mallfunction_rseolve_array['code'] = "";
            $mallfunction_rseolve_array['name'] = "";
            $mallfunction_rseolve_array['total'] = "";
            $return_evm_rseolve_status['data'][] = $mallfunction_rseolve_array;
        }

        $law_orders = DB::table('pro_law_order')
                      ->join('constituencies','pro_law_order.cons_code', 'constituencies.cons_code')
                      ->select('pro_law_order.cons_code', 'constituencies.cons_name', DB::raw('count(*) as total'))
                      ->where('pro_law_order.state_id', $request->state_id)
                      ->where('pro_law_order.dist_code', $request->dist_code)
                      ->where('pro_law_order.status', 0)
                      ->groupBy('pro_law_order.cons_code', 'constituencies.cons_name')
                      ->get();
        $pro_law_ordercount = DB::table('pro_law_order')
                                ->where('dist_code', $request->dist_code)
                                ->where('state_id', $request->state_id)->count();
        if($law_orders->count()){
            $return_law_status['tag'] = 1;
            foreach ($law_orders as $law_order) {
                $law_order_array['code'] = $law_order->cons_code;
                $law_order_array['name'] = $law_order->cons_name;
                $law_order_array['total'] = $law_order->total;
                $bind_law_array[] = $law_order_array;
            }
            $return_law_status['count'] = $pro_law_ordercount;
            $return_law_status['data'] = $bind_law_array;
        }
        else{
            $return_law_status['count'] = 0;
            $return_law_status['tag'] = 0;
            $law_order_array['code'] = "";
            $law_order_array['name'] = "";
            $law_order_array['total'] = "";
            $return_law_status['data'][] = $law_order_array;
        }

        $return_array['evm_mallfunction'] = $return_evm_status;
        $return_array['evm_mallfunction_resolve'] = $return_evm_rseolve_status;
        $return_array['law_order'] = $return_law_status;
        $return = array('result' => $return_array,'status_code'=>200);
        exit(json_encode($return));
    }

    public function dashboard_deo_pollday_activity(Request $request){
        $key_name = $request->key_name;
        $state_id = $request->state_id;
        $dist_code = $request->dist_code;
        $selected_key_value = DB::table('poll_booths')
            ->join('constituencies','poll_booths.cons_code', 'constituencies.cons_code')
            ->whereNotIn('bid', function($query) use($state_id, $dist_code, $key_name)
            {
                $query->select('bid')
                        ->from('pro_activity_pollday')
                        ->where('dist_code','=',$dist_code)
                        ->where('state_id','=',$state_id)
                        ->where($key_name, 'like', '%"comment_status":"no"%');
            })
            ->select('poll_booths.cons_code', 'constituencies.cons_name', DB::raw('count(*) as total'))
            ->groupBy('poll_booths.cons_code', 'constituencies.cons_name')
            ->where('poll_booths.dist_code','=',$dist_code)
            ->where('poll_booths.state_id','=',$state_id)
            ->get();

        if($selected_key_value->count()){
            foreach ($selected_key_value as $data) {
                $data_array['code'] = $data->cons_code;
                $data_array['name'] = $data->cons_name;
                $data_array['total'] = $data->total;
                $return_array[] = $data_array;
            }
            $return = array('result' => $return_array,'status_code'=>200);
        }
        else{
            $return = array('result' => 'No relavent data found','status_code'=>406);
        }
        exit(json_encode($return));
    }

    function getcommlist(){
            $data_array[0] = array('401','Nodal Office for Ballot Paper');
            $data_array[1] = array('402','Complaints');
            $data_array[2] = array('403','Communication');
            $data_array[3] = array('404','Computers');
            $data_array[4] = array('405','Daily Reports');
            $data_array[5] = array('406','EVMs');
            $data_array[6] = array('407','Expenditure');
            $data_array[7] = array('408','Liqure');
            $data_array[8] = array('409','Law & Order');
            $data_array[9] = array('410','Material');
            $data_array[10] = array('411','MCC');
            $data_array[11] = array('412','MCMC');
            $data_array[12] = array('413','Management');
            $data_array[13] = array('414','Manpower');
            $data_array[14] = array('415','Observer');
            $data_array[15] = array('416','Polling');
            $data_array[16] = array('417','SVEEP');
            $data_array[17] = array('418','Training');
            $data_array[17] = array('419','Transport');

            $return = array('result' => $data_array,'status_code'=>200);
            exit(json_encode($return));
    }

}