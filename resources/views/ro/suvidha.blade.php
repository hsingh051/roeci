@extends('layouts.main')
@section('content')
<?php //dd($getdata->Message);?>
    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget">
					<div class="panel-title pageTitle">
						<span>Track Permission Status</span>
					</div>
					<div class="panel-body">
						<table class="table table-bordered tablefilter">
							<thead>
								<tr>
									<th>Candidate Name</th>
									<th>Applicant Type</th>
									<th>Party</th>
									<th>Applied For</th>
									<th>Requested On</th>
									<th>Status</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Candidate Name</th>
									<th>Applicant Type</th>
									<th>Party</th>
									<th>Applied For</th>
									<th>Requested On</th>
									<th>Status</th>
								</tr>
							</tfoot>
							<tbody>
							@if($getdata)
							@foreach ($getdata as $data)
								<?php 
									//dd($data);
									$visiterId = $data->VisitorType; 
									$AppliedForID = $data->AppliedForID; 
									if($visiterId == '0'){ 
										$visitertype = "Party's Representative";
									} elseif ($visiterId == '1') {
										$visitertype = "Candidate";
									}
									elseif ($visiterId == '2') {
										$visitertype = "Candidate's Representative";
									}
									elseif ($visiterId == '3') {
										$visitertype = "Election Agent";
									}
									else{
										$visitertype = 'Others';
									}

									if ($AppliedForID == '1') {
										$AppliedFor = "Application for permission to hold meeting & Loud Speaker";
									}
									elseif ($AppliedForID == '2') {
										$AppliedFor = "Application";
									}
									elseif ($AppliedForID == '3') {
										$AppliedFor = "Aplication for Vehicle Permit";
									}
									elseif ($AppliedForID == '4') {
										$AppliedFor = "Application for Permit to take out Procession & Loud Speaker";
									}
									elseif ($AppliedForID == '5') {
										$AppliedFor = "Application for Permit for Street Corner Meeting & Loud Speaker";
									}
									elseif ($AppliedForID == '6') {
										$AppliedFor = "Application for Helicopter & Helipad";
									}
									elseif ($AppliedForID == '9') {
										$AppliedFor = "Permit for construction of Rostrum/Barricade";
									}
									else{
										$AppliedFor = "Aplication for Vehicle Permit (Intra District)";
									}
								?>
								<tr>
									<td>
									<a href="{{ url('/ro/suvidha-detail') }}/{{eci_encrypt($data->ApplicationID)}}">{{ $data->Name }}</a>
									</td>
									<td>{{ $visitertype }}</td>
									<td>
										@foreach ($getparty as $party)
											<?php
												if($party['id'] == $data->PoliticalPartyID){
													echo $party['name'];
												}
											?>
										@endforeach
									</td>
									<td>{{ $AppliedFor }}</td>
									<td>{{ $data->EntryDate }}</td>
									<td>{{ $data->Status }}</td>
								</tr>
							@endforeach
							@endif								
							</tbody>
						</table>
					</div>
				</div>
			</div>		
		</div>
	</div>
	<!-- END CONTAINER -->
@endsection

