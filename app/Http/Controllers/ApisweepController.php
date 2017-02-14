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

class ApisweepController extends Controller 
{

    /* List of RO along with dist code */

    public function get_events(Request $request)
    {
         $dist_code = $request->dist_code;
         // $events = App\Http\Controllers\CronjobController::get_events($dist_code);
         // return $events;
        $data_array = get_events($dist_code);
        if(!empty($data_array)){
       		foreach ($data_array as $data) {
       			$event_list['EventId'] = $data->EventId;
                $event_list['EventName'] = $data->EventName;
                $event_list['EventDescription'] = $data->EventDescription;
                $event_list['EventStartDate'] = date("d-m-Y",strtotime($data->EventStartDate));
                $event_list['EventEndDate'] =  date("d-m-Y",strtotime($data->EventEndDate));
                $return_array[] = $event_list;
       		}
       		$return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
       	}
        else{
        	$return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }
         

    }

    public function get_events_detail(Request $request)
    {
        $event_id = $request->event_id;
        $data_array = get_events_detail($event_id);
        if(!empty($data_array)){
	        $event_detail = $data_array->OneEvent[0];
	        $EventResource = $data_array->EventResource;
	        $return_array['EventName'] = $event_detail->EventName;
	        $return_array['EventDescription'] = $event_detail->EventDescription;
	        $return_array['EventStartDate'] =  date("d-m-Y",strtotime($event_detail->EventStartDate));
	        $return_array['EventEndDate'] =  date("d-m-Y",strtotime($event_detail->EventEndDate));
	        $return_array['ActivityName'] = $event_detail->ActivityName;
	        $return_array['InstituteId'] = $event_detail->InstituteId;
	        $return_array['InstituteName'] = $event_detail->InstituteName;
	        $return_array['IsCompleted'] = $event_detail->IsCompleted;
	        $return_array['DistrictName'] = $event_detail->DistrictName;
	        $return_array['StateName'] = $event_detail->StateName;
	        $return_array['TaskStatus'] = $event_detail->TaskStatus;
	        if(@$EventResource){
                foreach ($EventResource as $data) {
                   $EventResource_lists['FilePath'] = $data->FilePath;
                   $EventResource_lists['ThumbnailFilePath'] = $data->ThumbnailFilePath;
                $EventResource_list[] = $EventResource_lists;
                }
            }
            else{
               $EventResource_list = array();
            }
	    	$return_array['File'] = $EventResource_list;
	    	$return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return));
        }
        else{
        	$return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }
         

    }

    public function districtlist(Request $request){
       
        $districts = DB::table('districts')->orWhere('dist_code','5')->orWhere('dist_code','11')->get();
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
        
}