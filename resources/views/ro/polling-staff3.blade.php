@extends('layouts.main')
@section('content')
<?php //dd($polling_users);?>
    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">	
				<div class="searchBar">
					<form class="topSearchForm" enctype="multipart/form-data" method="post" action="{{url('ro/polling-staff') }}" >
						<input type="hidden" name="_token" value="<?= csrf_token(); ?>">						
						<div class="form-group">
							<select class="form-control" name="staff_type">
								<!-- <option>Select Randomization</option> -->
								<option value="first" >First Randomization</option>
								<option value="second" >Second Randomization</option>
								<option value="third" selected="selected">Third Randomization</option>
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
					<div class="panel-title pageTitle">
						<span>Polling Staff</span>
					</div>
					<div class="panel-body">
						<table class="table table-bordered tablefilter">
							<thead>
								<tr>
									<th>Supervisor</th>
									<th>Contact No.</th>
									<th>Assigned Polling Station</th>
									<th class="w22">Polling Party Details</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Supervisor</th>
									<th>Contact No.</th>
									<th>Assigned Polling Station</th>
									<th class="w22">Polling Party Details</th>
								</tr>
							</tfoot>
							<tbody>
								@foreach ($polling_staff as $pstaff)
								<tr>
									<td><?php echo $pstaff['supervisor_name'];?></td>
									<td><?php echo $pstaff['supervisor_phone'];?></td>
									<td><?php echo $pstaff['poll_building'];?></td>
									<td>
										<?php 
											foreach ($pstaff['staff'] as $value1) {
												echo $value1->elect_duty." : ".$value1->name."<br>";

											}
										?>
										<!-- PRO: Munish Kumar<br>PO1: Satish Kumar<br>PO2: Jatin Kumar<br> -->
										<a href="{{ url('/ro/polling-parties-details') }}/<?php echo eci_encrypt($pstaff['bid']);?>">Click here for more details</a>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>		
		</div>
	</div>
	<!-- END CONTAINER -->
@endsection

