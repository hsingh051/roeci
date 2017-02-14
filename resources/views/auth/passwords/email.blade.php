@extends('layouts.applogin')

<!-- Main Content -->
@section('content')
<div class="login-form">
	@if (session('status'))
		<div class="alert alert-success">
			{{ session('status') }}
		</div>
	@endif
	<form role="form" method="POST" action="{{ url('/password/email') }}">
		{{ csrf_field() }}
		<div class="top">
			<img src="{{ URL::asset('images/siteLogo.png')}}" alt="icon" class="icon" />
			<h1>Forgot Password</h1>
		</div>
		<div class="form-area">
			<div class="group{{ $errors->has('email') ? ' has-error' : '' }}">
				<input id="email" type="email" class="form-control" name="email" placeholder="E-Mail Address" value="{{ old('email') }}" required>
				<i class="fa fa-envelope"></i>

				@if ($errors->has('email'))
					<span class="help-block">
						<strong>{{ $errors->first('email') }}</strong>
					</span>
				@endif
			</div>
			<button type="submit" class="btn btn-default btn-block">Send Password Reset Link</button>		
		</div>
	</form>
</div>
@endsection
