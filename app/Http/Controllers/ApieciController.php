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

class ApieciController extends Controller 
{

    /* List of district along with dist code */

    public function ceolist()
    {
        $ceolist = DB::table('users')
                   ->join('states','states.StateID','=','users.state_id')
                   ->where('role', '2')
                   ->get();
        if($ceolist->count()){
            foreach($ceolist as $ceo){
                $ceoa = array();
                $ceoa['name'] = $ceo->name;
                $ceoa['state'] = $ceo->StateName;
                $ceoa['phone'] = $ceo->phone;
                $return_array[] = $ceoa;
            }
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }else{
            $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }       
        
    }


    public function statelist(){
       
        $states = DB::table('states')
                   ->orderby('StateName')
                   ->get();
        if($states->count()){
            foreach($states as $state){
                $st = array();
                $st['state_id'] = $state->StateID;
                $st['state_name'] = $state->StateName;
                $return_array[] = $st;
            }
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }else{
            $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }       
        
    }

    public function districtlist(Request $request){
       
        $districts = DB::table('districts')->where('state_id', $request->state_id)->get();
        if($districts->count()){
            foreach($districts as $district){
                $dt = array();
                $dt['dist_code'] = $district->dist_code;
                $dt['dist_name'] = $district->dist_name;
                $return_array[] = $dt;
            }
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }else{
            $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }       
        
    }

    public function observerlist(Request $request){

       $observers = DB::table('observer')
                   ->join('states','states.StateID','=','observer.state_id')
                   ->join('districts','districts.dist_code','=','observer.dist_code')
                   ->where('observer.state_id', $request->state_id)
                   ->where('observer.dist_code', $request->dist_code)
                   ->where('districts.state_id', $request->state_id)
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


    public function constituencieslist(Request $request){
       
        $constituencies = DB::table('constituencies')
                   ->where('state_id', $request->state_id)
                   ->where('dist_code', $request->dist_code)
                   ->orderby('cons_code')
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

    public function polling_station_list(Request $request)
    {
        $state_id = $request->state_id;
        $pollingstations = DB::table('poll_booths')
                            ->where('poll_booths.state_id', $state_id)
                            ->where('poll_booths.dist_code', $request->dist_code)
                            ->where('poll_booths.cons_code', $request->cons_code)
                            ->get();
        
        if($pollingstations->count()) { 
            foreach($pollingstations as $pslist){

                //dd($poll_booths);
                $getpspro = DB::table('randomization_staff_third')
                            ->join('users_pollday', 'randomization_staff_third.uid','users_pollday.uid')
                            ->where('randomization_staff_third.uid', 'like', 'PRO%')
                            ->where('bid',$pslist->bid)
                            ->first();
                if(@$getpspro){
                    $pollingstation['pro_name'] = $getpspro->name;
                    $pollingstation['pro_phone'] = $getpspro->phone;
                }
                else{
                    $pollingstation['pro_name'] = 'NA';
                    $pollingstation['pro_phone'] = 'NA';
                }
                $getpsblo = DB::table('randomization_staff_third')
                            ->join('users_pollday', 'randomization_staff_third.uid','users_pollday.uid')
                            ->where('randomization_staff_third.uid', 'like', 'BLO%')
                            ->where('bid',$pslist->bid)
                            ->first();
                if(@$getpsblo){
                    $pollingstation['blo_name'] = $getpsblo->name;
                    $pollingstation['blo_phone'] = $getpsblo->phone;
                }
                else{
                    $pollingstation['blo_name'] = 'NA';
                    $pollingstation['blo_phone'] = 'NA';
                }
                $getpsapro = DB::table('randomization_staff_third')
                             ->join('users_pollday', 'randomization_staff_third.uid','users_pollday.uid')
                             ->where('randomization_staff_third.uid', 'like', 'APR%')
                             ->where('bid',$pslist->bid)
                             ->first();
                if(@$getpsapro){
                    $pollingstation['apro_name'] = $getpsapro->name;
                    $pollingstation['apro_phone'] = $getpsapro->phone;
                }
                else{
                    $pollingstation['apro_name'] = 'NA';
                    $pollingstation['apro_phone'] = 'NA';
                }
                $getpspoo = DB::table('randomization_staff_third')->join('users_pollday', 'randomization_staff_third.uid','users_pollday.uid')->where('randomization_staff_third.uid', 'like', 'POO%')->where('bid',$pslist->bid)->get();
                $poo_array = array();
                foreach($getpspoo as $getdata){
                    $poolist['name'] = $getdata->name;
                    $poolist['phone'] = $getdata->phone;
                    $poolist['designation'] = $getdata->designation;
                       $poo_array[] = $poolist;
                   }
                //if(count($poo_array)){
                    $pollingstation['poo_list'] = $poo_array;
                    
                //}
                $pollingstation['ps_id'] = $pslist->ps_id;
                $pollingstation['booth_id'] = $pslist->bid;
                $pollingstation['name'] = $pslist->poll_building.' '.$pslist->poll_building_detail;
                $pollingstation['poll_areas'] = $pslist->poll_areas;
                $pollingstation['poll_type'] = $pslist->poll_type;
                $pollingstation['latitude'] = $pslist->latitude;
                $pollingstation['longitude'] = $pslist->longitude;
                $pollingstation['ps_image'] = url('/')."/images/poll_booths/".str_pad($state_id, 3, '0', STR_PAD_LEFT).'/'.str_pad($pslist->dist_code, 3, '0', STR_PAD_LEFT).'/AC'.str_pad($pslist->cons_code, 3, '0', STR_PAD_LEFT).'/'.str_pad($pslist->ps_id, 3, '0', STR_PAD_LEFT).'.jpg';
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

    public function polling_station_list_type(Request $request)
    {
        $state_id = $request->state_id;
        $pollingstations = DB::table('poll_booths')
                            ->where('poll_booths.poll_type', $request->poll_type)
                            ->where('poll_booths.state_id', $state_id)
                            ->where('poll_booths.dist_code', $request->dist_code)
                            ->where('poll_booths.cons_code', $request->cons_code)
                            ->get();
        
        if($pollingstations->count()) { 
            foreach($pollingstations as $pslist){
                $pollingstation['ps_id'] = $pslist->ps_id;
                $pollingstation['booth_id'] = $pslist->bid;
                $pollingstation['name'] = $pslist->poll_building.' '.$pslist->poll_building_detail;
                $pollingstation['poll_areas'] = $pslist->poll_areas;
                $pollingstation['poll_type'] = $pslist->poll_type;
                $pollingstation['latitude'] = $pslist->latitude;
                $pollingstation['longitude'] = $pslist->longitude;
                $pollingstation['ps_image'] = url('/')."/images/poll_booths/".str_pad($state_id, 3, '0', STR_PAD_LEFT).'/'.str_pad($pslist->dist_code, 3, '0', STR_PAD_LEFT).'/AC'.str_pad($pslist->cons_code, 3, '0', STR_PAD_LEFT).'/'.str_pad($pslist->ps_id, 3, '0', STR_PAD_LEFT).'.jpg';
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
                            ->where('booth_awareness_groups.state_id', $request->state_id)
                            ->where('booth_awareness_groups.bid', $request->bid)
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
                            ->where('poll_booths.state_id', $request->state_id)
                            ->where('poll_booths.dist_code', $request->dist_code)
                            ->where('poll_booths.cons_code', $request->cons_code)
                            ->select('poll_booths.bid','poll_booths.poll_building','poll_booths.poll_building_detail','pro_activity_before.election_material','pro_activity_before.party_reached','pro_activity_before.evm_received')
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

    public function polling_percentage(Request $request){
        $current_time = current_hour();
        $polling_stations = DB::table('poll_booths')
                            ->where('dist_code', $request->dist_code)
                            ->where('cons_code', $request->cons_code)
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

    public function polling_station_percentage(Request $request){
        $polling_stations = DB::table('pro_polling_percentage')
                            ->where('dist_code', $request->dist_code)
                            ->where('cons_code', $request->cons_code)
                            ->where('bid', $request->bid)
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

    public function electoral_epic(Request $request){
        $getdata = DB::table('voters')->join('states', 'voters.state_id', 'states.StateID')->join('districts', 'voters.dist_code', 'districts.dist_code')->join('constituencies', 'voters.cons_code', 'constituencies.cons_code')->where('idcardNo',$request->voter_number)->first();
        if($getdata){
            $bid = str_pad($getdata->dist_code, 2, '0', STR_PAD_LEFT).str_pad($getdata->cons_code, 3, '0', STR_PAD_LEFT).str_pad($getdata->ps_id, 3, '0', STR_PAD_LEFT);
            $getps = DB::table('poll_booths')->where('bid',$bid)->first();
            $return_array['Serial No'] = $getdata->slnoinpart;
            $return_array['idcardNo'] = $getdata->idcardNo;
            $return_array['firstName'] = $getdata->fm_nameEn;
            $return_array['LastName'] = $getdata->LastNameEn;
            $return_array['gender'] = $getdata->sex;
            $return_array['relation_type'] = $getdata->rlnType;
            $return_array['relation_first_name'] = $getdata->rln_Fm_NmEn;
            $return_array['relation_last_name'] = $getdata->rln_L_NmEn;
            $return_array['mobileno'] = $getdata->mobileno;
            $return_array['dob'] = $getdata->dob;
            $return_array['age'] = $getdata->age;
            $return_array['house_no'] = $getdata->house_no;
            $return_array['address'] = $getdata->section_name;
            $return_array['village'] = $getdata->part_name;
            $return_array['cons_name'] = $getdata->cons_name;
            $return_array['dist_name'] = $getdata->cons_name;
            $return_array['state_name'] = $getdata->StateName;
            if(@$getps){
                $return_array['ps_name'] = $getps->poll_building.' '.$getps->poll_building_detail;
                $return_array['poll_areas'] = $getps->poll_areas;
                $return_array['latitude'] = $getps->latitude;
                $return_array['longitude'] = $getps->longitude;
            }
            else{
                $return_array['ps_name'] = 'NA';
                $return_array['poll_areas'] = 'NA';
                $return_array['latitude'] = 'NA';
                $return_array['longitude'] = 'NA';
            }
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
        else{
            $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }
    }

    public function electoral_ps(Request $request){
        $getdata = DB::table('poll_booths')
            // ->where('state_id',$request->state_id)
            // ->where('dist_code',$request->dist_code)
            // ->where('cons_code',$request->cons_code)
            ->join('users', 'poll_booths.supervisior_uid','users.uid')
            ->where('poll_booths.state_id','=',$request->state_id)
            ->where('poll_booths.dist_code','=',$request->dist_code)
            ->where('poll_booths.cons_code','=',$request->cons_code)
            ->get();
        if($getdata->count()){
            //$bid = str_pad($getdata->dist_code, 2, '0', STR_PAD_LEFT).str_pad($getdata->cons_code, 3, '0', STR_PAD_LEFT).str_pad($getdata->ps_id, 3, '0', STR_PAD_LEFT);
            //$getps = DB::table('poll_booths')->where('bid',$bid)->first();
            foreach ($getdata as $data) {
                $voters['bid'] = $data->bid;
                $voters['supervisor_name'] = $data->name;
                $voters['supervisor_number'] = $data->phone;
                $voters['ps_name'] = $data->poll_building.' '.$data->poll_building_detail;
                $voters['cons_code'] = $data->cons_code;
                $voters['dist_code'] = $data->dist_code;
                $voters['ps_id'] = $data->ps_id;
                $voters['poll_type'] = $data->poll_type;
                // $voters['firstName'] = $data->fm_nameEn;
                // $voters['LastName'] = $data->LastNameEn;
                // $voters['gender'] = $data->sex;
                $return_array[] = $voters;
            }
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
        else{
            $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }
    }

    public function electoral_ps_detail(Request $request){
        $bid = $request->bid;
        $state_id = 53;
        $dist_code = $request->dist_code;
        $cons_code = $request->cons_code;
        $bid = str_pad($bid, 8, '0', STR_PAD_LEFT);
        $str = substr($bid, 5, 7);
        $part_no = ltrim($str, '0');
        $getdata = get_voter_list($state_id,$dist_code,$cons_code,$part_no);
        
        $getdata = json_decode($getdata);
       
        
        // $getdata = DB::table('voters')
        //     //->where('state_id',$request->state_id)
        //     ->where('dist_code',$request->dist_code)
        //     ->where('cons_code',$request->cons_code)
        //     ->where('ps_id',$part_no)
        //     ->get();
        //poll_booth_details($voterDetail->state_id, $voterDetail->dist_code, $voterDetail->cons_code, $voterDetail->ps_id);
        if(count($getdata)>=1){
            //$bid = str_pad($getdata->dist_code, 2, '0', STR_PAD_LEFT).str_pad($getdata->cons_code, 3, '0', STR_PAD_LEFT).str_pad($getdata->ps_id, 3, '0', STR_PAD_LEFT);
            //$getps = DB::table('poll_booths')->where('bid',$bid)->first();
            foreach ($getdata as $data) {
                $voters['sr no'] = $data->SLNOINPART;
                $voters['epic_number'] = $data->IDCARD_NO;
                $voters['firstName'] = $data->Fm_NameEn;
                $voters['LastName'] = $data->LastNameEn;
                $voters['gender'] = $data->SEX;
                $return_array[] = $voters;
            }
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
        else{
            $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }
    }

    public function poll_day_report(Request $request){
        $getdata = DB::table('pro_activity_pollday')
            ->where('dist_code',$request->dist_code)
            ->where('cons_code',$request->cons_code)
            ->where('bid', $request->bid)->first();
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
            $return_array['polled_evm'] = json_decode($getdata->polled_evm);
            $return_array['election_material'] = json_decode($getdata->election_material);
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
            $return_array['polled_evm'] = null;
            $return_array['election_material'] = null;
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
       
    }


    public function eciComplaints(Request $request)
    {
       $epic_no = $request->epic_no;
       $complaint_id_sec = $request->complaint_id_sec;
       $device_id = $request->device_id;

       if(($complaint_id_sec!="")&&($epic_no!=""))
       {
           $addComplaint = array(
                'epic_no' => $epic_no,
                'complaint_id_sec' => $complaint_id_sec,
                'device_id' => $device_id,
                'complaint_status' => "1",
            );     
            $addComplaints = DB::table('eci_complaints')->insert($addComplaint);  
            $return = array('result' => "Your complaint registered successfully",'status_code'=>200);
            exit(json_encode($return));
       }
    }

    public function eciComplaintsGet(Request $request)
    {
       $epic_no = $request->epic_no;
       $device_id = $request->device_id;

       $get_eci_complaints = DB::table('eci_complaints')
                            ->where('eci_complaints.epic_no', $request->epic_no)
                            //->where('eci_complaints.device_id', $request->device_id)
                            ->get();
        if($get_eci_complaints->count()) { 
            foreach($get_eci_complaints as $get_eci_complaints1){
                $group['complaint_id_sec'] = $get_eci_complaints1->complaint_id_sec;
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


     public function eciComplaint(Request $request){
        echo "hello";
        die();
     }

}