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
							<form enctype="multipart/form-data" method="post" action="{{url('deo/addObserverSubmit') }}" >
							<input type="hidden" name="_token" value="<?= csrf_token(); ?>">

								<div class="form-group{{ $errors->has('obsName') ? ' has-error' : '' }}">
									<label class="form-label">Observer Name</label>
									<input type="text" name="obsName" class="form-control" placeholder="Observer Name" required/>
									@if ($errors->has('obsName'))
										<span class="help-block">
											<strong>{{ $errors->first('obsName') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('obsEmail') ? ' has-error' : '' }}">
									<label class="form-label">Email Address</label>
									<input type="text" name="obsEmail" class="form-control" placeholder="Email Address" required/>
									@if ($errors->has('obsEmail'))
										<span class="help-block">
											<strong>{{ $errors->first('obsEmail') }}</strong>
										</span>
									@endif
								</div>
								<div class="form-group{{ $errors->has('obsPhone') ? ' has-error' : '' }}">
									<label class="form-label">Mobile Number</label>
									<input type="text" onkeypress="return isNumber(event)" name="obsPhone" class="form-control" placeholder="Mobile Number" required/>
									@if ($errors->has('obsPhone'))
										<span class="help-block">
											<strong>{{ $errors->first('obsPhone') }}</strong>
										</span>
									@endif
								</div>
								<div class="form-group{{ $errors->has('obsPic') ? ' has-error' : '' }}">
									<label class="form-label">Image</label>
									<input name="obsPic" type="file" class="form-control" required/>
									@if ($errors->has('obsPic'))
										<span class="help-block">
											<strong>{{ $errors->first('obsPic') }}</strong>
										</span>
									@endif
								</div>
								<div class="form-group{{ $errors->has('obAddress') ? ' has-error' : '' }}">
									<label class="form-label">Address</label>
									<textarea name="obAddress" class="form-control" placeholder="Address" required></textarea>
									@if ($errors->has('obAddress'))
										<span class="help-block">
											<strong>{{ $errors->first('obAddress') }}</strong>
										</span>
									@endif
								</div>


								<div class="form-group{{ $errors->has('obType') ? ' has-error' : '' }}">
									<label class="form-label">Observer Type</label>
									<select name="obType" type="file" class="form-control" required/>
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

