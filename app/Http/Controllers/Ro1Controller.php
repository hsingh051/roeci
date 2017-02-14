<?php
namespace App\Http\Controllers;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use DB;
use Excel;


class Ro1Controller extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('ro');

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    protected function guard()
    {
        return Auth::guard();
    }

    public function poll1day(){
        $user = Auth::user();
        
        $polling_stations = DB::table('poll_booths')
                            ->join('constituencies','constituencies.cons_code','=','poll_booths.cons_code')
                            ->leftjoin('pro_activity_before', 'poll_booths.bid', '=', 'pro_activity_before.bid')
                            ->where('poll_booths.dist_code', $user->dist_code)
                            ->where('poll_booths.cons_code', $user->cons_code)
                            ->select('constituencies.cons_name','poll_booths.poll_building')
                            ->get();
        //dd($polling_stations);
        return view('ro/poll-1day', [
           'polling_stations' => $polling_stations,
       ]);
        ///return view('ro/poll-1day');
    }

}
