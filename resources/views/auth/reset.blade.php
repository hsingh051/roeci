@extends('layouts.applogin')

@section('content')
<div class="login-form">
	<form class="form-horizontal" role="form" method="POST" action="{{ url('/resetpassword') }}" autocomplete="off">
	
		<div class="top">
			<img src="{{ URL::asset('images/siteLogo.png')}}" alt="icon" class="icon" />
			<h1>Reset Password</h1>
		</div>
		
		{{ csrf_field() }}
		<div class="form-area">
			<div class="group{{ $errors->has('password') ? ' has-error' : '' }}">
				<input id="password" type="password" class="form-control" placeholder="Password" name="password" autocomplete="off" required>
				<i class="fa fa-key"></i>

				@if ($errors->has('password'))
					<span class="help-block">
						<strong>{{ $errors->first('password') }}</strong>
					</span>
				@endif
			</div>

			<div class="group">
				<input id="password-confirm" type="password" class="form-control" autocomplete="off" placeholder="Confirm Password" name="password_confirmation" required>
				<i class="fa fa-key"></i>

				@if ($errors->has('confirmed'))
					<span class="help-block">
						<strong>{{ $errors->first('confirmed') }}</strong>
					</span>
				@endif
			</div>
			
			<button type="submit" class="btn btn-default btn-block">Reset Password</button>
		</div>
	</form>
</div>
@endsection


