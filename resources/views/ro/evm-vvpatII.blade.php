@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
	<div class="container-widget">
		@if(Session::has('message'))
		<div class="tab-content evm-tab-content">
			<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
		</div>
		@else
		<div class="row">
			<div class="col-md-12">
				<ul class="nav nav-pills evm-tabs" role="tablist">
					<li role="presentation" class="active"><a href="#evm" aria-controls="evm" role="tab" data-toggle="tab">EVM</a></li>
					<li role="presentation"><a href="#vvpat" aria-controls="vvpat" role="tab" data-toggle="tab">VVPAT</a></li>
				</ul>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="searchBar">
					<form enctype="multipart/form-data" method="get" action="{{url('ro/search-evm-vvpat') }}">
						<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
						<select class="form-control" name="rand_id">
							<?php
								$rand1=eci_encrypt('1');
							?>
							<option value="<?php echo $rand1; ?>">First Randomization</option>
							<?php if($visibile == 1){ 
								$rand2=eci_encrypt('2');
							?>
							<option value="<?php echo $rand2; ?>" selected >Second Randomization</option>
							<?php } ?>
						</select>
						<input type="submit" value="Search" class="btn btn-default">
					</form>
				</div>
			</div>
		</div> 
		
		<div class="tab-content evm-tab-content">
			<div role="tabpanel" class="tab-pane active" id="evm">
				 
				<div class="row">
					<!-- Nominations -->
					<div class="col-md-12">
						<div class="panel panel-widget">
							<div class="panel-title pageTitle">EVM</div>
							<div class="panel-body">
								<table class="table table-bordered tablefilter">
									<thead>
										<tr>
											<th>Polling Station</th>
											<th>CU</th>
											<th>BU</th>
										</tr>
									</thead>
									<tfoot>
										<th>Polling Station</th>
										<th>CU</th>
										<th>BU</th>
									</tfoot>
									<tbody>
										@foreach ($getsecondrandomisation as $getdata)
										<tr>
											<td>{{$getdata->poll_building}}</td>
											<td>{{$getdata->cu}}</td>
											<td>{{$getdata->bu1}}</td>
										</tr>
										@endforeach
									</tbody>
								</table>
								
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div role="tabpanel" class="tab-pane" id="vvpat">
				<div class="row">
					<!-- Nominations -->
					<div class="col-md-12">
						<div class="panel panel-widget">
							<div class="panel-title pageTitle">VVPAT</div>
							<div class="panel-body">
								<table class="table table-bordered tablefilter1">
									<thead>
										<tr>
											<th>Polling Station</th>
											<th>VVPAT No</th>
										</tr>
									</thead>
									<tfoot>
										<th>Polling Station</th>
										<th>VVPAT No</th>
									</tfoot>
									<tbody>
										@foreach ($getsecondrandomisation as $getdata)
										<tr>
											<td>{{$getdata->poll_building}}</td>
											<td>{{$getdata->vvpat}}</td>
											
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
		@endif
		
	</div>
	<!-- END CONTAINER -->
@endsection