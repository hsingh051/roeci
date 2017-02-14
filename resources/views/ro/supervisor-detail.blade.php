@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<!-- Nominations -->
			<div class="col-md-12">
				<div class="panel panel-widget">
					@if(Session::has('addPollSucc'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('addPollSucc') }}</p>
					@endif
					<div class="panel-title pageTitle titleBtn clearfix">
						<span>Assigned Polling Stations For - {{ $svDetail->name }}</span>
						<div class="panel-btn">
							<?php
								$uid = eci_encrypt($svDetail->uid);
							?>
							<!-- <a href="{{url('ro/addPollingStation') }}/<?php //echo $uid; ?>" class="btn btn-default">Add Polling Station</a>
							<a href="{{url('ro/polingCsvForm') }}/<?php //echo $uid; ?>" class="btn btn-default">Import CSV</a> -->
							<a href="{{url('ro/uploadRoutePlan') }}/<?php echo $uid; ?>" class="btn btn-default">Upload Route Plan</a>
							<!-- <a href="{{ url('/ro/supervisor-list') }}" class="btn btn-default">Back</a> -->
						</div>
					</div>
					<div class="panel-body">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>Booth ID</th>
									<th>Locality</th>
									<th>Poll Building</th>
									<th>Address</th>
									<th>Polling Station Type</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
							<?php if(count($polling_stations) > 0){ ?>
								
								<?php $abc=0; ?>
								@foreach ($polling_stations as $polling_station)
								<?php 
									$booth_no = str_pad($polling_station->booth_no, 3, 0, STR_PAD_LEFT);
									$pollBoothId=eci_encrypt($polling_station->poll_booth_id);
								?>
								<?php 
									  if($polling_station->poll_type=="Notified"){
									  		$type_text = 'type_notified';
									  }elseif($polling_station->poll_type=="Auxiliary"){
									  		$type_text = 'type_auxiliary';
									  }elseif($polling_station->poll_type=="Vulnerable"){
									  		$type_text = 'type_vulnerable';
									  }elseif($polling_station->poll_type=="Critical"){
									  		 $type_text = 'type_critical';
									  }elseif($polling_station->poll_type=="Model"){
									  		 $type_text = 'type_model';
									  }else{
									  		 $type_text = "";
									  }

								?>
								<tr>
									<td>{{ $booth_no }}</td>
									<td>{{ $polling_station->locality }}</td>
									<td>{{ $polling_station->poll_building }}</td>
									<td>{{ $polling_station->poll_areas }}</td>

									
									<td id="pollTypeTd<?php echo $abc; ?>" class="<?php echo $type_text;?> pollTypeTd<?php echo $abc; ?>" data-oldColorClass="<?php echo $type_text;?>">
										{{ $polling_station->poll_type }}
									</td>
						
									<td class="pollTypeDropdown<?php echo $abc; ?>" style="display:none;">
										<select  class="form-group pollTypeSelect pollTypeSelect<?php echo $abc; ?>" data-pollId="{{ $pollBoothId }}" data-selectCount="<?php echo $abc; ?>">
											<option value="">Select Type</option>
											<option value="Notified">Notified</option>
											<option value="Auxiliary">Auxiliary</option>
											<option value="Vulnerable">Vulnerable</option>
											<option value="Critical">Critical</option>
											<option value="Model">Model</option>
										</select>
									</td>
							
									<td>
										<i class="fa fa-pencil-square-o editPollStation" data-count="<?php echo $abc; ?>" aria-hidden="true"></i>
									</td>
								</tr>
								<?php $abc++; ?>
								@endforeach
							<?php } else { ?>
							<tr>
								<td colspan="6">No Record Found</td>
							</tr>
							<?php } ?>
							</tbody>

						</table>
					</div>
				</div>
			</div>
			<!-- End Nominations -->
		
		</div>  
	</div>
	<!-- END CONTAINER -->
@endsection

