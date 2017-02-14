@extends('layouts.main')
@section('content')
	<!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<!-- Nominations -->
			<div class="col-md-12">
				<div class="panel panel-widget">
					<div class="panel-title pageTitle titleBtn clearfix">
                        <span>DEO Management</span>
                        <div class="panel-btn">
                        	<a href="{{ url('ceo/addDeo') }}" class="btn btn-default">Add New DEO</a>
                        	<a href="javascript:void(0);" class="btn btn-default">Generate Report</a>
                        </div>
                    </div>
					<div class="panel-body">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>DEO Name</th>
									<th>District</th>
									<th>Address</th>
									<th>Email id</th>
									<th>Phone Number</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php
									$deoList = DB::table('users')->where('role', 3)->get();
								?>
								@foreach ($deoList as $deoLists)
								<tr>
									<td>{{ $deoLists->name }}</td>
									<td>
									<?php
										$distCode=$deoLists->dist_code;
										$deoDist = DB::table('districts')->where('dist_code', $distCode)->get();
										foreach ($deoDist as $deoDists){
											echo $deoDists->dist_name;
										}
									?>
									</td>
									<td>{{ $deoLists->address }}</td>
									<td>{{ $deoLists->email }}</td>
									<td>{{ $deoLists->phone }}</td>
									<td>
										<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
										<i class="fa fa-times" aria-hidden="true"></i>
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
			$(".delLRoBtn").click(function(){
				var answer=confirm('Do you want to delete RO?');
				if(answer){
					return true;
				}else{
					return false;
				}
			});
		});
	</script>
@endsection

