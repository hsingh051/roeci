@extends('layouts.main')

@section('content')
<?php
$dist = Auth::user()->dist_code;
$state = get_state_id();
?>
<!-- START CONTAINER -->
	<link rel="stylesheet" href="{{ URL::asset('dateTimePicker/css/bootstrap-datetimepicker.min.css')}}" />
	<link rel="stylesheet" href="{{ URL::asset('dateTimePicker/css/jquery-ui.css')}}" />
	<div class="container-widget">  
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget">
					<div class="panel-title pageTitle text-center">New Nomination</div>
					<div class="panel-body">
						<div class="noticeCandi">
							<form method="post" enctype="multipart/form-data" action="{{url('ro/addNominationSub') }}">
							<input type="hidden" name="_token" value="<?= csrf_token(); ?>">

								<div class="form-group{{ $errors->has('cName') ? ' has-error' : '' }}">
									<label for="cName" class="form-label">Candidate Name</label>
									<input type="text" name="cName" id="cName" placeholder="Name" class="form-control">
									@if ($errors->has('cName'))
										<span class="help-block">
											<strong>{{ $errors->first('cName') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('cProfilePic') ? ' has-error' : '' }}">
									<label for="cProfilePic" class="form-label">Profile Picture</label>
									<input type="file" name="cProfilePic" class="form-control">
									@if ($errors->has('cProfilePic'))
										<span class="help-block">
											<strong>{{ $errors->first('cProfilePic') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('fatherMotherHus') ? ' has-error' : '' }}">
									<label for="fatherMotherHus" class="form-label">Father’s/mother’s/husband’s Name</label>
									<input type="text" name="fatherMotherHus" id="fatherMotherHus" placeholder="Father’s/mother’s/husband’s Name" class="form-control">
									@if ($errors->has('fatherMotherHus'))
										<span class="help-block">
											<strong>{{ $errors->first('fatherMotherHus') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('postAddress') ? ' has-error' : '' }}">
									<label for="postAddress" class="form-label">Postal Address</label>
									<textarea cols="30" rows="3" name="postAddress" id="postAddress" placeholder="Postal Address" class="form-control"></textarea>
									@if ($errors->has('postAddress'))
										<span class="help-block">
											<strong>{{ $errors->first('postAddress') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('cState') ? ' has-error' : '' }}">
									<label for="cState" class="form-label">State</label>
									<select name="cState" id="cState"  class="form-control">
										<option value="1">Punjab</option>
										<option value="2">Haryana</option>
										<option value="3">Delhi</option>
									</select>
									@if ($errors->has('cState'))
										<span class="help-block">
											<strong>{{ $errors->first('cState') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('citizenship') ? ' has-error' : '' }}">
									<label for="citizenship" class="form-label">Citizenship</label>
									<input type="text" name="citizenship" id="citizenship" placeholder="Citizenship" class="form-control">
									@if ($errors->has('citizenship'))
										<span class="help-block">
											<strong>{{ $errors->first('citizenship') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('cDateOfBirth') ? ' has-error' : '' }}">
									<label for="cDateOfBirth" class="form-label">Date Of Birth</label>
									<input type="text" name="cDateOfBirth" id="datepicker-13" placeholder="Date Of Birth" class="form-control">
									@if ($errors->has('cDateOfBirth'))
										<span class="help-block">
											<strong>{{ $errors->first('cDateOfBirth') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('cPartyType') ? ' has-error' : '' }}">
									<label for="cPartyType" class="form-label">Party Type</label>
									<select name="cPartyType" id="cPartyType"  class="form-control">
										<option value="1">Type 1</option>
										<option value="2">Type 2</option>
										<option value="3">Type 3</option>
									</select>
									@if ($errors->has('cPartyType'))
										<span class="help-block">
											<strong>{{ $errors->first('cPartyType') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('cPartyName') ? ' has-error' : '' }}">
									<label for="cPartyName" class="form-label">Party Name</label>
									<select name="cPartyName" id="cPartyName"  class="form-control">
										<?php echo get_political_parties($state);?>
										<option value="INDEPENDENT">Independent</option>
									</select>
									@if ($errors->has('cPartyName'))
										<span class="help-block">
											<strong>{{ $errors->first('cPartyName') }}</strong>
										</span>
									@endif
								</div>

								<div id="symbolMain" style="display:none;">
									<div class="form-group{{ $errors->has('symbol1') ? ' has-error' : '' }}">
										<label for="symbol1" class="form-label">Symbols Preference</label>
										<input type="file" name="symbol1" class="form-control">
										@if ($errors->has('symbol1'))
											<span class="help-block">
												<strong>{{ $errors->first('symbol1') }}</strong>
											</span>
										@endif
									</div>

									<div class="form-group{{ $errors->has('symbol2') ? ' has-error' : '' }}">
										<input type="file" name="symbol2" class="form-control">
										@if ($errors->has('symbol2'))
											<span class="help-block">
												<strong>{{ $errors->first('symbol2') }}</strong>
											</span>
										@endif
									</div>

									<div class="form-group{{ $errors->has('symbol3') ? ' has-error' : '' }}">
										<input type="file" name="symbol3" class="form-control">
										@if ($errors->has('symbol3'))
											<span class="help-block">
												<strong>{{ $errors->first('symbol3') }}</strong>
											</span>
										@endif
									</div>
								</div>

								<div class="form-group{{ $errors->has('cConsCode') ? ' has-error' : '' }}">
									<label for="cConsCode" class="form-label">Cons Code</label>
									<select name="cConsCode" id="cConsCode"  class="form-control">
										<?php echo get_constituencies($state,$dist); ?>
									</select>
									@if ($errors->has('cConsCode'))
										<span class="help-block">
											<strong>{{ $errors->first('cConsCode') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('cCategory') ? ' has-error' : '' }}">
									<label for="cCategory" class="form-label">Category</label>
									<select name="cCategory" id="cCategory"  class="form-control">
										<option value="1">Category 1</option>
										<option value="2">Category 2</option>
										<option value="3">Category 3</option>
									</select>
									@if ($errors->has('cCategory'))
										<span class="help-block">
											<strong>{{ $errors->first('cCategory') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('cSeralNumNomination') ? ' has-error' : '' }}">
									<label for="cSeralNumNomination" class="form-label">Serial No. of Nomination Paper</label>
									<input type="text" name="cSeralNumNomination" placeholder="Serial No. of Nomination Paper" class="form-control">
									@if ($errors->has('cSeralNumNomination'))
										<span class="help-block">
											<strong>{{ $errors->first('cSeralNumNomination') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group">
									<label class="form-label">Nomination Received Date/Time</label>
									<div class="clearfix">
										<div class="fields">
											<input type="text" name="cNominationDate" id="datepicker-14" placeholder="Nomination Received Date" class="form-control">
											@if ($errors->has('cNominationDate'))
												<span class="help-block">
													<strong>{{ $errors->first('cNominationDate') }}</strong>
												</span>
											@endif
										</div>
										<div class="fields timeField">
											<div style="position: relative">
												<input type="text" id="time" name="cNominationTime" placeholder="Nomination Received Time" class="form-control">
											</div>
											@if ($errors->has('cNominationTime'))
												<span class="help-block">
													<strong>{{ $errors->first('cNominationTime') }}</strong>
												</span>
											@endif
										</div>
									</div>	
								</div>

								<div class="form-group">
									<label class="form-label" style="display:block;">Nomination Status</label>
									<div>
										<div class="radio radio-inline">
					                       <input type="radio" id="nominationAccp" value="1" name="cNominationStatus" checked>
					                       <label for="nominationAccp">Accepted</label>
					                   </div>
					                   <div class="radio radio-inline">
					                       <input type="radio" id="nominationReject" value="0" name="cNominationStatus">
					                       <label for="nominationReject">Rejected</label>
					                   </div>
					                	@if ($errors->has('cNominationStatus'))
											<span class="help-block">
												<strong>{{ $errors->first('cNominationStatus') }}</strong>
											</span>
										@endif
				               		</div>
				               		<div id="nominationTextarea" style="display:none;">
					               		<textarea name="cRejectedText" class="form-control" placeholder="type reason here" style="margin-top:15px;"></textarea>
					               		@if ($errors->has('cRejectedText'))
											<span class="help-block">
												<strong>{{ $errors->first('cRejectedText') }}</strong>
											</span>
										@endif
				               		</div>
								</div>

								<div class="form-group">
									<label class="form-label">Scrutiny Date/Time</label>
									<div class="clearfix">
										<div class="fields">
											<input type="text" name="scrutinyDate" id="datepicker-15" placeholder="Nomination Received Date" class="form-control">
											@if ($errors->has('scrutinyDate'))
												<span class="help-block">
													<strong>{{ $errors->first('scrutinyDate') }}</strong>
												</span>
											@endif
										</div>
										<div class="fields timeField">
											<div style="position: relative">
												<input type="text" id="time1" name="scrutinyTime" placeholder="Nomination Received Time" class="form-control">
											</div>
											@if ($errors->has('scrutinyTime'))
												<span class="help-block">
													<strong>{{ $errors->first('scrutinyTime') }}</strong>
												</span>
											@endif
										</div>
									</div>	
								</div>

								<div class="form-group{{ $errors->has('cEmail') ? ' has-error' : '' }}">
									<label for="cEmail" class="form-label">Email</label>
									<input type="text" name="cEmail" id="datepicker-13" placeholder="Email" class="form-control">
									@if ($errors->has('cEmail'))
										<span class="help-block">
											<strong>{{ $errors->first('cEmail') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('cPhone') ? ' has-error' : '' }}">
									<label for="cPhone" class="form-label">Phone</label>
									<input type="text" onkeypress="return isNumber(event)" name="cPhone" id="datepicker-13" placeholder="Phone" class="form-control">
									@if ($errors->has('cPhone'))
										<span class="help-block">
											<strong>{{ $errors->first('cPhone') }}</strong>
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
	<!-- END CONTAINER -->
	<script type="text/javascript" src="{{ URL::asset('dateTimePicker/js/jquery-ui.js')}}"></script>
    <script type="text/javascript" src="{{ URL::asset('dateTimePicker/js/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{ URL::asset('dateTimePicker/js/bootstrap-datetimepicker.min.js')}}"></script>
	<script>
		function isNumber(evt) {
			var charCode = (evt.which) ? evt.which : event.keyCode
			if (charCode != 43 && charCode > 31 && (charCode < 48 || charCode > 57))
			return false;
			return true;
		}

		$(document).ready(function(){
			
			$("#datepicker-13").click(function(){
				$( "#datepicker-13" ).datepicker();
				$( "#datepicker-13" ).datepicker("show");	
			});

			$("#datepicker-14").click(function(){
				$( "#datepicker-14" ).datepicker();
				$( "#datepicker-14" ).datepicker("show");	
			});

			$("#datepicker-15").click(function(){
				$( "#datepicker-15" ).datepicker();
				$( "#datepicker-15" ).datepicker("show");	
			});

			$('#time').datetimepicker({
				format: 'HH:mm:ss'
		    });
		    $('#time1').datetimepicker({
				format: 'HH:mm:ss'
		    });

		    $("#nominationAccp").click(function(){	
				$("#nominationTextarea").hide();
			});
			$("#nominationReject").click(function(){
				$("#nominationTextarea").show();
			}); 
		});


		$("#cPartyName").change(function(){
			$("#symbolMain").hide();
		     var val=$(this).val();
		     if(val=="INDEPENDENT"){
		     	$("#symbolMain").show();
		     	
		     }
		});

	</script>
@endsection