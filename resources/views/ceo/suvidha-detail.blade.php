@extends('layouts.main')

@section('content')
	<!-- START CONTAINER -->
	<div class="container-widget">	  
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget">
					<div class="panel-title pageTitle titleBtn clearfix">
						<!-- <div class="panel-btn">
							<a href="{{URL::previous()}}" class="btn btn-default">Back</a>
						</div> -->
					</div>
					<div class="panel-body">
						<div class="candidateDetails clearfix">
							<div class="Info">
								<ul>
									<?php
										$visiterId = $getdata[0]->VisitorType; 
										$AppliedForID = $getdata[0]->AppliedForID; 
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
									<li><b>Name:</b> {{ $getdata[0]->Name }}</li>
									<li><b>Epic No.:</b> {{ $getdata[0]->EPIC_NO }}</li>
									<li><b>Address:</b> {{ $getdata[0]->Address }}</li>
									<li><b>Mobile:</b> {{ $getdata[0]->MobileNo }}</li>
									<li><b>Email:</b>{{ $getdata[0]->EmailID }} </li>
									<li><b>Applicant Type:</b> {{ $visitertype }}</li>
									<li><b>Applied For:</b>{{$AppliedFor}} </li>
									<li><b>Requested On:</b>{{ $getdata[0]->permissinDateTime }} </li>
									<li><b>Permission Place:</b>{{ $getdata[0]->permissinPlace }} </li>
									<li><b>Status:</b>{{ $getdata[0]->Status }}</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>			
		</div>  
	</div>
	<!-- END CONTAINER -->
@endsection