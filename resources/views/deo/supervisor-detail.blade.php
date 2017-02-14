@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<!-- Nominations -->
			<div class="col-md-12">
				<div class="panel panel-widget">
					<div class="panel-title pageTitle titleBtn clearfix">
						<span>Assigned Polling Stations For - {{ $svDetail->name }}</span>
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
								</tr>
							</thead>
							<tbody>
							<?php if(count($polling_stations) > 0){ ?>
								
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

									
									<td class="<?php echo $type_text;?>" data-oldColorClass="<?php echo $type_text;?>">
										{{ $polling_station->poll_type }}
									</td>
								</tr>
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

