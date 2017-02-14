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
					<li role="presentation" class="active"><a href="#CU" aria-controls="CU" role="tab" data-toggle="tab">BALLOT</a></li>
					<li role="presentation"><a href="#BU" aria-controls="BU" role="tab" data-toggle="tab">CONTROL</a></li>
					<li role="presentation"><a href="#vvpat" aria-controls="vvpat" role="tab" data-toggle="tab">VVPAT</a></li>
				</ul>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="searchBar">
						<form class="topSearchForm" enctype="multipart/form-data" method="get" action="{{url('ro/search-evm-vvpat') }}">
							<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
							<div class="form-group">
								<select class="form-control" name="rand_id">
									<?php
										$rand1=eci_encrypt('1');
									?>
									<option value="<?php echo $rand1; ?>" <?php if($_GET['rand_id'] == $rand1) echo"selected"; ?> >First Randomization</option>
									<?php if($visibile == 1){ 
										$rand2=eci_encrypt('2');
									?>
									<option value="<?php echo $rand2; ?>" <?php if($_GET['rand_id'] == $rand2) echo"selected"; ?> >Second Randomization</option>
									<?php } ?>
								</select>
							</div>
							<div class="form-group"><input type="submit" value="Search" class="btn btn-default"></div>
						</form>
					</div>
				</div>
			</div> 
		</div>
		
		<div class="tab-content evm-tab-content">
			<div role="tabpanel" class="tab-pane active" id="CU">				 
				<div class="row">
					<!-- Nominations -->
					<div class="col-md-12">
						<div class="panel panel-widget heightWidget">
							<div class="panel-title pageTitle titleBtn clearfix">
								<!-- <div class="panel-btn">
									<a href="{{URL('/')}}/deo/add_evm-vvpat" class="btn btn-default">Upload Randomization</a>
								</div> -->
							</div>
							<div class="panel-body">
								<table class="table table-bordered tablefilter">
									<thead>
										<tr>
											<th>Constituency Name</th>
											<th>Unit ID</th>
											<th>Manufacturer</th>
											<th>Role</th>
										</tr>
									</thead>
									<tfoot>
										<tr>
											<th>Constituency Name</th>
											<th>Unit ID</th>
											<th>Manufacturer</th>
											<th>Role</th>
										</tr>
									</tfoot>
									<tbody>
										@foreach ($getfirstrandomisation as $getdata)
										@if($getdata->unit_type == 'BALLOT')
										<tr>
											<td>{{$getdata->cons_name}}</td>
											<td>{{$getdata->unit_id}}</td>
											<td>{{$getdata->manufacturer}}</td>
											<td>{{$getdata->role}}</td>
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
			
			<div role="tabpanel" class="tab-pane" id="BU">
				 
				<div class="row">
					<!-- Nominations -->
					<div class="col-md-12">
						<div class="panel panel-widget heightWidget">
							<div class="panel-body">
								<table class="table table-bordered tablefilter1">
									<thead>
										<tr>
											<th>Constituency Name</th>
											<th>Unit ID</th>
											<th>Manufacturer</th>
											<th>Role</th>
										</tr>
									</thead>
									<tfoot>
										<tr>
											<th>Constituency Name</th>
											<th>Unit ID</th>
											<th>Manufacturer</th>
											<th>Role</th>
										</tr>
									</tfoot>
									<tbody>
										@foreach ($getfirstrandomisation as $getdata)
										@if($getdata->unit_type == 'CONTROL')
										<tr>
											<td>{{$getdata->cons_name}}</td>
											<td>{{$getdata->unit_id}}</td>
											<td>{{$getdata->manufacturer}}</td>
											<td>{{$getdata->role}}</td>
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

			<div role="tabpanel" class="tab-pane" id="vvpat">
				 
				<div class="row">
					<!-- Nominations -->
					<div class="col-md-12">
						<div class="panel panel-widget heightWidget">
							<div class="panel-body">
								<table class="table table-bordered tablefilter2">
									<thead>
										<tr>
											<th>Constituency Name</th>
											<th>Unit ID</th>
											<th>Manufacturer</th>
											<th>Role</th>
										</tr>
									</thead>
									<tfoot>
										<tr>
											<th>Constituency Name</th>
											<th>Unit ID</th>
											<th>Manufacturer</th>
											<th>Role</th>
										</tr>
									</tfoot>
									<tbody>
										@foreach ($getfirstrandomisation as $getdata)
										@if($getdata->unit_type == 'VVPAT')
										<tr>
											<td>{{$getdata->cons_name}}</td>
											<td>{{$getdata->unit_id}}</td>
											<td>{{$getdata->manufacturer}}</td>
											<td>{{$getdata->role}}</td>
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
		@endif
		
	</div>
	<!-- END CONTAINER -->
@endsection