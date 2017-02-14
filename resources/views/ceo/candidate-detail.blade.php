@extends('layouts.main')

@section('content')
	<!-- START CONTAINER -->
	<div class="container-widget">	  
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget">
					<div class="panel-title pageTitle titleBtn clearfix">
						<div class="panel-btn">
							<a href="{{URL::previous()}}" class="btn btn-default">Back</a>
						</div>
					</div>
					<div class="panel-body">
						<div class="candidateDetails clearfix">
							<?php
								$proPic=$candidateDetail->profile_pic;
								$symbol_pic=$candidateDetail->symbol_pic;
								if($candidateDetail->Recognisedbyparty=="N")
								{
									$Recognisedbyparty = "National Party";
								}
								elseif($candidateDetail->Recognisedbyparty=="U")
								{
									$Recognisedbyparty = "Unorganized";
								}
								elseif($candidateDetail->Recognisedbyparty=="I")
								{
									$Recognisedbyparty = "Independent";
								}
								elseif($candidateDetail->Recognisedbyparty=="S")
								{
									$Recognisedbyparty = "State Party";
								}
								else
								{
									$Recognisedbyparty = "";
								}

								if($candidateDetail->nominationStatus=="N")
								{
									$nominationStatus = "Normal";
								}
								elseif($candidateDetail->nominationStatus=="R")
								{
									$nominationStatus = "Rejected";
								}
								elseif($candidateDetail->nominationStatus=="W")
								{
									$nominationStatus = "With Drawal";
								}
								elseif($candidateDetail->nominationStatus=="D")
								{
									$nominationStatus = "Duplicate";
								}
								else
								{
									$nominationStatus = "";
								}
							?>
							<div class="Pic"><img src="{{ URL::asset('images/candidate/profilePicture/'.$proPic)}}" /></div>
							<div class="Info">
								<ul>
									<li><b>Nomination Sr. No.:</b> {{ $candidateDetail->cand_symbol }}</li>
									<li><b>Name:</b> {{ ucwords($candidateDetail->name) }} ({{($candidateDetail->cand_name_pb)}})</li>
									<li><b>Father/Mother/Husband Name:</b> {{ ucwords($candidateDetail->guardian_name)}} ({{($candidateDetail->cand_fname_pb)}})</li>
									<li><b>Age:</b> {{ $candidateDetail->cand_age }} Years</li>
									<li><b>Phone:</b> {{ $candidateDetail->phone }}</li>
									<li><b>Address:</b> {{ $candidateDetail->address }}</li>
									<li><b></b>{{($candidateDetail->cand_address_pb)}} </li>
									<li><b>Constituency:</b>{{($candidateDetail->cons_name)}} </li>
									<li><b>District:</b>{{($candidateDetail->dist_name)}} </li>
									<li><b>Party Name:</b> {{ ucwords($candidateDetail->cand_party) }}</b> </li>
									
									<li><b>Symbol:</b> <img style="height:80px;" src="{{ URL::asset($symbol_pic)}}" /></li>
									<li><b>Recognized by Party:</b> {{ $Recognisedbyparty }}</li>
									<li><b>Symbol Name:</b> {{ $candidateDetail->cand_symbol_name }}</li>
									<?php 
									if($candidateDetail->cand_party=="Independent")
									{
									?>
									<li><b>Symbol 1:</b> {{ $candidateDetail->symbol1 }}</li>
									<li><b>Symbol 2:</b> {{ $candidateDetail->symbol2 }}</li>
									<li><b>Symbol 3:</b> {{ $candidateDetail->symbol3 }}</li>
									<?php 
									}
									?>
									<li><b>Nomination Date:</b> {{ $candidateDetail->nominationDate }}</li>
									<li><b>Nomination Date:</b> {{ $nominationStatus }}</li>
									<li><b>Withdrawal Date:</b> {{ $candidateDetail->withdrawal_date }}</li>
									<li><b>Rejection Date:</b> {{ $candidateDetail->rejection_date }}</li>
									<li><b>Rejection Reason:</b> {{ $candidateDetail->rejectionReason }}</li>
									<li><b>Withdraw Refernce No:</b> {{ $candidateDetail->withdraw_refno }}</li>


									<li><b>Candidate Main Sub:</b> {{ $candidateDetail->cand_main_sub }}</li>
									<li><b>From AB Received:</b> {{ $candidateDetail->fromab_recv }}</li>
									<li><b>Form 2b Id:</b> {{ $candidateDetail->form2b_id }}</li>
									<li><b>Form 26 Id:</b> {{ $candidateDetail->form26_id }}</li>
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