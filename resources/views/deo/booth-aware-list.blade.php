@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<!-- Nominations -->
			<div class="col-md-12">
				<div class="panel panel-widget">
	            	@if(Session::has('boothAware'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('boothAware') }}</p>
					@endif
					<div class="panel-title pageTitle titleBtn clearfix">
						<span>Booth Awareness Groups</span>
						<div class="panel-btn">
							<a href="{{url('deo/add-booth-aware') }}" class="btn btn-default">Add Booth Awareness Group</a>
							<a href="{{url('deo/booth-aware-csv') }}" class="btn btn-default">Import CSV</a>
						</div>
					</div>
					<div class="panel-body">
						<table class="table table-bordered tablefilter">
							<thead>
								<tr>
									<th>Group Name</th>
									<th>Phone Number</th>
									<th>Address</th>
									<th>District</th>
									<th>Constituency</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Group Name</th>
									<th>Phone Number</th>
									<th>Address</th>
									<th>District</th>
									<th>Constituency</th>
								</tr>
							</tfoot>
							<tbody>
							@foreach($boothAwareList as $boothAwareLists)
								<tr>
									<td>{{ $boothAwareLists->name }}</td>
									<td>{{ $boothAwareLists->phone }}</td>
									<td>{{ $boothAwareLists->address }}</td>
									<td>{{ $boothAwareLists->dist_name }}</td>
									<td>{{ $boothAwareLists->cons_name }}</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- End Nominations -->
		</div>  
	</div>
	<!-- END CONTAINER -->
@endsection

