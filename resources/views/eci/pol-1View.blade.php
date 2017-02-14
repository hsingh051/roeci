@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					<div class="panel-title pageTitle">Poll - 1 Day</div>
					<div class="panel-body">
						<table class="table table-bordered tablefilter">
							<thead>
								<tr>
									<th>Polling Stations</th>
									<th>Election Material Received</th>
									<th>Polling Party Reached</th>
									<th>EVM &amp; VVPAT Received</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Polling Stations</th>
									<th>Election Material Received</th>
									<th>Polling Party Reached</th>
									<th>EVM &amp; VVPAT Received</th>
								</tr>
							</tfoot>
							<tbody>
								<?php 
									if(@$polMinus1View){
										foreach ($polMinus1View as $value) {
										?>
											<tr>
												<?php
												echo "<td>".$value->poll_building."</td>";

												$eMat= (isset($value->election_material))? $value->election_material : ""; 
												if($eMat==1){
													echo "<td>Received</td>";
												}else{
													echo "<td class='red-text'>Not-Received</td>";
												}
												
												$partyReached= (isset($value->party_reached))? $value->party_reached : ""; 
												if($partyReached==1){
													echo "<td>Reached</td>";
												}else{
													echo "<td class='red-text'>Not-Reached</td>";
												}

												$evmStatus= (isset($value->evm_received))? $value->evm_received : ""; 
												if($evmStatus==1){
													echo "<td>Received</td>";
												}else{
													echo "<td class='red-text'>Not-Received</td>";
												}
												?>
											</tr>
										<?php
										}
								 	}?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- END CONTAINER -->
@endsection