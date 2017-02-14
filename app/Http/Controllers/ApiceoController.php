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

class ApiceoController extends Controller 
{

    /* List of district along with dist code */

    public function districtlist(Request $request)
    {
        $state_id = $request->state_id;
        $districts = DB::table('districts')
                    ->where('state_id', $state_id)
                    ->where('status', 1)->get();
        if($districts->count()) {
            foreach($districts as $districtlist){
                $district['dist_code'] = $districtlist->dist_code;
                $district['dist_name'] = $districtlist->dist_name;
                $return_array[] = $district;
            }
            $return = array('result' => $return_array,'status_code'=>200);
			exit(json_encode($return));
            
        }
        else{
            $return = array('result' => 'No relevant data found','status_code'=>406);
			exit(json_encode($return));
        }
    }

     public function deolist(Request $request)
    {
        $getdeolist = DB::table('users')->join('districts', 'users.dist_code', '=', 'districts.dist_code')->where('users.role',3)->get();
        if($getdeolist->count()) {
            foreach($getdeolist as $getdeo){
                $deolist['user_id'] = $getdeo->uid;
                $deolist['name'] = $getdeo->name;
                $deolist['dist_code'] = $getdeo->dist_code;
                $deolist['dist_name'] = $getdeo->dist_name;
                $rolist['designation'] = $getdeo->designation;
                $rolist['department'] = $getdeo->organisation;
                $deolist['phone'] = $getdeo->phone;
                $return_array[] = $deolist;
            }
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
            
        }
        else{
            $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }
    }


    public function observerlist(Request $request){

       $observers = DB::table('observer')
                   ->join('states','states.StateID','=','observer.state_id')
                   ->join('districts','districts.dist_code','=','observer.dist_code')
                   ->where('observer.state_id', get_state_id())
                   ->where('observer.dist_code', $request->dist_code)
                   ->get();
       
        if($observers->count()){
            foreach($observers as $observer){
                $dt = array();
                $dt['uid'] = $observer->uid;
                $dt['name'] = $observer->name;
                $dt['email'] = $observer->email;
                $dt['phone'] = $observer->phone;
                $dt['address'] = $observer->address;
                $dt['type'] = $observer->type;
                $dt['state_name'] = $observer->StateName;
                $dt['district_name'] = $observer->dist_name;
                $dt['profile_image'] = url('/').'/images/observer/'.$observer->profile_image;
                $return_array[] = $dt;
            }
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }else{
            $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }       
        
    }

     public function polling_station_list(Request $request)
    {
        $pollingst = DB::table('poll_booths')->where('poll_booths.state_id',  get_state_id());
        if(@$request->dist_code){            
            $pollingst->where('poll_booths.dist_code', $request->dist_code);
            if(@$request->cons_code){
                $pollingst->where('poll_booths.cons_code', $request->cons_code);
            }
            if(@$request->poll_type){
                $pollingst->where('poll_booths.poll_type', $request->poll_type);
            }
        }
        
        $pollingstations = $pollingst->get();
        
        if($pollingstations->count()) { 
            foreach($pollingstations as $pslist){
                $pollingstation['booth_id'] = $pslist->bid;
                $pollingstation['name'] = $pslist->poll_building.' '.$pslist->poll_building_detail;
                $pollingstation['poll_areas'] = $pslist->poll_areas;
                $pollingstation['poll_type'] = $pslist->poll_type;
                $pollingstation['latitude'] = $pslist->latitude;
                $pollingstation['longitude'] = $pslist->longitude;
                $pollingstation['ps_image'] = url('/')."/images/poll_booths/".str_pad(get_state_id(), 3, '0', STR_PAD_LEFT).'/'.str_pad($pslist->dist_code, 3, '0', STR_PAD_LEFT).'/AC'.str_pad($pslist->cons_code, 3, '0', STR_PAD_LEFT).'/'.str_pad($pslist->ps_id, 3, '0', STR_PAD_LEFT).'.jpg';
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

    public function ps_awareness_group(Request $request)
    {
        $ps_awareness_group = DB::table('booth_awareness_groups')
                            ->where('booth_awareness_groups.state_id', get_state_id())
                            ->where('booth_awareness_groups.bid', $request->booth_id)
                            ->get();
        if($ps_awareness_group->count()) { 
            foreach($ps_awareness_group as $pslist){
                $group['name'] = $pslist->name;
                $group['phone'] = $pslist->phone;
                $group['designation'] = $pslist->designation;
                $group['organisation'] = $pslist->organisation;
                $group['address'] = $pslist->address;
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


    public function pollbeforeday(Request $request){
        $polling_stations = DB::table('poll_booths')
                            ->leftjoin('pro_activity_before', 'poll_booths.bid', '=', 'pro_activity_before.bid')
                            ->where('poll_booths.state_id', get_state_id())
                            ->where('poll_booths.dist_code', $request->dist_code)
                            ->where('poll_booths.cons_code', $request->cons_code)
                            ->select('poll_booths.bid','poll_booths.poll_building','pro_activity_before.election_material','pro_activity_before.party_reached','pro_activity_before.evm_received','pro_activity_before.eroll_list')
                            ->get();
        if($polling_stations->count()) { 
            foreach($polling_stations as $pslist){
                $group['booth_id'] = $pslist->bid;
                $group['name'] = $pslist->poll_building.' '.$pslist->poll_building_detail;
                $group['election_material'] = $pslist->election_material;
                $group['party_reached'] = $pslist->party_reached;
                $group['evm_received'] = $pslist->evm_received;
                $group['eroll_list'] = $pslist->eroll_list;
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

    public function dashboard_ceo_pollday(Request $request){
        $mallfunctions = DB::table('pro_evm_malfunctioning')
                      ->join('districts','pro_evm_malfunctioning.dist_code', 'districts.dist_code')
                      ->select('pro_evm_malfunctioning.dist_code', 'districts.dist_name', DB::raw('count(*) as total'))
                      ->where('pro_evm_malfunctioning.state_id', $request->state_id)
                      ->where('pro_evm_malfunctioning.status', 0)
                      ->groupBy('pro_evm_malfunctioning.dist_code', 'districts.dist_name')
                      ->get();
        $mallfunctionscount = DB::table('pro_evm_malfunctioning')->where('state_id', $request->state_id)->where('status', 0)->count();
        if($mallfunctions->count()){
            $return_evm_status['tag'] = 1;
            foreach ($mallfunctions as $mallfunction) {
                $mallfunction_array['code'] = $mallfunction->dist_code;
                $mallfunction_array['name'] = $mallfunction->dist_name;
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
                      ->join('districts','pro_evm_malfunctioning.dist_code', 'districts.dist_code')
                      ->select('pro_evm_malfunctioning.dist_code', 'districts.dist_name', DB::raw('count(*) as total'))
                      ->where('pro_evm_malfunctioning.state_id', $request->state_id)
                      ->where('pro_evm_malfunctioning.status', 1)
                      ->groupBy('pro_evm_malfunctioning.dist_code', 'districts.dist_name')
                      ->get();
        $mallfunctions_resolvecount = DB::table('pro_evm_malfunctioning')->where('state_id', $request->state_id)->where('status', 1)->count();
        if($mallfunctions_resolve->count()){
            $return_evm_rseolve_status['tag'] = 1;
            foreach ($mallfunctions_resolve as $mallfunction_resolve) {
                $mallfunction_rseolve_array['code'] = $mallfunction_resolve->dist_code;
                $mallfunction_rseolve_array['name'] = $mallfunction_resolve->dist_name;
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
                      ->join('districts','pro_law_order.dist_code', 'districts.dist_code')
                      ->select('pro_law_order.dist_code', 'districts.dist_name', DB::raw('count(*) as total'))
                      ->where('pro_law_order.state_id', $request->state_id)
                      ->where('pro_law_order.status', 0)
                      ->groupBy('pro_law_order.dist_code', 'districts.dist_name')
                      ->get();
        $pro_law_ordercount = DB::table('pro_law_order')->where('state_id', $request->state_id)->count();
        if($law_orders->count()){
            $return_law_status['tag'] = 1;
            foreach ($law_orders as $law_order) {
                $law_order_array['code'] = $law_order->dist_code;
                $law_order_array['name'] = $law_order->dist_name;
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

    public function dashboard_ceo_pollday_activity(Request $request){
        $key_name = $request->key_name;
        $state_id = $request->state_id;
        $selected_key_value = DB::table('poll_booths')
            ->join('districts','poll_booths.dist_code', 'districts.dist_code')
            ->whereNotIn('bid', function($query) use($state_id, $key_name)
            {
                $query->select('bid')
                      ->from('pro_activity_pollday')
                      ->where('state_id','=',$state_id)
                     ->where($key_name, 'like', '%"comment_status":"no"%');
            })
            ->select('poll_booths.dist_code', 'districts.dist_name', DB::raw('count(*) as total'))
            ->groupBy('poll_booths.dist_code', 'districts.dist_name')
            ->where('poll_booths.state_id','=',$state_id)
            ->get();

        if($selected_key_value->count()){
            foreach ($selected_key_value as $data) {
                $data_array['code'] = $data->dist_code;
                $data_array['name'] = $data->dist_name;
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

            $data_array[0] = array('id'=>'401','label'=>'Nodal Office for Ballot Paper');
            $data_array[1] = array('id'=>'402','label'=>'Complaints');
            $data_array[2] = array('id'=>'403','label'=>'Communication');
            $data_array[3] = array('id'=>'404','label'=>'Computers');
            $data_array[4] = array('id'=>'405','label'=>'Daily Reports');
            $data_array[5] = array('id'=>'406','label'=>'EVMs');
            $data_array[6] = array('id'=>'407','label'=>'Expenditure');
            $data_array[7] = array('id'=>'408','label'=>'Liqure');
            $data_array[8] = array('id'=>'409','label'=>'Law & Order');
            $data_array[9] = array('id'=>'410','label'=>'Material');
            $data_array[10] = array('id'=>'411','label'=>'MCC');
            $data_array[11] = array('id'=>'412','label'=>'MCMC');
            $data_array[12] = array('id'=>'413','label'=>'Management');
            $data_array[13] = array('id'=>'414','label'=>'Manpower');
            $data_array[14] = array('id'=>'415','label'=>'Observer');
            $data_array[15] = array('id'=>'416','label'=>'Polling');
            $data_array[16] = array('id'=>'417','label'=>'SVEEP');
            $data_array[17] = array('id'=>'418','label'=>'Training');
            $data_array[17] = array('id'=>'419','label'=>'Transport');

            $return = array('result' => $data_array,'status_code'=>200);

            exit(json_encode($return));
    }

    function getcommdetails(Request $request){

        $details = getcommdetails($request->id,$request->no);
        if(@$details){
            $return = array('result' => $details,'status_code'=>200);
        }else{
            $return = array('result' => 'No relavent data found','status_code'=>406);
        }
        exit(json_encode($return));
        
    }

}