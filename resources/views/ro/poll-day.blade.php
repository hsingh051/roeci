@extends('layouts.main')
@section('content')
	<link rel="stylesheet" href="{{ URL::asset('dateTimePicker/css/bootstrap-datetimepicker.min.css')}}" />
	<link rel="stylesheet" href="{{ URL::asset('dateTimePicker/css/jquery-ui.css')}}" />
    <!-- START CONTAINER -->
	<div class="container-widget">
		<!-- <div class="row">
			<div class="col-md-12">
				<div class="searchTime">
					<div class="form-group">
						<label>Select Time From: </label>
						<input type="text" id="time" placeholder="Click To Select Time" class="form-control" />
					</div>
					<div class="form-group">
						<label>To: </label>
						<input type="text" id="time1" placeholder="Click To Select Time" class="form-control" />
					</div>
					<input type="submit" value="Search" class="btn btn-default">
				</div>
			</div>
		</div>   -->
		
		<div class="row">
			<!-- Nominations -->
			<div class="col-md-12">
				<div class="panel panel-widget">
					<div class="panel-title pageTitle">POLL DAY REPORT</div>
					<div class="panel-body">
						<div class="table-scroll">
						<table class="table table-bordered tablefilter">
							<thead>
								<tr>
									<th>Polling Stations</th>
									<th>Polling Booth Set Up</th>
									<th>Mock Poll</th>
									<th>Agent Present</th>
									<th>EVM Reset</th>
									<th>Poll Start</th>									
									<th>RO Handbook Annexure 41</th>
									<th>Queue Status at 5:00</th>
									<th>Tenders voters</th>
									<th>Poll Day End Time</th>
									<th>EVM/VVPAT End Button</th>
									<th>Turn off EVM/VVPAT</th>
									<th>Lock & Seal EVM/VVPAT</th>
									<th>RO Handbook Annexure 41</th>
									<th>Poll EVM/VVPAT Returned</th>
									<th>Election Material Returned</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Polling Stations</th>
									<th>Polling Booth Set Up</th>
									<th>Mock Poll</th>
									<th>Agent Present</th>
									<th>EVM Reset</th>
									<th>Poll Start</th>									
									<th>RO Handbook Annexure 41</th>
									<th>Queue Status at 5:00</th>
									<th>Tenders voters</th>
									<th>Poll Day End Time</th>
									<th>EVM/VVPAT End Button</th>
									<th>Turn off EVM/VVPAT</th>
									<th>Lock & Seal EVM/VVPAT</th>
									<th>RO Handbook Annexure 41</th>
									<th>Poll EVM/VVPAT Returned</th>
									<th>Election Material Returned</th>
								</tr>
							</tfoot>
							
							<tbody>									
								
								
								@foreach($pollDayDetail as $pollDayDetails)
								<?php
									
									$setup_pollbooth = json_decode($pollDayDetails->setup_pollbooth);
									$mock_poll = json_decode($pollDayDetails->mock_poll);
									$agent_present = json_decode($pollDayDetails->agent_present);
									$evm_reset = json_decode($pollDayDetails->evm_reset);
									$poll_start = json_decode($pollDayDetails->poll_start);
									$handbook_annexure_13 = json_decode($pollDayDetails->handbook_annexure_13);
									$queue_status_17 = json_decode($pollDayDetails->queue_status_17);
									$tenders_voters = json_decode($pollDayDetails->tenders_voters);
									$poll_end_time = json_decode($pollDayDetails->poll_end_time);
									$poll_end_button = json_decode($pollDayDetails->poll_end_button);
									$turn_off_evm = json_decode($pollDayDetails->turn_off_evm);
									$lock_seal_evm = json_decode($pollDayDetails->lock_seal_evm);
									$handbook_annexure_19 = json_decode($pollDayDetails->handbook_annexure_19);
									$polled_evm = json_decode($pollDayDetails->polled_evm);
									$election_material = json_decode($pollDayDetails->election_material);
									/*echo "<pre>";
									print_r($agent_present);
									echo "</pre>";
									die();*/
								?>
								<tr>
									<td><a href="{{ url('/ro/polling-detail') }}/{{eci_encrypt($pollDayDetails->poll_booth_id)}}">{{ $pollDayDetails->poll_building }}</a></td>
									<td>
										<?php
											if(!empty($setup_pollbooth)){
												if($setup_pollbooth->comment_status == 'yes'){
													echo "<span>Done</br>Time: ".$setup_pollbooth->activity_time."</br>Reason: ".$setup_pollbooth->comment.'</span>';
												}
												else{
													echo "<span style='color: #458B00;'>Done</br>Time: ".$setup_pollbooth->activity_time.'</span>';
												}
											}
											else{
												echo "<span style='color: #FF0000;'>pending</span>";
											}
										?>
										
									</td>
									<td>
										<?php
											if(!empty($mock_poll)){
												if($mock_poll->comment_status == 'yes'){
													echo "<span>Done</span></br>Time: ".$mock_poll->activity_time."</br>Reason: ".$mock_poll->comment;
												}
												else{
													echo "<span style='color: #76EE00;'>Done</br>Time: ".$mock_poll->activity_time.'</span>';
												}
											}
											else{
												echo "<span style='color: #FF0000;'>pending<span>";
											}
										?>
									</td>
									<td>
										<?php
											if(!empty($agent_present)){
												echo "<span style='color: #458B00;'>Done</br>Time: ".$agent_present->activity_time."</br>Members: ".$agent_present->comment.'</span>';
												
											}
											else{
												echo "<span style='color: #FF0000;'>pending</span>";
											}
										?>
									</td>
									<td>
										<?php
											if(!empty($evm_reset)){
												if($evm_reset->comment_status == 'yes'){
													echo "<span>Done</br>Time: ".$evm_reset->activity_time."</br>Reason: ".$evm_reset->comment.'</span>';
												}
												else{
													echo "<span style='color: #458B00;'>Done</br>Time: ".$evm_reset->activity_time.'</span>';
												}
											}
											else{
												echo "<span style='color: #FF0000;'>pending</span>";
											}
										?>
									</td>
									<td>
										<?php
											if(!empty($poll_start)){
												if($poll_start->comment_status == 'yes'){
													echo "<span>Done</br>Time: ".$poll_start->activity_time."</br>Reason: ".$poll_start->comment.'</span>';
												}
												else{
													echo "<span style='color: #458B00;'>Done</br>Time: ".$poll_start->activity_time.'</span>';
												}
											}
											else{
												echo "<span style='color: #FF0000;'>pending</span>";
											}
										?>
									</td>
									<td>
										<?php
											if(!empty($handbook_annexure_13)){
												if($handbook_annexure_13->comment_status == 'yes'){
													echo "<span>Done</br>Time: ".$handbook_annexure_13->activity_time."</br>Reason: ".$handbook_annexure_13->comment.'</span>';
												}
												else{
													echo "<span style='color: #458B00;'>Done</br>Time: ".$handbook_annexure_13->activity_time.'</span>';
												}
											}
											else{
												echo "<span style='color: #FF0000;'>pending</span>";
											}
										?>
									</td>
									<td>
										<?php
											if(!empty($queue_status_17)){
												if($queue_status_17->comment_status == 'yes'){
													echo "<span>Done</br>Time: ".$queue_status_17->activity_time."</br>Voters in queue: ".$queue_status_17->comment.'</span>';
												}
												else{
													echo "<span style='color: #458B00;'>Done</br>Time: ".$queue_status_17->activity_time.'</span>';
												}
											}
											else{
												echo "<span style='color: #FF0000;'>pending</span>";
											}
										?>
									</td>
									<td>
										<?php
											if(!empty($tenders_voters)){
												echo "<span style='color: #458B00;'>Done</br>Time: ".$tenders_voters->activity_time."</br>Members: ".$tenders_voters->comment.'</span>';
												
											}
											else{
												echo "<span style='color: #FF0000;'>pending</span>";
											}
										?>
									</td>
									<td>
										<?php
											if(!empty($poll_end_time)){
												echo "<span>Done</br>Time: ".$poll_end_time->activity_time.'</span>';
											}
											else{
												echo "<span style='color: #FF0000;'>pending</span>";
											}
										?>
									</td>
									<td>
										<?php
											if(!empty($poll_end_button)){
												if($poll_end_button->comment_status == 'yes'){
													echo "<span>Done</br>Time: ".$poll_end_button->activity_time."</br>Reason: ".$poll_end_button->comment.'</span>';
												}
												else{
													echo "<span style='color: #458B00;'>Done</br>Time: ".$poll_end_button->activity_time.'</span>';
												}
											}
											else{
												echo "<span style='color: #FF0000;'>pending</span>";
											}
										?>
									</td>
									<td>
										<?php
											if(!empty($turn_off_evm)){
												if($turn_off_evm->comment_status == 'yes'){
													echo "<span>Done</br>Time: ".$turn_off_evm->activity_time."</br>Reason: ".$turn_off_evm->comment.'</span>';
												}
												else{
													echo "<span style='color: #458B00;'>Done</br>Time: ".$turn_off_evm->activity_time.'</span>';
												}
											}
											else{
												echo "<span style='color: #FF0000;'>pending</span>";
											}
										?>
									</td>
									<td>
										<?php
											if(!empty($lock_seal_evm)){
												if($lock_seal_evm->comment_status == 'yes'){
													echo "<span>Done</br>Time: ".$lock_seal_evm->activity_time."</br>Reason: ".$lock_seal_evm->comment.'</span>';
												}
												else{
													echo "<span style='color: #458B00;'>Done</br>Time: ".$lock_seal_evm->activity_time.'</span>';
												}
											}
											else{
												echo "<span style='color: #FF0000;'>pending</span>";
											}
										?>
									</td>
									<td>
										<?php
											if(!empty($handbook_annexure_19)){
												if($handbook_annexure_19->comment_status == 'yes'){
													echo "<span>Done</br>Time: ".$handbook_annexure_19->activity_time."</br>Reason: ".$handbook_annexure_19->comment.'</span>';
												}
												else{
													echo "<span style='color: #458B00;'>Done</br>Time: ".$handbook_annexure_19->activity_time.'</span>';
												}
											}
											else{
												echo "<span style='color: #FF0000;'>pending</span>";
											}
										?>
									</td>
									<td>
										<?php
											if(!empty($polled_evm)){
												if($polled_evm->comment_status == 'yes'){
													echo "<span>Done</br>Time: ".$polled_evm->activity_time."</br>Reason: ".$polled_evm->comment.'</span>';
												}
												else{
													echo "<span style='color: #458B00;'>Done</br>Time: ".$polled_evm->activity_time.'</span>';
												}
											}
											else{
												echo "<span style='color: #FF0000;'>pending</span>";
											}
										?>
									</td>
									<td>
										<?php
											if(!empty($election_material)){
												if($election_material->comment_status == 'yes'){
													echo "<span>Done</br>Time: ".$election_material->activity_time."</br>Reason: ".$election_material->comment.'</span>';
												}
												else{
													echo "<span style='color: #458B00;'>Done</br>Time: ".$election_material->activity_time.'</span>';
												}
											}
											else{
												echo "<span style='color: #FF0000;'>pending</span>";
											}
										?>
									</td>
								</tr>
								@endforeach
								
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
	<script type="text/javascript" src="{{ URL::asset('dateTimePicker/js/jquery-ui.js')}}"></script>
    <script type="text/javascript" src="{{ URL::asset('dateTimePicker/js/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{ URL::asset('dateTimePicker/js/bootstrap-datetimepicker.min.js')}}"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('#time').datetimepicker({
				format: 'HH:mm:ss'
		    });
		    $('#time1').datetimepicker({
				format: 'HH:mm:ss'
		    });

		    $('#time').keypress(function(e) {
			    return false
			});
			$('#time1').keypress(function(e) {
			    return false
			});
		});
	</script>
@endsection