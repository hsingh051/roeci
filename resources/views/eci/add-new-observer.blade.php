@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
	<div class="container-widget">  
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					<div class="panel-title pageTitle titleBtn text-center">
						<span>Add New Observer</span>
						<a href="{{URL::previous()}}" class="formBackBtn btn btn-default">Back</a>
					</div>
					<div class="panel-body">
						<div class="noticeCandi">
							<form enctype="multipart/form-data" method="post" action="{{url('eci/addObserverSubmit') }}" >
							<input type="hidden" name="_token" value="<?= csrf_token(); ?>">

								<div class="form-group{{ $errors->has('obsName') ? ' has-error' : '' }}">
									<label class="form-label">Observer Name</label>
									<input type="text" name="obsName" class="form-control" placeholder="Observer Name" />
									@if ($errors->has('obsName'))
										<span class="help-block">
											<strong>{{ $errors->first('obsName') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('obsDistrict') ? ' has-error' : '' }}">
									<label class="form-label">Observer district</label>
									<select name="obsDistrict" class="form-control" />
										<option value="">Select district</option>
										@foreach($distlist as $distlists)
										<?php $distCodeEnc=eci_encrypt($distlists->dist_code); ?>
										<option value="<?php echo $distCodeEnc; ?>">{{ $distlists->dist_name }}</option>
										@endforeach

									</select>
									@if ($errors->has('obsDistrict'))
										<span class="help-block">
											<strong>{{ $errors->first('obsDistrict') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('obsEmail') ? ' has-error' : '' }}">
									<label class="form-label">Email Address</label>
									<input type="text" name="obsEmail" class="form-control" placeholder="Email Address"/>
									@if ($errors->has('obsEmail'))
										<span class="help-block">
											<strong>{{ $errors->first('obsEmail') }}</strong>
										</span>
									@endif
								</div>
								<div class="form-group{{ $errors->has('obsPhone') ? ' has-error' : '' }}">
									<label class="form-label">Mobile Number</label>
									<input type="text" onkeypress="return isNumber(event)" name="obsPhone" class="form-control" placeholder="Mobile Number" />
									@if ($errors->has('obsPhone'))
										<span class="help-block">
											<strong>{{ $errors->first('obsPhone') }}</strong>
										</span>
									@endif
								</div>
								<div class="form-group{{ $errors->has('obsPic') ? ' has-error' : '' }}">
									<label class="form-label">Image</label>
									<input name="obsPic" type="file" class="form-control" />
									@if ($errors->has('obsPic'))
										<span class="help-block">
											<strong>{{ $errors->first('obsPic') }}</strong>
										</span>
									@endif
								</div>
								<div class="form-group{{ $errors->has('obAddress') ? ' has-error' : '' }}">
									<label class="form-label">Address</label>
									<textarea name="obAddress" class="form-control" placeholder="Address" ></textarea>
									@if ($errors->has('obAddress'))
										<span class="help-block">
											<strong>{{ $errors->first('obAddress') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('obType') ? ' has-error' : '' }}">
									<label class="form-label">Observer Type</label>
									<select name="obType" type="file" class="form-control" />
										<option value="">Select</option>
										<option value="General Observer">General Observer</option>
										<option value="Expenditure Observer">Expenditure Observer</option>
										<option value="Police Observer">Police Observer</option>
										<option value="Awareness Oserver">Awareness Oserver</option>
									</select>
									@if ($errors->has('obType'))
										<span class="help-block">
											<strong>{{ $errors->first('obType') }}</strong>
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

