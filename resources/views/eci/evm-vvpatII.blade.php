@extends('layouts.main')
@section('content')
<?php
$distCodeCheck= (isset($_GET['dist_code']))? $_GET['dist_code'] : ""; 
$consCodeCheck= (isset($_GET['cons_code']))? $_GET['cons_code'] : "";
$getRand= (isset($_GET['rand_id']))? $_GET['rand_id'] : "";

$visibile= (isset($visibile))? $visibile : "";
$selectedRand= (isset($selectedRand))? $selectedRand : "";
$encryptConsCode= (isset($encryptCons))? $encryptCons : "";
$encryptDistCode= (isset($encryptDist))? $encryptDist : "";
$consList= (isset($constituency))? $constituency : "";
?>

    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">
				<div class="searchBar">
					<form class="topSearchForm" enctype="multipart/form-data" method="get" action="{{url('eci/evmVvpatSearch') }}">
					<input type="hidden" name="_token" value="<?= csrf_token(); ?>">

						<div class="form-group{{ $errors->has('dist_code') ? ' has-error' : '' }}">
							<select name="dist_code" class="form-control poll_dist_code">
									<option value="">Select district</option>
								@foreach($district as $districts)
									<?php $distCode=eci_encrypt($districts->dist_code); ?>
									<option value="{{$distCode}}" <?php if($distCode==$encryptDistCode){ echo "selected"; } ?> >{{ $districts->dist_name }}</option>
								@endforeach
							</select>
							@if ($errors->has('dist_code'))
							<span class="help-block">
								<strong>{{ $errors->first('dist_code') }}</strong>
							</span>
							@endif
						</div>


						<div class="form-group{{ $errors->has('cons_code') ? ' has-error' : '' }}">
							<select name="cons_code" class="form-control poll_cons_code">
								<option value="">Select Constituency</option>
								@if($consList)
									@foreach($consList as $constituencies)
										<?php $consCode=eci_encrypt($constituencies->cons_code); ?>
										<option value="{{$consCode}}" <?php if($consCode==$encryptConsCode){ echo "selected"; } ?> >{{ $constituencies->cons_name }}</option>
									@endforeach
								@endif
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
								<option value="<?php echo $rand1; ?>" <?php if($selectedRand==$rand1){ echo "selected"; } ?>>First Randomization</option>
								<?php if($visibile == 1){ 
									$rand2=eci_encrypt('2');
								?>
								<option value="<?php echo $rand2; ?>" <?php if($selectedRand==$rand2){ echo "selected"; } ?>>Second Randomization</option>
								<?php } ?>
							</select>
						</div>

						<div class="form-group">
							<button type="submit" class="btn btn-default">Submit</button>
						</div>

					</form>
				</div>
			</div>
		</div>

		<?php if((($distCodeCheck!=="") && ($consCodeCheck!=="") && ($getRand!=="") ) || (($encryptConsCode!=="") && ($encryptDistCode!=="") && ($selectedRand!=="") )) { ?>

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
											<th>Constituency Name</th>
											<th>Polling Station Name</th>
											<th>CU</th>
											<th>BU</th>
										</tr>
									</thead>
									<tfoot>
										<th>Constituency Name</th>
										<th>Polling Station Name</th>
										<th>CU</th>
										<th>BU</th>
									</tfoot>
									<tbody>
										@foreach ($getsecondrandomisation as $getdata)
										<tr>
											<td>{{$getdata->cons_name}}</td>
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
								<table class="table table-bordered tablefilter">
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
		<?php  } ?> 
	</div>
	<!-- END CONTAINER -->
@endsection