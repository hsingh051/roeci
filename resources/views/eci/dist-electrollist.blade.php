@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget">
					<div class="panel-title pageTitle">
                        <span>Electrol List</span>
                    </div>
					<div class="panel-body">
						<table class="table table-bordered tablefilter">
							<thead>
								<tr>
									<th>S.No</th>
									<th>District Name</th>
									<th>Total No. Of Voters</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>S.No</th>
									<th>District Name</th>
									<th>Total No. Of Voters</th>
								</tr>
							</tfoot>
							<tbody>
								<?php 
									$i=1; 
									foreach($voterlist as $list){
										$dist_code=eci_encrypt($list->dist_code);
								?>
										<tr>
											<td>{{$i}}</td>
											<td><a href="{{ url('eci/cons-electrollist') }}/<?php echo $dist_code; ?>">{{$list->dist_name}}</a></td>
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