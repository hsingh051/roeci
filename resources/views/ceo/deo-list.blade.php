@extends('layouts.main')
@section('content')
	<!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<!-- Nominations -->
			<div class="col-md-12">
				<div class="panel panel-widget">
					@if(Session::has('reqErrDeo'))
						<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('reqErrDeo') }}</p>
					@endif
					@if(Session::has('DistErrDeo'))
						<p class="alert alert-class alert-info">{{ Session::get('DistErrDeo') }}</p>
					@endif
					@if(Session::has('addDeoSucc'))
						<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('addDeoSucc') }}</p>
					@endif
					@if(Session::has('editDeoErr'))
						<p class="alert alert-danger">{{ Session::get('editDeoErr') }}</p>
					@endif
					@if(Session::has('phoneErrDeo'))
						<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('phoneErrDeo') }}</p>
					@endif
					<div class="panel-title pageTitle titleBtn clearfix">
                        <span>DEO Management</span>
                        <div class="panel-btn">
                        	<a href="javascript:void(0);" class="btn btn-default">Generate Report</a>
                        	<a href="{{ url('ceo/addDeoForm') }}" class="btn btn-default">Add New DEO</a>
                        </div>
                    </div>
                    <div id="notice"></div>
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
								@foreach ($getdeolist as $deoLists)
								<?php
									$deoId=eci_encrypt($deoLists->uid);
								?>
								<tr>
									<td><a href="{{ url('ceo/ro-list') }}/<?php echo $deoId; ?>">{{ $deoLists->name }}</a></td>
									<td>{{ $deoLists->dist_name }}</td>
									<td>{{ $deoLists->address }}</td>
									<td>{{ $deoLists->email }}</td>
									<td>{{ $deoLists->phone }}</td>
									<td>
										<a href="{{ url('ceo/editDeo') }}/<?php echo $deoId; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
										<a class="delDeos" id="<?php echo $deoId; ?>" rel="<?= csrf_token(); ?>"><i class="fa fa-times" aria-hidden="true"></i></a>
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
			$(".delDeo").click(function(){
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