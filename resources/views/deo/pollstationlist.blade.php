@extends('layouts.main')
@section('content')
<!-- START CONTAINER -->
	<div class="container-widget">	
		<div class="row">
			<!-- Nominations -->
			<div class="col-md-12">
				<div class="panel panel-widget">
					<div class="panel-title pageTitle titleBtn clearfix">
						<span>POLLING STATIONS</span>
						
					</div>
					<div class="panel-body">
						<table id="tableview" class="table table-bordered">
							<thead>
								<tr>
									<th >S.No.</th>
									<th>Constituency</th>
									<th>Total Polling Station</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>S.No.</th>
									<th>Constituency</th>
									<th>Total Polling Station</th>
								</tr>
							</tfoot>
							<tbody>
								<?php 
									$i=1; 
									foreach($pollstationlist as $list){
										$cons_code = eci_encrypt($list->cons_code);
								?>
										<tr>
											<td>{{$i}}</td>
											<td><a href="{{url('deo/cons-polling-staff') }}/<?php echo $cons_code; ?>">{{$list->cons_name}}</td>
											<td>{{$list->total}}</td>
										</tr>
								<?php $i++; }?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
<!-- END CONTAINER -->
@endsection