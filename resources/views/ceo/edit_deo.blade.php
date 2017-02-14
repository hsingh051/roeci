@extends('layouts.main')
@section('content')
	<!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<!-- Nominations -->
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					<div class="panel-title pageTitle titleBtn text-center">
						<span>Edit DEO</span>
						<div class="panel-btn formRightBtns">
							<!-- <a href="{{ url('eci/addceoCsv') }}" class="btn btn-default">Import CSV</a> -->
							<a href="{{ url('/ceo/deo-list') }}" class="btn btn-default">Back</a>
						</div>
						
					</div>
					<div class="panel-body">
						<div class="noticeCandi">

							<form enctype="multipart/form-data" method="post" action="{{url('ceo/editDeoSub') }}">
							<input type="hidden" name="_token" value="<?= csrf_token(); ?>">

							<?php $uidEnc=eci_encrypt($getDeo->uid); ?>
							<input type="hidden" name="uidDeo" value="<?php echo $uidEnc; ?>">

								<div class="form-group{{ $errors->has('deoNameEdit') ? ' has-error' : '' }}">
									<label class="form-label">CEO Name</label>
									<input type="text" value="{{ $getDeo->name }}" name="deoNameEdit" class="form-control" placeholder="CEO Name">
									@if ($errors->has('deoNameEdit'))
										<span class="help-block">
											<strong>{{ $errors->first('deoNameEdit') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('deoDistEdit') ? ' has-error' : '' }}">
									<label class="form-label">District</label>
									<select class="form-control" name="deoDistEdit" readonly>
										<option value="{{ $getDeo->dist_code }}" selected>{{ $getDeo->dist_name }}</option>
									</select>
									@if ($errors->has('deoDistEdit'))
										<span class="help-block">
											<strong>{{ $errors->first('deoDistEdit') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('deoAddressEdit') ? ' has-error' : '' }}">
									<label class="form-label">Address</label>
									<textarea rows="3" cols="30" name="deoAddressEdit" class="form-control" placeholder="Address">{{ $getDeo->address }}</textarea>
									@if ($errors->has('deoAddressEdit'))
										<span class="help-block">
											<strong>{{ $errors->first('deoAddressEdit') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('deoEmailEdit') ? ' has-error' : '' }}">
									<label class="form-label">Email id</label>
									<input type="text" value="{{ $getDeo->email }}" name="deoEmailEdit" class="form-control" placeholder="Email id">
									@if ($errors->has('deoEmailEdit'))
										<span class="help-block">
											<strong>{{ $errors->first('deoEmailEdit') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('deoPhoneEdit') ? ' has-error' : '' }}">
									<label class="form-label">Phone Number</label>
									<input type="text" value="{{ $getDeo->phone }}" name="deoPhoneEdit" class="form-control" placeholder="Phone Number">
									@if ($errors->has('deoPhoneEdit'))
										<span class="help-block">
											<strong>{{ $errors->first('deoPhoneEdit') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('deoofficePhoneEdit') ? ' has-error' : '' }}">
									<label class="form-label">Office Phone Number</label>
									<input type="text" value="{{ $getDeo->office_phone }}" name="deoofficePhoneEdit" class="form-control" placeholder="Office Phone Number">
									@if ($errors->has('deoofficePhoneEdit'))
										<span class="help-block">
											<strong>{{ $errors->first('deoofficePhoneEdit') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('deoPWEdit') ? ' has-error' : '' }}">
									<label class="form-label">Password</label>
									<input type="password" name="deoPWEdit" class="form-control"  placeholder="Password">
									<input type="hidden" name="deoPWOld" value="{{ $getDeo->password }}">
									@if ($errors->has('deoPWEdit'))
										<span class="help-block">
											<strong>{{ $errors->first('deoPWEdit') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('deoOrganizationEdit') ? ' has-error' : '' }}">
									<label class="form-label">Organization</label>
									<input type="text" value="{{ $getDeo->organisation }}" name="deoOrganizationEdit" class="form-control" placeholder="Organization">
									@if ($errors->has('deoOrganizationEdit'))
										<span class="help-block">
											<strong>{{ $errors->first('deoOrganizationEdit') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('deoDesignationEdit') ? ' has-error' : '' }}">
									<label class="form-label">Designation</label>
									<input type="text" value="{{ $getDeo->designation }}" name="deoDesignationEdit" class="form-control" placeholder="Designation">
									@if ($errors->has('deoDesignationEdit'))
										<span class="help-block">
											<strong>{{ $errors->first('deoDesignationEdit') }}</strong>
										</span>
									@endif
								</div>

								<button type="submit" class="btn btn-default">Send</button>
							</form>
						</div>
					</div>
				</div>
			</div>
			<!-- End Nominations -->
		
		</div>  
	</div>
	<!-- END CONTAINER -->
@endsection

