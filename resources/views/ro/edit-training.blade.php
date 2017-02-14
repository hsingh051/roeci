@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
	<link rel="stylesheet" href="{{ URL::asset('dateTimePicker/css/bootstrap-datetimepicker.min.css')}}" />
	<link rel="stylesheet" href="{{ URL::asset('dateTimePicker/css/jquery-ui.css')}}" />
	<div class="container-widget">  
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					@if(Session::has('traningErrEditRo'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('traningErrEditRo') }}</p>
					@endif
					<div class="panel-title pageTitle titleBtn text-center">
						<span>Edit Training</span>
						<!-- <a href="{{ url('/ro/training') }}" class="btn btn-default formBackBtn">Back</a> -->
					</div>
					<div class="panel-body">
						<div class="noticeCandi">
							<form enctype="multipart/form-data" method="post" action="{{url('ro/editTraningSub') }}" >
							<input type="hidden" name="_token" value="<?= csrf_token(); ?>">

							<?php
							$trId=$traningDetail->id;
							$trIdEnc=eci_encrypt($trId);
							?>
							<input type="hidden" name="idTraningHidden" value="<?php echo $trIdEnc; ?>">

								<div class="form-group{{ $errors->has('editTraningLabel') ? ' has-error' : '' }}">
									<label class="form-label">Training Type</label>
									<input type="text" name="editTraningLabel" value="{{ $traningDetail->name }}" class="form-control" placeholder="Training Type" />
									@if ($errors->has('editTraningLabel'))
										<span class="help-block">
											<strong>{{ $errors->first('editTraningLabel') }}</strong>
										</span>
									@endif
								</div>
								<div class="form-group{{ $errors->has('editTraningDate') ? ' has-error' : '' }}">
									<label class="form-label">Date</label>
									<input type="text" name="editTraningDate" value="{{ $traningDetail->date }}" id="datepicker-13" class="form-control" placeholder="Date" />
									@if ($errors->has('editTraningDate'))
										<span class="help-block">
											<strong>{{ $errors->first('editTraningDate') }}</strong>
										</span>
									@endif
								</div>
								<div class="form-group">
									<label class="form-label">Time</label>
									<div class="clearfix">
										<div class="fields">
											<input type="text" value="{{ $traningDetail->from_time }}" name="editTraningTimeFrom" id="time" placeholder="Time From" class="form-control">
											@if ($errors->has('editTraningTimeFrom'))
												<span class="help-block">
													<strong>{{ $errors->first('editTraningTimeFrom') }}</strong>
												</span>
											@endif
										</div>
										<div class="fields timeField">
											<div style="position: relative">
												<input type="text" value="{{ $traningDetail->to_time }}" name="editTraningTimeTo" id="time1" placeholder="Time To" class="form-control">
											</div>
											@if ($errors->has('editTraningTimeTo'))
												<span class="help-block">
													<strong>{{ $errors->first('editTraningTimeTo') }}</strong>
												</span>
											@endif
										</div>
									</div>
								</div>
								<div class="form-group{{ $errors->has('editTraningVenue') ? ' has-error' : '' }}">
									<label class="form-label">Venue</label>
									<textarea rows="3" cols="30" name="editTraningVenue" class="form-control" placeholder="Venue" />{{ $traningDetail->location }}</textarea>
									@if ($errors->has('editTraningVenue'))
										<span class="help-block">
											<strong>{{ $errors->first('editTraningVenue') }}</strong>
										</span>
									@endif
								</div>
								<div class="form-group{{ $errors->has('editTraningType') ? ' has-error' : '' }}">
									<label class="form-label">Venue Type</label>
									<select name="editTraningType" class="form-control" />
										<option value="">Select</option>
										<option value="EVM Preparation" <?php if(($traningDetail->type)=="EVM Preparation"){echo "selected"; } ?>>EVM Preparation</option>
										<option value="Kit Bag Preparation" <?php if(($traningDetail->type)=="Kit Bag Preparation"){echo "selected"; } ?>>Kit Bag Preparation</option>
										<option value="Material Dispatch" <?php if(($traningDetail->type)=="Material Dispatch"){echo "selected"; } ?>>Material Dispatch</option>
										<option value="Marked Copy" <?php if(($traningDetail->type)=="Marked Copy"){echo "selected"; } ?>>Marked Copy</option>
									</select>
									@if ($errors->has('editTraningType'))
										<span class="help-block">
											<strong>{{ $errors->first('editTraningType') }}</strong>
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
				format: 'HH:mm'
		    });
		    $('#time1').datetimepicker({
				format: 'HH:mm'
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
			var dates = $("#datepicker-13").datepicker({
				minDate: dateToday,
			});
		});
	</script>
	<!-- END CONTAINER -->
@endsection

