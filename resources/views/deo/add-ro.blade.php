@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
    <?php
    $state=Auth::user()->state_id;
    $dist = Auth::user()->dist_code;
    ?>
	<div class="container-widget">  
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					@if(Session::has('roErr'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('roErr') }}</p>
					@endif
					<div class="panel-title pageTitle titleBtn text-center">
						<span>Add New Ro</span>
						<!-- <a href="{{ url('/deo/ro-list') }}" class="formBackBtn btn btn-default">Back</a> -->
					</div>
					<div class="panel-body">
						<div class="noticeCandi">
							<form enctype="multipart/form-data" method="post" action="{{url('deo/addRoSub') }}" >
							<input type="hidden" name="_token" value="<?= csrf_token(); ?>">

								<div class="form-group{{ $errors->has('roCons') ? ' has-error' : '' }}">
									<label class="form-label">Select Constituency</label>
									<select name="roCons" type="file" class="form-control"/>
										<?php echo get_constituencies($state,$dist); ?>
									</select>
									@if ($errors->has('roCons'))
										<span class="help-block">
											<strong>{{ $errors->first('roCons') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('roName') ? ' has-error' : '' }}">
									<label class="form-label">RO Name</label>
									<input type="text" name="roName" class="form-control" placeholder="Ro Name" value="{{ old('roName') }}"/>
									@if ($errors->has('roName'))
										<span class="help-block">
											<strong>{{ $errors->first('roName') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('roPhone') ? ' has-error' : '' }}">
									<label class="form-label">Phone Number</label>
									<input type="text" name="roPhone" class="form-control phoneClass" placeholder="Mobile Number" value="{{ old('roPhone') }}"/>
									@if ($errors->has('roPhone'))
										<span class="help-block">
											<strong>{{ $errors->first('roPhone') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('roPhoneOffice') ? ' has-error' : '' }}">
									<label class="form-label">Office Phone Number</label>
									<input type="text" name="roPhoneOffice" class="form-control phoneClass" placeholder="Mobile Number" value="{{ old('roPhoneOffice') }}"/>
									@if ($errors->has('roPhoneOffice'))
										<span class="help-block">
											<strong>{{ $errors->first('roPhoneOffice') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('faxRo') ? ' has-error' : '' }}">
									<label class="form-label">Fax</label>
									<input type="text" name="faxRo" class="form-control phoneClass" placeholder="Fax" value="{{ old('faxRo') }}"/>
									@if ($errors->has('faxRo'))
										<span class="help-block">
											<strong>{{ $errors->first('faxRo') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('designationRo') ? ' has-error' : '' }}">
									<label class="form-label">Designation</label>
									<input type="text" name="designationRo" class="form-control" placeholder="Designation" value="{{ old('designationRo') }}"/>
									@if ($errors->has('designationRo'))
										<span class="help-block">
											<strong>{{ $errors->first('designationRo') }}</strong>
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
		jQuery(".phoneClass").keypress(function (evt) { 
			var charCode = (evt.which) ? evt.which : evt.keyCode;
			if (charCode != 48 && charCode > 31 && (charCode < 43 || charCode > 57)){
				return false;
			}else{ 
				return true;
			}
		});
	</script>
	<!-- END CONTAINER -->
@endsection

