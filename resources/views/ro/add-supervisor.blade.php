@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
	<div class="container-widget">  
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					@if(Session::has('supvError'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('supvError') }}</p>
					@endif
					<div class="panel-title pageTitle titleBtn text-center">
						<span>Add Sector Officer</span>
						<div class="panel-btn formRightBtns">
							<a href="{{ url('ro/addSupervisorCsv') }}" class="btn btn-default">Import CSV</a>
							<!-- <a href="{{ url('/ro/supervisor-list') }}" class="btn btn-default">Back</a> -->
						</div>					
					</div>
					<div class="panel-body">
						<div class="noticeCandi">
							<form method="post" action="{{url('ro/addSupevisorSub') }}">
							<input type="hidden" name="_token" value="<?= csrf_token(); ?>">

								<div class="form-group{{ $errors->has('supvName') ? ' has-error' : '' }}">
									<label for="svName" class="form-label">Sector Officer Name</label>
									<input name="supvName" type="text" id="svName" class="form-control" placeholder="Sector Officer Name" value="{{ old('supvName') }}" required/>
									@if ($errors->has('supvName'))
										<span class="help-block">
											<strong>{{ $errors->first('supvName') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('supvPhone') ? ' has-error' : '' }}">
									<label for="svPhone" class="form-label">Phone Number</label>
									<input name="supvPhone" onkeypress="return isNumber(event)" type="text" id="svPhone" class="form-control" value="{{ old('supvPhone') }}" placeholder="Phone Number" required/>
									@if ($errors->has('supvPhone'))
										<span class="help-block">
											<strong>{{ $errors->first('supvPhone') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('supvDesig') ? ' has-error' : '' }}">
									<label for="svDesignation" class="form-label">Designation</label>
									<input name="supvDesig" value="{{ old('supvDesig') }}" type="text" id="svDesignation" class="form-control" placeholder="Designation" required/>
									@if ($errors->has('supvDesig'))
										<span class="help-block">
											<strong>{{ $errors->first('supvDesig') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('supvDpt') ? ' has-error' : '' }}">
									<label for="svDepartment" class="form-label">Department</label>
									<input name="supvDpt" value="{{ old('supvDpt') }}" type="text" id="svDepartment" class="form-control" placeholder="Department" required/>
									@if ($errors->has('supvDpt'))
										<span class="help-block">
											<strong>{{ $errors->first('supvDpt') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('supvPass') ? ' has-error' : '' }}">
									<label for="svPassword" class="form-label">Password</label>
									<input name="supvPass" type="password" id="svPassword" class="form-control" placeholder="Password" required/>
									@if ($errors->has('supvPass'))
										<span class="help-block">
											<strong>{{ $errors->first('supvPass') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('supvCpass') ? ' has-error' : '' }}">
									<label for="svConfPassword" class="form-label">Confirm Password</label>
									<input name="supvCpass" type="password" id="svConfPassword" class="form-control" placeholder="Confirm Password" required/>
									@if ($errors->has('supvCpass'))
										<span class="help-block">
											<strong>{{ $errors->first('supvCpass') }}</strong>
										</span>
									@endif
								</div>

								<button type="submit" class="btn btn-default">Add Supervisor</button>
							</form>
						</div>
					</div>
				</div>
			</div>		
		</div>  
	</div>
	<!-- END CONTAINER -->
	<script type="text/javascript">
		function isNumber(evt) {
			var charCode = (evt.which) ? evt.which : event.keyCode
			if (charCode != 43 && charCode > 31 && (charCode < 48 || charCode > 57))
			return false;
			return true;
		}
	</script>
@endsection

