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
									<th>Constituencies</th>
									<th>Districts</th>
									<th>Status</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>S.No</th>
									<th>Constituencies</th>
									<th>Districts</th>
									<th>Status</th>
								</tr>
							</tfoot>
							<tbody>
								<?php 
									$i=1; 
									foreach($evmlist as $list){?>
										<tr>
											<td>{{$i}}</td>
											<td>{{$list->cons_name}}</td>
											<td>{{$list->dist_name}}</td>
											<td class="color-red"><b>Pending</b></td>
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