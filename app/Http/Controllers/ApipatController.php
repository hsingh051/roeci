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

class ApipatController extends Controller 
{
    public function polling_stations(Request $request){
        $uid = $request->uid;
        $prodetail = DB::table('poll_booth_patwari')
                     ->join('poll_booths', 'poll_booth_patwari.bid','poll_booths.bid')
                     ->where('poll_booth_patwari.uid', $uid)
                     ->get();
        if($prodetail->count()>=1){
            foreach($prodetail as $poll){
                $grouplist['state_id'] = $poll->state_id;
                $grouplist['dist_code'] = $poll->dist_code;
                $grouplist['cons_code'] = $poll->cons_code;
                $grouplist['bid'] = $poll->bid;
                $grouplist['ps_id'] = $poll->ps_id;
                $grouplist['poll_name'] = $poll->poll_building." ".$poll->poll_building_detail;
                $grouplist['latitude'] = $poll->latitude;
                $grouplist['longitude'] = $poll->longitude;
                $group_array[] = $grouplist;
            }
            $return = array('result' => $group_array,'status_code'=>200);
            exit(json_encode($return));
        }else{
            $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }
        
    }

    public function add_time_queue(Request $request){
        $bid = $request->bid;
        $uid = $request->uid;
        $datas = array(
            'bid' => $bid,
            'uid' => $uid,
            'queue' => $request->queue,
        );
        $getdata = DB::table('patwari_time')->where('uid', $uid)->where('bid', $bid)->first();
        if($getdata){
            $i = DB::table('patwari_time')->where('uid', $uid)->where('bid', $bid)->update($datas);
            if($i==0){
                $return = array('result' => "Please try again!",'status_code'=>406);
                exit(json_encode($return));
            }
        }
        else{
            $i = DB::table('patwari_time')->insert($datas);
            if($i==0){
                $return = array('result' => "Please try again!",'status_code'=>406);
                exit(json_encode($return));
            }
        }
        $getupdateddata = DB::table('patwari_time')->where('uid', $uid)->where('bid', $bid)->first();
        $return_array['queue'] = json_decode($getupdateddata->queue);
        $return_array['bid'] = $bid;
        $return_array['uid'] = $uid;
        $return = array('result' => $return_array,'status_code'=>200);
        exit(json_encode($return));

    }

    public function get_time_queue(Request $request){
        $uid = $request->uid;
        $queuedetail = DB::table('patwari_time')
                     //->where('patwari_time.uid', $request->uid)
                     ->where('patwari_time.bid', $request->bid)
                     ->first();
        if(@$queuedetail){
            $grouplist['queue'] = json_decode($queuedetail->queue);
            $return = array('result' => $grouplist,'status_code'=>200);
            exit(json_encode($return));
        }else{
            $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }
        
    }

    
}