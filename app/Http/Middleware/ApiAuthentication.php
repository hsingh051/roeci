<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Hash;

class ApiAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
		$authorizaation_key = $_SERVER['HTTP_AUTHORIZATION'];
        $timestamp = $_SERVER['REQUEST_TIME'];
        //$authorizaation_key = "MDFTeW5lcmd5OnJIdVtxIzc0Rk06OTQxNzQ5Nzc1OToxNDgxMDIyNjU4";
        //$authorizaation_key = "MDFTeW5lcmd5ejpySHVbcSM3NEZNOjk3ODAwODQ4NDg6MTQ4MTI2Mzg3Nw==";
        //$authorizaation_key = "cHJlbG9naW46ckh1W3EjNzRGTTo5NzgwMDg0ODQ4OjE0ODEyNjQyODM=";
        //$authorizaation_key = "cHJlbG9naW46ckh1W3EjNzRGTTo5NDYzMjQzMDM0OjE0ODU4NTA2NzM=";

        if(!empty($authorizaation_key)){

            $decoded_key = base64_decode($authorizaation_key);
            $decoded_value = explode(':',$decoded_key);
            $requested_time = $decoded_value[3];
            $timediff = $timestamp-$requested_time;
            $phone = $decoded_value[2];
            if($timediff < 300){
                if($decoded_value[1] == 'rHu[q#74FM'){
                    $user = DB::table('users')->where('phone', $phone)->first();
                    $polldayuser = DB::table('users_pollday')->where('phone', $phone)->first();
                    $mediauser = DB::table('users_media')->where('uid', $phone)->orWhere('phone', $phone)->first();
                    $password = $decoded_value[0];
                    if(@$user){

                        if($password == 'prelogin'){
                            return $next($request);
                        }
                        else{
                            if (Hash::check($password, $user->password)) {
                                return $next($request);
                            }

                            else{
                                $return = array('result' => 'UNAUTHORIZED','status_code'=>401);
                                exit(json_encode($return));
                            }
                        }
                    }
                    elseif(@$polldayuser){
                        if($password == 'prelogin'){
                            return $next($request);
                        }
                        else{
                            $makepassword = 'Zk5z[('.$request->password.'B4[hY6';
                            $decrypted_password = md5($makepassword);
                            $checkpassword = DB::table('users_pollday')->where('phone', $phone)->where('password', $decrypted_password)->first();
                            if ($checkpassword) {
                                return $next($request);
                            }

                            else{
                                $return = array('result' => 'UNAUTHORIZED','status_code'=>401);
                                exit(json_encode($return));
                            }
                        }
                    }
                    elseif(@$mediauser){
                        if($password == 'prelogin'){
                            return $next($request);
                        }
                        else{

                            $makepassword = $request->password;

                            $decrypted_password = md5($makepassword);
                            $matchThese = ['uid' => $phone, 'password' => $decrypted_password];
                            $orThose = ['phone' => $phone, 'password' => $decrypted_password];
                            $checkpassword = DB::table('users_media')->where($matchThese)->orWhere($orThose)->first();
                            if ($checkpassword) { 
                                return $next($request);
                            }

                            else{
                                $return = array('result' => 'UNAUTHORIZED','status_code'=>401);
                                exit(json_encode($return));
                            }
                        }
                    }
                    else{
                        $return = array('result' => 'UNAUTHORIZED','status_code'=>401);
                        exit(json_encode($return)); 
                    }
                }
                else{
                    $return = array('result' => 'UNAUTHORIZED','status_code'=>401);
                    exit(json_encode($return)); 
                }
            }
            else{
                $return = array('result' => 'UNAUTHORIZED','status_code'=>401);
                exit(json_encode($return)); 
            }
        }
        else{
            $return = array('result' => 'UNAUTHORIZED','status_code'=>401);
            exit(json_encode($return));
        }
    }
}
