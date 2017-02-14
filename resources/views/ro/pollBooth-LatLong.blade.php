@extends('layouts.main')
@section('content')
	 <!-- START CONTAINER -->
	<div class="container-widget">  
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					@if(Session::has('latLongUpErr'))
						<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('latLongUpErr') }}</p>
					@endif
					<div class="panel-title pageTitle text-center">Poll Booth Lat-Long</div>
					<div class="panel-body">
						<div class="noticeCandi">
							<form enctype="multipart/form-data" method="post" action="{{url('ro/updatePollBoothLatLong') }}" >
								<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
								<div class="form-group{{ $errors->has('pBoothLatLongExcel') ? ' has-error' : '' }}">
									<label class="form-label">Excel Sheet</label>
									<input name="pBoothLatLongExcel" type="file" id="pBoothLatLongExcel" class="form-control"  required/>
									@if ($errors->has('pBoothLatLongExcel'))
										<span class="help-block">
											<strong>{{ $errors->first('pBoothLatLongExcel') }}</strong>
										</span>
									@endif
								</div>
								<button type="submit" class="btn btn-default">UPLOAD CSV</button> 
							</form>
						</div>
					</div>
				</div>
			</div>		
		</div>  
	</div>
@endsection

