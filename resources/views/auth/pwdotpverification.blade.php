@extends('layouts.applogin')

@section('content')
<div class="login-form">
	<form role="form" method="POST" action="{{ url('/enterotp') }}" autocomplete="off">
		{{ csrf_field() }}
		<div class="top">
			<img src="{{ URL::asset('images/siteLogo.png')}}" alt="icon" class="icon" />
			<h1>OTP</h1>
		</div>
		<div class="form-area">
			<p style="color:red"><?php echo Session::get('opterror') ?></p>
			<div class="group{{ $errors->has('otp') ? ' has-error' : '' }}">

				<input id="otp" type="text" class="form-control" autocomplete="off" placeholder="Enter Your OTP" name="otp" value="{{ old('otp') }}" autofocus>
				<i class="fa fa-key"></i>

				@if ($errors->has('otp'))
					<span class="help-block">
						<strong>{{ $errors->first('otp') }}</strong>
					</span>
				@endif
			</div>
			<button type="submit" class="btn btn-default btn-block">OTP Verification</button>
		</div>
	</form>
	
	<form class="form-horizontal" role="form" method="POST" action="resendotppwd" id="pwd_resend_otp">
		{{ csrf_field() }}
		<input type="hidden" name="id" value="ss" >
		<div class="footer-links text-center">
			<button type="submit" class="btn-text"><i class="fa fa-repeat"></i> Resend OTP</button>
		</div>		
	</form>
               
</div>

@endsection
