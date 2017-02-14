@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">
				<ul class="nav nav-pills evm-tabs" role="tablist">
					<li class="active"><a href="#NPS" aria-controls="NPS" role="tab" data-toggle="tab">Notified P.S.</a></li>
					<li><a href="#APS" aria-controls="APS" role="tab" data-toggle="tab">Auxiliary P.S.</a></li>
					<li><a href="#VPS" aria-controls="VPS" role="tab" data-toggle="tab">Vulnerable P.S.</a></li>
					<li><a href="#CPS" aria-controls="CPS" role="tab" data-toggle="tab">Critical P.S.</a></li>
					<li><a href="#MPS" aria-controls="MPS" role="tab" data-toggle="tab">Model P.S.</a></li>
				</ul>
			</div>
		</div>
		<div class="tab-content evm-tab-content">
			@if(Session::has('addPollSucc'))
			<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('addPollSucc') }}</p>
			@endif
			@if(Session::has('bidsRepeatErr'))
			<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('bidsRepeatErr') }}</p>
			@endif
			@if(Session::has('maxDigitErr'))
			<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('maxDigitErr') }}</p>
			@endif
			@if(Session::has('numericErr'))
			<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('numericErr') }}</p>
			@endif
			@if(Session::has('requireErr'))
			<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('requireErr') }}</p>
			@endif
			<div role="tabpanel" class="tab-pane active" id="NPS">
				<div class="row">
					<!-- Nominations -->
					<div class="col-md-12">
						<div class="panel panel-widget">
							<div class="panel-title pageTitle titleBtn clearfix">
								<span>NOTIFIED POLLING STATIONS</span>
								<div class="panel-btn">
									<a href="{{url('/deo/addPollingStation') }}" class="btn btn-default">Add Polling Station</a>
									<a href="{{url('/deo/polingCsvForm') }}" class="btn btn-default">Import CSV</a>
									<a href="{{ url('/deo/polling-stations-map') }}" class="btn btn-default">View Map</a></div>
							</div>
							<div class="panel-body">
								<table class="table table-bordered tablefilter">
									<thead>
										<tr>
											<th>Booth Number</th>
											<th class="w45">Polling Station</th>
											<th>Supervisor</th>
											<th>Contact No.</th>
											
										</tr>
									</thead>
									<tfoot>
										<tr>
											<th>Booth Number</th>
											<th>Polling Station</th>
											<th>Supervisor</th>
											<th>Contact No.</th>
											
										</tr>
									</tfoot>
									<tbody>	
										@foreach ($pollstationlist as $polling_station)							
										@if($polling_station->poll_type == 'Notified')
											<tr>
												<td>{{$polling_station->ps_id}}</td>
												<td>
													<a href="{{ url('/deo/polling-detail') }}/{{eci_encrypt($polling_station->poll_booth_id)}}">{{$polling_station->poll_building}}</a>
												</td>
												<td>{{$polling_station->name}}</td>
												<td>{{$polling_station->phone}}</td>
											</tr>
										@endif
										@endforeach
									</tbody>
								</table>									
							</div>
						</div>
					</div>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane" id="APS">
				<div class="row">
					<!-- Nominations -->
					<div class="col-md-12">
						<div class="panel panel-widget">
							<div class="panel-title pageTitle titleBtn clearfix">
								<span>AUXILIARY POLLING STATIONS</span>
								<div class="panel-btn">
									<a href="{{url('/deo/addPollingStation') }}" class="btn btn-default">Add Polling Station</a>
									<a href="{{url('/deo/polingCsvForm') }}" class="btn btn-default">Import CSV</a>
									<a href="{{ url('/deo/polling-stations-map') }}" class="btn btn-default">View Map</a>
								</div>
							</div>
							<div class="panel-body">
								<table class="table table-bordered tablefilter1">
									<thead>
										<tr>
											<th>Booth Number</th>
											<th>Polling Station Name</th>
											<th>Supervisor Name</th>
											<th>Supervisor Contact No.</th>
										</tr>
									</thead>
									<tfoot>
										<tr>
											<th>Booth Number</th>
											<th>Polling Station Name</th>
											<th>Supervisor Name</th>
											<th>Supervisor Contact No.</th>
										</tr>
									</tfoot>
									<tbody>	
										@foreach ($pollstationlist as $polling_station)
											@if($polling_station->poll_type == 'Auxiliary')
											<tr>
												<td>{{$polling_station->ps_id}}</td>
												<td>
													<a href="{{ url('/deo/polling-detail') }}/{{eci_encrypt($polling_station->poll_booth_id)}}">{{$polling_station->poll_building}}</a>
												</td>
												<td>{{$polling_station->name}}</td>
												<td>{{$polling_station->phone}}</td>
											</tr>
											@endif
										@endforeach
									</tbody>
								</table>									
							</div>
						</div>
					</div>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane" id="VPS">
				<div class="row">
					<!-- Nominations -->
					<div class="col-md-12">
						<div class="panel panel-widget">
							<div class="panel-title pageTitle titleBtn clearfix">
								<span>VULNERABLE POLLING STATIONS</span>
								<div class="panel-btn">
									<a href="{{url('/deo/addPollingStation') }}" class="btn btn-default">Add Polling Station</a>
									<a href="{{url('/deo/polingCsvForm') }}" class="btn btn-default">Import CSV</a>
									<a href="{{ url('/deo/polling-stations-map') }}" class="btn btn-default">View Map</a>
								</div>
							</div>
							<div class="panel-body">
								<table class="table table-bordered tablefilter2">
									<thead>
										<tr>
											<th>Booth Number</th>
											<th>Polling Station Name</th>
											<th>Supervisor Name</th>
											<th>Supervisor Contact No.</th>
										</tr>
									</thead>
									<tfoot>
										<tr>
											<th>Booth Number</th>
											<th>Polling Station Name</th>
											<th>Supervisor Name</th>
											<th>Supervisor Contact No.</th>
										</tr>
									</tfoot>
									<tbody>		
										@foreach ($pollstationlist as $polling_station)	
										@if($polling_station->poll_type == 'Vulnerable')
											<tr>
												<td>{{$polling_station->ps_id}}</td>
												<td>
													<a href="{{ url('/deo/polling-detail') }}/{{eci_encrypt($polling_station->poll_booth_id)}}">{{$polling_station->poll_building}}</a>
												</td>
												<td>{{$polling_station->name}}</td>
												<td>{{$polling_station->poll_type}}</td>
											</tr>
											@endif
										@endforeach
									</tbody>
								</table>									
							</div>
						</div>
					</div>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane" id="CPS">
				<div class="row">
					<!-- Nominations -->
					<div class="col-md-12">
						<div class="panel panel-widget">
							<div class="panel-title pageTitle titleBtn clearfix">
								<span>CRITICAL POLLING STATIONS</span>
								<div class="panel-btn">
									<a href="{{url('/deo/addPollingStation') }}" class="btn btn-default">Add Polling Station</a>
									<a href="{{url('/deo/polingCsvForm') }}" class="btn btn-default">Import CSV</a>
									<a href="{{ url('/deo/polling-stations-map') }}" class="btn btn-default">View Map</a>
								</div>
							</div>
							<div class="panel-body">
								<table class="table table-bordered tablefilter3">
									<thead>
										<tr>
											<th>Booth Number</th>
											<th>Polling Station Name</th>
											<th>Supervisor Name</th>
											<th>Supervisor Contact No.</th>
										</tr>
									</thead>
									<tfoot>
										<tr>
											<th>Booth Number</th>
											<th>Polling Station Name</th>
											<th>Supervisor Name</th>
											<th>Supervisor Contact No.</th>
										</tr>
									</tfoot>
									<tbody>	
										@foreach ($pollstationlist as $polling_station)	
										@if($polling_station->poll_type == 'Critical')
											<tr>
												<td>{{$polling_station->ps_id}}</td>
												<td>
													<a href="{{ url('/deo/polling-detail') }}/{{eci_encrypt($polling_station->poll_booth_id)}}">{{$polling_station->poll_building}}</a>
												</td>
												<td>{{$polling_station->name}}</td>
												<td>{{$polling_station->phone}}</td>
											</tr>
											@endif
										@endforeach
									</tbody>
								</table>									
							</div>
						</div>
					</div>
				</div>
			</div>
			<div role="tabpanel" class="tab-pane" id="MPS">
				<div class="row">
					<!-- Nominations -->
					<div class="col-md-12">
						<div class="panel panel-widget">
							<div class="panel-title pageTitle titleBtn clearfix">
								<span>MODEL POLLING STATIONS</span>
								<div class="panel-btn">
									<a href="{{url('/deo/addPollingStation') }}" class="btn btn-default">Add Polling Station</a>
									<a href="{{url('/deo/polingCsvForm') }}" class="btn btn-default">Import CSV</a>
									<a href="{{ url('/deo/polling-stations-map') }}" class="btn btn-default">View Map</a>
								</div>
							</div>
							<div class="panel-body">
								<table class="table table-bordered tablefilter4">
									<thead>
										<tr>
											<th>Booth Number</th>
											<th>Polling Station Name</th>
											<th>Supervisor Name</th>
											<th>Supervisor Contact No.</th>
											
										</tr>
									</thead>
									<tfoot>
										<tr>
											<th>Booth Number</th>
											<th>Polling Station Name</th>
											<th>Supervisor Name</th>
											<th>Supervisor Contact No.</th>
											
										</tr>
									</tfoot>
									<tbody>	
										
										@foreach ($pollstationlist as $polling_station)							
										@if($polling_station->poll_type == 'Model')
											<tr>
												<td>{{$polling_station->ps_id}}</td>
												<td>
													<a href="{{ url('/deo/polling-detail') }}/{{eci_encrypt($polling_station->poll_booth_id)}}">{{$polling_station->poll_building}}</a>
												</td>
												<td>{{$polling_station->name}}</td>
												<td>{{$polling_station->phone}}</td>											
											</tr>
											@endif
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


