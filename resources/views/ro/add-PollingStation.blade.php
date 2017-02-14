@extends('layouts.main')
@section('content')
	 <!-- START CONTAINER -->
	<div class="container-widget">  
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					@if(Session::has('addPollErr'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('addPollErr') }}</p>
					@endif
					<div class="panel-title pageTitle titleBtn text-center">
						<span>Add Polling Station</span>
						<!-- <a href="{{URL::previous()}}" class="btn btn-default formBackBtn">Back</a> -->
					</div>
					<div class="panel-body">
						<div class="noticeCandi">
							<form method="post" action="{{url('ro/addPolStationSubmit') }}">
							<input type="hidden" name="_token" value="<?= csrf_token(); ?>">

								<?php $uidEnc=eci_encrypt($sVisorDetail->uid) ?>
								<input type="hidden" name="uidSV" value="<?php echo $uidEnc; ?>">

								
								<?php $distEnc=eci_encrypt($sVisorDetail->dist_code) ?>
								<input type="hidden" name="distSV" value="<?php echo $distEnc; ?>">

								<?php $consEnc=eci_encrypt($sVisorDetail->cons_code) ?>
								<input type="hidden" name="consSV" value="<?php echo $consEnc; ?>">



								<div class="form-group{{ $errors->has('svDistrict') ? ' has-error' : '' }}">
									<label for="svDistrict" class="form-label">District</label>
									<input name="svDistrict" value="{{ $sVisorDetail->dist_name }}" type="text" id="svDistrict" class="form-control" readonly/>
									@if ($errors->has('svDistrict'))
										<span class="help-block">
											<strong>{{ $errors->first('svDistrict') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('svConstituency') ? ' has-error' : '' }}">
									<label for="svConstituency" class="form-label">Constituency</label>
									<input name="svConstituency" value="{{ $sVisorDetail->cons_name }}" type="text" id="svConstituency" class="form-control" readonly/>
									@if ($errors->has('svConstituency'))
										<span class="help-block">
											<strong>{{ $errors->first('svConstituency') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('svBoothNum') ? ' has-error' : '' }}">
									<label for="svBoothNum" class="form-label">Booth Number</label>
									<input onkeypress="return isNumber(event)" name="svBoothNum" type="text" id="svBoothNum" class="form-control" placeholder="Booth Number" required/>
									@if ($errors->has('svBoothNum'))
										<span class="help-block">
											<strong>{{ $errors->first('svBoothNum') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('svLocality') ? ' has-error' : '' }}">
									<label for="svLocality" class="form-label">Locality</label>
									<input name="svLocality" type="text" id="svLocality" class="form-control" placeholder="Locality" required/>
									@if ($errors->has('svLocality'))
										<span class="help-block">
											<strong>{{ $errors->first('svLocality') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('svPollBuilding') ? ' has-error' : '' }}">
									<label for="svPollBuilding" class="form-label">Poll Building</label>
									<textarea rows="3" cols="30" name="svPollBuilding" id="svPollBuilding" class="form-control" required/></textarea>
									@if ($errors->has('svPollBuilding'))
										<span class="help-block">
											<strong>{{ $errors->first('svPollBuilding') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('svAreaPollStation') ? ' has-error' : '' }}">
									<label for="svAreaPollStation" class="form-label">Area of Polling Station</label>
									<textarea rows="3" cols="30" name="svAreaPollStation" id="svAreaPollStation" class="form-control" required/></textarea>
									@if ($errors->has('svAreaPollStation'))
										<span class="help-block">
											<strong>{{ $errors->first('svAreaPollStation') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('svSepEnterExit') ? ' has-error' : '' }}">
									<label for="svSepEnterExit" class="form-label">Separate Entrance and Exit</label>
									<select name="svSepEnterExit" class="form-control">
										<option value="YES" selected>Yes</option>
										<option value="NO">No</option>
									</select>
									@if ($errors->has('svSepEnterExit'))
										<span class="help-block">
											<strong>{{ $errors->first('svSepEnterExit') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('svPollingArea') ? ' has-error' : '' }}">
									<label for="svPollingArea" class="form-label">Area of Polling Station</label>
									<textarea rows="3" cols="30" name="svPollingArea" id="svPollingArea" class="form-control" required/></textarea>
									@if ($errors->has('svPollingArea'))
										<span class="help-block">
											<strong>{{ $errors->first('svPollingArea') }}</strong>
										</span>
									@endif
								</div> 

								<div class="form-group{{ $errors->has('svVotersType') ? ' has-error' : '' }}">
									<label for="svVotersType" class="form-label">Whether For</label>
									<select name="svVotersType" class="form-control">
										<option value="ALL VOTERS" selected>All Voters</option>
										<option value="ONLY MEN">Men Only</option>
										<option value="ONLY WOMEN">Women Only</option>
									</select>
									@if ($errors->has('svVotersType'))
										<span class="help-block">
											<strong>{{ $errors->first('svVotersType') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('svTotalVoters') ? ' has-error' : '' }}">
									<label for="svTotalVoters" class="form-label">Total No. Of Voters</label>
									<input onkeypress="return isNumber(event)" name="svTotalVoters" type="text" id="svTotalVoters" class="form-control" placeholder="No. Of Voters" required/>
									@if ($errors->has('svTotalVoters'))
										<span class="help-block">
											<strong>{{ $errors->first('svTotalVoters') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('svMaxDistence') ? ' has-error' : '' }}">
									<label for="svMaxDistence" class="form-label">Maximum Distence</label>
									<input name="svMaxDistence" type="text" id="svMaxDistence" class="form-control" placeholder="Maximum Distence" required/>
									@if ($errors->has('svMaxDistence'))
										<span class="help-block">
											<strong>{{ $errors->first('svMaxDistence') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('svRemarks') ? ' has-error' : '' }}">
									<label for="svRemarks" class="form-label">Remarks</label>
									<textarea rows="3" cols="30" name="svRemarks" id="svRemarks" class="form-control"/></textarea>
									@if ($errors->has('svRemarks'))
										<span class="help-block">
											<strong>{{ $errors->first('svRemarks') }}</strong>
										</span>
									@endif
								</div>

								<button type="submit" class="btn btn-default">Add Polling Station</button>
							</form>
						</div>
					</div>
				</div>
			</div>		
		</div>  
	</div>
	<script type="text/javascript">
		function isNumber(evt) {
			var charCode = (evt.which) ? evt.which : event.keyCode
			if (charCode != 43 && charCode > 31 && (charCode < 48 || charCode > 57))
			return false;
			return true;
		}
	</script>
@endsection

