@extends('layouts.main')
@section('content')
	<?php
		$distCodeCheck= (isset($_GET['dist_code']))? $_GET['dist_code'] : ""; 
		$consCodeCheck= (isset($_GET['cons_code']))? $_GET['cons_code'] : "";
		$polltimingGet= (isset($_GET['polltiming']))? $_GET['polltiming'] : "";
		$encryptConsCode= (isset($encryptCons))? $encryptCons : "";
		$encryptDistCode= (isset($encryptDist))? $encryptDist : "";
		$consList= (isset($constituency))? $constituency : "";
		$polltiming= (isset($polltiming))? $polltiming : "";
	?>
    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">
				<div class="searchBar">
					<form class="topSearchForm" enctype="multipart/form-data" method="get" action="{{url('eci/pollPercentagetiming') }}">
					<input type="hidden" name="_token" value="<?= csrf_token(); ?>">

						<div class="form-group{{ $errors->has('dist_code') ? ' has-error' : '' }}">
							<select name="dist_code" class="form-control poll_dist_code">
									<option value="">Select district</option>
								@foreach($district as $districts)
									<?php $distCode=eci_encrypt($districts->dist_code); ?>
									<option value="{{$distCode}}" <?php if($distCode==$encryptDistCode){ echo "selected"; } ?> >{{ $districts->dist_name }}</option>
								@endforeach
							</select>
							@if ($errors->has('dist_code'))
							<span class="help-block">
								<strong>{{ $errors->first('dist_code') }}</strong>
							</span>
							@endif
						</div>

						<div class="form-group{{ $errors->has('cons_code') ? ' has-error' : '' }}">
							<select name="cons_code" class="form-control poll_cons_code">
								<option value="">Select Constituency</option>
								@if($consList)
									@foreach($consList as $constituencies)
										<?php $consCode=eci_encrypt($constituencies->cons_code); ?>
										<option value="{{$consCode}}" <?php if($consCode==$encryptConsCode){ echo "selected"; } ?> >{{ $constituencies->cons_name }}</option>
									@endforeach
								@endif
							</select>
							@if ($errors->has('cons_code'))
							<span class="help-block">
								<strong>{{ $errors->first('cons_code') }}</strong>
							</span>
							@endif
						</div>

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
		<?php if(($distCodeCheck!=="" && $consCodeCheck!=="" && $polltimingGet!=="") || ($encryptDistCode!=="" && $encryptConsCode!=="" && $polltiming!=="")) { ?>
		
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
									<th>Male%</th>
									<th>Female%</th>
									<th>Total</th>
									<th>Activity Time</th>									
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Polling Stations</th>
									<th>Queue</th>
									<th>Male%</th>
									<th>Female%</th>
									<th>Total</th>
									<th>Activity Time</th>									
								</tr>
							</tfoot>
							<tbody>									
								@foreach($pollpercentages as $pollpercentage)
								<?php
									$jsonearraye = json_decode($pollpercentage->$polltiming);
								?>
								<tr>
									<td><a href="{{ url('/eci/polling-percentage-detail') }}/{{eci_encrypt($pollpercentage->bid)}}">{{ $pollpercentage->poll_building }}</a></td>
									<?php
									if(!empty($jsonearraye)){
									?>
									<td><?php echo $jsonearraye->queue; ?></td>
									<td><?php echo $jsonearraye->male;  ?></td>
									<td><?php echo $jsonearraye->female;  ?></td>
									<td><?php echo $jsonearraye->percentage;  ?></td>
									<td><?php echo $jsonearraye->activity_time;  ?></td>
									<?php } else {?>
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
		<?php  } ?>
	</div>
	<!-- END CONTAINER -->
@endsection