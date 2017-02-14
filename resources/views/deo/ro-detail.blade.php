@extends('layouts.main')
@section('content')
<!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<!-- Nominations -->
			<div class="col-md-12">
				<div class="panel panel-widget">
					
					<div class="panel-title pageTitle">
						{{ $getRoDetail->name }}
						{{ $getRoDetail->cons_name }}
					</div>
					
					<div class="panel-body">
						<table id="tableview" class="table table-bordered">
							<thead>
								<tr>
									<th class="w45">Polling Stations</th>
									<th>Supervisor Name</th>
									<th>PRO</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Polling Stations</th>
									<th>Supervisor Name</th>
									<th>Contact Number</th>
								</tr>
							</tfoot>
							<tbody>								
								@foreach ($getsupervisors as $getsupervisor)
								<tr>
									<td>{{ $getsupervisor->poll_building }}</td>
									<td>{{ $getsupervisor->name }}</td>
									<td>{{ $getsupervisor->phone }}</td>
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