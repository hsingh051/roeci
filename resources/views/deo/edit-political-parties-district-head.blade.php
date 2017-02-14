@extends('layouts.main')
@section('content')
	<script type="text/javascript">
		function isNumber(evt) {
			var charCode = (evt.which) ? evt.which : event.keyCode
			if (charCode != 43 && charCode > 31 && (charCode < 48 || charCode > 57))
			return false;
			return true;
		}
	</script>
    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					@if(Session::has('editPolDistHeadMsz'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('editPolDistHeadMsz') }}</p>
					@endif
					<div class="panel-title pageTitle titleBtn text-center">
						<span>Add New Political Party District Head</span>
						<a href="{{URL::previous()}}" class="formBackBtn btn btn-default">Back</a>
					</div>
					<div class="panel-body">
						<div class="noticeCandi">
							<form enctype="multipart/form-data" method="post" action="{{url('deo/editPPdistHeadSubmit') }}" >
							<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
								<div class="form-group{{ $errors->has('editPartyName') ? ' has-error' : '' }}">
									
									<label class="form-label">Party Name</label>
									<select name="editPartyName" class="form-control">
										<option value="">Select Party</option>
										@foreach($polParty as $polPartys)
										<?php $encryptPpid=eci_encrypt($polPartys->ppid)?>
										<option value="<?php echo $encryptPpid; ?>" <?php if(($polPartys->ppid)==($polPartyDetail->ppid)){ echo "selected"; } ?> >{{ $polPartys->party_name }}</option>
										@endforeach
									</select>
									@if ($errors->has('editPartyName'))
										<span class="help-block">
											<strong>{{ $errors->first('editPartyName') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('editOfficeAddress') ? ' has-error' : '' }}">
									<label class="form-label">Office Address</label>
									<input type="text" class="form-control" value="{{ $polPartyDetail->office_address }}" name="editOfficeAddress" placeholder="Enter Office Address" />
									@if ($errors->has('editOfficeAddress'))
										<span class="help-block">
											<strong>{{ $errors->first('editOfficeAddress') }}</strong>
										</span>
									@endif
								</div>	

								<div class="form-group{{ $errors->has('editOfficeNumber') ? ' has-error' : '' }}">
									<label class="form-label">Office Number</label>
									<input type="text" value="{{ $polPartyDetail->office_phone }}" name="editOfficeNumber" onkeypress="return isNumber(event)" class="form-control" placeholder="Enter Office Number" />
									@if ($errors->has('editOfficeNumber'))
										<span class="help-block">
											<strong>{{ $errors->first('editOfficeNumber') }}</strong>
										</span>
									@endif
								</div>

								<div id="addMoreDist" class="addMoreBox">
									<div class="CloneDistHead cloneBox">
										<div class="form-group{{ $errors->has('editDistHeadName') ? ' has-error' : '' }}">
											<label class="form-label">District Head Name</label>
											<input type="text" value="{{ $polPartyDetail->name }}" name="editDistHeadName" class="form-control" placeholder="Enter District Head Name" />
											@if ($errors->has('editDistHeadName'))
											<span class="help-block">
												<strong>{{ $errors->first('editDistHeadName') }}</strong>
											</span>
											@endif
										</div>
										<div class="form-group{{ $errors->has('editPrimaryMob') ? ' has-error' : '' }}">
											<label class="form-label">Primary Mobile No.</label>
											<input type="text" value="{{ $polPartyDetail->phone }}" name="editPrimaryMob" onkeypress="return isNumber(event)" class="form-control" placeholder="Enter Primary Mobile No." />
											<input type="hidden" value="{{ $polPartyDetail->phone }}" name="oldPrimaryMob" />
											@if ($errors->has('editPrimaryMob'))
											<span class="help-block">
												<strong>{{ $errors->first('editPrimaryMob') }}</strong>
											</span>
											@endif
										</div>
										<div class="form-group{{ $errors->has('editSecondaryMob') ? ' has-error' : '' }}">
											<label class="form-label">Secondary Mobile No.</label>
											<input type="text" value="{{ $polPartyDetail->sphone }}" name="editSecondaryMob" onkeypress="return isNumber(event)" class="form-control" placeholder="Enter Secondary Mobile No." />
											@if ($errors->has('editSecondaryMob'))
											<span class="help-block">
												<strong>{{ $errors->first('editSecondaryMob') }}</strong>
											</span>
											@endif
										</div>
										<div class="form-group{{ $errors->has('editPpEmail') ? ' has-error' : '' }}">
											<label class="form-label">Email Address</label>
											<input type="text" value="{{ $polPartyDetail->email }}" name="editPpEmail" class="form-control" placeholder="Enter Email Address" />
											@if ($errors->has('editPpEmail'))
											<span class="help-block">
												<strong>{{ $errors->first('editPpEmail') }}</strong>
											</span>
											@endif
										</div>
									</div>
								</div>
								<button type="submit" class="btn btn-default">Submit</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>  
	</div>
	<!-- END CONTAINER -->
@endsection


