@extends('layouts.main')
@section('content')

	<?php

		$selectedPoll= (isset($_GET['ps_id']))? $_GET['ps_id'] : "";
		$selectedCons= (isset($_GET['cons_code']))? $_GET['cons_code'] : "";

		$encryptPSid= (isset($encPsId))? $encPsId : "";
		$encryptConsCode= (isset($encConsCode))? $encConsCode : "";

		$consList= (isset($constituency))? $constituency : "";
		$pslistTemp= (isset($pslist))? $pslist : "";
		

	?>
	
	<!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">
				<div class="searchBar">
					<form class="topSearchForm" enctype="multipart/form-data" method="get" action="{{url('deo/electoral-rolls-submit') }}">
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
						<div class="form-group{{ $errors->has('ps_id') ? ' has-error' : '' }}">
							<select id="ps_id" name="ps_id" class="form-control">
								<option value="">Select Polling Station</option>
								<?php
									if(isset($pslistTemp)){
										foreach($pslistTemp as $ps):
							
										$ps_id=eci_encrypt($ps->ps_id);
								?>
										<option value="{{ $ps_id }}" <?php if($encryptPSid == $ps_id) { echo"selected"; } ?> >{{ $ps->poll_building }}</option>
								<?php
									endforeach;
									}
								?>
							</select>
							@if ($errors->has('ps_id'))
								<span class="help-block">
									<strong>{{ $errors->first('ps_id') }}</strong>
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
		
		<div class="row hideClass">
			<!-- Nominations -->
			<?php if((($selectedPoll!=="") && ($selectedCons!=="")) || (($encryptPSid!=="") && ($encryptConsCode!==""))) { ?>
			<div class="col-md-12">
				<div class="panel panel-widget">
					<div class="panel-title pageTitle">
						<span>Electoral Rolls</span>
					</div>
					<div class="panel-body">
						<table id="example0" class="table table-bordered tablefilter">
							<thead>
								<tr>
									<!-- <th>PART NAME</th> -->
									<th>SERIAL NUMBER</th>
									<th>EPIC NUMBER</th>
									<th>VOTER NAME</th>
									<th>RELATION TYPE</th>
									<th>RELATION NAME</th>
									<th>DATE OF BIRTH</th>
									<th>AGE</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<!-- <th>PART NAME</th> -->
									<th>SERIAL NUMBER</th>
									<th>EPIC NUMBER</th>
									<th>VOTER NAME</th>
									<th>RELATION TYPE</th>
									<th>RELATION NAME</th>
									<th>DATE OF BIRTH</th>
									<th>AGE</th>
								</tr>
							</tfoot>
							<tbody>
								@foreach($votersList as $votersLists)
								<?php
									if(@$votersLists->idcardNo){
										$iCardNo = $votersLists->idcardNo;
										$ps_id = $votersLists->ps_id;
										$name = $votersLists->fm_nameEn." ".$votersLists->LastNameEn;
										$age = $votersLists->age;
										$dob = date("d F, Y",strtotime($votersLists->dob));
										$mobileno = $votersLists->mobileno;
										$slnoinpart = $votersLists->slnoinpart;
										$rlnType = $votersLists->rlnType;
										$rln_name =  $votersLists->rln_Fm_NmEn." ".$votersLists->rln_L_NmEn;
										$age = $votersLists->age;
										$dob = date("d F, Y",strtotime($votersLists->dob));
									}elseif(@$votersLists->IDCARD_NO){
										$iCardNo = $votersLists->IDCARD_NO;
										$ps_id = $votersLists->PART_NO;
										$name = $votersLists->Fm_NameEn." ".$votersLists->LastNameEn;
										$dob = date("d F, Y",strtotime($votersLists->dob));
										$age = $votersLists->AGE;
										$mobileno = $votersLists->Mobileno;
										$slnoinpart = $votersLists->SLNOINPART;
										$rlnType = $votersLists->RLN_TYPE;
										$rln_name = $votersLists->Rln_Fm_NmEn. " ".$votersLists->RLn_L_NmEn;
										$age = $votersLists->AGE;
										$dob = date("d F, Y",strtotime($votersLists->dob));
									}
									$iCardNoEnc = eci_encrypt($iCardNo);
								?>
								<tr>
									
									<td>{{ $slnoinpart }}</td>
									<td><a href="{{ url('/deo/voter-detail/'.$iCardNoEnc) }}">{{ $iCardNo }}</a></td>
									<td>{{ $name }}</td>
									<td>
									<?php
										if(($rlnType)=="F"){
											echo "FATHER";
										}
										if(($rlnType)=="H"){
											echo "HUSBAND";
										}
									?>
									</td>
									<td>{{ $rln_name }}</td>
									<td>{{ $dob }}</td>
									<td>{{ $age }}</td>
								</tr>	
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>	
			<?php } ?>
			<!-- End Nominations -->
		</div>  
	</div>
	<!-- END CONTAINER -->	
	<div style="display:none;" class="loadingPic loaderShowHide"></div>
@endsection
