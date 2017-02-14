@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">					
				<div class="searchBar">
					<div class="panel-title pageTitle searchPageTitle">Election Material</div>
					<input type="text" placeholder="Search" class="form-control">
					<input type="submit" value="Search" class="btn btn-default">
				</div>
			</div>
		</div>  
		
		<div class="row">
			<div class="col-md-12 col-lg-6">
				<div class="panel panel-widget">
					<div class="panel-title">Recieving Status</div>
					<div class="panel-body">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>Polling Stations</th>
									<th>Recieving Status</th>
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
									<td>Polling Station 1</td>
									<td>Recieved</td>
								</tr>
								<tr>
									<td>Polling Station 2</td>
									<td><span class="red-text">Not Recieved</span></td>
								</tr>	
								<tr>
									<td>Polling Station 3</td>
									<td>Recieved</td>
								</tr>	
								<tr>
									<td>Polling Station 4</td>
									<td>Recieved</td>
								</tr>	
								<tr>
									<td>Polling Station 5</td>
									<td><span class="red-text">Not Recieved</span></td>
								</tr>
								<tr>
									<td>Polling Station 6</td>
									<td>Recieved</td>
								</tr>							
							</tbody>
						</table>
					</div>
				</div>
			</div>	

			<div class="col-md-12 col-lg-6">
				<div class="panel panel-widget">
					<div class="panel-title">Returning Status</div>
					<div class="panel-body">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>Polling Stations</th>
									<th>Recieving Status</th>
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
									<td>Polling Station 1</td>
									<td>Recieved</td>
								</tr>
								<tr>
									<td>Polling Station 2</td>
									<td><span class="red-text">Not Returned</span></td>
								</tr>	
								<tr>
									<td>Polling Station 3</td>
									<td>Recieved</td>
								</tr>	
								<tr>
									<td>Polling Station 4</td>
									<td>Recieved</td>
								</tr>	
								<tr>
									<td>Polling Station 5</td>
									<td><span class="red-text">Not Returned</span></td>
								</tr>
								<tr>
									<td>Polling Station 6</td>
									<td>Recieved</td>
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

