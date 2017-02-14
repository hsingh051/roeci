@extends('layouts.main')
@section('content')
<!-- START CONTAINER -->
	<div class="container-widget">	
		<div class="row">
			<!-- Nominations -->
			<div class="col-md-12">
				<div class="panel panel-widget">
					<div class="panel-title pageTitle">RO List</div>
					<div class="panel-body">
						<table id="tableview" class="table table-bordered">
							<thead>
								<tr>
									<th class="w45">RO Name</th>
									<th>Constituency Name</th>
									<th>Contact Number</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>RO Name</th>
									<th>Constituency Name</th>
									<th>Contact Number</th>
								</tr>
							</tfoot>
							<tbody>
								</tr>
								@foreach ($getRoConst as $getRos)
								<?php
									$roId=eci_encrypt($getRos->uid);
								?>
								<tr>
									<td><a href="{{ url('ceo/supervisor-list') }}/<?php echo $roId; ?>">{{ $getRos->name }}</a></td>
									<td>{{ $getRos->cons_name }}</td>
									<td>{{ $getRos->phone }}</td>
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