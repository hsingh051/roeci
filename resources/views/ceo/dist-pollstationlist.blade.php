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
						<table class="table table-bordered tablefilter">
							<thead>
								<tr>
									<th>S.No</th>
									<th>District Name</th>
									<th>Total Polling Station</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>S.No</th>
									<th>District Name</th>
									<th>Total Polling Station</th>
								</tr>
							</tfoot>
							<tbody>
								<?php 
									$i=1; 
									foreach($distPollstationlist as $list){
										$distCode=eci_encrypt($list->dist_code);
									?>
										<tr>
											<td>{{$i}}</td>
											<td><a href="{{url('/').'/ceo/cons-pollstationlist'}}/{{ $distCode }}">{{$list->dist_name}}</a></td>
											<td class="text-center">{{$list->total}}</td>
										</tr>
								<?php $i++; } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
<!-- END CONTAINER -->
@endsection




