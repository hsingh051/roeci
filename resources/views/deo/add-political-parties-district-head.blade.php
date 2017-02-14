@extends('layouts.main')
@section('content')
	<script type="text/javascript">
		function isNumber(evt) {
			var charCode = (evt.which) ? evt.which : event.keyCode
			if (charCode != 43 && charCode > 31 && (charCode < 48 || charCode > 57))
			return false;
			return true;
		}


		$(document).ready(function(){
			$('#addMoreDistBtn').click(function(){
				$('.CloneDistHead:first').clone().appendTo('#addMoreDist');
			});
		});
	</script>
    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					@if(Session::has('polDistHeadMsz'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('polDistHeadMsz') }}</p>
					@endif
					<div class="panel-title pageTitle titleBtn text-center">
						<span>Add New Political Party District Head</span>
						<a href="{{URL::previous()}}" class="formBackBtn btn btn-default">Back</a>
					</div>
					<div class="panel-body">
						<div class="noticeCandi">
							<form enctype="multipart/form-data" method="post" action="{{url('deo/addPolPartDistHeadSubmit') }}" >
							<input type="hidden" name="_token" value="<?= csrf_token(); ?>">

								<div class="form-group{{ $errors->has('partyName') ? ' has-error' : '' }}">
									<label class="form-label">Party Name</label>
									<select name="partyName" class="form-control">
										<option value="">Select Party</option>
										@foreach($polParty as $polParties)
										<?php $encryptPpid=eci_encrypt($polParties->ppid)?>
										<option value="<?php echo $encryptPpid; ?>">{{ $polParties->party_name }}</option>
										@endforeach
									</select>
									@if ($errors->has('partyName'))
										<span class="help-block">
											<strong>{{ $errors->first('partyName') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('officeAddress') ? ' has-error' : '' }}">
									<label class="form-label">Office Address</label>
									<input type="text" class="form-control" name="officeAddress" placeholder="Enter Office Address" />
									@if ($errors->has('officeAddress'))
										<span class="help-block">
											<strong>{{ $errors->first('officeAddress') }}</strong>
										</span>
									@endif
								</div>	


								<div class="form-group{{ $errors->has('officeNumber') ? ' has-error' : '' }}">
									<label class="form-label">Office Number</label>
									<input type="text" name="officeNumber" onkeypress="return isNumber(event)" class="form-control" placeholder="Enter Office Number" />
									@if ($errors->has('officeNumber'))
										<span class="help-block">
											<strong>{{ $errors->first('officeNumber') }}</strong>
										</span>
									@endif
								</div>

								<div id="addMoreDist" class="addMoreBox">
									<div class="CloneDistHead cloneBox">
										<div class="form-group{{ $errors->has('distHeadName') ? ' has-error' : '' }}">
											<label class="form-label">District Head Name</label>
											<input type="text" name="distHeadName" class="form-control" placeholder="Enter District Head Name" />
											@if ($errors->has('distHeadName'))
											<span class="help-block">
												<strong>{{ $errors->first('distHeadName') }}</strong>
											</span>
											@endif
										</div>
										<div class="form-group{{ $errors->has('primaryMob') ? ' has-error' : '' }}">
											<label class="form-label">Primary Mobile No.</label>
											<input type="text" name="primaryMob" onkeypress="return isNumber(event)" class="form-control" placeholder="Enter Primary Mobile No." />
											@if ($errors->has('primaryMob'))
											<span class="help-block">
												<strong>{{ $errors->first('primaryMob') }}</strong>
											</span>
											@endif
										</div>
										<div class="form-group{{ $errors->has('secondaryMob') ? ' has-error' : '' }}">
											<label class="form-label">Secondary Mobile No.</label>
											<input type="text" name="secondaryMob" onkeypress="return isNumber(event)" class="form-control" placeholder="Enter Secondary Mobile No." />
											@if ($errors->has('secondaryMob'))
											<span class="help-block">
												<strong>{{ $errors->first('secondaryMob') }}</strong>
											</span>
											@endif
										</div>
										<div class="form-group{{ $errors->has('ppEmail') ? ' has-error' : '' }}">
											<label class="form-label">Email Address</label>
											<input type="text" name="ppEmail" class="form-control" placeholder="Enter Email Address" />
											@if ($errors->has('ppEmail'))
											<span class="help-block">
												<strong>{{ $errors->first('ppEmail') }}</strong>
											</span>
											@endif
										</div>
									</div>
								</div>
								<!-- <div class="form-group addMoreForm">
									<a href="javascript:void(0);" id="addMoreDistBtn"><i class="fa fa-plus-square"></i> Add More District Head</a>
								</div> -->
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

