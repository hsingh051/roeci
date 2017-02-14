@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<!-- Nominations -->
			<div class="col-md-12">
				<div class="panel panel-widget">
	            	@if(Session::has('bloError1'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('bloError1') }}</p>
					@endif
					@if(Session::has('bloError2'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('bloError2') }}</p>
					@endif
					@if(Session::has('bloError3'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('bloError3') }}</p>
					@endif
					@if(Session::has('bloSucc'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('bloSucc') }}</p>
					@endif
					@if(Session::has('bloError4'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('bloError4') }}</p>
					@endif
					<div class="panel-title pageTitle titleBtn clearfix">
						<span>BLO List</span>
						<div class="panel-btn">
							<a href="{{url('ro/addBLOCsv') }}" class="btn btn-default">Import CSV</a>
						</div>
					</div>
					<div class="panel-body">
						<table class="table table-bordered tablefilter">
							<thead>
								<tr>
									<th>BLO Name</th>
									<th>Contact Number</th>
									<th>Department</th>
									<th>Designation</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>BLO Name</th>
									<th>Contact Number</th>
									<th>Department</th>
									<th>Designation</th>
									
								</tr>
							</tfoot>
							<tbody>
								@foreach ($BLOs as $BLO)
								<tr>
								<?php
									$uid = eci_encrypt($BLO->uid);
								?>
									<td>{{ $BLO->name }}</td>
									<td>{{ $BLO->phone }}</td>
									<td>{{ $BLO->organisation }}</td>
									<td>{{ $BLO->designation }}</td>
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

