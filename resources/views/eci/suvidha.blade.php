@extends('layouts.main')
@section('content')
<?php
$distCodeCheck= (isset($_GET['dist_code']))? $_GET['dist_code'] : ""; 
$consCodeCheck= (isset($_GET['cons_code']))? $_GET['cons_code'] : "";

$encryptConsCode= (isset($encryptCons))? $encryptCons : "";
$encryptDistCode= (isset($encryptDist))? $encryptDist : "";
$consList= (isset($constituency))? $constituency : "";
?>
    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">
				<div class="searchBar">
					<form class="topSearchForm" enctype="multipart/form-data" method="get" action="{{url('eci/suvidhaSub') }}">
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
							<button type="submit" class="btn btn-default">Submit</button>
						</div>

					</form>
				</div>
			</div>
		</div>  
		<?php 
		if(($distCodeCheck!=="") && ($consCodeCheck!=="") || ($encryptConsCode!=="") && ($encryptDistCode!=="")) { 
		//$dataCount=count($getdata);
		//if($dataCount>0){
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget">
					<div class="panel-title pageTitle">
						<span>Permission Requests</span>
					</div>
					<div class="panel-body">
						<table class="table table-bordered tablefilter">
							<thead>
								<tr>
									<th>Candidate Name</th>
									<th>Applicant Type</th>
									<th>Party</th>
									<th>Applied For</th>
									<th>Requested On</th>
									<th>Status</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Candidate Name</th>
									<th>Applicant Type</th>
									<th>Party</th>
									<th>Applied For</th>
									<th>Requested On</th>
									<th>Status</th>
								</tr>
							</tfoot>
							<tbody>
							@if($getdata)
							@foreach ($getdata as $data)
								<?php 
									//dd($data);
									$visiterId = $data->VisitorType; 
									$AppliedForID = $data->AppliedForID; 
									if($visiterId == '0'){ 
										$visitertype = "Party's Representative";
									} elseif ($visiterId == '1') {
										$visitertype = "Candidate";
									}
									elseif ($visiterId == '2') {
										$visitertype = "Candidate's Representative";
									}
									elseif ($visiterId == '3') {
										$visitertype = "Election Agent";
									}
									else{
										$visitertype = 'Others';
									}

									if ($AppliedForID == '1') {
										$AppliedFor = "Application for permission to hold meeting & Loud Speaker";
									}
									elseif ($AppliedForID == '2') {
										$AppliedFor = "Application";
									}
									elseif ($AppliedForID == '3') {
										$AppliedFor = "Aplication for Vehicle Permit";
									}
									elseif ($AppliedForID == '4') {
										$AppliedFor = "Application for Permit to take out Procession & Loud Speaker";
									}
									elseif ($AppliedForID == '5') {
										$AppliedFor = "Application for Permit for Street Corner Meeting & Loud Speaker";
									}
									elseif ($AppliedForID == '6') {
										$AppliedFor = "Application for Helicopter & Helipad";
									}
									elseif ($AppliedForID == '9') {
										$AppliedFor = "Permit for construction of Rostrum/Barricade";
									}
									else{
										$AppliedFor = "Aplication for Vehicle Permit (Intra District)";
									}
								?>
								<tr>
									<td>
									<a href="{{ url('/eci/suvidha-detail') }}/{{eci_encrypt($data->ApplicationID)}}">{{ $data->Name }}</a>
									</td>
									<td>{{ $visitertype }}</td>
									<td>
										@foreach ($getparty as $party)
											<?php
												if($party['id'] == $data->PoliticalPartyID){
													echo $party['name'];
												}
											?>
										@endforeach
									</td>
									<td>{{ $AppliedFor }}</td>
									<td>{{ $data->EntryDate }}</td>
									<td>{{ $data->Status }}</td>
								</tr>
							@endforeach
							@endif								
							</tbody>
						</table>
					</div>
				</div>
			</div>		
		</div>
		<?php } ?>
	</div>
	<!-- END CONTAINER -->
@endsection

