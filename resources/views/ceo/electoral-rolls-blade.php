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
						<div class="form-group{{ $errors->has('cons_code') ? ' has-error' : '' }}">
							<select name="dist_code" class="form-control" id="dist_code">
								<option value="">Select District</option>
								$user = Auth::user();
      							$stateCeo = Auth::user()->state_id;
								$districts = DB::table('districts')->where('state_id', $stateCeo)->get();
								@foreach($districts as $data)
								<?php
									$dist_code=eci_encrypt($data->dist_code);
								?>
								<option value="{{ $dist_code }}">{{ $data->dist_name }}</option>
								@endforeach
							</select>
							<select name="cons_code" class="form-control" id="cons_code">
								<option value="">Select Constituency</option>
								@foreach($constituency as $data)
								<?php
									$cons_code=eci_encrypt($data->cons_code);
								?>
								<option value="{{ $cons_code }}" <?php if(isset($_GET['cons_code'])){ if($_GET['cons_code'] == $cons_code) echo"selected"; } ?> >{{ $data->cons_name }}</option>
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
						<div id="consTest">

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
									<th>VOTER NAME</th>
									<th>DATE OF BIRTH</th>
									<th>AGE</th>
									<th>PHONE</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>VOTER NAME</th>
									<th>DATE OF BIRTH</th>
									<th>AGE</th>
									<th>PHONE</th>
								</tr>
							</tfoot>
							<tbody>
								@foreach($votersList as $votersLists)
								<?php
									$iCardNo=$votersLists->idcardNo;
									$iCardNoEnc=eci_encrypt($iCardNo)
								?>
								<tr>
									<td><a href="{{ url('/ro/voter-detail/'.$iCardNoEnc) }}">{{ $votersLists->fm_nameEn }} {{ $votersLists->LastNameEn }}</a></td>
									<td>{{ $votersLists->dob }}</td>
									<td>{{ $votersLists->age }}</td>
									<td>{{ $votersLists->mobileno }}</td>
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
