@extends('layouts.main')
@section('content')
	<!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<!-- Nominations -->
			<div class="col-md-12">
				<div class="panel panel-widget">
				@if(Session::has('addCeoSucc'))
				<p class="alert alert-success">{{ Session::get('addCeoSucc') }}</p>
				@endif
				@if(Session::has('addCeoErr'))
				<p class="alert alert-danger">{{ Session::get('addCeoErr') }}</p>
				@endif
					<div class="panel-title pageTitle titleBtn clearfix">
                        <span>CEO Management</span>
                        <div class="panel-btn">
                        	<a href="{{ url('eci/add_ceo') }}" class="btn btn-default">Add New CEO</a>
                        	<!-- <a href="javascript:void(0);" class="btn btn-default">Generate Report</a> -->
                        </div>
                    </div>
					<div class="panel-body">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>CEO Name</th>
									<th>State</th>
									<th>Phone Number</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($ceolist as $ceolists)
								<tr>
									<td>{{ $ceolists->name }}</td>
									<td>{{ $ceolists->state_name }}</td>
									<td>{{ $ceolists->phone }}</td>
									<td>
										<?php
											$ceoUidEncrypt=eci_encrypt($ceolists->uid);
										?>
										<a href="{{ url('eci/editCeo') }}/<?php echo $ceoUidEncrypt; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
										<a class="delCeo" href="{{ url('eci/deleteCeo') }}/<?php echo $ceoUidEncrypt; ?>"><i class="fa fa-times" aria-hidden="true"></i></a>
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
			$(".delCeo").click(function(){
				var answer=confirm('Do you want to delete?');
				if(answer){
					return true;
				}else{
					return false;
				}
			});
		});
	</script>
@endsection