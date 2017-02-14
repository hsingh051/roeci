@extends('layouts.main')
@section('content')

	<?php $selectedPoll= (isset($_GET['ps_id']))? $_GET['ps_id'] : "";
	if($selectedPoll==""){ ?>
		<style>
			.hideClass{
				display: none;
			}
		</style>
	<?php }else{ ?>
		<style>
			.hideClass{
				display: block;
			}
		</style>
	<?php } ?>

    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">
				<div class="searchBar">
					<form class="topSearchForm" enctype="multipart/form-data" method="get" action="{{url('ceo/electoral-rolls-submit') }}">
						<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
						<div class="form-group{{ $errors->has('dist_code') ? ' has-error' : '' }}">
							<select name="dist_code" class="form-control poll_dist_code_ceo">
								<option value="">Select District</option>
								<?php
								$user = Auth::user();
      							$stateCeo = Auth::user()->state_id;
								$districts = DB::table('districts')->where('state_id', $stateCeo)->get();
								foreach($districts as $data)
								{
									$dist_code=eci_encrypt($data->dist_code);
								?>
								<option value="{{ $dist_code }}" <?php if(isset($_GET['dist_code'])){ if($_GET['dist_code'] == $dist_code) echo 'selected="selected"'; } ?>>{{ $data->dist_name }}</option>
								<?php
								}
								?>
							</select>
						</div>
						<div class="form-group{{ $errors->has('cons_code') ? ' has-error' : '' }}">
							<select name="cons_code" class="form-control poll_cons_code_ceo">
								<?php 
									if(isset($_GET['cons_code']))
									{ 
										$cons_code=eci_decrypt($_GET['cons_code']);
										$cons_name = DB::table('constituencies')->where('cons_code', $cons_code)->first();
										echo "<option selected='selected'>$cons_name->cons_name</option>";
									} 
									else
									{
								?>
									<option value="">Select Constituency</option>
								<?php
									}
								?>
								<?php 
								if(isset($consList))
								{
									foreach($consList as $data)
									{								
										$cons_code=eci_encrypt($data->cons_code);
								?>
								<option value="<?php echo $cons_code ?>" <?php if(isset($_GET['cons_code'])){ if($_GET['cons_code'] == $cons_code) echo"selected"; } ?> ><?php echo $data->cons_name ?></option>
									<?php 
									}
								}
								?>
							</select>
							@if ($errors->has('cons_code'))
							<span class="help-block">
								<strong>{{ $errors->first('cons_code') }}</strong>
							</span>
							@endif
						</div>
						<div class="form-group{{ $errors->has('ps_id') ? ' has-error' : '' }}">
							<select id="ps_id" name="ps_id" class="form-control">
								<?php 
									if(isset($_GET['ps_id']))
									{ 
										$cons_code=eci_decrypt($_GET['cons_code']);
										$ps_id=eci_decrypt($_GET['ps_id']);
										$poll_booths = DB::table('poll_booths')->where('cons_code', $cons_code)->where('ps_id', $ps_id)->first();
										echo "<option selected='selected'>$poll_booths->poll_building</option>";
									} 
									else
									{
								?>
									<option value="">Select Polling Station</option>
								<?php
									}
								?>
								<?php
									if(isset($pslist)){
										foreach($pslist as $ps):
							
										$ps_id=eci_encrypt($ps->ps_id);
								?>
										<option value="{{ $ps_id }}" <?php if(isset($_GET['ps_id'])){ if($_GET['ps_id'] == $ps_id) echo"selected"; } ?> >{{ $ps->poll_building }}</option>
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
			<?php if($selectedPoll!==""){ ?>
			<div class="col-md-12">
				<div class="panel panel-widget">
					<div class="panel-title pageTitle">Electoral Rolls</div>
					<div class="panel-body">
						<table id="example0" class="table table-bordered tablefilter">
							<thead>
								<tr>
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
									<td><a href="{{ url('/ceo/voter-detail/'.$iCardNoEnc) }}">{{ $iCardNo }}</a></td>
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
@endsection




