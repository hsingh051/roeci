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
			<!-- Nominations -->
			<div class="col-md-12">
				<div class="panel panel-widget">
					<div class="panel-title pageTitle">POLL PERCENTAGE REPORT of {{ $pollpercentageDetail->poll_building }}</div>
					<div class="panel-body">
						<div class="table-scroll">
						<table class="table table-bordered tablefilter">
							<thead>
								<tr>
									<th>Time Slot</th>
									<th>Queue</th>
									<th>Male</th>
									<th>Female</th>
									<th>Transgenders</th>
									<th>Total%</th>
									<th>Activity Time</th>									
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Time Slot</th>
									<th>Queue</th>
									<th>Male</th>
									<th>Female</th>
									<th>Transgenders</th>
									<th>Total%</th>
									<th>Activity Time</th>									
								</tr>
							</tfoot>
							<tbody>									
								<tr>
									<td>8 AM</td>
									<?php
									 $jsonearraye8 = json_decode($pollpercentageDetail->percentage_8); 
									if(!empty($jsonearraye8)){
									?>
									<td><?php echo $jsonearraye8->queue; ?></td>
									<td><?php echo $jsonearraye8->male; ?></td>
									<td><?php echo $jsonearraye8->female; ?></td>
									<td><?php echo $jsonearraye8->tg; ?></td>
									<td><?php echo $jsonearraye8->percentage; ?></td>
									<td><?php echo $jsonearraye8->activity_time; ?></td>
									<?php } else {?>
									<td>Pending</td>
									<td>Pending</td>
									<td>Pending</td>
									<td>Pending</td>
									<td>Pending</td>
									<td>Pending</td>
									<?php } ?>
								</tr>
								<tr>
									<td>10 AM</td>
									<?php
									 $jsonearraye10 = json_decode($pollpercentageDetail->percentage_10); 
									if(!empty($jsonearraye10)){
									?>
									<td><?php echo $jsonearraye10->queue; ?></td>
									<td><?php echo $jsonearraye10->male;  ?></td>
									<td><?php echo $jsonearraye10->female;  ?></td>
									<td><?php echo $jsonearraye8->tg; ?></td>
									<td><?php echo $jsonearraye10->percentage;  ?></td>
									<td><?php echo $jsonearraye10->activity_time;  ?></td>
									<?php } else {?>
									<td>Pending</td>
									<td>Pending</td>
									<td>Pending</td>
									<td>Pending</td>
									<td>Pending</td>
									<td>Pending</td>
									<?php } ?>
								</tr>
								<tr>
									<td>12 PM</td>
									<?php
									 $jsonearraye12 = json_decode($pollpercentageDetail->percentage_12); 
									if(!empty($jsonearraye12)){
									?>
									<td><?php echo $jsonearraye12->queue; ?></td>
									<td><?php echo $jsonearraye12->male;  ?></td>
									<td><?php echo $jsonearraye12->female;  ?></td>
									<td><?php echo $jsonearraye8->tg; ?></td>
									<td><?php echo $jsonearraye12->percentage;  ?></td>
									<td><?php echo $jsonearraye12->activity_time;  ?></td>
									<?php } else {?>
									<td>Pending</td>
									<td>Pending</td>
									<td>Pending</td>
									<td>Pending</td>
									<td>Pending</td>
									<td>Pending</td>
									<?php } ?>
								</tr>
								<tr>
									<td>2 PM</td>
									<?php
									 $jsonearraye14 = json_decode($pollpercentageDetail->percentage_14); 
									if(!empty($jsonearraye14)){
									?>
									<td><?php echo $jsonearraye14->queue; ?></td>
									<td><?php echo $jsonearraye14->male;  ?></td>
									<td><?php echo $jsonearraye14->female;  ?></td>
									<td><?php echo $jsonearraye8->tg; ?></td>
									<td><?php echo $jsonearraye14->percentage;  ?></td>
									<td><?php echo $jsonearraye14->activity_time;  ?></td>
									<?php } else {?>
									<td>Pending</td>
									<td>Pending</td>
									<td>Pending</td>
									<td>Pending</td>
									<td>Pending</td>
									<td>Pending</td>
									<?php } ?>
								</tr>
								<tr>
									<td>4 PM</td>
									<?php
									 $jsonearraye16 = json_decode($pollpercentageDetail->percentage_16); 
									if(!empty($jsonearraye16)){
									?>
									<td><?php echo $jsonearraye16->queue; ?></td>
									<td><?php echo $jsonearraye16->male;  ?></td>
									<td><?php echo $jsonearraye16->female;  ?></td>
									<td><?php echo $jsonearraye8->tg; ?></td>
									<td><?php echo $jsonearraye16->percentage;  ?></td>
									<td><?php echo $jsonearraye16->activity_time;  ?></td>
									<?php } else {?>
									<td>Pending</td>
									<td>Pending</td>
									<td>Pending</td>
									<td>Pending</td>
									<td>Pending</td>
									<td>Pending</td>
									<?php } ?>
								</tr>
								<tr>
									<td>6 PM</td>
									<?php
									 $jsonearraye18 = json_decode($pollpercentageDetail->percentage_18); 
									if(!empty($jsonearraye18)){
									?>
									<td><?php echo $jsonearraye18->queue; ?></td>
									<td><?php echo $jsonearraye18->male;  ?></td>
									<td><?php echo $jsonearraye18->female;  ?></td>
									<td><?php echo $jsonearraye8->tg; ?></td>
									<td><?php echo $jsonearraye18->percentage;  ?></td>
									<td><?php echo $jsonearraye18->activity_time;  ?></td>
									<?php } else {?>
									<td>Pending</td>
									<td>Pending</td>
									<td>Pending</td>
									<td>Pending</td>
									<td>Pending</td>
									<td>Pending</td>
									<?php } ?>
								</tr>
							</tbody>
						</table>
						</div>
					</div>
				</div>
			</div>
			<!-- End Nominations -->
		</div>
	</div>
	<!-- END CONTAINER -->
@endsection

