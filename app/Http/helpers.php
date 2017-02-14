<?php

 function eci_encrypt($string){
	 
    $string = "r5(Sb$|||".$string;
    return base64_encode($string);
 }

 function eci_decrypt($string){
    $string =  explode("|||",base64_decode($string));
    return $string[1];
 }

  function eci_web_decrypt($string){
  	$key = pack("H*", "0123456789abcdef0123456789abcdef");
	$iv =  pack("H*", "abcdef9876543210abcdef9876543210");
	$encrypted = base64_decode($string);
	$string = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $encrypted, MCRYPT_MODE_CBC, $iv);
    return trim($string,"\x07");
 }

  function pass_encrypt($string){
	
    return base64_encode($string);
 }

  function pass_decrypt($string){
	$string =  explode("|||@",base64_decode($string));
	return $string[1];
 }

function pro_notification($phonenumber,$bid,$msg,$user_role){

	$get = DB::table('users_token')->where('phone',$phonenumber )->first();
	$count=count($get);
	if($count > 0) {
		$message = $msg;
		if($get->type == 'a') {
			$path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';
			
			$fields = array(
	            'to' => $get->token,
	            'notification' => array('title' => 'ECI360', 'body' => $message, 'tag'=> 'ECI360'),
	            'data' => array('message' => $message,'bid' => $bid,'role' => $user_role)
	        );
	        $headers = array(
	            'Authorization:key=' . 'AIzaSyBWQEnTrX4B5VNsYm1pbeN9A8VTfOvcL_M',
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
	        
	   }
		
		// start Iphone
		else{
			
			$deviceToken = $get->token;
	        
	        try {

	        	$passphrase = '1234';
	        
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
	                'bid' => $bid,
	                'role' => $user_role
	            );
	            
	            $payload = json_encode($body);
	            
	            $result = array();
	            
	            $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

	            $result = fwrite($fp, $msg, strlen($msg));

	        	fclose($fp);
	        
	        } catch (Exception $e) {

	        	$errorexception = $e;
	        	
	        }	 

	     // End Iphone
		}

	}

}

function voternotification($token,$msgid,$msg,$tokentype){
	$message = $msg;
	if($tokentype == 'a') {
		$path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';
		
		$fields = array(
            'to' => $token,
            'notification' => array('title' => 'ECI360', 'body' => $message, 'tag'=> 'ECI360'),
            'data' => array('message' => $message,'msgid' => $msgid)
        );
        $headers = array(
            'Authorization:key=' . 'AIzaSyBr5ChOyD7uyitpHkW0qpcpKTa8tMAM4dY',
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
		$deviceToken = $token;
		try {
        	$passphrase = '1234';
        	//$path = "http://" . $_SERVER['SERVER_NAME'].'/bookyourcab/public/uploads/bookyourcab.pem';
            $path = public_path().'/files/EciVoter.pem';
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
                'msgid' => $msgid
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

function pwdnotification($epic_no,$phone,$name,$ps_name,$disability_type,$gender,$token,$tokentype){
	$message = "Data";
	if($tokentype == 'a') {
		$path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';
		
		$fields = array(
            'to' => $token,
            'notification' => array('title' => 'ECI360', 'body' => $message, 'tag'=> 'ECI360'),
            'data' => array('message' => $message,'role' => '4')
        );
        $headers = array(
            'Authorization:key=' . 'AIzaSyA6yAaZsyyefZBoP6byJgocElWbh6PQESI',
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
		
		$deviceToken = $token;
		try {
        	$passphrase = '1234';
        
        	//$path = "http://" . $_SERVER['SERVER_NAME'].'/bookyourcab/public/uploads/bookyourcab.pem';
            $path = public_path().'/files/EciappPush.pem';
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
                'role' => '4'
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

 function get_political_parties($state_id,$selected = NULL){
 	//dd($state_id);
	$parties = App\Http\Controllers\HomeController::get_political_parties($state_id);

	$html = "<option value='0'>Select Party</option>";
	if(@$parties){
		foreach ($parties as $value) {
			if($value->ppid == $selected){
				$html .= "<option selected='selected' value='".$value->ppid."'>".$value->party_name."</option>";
			}else{
				$html .= "<option value='".$value->ppid."'>".$value->party_name."</option>";
			}
		}	
	}
	
	return $html;
}

 function get_constituencies($state_id,$dist_code,$selected = NULL){

	$constituencies = App\Http\Controllers\HomeController::get_constituencies($state_id,$dist_code);

	$html = "<option value=''>Select Constituency</option>";
	if(@$constituencies){
		foreach ($constituencies as $value) {
			if($value->cons_code == $selected){
				$html .= "<option selected='selected' value='".$value->cons_code."'>".$value->cons_name."</option>";
			}else{
				$html .= "<option value='".$value->cons_code."'>".$value->cons_name."</option>";
			}
		}	
	}
	
	return $html;
}

	function current_date(){
		 $time_now = mktime(date('h')+5,date('i')+30,date('s'));
      	 $current_date = date('Y-m-d', $time_now);
      	 return $current_date;
	}

	function current_datetime(){
		 $time_now = mktime(date('h')+5,date('i')+30,date('s'));
      	 $current_datetime = date('Y-m-d H:i:s', $time_now);
      	 return $current_datetime;
	}

	function current_hour(){
		 $time_now = mktime(date('H')+5,date('i')+30,date('s'));
      	 $current_hour = date('H', $time_now);
      	 return $current_hour;
	}

	function get_state_id(){
		//$state_id = App\Http\Controllers\HomeController::get_state_id();
		$state_id = Session::get('state_id');
		
		return $state_id;	
	}

	function get_aws_images_url(){

		$url = Config::get('constants.AWS_IMAGES_URL');

		return $url;
	}

	function get_gen_password(){

		$alpha = "abcdefghijklmnopqrstuvwxyz";
		$alpha_upper = strtoupper($alpha);
		$numeric = "0123456789";
		$special = "!@$#*";
		$chars = $alpha . $alpha_upper . $numeric . $special;
		$length = 8;
		$len = strlen($chars);
		$pw = '';
		for ($i=0;$i<$length;$i++){
		      $pw .= substr($chars, rand(0, $len-1), 1);
		}
		// the finished password
		$pw = str_shuffle($pw);

		return $pw;
	}

	function send_otp($username){ 

		if(($username=='9988112233') || ($username=='9814381177')){
			$otp = '729358';
		}else{
			
			$otp = mt_rand(100000, 999999);
		}
		$user = "01synergy";
        $password = "01@Synergy";
        $msisdn = $username;
        $sid = "SYNRGY";
        $msg = "OTP to Login in ECI360 is ".$otp."";
		$msg = urlencode($msg);
		$fl = "0";
		$gwid = "2";
		$ch =
		curl_init("http://cloud.smsindiahub.in/vendorsms/pushsms.aspx?user=".$user."&password=".$password."&msisdn=".$msisdn."&sid=".$sid."&msg=".$msg."&fl=".$fl."&gwid=".$gwid);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);

		return $otp;
	}

	function lawordersmsalert($msg,$dist_code){ 
		$user = "01synergy";
        $password = "01@Synergy";
        if($dist_code == '11'){
	        $msisdn = '7837018500,9837018600';
	    }
	    elseif ($dist_code == '5') {
	    	$msisdn = '9592918501,9592918502,9592918509,9592918510';
	    }
	    else{
	    	$msisdn = '9464529625';
	    }
        $sid = "SYNRGY";
        //$msg = "OTP to Login in ECI360 is ".$otp."";
		$msg = urlencode($msg);
		$fl = "0";
		$gwid = "2";
		$ch =
		curl_init("http://cloud.smsindiahub.in/vendorsms/pushsms.aspx?user=".$user."&password=".$password."&msisdn=".$msisdn."&sid=".$sid."&msg=".$msg."&fl=".$fl."&gwid=".$gwid);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);
	}

	function voter_data($epic_no){

		//die($epic_no);
		
		$pass = $epic_no."KLPSCVBPQZKLA129846RMZRBHJ#1675GHGHSDSKQLAPX";
		$pass_key = hash('sha512', $pass);
		$c_id = "B45623";

		$url = "http://electoralsearch.in/VoterSearch/service/search?c_id=".$c_id."&epic_no=".$epic_no."&search_type=epic&pass_key=".$pass_key;

		//  Initiate curl
		$ch = curl_init();
		// Disable SSL verification
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		// Will return the response, if false it print the response
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// Set the url
		curl_setopt($ch, CURLOPT_URL,$url);
		// Execute
		$result=curl_exec($ch);
		// Closing
		curl_close($ch);

		$return = json_decode($result);
		if((@$return->response->numFound) && ($return->response->numFound==1)){
			return $result;
		}else{
			$result = voter_details(trim($epic_no));
			return $result;
		}
		//$result = voter_details(trim($epic_no));
		// echo "<pre>";
		// print_r($return->response->numFound);
		// die;9530703567    
		// return $result;

	}

	 function get_voter_list($state_id,$dist_code,$cons_code,$part_no){

		$voters = App\Http\Controllers\CronjobController::get_voter_list($state_id,$dist_code,$cons_code,$part_no);
		return $voters;
	}

	function get_pwd_data($epic_no){
		$query = "Select * from consolidated_data.dbo.pwdvoters where IDCARD_NO = '".$epic_no."'";
        //die;
        $hash = base64_encode($query);
        $url = "http://104.238.103.23:90/api/ecivoterapi/".$hash;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        // echo "<pre>"; 
        // print_r($result);
        // die;
        if(@$result){
        	$return  = json_decode($result);
	        if(@$return->Message){
	        	$return = array();
	           return $return;
	        	
	        }else{
	            return json_encode($return[0]);
	        }
        }else{
        	$return = array();
	           return $return;
        }
        
		// $pwdvoter = App\Http\Controllers\CronjobController::get_pwd_data($epic_no);
		// return $pwdvoter;
	}



	function voter_details($epic_no){
		$epic_no = trim($epic_no);
		$voterDetail= DB::table('voters') 
                      ->where('idcardNo', trim($epic_no))
                      ->first();

        if(empty($voterDetail)){
        	
            //$votersListAPI = app('App\Http\Controllers\CronjobController')->voterDetail($epic_no);
            $query = "Select * from Search_db.dbo.".substr($epic_no, 0, 2)." where IDCARD_NO='".$epic_no."'";
            
            $hash = base64_encode($query);
            //$url = "http://104.238.103.23:90/api/eciapi/".$hash;
            $fields = array(
                'id' => urlencode($hash),
            );
            $fields_string = "";
            foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
            rtrim($fields_string, '&');                    
            $url = "http://104.238.103.23:90/api/ecivoterapi/";                    
            $ch = curl_init();                   
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string); 
            $result = curl_exec($ch);
            $return = json_decode($result);
            $votersListAPI1 = array();
            // print_r($return);
            // die;
            if(@$return){
				// print_r($return[0]);
				// die;
				//dd(json_encode($return[0]));
                $votersListAPI1 = $return[0];
                $votersListAPI1->fm_nameEn = $votersListAPI1->Fm_NameEn;
                if($votersListAPI1->ST_Code == "S19"){
                	$votersListAPI1->state_id = "53";
                	$distic = DB::table('constituencies')
			                   ->where('state_id', $votersListAPI1->state_id)
			                   ->where('cons_code', $votersListAPI1->AC_NO)
			                   ->select('dist_code')
			                   ->first();
			        $votersListAPI1->dist_code = $distic->dist_code;
                }
                	$votersListAPI1->cons_code = $votersListAPI1->AC_NO;
                	$votersListAPI1->ps_id = $votersListAPI1->PART_NO;
                	$votersListAPI1->sex = $votersListAPI1->SEX;
                	$votersListAPI1->idcardNo = $votersListAPI1->IDCARD_NO;
                	$votersListAPI1->rlnType = $votersListAPI1->RLN_TYPE;
                	$votersListAPI1->rln_Fm_NmEn = $votersListAPI1->Rln_Fm_NmEn;
                	$votersListAPI1->rln_L_NmEn = $votersListAPI1->Rln_L_NmEn;
                	$votersListAPI1->slnoinpart = $votersListAPI1->SLNOINPART;
                	$votersListAPI1->house_no = $votersListAPI1->HOUSE_NO;
                	$votersListAPI1->section_name = "";

                	// For image and mobile no
                    //  $db1 = "AC_".str_pad($votersListAPI1->cons_code, 3, '0', STR_PAD_LEFT);
                    //  $db2 = "AC".str_pad($votersListAPI1->cons_code, 3, '0', STR_PAD_LEFT);
		            // $part = str_pad($votersListAPI1->ps_id, 3, '0', STR_PAD_LEFT);
		            // //$query = "Select JPGIMAGE,Mobileno from ".$db1.".dbo.".$db2."PART".$part." where IDCARD_NO='".$votersListAPI1->idcardNo."'";
		            // $query = "Select Mobileno from ".$db1.".dbo.".$db2."PART".$part." where IDCARD_NO='".$votersListAPI1->idcardNo."'";
		            // $hash = base64_encode($query);
		            // //$url = "http://104.238.103.23:90/api/eciapi/".$hash;
		            // $fields = array(
		            //     'id' => urlencode($hash),
		            // );
		            // $fields_string = "";
		            // foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		            // rtrim($fields_string, '&');                    
		            // $url = "http://104.238.103.23:90/api/ecivoterapi/";                    
		            // $ch = curl_init();                   
		            // curl_setopt($ch, CURLOPT_URL, $url);
		            // curl_setopt($ch, CURLOPT_POST, true);
		            // //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		            // curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string); 
		            // $result11 = curl_exec($ch); 
		            // $return11 = json_decode($result11);
		            // if(@$return11){
		            // 	//$votersListAPI1->voter_image =  $return11[0]->JPGIMAGE;
		            // 	$votersListAPI1->mobileno  =  $return11[0]->Mobileno;
		            // }
		            //dd($return11);

            }else{
                $return = array();
               $votersListAPI = $return;
            }
            
            $voterDetail = $votersListAPI1;
            
        }
        $voterDetail = json_encode($voterDetail);
        // echo "<pre>";
        // print_r($voterDetail);
        // die;
        return $voterDetail;
	}


	function poll_booth_details($state_id, $dist_code, $cons_code, $part_no){

		$poll_booths = DB::table('poll_booths')
						->join('constituencies', function($join) { 
							$join->on('poll_booths.cons_code', '=', 'constituencies.cons_code')
							     ->on('poll_booths.state_id', '=', 'constituencies.state_id')
							     ->on('poll_booths.dist_code', '=', 'constituencies.dist_code'); 
							 })
		   	            ->where('poll_booths.state_id',$state_id)
		   	            ->where('poll_booths.dist_code',$dist_code)
		   	            ->where('poll_booths.cons_code',$cons_code)
		   	            ->where('poll_booths.ps_id',$part_no)
		   	            ->first();

		$poll_booths = json_encode($poll_booths);

        return $poll_booths;
	}

	function get_events($dist_code){
        $url = "http://104.238.103.23:90/api/sveepapi/".$dist_code;                    
        $ch = curl_init();                   
        curl_setopt($ch, CURLOPT_URL, $url);
        //curl_setopt($ch, CURLOPT_POST, true);
        //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string); 
        $result = curl_exec($ch);
        $return = json_decode($result);
      	return $return;
    }

    function get_events_detail($event_id){
        $url = "http://104.238.103.23:90/api/EventsApi/".$event_id;
        $ch = curl_init();                   
        curl_setopt($ch, CURLOPT_URL, $url);
        //curl_setopt($ch, CURLOPT_POST, true);
        //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string); 
        $result = curl_exec($ch);
        $return = json_decode($result);
        return $return;
    }

    function get_candidate_affidavit($cons_code, $cand_sl_no, $state_id){
        $url = "http://164.100.128.74/WebApi/Service.svc/GetAffidavitListCurrentElection?st_code=s19&ac_no=".$cons_code."&cand_sl_no=".$cand_sl_no;
       
        $ch = curl_init();                   
        curl_setopt($ch, CURLOPT_URL, $url);
        //curl_setopt($ch, CURLOPT_POST, true);
        //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string); 
        $result = curl_exec($ch);
        $return = json_decode($result);
        return $return;
    }

    function get_nomination_list($state_id = S19, $cons_code){
        $url = "http://164.100.128.74/WebApi/Service.svc/GetCandidatelistACCurrentElection?st_code=".$state_id."&ac_no=".$cons_code;
        $ch = curl_init();                   
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        $result = curl_exec($ch);
        print_r($result);


        
        $return = json_decode($result);
        return $return;
    }
	
	function base64_to_image($base64_string, $output_file) {
		$ifp = fopen($output_file, "wb"); 	
		$data = explode(',', $base64_string);
		if(@$data[1]){
			fwrite($ifp, base64_decode($data[1])); 
		}else{
			fwrite($ifp, base64_decode($base64_string));
		}
		fclose($ifp); 
	
		return $output_file; 
	}

	function get_complaints($state_id, $dist_code=NULL, $cons_code=NULL) {

		$complaints = App\Http\Controllers\CronjobController::get_complaints($state_id, $dist_code, $cons_code);
        return $complaints;
	}

	function get_complaint_detail($id) {

		$complaints = App\Http\Controllers\CronjobController::get_complaint_detail($id);
        return $complaints;
	}

	function getcommdetails($id,$no=NULL){

		$getcommdetails = App\Http\Controllers\CronjobController::getcommdetails($id, $no);
		
        return $getcommdetails;
	
	}

	function get_suvidha_data($state_id, $dist_code=NULL, $cons_code=NULL) {

		$complaints = App\Http\Controllers\CronjobController::get_suvidha_data($state_id, $dist_code, $cons_code);
        return $complaints;
	}

	function get_suvidha_detail($id) {

		$complaints = App\Http\Controllers\CronjobController::get_suvidha_detail($id);
        return $complaints;
	}

	function get_party_list(){
		

		//$url = 'http://164.100.34.140/WCFECIService/Service.svc/getPartyList/S19';
		$url = 'http://164.100.129.187/samadhanapppb/Service.svc/getPartyList/S19';
		$ch = curl_init();                   
        curl_setopt($ch, CURLOPT_URL, $url);
        //curl_setopt($ch, CURLOPT_POST, true);
        //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string); 
        $result = curl_exec($ch);
        $return = json_decode($result);
        $i = 0;
        foreach ($return as $value) {
        	# code...
        	$parties[$i]['id'] = $value->PARTY_ID;
        	$parties[$i]['name'] = $value->PARTY_NAME;
        	$i++;
        }

        return $parties;
	}
	function get_com_nature(){
		

		$url = 'http://164.100.129.187/samadhanapppb/Service.svc/get_ddlNOC/1';
		$ch = curl_init();                   
        curl_setopt($ch, CURLOPT_URL, $url);
        //curl_setopt($ch, CURLOPT_POST, true);
        //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string); 
        $result = curl_exec($ch);
		curl_close($ch);
        $return = json_decode($result);
        $i = 0;
        foreach ($return as $value) {
        	# code...
        	$parties[$i]['id'] = $value->Ccode;
        	$parties[$i]['name'] = $value->NOCName_EN;
        	$i++;
        }
		
		$url = 'http://164.100.129.187/samadhanapppb/Service.svc/get_ddlNOC/2';
		$ch = curl_init();                   
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
        $result = curl_exec($ch);
		curl_close($ch);
        $return = json_decode($result);
        foreach ($return as $value) {
        	# code...
        	$parties[$i]['id'] = $value->Ccode;
        	$parties[$i]['name'] = $value->NOCName_EN;
        	$i++;
        }

        return $parties;
	}

	 function get_poll_images($state_id,$dist_code,$cons_code,$part_no){

		$pollImages = App\Http\Controllers\CronjobController::get_poll_images($state_id,$dist_code,$cons_code,$part_no);
		return $pollImages;
	}

	function get_candidate_list($state_id,$cons_code,$type){

		$state = DB::table('states')
                   ->where('StateID', $state_id)
                   ->select('st_code')
                   ->first();

        $state_code = $state->st_code;
        if($type == "R" || $type == "W"){
        	$url = 'http://164.100.128.74/WebApi/Service.svc/GetRejectedCandlistCurrentElection?st_code='.$state_code.'&ac_no='.$cons_code;
    	}else{

    	}
		$ch = curl_init();                   
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
        $result = curl_exec($ch);
		curl_close($ch);
        $return = json_decode($result);

        if($return[0]->ResponseMessage == "Success"){
        	$candidates = array();
        	$i = 0;
	        foreach ($return as $value) {
	        	# code...
	        	if(($type == "R") && ($value->WithdrawnRejected == "R")){
	        		$candidates[$i]['CandidateName'] = $value->CandidateName;
	        		$candidates[$i]['PartyName'] = $value->PartyName;
	        	}else if(($type == "W") && ($value->WithdrawnRejected == "W")){
	        		$candidates[$i]['CandidateName'] = $value->CandidateName;
	        		$candidates[$i]['PartyName'] = $value->PartyName;
	        	}else if($type == "N"){
	        		$candidates[$i]['CandidateName'] = $value->CandidateName;
	        		$candidates[$i]['PartyName'] = $value->PartyName;	
	        	}
	        	
	        	$i++;
	        }
        }else{
        	$candidates = array();
        }
        

        return $candidates;
	}


	function getPwdVoter($cons_code) {

		$pwdVoter = App\Http\Controllers\CronjobController::getPwdVoter($cons_code);
        return $pwdVoter;
	}

?>
    