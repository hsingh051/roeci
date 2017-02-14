@extends('layouts.main')
@section('content')
<!-- START CONTAINER -->
	<div class="container-widget">	
		<div class="row">
			<!-- Nominations -->
			<div class="col-md-12">
				<div class="panel panel-widget">
					<div class="panel-title pageTitle titleBtn clearfix">
						<span>BOOTH AWARENESS GROUP</span>
					</div>
					<div class="panel-body">
						<table id="tableview" class="table table-bordered">
							<thead>
								<tr>
									<th>NAME</th>
									<th>CONTACT NUMBER</th>
									<th>ADDRESS</th>
									<th>ORGANIZATION</th>
									<th>DESIGNATION</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>NAME</th>
									<th>CONTACT NUMBER</th>
									<th>ADDRESS</th>
									<th>ORGANIZATION</th>
									<th>DESIGNATION</th>
								</tr>
							</tfoot>
							<tbody>
								</tr>
								@foreach ($polling_detail as $polling_details)
								<tr>
									<td>{{ $polling_details->name }}</td>
									<td>{{ $polling_details->phone }}</td>
									<td>{{ $polling_details->address }}</td>
									<td>{{ $polling_details->organisation }}</td>
									<td>{{ $polling_details->designation }}</td>
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