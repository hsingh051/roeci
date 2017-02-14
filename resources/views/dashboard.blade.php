@extends('layouts.app')
@section('content')
    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<!-- Nominations -->
			<div class="col-md-12 col-lg-4">
				<div class="panel panel-widget">
					<div class="panel-title">Nominations</div>
					<div class="panel-body" style="min-height: 253px;">
						<ul class="basic-list">
							<li>Total Application Recieved <span class="right label label-primary">80</span></li>
							<li>Scrutiny <span class="right label label-primary">15</span></li>
							<li>Withdrawls <span class="right label label-primary">50</span></li>
							<li>Total Candidates Contesting <span class="right label label-primary">200</span></li>
						</ul>
					</div>
				</div>
			</div>
			<!-- End Nominations -->


			<!-- Election Material -->
			<div class="col-md-12 col-lg-4">
				<div class="panel panel-widget">
					<div class="panel-title">Election Material Distribution (P - 1)</div>
					<div class="panel-body">
						<div id="election-material-chart" class="ct-chart ct-perfect-fourth"></div>
					</div>
				</div>
			</div>
			<!-- End Election Material -->
			
			<!-- Daily Activity -->
			<div class="col-md-12 col-lg-4">
				<div class="panel panel-widget">
					<div class="panel-title">Daily Activity</div>
					<div class="panel-body" style="min-height: 253px;">
						
					</div>
				</div>
			</div>
			<!-- Daily Activity -->
		</div>  
		
		<div class="row">
			<!-- Polling Personnel -->
			<div class="col-md-12 col-lg-4">
				<div class="panel panel-widget">
					<div class="panel-title">Polling Personnel</div>
					<div class="panel-body table-responsive">
						<table class="table">
							<thead>
								<tr>
									<td>Randomisation</td>
									<td>Status</td>
									<td>Date</td>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>First Randomisation</td>
									<td class="color-down">Pending</td>
									<td></td>
								</tr>
								<tr>
									<td>Second Randomisation</td>
									<td class="color-up">Completed</td>
									<td>01/10/2016</td>
								</tr>
								<tr>
									<td>Third Randomisation</td>
									<td class="color-up">Completed</td>
									<td>03/10/2016</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- End Polling Personnel -->
			
			<!-- Training -->
			<div class="col-md-12 col-lg-4">
				<div class="panel panel-widget">
					<div class="panel-title">Training</div>
					<div class="panel-body table-responsive">
						<table class="table">
							<thead>
								<tr>
									<td>Training</td>
									<td>Date</td>
									<td>Status</td>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Training1</td>
									<td></td>
									<td class="color-down">Pending</td>
								</tr>
								<tr>
									<td>Training2</td>
									<td>01/10/2016</td>
									<td class="color-up">Completed</td>
								</tr>
								<tr>
									<td>Training3</td>
									<td>03/10/2016</td>
									<td class="color-up">Completed</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- End Training -->
			
			<!-- Daily Activity -->
			<div class="col-md-12 col-lg-4">
				<div class="panel panel-widget">
					<div class="panel-title">Daily Activity</div>
					<div class="panel-body" style="min-height: 201px;">
						
					</div>
				</div>
			</div>
			<!-- Daily Activity -->			
		</div>
		
		<div class="row">
			<!-- Polling Stations -->
			<div class="col-md-12 col-lg-4">
				<div class="panel panel-widget">
					<div class="panel-title">Polling Stations</div>
					<div class="panel-body">
						<div id="polling-station-chart" class="ct-chart ct-perfect-fourth"></div>
					</div>
				</div>
			</div>
			<!-- End Polling Stations -->
			
			<!-- Request for Permission -->
			<div class="col-md-12 col-lg-4">
				<div class="panel panel-widget">
					<div class="panel-title">Request for Permission</div>
					<div class="panel-body">
						<div id="request-permission-chart" class="ct-chart ct-perfect-fourth"></div>
					</div>
				</div>
			</div>
			<!-- End Request for Permission -->
			
			<!-- Daily Activity -->
			<div class="col-md-12 col-lg-4">
				<div class="panel panel-widget">
					<div class="panel-title">Daily Activity</div>
					<div class="panel-body" style="min-height: 253px;">
						
					</div>
				</div>
			</div>
			<!-- Daily Activity -->			
		</div>
	</div>
	<!-- END CONTAINER -->
@endsection

