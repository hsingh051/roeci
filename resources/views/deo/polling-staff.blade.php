@extends('layouts.main')
@section('content')
<?php //dd($polling_users);?>
    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">	
				<div class="searchBar">
					<form class="topSearchForm" enctype="multipart/form-data" method="post" action="{{url('deo/polling-staff') }}" >
						<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
						<div class="form-group">
							<select class="form-control" name="staff_type">
								<!-- <option>Select Randomization</option> -->
								<option value="first" selected="selected">First Randomization</option>
								<option value="second">Second Randomization</option>
								<option value="third">Third Randomization</option>
							</select>
						</div>
						<!-- <select class="form-control">
							<?php
								// $user = Auth::user();
								// $details = json_decode($user);
								// $dist_code = $details->dist_code;
								// $state_id = "53";
								// echo get_constituencies($state_id,$dist_code); 
							?>
						</select> -->
						<div class="form-group">
							<input type="submit" value="Search" class="btn btn-default" />
						</div>
					</form>
				</div>
			</div>
		</div>
		<div style="color:red;"><?php echo Session::get('addPollinguser') ?></div>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget">
					<div class="panel-title pageTitle titleBtn clearfix">
						<span>Polling Staff</span>
						<div class="panel-btn">
							<a href="{{url('deo/add-polling-staff') }}" class="btn btn-default">Add Staff</a>
							<!-- <a href="javascript:void(0);" class="btn btn-default">Generate Report</a> -->
						</div>
					</div>
					<div class="panel-body">
						<table class="table table-bordered tablefilter">
							<thead>
								<tr>
									<th>Employee ID/Reference Number</th>
									<th>Constituency</th>
									<th>Name</th>
									<th>Designation</th>
									<th>Department</th>
									<th>Mobile</th>
									<th>Class</th>									
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Employee ID/Reference Number</th>
									<th>Constituency</th>
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
											<td><?php echo $plling_users1->emp_id."/".$plling_users1->ref_no; ?></td>
											<td><?php echo $plling_users1->cons_name; ?></td>
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
	<!-- END CONTAINER -->
@endsection

