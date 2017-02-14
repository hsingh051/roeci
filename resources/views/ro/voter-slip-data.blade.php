@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
	<link rel="stylesheet" href="{{ URL::asset('dateTimePicker/css/bootstrap-datetimepicker.min.css')}}" />
	<link rel="stylesheet" href="{{ URL::asset('dateTimePicker/css/jquery-ui.css')}}" />
    
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					@if(Session::has('VoterdataSucc'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('VoterdataSucc') }}</p>
					@endif
					<div class="panel-title pageTitle text-center">
						<span>Voter Slip Data</span>
					</div>
					<div class="panel-body">
						<div class="voterSlip">
							<?php 
								foreach($voterslipData as $voterslipData1)
								{
								}
							?>
							<form method="post" action="{{url('ro/add-voter-slip-data') }}">
								<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
								<div class="form-group mb5 clearfix">
									<div class="Fields4 fields ">
										<label class="form-label">Date</label>
									</div>
									<div class="Fields4 fields text-center">
										<label class="form-label">Total Voter Slips</label>
									</div>
									<div class="Fields4 fields text-center">
										<label class="form-label">No. of Slips Distributed</label>
									</div>
									<div class="Fields4 fields text-center">
										<label class="form-label">No. of Slips Pending</label>
									</div>
								</div>
								<div class="form-group clearfix">
									<div class="Fields4 fields">
										<input type="text" id="datepicker-1" name="date1" placeholder="Date" class="form-control" value="<?php if(isset($voterslipData1->date1)){ echo $voterslipData1->date1; } else {?> {{ old('date1') }} <?php } ?>" />
										@if ($errors->has('date1'))
											<span class="help-block">
												<strong>{{ $errors->first('date1') }}</strong>
											</span>
										@endif
									</div>
									<div class="Fields4 fields">
										<input type="number" name="total_voter_slip1" placeholder="Total Voter Slips" class="form-control" value="<?php if(isset($voterslipData1->total_voter_slip1)){ echo $voterslipData1->total_voter_slip1; } else {?> {{ old('total_voter_slip1') }} <?php } ?>" />
										@if ($errors->has('total_voter_slip1'))
											<span class="help-block">
												<strong>{{ $errors->first('total_voter_slip1') }}</strong>
											</span>
										@endif
									</div>
									<div class="Fields4 fields">
										<input type="number" name="slip_distributed1" placeholder="No. of Slips Distributed" class="form-control" value="<?php if(isset($voterslipData1->slip_distributed1)){ echo $voterslipData1->slip_distributed1; } else {?> {{ old('slip_distributed1') }} <?php } ?>" />
										@if ($errors->has('slip_distributed1'))
											<span class="help-block">
												<strong>{{ $errors->first('slip_distributed1') }}</strong>
											</span>
										@endif
									</div>
									<div class="Fields4 fields">
										<input type="number" name="slip_pending1" placeholder="No. of Slips Pending" class="form-control" value="<?php if(isset($voterslipData1->slip_pending1)){ echo $voterslipData1->slip_pending1; } else {?> {{ old('slip_pending1') }} <?php } ?>" />
										@if ($errors->has('slip_pending1'))
											<span class="help-block">
												<strong>{{ $errors->first('slip_pending1') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="form-group clearfix">
									<div class="Fields4 fields">
										<input type="text" id="datepicker-2" name="date2" placeholder="Date" class="form-control" value="<?php if(isset($voterslipData1->date2)){ echo $voterslipData1->date2; } else {?> {{ old('date2') }} <?php } ?>" />
										@if ($errors->has('date2'))
											<span class="help-block">
												<strong>{{ $errors->first('date2') }}</strong>
											</span>
										@endif
									</div>
									<div class="Fields4 fields">
										<input type="number" name="total_voter_slip2" placeholder="Total Voter Slips" class="form-control" value="<?php if(isset($voterslipData1->total_voter_slip2)){ echo $voterslipData1->total_voter_slip2; } else {?> {{ old('total_voter_slip2') }} <?php } ?>" />
										@if ($errors->has('total_voter_slip2'))
											<span class="help-block">
												<strong>{{ $errors->first('total_voter_slip2') }}</strong>
											</span>
										@endif
									</div>
									<div class="Fields4 fields">
										<input type="number" name="slip_distributed2" placeholder="No. of Slips Distributed" class="form-control" value="<?php if(isset($voterslipData1->slip_distributed2)){ echo $voterslipData1->slip_distributed2; } else {?> {{ old('slip_distributed2') }} <?php } ?>" />
										@if ($errors->has('slip_distributed2'))
											<span class="help-block">
												<strong>{{ $errors->first('slip_distributed2') }}</strong>
											</span>
										@endif
									</div>
									<div class="Fields4 fields">
										<input type="number" name="slip_pending2" placeholder="No. of Slips Pending" class="form-control" value="<?php if(isset($voterslipData1->slip_pending2)){ echo $voterslipData1->slip_pending2; } else {?> {{ old('slip_pending2') }} <?php } ?>" />
										@if ($errors->has('slip_pending2'))
											<span class="help-block">
												<strong>{{ $errors->first('slip_pending2') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="form-group clearfix">
									<div class="Fields4 fields">
										<input type="text" id="datepicker-3" name="date3" placeholder="Date" class="form-control" value="<?php if(isset($voterslipData1->date3)){ echo $voterslipData1->date3; } else {?> {{ old('date3') }} <?php } ?>" />
										@if ($errors->has('date3'))
											<span class="help-block">
												<strong>{{ $errors->first('date3') }}</strong>
											</span>
										@endif
									</div>
									<div class="Fields4 fields">
										<input type="number" name="total_voter_slip3" placeholder="Total Voter Slips" class="form-control" value="<?php if(isset($voterslipData1->total_voter_slip3)){ echo $voterslipData1->total_voter_slip3; } else {?> {{ old('total_voter_slip3') }} <?php } ?>" />
										@if ($errors->has('total_voter_slip3'))
											<span class="help-block">
												<strong>{{ $errors->first('total_voter_slip3') }}</strong>
											</span>
										@endif
									</div>
									<div class="Fields4 fields">
										<input type="number" name="slip_distributed3" placeholder="No. of Slips Distributed" class="form-control" value="<?php if(isset($voterslipData1->slip_distributed3)){ echo $voterslipData1->slip_distributed3; } else {?> {{ old('slip_distributed3') }} <?php } ?>" />
										@if ($errors->has('slip_distributed3'))
											<span class="help-block">
												<strong>{{ $errors->first('slip_distributed3') }}</strong>
											</span>
										@endif
									</div>
									<div class="Fields4 fields">
										<input type="number" name="slip_pending3" placeholder="No. of Slips Pending" class="form-control" value="<?php if(isset($voterslipData1->slip_pending3)){ echo $voterslipData1->slip_pending3; } else {?> {{ old('slip_pending3') }} <?php } ?>" />
										@if ($errors->has('slip_pending3'))
											<span class="help-block">
												<strong>{{ $errors->first('slip_pending3') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<button type="submit" class="btn btn-default"><?php if(isset($voterslipData1->date1)){ echo "Update"; } else { echo "Submit"; } ?></button>
							</form>
						</div>
					</div>
				</div>
			</div>		
		</div>  
	</div>
	
	<script type="text/javascript" src="{{ URL::asset('dateTimePicker/js/jquery-ui.js')}}"></script>
    <script type="text/javascript" src="{{ URL::asset('dateTimePicker/js/bootstrap-datetimepicker.min.js')}}"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#datepicker-1").click(function(){
				$( "#datepicker-1" ).datepicker();
				$( "#datepicker-1" ).datepicker("show");	
			});
			$('#datepicker-1').keypress(function(e) {
			    return false
			});
			var dateToday = new Date();
			var dates = $("#datepicker-1").datepicker({
				minDate: dateToday,
			});
			
			$("#datepicker-2").click(function(){
				$( "#datepicker-2" ).datepicker();
				$( "#datepicker-2" ).datepicker("show");	
			});
			$('#datepicker-2').keypress(function(e) {
			    return false
			});
			var dateToday = new Date();
			var dates = $("#datepicker-2").datepicker({
				minDate: dateToday,
			});
			
			$("#datepicker-3").click(function(){
				$( "#datepicker-3" ).datepicker();
				$( "#datepicker-3" ).datepicker("show");	
			});
			$('#datepicker-3').keypress(function(e) {
			    return false
			});
			var dateToday = new Date();
			var dates = $("#datepicker-3").datepicker({
				minDate: dateToday,
			});
		});
	</script>
@endsection

