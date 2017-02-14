@extends('layouts.main')
@section('content')
<!-- START CONTAINER -->
	<div class="container-widget">	
		<div class="row">
			<!-- Nominations -->
			<div class="col-md-12">
				<div class="panel panel-widget">
					<div class="panel-title pageTitle titleBtn">
						<span>Poll-1 Day</span>
						<a href="{{URL::previous()}}" class="formBackBtn btn btn-default">Back</a>
					</div>
					<div class="panel-body">
						<table id="tableview" class="table table-bordered">
							<thead>
								<tr>
									<th>Polling Station</th>
									<th>Election Material</th>
									<th>Party Reached</th>
									<th>EVM Received</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Polling Station</th>
									<th>Election Material</th>
									<th>Party Reached</th>
									<th>EVM Received</th>
								</tr>
							</tfoot>
							<tbody>
								</tr>
								@foreach ($polMinus1day as $value)
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
								@endforeach	
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
<!-- END CONTAINER -->
@endsection