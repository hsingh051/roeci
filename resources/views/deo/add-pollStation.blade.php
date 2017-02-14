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
						<a href="{{URL::previous()}}" class="btn btn-default formBackBtn">Back</a>
					</div>
					<div class="panel-body">
						<div class="noticeCandi">
							<form method="post" action="{{url('deo/addPolStationSubmit') }}">
							<input type="hidden" name="_token" value="<?= csrf_token(); ?>">

								<div class="form-group{{ $errors->has('psDistrict') ? ' has-error' : '' }}">
									<label for="psDistrict" class="form-label">District</label>
									<input name="psDistrict" value="{{ $distDetail->dist_name }}" type="text" id="psDistrict" class="form-control" readonly />
									@if ($errors->has('psDistrict'))
										<span class="help-block">
											<strong>{{ $errors->first('psDistrict') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('psConstituency') ? ' has-error' : '' }}">
									<label for="psConstituency" class="form-label">Constituency</label>
									<select name="psConstituency" class="form-control">
										<option value="">Select Constituency</option>
										@foreach($consDetail as $consDetails)
											<?php  $consCode=eci_encrypt($consDetails->cons_code); ?>
											<option value="<?php echo $consCode; ?>">{{ $consDetails->cons_name }}</option>
										@endforeach
									</select>
									@if ($errors->has('psConstituency'))
										<span class="help-block">
											<strong>{{ $errors->first('psConstituency') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('psBoothNum') ? ' has-error' : '' }}">
									<label for="psBoothNum" class="form-label">Booth Number</label>
									<input onkeypress="return isNumber(event)" name="psBoothNum" type="text" id="psBoothNum" class="form-control" placeholder="Booth Number" value="{{ old('psBoothNum') }}"/>
									@if ($errors->has('psBoothNum'))
										<span class="help-block">
											<strong>{{ $errors->first('psBoothNum') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('psLocality') ? ' has-error' : '' }}">
									<label for="psLocality" class="form-label">Locality</label>
									<input name="psLocality" type="text" id="psLocality" class="form-control" placeholder="Locality" value="{{ old('psLocality') }}" />
									@if ($errors->has('psLocality'))
										<span class="help-block">
											<strong>{{ $errors->first('psLocality') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('psPollBuilding') ? ' has-error' : '' }}">
									<label for="psPollBuilding" class="form-label">Poll Building</label>
									<textarea rows="3" cols="30" name="psPollBuilding" id="psPollBuilding" class="form-control">{{ old('psPollBuilding') }}</textarea>
									@if ($errors->has('psPollBuilding'))
										<span class="help-block">
											<strong>{{ $errors->first('psPollBuilding') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('psAreaPollStation') ? ' has-error' : '' }}">
									<label for="psAreaPollStation" class="form-label">Area</label>
									<input type="text" name="psAreaPollStation" id="psAreaPollStation" class="form-control" value="{{ old('psAreaPollStation') }}">
									@if ($errors->has('psAreaPollStation'))
										<span class="help-block">
											<strong>{{ $errors->first('psAreaPollStation') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('psLatPollStation') ? ' has-error' : '' }}">
									<label for="psLatPollStation" class="form-label">Polling Station Latitude</label>
									<input name="psLatPollStation" type="text" id="psLatPollStation" class="form-control" placeholder="Latitude" value="{{ old('psLatPollStation') }}" />
									@if ($errors->has('psLatPollStation'))
										<span class="help-block">
											<strong>{{ $errors->first('psLatPollStation') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('psLongPollStation') ? ' has-error' : '' }}">
									<label for="psLongPollStation" class="form-label">Polling Station Longitude</label>
									<input name="psLongPollStation" type="text" id="psLongPollStation" class="form-control" placeholder="Latitude" value="{{ old('psLongPollStation') }}" />
									@if ($errors->has('psLongPollStation'))
										<span class="help-block">
											<strong>{{ $errors->first('psLongPollStation') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('psSepEnterExit') ? ' has-error' : '' }}">
									<label for="psSepEnterExit" class="form-label">Separate Entrance and Exit</label>
									<select name="psSepEnterExit" class="form-control">
										<option value="YES" selected>Yes</option>
										<option value="NO">No</option>
									</select>
									@if ($errors->has('psSepEnterExit'))
										<span class="help-block">
											<strong>{{ $errors->first('psSepEnterExit') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('psPollingArea') ? ' has-error' : '' }}">
									<label for="psPollingArea" class="form-label">Area of Polling Station</label>
									<textarea rows="3" cols="30" name="psPollingArea" id="psPollingArea" class="form-control">{{ old('psPollingArea') }}</textarea>
									@if ($errors->has('psPollingArea'))
										<span class="help-block">
											<strong>{{ $errors->first('psPollingArea') }}</strong>
										</span>
									@endif
								</div> 

								<div class="form-group{{ $errors->has('psVotersType') ? ' has-error' : '' }}">
									<label for="psVotersType" class="form-label">Whether For</label>
									<select name="psVotersType" class="form-control">
										<option value="ALL VOTERS" selected>All Voters</option>
										<option value="ONLY MEN">Men Only</option>
										<option value="ONLY WOMEN">Women Only</option>
									</select>
									@if ($errors->has('psVotersType'))
										<span class="help-block">
											<strong>{{ $errors->first('psVotersType') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('psTotalVoters') ? ' has-error' : '' }}">
									<label for="psTotalVoters" class="form-label">Total No. Of Voters</label>
									<input onkeypress="return isNumber(event)" name="psTotalVoters" type="text" id="psTotalVoters" class="form-control" placeholder="No. Of Voters" value="{{ old('psTotalVoters') }}" />
									@if ($errors->has('psTotalVoters'))
										<span class="help-block">
											<strong>{{ $errors->first('psTotalVoters') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('psMaxDistence') ? ' has-error' : '' }}">
									<label for="psMaxDistence" class="form-label">Maximum Distence</label>
									<input name="psMaxDistence" type="text" id="psMaxDistence" class="form-control" placeholder="Maximum Distence" value="{{ old('psMaxDistence') }}" />
									@if ($errors->has('psMaxDistence'))
										<span class="help-block">
											<strong>{{ $errors->first('psMaxDistence') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('psRemarks') ? ' has-error' : '' }}">
									<label for="psRemarks" class="form-label">Remarks</label>
									<textarea rows="3" cols="30" name="psRemarks" id="psRemarks" class="form-control">{{ old('psMaxDistence') }}</textarea>
									@if ($errors->has('psRemarks'))
										<span class="help-block">
											<strong>{{ $errors->first('psRemarks') }}</strong>
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