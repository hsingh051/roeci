@extends('layouts.main')
@section('content')
	<link rel="stylesheet" href="{{ URL::asset('dateTimePicker/css/bootstrap-datetimepicker.min.css')}}" />
	<link rel="stylesheet" href="{{ URL::asset('dateTimePicker/css/jquery-ui.css')}}" />
    <!-- START CONTAINER -->
	<div class="container-widget">
		
		<div class="row">
			<div class="col-md-12">
				<div class="searchBar">
					<form class="topSearchForm" enctype="multipart/form-data" method="get" action="{{url('ro/pollPercentagetiming') }}">
					<input type="hidden" name="_token" value="<?= csrf_token(); ?>">

						<div class="form-group{{ $errors->has('polltiming') ? ' has-error' : '' }}">
							<select name="polltiming" class="form-control" id="polltiming">
									<option value="">Select Time</option>
									<option value="percentage_8" <?php if($polltiming=='percentage_8'){ echo "selected"; } ?> >8:00 AM</option>
									<option value="percentage_10" <?php if($polltiming=='percentage_10'){ echo "selected"; } ?> >10:00 AM</option>
									<option value="percentage_12" <?php if($polltiming=='percentage_12'){ echo "selected"; } ?> >12:00 PM</option>
									<option value="percentage_14" <?php if($polltiming=='percentage_14'){ echo "selected"; } ?> >2:00 PM</option>
									<option value="percentage_16" <?php if($polltiming=='percentage_16'){ echo "selected"; } ?> >4:00 PM</option>
									<option value="percentage_18" <?php if($polltiming=='percentage_18'){ echo "selected"; } ?> >6:00 PM</option>
							</select>
							@if ($errors->has('polltiming'))
							<span class="help-block">
								<strong>{{ $errors->first('polltiming') }}</strong>
							</span>
							@endif
						</div>

						<div class="form-group">
							<button type="submit" class="btn btn-default">Submit</button>
						</div>						
					</form>
				</div>
			</div>
		</div>
		
		<div class="row">
			<!-- Nominations -->
			<div class="col-md-12">
				<div class="panel panel-widget">
					<div class="panel-title pageTitle">POLL PERCENTAGE</div>
					<div class="panel-body">
						<div class="table-scroll">
						<table class="table table-bordered tablefilter">
							<thead>
								<tr>
									<th>Polling Stations</th>
									<th>Queue</th>
									<th>Male</th>
									<th>Female</th>
									<th>Transgender</th>
									<th>Total%</th>
									<th>Activity Time</th>								
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Polling Stations</th>
									<th>Queue</th>
									<th>Male</th>
									<th>Female</th>
									<th>Transgender</th>
									<th>Total%</th>
									<th>Activity Time</th>									
								</tr>
							</tfoot>
							<tbody>									
								@foreach($pollpercentages as $pollpercentage)
								<?php
									$jsonearraye = json_decode($pollpercentage->$polltiming);
									// print_r($jsonearraye);
									// die();
								?>
								<tr>
									<td><a href="{{ url('/ro/polling-percentage-detail') }}/{{eci_encrypt($pollpercentage->bid)}}">{{ $pollpercentage->poll_building }}</a></td>
									<?php
									if(!empty($jsonearraye)){
									?>
									<td><?php echo $jsonearraye->queue; ?></td>
									<td><?php echo $jsonearraye->male;  ?></td>
									<td><?php echo $jsonearraye->female;  ?></td>
									<td><?php echo $jsonearraye->tg;  ?></td>
									<td><?php echo $jsonearraye->percentage;  ?></td>
									<td><?php echo $jsonearraye->activity_time;  ?></td>
									<?php } else {?>
									<td>Pending</td>
									<td>Pending</td>
									<td>Pending</td>
									<td>Pending</td>
									<td>Pending</td>
									<td>Pending</td>
									<?php	} ?>
								</tr>
								@endforeach
								
							</tbody>
						</table>
						</div>
					</div>
				</div>
			</div>
			<!-- End Nominations -->
		
		</div>  
	</div>
	<!-- END CONTAINER -->
	<script type="text/javascript" src="{{ URL::asset('dateTimePicker/js/jquery-ui.js')}}"></script>
    <script type="text/javascript" src="{{ URL::asset('dateTimePicker/js/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{ URL::asset('dateTimePicker/js/bootstrap-datetimepicker.min.js')}}"></script>
	<script type="text/javascript">
		$(document).ready(function(){
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
		});
	</script>
@endsection