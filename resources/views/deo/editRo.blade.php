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
						<span>Edit Ro</span>
						<a href="{{ url('/deo/ro-list') }}" class="formBackBtn btn btn-default">Back</a>
					</div>
					<div class="panel-body">
						<div class="noticeCandi">
							<form enctype="multipart/form-data" method="post" action="{{url('deo/editRoSub') }}" >
							<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
							<input type="hidden" name="uidRo" value="{{ $uid }}">
								<div class="form-group{{ $errors->has('roNameEdit') ? ' has-error' : '' }}">
									<label class="form-label">RO Name</label>
									<input type="text" name="roNameEdit" class="form-control" placeholder="Ro Name" value="{{ $RoDetail->name }}"/>
									@if ($errors->has('roNameEdit'))
										<span class="help-block">
											<strong>{{ $errors->first('roNameEdit') }}</strong>
										</span>
									@endif
								</div>
								<div class="form-group{{ $errors->has('roPhoneEdit') ? ' has-error' : '' }}">
									<label class="form-label">Phone Number</label>
									<input type="text" name="roPhoneEdit" class="form-control phoneClass" placeholder="Mobile Number" value="{{ $RoDetail->phone }}" />

									<input type="hidden" name="oldPhoneRo" value="{{ $RoDetail->phone }}" />
									@if ($errors->has('roPhoneEdit'))
										<span class="help-block">
											<strong>{{ $errors->first('roPhoneEdit') }}</strong>
										</span>
									@endif
								</div>
								<div class="form-group{{ $errors->has('roPhoneOfficeEdit') ? ' has-error' : '' }}">
									<label class="form-label">Office Phone Number</label>
									<input type="text" name="roPhoneOfficeEdit" class="form-control phoneClass" placeholder="Mobile Number" value="{{ $RoDetail->office_phone }}" />
									@if ($errors->has('roPhoneOfficeEdit'))
										<span class="help-block">
											<strong>{{ $errors->first('roPhoneOfficeEdit') }}</strong>
										</span>
									@endif
								</div>
								<div class="form-group{{ $errors->has('faxRoEdit') ? ' has-error' : '' }}">
									<label class="form-label">Fax</label>
									<input type="text" name="faxRoEdit" class="form-control phoneClass" placeholder="Fax" value="{{ $RoDetail->fax }}" />
									@if ($errors->has('faxRoEdit'))
										<span class="help-block">
											<strong>{{ $errors->first('faxRoEdit') }}</strong>
										</span>
									@endif
								</div>
								<div class="form-group{{ $errors->has('designationRoEdit') ? ' has-error' : '' }}">
									<label class="form-label">Designation</label>
									<input type="text" name="designationRoEdit" class="form-control" placeholder="Designation" value="{{ $RoDetail->designation }}" />
									@if ($errors->has('designationRoEdit'))
										<span class="help-block">
											<strong>{{ $errors->first('designationRoEdit') }}</strong>
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


