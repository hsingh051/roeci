@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
    
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					@if(Session::has('postBallotFail'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('postBallotFail') }}</p>
					@endif
					<div class="panel-title pageTitle text-center">
						<span>Edit Postal Ballot</span>
					</div>
					<div class="panel-body">
						<div class="noticeCandi">
							<form method="post" action="{{url('ro/updatePostalBallot') }}">
								<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
								<input type="hidden" value="{{ $encBalId }}" name="ballotId">

								<div class="form-group">
									<label class="form-label">Army Voters</label>
									<div class="clearfix">
										<div class="form-group{{ $errors->has('armyMaleVoterEdit') ? ' has-error' : '' }} Fields3 fields">
											<input type="text" name="armyMaleVoterEdit" placeholder="Male" class="form-control" value="{{ $postBallotEdit->army_voters_male }}" onkeypress="return isNumber(event)"/>
											@if ($errors->has('armyMaleVoterEdit'))
												<span class="help-block">
													<strong>{{ $errors->first('armyMaleVoterEdit') }}</strong>
												</span>
											@endif
										</div>
										<div class="form-group{{ $errors->has('armyFemaleVoterEdit') ? ' has-error' : '' }} Fields3 fields">
											<input type="text" name="armyFemaleVoterEdit" placeholder="Female" class="form-control" value="{{ $postBallotEdit->army_voters_female }}" onkeypress="return isNumber(event)"/>
											@if ($errors->has('armyFemaleVoterEdit'))
												<span class="help-block">
													<strong>{{ $errors->first('armyFemaleVoterEdit') }}</strong>
												</span>
											@endif
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="form-label">EDC</label>
									<div class="clearfix">
										<div class="form-group{{ $errors->has('edcMaleVoterEdit') ? ' has-error' : '' }} Fields3 fields">

											<input type="text" name="edcMaleVoterEdit" placeholder="Male" class="form-control" value="{{ $postBallotEdit->edc_voters_male }}" onkeypress="return isNumber(event)"/>
											@if ($errors->has('edcMaleVoterEdit'))
												<span class="help-block">
													<strong>{{ $errors->first('edcMaleVoterEdit') }}</strong>
												</span>
											@endif
										</div>
										<div class="form-group{{ $errors->has('edcFemaleVoterEdit') ? ' has-error' : '' }} Fields3 fields">

											<input type="text" name="edcFemaleVoterEdit" placeholder="Female" class="form-control" value="{{ $postBallotEdit->edc_voters_female }}" onkeypress="return isNumber(event)"/>
											@if ($errors->has('edcFemaleVoterEdit'))
												<span class="help-block">
													<strong>{{ $errors->first('edcFemaleVoterEdit') }}</strong>
												</span>
											@endif
										</div>
									</div>
								</div>
								<button type="submit" class="btn btn-default">Submit</button>
							</form>
						</div>
					</div>
				</div>
			</div>		
		</div>  
	</div>
	<!-- END CONTAINER -->
	<script type="text/javascript">
		function isNumber(evt) {
			var charCode = (evt.which) ? evt.which : event.keyCode
			if (charCode != 43 && charCode > 31 && (charCode < 48 || charCode > 57))
			return false;
			return true;
		}
	</script>
@endsection

