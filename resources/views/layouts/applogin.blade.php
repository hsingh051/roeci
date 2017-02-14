<!DOCTYPE html>
<html lang="en">
<head>
<!-- sdds-->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="X-Frame-Options" content="DENY">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="cache-control" content="private, max-age=0, no-cache">
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="expires" content="Sat, 26 Jul 1997 05:00:00 GMT">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="css/app.css" rel="stylesheet">
	<link rel="stylesheet" href="{{ URL::asset('css/root-login.css')}}" />
    <script type="text/javascript">  window.history.forward(); function noBack() { window.history.forward(); } </script>
    <!-- Scripts -->
    <script src="{{ URL::asset('js/jquery-1.9.1.js')}}"></script>
    <script src="{{ URL::asset('js/core.js')}}"></script>
    <script src="{{ URL::asset('js/aes.js')}}"></script>
    <script src="{{ URL::asset('js/sha256.js')}}"></script>

    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
    <script>if (top!=self) top.location.href=self.location.href</script>
    <script type="text/javascript">
	    $(document).ready(function() {
	        $("#phone").keypress(function (evt) { 
	             var charCode = (evt.which) ? evt.which : evt.keyCode;

	             if (charCode != 46 && charCode > 31 && (charCode < 43 || charCode > 57)){
	                return false;
	             }else{ 
	                return true;
	             }
	        });
	    });
	</script>
<script>
$(document).keydown(function(event){
    if(event.keyCode==123){
        return false;
    }
    else if (event.ctrlKey && event.shiftKey && event.keyCode==73){        
             return false;
    }
});
$(document).ready(function(){
    $(document).on("contextmenu",function(e){
        if(e.target.nodeName != "INPUT" && e.target.nodeName != "TEXTAREA")
             e.preventDefault();
     });
 });
function disabledkey() {
    var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9\+\/\=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/\r\n/g,"\n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}

var d = new Date();
var n = d.getTime();
var val = document.getElementById("password").value;
var newpas = n + 'r5(Sb$|||@' + val;
var encodedString = Base64.encode(newpas);
// var key = CryptoJS.enc.Hex.parse("0123456789abcdef0123456789abcdef");
// var iv =  CryptoJS.enc.Hex.parse("abcdef9876543210abcdef9876543210");
// var encrypted = CryptoJS.AES.encrypt(val, key, {iv:iv});
// encrypted = encrypted.ciphertext.toString(CryptoJS.enc.Base64);

// document.getElementById("password").value = encrypted;
document.getElementById("password").value = encodedString;
}
</script>
</head>
<body class="whiteBg" onload="noBack();" onpageshow="if (event.persisted) noBack();" onunload="if (event.persisted) noBack();">
	<div id="top" class="clearfix">
		<!-- Start App Logo -->
		<div class="applogo">
			<a href="{{ url('/') }}" class="logo">ECI</a>
		</div>
		<!-- End App Logo -->
	</div>
	
	@yield('content')
	
	<!-- Start Footer -->
	<div class="row footer staticFtr">
		<div class="col-md-6 text-left">
			© Copyright 2016, Election Commision of India.
		</div>
		<div class="col-md-6 text-right">
			Design and Developed by 01 Synergy
		</div> 
	</div>
	<!-- End Footer -->

    <!-- Scripts -->
	
    <script src="js/app.js"></script>
	<script type="text/javascript" src="{{ URL::asset('js/bootstrap/bootstrap.min.js')}}"></script> 
	<script type="text/javascript" src="{{ URL::asset('js/bootstrap-toggle/bootstrap-toggle.min.js')}}"></script> 
	<script type="text/javascript" src="{{ URL::asset('js/plugins.js')}}"></script> 
	<script type="text/javascript">
        $(function(){
        	console.log($('#resend_otp'));
            $('#pwd_resend_otp').on('submit',function(e){
                e.preventDefault();
                console.log($(this).serialize());
                $.post('http://localhost/eci/public/resendotppwd', $(this).serialize(), function(data) { 
                        
                });

               
            });

        });
    </script>

    <script type="text/javascript">
        $(function(){
            console.log($('#resend_otp'));
            $('#resend_otp').on('submit',function(e){
                e.preventDefault();
                console.log($(this).serialize());
                $.post('http://localhost/eci/public/resendotp', $(this).serialize(), function(data) { 
                        
                });

               
            });

        });
    </script>
</body>
</html>
