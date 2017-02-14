@extends('layouts.main')
@section('content')
	<script>
		$(document).ready(function() {
			$('#example0').DataTable();
		});
	</script>
    <!-- START CONTAINER -->
	<div class="container-widget">		
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget">
					<?php
						$proPic=$observerDetail->profile_image;
						$obsUid=$observerDetail->uid;
						$uidEnc=eci_encrypt($obsUid);
					?>
					<div class="panel-title pageTitle titleBtn clearfix">
						<div class="panel-btn">
							<!-- <a href="{{ url('/deo/edit-observer/'.$uidEnc) }}" class="btn btn-default">Edit Observer</a> -->
							<!-- <a href="{{URL::previous()}}" class="btn btn-default">Back</a> -->
						</div>
					</div>
					<div class="panel-body oberserverPro">
						<div class="candidateDetails clearfix">
							<!-- <div class="Pic"><img src="{{ URL::asset('images/observer/'.$proPic)}}" /></div> -->
							<div class="Info">
								<ul>
									<li><b>Name:</b> {{ $observerDetail->name }}</li>
									<li><b>Contact No:</b> {{ $observerDetail->phone }}</li>
									<li><b>Email:</b> {{ $observerDetail->email }}</li>
									<li><b>Address:</b> {{ $observerDetail->address }}</li>
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

