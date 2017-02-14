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
									@if($candidateDetail->cand_symbol)<li><b>Nomination Sr. No.:</b> {{ $candidateDetail->cand_symbol }}</li>@endif

									@if($candidateDetail->name)<li><b>Name:</b> {{ ucwords($candidateDetail->name) }}</li>@endif

									@if($candidateDetail->guardian_name)<li><b>Father/Mother/Husband Name:</b> {{ ucwords($candidateDetail->guardian_name)}} ({{($candidateDetail->cand_fname_pb)}})</li>@endif

									@if($candidateDetail->cand_age)<li><b>Age:</b> {{ $candidateDetail->cand_age }} Years</li>@endif

									@if($candidateDetail->phone)<li><b>Phone:</b> {{ $candidateDetail->phone }}</li>@endif

									@if($candidateDetail->address)<li><b>Address:</b> {{ $candidateDetail->address }}</li>@endif

									@if($candidateDetail->cand_address_pb)<li><b></b>{{($candidateDetail->cand_address_pb)}} </li>@endif

									@if($candidateDetail->cons_name)<li><b>Constituency:</b>{{($candidateDetail->cons_name)}} </li>@endif

									@if($candidateDetail->dist_name)<li><b>District:</b>{{($candidateDetail->dist_name)}} </li>@endif

									@if($candidateDetail->cand_party)<li><b>Party Name:</b> {{ ucwords($candidateDetail->cand_party) }}</b> </li>@endif

									@if($symbol_pic)<li><b>Symbol:</b> <img style="height:80px;" src="{{ URL::asset($symbol_pic)}}" /></li>@endif

									@if($Recognisedbyparty)<li><b>Recognized by Party:</b> {{ $Recognisedbyparty }}</li>@endif

									@if($candidateDetail->cand_symbol_name)<li><b>Symbol Name:</b> {{ $candidateDetail->cand_symbol_name }}</li>@endif

									<?php 
									if(!empty($candidateDetail->cand_party)){
										if($candidateDetail->cand_party=="Independent")
										{
										?>
										@if($candidateDetail->symbol1)<li><b>Symbol 1:</b> {{ $candidateDetail->symbol1 }}</li>@endif

										@if($candidateDetail->symbol2)<li><b>Symbol 2:</b> {{ $candidateDetail->symbol2 }}</li>@endif

										@if($candidateDetail->symbol3)<li><b>Symbol 3:</b> {{ $candidateDetail->symbol3 }}</li>@endif

										<?php 
										}
									}
									?>
									@if($candidateDetail->nominationDate)<li><b>Nomination Date:</b> {{ $candidateDetail->nominationDate }}</li>@endif

									@if($nominationStatus)<li><b>Nomination Status:</b> {{ $nominationStatus }}</li>@endif

									@if($candidateDetail->withdrawal_date)<li><b>Withdrawal Date:</b> {{ $candidateDetail->withdrawal_date }}</li>@endif

									@if($candidateDetail->rejection_date)<li><b>Rejection Date:</b> {{ $candidateDetail->rejection_date }}</li>@endif

									@if($candidateDetail->rejectionReason)<li><b>Rejection Reason:</b> {{ $candidateDetail->rejectionReason }}</li>@endif

									@if($candidateDetail->withdraw_refno)<li><b>Withdrawal Reference No:</b> {{ $candidateDetail->withdraw_refno }}</li>@endif

									@if($candidateDetail->cand_main_sub)<li><b>Candidate Main Sub:</b> {{ $candidateDetail->cand_main_sub }}</li>@endif

									@if($candidateDetail->fromab_recv)<li><b>From AB Received:</b> {{ $candidateDetail->fromab_recv }}</li>@endif

									@if($candidateDetail->form2b_id)<li><b>Form 2b Id:</b> {{ $candidateDetail->form2b_id }}</li>@endif

									@if($candidateDetail->form26_id)<li><b>Form 26 Id:</b> {{ $candidateDetail->form26_id }}</li>@endif
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