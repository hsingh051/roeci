@extends('layouts.applogin')

@section('content')
<div class="login-form">
	<form role="form" method="POST" action="{{ url('/postlogin') }}" autocomplete='off' enctype="x-www-urlencoded">
		<div class="top">
			<img src="{{ URL::asset('images/siteLogo.png')}}" alt="icon" class="icon" />
			<h1>Login</h1>
		</div>
		{{ csrf_field() }}
		<span class="help-block">
			<strong>{{ Session::get('username') }}{{ Session::get('mismatch') }}</strong>
		</span>
		<div class="form-area">
			<div class="group{{ $errors->has('phone') ? ' has-error' : '' }}">
				<input id="phone" type="text" class="form-control" placeholder="Enter Mobile No." name="phone" value="{{ old('phone') }}" autocomplete="off">
				<i class="fa fa-user"></i>

				@if ($errors->has('phone'))
					<span class="help-block">
						<strong>{{ $errors->first('phone') }}</strong>
					</span>
				@endif
			</div>

			<div class="group{{ $errors->has('password') ? ' has-error' : '' }}">
				<input id="password" onchange ="disabledkey()" type="password" class="form-control" placeholder="PIN" name="password" autocomplete="off" >
				<i class="fa fa-key"></i>

				@if ($errors->has('password'))
					<span class="help-block">
						<strong>{{ $errors->first('password') }}</strong>
					</span>
				@endif
			</div>

			<!-- <div class="checkbox checkbox-primary">
				<input type="checkbox" name="remember" id="rememberMe" />
				<label for="rememberMe"> Remember Me</label>
			</div> -->
			<button type="submit" class="btn btn-default btn-block">Login</button>
		</div>
	</form>
	
	<div class="footer-links text-center">
		<a href="{{ url('/forget') }}"><i class="fa fa-lock"></i> Forgot Your Password?</a>
	</div>
</div>
@endsection
