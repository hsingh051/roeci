@extends('layouts.main')
@section('content')
	<!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<!-- Nominations -->
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					<div class="panel-title pageTitle titleBtn text-center">
						<span>Add New CEO</span>
						<div class="panel-btn formRightBtns">
							<!-- <a href="{{ url('eci/addceoCsv') }}" class="btn btn-default">Import CSV</a> -->
							<a href="{{URL::previous()}}" class="btn btn-default">Back</a>
						</div>
						
					</div>
					<div class="panel-body">
						<div class="noticeCandi">

							<form enctype="multipart/form-data" method="post" action="{{url('eci/addceoSub') }}">
							<input type="hidden" name="_token" value="<?= csrf_token(); ?>">

								<div class="form-group{{ $errors->has('ceoName') ? ' has-error' : '' }}">
									<label class="form-label">CEO Name</label>
									<input type="text" name="ceoName" class="form-control" placeholder="CEO Name">
									@if ($errors->has('ceoName'))
										<span class="help-block">
											<strong>{{ $errors->first('ceoName') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('ceoState') ? ' has-error' : '' }}">
									<label class="form-label">State</label>
									<select class="form-control" name="ceoState">
										<option value="">Select State</option>
										@foreach($stateList as $stateLists)
										<option value="{{ $stateLists->StateID }}">{{ $stateLists->StateName }}</option>
										@endforeach
									</select>
									@if ($errors->has('ceoState'))
										<span class="help-block">
											<strong>{{ $errors->first('ceoState') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('ceoAddress') ? ' has-error' : '' }}">
									<label class="form-label">Address</label>
									<textarea rows="3" cols="30" name="ceoAddress" class="form-control" placeholder="Address"></textarea>
									@if ($errors->has('ceoAddress'))
										<span class="help-block">
											<strong>{{ $errors->first('ceoAddress') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('ceoEmail') ? ' has-error' : '' }}">
									<label class="form-label">Email id</label>
									<input type="text" name="ceoEmail" class="form-control" placeholder="Email id">
									@if ($errors->has('ceoEmail'))
										<span class="help-block">
											<strong>{{ $errors->first('ceoEmail') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('ceoPhone') ? ' has-error' : '' }}">
									<label class="form-label">Phone Number</label>
									<input type="text" name="ceoPhone" class="form-control" placeholder="Phone Number">
									@if ($errors->has('ceoPhone'))
										<span class="help-block">
											<strong>{{ $errors->first('ceoPhone') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('ceoofficePhone') ? ' has-error' : '' }}">
									<label class="form-label">Office Phone Number</label>
									<input type="text" name="ceoofficePhone" class="form-control" placeholder="Office Phone Number">
									@if ($errors->has('ceoofficePhone'))
										<span class="help-block">
											<strong>{{ $errors->first('ceoofficePhone') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('ceoPW') ? ' has-error' : '' }}">
									<label class="form-label">Password</label>
									<input type="password" name="ceoPW" class="form-control"  placeholder="Password">
									@if ($errors->has('ceoPW'))
										<span class="help-block">
											<strong>{{ $errors->first('ceoPW') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('ceoOrganization') ? ' has-error' : '' }}">
									<label class="form-label">Organization</label>
									<input type="text" name="ceoOrganization" class="form-control" placeholder="Organization">
									@if ($errors->has('ceoOrganization'))
										<span class="help-block">
											<strong>{{ $errors->first('ceoOrganization') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('ceoDesignation') ? ' has-error' : '' }}">
									<label class="form-label">Designation</label>
									<input type="text" name="ceoDesignation" class="form-control" placeholder="Designation">
									@if ($errors->has('ceoDesignation'))
										<span class="help-block">
											<strong>{{ $errors->first('ceoDesignation') }}</strong>
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

