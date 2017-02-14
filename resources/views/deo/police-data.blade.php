@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
    
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					@if(Session::has('PolicedataSucc'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('PolicedataSucc') }}</p>
					@endif
					<div class="panel-title pageTitle text-center">
						<span>Police Data</span>
					</div>
					<div class="panel-body">
						<div class="voterSlip">
							<form method="post" action="{{url('deo/add-police-data') }}">
								<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
								<div class="form-group mb5 clearfix">
									<div class="Fields4 fields ">
										<label class="form-label"> </label>
									</div>
									<div class="Fields4 fields text-center">
										<label class="form-label">Total</label>
									</div>
									<div class="Fields4 fields text-center">
										<label class="form-label">Resolved</label>
									</div>
									<div class="Fields4 fields text-center">
										<label class="form-label">Pending</label>
									</div>
								</div>
								<div class="form-group mb10 clearfix">
									<div class="Fields4 fields">
										<label class="form-label mt8">NBW's</label>
									</div>
									<div class="Fields4 fields">
										<input type="text" name="nbw_total" class="form-control" value="{{ old('nbw_total') }}" />
										@if ($errors->has('nbw_total'))
											<span class="help-block">
												<strong>{{ $errors->first('nbw_total') }}</strong>
											</span>
										@endif
									</div>
									<div class="Fields4 fields">
										<input type="text" name="nbw_resolved" class="form-control" value="{{ old('nbw_resolved') }}" />
										@if ($errors->has('nbw_resolved'))
											<span class="help-block">
												<strong>{{ $errors->first('nbw_resolved') }}</strong>
											</span>
										@endif
									</div>
									<div class="Fields4 fields">
										<input type="text" name="nbw_pending" class="form-control" value="{{ old('nbw_pending') }}" />
										@if ($errors->has('nbw_pending'))
											<span class="help-block">
												<strong>{{ $errors->first('nbw_pending') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="form-group clearfix">
									<div class="Fields4 fields">
										<label class="form-label mt8">Arms & Amination</label>
									</div>
									<div class="Fields4 fields">
										<input type="text" name="arm_total" class="form-control" value="{{ old('arm_total') }}" />
										@if ($errors->has('arm_total'))
											<span class="help-block">
												<strong>{{ $errors->first('arm_total') }}</strong>
											</span>
										@endif
									</div>
									<div class="Fields4 fields">
										<input type="text" name="arm_resolved" class="form-control" value="{{ old('arm_resolved') }}" />
										@if ($errors->has('arm_resolved'))
											<span class="help-block">
												<strong>{{ $errors->first('arm_resolved') }}</strong>
											</span>
										@endif
									</div>
									<div class="Fields4 fields">
										<input type="text" name="arm_pending" class="form-control" value="{{ old('arm_pending') }}" />
										@if ($errors->has('arm_pending'))
											<span class="help-block">
												<strong>{{ $errors->first('arm_pending') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<button type="submit" class="btn btn-default">Submit</button>
							</form>
						</div>
					</div>
				</div>
			</div>		
		</div>
	</div>
@endsection

