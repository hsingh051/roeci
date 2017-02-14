@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					<div class="panel-title pageTitle text-center">Polled EVM & Election Material</div>
					<h2 class="subTitle">Dispatch &amp; Collection Centre: Model Town</h2>
					<div class="panel-body">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>Polling Station Name</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><input type="text" class="form-control" placeholder="Search Polling Station"></td>
									<td>
										<select class="form-control">
											<option>Select</option>
											<option>Received</option>
											<option>Not Received</option>
										</select>
									</td>
								</tr>
								<tr>
									<td>Sahnewal</td>
									<td>Received</td>
								</tr>
								<tr>
									<td>Sherpur</td>
									<td><span class="red-text">Not Received</span></td>
								</tr>
								<tr>
									<td>Samrala Chownk</td>
									<td>Received</td>
								</tr>
								<tr>
									<td>Basti Jodhewal</td>
									<td>Received</td>
								</tr>
								<tr>
									<td>Shivpuri</td>
									<td><span class="red-text">Not Received</span></td>
								</tr>
								<tr>
									<td>Sahnewal</td>
									<td>Received</td>
								</tr>
								<tr>
									<td>Sherpur</td>
									<td><span class="red-text">Not Received</span></td>
								</tr>
								<tr>
									<td>Samrala Chownk</td>
									<td>Received</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- END CONTAINER -->
@endsection