@extends('layouts.main')
@section('content')
	<!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<!-- Nominations -->
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					<div class="panel-title pageTitle titleBtn clearfix text-center">
                        <span>Electoral Rolls</span>
                    </div>
					<div class="panel-body">
						<div class="noticeCandi">
							<form enctype="multipart/form-data" method="get" action="{{url('ceo/electoral-rolls-submit') }}">
							<input type="hidden" name="_token" value="<?= csrf_token(); ?>">

								<div class="form-group{{ $errors->has('pollStation') ? ' has-error' : '' }}">
									<label class="form-label">Select Polling Station</label>
									<select id="district" name="pollStation" class="form-control">
										<option value="">Select Polling Station</option>
										@foreach($poll_station as $poll_stations)
										<option value="{{ $poll_stations->ps_id }}">{{ $poll_stations->poll_building }}</option>
										@endforeeach
									</select>
									@if ($errors->has('pollStation'))
										<span class="help-block">
											<strong>{{ $errors->first('pollStation') }}</strong>
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

