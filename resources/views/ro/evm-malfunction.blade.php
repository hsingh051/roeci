@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
	<div class="container-widget">

		<div class="row">
			<div class="col-md-12">
				<ul class="nav nav-pills evm-tabs" role="tablist">
					<li role="presentation" class="active"><a href="#CU" aria-controls="CU" role="tab" data-toggle="tab">Malfunction Pending</a></li>
					<li role="presentation"><a href="#BU" aria-controls="BU" role="tab" data-toggle="tab">Malfunction Resolved</a></li>
				</ul>
			</div>
		</div>
		
		<div class="tab-content evm-tab-content">
			<div role="tabpanel" class="tab-pane active" id="CU">				 
				<div class="row">
					<!-- Nominations -->
					<div class="col-md-12">
						<div class="panel panel-widget heightWidget">
							<div class="panel-body">
								<table class="table table-bordered tablefilter">
									<thead>
										<tr>
											<th>BID</th>
											<th>Poll Building</th>
											<th>Name</th>
											<th>Comment</th>									
										</tr>
									</thead>
									<tfoot>
										<tr>
											<th>BID</th>
											<th>Poll Building</th>
											<th>Name</th>
											<th>Comment</th>									
										</tr>
									</tfoot>
									<tbody>
										@foreach($mallfunctions as $mallfunction)
										<tr>
											<td>{{ $mallfunction->bid }}</td>
											<td>{{ $mallfunction->poll_building }}</td>
											<td>{{ $mallfunction->name }}</td>
											<td>{{ $mallfunction->comment }}</td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div role="tabpanel" class="tab-pane" id="BU">
				<div class="row">
					<!-- Nominations -->
					<div class="col-md-12">
						<div class="panel panel-widget heightWidget">
							<div class="panel-body">
								<table class="table table-bordered tablefilter1">
									<thead>
										<tr>
											<th>BID</th>
											<th>Poll Building</th>
											<th>Name</th>
											<th>Reply</th>
										</tr>
									</thead>
									<tfoot>
										<tr>
											<th>BID</th>
											<th>Poll Building</th>
											<th>Name</th>
											<th>Reply</th>
										</tr>
									</tfoot>
									<tbody>
										@foreach($mallfunctions_resolve as $mallfunctions_resolves)
										<tr>
											<td>{{ $mallfunctions_resolves->bid }}</td>
											<td>{{ $mallfunctions_resolves->poll_building }}</td>
											<td>{{ $mallfunctions_resolves->name }}</td>
											<td>{{ $mallfunctions_resolves->reply }}</td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- END CONTAINER -->
@endsection

