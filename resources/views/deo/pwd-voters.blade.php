@extends('layouts.main')
@section('content')
<?php
	$selectedCons= (isset($_GET['cons_code']))? $_GET['cons_code'] : "";
	$encryptConsCode= (isset($encConsCode))? $encConsCode : "";
	$consList= (isset($constituency))? $constituency : "";
?>
	<!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">
				<div class="searchBar">
					<form class="topSearchForm" enctype="multipart/form-data" method="get" action="{{url('deo/pwd-voters-sub') }}">
					<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
						<div class="form-group{{ $errors->has('cons_code') ? ' has-error' : '' }}">
							<select name="cons_code" class="form-control" id="cons_code">
								<option value="">Select Assembly Constituency</option>
								@foreach($consList as $data)
								<?php
									$cons_code=eci_encrypt($data->cons_code);
								?>
								<option value="{{ $cons_code }}" <?php if($encryptConsCode == $cons_code) { echo"selected"; } ?> >{{ $data->cons_name }}
								</option>
								@endforeach
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
		<?php if(($selectedCons!=="") || ($encryptConsCode!=="")) { ?>
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
		<?php } ?>
	</div>
	<!-- END CONTAINER -->	
@endsection
