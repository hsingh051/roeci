@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget">
					<div class="panel-title pageTitle">
                        <span>EVM</span>
                    </div>
					<div class="panel-body">
						<table class="table table-bordered tablefilter">
							<thead>
								<tr>
									<th>S.No</th>
									<th>Polling Station Name</th>
									<th>Total No. Of Voters</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>S.No</th>
									<th>Polling Station Name</th>
									<th>Total No. Of Voters</th>
								</tr>
							</tfoot>
							<tbody>
								<?php
									$dist_code=eci_encrypt($dist_code);
									$cons_code=eci_encrypt($cons_code);
									$i=1; 
									foreach($psvoterlist as $list){
										$ps_id=eci_encrypt($list->ps_id);
								?>
										<tr>
											<td>{{$i}}</td>
											<td><a href="{{ url('ceo/electrollist') }}/<?php echo $ps_id.'?&dist_code='.$dist_code.'&cons_code='.$cons_code; ?>">{{$list->poll_building}}</a></td>
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