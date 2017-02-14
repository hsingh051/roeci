@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
	<div class="container-widget dashboard">
		<div class="row">
			<!-- Daily Activity -->
			<div class="col-xs-6 col-sm-4">
				<div class="panel panel-widget dashBox1">
					<div class="panel-title">Ro List</div>
					<div class="panel-body table-responsive">
						<table class="table">
							<thead>
								<tr>
									<td>S.No</td>	
									<td>Name</td>
									<td>Constituency</td>
									<td>Phone</td>
								</tr>
							</thead>
							<tbody>
								<?php 
									$i=1; 
									foreach($rolist as $list){?>
										<tr>
											<td>{{$i}}</td>
											<td>{{$list->name}}</td>
											<td>{{$list->cons_name}}</td>
											<td>{{$list->phone}}</td>
										</tr>
								<?php $i++; }?>
							</tbody>
						</table>
						<div class="widget-more text-right"><a href="{{url('/').'/deo/ro-list'}}">View All</a></div>
					</div>
				</div>
			</div>
			<!-- Daily Activity -->
			
			<!-- Nominations -->
			<div class="col-xs-6 col-sm-4">
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
			<div class="col-xs-6 col-sm-4">
				<div class="panel panel-widget dashBox3">
					<div class="panel-title">EVM & VVPAT Randomization</div>
					<div class="panel-body table-responsive">
						<table class="table">
							<thead>
								<tr>
									<td>S.No</td>	
									<td>Constituency</td>									
									<td>Status</td>
								</tr>
							</thead>
							<tbody>
								<?php 
									$i=1; 
									foreach($evmlist as $list){?>
										<tr>
											<td>{{$i}}</td>
											<td>{{$list->cons_name}}</td>											
											<td>Done</td>
										</tr>
								<?php $i++; }?>
							</tbody>
						</table>
						<!-- <div class="widget-more text-right"><a href="{{url('/').'/deo/evm-vvpat'}}">View All</a></div> -->
					</div>
				</div>
			</div>
			<!-- End Election Material -->	
		</div>
		
		<div class="row">			
			<!-- Polling Personnel -->
			<div class="col-xs-6 col-sm-4">
				<div class="panel panel-widget dashBox4">
					<div class="panel-title">Polling Staff Randomization</div>
					<div class="panel-body table-responsive">
						<table class="table">
							<thead>
								<tr>
									<td>S.No</td>	
									<td>Constituency</td>
									
									<td>Status</td>										
								</tr>
							</thead>
							<tbody>
								<?php 
									$i=1; 
									foreach($stafflist as $list){?>
										<tr>
											<td>{{$i}}</td>
											<td>{{$list->cons_name}}</td>											
											<td>Done</td>
										</tr>
								<?php $i++; }?>
							</tbody>
						</table>
						<!-- <div class="widget-more"><a href="{{url('/').'/deo/pending-stafflist'}}">View All</a></div> -->
					</div>
				</div>
			</div>
			<!-- End Polling Personnel -->
			
			<!-- Training -->
			<div class="col-xs-6 col-sm-4">
				<div class="panel panel-widget dashBox5">
					<div class="panel-title">Polling Stations</div>
					<div class="panel-body table-responsive">
						<table class="table">
							<thead>
								<tr>
									<td>S.No</td>
									<td>Constituency</td>
									<td class="text-center">Total Polling Station</td>										
								</tr>
							</thead>
							<tbody>
								<?php 
									$i=1; 
									foreach($pollstationlist as $list){?>
										<tr>
											<td>{{$i}}</td>
											<td>{{$list->cons_name}}</td>
											<td class="text-center">{{$list->total}}</td>
										</tr>
								<?php $i++; }?>
							</tbody>
						</table>
						<div class="widget-more"><a href="{{url('/').'/deo/pollstationlist'}}">View All</a></div>
					</div>
				</div>
			</div>
			<!-- End Training -->
			
			<!-- Daily Activity -->
			<div class="col-xs-6 col-sm-4">
				<div class="panel panel-widget dashBox6">
					<div class="panel-title">Electoral Rolls</div>
					<div class="panel-body table-responsive">
						<table class="table">
							<thead>
								<tr>
									<td>S.No</td>
									<td>Constituency</td>
									<td class="text-center">Total no. of Voters</td>										
								</tr>
							</thead>
							<tbody>
								<?php 
									$i=1; 
									foreach($voterlist as $list){?>
										<tr>
											<td>{{$i}}</td>
											<td>{{$list->cons_name}}</td>
											<td class="text-center">{{$list->total}}</td>
										</tr>
								<?php $i++; }?>
							</tbody>
						</table>
						<div class="widget-more"><a href="{{url('/').'/deo/cons-electrollist'}}">View All</a></div>
					</div>
				</div>
			</div>
			<!-- Daily Activity -->
		</div>
		
	</div>
	<!-- END CONTAINER -->
@endsection

