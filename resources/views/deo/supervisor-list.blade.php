@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<!-- Nominations -->
			<div class="col-md-12">
				<div class="panel panel-widget">
					<?php 
						if(!empty(Session::get('supvSucc'))){
							echo "<strong><span style='font-weight:700;color: #a94442;'>".Session::get('supvSucc')."</span></strong>";
		            		Session::forget('supvSucc');	
						}
	            	?>
					<div class="panel-title pageTitle titleBtn clearfix">
						<span>{{ $getRoDetail->name }} {{ $getRoDetail->cons_name }}</span>
					</div>
					<div class="panel-body">
						<table class="table table-bordered tablefilter">
							<thead>
								<tr>
									<th>Sector Officer</th>
									<th>Contact Number</th>
									<th>Department</th>
									<th>Designation</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Sector Officer</th>
									<th>Contact Number</th>
									<th>Department</th>
									<th>Designation</th>
								</tr>
							</tfoot>
							<tbody>
								@foreach ($supervisors as $supervisor)
								<tr>
								<?php
									$uid = eci_encrypt($supervisor->uid);
								?>
									<td><a href="{{url('deo/supervisor-detail') }}/<?php echo $uid; ?>">{{ $supervisor->name }}</a></td>
									<td>{{ $supervisor->phone }}</td>
									<td>{{ $supervisor->organisation }}</td>
									<td>{{ $supervisor->designation }}</td>
								</tr>	
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- End Nominations -->
		</div>  
	</div>
	<!-- END CONTAINER -->
@endsection