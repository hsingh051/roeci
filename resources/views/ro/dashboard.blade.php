@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
	<div class="container-widget dashboard">
		<div class="row">
			<!-- Daily Activity -->
			<div class="col-xs-6 col-md-4">
				<div class="panel panel-widget dashBox1">
					<div class="panel-title">Sector Officer List</div>
					<div class="panel-body table-responsive">
						<table class="table">
							<thead>
								<tr>
									<td>Serial No</td>	
									<td>Name</td>
									<td>Phone</td>
								</tr>
							</thead>
							<tbody>
								<?php 
									$i=1; 
									foreach($supervisorlist as $list){?>
										<tr>
											<td>{{$i}}</td>
											<td>{{$list->name}}</td>
											<td>{{$list->phone}}</td>
										</tr>
								<?php $i++; }?>
							</tbody>
						</table>
						<div class="widget-more text-right"><a href="{{url('/').'/ro/supervisor-list'}}">View All</a></div>
					</div>
				</div>
			</div>
			<!-- Daily Activity -->
			
			<!-- Nominations -->
			<div class="col-xs-6 col-md-4">
				<div class="panel panel-widget dashBox2 nomiDashboard">
					<div class="panel-title">Nominations</div>
					<div class="panel-body">
						<ul class="basic-list">
							<li>Start Date of Nominations<span class="right label label-primary">11th Jan 2017</span></li>
							<li>Last Date of Making Nominations<span class="right label label-primary">18th Jan 2017</span></li>
							<li>Scrutiny of Nominations <span class="right label label-primary">19th Jan 2017</span></li>
							<li>Withdrawal of Candidature <span class="right label label-primary">21th Jan 2017</span></li>
							<li>Final List of Contesting Candidates<span class="right label label-primary">22nd Jan 2017</span></li>
						</ul>
					</div>
				</div>
			</div>
			<!-- End Nominations -->

			<!-- Election Material -->
			<div class="col-xs-6 col-md-4">
				<div class="panel panel-widget dashBox3">
					<div class="panel-title">EVM & VVPAT</div>
					<div class="panel-body table-responsive">
						<table class="table">
							<thead>
								<tr>										
									<td>Randomization</td>
									<td>Status</td>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>First</td>
									<td>{{$evmlist['first']}}</td>
								</tr>
								<tr>
									<td>Second</td>
									<td>{{$evmlist['second']}}</td>
								</tr>								
							</tbody>
						</table>
						<!-- <div class="widget-more text-right"><a href="{{url('/').'/ro/supervisor-list'}}">View All</a></div> -->
					</div> 
				</div>
			</div>
			<!-- End Election Material -->		
				
		</div>
		<div class="row">
			<!-- Daily Activity -->
			<div class="col-xs-6 col-md-4">
				<div class="panel panel-widget dashBox4">
					<div class="panel-title">Polling Stations</div>
					<div class="panel-body table-responsive">
						<table class="table">
							<thead>
								<tr>
									<td>Serial No</td>
									<td>Type</td>	
									<td>Total</td>
								</tr>
							</thead>
							<tbody>
								<?php 
									$i=1; 
									foreach($poll_types as $t=>$c){?>
										<tr>
											<td>{{$i}}</td>
											<td>{{$t}}</td>
											<td>{{$c}}</td>
										</tr>
								<?php $i++; }?>
							</tbody>
						</table>
						<!-- <div class="widget-more text-right"><a href="{{url('/').'/ro/supervisor-list'}}">View All</a></div> -->
					</div>
				</div>
			</div>
			<div class="col-xs-6 col-sm-4">
				<div class="panel panel-widget dashBox5">
					<div class="panel-title">Electoral Rolls</div>
					<div class="panel-body table-responsive">
						<table class="table">
							<thead>
								<tr>
									<td>Serial No</td>
									<td>Polling Station</td>
									<td class="text-center">Total number of Voters</td>										
								</tr>
							</thead>
							<tbody>
								<?php 
									$i=1; 
									foreach($voterlist as $list){?>
										<tr>
											<td>{{$i}}</td>
											<td>{{$list->poll_building}}</td>
											<td class="text-center">{{$list->total}}</td>
										</tr>
								<?php $i++; }?>
							</tbody>
						</table>
						<div class="widget-more"><a href="{{url('/').'/ro/electoral-rolls'}}">View All</a></div>
					</div>
				</div>
			</div>
			<div class="col-xs-6 col-md-4">
				<div class="panel panel-widget dashBox6">
					<div class="panel-title">Polling Staff</div>
					<div class="panel-body table-responsive">
						<table class="table">
							<thead>
								<tr>										
									<td>Randomization</td>
									<td>Status</td>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>First</td>
									<td>{{$stafflist['first']}}</td>
								</tr>
								<tr>
									<td>Second</td>
									<td>{{$stafflist['second']}}</td>
								</tr>
								<tr>
									<td>Third</td>
									<td>{{$stafflist['third']}}</td>
								</tr>								
							</tbody>
						</table>
						<!-- <div class="widget-more text-right"><a href="{{url('/').'/ro/supervisor-list'}}">View All</a></div> -->
					</div> 
				</div>
			</div>
		</div>
	</div>
	<!-- END CONTAINER -->
@endsection