<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use DB;

class TestController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    protected function guard(){
        return Auth::guard();
    }

    public function dashboard(){
        // $user = Auth::user();
        // return view('ro/dashboard');
    }




    public function addpro(){
		$token = hash_hmac('sha256', str_random(40), config('app.key'));
        $password = bcrypt('123456');
 		$datas = array(
            'uid' =>'11000200050006',
            'mobile_no' =>'9780084844',
            'password' => $password,
            'name' => 'URMLA DEVI',
            'designation' => 'SOIL CONSERVATION OFFICER',
            'elect_duty' => 'APRO',
            'dist_code' => '11',
            'cons_code' => '63',
            'booth_no' => '002',
            'booth_no_a' => '',
            'data_entry_date' => date("Y-m-d",strtotime('11/24/2016')),
        );

        DB::table('users_pollday')->insert($datas);
        dd("dfs");

    }

    public function addbooth(){
    	$datas = array(
            'dist_code' => '11',
            'cons_code' => '57',
            'booth_no' => '001',
            'booth_no_a' => '',
            'ps_id' => '11057001',
            'village' => 'JATANA',
            'poll_build' => 'GOVT. HIGH SCHOOL, JATANA  (E W)',
            'poll_area' => '',
            'poll_type' => 'Sensitive',
        );
        DB::table('poll_booths')->insert($datas);
        dd("dfs");
    }

    public function addboothvote(){
    	$datas = array(
            'poll_booth_id' => '1',
            'male_vote' => '599',
            'female_vote' => '544',
            'total_vote' => '1143',
            'male_vote_polled' => '0',
            'female_vote_polled' => '0',
            'total_vote_polled' => '0',
            'last_updated' => date("Y-m-d H:i:s"),
            'created' => date("Y-m-d H:i:s"),
        );
        DB::table('poll_booth_votes')->insert($datas);
        dd("dfs");
    }
    
    public function addboothdetails(){
    	$datas = array(
            'poll_booth_id' => '1',
            'urban' => '0',
            'female_party' => '0',
            'no_of_officer' => '4',
            'party_no' => '1',
            'pardanashin' => '0',
            'phone' => '',
            'party_reached' => 'N',
            'agent_no' => '0',
            'mock_poll' => 'N',
            'polling_started' => 'N',
            'polling_int_res' => 'N',
            'polling_complete' => 'N',
            'party_departed' => 'N','evm_deposited' => 'N',
            'evm_faulty' => 'N',
            'law_and_order' => 'N',
            'thirdstage_process_date' => date(("Y-m-d H:i:s"),strtotime('11/24/2016 3:25:54 PM')),
            'last_updated' => date("Y-m-d H:i:s"),
            'created' => date("Y-m-d H:i:s"),
        );
        DB::table('poll_booth_details')->insert($datas);
        dd("dfs");
    }

    public function loginpro(Request $request){
    	die("asdsad");
    	// dd($request);
    	// $phone = $request->phone;
     //    $user = DB::table('users_pollday')->where('phone', $phone)->first();
    }

}
