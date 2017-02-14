@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<!-- Nominations -->
			<div class="col-md-12">
				<div class="panel panel-widget">
	            	@if(Session::has('supvSucc'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('supvSucc') }}</p>
					@endif
					<div class="panel-title pageTitle titleBtn clearfix">
						<span>Sector Officer List</span>
						<div class="panel-btn">
							<a href="{{url('ro/add-supervisor') }}" class="btn btn-default">Add New Sector Officer</a>
						</div>
					</div>
					<div class="panel-body">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>Sector Officer Name</th>
									<th>Contact Number</th>
									<th>Department</th>
									<th>Designation</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								@foreach ($supervisors as $supervisor)
								<tr>
								<?php
									$uid = eci_encrypt($supervisor->uid);
								?>
									<td><a href="{{url('ro/supervisor-detail') }}/<?php echo $uid; ?>">{{ $supervisor->name }}</a></td>
									<td>{{ $supervisor->phone }}</td>
									<td>{{ $supervisor->organisation }}</td>
									<td>{{ $supervisor->designation }}</td>
									<td>
										<a href="{{url('ro/supervisorEdit') }}/<?php echo $uid; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
										<a class="delTraning" href="{{url('ro/supervisorDel') }}/<?php echo $uid; ?>"><i class="fa fa-times" aria-hidden="true"></i></a>
									</td>
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
	<script type="text/javascript">
		$(document).ready(function(){
			$(".delTraning").click(function(){
				var answer=confirm('Do you want to delete?');
				if(!answer){
					return false;
				}
			});
		});
	</script>
@endsection


