@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
    <?php
    $state= Auth::user()->state_id;
    $dist = Auth::user()->dist_code;
    ?>
	<div class="container-widget">  
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					@if(Session::has('boothAwareErr'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('boothAwareErr') }}</p>
					@endif
					<div class="panel-title pageTitle titleBtn text-center">
						<span>Add Booth Awareness Group</span>
						<a href="{{ url('/deo/booth-aware-list') }}" class="formBackBtn btn btn-default">Back</a>
					</div>
					<div class="panel-body">
						<div class="noticeCandi">
							<form enctype="multipart/form-data" method="post" action="{{url('deo/addBoothAwareSub') }}" >
							<input type="hidden" name="_token" value="<?= csrf_token(); ?>">

								<div class="form-group{{ $errors->has('boothAwareName') ? ' has-error' : '' }}">
									<label class="form-label">Group Name</label>
									<input type="text" name="boothAwareName" class="form-control" placeholder="Group Name" value="{{ old('boothAwareName') }}"/>
									@if ($errors->has('boothAwareName'))
										<span class="help-block">
											<strong>{{ $errors->first('boothAwareName') }}</strong>
										</span>
									@endif
								</div>
								<div class="form-group{{ $errors->has('boothAwarePhone') ? ' has-error' : '' }}">
									<label class="form-label">Mobile Number</label>
									<input onkeypress="return isNumber(event)" type="text" name="boothAwarePhone" class="form-control" placeholder="Mobile Number" value="{{ old('boothAwarePhone') }}"/>
									@if ($errors->has('boothAwarePhone'))
										<span class="help-block">
											<strong>{{ $errors->first('boothAwarePhone') }}</strong>
										</span>
									@endif
								</div>
								<div class="form-group{{ $errors->has('boothAwareAddress') ? ' has-error' : '' }}">
									<label class="form-label">Address</label>
									<textarea name="boothAwareAddress" class="form-control" placeholder="Address" >{{ old('boothAwareAddress') }}</textarea>
									@if ($errors->has('boothAwareAddress'))
										<span class="help-block">
											<strong>{{ $errors->first('boothAwareAddress') }}</strong>
										</span>
									@endif
								</div>
								<div class="form-group{{ $errors->has('boothAwarePollNum') ? ' has-error' : '' }}">
									<label class="form-label">Polling Station Number</label>
									<input onkeypress="return isNumber(event)" type="text" name="boothAwarePollNum" class="form-control" placeholder="Polling Station Number" value="{{ old('boothAwarePollNum') }}"/>
									@if ($errors->has('boothAwarePollNum'))
										<span class="help-block">
											<strong>{{ $errors->first('boothAwarePollNum') }}</strong>
										</span>
									@endif
								</div>
								<div class="form-group{{ $errors->has('boothAwareCons') ? ' has-error' : '' }}">
									<label class="form-label">Select Constituency</label>
									<select name="boothAwareCons" type="file" class="form-control" />
										<?php echo get_constituencies($state,$dist); ?>
									</select>
									@if ($errors->has('boothAwareCons'))
										<span class="help-block">
											<strong>{{ $errors->first('boothAwareCons') }}</strong>
										</span>
									@endif
								</div>
								<div class="form-group{{ $errors->has('boothAwareDesig') ? ' has-error' : '' }}">
									<label class="form-label">Designation</label>
									<input type="text" name="boothAwareDesig" class="form-control" placeholder="Designation" value="{{ old('boothAwareDesig') }}"/>
									@if ($errors->has('boothAwareDesig'))
										<span class="help-block">
											<strong>{{ $errors->first('boothAwareDesig') }}</strong>
										</span>
									@endif
								</div>
								<div class="form-group{{ $errors->has('boothAwareOrg') ? ' has-error' : '' }}">
									<label class="form-label">Organisation</label>
									<input type="text" name="boothAwareOrg" class="form-control" placeholder="Organisation" value="{{ old('boothAwareOrg') }}"/>
									@if ($errors->has('boothAwareOrg'))
										<span class="help-block">
											<strong>{{ $errors->first('boothAwareOrg') }}</strong>
										</span>
									@endif
								</div>
								<button type="submit" class="btn btn-default">Submit</button>
							</form>
						</div>
					</div>
				</div>
			</div>		
		</div>  
	</div>
	<script type="text/javascript">
		function isNumber(evt) {
			var charCode = (evt.which) ? evt.which : event.keyCode
			if (charCode != 43 && charCode > 31 && (charCode < 48 || charCode > 57))
			return false;
			return true;
		}
	</script>
	<!-- END CONTAINER -->
@endsection

