@extends('layouts.main')
@section('content')
	<!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<!-- Nominations -->
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					<div class="panel-title pageTitle titleBtn text-center">
						<span>Edit CEO</span>
						<div class="panel-btn formRightBtns">
							<!-- <a href="{{ url('eci/addceoCsv') }}" class="btn btn-default">Import CSV</a> -->
							<a href="{{URL::previous()}}" class="btn btn-default">Back</a>
						</div>
						
					</div>
					<div class="panel-body">
						<div class="noticeCandi">

							<form enctype="multipart/form-data" method="post" action="{{url('eci/editCeoSub') }}">
							<input type="hidden" name="_token" value="<?= csrf_token(); ?>">

							<?php $uidEnc=eci_encrypt($getCeo->uid); ?>
							<input type="hidden" name="uidCeo" value="<?php echo $uidEnc; ?>">

								<div class="form-group{{ $errors->has('ceoNameEdit') ? ' has-error' : '' }}">
									<label class="form-label">CEO Name</label>
									<input type="text" value="{{ $getCeo->name }}" name="ceoNameEdit" class="form-control" placeholder="CEO Name">
									@if ($errors->has('ceoNameEdit'))
										<span class="help-block">
											<strong>{{ $errors->first('ceoNameEdit') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('ceoStateEdit') ? ' has-error' : '' }}">
									<label class="form-label">State</label>
									<select class="form-control" name="ceoStateEdit" readonly>
										<option value="{{ $getCeo->StateID }}" selected>{{ $getCeo->StateName }}</option>
									</select>
									@if ($errors->has('ceoStateEdit'))
										<span class="help-block">
											<strong>{{ $errors->first('ceoStateEdit') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('ceoAddressEdit') ? ' has-error' : '' }}">
									<label class="form-label">Address</label>
									<textarea rows="3" cols="30" name="ceoAddressEdit" class="form-control" placeholder="Address">{{ $getCeo->address }}</textarea>
									@if ($errors->has('ceoAddressEdit'))
										<span class="help-block">
											<strong>{{ $errors->first('ceoAddressEdit') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('ceoEmailEdit') ? ' has-error' : '' }}">
									<label class="form-label">Email id</label>
									<input type="text" value="{{ $getCeo->email }}" name="ceoEmailEdit" class="form-control" placeholder="Email id">
									@if ($errors->has('ceoEmailEdit'))
										<span class="help-block">
											<strong>{{ $errors->first('ceoEmailEdit') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('ceoPhoneEdit') ? ' has-error' : '' }}">
									<label class="form-label">Phone Number</label>
									<input type="text" value="{{ $getCeo->phone }}" name="ceoPhoneEdit" class="form-control" placeholder="Phone Number">
									@if ($errors->has('ceoPhoneEdit'))
										<span class="help-block">
											<strong>{{ $errors->first('ceoPhoneEdit') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('ceoofficePhoneEdit') ? ' has-error' : '' }}">
									<label class="form-label">Office Phone Number</label>
									<input type="text" value="{{ $getCeo->office_phone }}" name="ceoofficePhoneEdit" class="form-control" placeholder="Office Phone Number">
									@if ($errors->has('ceoofficePhoneEdit'))
										<span class="help-block">
											<strong>{{ $errors->first('ceoofficePhoneEdit') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('ceoPWEdit') ? ' has-error' : '' }}">
									<label class="form-label">Password</label>
									<input type="password" name="ceoPWEdit" class="form-control"  placeholder="Password">
									<input type="hidden" name="ceoPWOld" value="{{ $getCeo->password }}">
									@if ($errors->has('ceoPWEdit'))
										<span class="help-block">
											<strong>{{ $errors->first('ceoPWEdit') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('ceoOrganizationEdit') ? ' has-error' : '' }}">
									<label class="form-label">Organization</label>
									<input type="text" value="{{ $getCeo->organisation }}" name="ceoOrganizationEdit" class="form-control" placeholder="Organization">
									@if ($errors->has('ceoOrganizationEdit'))
										<span class="help-block">
											<strong>{{ $errors->first('ceoOrganizationEdit') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('ceoDesignationEdit') ? ' has-error' : '' }}">
									<label class="form-label">Designation</label>
									<input type="text" value="{{ $getCeo->designation }}" name="ceoDesignationEdit" class="form-control" placeholder="Designation">
									@if ($errors->has('ceoDesignationEdit'))
										<span class="help-block">
											<strong>{{ $errors->first('ceoDesignationEdit') }}</strong>
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

