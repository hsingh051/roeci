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
					<form class="topSearchForm" enctype="multipart/form-data" method="get" action="{{url('eci/pwd-voters-sub') }}">
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
		<?php if(($distCodeCheck!=="") && ($consCodeCheck!=="") || ($encryptConsCode!=="") && ($encryptDistCode!=="")) { ?>
		<div class="row">
			<!-- Nominations -->
			<div class="col-md-12">
				<div class="panel panel-widget">
					<div class="panel-title pageTitle titleBtn clearfix">
						<span>PWD Database</span>
					</div>
					<div class="panel-body">
						<table class="table table-bordered tablefilter">
							<thead>
								<tr>
									<th>SERIAL NUMBER</th>
									<th>EPIC NUMBER</th>
									<th>VOTER NAME</th>
									<th>RELATION TYPE</th>
									<th>RELATION NAME</th>
									<th>DATE OF BIRTH</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>SERIAL NUMBER</th>
									<th>EPIC NUMBER</th>
									<th>VOTER NAME</th>
									<th>RELATION TYPE</th>
									<th>RELATION NAME</th>
									<th>DATE OF BIRTH</th>
								</tr>
							</tfoot>
							<tbody>
								@foreach ($getPwdVoter as $getPwdVoters)
								<tr>
									<td>{{ $getPwdVoters->slnoinpart }}</td>
									<td>
										<?php $iCardNoEnc = eci_encrypt($getPwdVoters->IDCARD_NO); ?>
										<a href="{{ url('/deo/voter-detail/'.$iCardNoEnc) }}">{{ $getPwdVoters->IDCARD_NO }}</a>
									</td>
									<td>{{ $getPwdVoters->Fm_NameEn }} {{ $getPwdVoters->LastNameEn }}</td>
									<td>
									<?php
										if($getPwdVoters->RLN_TYPE == "H"){
											echo "HUSBAND";
										}
										if($getPwdVoters->RLN_TYPE == "F"){
											echo "FATHER ";
										}
									?>
									</td>
									<td>{{ $getPwdVoters->Rln_Fm_NmEn }} {{ $getPwdVoters->RLn_L_NmEn }}</td>
									<td>{{ $getPwdVoters->dob }}</td>
								</tr>	
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- End Nominations -->
		</div>  
		<?php  } ?>
	</div>
	<!-- END CONTAINER -->
@endsection