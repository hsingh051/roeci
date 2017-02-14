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

class ApiController extends Controller 
{
    /* Login for mobile app */
    public function login(Request $request)
    {
        $username = $request->username;
        $password = $request->password;
        if(!isset($username) || $username == "")            
        {               
            $return = array('result' => 'Please enter the username','status_code'=>204);
            exit(json_encode($return));         
        
        }
        if(!isset($password) || $password == "")            
        {               
            $return = array('result' => 'Please enter the password','status_code'=>204);
            exit(json_encode($return));         
        
        }
        $user = DB::table('users')->where('phone', $username)->first();
        $polldayuser = DB::table('users_pollday')->where('phone', $username)->first();
        $mediauser = DB::table('users_media')->where('uid', $username)->orWhere('phone', $username)->first();
        if(@$user){
            if (Hash::check($request->password, $user->password)) {
                /* Generate OTP for verification */
                $mobile_otp = send_otp($username);
                // if($user->role == '15'){
                //     $mobile_otp = send_otp($username);
                // }
                // else{
                //     $mobile_otp = '123456';
                // }
                $date = Carbon::now();
                $currentTime = $date->format('Y-m-d H:i:s');
                $datas = array(
                    'mobile_otp' => $mobile_otp,
                    'otp_time' => $currentTime,
                );
                $i = DB::table('users')->where('id', $user->id)->update($datas);
                $return_array['username'] = $username;
                $return_array['password'] = $password;
                $return_array['otp_flag'] = False;
                $return = array('result' => $return_array,'status_code'=>200);
                exit(json_encode($return)); 
            }
            else{
                $return = array('result' => 'Username and Password does not match','status_code'=>406);
                exit(json_encode($return)); 
            }
        }
        elseif (@$polldayuser) {
            $makepassword = 'Zk5z[('.$request->password.'B4[hY6';
            $decrypted_password = md5($makepassword);
            $checkpassword = DB::table('users_pollday')->where('phone', $username)->where('password', $decrypted_password)->first();
            if (@$checkpassword) {
                /* Generate OTP for verification */
                $uid = 'PRO'.$username;
                $checkrand = DB::table('randomization_staff_third')->where('uid', $uid)->first();
                if (@$checkrand) {
                    $mobile_otp = send_otp($username);
                    $date = Carbon::now();
                    $currentTime = $date->format('Y-m-d H:i:s');
                    $datas = array(
                        'mobile_otp' => $mobile_otp,
                        'otp_time' => $currentTime,
                    );
                    $i = DB::table('users_pollday')->where('id', $polldayuser->id)->update($datas);
                    $return_array['username'] = $username;
                    $return_array['password'] = $password;
                    $return_array['otp_flag'] = False;
                    $return = array('result' => $return_array,'status_code'=>200);
                    exit(json_encode($return));
                }
                else{
                    $return = array('result' => 'UNAUTHORIZED','status_code'=>401);
                    exit(json_encode($return));
                }
            }
            else{
                $return = array('result' => 'This username and Password does not match','status_code'=>406);
                exit(json_encode($return)); 
            }
        }
        elseif (@$mediauser) {
            $makepassword = $request->password;
            $decrypted_password = md5($makepassword);
            $matchThese = ['uid' => $username, 'password' => $decrypted_password];
            $orThose = ['phone' => $username, 'password' => $decrypted_password];
            //$checkpassword = DB::table('users_media')->where('uid', $username)->where('password', $decrypted_password)->first();
            $checkpassword = DB::table('users_media')->where($matchThese)->orWhere($orThose)->first();
            if (@$checkpassword) {
                /* Generate OTP for verification */
                $mobile_otp = send_otp($checkpassword->phone);
                $date = Carbon::now();
                $currentTime = $date->format('Y-m-d H:i:s');
                $datas = array(
                    'mobile_otp' => $mobile_otp,
                    'otp_time' => $currentTime,
                );
                $i = DB::table('users_media')->where('id', $mediauser->id)->update($datas);
                $return_array['username'] = $username;
                $return_array['password'] = $password;
                $return_array['otp_flag'] = False;
                $return = array('result' => $return_array,'status_code'=>200);
                exit(json_encode($return)); 
            }
            else{
                $return = array('result' => 'This username and Password does not match','status_code'=>406);
                exit(json_encode($return)); 
            }
        }
        else{
            $return = array('result' => 'Username does not exist','status_code'=>406);
            exit(json_encode($return));
        }
        
    }

    /* OTP verification */

    public function verifyloginotp(Request $request)
    {
        $mobile_otp = $request->mobile_otp;
        $username = $request->username;
        $password = $request->password;
        if(!isset($username) || $username == "")            
        {               
            $return = array('result' => 'Please enter the username','status_code'=>204);
            exit(json_encode($return));         
        
        }
        if(!isset($password) || $password == "")            
        {               
            $return = array('result' => 'Please enter the password','status_code'=>204);
            exit(json_encode($return));         
        
        }
        if(!isset($mobile_otp) || $mobile_otp == "")            
        {               
            $return = array('result' => 'Please enter one time password','status_code'=>204);
            exit(json_encode($return));         
        
        }
        $matchotp = DB::table('users')->where('phone', $username)->where('mobile_otp', $mobile_otp)->first();
        $matchotppolldayuser = DB::table('users_pollday')->where('phone', $username)->where('mobile_otp', $mobile_otp)->first();
        $matchThese = ['uid' => $username, 'mobile_otp' => $mobile_otp];
        $orThose = ['phone' => $username, 'mobile_otp' => $mobile_otp];
        $matchotpmediauser = DB::table('users_media')->where($matchThese)->orWhere($orThose)->first();
        $date = Carbon::now();
        $date->modify('-2 minutes');
        $formatted_date = $date->format('Y-m-d H:i:s');
        if(@$matchotp) {
            $otptime = DB::table('users')->where('otp_time','>=',$formatted_date)->where('phone', $username)->where('mobile_otp', $mobile_otp)->first();
            if($otptime) {
                if (Hash::check($request->password, $matchotp->password)) {
                    $return_array['user_id'] = $otptime->uid;
                    $return_array['username'] = $username;
                    $return_array['password'] = $password;
                    if($otptime->role != 1){
                        $return_array['state_id'] = $otptime->state_id;
                    }
                    $return_array['dist_code'] = $otptime->dist_code;
                    $return_array['cons_code'] = $otptime->cons_code;
                    $return_array['user_role'] = $otptime->role;
                    $return_array['otp_flag'] = True;
                    $deviceToken = $request->deviceToken;
                    $deviceType = $request->deviceType;
                    $gettoken = DB::table('users_token')->where('token','=',$deviceToken)->first();
                    if($gettoken){
                         $deviceData = array(
                            'phone' => $username,
                            'role' => $otptime->role,
                            'type' => $deviceType,
                        );
                        $deviceupdate = DB::table('users_token')->where('token', $deviceToken)->update($deviceData);
                    }
                    else{
                        $getphonenumber = DB::table('users_token')->where('phone','=',$username)->first();
                        if($getphonenumber){
                             $deviceData = array(
                                'token' => $deviceToken,
                                'role' => $otptime->role,
                                'type' => $deviceType,
                            );
                            $deviceupdatephone = DB::table('users_token')->where('phone', $username)->update($deviceData);
                        }
                        else{
                            $deviceData = array(
                                'phone' => $username,
                                'token' => $deviceToken,
                                'role' => $otptime->role,
                                'type' => $deviceType,
                            );
                            $insertdevicetoken = DB::table('users_token')->insert($deviceData);
                        }
                    }
                    $return = array('result' => $return_array,'status_code'=>200);
                    exit(json_encode($return)); 
                }
                else{
                    $return = array('result' => 'Something went wrong, please try again!','status_code'=>406);
                    exit(json_encode($return)); 
                }
            }
            else{
                $return = array('result' => 'OTP has been expired, please resend OTP','status_code'=>406);
                exit(json_encode($return)); 
               
            }
        }
        elseif (@$matchotppolldayuser) {
            $otptime = DB::table('users_pollday')->where('otp_time','>=',$formatted_date)->where('phone', $username)->where('mobile_otp', $mobile_otp)->first();
            if($otptime) {
                $makepassword = 'Zk5z[('.$request->password.'B4[hY6';
                $decrypted_password = md5($makepassword);
                $checkpassword = DB::table('users_pollday')->where('phone', $username)->where('password', $decrypted_password)->first();
                if ($checkpassword) {
                    $bid = "";
                    $i = DB::table('randomization_staff_third')->where('uid', $otptime->uid)->first();
                    if($i){
                        $bid = $i->bid;
                    }
                    $return_array['user_id'] = $otptime->uid;
                    $return_array['username'] = $username;
                    $return_array['password'] = $password;
                    $return_array['user_role'] = $otptime->role;
                    $return_array['state_id'] = $otptime->state_id;
                    $return_array['dist_code'] = $otptime->dist_code;
                    $return_array['cons_code'] = $otptime->cons_code;
                    $return_array['bid'] = $bid;
                    $return_array['otp_flag'] = True;
                    $deviceToken = $request->deviceToken;
                    $deviceType = $request->deviceType;
                    $gettoken = DB::table('users_token')->where('token','=',$deviceToken)->first();
                    if($gettoken){
                         $deviceData = array(
                            'phone' => $username,
                            'role' => $otptime->role,
                            'type' => $deviceType,
                        );
                        $deviceupdate = DB::table('users_token')->where('token', $deviceToken)->update($deviceData);
                    }
                    else{
                        $getphonenumber = DB::table('users_token')->where('phone','=',$username)->first();
                        if($getphonenumber){
                             $deviceData = array(
                                'token' => $deviceToken,
                                'role' => $otptime->role,
                                'type' => $deviceType,
                            );
                            $deviceupdatephone = DB::table('users_token')->where('phone', $username)->update($deviceData);
                        }
                        else{
                            $deviceData = array(
                                'phone' => $username,
                                'token' => $deviceToken,
                                'role' => $otptime->role,
                                'type' => $deviceType,
                            );
                            $insertdevicetoken = DB::table('users_token')->insert($deviceData);
                        }
                    }
                    $return = array('result' => $return_array,'status_code'=>200);
                    exit(json_encode($return)); 
                }
                else{
                    $return = array('result' => 'Something went wrong, please try again!','status_code'=>406);
                    exit(json_encode($return)); 
                }
            }
            else{
                $return = array('result' => 'OTP has been expired, please resend OTP','status_code'=>406);
                exit(json_encode($return)); 
               
            }
        }
        elseif (@$matchotpmediauser) {
            $otptime = DB::table('users_media')->where('otp_time','>=',$formatted_date)->where('uid', $matchotpmediauser->uid)->where('mobile_otp', $mobile_otp)->first();
            if($otptime) {
                $makepassword = $request->password;
                $decrypted_password = md5($makepassword);
                $checkpassword = DB::table('users_media')->where('uid', $matchotpmediauser->uid)->where('password', $decrypted_password)->first();
                if ($checkpassword) {
                    $return_array['username'] = $username;
                    $return_array['phone'] = $otptime->phone;
                    $return_array['password'] = $password;
                    $return_array['user_role'] = $otptime->role;
                    $return_array['state_id'] = $otptime->state_id;
                    $return_array['dist_code'] = $otptime->dist_code;
                    $return_array['otp_flag'] = True;
                    $return = array('result' => $return_array,'status_code'=>200);
                    exit(json_encode($return)); 
                }
                else{
                    $return = array('result' => 'Something went wrong, please try again!','status_code'=>406);
                    exit(json_encode($return)); 
                }
            }
            else{
                $return = array('result' => 'OTP has been expired, please resend OTP','status_code'=>406);
                exit(json_encode($return)); 
               
            }
        }
        else{
            $return = array('result' => 'OTP does not match. Please resend OTP','status_code'=>406);
            exit(json_encode($return)); 
        }
    }

    /* Resend OTP */
    public function resendotp(Request $request)
    {
        $username = $request->username;
        $password = $request->password;
        if(!isset($username) || $username == "")            
        {               
            $return = array('result' => 'Please enter the username','status_code'=>204);
            exit(json_encode($return));         
        
        }
        if(!isset($password) || $password == "")            
        {               
            $return = array('result' => 'Please enter the password','status_code'=>204);
            exit(json_encode($return));         
        
        }
        $user = DB::table('users')->where('phone', $username)->first();
        $polldayuser = DB::table('users_pollday')->where('phone', $username)->first();
        $mediauser = DB::table('users_media')->where('uid', $username)->orWhere('phone', $username)->first();
        $date = Carbon::now();
        $currentTime = $date->format('Y-m-d H:i:s');
        if($user) {
           if (Hash::check($password, $user->password)) {
                $mobile_otp = send_otp($user->phone);
                // if($user->role == '15'){
                //     $mobile_otp = send_otp($user->phone);
                // }
                // else{
                //     $mobile_otp = '123456';
                // }
                //$mobile_otp = send_otp($checkpassword->phone);
                $datas = array(
                    'mobile_otp' => $mobile_otp,
                    'otp_time' => $currentTime,
                );
                /* Re save otp  */
                $i = DB::table('users')->where('id', $user->id)->update($datas);
                // $return_array['username'] = $username;
                // $return_array['password'] = $password;
                // $return_array['otp_flag'] = False;
                $return = array('result' => "OTP sent again",'status_code'=>200);
                exit(json_encode($return)); 
            }
            else{
                $return = array('result' => 'This Username and Password does not match','status_code'=>406);
                exit(json_encode($return)); 
            }
        }
        elseif (@$polldayuser) {
            $makepassword = 'Zk5z[('.$password.'B4[hY6';
            $decrypted_password = md5($makepassword);
            $checkpassword = DB::table('users_pollday')->where('phone', $username)->where('password', $decrypted_password)->first();
            if (@$checkpassword) {
                $mobile_otp = send_otp($polldayuser->phone);
                $datas = array(
                    'mobile_otp' => $mobile_otp,
                    'otp_time' => $currentTime,
                );
                /* Re save otp  */
                $i = DB::table('users_pollday')->where('id', $polldayuser->id)->update($datas);
                $return_array['username'] = $username;
                $return_array['password'] = $password;
                $return_array['otp_flag'] = False;
                $return = array('result' => $return_array,'status_code'=>200);
                exit(json_encode($return)); 
            }
            else{
                $return = array('result' => 'This Username and Password does not match','status_code'=>406);
                exit(json_encode($return)); 
            }
        }
        elseif (@$mediauser) {
            $makepassword = $password;
            $decrypted_password = md5($makepassword);
            $checkpassword = DB::table('users_media')->where('uid', $mediauser->uid)->where('password', $decrypted_password)->first();
            if (@$checkpassword) {
                $mobile_otp = send_otp($mediauser->phone);
                $datas_med = array(
                    'mobile_otp' => $mobile_otp,
                    'otp_time' => $currentTime,
                );
                /* Re save otp  */
                $i = DB::table('users_media')->where('id', $mediauser->id)->update($datas_med);
                $return_array['username'] = $username;
                $return_array['password'] = $password;
                $return_array['otp_flag'] = False;
                //$return = array('result' => $return_array,'status_code'=>200);
                $return = array('result' => 'OTP has been sent again','status_code'=>200);
                exit(json_encode($return)); 
            }
            else{
                $return = array('result' => 'This Username and Password does not match','status_code'=>406);
                exit(json_encode($return)); 
            }
        }
        else{
            $return = array('result' => 'This Username does not exist','status_code'=>406);
            exit(json_encode($return));
        }
    }

    /* forget password */

    /*get request for password */

    public function forgetpassword(Request $request)
    {
        $username = $request->username;
        if(!isset($username) || $username == ""){               
            $return = array('result' => 'Please enter the Username','status_code'=>204);
            exit(json_encode($return));         
        }
        $user = DB::table('users')->where('phone', $username)->first();
        $polldayuser = DB::table('users_pollday')->where('phone', $username)->first();
        $mediauser = DB::table('users_media')->where('uid', $username)->orWhere('phone',$username)->first();
        $date = Carbon::now();
        $currentTime = $date->format('Y-m-d H:i:s');
        if(@$user){
            $mobile_otp = send_otp($user->phone);
            $datas = array(
                'reset_otp' => $mobile_otp,
                'reset_time' => $currentTime,
            );
            $i = DB::table('users')->where('id', $user->id)->update($datas);
            $return_array['username'] = $username;
            $return_array['otp_flag'] = False;
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return)); 
        }
        elseif (@$polldayuser) {
            $mobile_otp = send_otp($polldayuser->phone);
            $datas = array(
                'reset_otp' => $mobile_otp,
                'reset_time' => $currentTime,
            );
            $i = DB::table('users_pollday')->where('id', $polldayuser->id)->update($datas);
            $return_array['username'] = $username;
            $return_array['otp_flag'] = False;
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return)); 
        }
        elseif (@$mediauser) {
            $mobile_otp = send_otp($mediauser->phone);
            $mobileotp = array(
                'reset_otp' => $mobile_otp,
                'reset_time' => $currentTime,
            );
            $i = DB::table('users_media')->where('id', $mediauser->id)->update($mobileotp);
            $return_array['username'] = $username;
            $return_array['otp_flag'] = False;
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return)); 
        }
        else{
            $return = array('result' => 'This Username does not exist','status_code'=>406);
            exit(json_encode($return));
        }
    }

    /* RESET OTP verification */

    public function verifyresetotp(Request $request)
    {
        $mobile_otp = $request->mobile_otp;
        $username = $request->username;
        if(!isset($username) || $username == "")            
        {               
            $return = array('result' => 'Please enter the Username','status_code'=>204);
            exit(json_encode($return));         
        
        }
        if(!isset($mobile_otp) || $mobile_otp == "")            
        {               
            $return = array('result' => 'Please enter OTP','status_code'=>204);
            exit(json_encode($return));         
        
        }
        $matchotp = DB::table('users')->where('phone', $username)->where('reset_otp', $mobile_otp)->first();
        $matchotppolldayuser = DB::table('users_pollday')->where('phone', $username)->where('reset_otp', $mobile_otp)->first();
        $matchThese = ['uid' => $username, 'reset_otp' => $mobile_otp];
        $orThose = ['phone' => $username, 'reset_otp' => $mobile_otp];
        $matchotpmediauser = DB::table('users_media')->where($matchThese)->orWhere($orThose)->first();
        $date = Carbon::now();
        $date->modify('-2 minutes');
        $formatted_date = $date->format('Y-m-d H:i:s');
        if(@$matchotp) {
            $otptime = DB::table('users')->where('reset_time','>=',$formatted_date)->where('phone', $username)->where('reset_otp', $mobile_otp)->first();
            if($otptime) {
                $return_array['username'] = $username;
                $return_array['mobile_otp'] = $mobile_otp;
                $return_array['otp_flag'] = True;
                $return = array('result' => 'OTP sent successfully.','status_code'=>200);
                exit(json_encode($return)); 
            }
            else{
                $return = array('result' => 'OTP has been expired, please resend otp.','status_code'=>406);
                exit(json_encode($return)); 
               
            }
        }
        elseif (@$matchotppolldayuser) {
            $otptime = DB::table('users_pollday')->where('reset_time','>=',$formatted_date)->where('phone', $username)->where('reset_otp', $mobile_otp)->first();
            if($otptime) {
                $return_array['username'] = $username;
                $return_array['mobile_otp'] = $mobile_otp;
                $return_array['otp_flag'] = True;
                $return = array('result' => 'OTP sent successfully.','status_code'=>200);
                exit(json_encode($return)); 
            }
            else{
                $return = array('result' => 'OTP has been expired, please resend otp.','status_code'=>406);
                exit(json_encode($return)); 
               
            }
        }
        elseif (@$matchotpmediauser) {
            $otptime = DB::table('users_media')->where('reset_time','>=',$formatted_date)->where('uid', $matchotpmediauser->uid)->where('reset_otp', $mobile_otp)->first();
            if($otptime) {
                $return_array['username'] = $username;
                $return_array['mobile_otp'] = $mobile_otp;
                $return_array['otp_flag'] = True;
                $return = array('result' => 'OTP sent successfully.','status_code'=>200);
                exit(json_encode($return)); 
            }
            else{
                $return = array('result' => 'OTP has been expired, please resend otp.','status_code'=>406);
                exit(json_encode($return)); 
               
            }
        }
        else{
            $return = array('result' => 'OTP does not match. Please resend otp to your mobile.','status_code'=>406);
            exit(json_encode($return)); 
        }
    }

    /* Resend reset otp for password*/

    public function resendresetotp(Request $request)
    {
        $username = $request->username;
        if(!isset($username) || $username == "")            
        {               
            $return = array('result' => 'Please enter the username','status_code'=>204);
            exit(json_encode($return));         
        
        }
        $user = DB::table('users')->where('phone', $username)->first();
        $polldayuser = DB::table('users_pollday')->where('phone', $username)->first();
        $mediauser = DB::table('users_media')->where('uid', $username)->orWhere('phone', $username)->first();
        $date = Carbon::now();
        $currentTime = $date->format('Y-m-d H:i:s');
        if(@$user){
            /*update reset otp for login */
            $mobile_otp = send_otp($user->phone);
            // if($user->role == '15'){
            //     $mobile_otp = send_otp($user->phone);
            // }
            // else{
            //     $mobile_otp = '654321';
            // }
            $mobileotp = array(
                'reset_otp' => $mobile_otp,
                'reset_time' => $currentTime,
            );
            $updatetoken = DB::table('users')->where('phone', $username)->update($mobileotp);
            $return_array['username'] = $username;
            $return_array['otp_flag'] = False;
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return)); 
        }
        elseif (@$polldayuser) {
            $mobile_otp = send_otp($polldayuser->phone);
            $mobileotp = array(
                'reset_otp' => $mobile_otp*/,
                'reset_time' => $currentTime,
            );
            $updatetoken = DB::table('users_pollday')->where('phone', $username)->update($mobileotp);
            $return_array['username'] = $username;
            $return_array['otp_flag'] = False;
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return)); 
        }
        elseif (@$mediauser) {
            $mobile_otp = send_otp($mediauser->phone);
            $mobileotp = array(
                'reset_otp' => $mobile_otp,
                'reset_time' => $currentTime,
            );
            $updatetoken = DB::table('users_media')->where('uid', $mediauser->uid)->update($mobileotp);
            $return_array['username'] = $username;
            $return_array['otp_flag'] = False;
            $return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return)); 
        }
        else{
            $return = array('result' => 'This username does not exist.','status_code'=>406);
            exit(json_encode($return));
        }
    }

    /*Change Password */

    public function resetpassword(Request $request)
    {
        $username = $request->username;
        $mobile_otp = $request->mobile_otp;
        if(!isset($username) || $username == "")            
        {               
            $return = array('result' => 'Please enter the username','status_code'=>204);
            exit(json_encode($return));         
        
        }
        if(!isset($mobile_otp) || $mobile_otp == "")            
        {               
            $return = array('result' => 'Please enter one time password','status_code'=>204);
            exit(json_encode($return));         
        
        }
        $user = DB::table('users')->where('phone', $username)->where('reset_otp', $mobile_otp)->first();
        $polldayuser = DB::table('users_pollday')->where('phone', $username)->where('reset_otp', $mobile_otp)->first();
        $matchThese = ['uid' => $username, 'reset_otp' => $mobile_otp];
        $orThose = ['phone' => $username, 'reset_otp' => $mobile_otp];
        $mediauser = DB::table('users_media')->where($matchThese)->orWhere($orThose)->first();
        if(@$user) {
            $resetpassword = array(
                'password' => Hash::make($request->password),/*$new password*/
            );
            $updatepassword = DB::table('users')->where('phone', $username)->where('reset_otp', $mobile_otp)->update($resetpassword);
            $return = array('result' => 'Password change successfully. Please login with your new password.','status_code'=>200);
            exit(json_encode($return));
            
        }
        elseif (@$polldayuser) {
            $makepassword = 'Zk5z[('.$request->password.'B4[hY6';
            $resetpassword = array(
                'password' => md5($makepassword),/*$new password*/
            );
            $updatepassword = DB::table('users_pollday')->where('phone', $username)->where('reset_otp', $mobile_otp)->update($resetpassword);
            $return = array('result' => 'Password change successfully. Please login with your new password.','status_code'=>200);
            exit(json_encode($return));
        }
        elseif (@$mediauser) {
            $makepassword = $request->password;
            $resetpassword = array(
                'password' => md5($makepassword),/*$new password*/
            );
            $updatepassword = DB::table('users_media')->where('uid', $mediauser->uid)->where('reset_otp', $mobile_otp)->update($resetpassword);
            $return = array('result' => 'Password change successfully. Please login with your new password.','status_code'=>200);
            exit(json_encode($return));
        }
        else{
            $return = array('result' => 'Something went wrong, please try again!','status_code'=>406);
            exit(json_encode($return));
        }
    }

    /* List of district along with dist code */

    public function districtlist(Request $request)
    {
        $state_id = $request->state_id;
        $districts = DB::table('districts')
                        ->where('state_id',$state_id)
                        ->where('status', 1)->get();
        if($districts) {
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

    public function eci_date_setting()
    {
        $dates = DB::table('eci_settings')->get();
        if($dates) {
            foreach($dates as $date){
                $date_list[$date->eci_key] = $date->eci_value;
                
            }
            $return_array['result'] = $date_list;
            $return_array['status_code'] = 200;
            //$return = array('result' => $return_array,'status_code'=>200);
            exit(json_encode($return_array));
            
        }
        else{
            $return = array('result' => 'No relevant data found','status_code'=>406);
            exit(json_encode($return));
        }
    }

    public function login_alert_msg()
    {
        $datas = DB::table('login_alert_msg')->first();
        $return_array['message'] = $datas->message;
        $return = array('result' => $return_array,'status_code'=>200);
        exit(json_encode($return));
    }

    public function mcc_files()
    {
        //$return_array['file'] = url('/').'/files/MCC.pdf';
        $return_array['live_poll_percentage'] = 'http://ceopunjab.nic.in/';
        $return_array['file'] = 'http://02pg.com/eci360/public/files/MCC.pdf';
        $return = array('result' => $return_array,'status_code'=>200);
        exit(json_encode($return));
    
    }

    public function android_version()
    {
        $android_voter = "android_voter";
        $voters_version = DB::table('eci_settings')->where('eci_key',$android_voter)->first();
        $android_ronet = "android_ronet";
        $ronet_version = DB::table('eci_settings')->where('eci_key',$android_ronet)->first();
        $android_patwari = "android_patwari";
        $patwari_version = DB::table('eci_settings')->where('eci_key',$android_patwari)->first();
        $version['mobile_version'] = $ronet_version->eci_value;
        $version['mobile_version_new'] = $voters_version->eci_value;
        $version['mobile_version_ronet'] = $ronet_version->eci_value;
        $version['mobile_version_patwari'] = $patwari_version->eci_value;
        $return_array[] = $version;
        $return = array('result' => $return_array,'status_code'=>200);
        exit(json_encode($return));
    }

    public function ios_version()
    {
        $ios_voter = "ios_voter";
        $voters_version = DB::table('eci_settings')->where('eci_key',$ios_voter)->first();
        $ios_ronet = "ios_ronet";
        $ronet_version = DB::table('eci_settings')->where('eci_key',$ios_ronet)->first();
        $version['mobile_version'] = $voters_version->eci_value;
        $version['mobile_version_ronet'] = $ronet_version->eci_value;
        $return_array[] = $version;
        $return = array('result' => $return_array,'status_code'=>200);
        exit(json_encode($return));
    }


    public function send_welcome_msg()
    {
        $user = "01synergy";
        $password = "01@Synergy";
        $msisdn = "9464529625";
        $sid = "SYNRGY";
        $msg = "Welcome to RONET! It has been launched by Honourable Election Commission of India. Login to proceed further!";
        $msg = urlencode($msg);
        $fl = "0";
        $gwid = "2";
        $ch =
        curl_init("http://cloud.smsindiahub.in/vendorsms/pushsms.aspx?user=".$user."&password=".$password."&ms
        isdn=".$msisdn."&sid=".$sid."&msg=".$msg."&fl=".$fl."&gwid=".$gwid."");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        // Display MSGID of the successful sms push
        echo $output; 
        die();


    }



    public function dashboard_pollbeforeday(Request $request){
        $current_time = current_hour();
        $first_evm = Config::get('constants.FIRST_RANDOMIZATION_EVM_DATE');
        $first_evm_time = strtotime($first_evm);
        $second_evm = Config::get('constants.SECOND_RANDOMIZATION_EVM_DATE');
        $second_evm_time = strtotime($second_evm);
        $first_staff = Config::get('constants.FIRST_RANDOMIZATION_STAFF_DATE');
        $first_staff_time = strtotime($first_staff);
        $second_staff = Config::get('constants.SECOND_RANDOMIZATION_STAFF_DATE');
        $second_staff_time = strtotime($second_staff);
        $third_staff = Config::get('constants.THIRD_RANDOMIZATION_STAFF_DATE');
        $third_staff_time = strtotime($third_staff);
        
        $state_id = $request->state_id;
        $dist_code = $request->dist_code;
        $cons_code = $request->cons_code;
        $today = time();
        if($first_evm_time <= $today){
            $evmfirstlist = DB::table('constituencies')
                        ->join('districts','districts.dist_code','=','constituencies.dist_code')
                        ->whereNotIn('constituencies.cons_code', function($query) use($state_id, $dist_code, $cons_code)
                        {
                            $query->select('cons_code')
                                  ->from('randomization_evm_first')
                                  ->where('state_id','=',$state_id)
                                  ->where('dist_code','=',$dist_code)
                                  ->where('cons_code','=',$cons_code);
                        })
                        ->select('constituencies.cons_name','districts.dist_name')
                        ->where('constituencies.state_id','=',$state_id)
                        ->where('constituencies.dist_code','=',$dist_code)
                        ->where('constituencies.cons_code','=',$cons_code)
                        ->first();
            if($evmfirstlist){
                $return_evm['tag'] = 1;
                $msg['poll_building'] = "pending";
                $msg['ps_id'] = 0;
                $return_evm['data'][] = $msg;
            }
            else{
                if($second_evm_time <= $today){
                    $evmsecondlist = DB::table('constituencies')
                                ->join('districts','districts.dist_code','=','constituencies.dist_code')
                                ->whereNotIn('constituencies.cons_code', function($query) use($state_id, $dist_code, $cons_code)
                                {
                                    $query->select('cons_code')
                                          ->from('randomization_evm_second')
                                          ->where('state_id','=',$state_id)
                                          ->where('dist_code','=',$dist_code)
                                          ->where('cons_code','=',$cons_code);
                                })
                                ->select('constituencies.cons_name','districts.dist_name')
                                ->where('constituencies.state_id','=',$state_id)
                                ->where('constituencies.dist_code','=',$dist_code)
                                ->where('constituencies.cons_code','=',$cons_code)
                                ->first();
                    if($evmsecondlist){
                        $return_evm['tag'] = 1;
                        $msg['poll_building'] = "pending";
                        $msg['ps_id'] = 0;
                        $return_evm['data'][] = $msg;
                    }
                    else{
                        $return_evm['tag'] = 0;
                        $msg['poll_building'] = "done";
                        $msg['ps_id'] = 0;
                        $return_evm['data'][] = $msg;
                    }
                }
                else{
                    $return_evm['tag'] = 1;
                    $msg['poll_building'] = 'Due date '.$second_evm;
                    $msg['ps_id'] = 0;
                    $return_evm['data'][] = $msg;
                }
            }
        }
        else{
            $return_evm['tag'] = 1;
            $msg['poll_building'] = 'Due date '.$first_evm;
            $msg['ps_id'] = 0;
            $return_evm['data'][] = $msg;
        }

        if($first_staff_time <= $today){
            $firststaff = DB::table('constituencies')
                        ->join('districts','districts.dist_code','=','constituencies.dist_code')
                        ->whereNotIn('constituencies.cons_code', function($query) use($state_id, $dist_code, $cons_code)
                        {
                            $query->select('cons_code')
                                  ->from('users_pollday')
                                  ->where('state_id','=',$state_id)
                                  ->where('dist_code','=',$dist_code)
                                  ->where('cons_code','=',$cons_code);
                        })
                        ->select('constituencies.cons_name','districts.dist_name')
                        ->where('constituencies.state_id','=',$state_id)
                        ->where('constituencies.dist_code','=',$dist_code)
                        ->where('constituencies.cons_code','=',$cons_code)
                        ->first();
            if($firststaff){
                $return_staff['tag'] = 1;
                $msg['poll_building'] = 'pending';
                $msg['ps_id'] = 0;
                $return_staff['data'][] = $msg;
            }
            else{
                if($second_staff_time <= $today){
                    $secondstaff = DB::table('constituencies')
                        ->join('districts','districts.dist_code','=','constituencies.dist_code')
                        ->whereNotIn('constituencies.cons_code', function($query) use($state_id, $dist_code, $cons_code)
                        {
                            $query->select('cons_code')
                                  ->from('randomization_staff_second')
                                  ->where('state_id','=',$state_id)
                                  ->where('dist_code','=',$dist_code)
                                  ->where('cons_code','=',$cons_code);
                        })
                        ->select('constituencies.cons_name','districts.dist_name')
                        ->where('constituencies.state_id','=',$state_id)
                        ->where('constituencies.dist_code','=',$dist_code)
                        ->where('constituencies.cons_code','=',$cons_code)
                        ->first();
                if($secondstaff){
                    $return_staff['tag'] = 1;
                    $msg['poll_building'] = 'pending';
                    $msg['ps_id'] = 0;
                    $return_staff['data'][] = $msg;
                }
                else{
                    if($third_staff_time <= $today){
                        $thirdsecondlist = DB::table('constituencies')
                                    ->join('districts','districts.dist_code','=','constituencies.dist_code')
                                    ->whereNotIn('constituencies.cons_code', function($query) use($state_id, $dist_code, $cons_code)
                                    {
                                        $query->select('cons_code')
                                              ->from('randomization_staff_third')
                                              ->where('state_id','=',$state_id)
                                              ->where('dist_code','=',$dist_code)
                                              ->where('cons_code','=',$cons_code);
                                    })
                                    ->select('constituencies.cons_name','districts.dist_name')
                                    ->where('constituencies.state_id','=',$state_id)
                                    ->where('constituencies.dist_code','=',$dist_code)
                                    ->where('constituencies.cons_code','=',$cons_code)
                                    ->first();
                        if($thirdsecondlist){
                            $return_staff['tag'] = 1;
                            $msg['poll_building'] = 'pending';
                            $msg['ps_id'] = 0;
                            $return_staff['data'][] = $msg;
                        }
                        else{
                            $return_staff['tag'] = 0;
                            $msg['poll_building'] = 'done';
                            $msg['ps_id'] = 0;
                            $return_staff['data'][] = $msg;
                        }
                    }
                    else{
                        $return_staff['tag'] = 1;
                        $msg['poll_building'] = 'Due date '.$third_staff;
                        $msg['ps_id'] = 0;
                        $return_staff['data'][] = $msg;
                    }
                }
            }
            else{
                $return_staff['tag'] = 1;
                $msg['poll_building'] = 'Due date '.$second_staff;
                $msg['ps_id'] = 0;
                $return_staff['data'][] = $msg;
            }
            }
        }
        else{
            $return_staff['tag'] = 1;
            $msg['poll_building'] = 'Due date '.$first_staff;
            $msg['ps_id'] = 0;
            $return_staff['data'][] = $msg;
        }
        $election_material = DB::table('poll_booths')
            ->whereNotIn('bid', function($query) use($state_id, $dist_code, $cons_code)
            {
                $query->select('bid')
                      ->from('pro_activity_before')
                      ->where('state_id','=',$state_id)
                      ->where('dist_code','=',$dist_code)
                      ->where('cons_code','=',$cons_code)
                      ->where('election_material','=',1);
            })
            ->select('bid','poll_building','poll_building_detail','ps_id')
            ->where('state_id','=',$state_id)
            ->where('dist_code','=',$dist_code)
            ->where('cons_code','=',$cons_code)
            ->get();
        if($election_material->count()){
            $return_material['tag'] = 1;
            foreach ($election_material as $materials) {
                $material_array['poll_building'] = $materials->poll_building.' '.$materials->poll_building_detail;
                $material_array['ps_id'] = $materials->ps_id;
                $bind_material_array[] = $material_array;
            }

            $return_material['data'] = $bind_material_array;
        }
        else{
            $return_material['tag'] = 0;
            $msg['poll_building'] = '';
            $msg['ps_id'] = 0;
            $return_material['data'][] = $msg;
        }

        $party_reached = DB::table('poll_booths')
            ->whereNotIn('bid', function($query) use($state_id, $dist_code, $cons_code)
            {
                $query->select('bid')
                      ->from('pro_activity_before')
                      ->where('state_id','=',$state_id)
                      ->where('dist_code','=',$dist_code)
                      ->where('cons_code','=',$cons_code)
                      ->where('party_reached','=',1);
            })
            ->select('bid','poll_building','poll_building_detail','ps_id')
            ->where('state_id','=',$state_id)
            ->where('dist_code','=',$dist_code)
            ->where('cons_code','=',$cons_code)
            ->get();
        if($party_reached->count()){
            $return_party['tag'] = 1;
            foreach ($party_reached as $party) {
                $party_array['poll_building'] = $party->poll_building.' '.$party->poll_building_detail;
                $party_array['ps_id'] = $party->ps_id;
                $bind_party_array[] = $party_array;
            }

            $return_party['data'] = $bind_party_array;
        }
        else{
            $return_party['tag'] = 0;
            $msg['poll_building'] = '';
            $msg['ps_id'] = 0;
            $return_party['data'] = $msg;
        }

        $evm_received = DB::table('poll_booths')
            ->whereNotIn('bid', function($query) use($state_id, $dist_code, $cons_code)
            {
                $query->select('bid')
                      ->from('pro_activity_before')
                      ->where('state_id','=',$state_id)
                      ->where('dist_code','=',$dist_code)
                      ->where('cons_code','=',$cons_code)
                      ->where('evm_received','=',1);
            })
            ->select('bid','poll_building','poll_building_detail','ps_id')
            ->where('state_id','=',$state_id)
            ->where('dist_code','=',$dist_code)
            ->where('cons_code','=',$cons_code)
            ->get();
        if($evm_received->count()){
            $return_evm_status['tag'] = 1;
            foreach ($evm_received as $evm) {
                $evm_array['poll_building'] = $evm->poll_building.' '.$evm->poll_building_detail;
                $evm_array['ps_id'] = $evm->ps_id;
                $bind_evm_array[] = $evm_array;
            }

            $return_evm_status['data'] = $bind_evm_array;
        }
        else{
            $return_evm_status['tag'] = 0;
            $msg['poll_building'] = '';
            $msg['ps_id'] = 0;
            $return_evm_status['data'] = $msg;
        }

        $return_array['evm_randomization'] = $return_evm;
        $return_array['staff_randomization'] = $return_staff;
        $return_array['election_material'] = $return_material;
        $return_array['party_reached'] = $return_party;
        $return_array['evm_received'] = $return_evm_status;
        $return = array('result' => $return_array,'status_code'=>200);
        exit(json_encode($return));
    }



    public function notification($lastID)
    
    {
        $get = DB::table('bookinghistory')->join('usersToken', 'bookinghistory.driverId', '=', 'usersToken.userId')->where('bId',$lastID )->first();
        $passenger = DB::table('bookinghistory')->join('users', 'bookinghistory.userId', '=', 'users.id')->where('bId',$lastID )->first();
        $count=count($get);
        if($count > 0) {
            $message = $passenger->name." has booked new ride";
            if($get->type == 'a') {
                $path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';
                
                $fields = array(
                   'to' => $get->token,
                   'notification' => array('title' => 'Book Your Cab', 'body' => $message, 'tag'=> 'Testing'),
                   'data' => array('message' => $message,'passengerName' => $passenger->name,'contactNumber' => $passenger->mobile,'picklocation' => $passenger->picklocation,'droplocation' => $passenger->droplocation,'currentTime' => $passenger->originaltime,'rideType' => $passenger->rideType,'bookingId' => $lastID,'response' => 'driver')
               );
               $headers = array(
                   'Authorization:key=' . 'AIzaSyDOo2fq1HnXRU3x0oT3u9PL-TzbYVT4cD0',
                   'Content-Type:application/json'
               );       
                $ch = curl_init();
         
               curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm); 
               curl_setopt($ch, CURLOPT_POST, true);
               curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
               curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
               curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
               curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 ); 
               curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
           
               $result = curl_exec($ch);
              
               curl_close($ch);
               
                // close android
               
              // print_r($result);
               
               
            }
            
            // start Iphone
            else{
                
                $deviceToken = $get->token;
               
               try {

                $passphrase = '1234';
               
                //$path = "http://" . $_SERVER['SERVER_NAME'].'/bookyourcab/public/uploads/bookyourcab.pem';
                   $path = public_path().'/uploads/bookyourcab.pem';
                   $ctx = stream_context_create();
                   
                   stream_context_set_option($ctx, 'ssl', 'local_cert', $path);
                   stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

                $fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
                
                   //$fp = stream_socket_client('ssl://gateway.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
                   
                   if (!$fp)
                       exit("Failed to connect: $err $errstr" . PHP_EOL);
                   
                   
                   $apple_push = 'Connected to APNS' . PHP_EOL;
                   
                   $body['aps'] = array(
                       'alert' => $message,
                       'sound' => 'default',
                       'passengerName' => $passenger->name,
                       'contactNumber' => $passenger->mobile,
                       'picklocation' => $passenger->picklocation,
                       'droplocation' => $passenger->droplocation,
                       'currentTime' => $passenger->originaltime,
                       'rideType' => $passenger->rideType,
                       'bookingId' => $lastID,
                       'response' => 'driver'
                   );
                   
                   $payload = json_encode($body);
                   
                   $result = array();
                   
                   $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

                   // print_r($msg);
                   // die();
               
                $result = fwrite($fp, $msg, strlen($msg));

                fclose($fp);
               
               } catch (Exception $e) {

                $errorexception = $e;
                
               }     

            // End Iphone
            }

        }

    }


}