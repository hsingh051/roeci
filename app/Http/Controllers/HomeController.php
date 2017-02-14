<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Hash;
use Validator;
use Config;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {

        /* match session with database credential for login */
        if(Auth::check()){
            $user = Auth::user();
            $role = $user->role;
            if($role == 1){
                return Redirect::to('eci/dashboard');
            }elseif($role == 2){
                return Redirect::to('ceo/dashboard');
            }
            elseif($role == 3){
                return Redirect::to('deo/dashboard');
            }
            elseif($role == 4){
                return Redirect::to('ro/dashboard');
            }else{
                return Redirect::to('/');
            }
           
        }
        else {
            return redirect('/login');
        }
    }

    public function pagenotfound(){
        return view('pagenotfound');
    }

    public function postlogin(Request $request){
        $this->validate($request, [
          'phone' => 'required',
          'password' => 'required',
        ]);

        $phone = $request->phone;
        $user = DB::table('users')->where('phone', $phone)->first();
        if($user){
            if (Hash::check(pass_decrypt($request->password), $user->password)) {

                /* custom code to save auth_token in user table */
                $code = Hash::make(str_random(10));
                $date = Carbon::now();
                $currentTime = $date->format('Y-m-d H:i:s');
                $datas = array(
                    'mobile_otp' => '123456'/*$mobile_otp*/,
                    'otp_time' => $currentTime,
                    'auth_token' => $code,
                );
                $i = DB::table('users')->where('id', $user->id)->update($datas);
                Session::set('phone', $_POST['phone']);
                Session::set('password', $_POST['password']);
                Session::set('auth_token', $code);
                return Redirect::to('/otpverification');
                /* end custom code */
            }

            else{
                \Session::flash('mismatch', 'This password is wrong');
                return Redirect::to('/login');
            }
        }
        else{
            \Session::flash('username', 'This username does not exist.');
            return Redirect::to('/login');
        }

   }

    /* OPT Verification View Page */

    public function otpverification(Request $request)
    {
        // $phone = Session::get('phone');
        // $token = Session::get('auth_token');
        if ($request->session()->has('phone') && $request->session()->has('auth_token')) {
            
            return view('otpverification');
        }
        else{

            return redirect('/');
        }
    }

    /* Resend OTP */
    public function resendotp()
    {
        $phone = Session::get('phone');
        $token = Session::get('auth_token');
        if(!empty($phone) && !empty($token)) {
            $date = Carbon::now();
            $currentTime = $date->format('Y-m-d H:i:s');
            /*update otp for login */
            $phone = Session::get('phone');
            $token = Session::get('auth_token');
            $mobileotp = array(
                'mobile_otp' => '123456'/*$mobile_otp*/,
                'otp_time' => $currentTime,
            );
            $updatetoken = DB::table('users')->where('phone', $phone)->where('auth_token', $token)->update($mobileotp);
            return view('otpverification');
        }
        else{
            return redirect('/');
        }
    }

    /* Otp vervification and redirect to dashboard */

    public function verifyloginotp(Request $request)
    {
        $phone = Session::get('phone');
        $token = Session::get('auth_token');
        if(!empty($phone) && !empty($token)) {
            $post = $request->all();
            Session::set('otp', $post['otp']);
            $this->validate($request, [
                'otp' => 'required',
            ]);
            $phone = Session::get('phone');
            $password = Session::get('password');
            $token = Session::get('auth_token');
            $credentials = $request->only($phone, pass_decrypt($password));
            $matchotp = DB::table('users')->where('phone', $phone)->where('mobile_otp', $post['otp'])->where('auth_token', $token)->first();
            if($matchotp) {
                $date = Carbon::now();
                $date->modify('-1 minutes');
                $formatted_date = $date->format('Y-m-d H:i:s');
                $otptime = DB::table('users')->where('otp_time','>=',$formatted_date)->where('phone', $phone)->where('mobile_otp', $post['otp'])->first();
                if($otptime) {
                    if (Auth::attempt(['phone' => $phone, 'password' => pass_decrypt($password), 'mobile_otp' => $post['otp'], 'auth_token' => $token])) {
                        Session::forget('phone');
                        Session::forget('otp');
                        Session::forget('password');
                        Session::forget('auth_token');
                        $user = Auth::user();
                        $role = $user->role;
                        if($role == 1){
                           return Redirect::to('/select_state');
                        }
                        if($role == 2){
                            return Redirect::to('ceo/dashboard');
                        }
                        if($role == 3){
                            return Redirect::to('deo/dashboard');
                        }
                        if($role == 4){
                            return Redirect::to('ro/dashboard');
                        }
                    }
                    else{
                        return view('home');
                    }
                }
                else{
                    \Session::flash('opterror', 'OTP has been expired, please resend otp.');
                        return Redirect::to('/otpverification');
                   
                }
            }
            else{
                \Session::flash('opterror', 'OTP does not match. Please resend otp to your mobile.');
                return Redirect::to('/otpverification');
                
            }
        }
        else{
            return redirect('/');
        }

    }

    /* Select State for ECI */

    public function select_state()
    {

        
        $states = DB::table('states')
                      ->orderby('states.StateName')
                      ->get();
        return view('select_states', [
           'states' => $states,
        ]);


    }

    public function state(Request $request)
    {
        $this->validate($request, [
            'state_id' => 'required',
        ]);
        Session::set('state_id', $request->state_id);
        return Redirect::to('eci/dashboard');
    }


    /*forget password view */

    public function forget()
    {
        return view('auth.forget');
    }

    /*get request for password */

    public function resetnumber(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required|size:10',
        ]);
        $phone = $request->phone;
        $user = DB::table('users')->where('phone', $phone)->first();
        if($user){
            $code = Hash::make(str_random(10));
            $date = Carbon::now();
            $currentTime = $date->format('Y-m-d H:i:s');
            $datas = array(
                'reset_otp' => '654321'/*$mobile_otp*/,
                'reset_time' => $currentTime,
                'auth_token' => $code,
            );
            $i = DB::table('users')->where('id', $user->id)->update($datas);
            Session::set('reset_phone_password', $phone);
            Session::set('auth_token', $code);
            return Redirect::to('pwdotpverification');
        }
        else{
            \Session::flash('invalid', 'Invalid User!');
            return Redirect::to('forget');
        }
    }

    /*OTP verification page for forget password */

    public function pwdotpverification()
    {
        $phone = Session::get('reset_phone_password');
        $auth_token = Session::get('auth_token');
        if(!empty($phone) && !empty($auth_token)) {
            return view('auth.pwdotpverification');
        }
        else{
            return redirect('/');
        }
    }

    /*Enter otp for reset password */

    public function enterotp(Request $request)
    {
        $phone = Session::get('reset_phone_password');
        $auth_token = Session::get('auth_token');
        if(!empty($phone) && !empty($auth_token)) {
            $this->validate($request, [
                'otp' => 'required',
            ]);
            $resetotp = $request->otp;
            $matchotp = DB::table('users')->where('phone', $phone)->where('reset_otp', $resetotp)->where('auth_token', $auth_token)->first();
            if($matchotp) {
                $date = Carbon::now();
                $date->modify('-1 minutes');
                $formatted_date = $date->format('Y-m-d H:i:s');
                $otptime = DB::table('users')->where('reset_time','>=',$formatted_date)->where('phone', $phone)->where('reset_otp', $resetotp)->first();
                if($otptime) {
                    Session::set('reset_otp', $resetotp);
                    return Redirect::to('reset');
                }
                else{
                    \Session::flash('opterror', 'OTP has been expired, please resend otp.');
                        return Redirect::to('/pwdotpverification');
                   
                }
            }
            else{
                \Session::flash('opterror', 'OTP does not match. Please resend otp to your mobile.');
                return Redirect::to('/pwdotpverification');
                
            }
        }
        else{
            return redirect('/');
        }
    }

    /*Resend otp for forget password*/

    public function resendotppwd()
    {
        $phone = Session::get('reset_phone_password');
        $token = Session::get('auth_token');
        if(!empty($phone) && !empty($token)) {
            $date = Carbon::now();
            $currentTime = $date->format('Y-m-d H:i:s');
            /*update otp for login */
            $phone = Session::get('phone');
            $token = Session::get('auth_token');
            $mobileotp = array(
                'reset_otp' => '654321'/*$mobile_otp*/,
                'reset_time' => $currentTime,
            );
            $updatetoken = DB::table('users')->where('phone', $phone)->where('auth_token', $token)->update($mobileotp);
            return view('otpverification');
        }
        else{
            return redirect('/');
        }
    }

    /*Reset password view page */

    public function reset()
    {
        $phone = Session::get('reset_phone_password');
        $auth_token = Session::get('auth_token');
        if(!empty($phone) && !empty($auth_token)) {
            return view('auth.reset');
        }
        else{
            return redirect('/');
        }
    }

    public function resetpassword(Request $request)
    {
        $phone = Session::get('reset_phone_password');
        $auth_token = Session::get('auth_token');
        $reset_otp = Session::get('reset_otp');
        if(!empty($phone) && !empty($auth_token) && !empty($reset_otp)) {
            $this->validate($request, [
                'password' => 'required|min:6|confirmed',
            ]);

            $resetpassword = array(
                'password' => Hash::make($request->password),/*$new password*/
            );
            $updatepassword = DB::table('users')->where('phone', $phone)->where('auth_token', $auth_token)->where('reset_otp', $reset_otp)->update($resetpassword);
            Session::forget('reset_phone_password');
            Session::forget('auth_token');
            Session::forget('reset_otp');
            return redirect('/');
            
        }
        else{
            return redirect('/');
        }
    }

    public static function get_political_parties($state_id = NULL){

        $political_parties = DB::table('political_parties')
                               ->where('is_national_party', '1')
                               ->orWhere('state_party', 'like', "%'".$state_id."'%")
                               ->orderBy('party_name', 'asc')
                               ->get();
        //dd($political_parties);
        return $political_parties;
    }


    public static function get_constituencies($state_id,$dist_code){

        $constituencies = DB::table('constituencies')
                               ->where('state_id',$state_id)
                               ->where('dist_code',$dist_code)
                               ->orderBy('cons_code', 'asc')
                               ->get();
        //dd($political_parties);
        return $constituencies;
    }
    //{{ ControllerName::Functionname($params); }}


    public static function get_state_id(){
        $state_id = DB::table('eci_settings')
                               ->where('eci_key','state_id')
                               ->first();
        //dd($political_parties);
        return $state_id->eci_value;    
    }

}
