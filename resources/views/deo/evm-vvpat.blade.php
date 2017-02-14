@extends('layouts.main')
@section('content')

<?php
$encryptCons= (isset($encryptCons))? $encryptCons : "";
$selectedRand= (isset($selectedRand))? $selectedRand : "";

?>
    <!-- START CONTAINER -->
	<div class="container-widget">
		@if(Session::has('message'))
		<div class="tab-content evm-tab-content">
			<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
		</div>
		@else
		@if(Session::has('emptyRandMsz'))
		<p class="alert alert-danger">{{ Session::get('emptyRandMsz') }}</p>
		@endif
		@if(Session::has('repeatRandMsz'))
		<p class="alert alert-danger">{{ Session::get('repeatRandMsz') }}</p>
		@endif
		@if(Session::has('emptySecondRandMsz'))
		<p class="alert alert-danger">{{ Session::get('emptySecondRandMsz') }}</p>
		@endif
		@if(Session::has('firstRandSuccess'))
		<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('firstRandSuccess') }}</p>
		@endif
		<div class="row">
			<div class="col-md-12">
				<ul class="nav nav-pills evm-tabs" role="tablist">
					<li role="presentation" class="active"><a href="#CU" aria-controls="CU" role="tab" data-toggle="tab">BALLOT</a></li>
					<li role="presentation"><a href="#BU" aria-controls="BU" role="tab" data-toggle="tab">CONTROL</a></li>
					<li role="presentation"><a href="#vvpat" aria-controls="vvpat" role="tab" data-toggle="tab">VVPAT</a></li>
				</ul>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="searchBar">
					<form class="topSearchForm" enctype="multipart/form-data" method="get" action="{{url('deo/search-evm-vvpat') }}">
						<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
						<div class="form-group">
							<select class="form-control" name="cons_code">
								<option value="">Select Constituency</option>
								@foreach ($getConst as $Const)
								<?php
									$cons_code=eci_encrypt($Const->cons_code);
								?>
								<option value="{{$cons_code}}" <?php if($cons_code==$encryptCons){echo "selected"; } ?>>{{$Const->cons_name}}</option>
								@endforeach
							</select>
							@if ($errors->has('cons_code'))
								<span class="help-block">
									<strong>{{ $errors->first('cons_code') }}</strong>
								</span>
							@endif
						</div>
						<div class="form-group">
							<select class="form-control" name="rand_id">
								<?php
									$rand1=eci_encrypt('1');
								?>
								<option value="<?php echo $rand1; ?>" <?php if($rand1==$selectedRand){echo "selected";} ?>>First Randomization</option>
								<?php if($visibile == 1){ 
									$rand2=eci_encrypt('2');
								?>
								<option value="<?php echo $rand2; ?>" <?php if($rand2==$selectedRand){echo "selected";} ?>>Second Randomization</option>
								<?php } ?>
							</select>
						</div>
						<div class="form-group"><input type="submit" value="Search" class="btn btn-default"></div>
					</form>
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
								<div class="panel-btn">
									<a href="{{URL('/')}}/deo/add_evm-vvpat" class="btn btn-default">Upload Randomization</a>
								</div>
							</div>
							<div class="panel-body">
								<table class="table table-bordered tablefilter">
									<thead>
										<tr>
											<th>Constituency Name</th>
											<th>BU</th>
											<th>Manufacturer</th>
											<th>Role</th>
										</tr>
									</thead>
									<tfoot>
										<tr>
											<th>Constituency Name</th>
											<th>BU</th>
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
											<th>CU</th>
											<th>Manufacturer</th>
											<th>Role</th>
										</tr>
									</thead>
									<tfoot>
										<tr>
											<th>Constituency Name</th>
											<th>CU</th>
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
											<th>VVPAT</th>
											<th>Manufacturer</th>
											<th>Role</th>
										</tr>
									</thead>
									<tfoot>
										<tr>
											<th>Constituency Name</th>
											<th>VVPAT</th>
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