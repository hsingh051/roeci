@extends('layouts.main')
@section('content')
	<!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<!-- Nominations -->
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					@if(Session::has('addDeoError'))
					<p class="alert alert-danger">{{ Session::get('addDeoError') }}</p>
					@endif
					<div class="panel-title pageTitle titleBtn text-center">
						<span>Add New DEO</span>
						<div class="panel-btn formRightBtns">
							<a href="{{ url('ceo/addDeo') }}" class="btn btn-default">Import CSV</a>
							<a href="{{ url('/ceo/deo-list') }}" class="btn btn-default">Back</a>
						</div>
						
					</div>
					<div class="panel-body">
						<div class="noticeCandi">

							<form enctype="multipart/form-data" method="post" action="{{url('ceo/addDeoFormSub') }}">
							<input type="hidden" name="_token" value="<?= csrf_token(); ?>">

								<div class="form-group{{ $errors->has('deoName') ? ' has-error' : '' }}">
									<label class="form-label">DEO Name</label>
									<input type="text" name="deoName" class="form-control" placeholder="DEO Name">
									@if ($errors->has('deoName'))
										<span class="help-block">
											<strong>{{ $errors->first('deoName') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('deoDist') ? ' has-error' : '' }}">
									<label class="form-label">District</label>
									<select class="form-control" name="deoDist">
										<option value="">Select District</option>
										@foreach($distList as $distLists)
										<option value="{{ $distLists->dist_code }}">{{ $distLists->dist_name }}</option>
										@endforeach
									</select>
									@if ($errors->has('deoDist'))
										<span class="help-block">
											<strong>{{ $errors->first('deoDist') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('deoAddress') ? ' has-error' : '' }}">
									<label class="form-label">Address</label>
									<textarea rows="3" cols="30" name="deoAddress" class="form-control" placeholder="Address"></textarea>
									@if ($errors->has('deoAddress'))
										<span class="help-block">
											<strong>{{ $errors->first('deoAddress') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('deoEmail') ? ' has-error' : '' }}">
									<label class="form-label">Email id</label>
									<input type="text" name="deoEmail" class="form-control" placeholder="Email id">
									@if ($errors->has('deoEmail'))
										<span class="help-block">
											<strong>{{ $errors->first('deoEmail') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('deoPhone') ? ' has-error' : '' }}">
									<label class="form-label">Phone Number</label>
									<input type="text" name="deoPhone" class="form-control" placeholder="Phone Number">
									@if ($errors->has('deoPhone'))
										<span class="help-block">
											<strong>{{ $errors->first('deoPhone') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('deoofficePhone') ? ' has-error' : '' }}">
									<label class="form-label">Office Phone Number</label>
									<input type="text" name="deoofficePhone" class="form-control" placeholder="Office Phone Number">
									@if ($errors->has('deoofficePhone'))
										<span class="help-block">
											<strong>{{ $errors->first('deoofficePhone') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('deoPW') ? ' has-error' : '' }}">
									<label class="form-label">Password</label>
									<input type="password" name="deoPW" class="form-control"  placeholder="Password">
									@if ($errors->has('deoPW'))
										<span class="help-block">
											<strong>{{ $errors->first('deoPW') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('deoCPW') ? ' has-error' : '' }}">
									<label class="form-label">Confirm Password</label>
									<input name="deoCPW" type="password" class="form-control" placeholder="Confirm Password" />
									@if ($errors->has('deoCPW'))
										<span class="help-block">
											<strong>{{ $errors->first('deoCPW') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('deoOrganization') ? ' has-error' : '' }}">
									<label class="form-label">Organization</label>
									<input type="text" name="deoOrganization" class="form-control" placeholder="Organization">
									@if ($errors->has('deoOrganization'))
										<span class="help-block">
											<strong>{{ $errors->first('deoOrganization') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('deoDesignation') ? ' has-error' : '' }}">
									<label class="form-label">Designation</label>
									<input type="text" name="deoDesignation" class="form-control" placeholder="Designation">
									@if ($errors->has('deoDesignation'))
										<span class="help-block">
											<strong>{{ $errors->first('deoDesignation') }}</strong>
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

