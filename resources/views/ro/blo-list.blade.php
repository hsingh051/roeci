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
					<div class="panel-title pageTitle titleBtn clearfix">
						<span>Sector Officer List</span>
						<div class="panel-btn">
							<a href="{{url('ro/addBLOCsv') }}" class="btn btn-default">Impot CSV</a>
						</div>
					</div>
					<div class="panel-body">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>Sector Officer Name</th>
									<th>Contact Number</th>
									<th>Department</th>
									<th>Designation</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								@foreach ($BLOs as $BLO)
								<tr>
								<?php
									$uid = eci_encrypt($BLO->uid);
								?>
									<td><a href="{{url('ro/blo-detail') }}/<?php echo $uid; ?>">{{ $BLO->name }}</a></td>
									<td>{{ $BLO->phone }}</td>
									<td>{{ $BLO->organisation }}</td>
									<td>{{ $BLO->designation }}</td>
									<td>
										<a href="{{url('ro/supervisorEdit') }}/<?php echo $uid; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
									</td>
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

