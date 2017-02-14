@extends('layouts.applogin')

@section('content')
<div class="login-form">
	<form role="form" method="POST" action="{{ url('/select_state') }}">
		{{ csrf_field() }}
		<div class="top">
			<img src="{{ URL::asset('images/siteLogo.png')}}" alt="icon" class="icon" />
			<h1>Select State</h1>
		</div>
		<div class="form-area">
			<div class="group{{ $errors->has('state_id') ? ' has-error' : '' }}">
				<select class="form-control" name="state_id">
					<option value="">Select State</option>
					<?php foreach($states as $state){?>
						<option value="{{$state->StateID}}">{{$state->StateName}}</option>	
					<?php }?>
				</select>
				@if ($errors->has('state_id'))
					<span class="help-block">
						<strong>{{ $errors->first('state_id') }}</strong>
					</span>
				@endif
				
			</div>
			<button type="submit" class="btn btn-default btn-block">Select State</button>
		</div>
	</form>
	
	<form class="form-horizontal" role="form" method="POST" action="resendotp" id="resend_otp">
		{{ csrf_field() }}
		<input type="hidden" name="id" value="ss" >
		<div class="footer-links text-center">
		</div>		
	</form>
               
</div>

@endsection
