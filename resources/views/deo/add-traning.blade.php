@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
	<link rel="stylesheet" href="{{ URL::asset('dateTimePicker/css/bootstrap-datetimepicker.min.css')}}" />
	<link rel="stylesheet" href="{{ URL::asset('dateTimePicker/css/jquery-ui.css')}}" />
	<div class="container-widget">  
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					@if(Session::has('traningErr'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('traningErr') }}</p>
					@endif
					<div class="panel-title pageTitle titleBtn text-center">
						<span>Add New Training</span>
						<a href="{{ url('/deo/traning-list') }}" class="formBackBtn btn btn-default">Back</a>
					</div>
					<div class="panel-body">
						<div class="noticeCandi">
							<form enctype="multipart/form-data" method="post" action="{{url('deo/addTraningSub') }}" >
							<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
								<div class="form-group{{ $errors->has('traningLabel') ? ' has-error' : '' }}">
									<label class="form-label">Training Name </label>
									<input type="text" name="traningLabel" class="form-control" placeholder="Training Name" value="{{ old('traningLabel') }}" />
									@if ($errors->has('traningLabel'))
										<span class="help-block">
											<strong>{{ $errors->first('traningLabel') }}</strong>
										</span>
									@endif
								</div>
								<div class="form-group{{ $errors->has('traningDate') ? ' has-error' : '' }}">
									<label class="form-label">Date</label>
									<input type="text" name="traningDate" id="datepicker-13" class="form-control" placeholder="Date" value="{{ old('traningDate') }}"/>
									@if ($errors->has('traningDate'))
										<span class="help-block">
											<strong>{{ $errors->first('traningDate') }}</strong>
										</span>
									@endif
								</div>
								<div class="form-group">
									<label class="form-label">Time</label>
									<div class="clearfix">
										<div class="fields">
											<input type="text" name="traningTimeFrom" id="time" placeholder="Time From" class="form-control" value="{{ old('traningTimeFrom') }}">
											@if ($errors->has('traningTimeFrom'))
												<span class="help-block">
													<strong>{{ $errors->first('traningTimeFrom') }}</strong>
												</span>
											@endif
										</div>
										<div class="fields timeField">
											<div style="position: relative">
												<input type="text" name="traningTimeTo" id="time1" placeholder="Time To" class="form-control" value="{{ old('traningTimeTo') }}">
											</div>
											@if ($errors->has('traningTimeTo'))
												<span class="help-block">
													<strong>{{ $errors->first('traningTimeTo') }}</strong>
												</span>
											@endif
										</div>
									</div>
								</div>
								<div class="form-group{{ $errors->has('traningVenue') ? ' has-error' : '' }}">
									<label class="form-label">Venue</label>
									<textarea rows="3" cols="30" name="traningVenue" class="form-control" placeholder="Venue"/>{{ old('traningVenue') }}</textarea>
									@if ($errors->has('traningVenue'))
										<span class="help-block">
											<strong>{{ $errors->first('traningVenue') }}</strong>
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
    <script type="text/javascript" src="{{ URL::asset('dateTimePicker/js/jquery-ui.js')}}"></script>
    <script type="text/javascript" src="{{ URL::asset('dateTimePicker/js/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{ URL::asset('dateTimePicker/js/bootstrap-datetimepicker.min.js')}}"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#datepicker-13").click(function(){
				$( "#datepicker-13" ).datepicker();
				$( "#datepicker-13" ).datepicker("show");	
			});
			$('#time').datetimepicker({
				format: 'HH:mm:ss'
		    });
		    $('#time1').datetimepicker({
				format: 'HH:mm:ss'
		    });

		    $('#time').keypress(function(e) {
			    return false
			});
			$('#time1').keypress(function(e) {
			    return false
			});
			$('#datepicker-13').keypress(function(e) {
			    return false
			});
		});

		var dateToday = new Date();
		var dates = $("#datepicker-13").datepicker({
			//minDate: dateToday,
		});
	</script>
	<!-- END CONTAINER -->
@endsection

