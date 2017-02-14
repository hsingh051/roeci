@extends('layouts.main')
@section('content')
<?php //dd($polling_users);?>
    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget">
					<div class="panel-title pageTitle titleBtn clearfix">
						<span>POLLING PARTY DETAILS</span>
						<div class="panel-btn"><a href="{{URL::previous()}}" class="btn btn-default">Back</a></div>
					</div>
					<div class="panel-body">
						<?php
							if($visibile == 0){
						?>
						<h4>Randomization is pending...</h4>
						<?php
						}
						else{
						?>
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>Polling Stations</th>
									<th>PRO</th>
									<th>APRO</th>
									<th>BLO</th>
									<?php
										if(@$poo_array){
											$i=1;
											foreach ($poo_array as $d) {
												echo "<th>PO".$i."</th>";
												$i++;
											}
										}
									?>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>{{ $poll_booths->poll_building}}</td>
									<td>
										<?php 
											if(@$polling_users['pro_name']){
												echo $polling_users['pro_name'];
											};
										?>
										<br>
										<?php 
											if(@$polling_users['pro_phone']){
												echo $polling_users['pro_phone'];
											};
										?>
										
									</td>
									<td>
									<?php 
										if(@$polling_users['apro_name']){
											echo $polling_users['apro_name'];
										};
									?>
									<br>
									<?php 
										if(@$polling_users['apro_phone']){
											echo $polling_users['apro_phone'];
										};
									?>
									</td>
									<td>
									<?php 
										if(@$polling_users['blo_name']){
											echo $polling_users['blo_name'];
										};
									?>
									<br>
									<?php 
										if(@$polling_users['blo_phone']){
											echo $polling_users['blo_phone'];
										};
									?>
									</td>
									<?php
										if(@$poo_array){
											$i=1;
											foreach ($poo_array as $d) {
											?>
												<td>{{$d['name']}}<br>{{$d['phone']}}</td>
											<?php
												$i++;
											}
										}
									?>
									
								</tr>								
							</tbody>
						<?php
						}
						?>
						</table>
					</div>
				</div>
			</div>		
		</div>
	</div>
	<!-- END CONTAINER -->
@endsection

