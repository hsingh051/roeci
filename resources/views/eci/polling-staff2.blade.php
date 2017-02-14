@extends('layouts.main')
@section('content')
<?php
$distCodeCheck= (isset($_GET['dist_code']))? $_GET['dist_code'] : ""; 
$consCodeCheck= (isset($_GET['cons_code']))? $_GET['cons_code'] : "";
$encryptConsCode= (isset($encryptCons))? $encryptCons : "";
$encryptDistCode= (isset($encryptDist))? $encryptDist : "";
$consList= (isset($constituency))? $constituency : "";
$pollTypeSelect= (isset($pollType))? $pollType : "";
?>
    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">
				<div class="searchBar">
					<form class="topSearchForm" enctype="multipart/form-data" method="get" action="{{url('eci/pollingStaffSub') }}">
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



						<div class="form-group{{ $errors->has('poll_type') ? ' has-error' : '' }}">
							<select name="poll_type" class="form-control">
								<option value="">Select Randomization</option>	
								<option value="first" <?php if($pollTypeSelect=='first'){ echo "selected"; } ?>>First Randomization</option>
								<option value="second" <?php if($pollTypeSelect=='second'){ echo "selected"; } ?>>Second Randomization</option>
								<option value="third" <?php if($pollTypeSelect=='third'){ echo "selected"; } ?>>Third Randomization</option>
							</select>
							@if ($errors->has('poll_type'))
							<span class="help-block">
								<strong>{{ $errors->first('poll_type') }}</strong>
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
		<?php if(($distCodeCheck!=="") && ($consCodeCheck!=="")) { ?>
		<div class="row">
			<!-- Nominations -->
			<div class="col-md-12">
				<div class="panel panel-widget">
					<div class="panel-title pageTitle">Polling Staff</div>
					<div class="panel-body">
						<div class="table-scroll">
						<table class="table table-bordered tablefilter">
							<thead>
								<tr>
									
									<th>Employee ID / Reference Number</th>
									<th>Constituency</th>
									<th>Party No.</th>
									<th>Name</th>
									<th>Designation</th>
									<th>Department</th>
									<th>Mobile</th>
									<th>Class</th>									
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Employee ID / Reference Number</th>
									<th>Constituency</th>
									<th>Party No.</th>
									<th>Name</th>
									<th>Designation</th>
									<th>Department</th>
									<th>Mobile</th>
									<th>Class</th>									
								</tr>
							</tfoot>
							<tbody>
								<!--<tr>
									<td><input type="text" class="form-control" placeholder="Search Supervisor"></td>
									<td><input type="text" class="form-control" placeholder="Search Age"></td>
									<td><input type="text" class="form-control" placeholder="Search Department"></td>
									<td></td>
									<td><input type="text" class="form-control" placeholder="Search Polling Station"></td>
									<td></td>
								</tr> -->
								<?php 
									
									foreach($polling_users as $plling_users1)
									{
								?>
										<tr>
											<td><?php echo $plling_users1->emp_id." / ".$plling_users1->ref_no; ?></td>
											<td><?php echo $plling_users1->cons_name; ?></td>
											<td><?php echo $plling_users1->party_no; ?></td>
											<td><?php echo $plling_users1->name; ?></td>
											<td><?php echo $plling_users1->designation; ?></td>
											<td><?php echo $plling_users1->department; ?></td>
											<td><?php echo $plling_users1->phone; ?></td>
											<td><?php echo strtoupper($plling_users1->elect_duty); ?></td>
											
										</tr>
								<?php 
									}
								?>
							</tbody>
						</table>
						</div>
					</div>
				</div>
			</div>	
		</div>
		<?php  } ?>
	</div>
	<!-- END CONTAINER -->
@endsection