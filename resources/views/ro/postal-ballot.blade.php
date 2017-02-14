@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
    
	<div class="container-widget">
	<?php if(!empty($postBallot)){ ?>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					@if(Session::has('postBallotSuccess'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('postBallotSuccess') }}</p>
					@endif
					<div class="panel-title pageTitle titleBtn clearfix">
						<div class="panel-btn">
							<?php $encBalId=eci_encrypt($postBallot->ballot_id); ?>
							<a href="{{ url('/ro/editPostBallot') }}/{{$encBalId}}" class="btn btn-default">Edit</a>
							<!-- <a href="{{URL::previous()}}" class="btn btn-default">Back</a> -->
						</div>	
					</div>
					<div class="panel-body">
						<table class="table table-bordered dataTable">
							<thead>
								<tr>
									<th>Type</th>
									<th>Male</th>
									<th>Female</th>
									<th>Total</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Army Voters</td>
									<td>{{ $postBallot->army_voters_male }}</td>
									<td>{{ $postBallot->army_voters_female }}</td>
									<td>
									<?php
										$a=$postBallot->army_voters_male;
										$b=$postBallot->army_voters_female;
										$c=($a+$b);
										echo $c;
									?>
									</td>
								</tr>
								<tr>
									<td>EDC Voters</td>
									<td>{{ $postBallot->edc_voters_male }}</td>
									<td>{{ $postBallot->edc_voters_female }}</td>
									<td>
									<?php
										$d=$postBallot->edc_voters_male;
										$e=$postBallot->edc_voters_female;
										$f=($d+$e);
										echo $f;
									?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	<?php } else { ?>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					@if(Session::has('postBallotSuccess'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('postBallotSuccess') }}</p>
					@endif
					<div class="panel-title pageTitle text-center">
						<span>Add Postal Ballot</span>
					</div>
					<div class="panel-body">
						<div class="noticeCandi">
							<form method="post" action="{{url('ro/addPostalBallot') }}">
								<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
								<div class="form-group">
									<label class="form-label">Army Voters</label>
									<div class="clearfix">
										<div class="form-group{{ $errors->has('armyMaleVoter') ? ' has-error' : '' }} Fields3 fields">
											<input type="text" name="armyMaleVoter" placeholder="Male" class="form-control armyMaleClass commonArmy" value="{{ old('armyMaleVoter') }}" onkeypress="return isNumber(event)"/>
											@if ($errors->has('armyMaleVoter'))
												<span class="help-block">
													<strong>{{ $errors->first('armyMaleVoter') }}</strong>
												</span>
											@endif
										</div>
										<div class="form-group{{ $errors->has('armyFemaleVoter') ? ' has-error' : '' }} Fields3 fields">
											<input type="text" name="armyFemaleVoter" placeholder="Female" class="form-control armyFemaleClass commonArmy" value="{{ old('armyFemaleVoter') }}" onkeypress="return isNumber(event)"/>
											@if ($errors->has('armyFemaleVoter'))
												<span class="help-block">
													<strong>{{ $errors->first('armyFemaleVoter') }}</strong>
												</span>
											@endif
										</div>

										<div class="form-group Fields3 fields">
											<input type="text" name="armyTotal" placeholder="Total" class="form-control armyTotal" readonly/>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="form-label">EDC</label>
									<div class="clearfix">
										<div class="form-group{{ $errors->has('edcMaleVoter') ? ' has-error' : '' }} Fields3 fields">

											<input type="text" name="edcMaleVoter" placeholder="Male" class="form-control edcMaleClass commonEdc" value="{{ old('edcMaleVoter') }}" onkeypress="return isNumber(event)"/>
											@if ($errors->has('edcMaleVoter'))
												<span class="help-block">
													<strong>{{ $errors->first('edcMaleVoter') }}</strong>
												</span>
											@endif
										</div>
										<div class="form-group{{ $errors->has('edcFemaleVoter') ? ' has-error' : '' }} Fields3 fields">

											<input type="text" name="edcFemaleVoter" placeholder="Female" class="form-control edcFemaleClass commonEdc" value="{{ old('edcFemaleVoter') }}" onkeypress="return isNumber(event)"/>
											@if ($errors->has('edcFemaleVoter'))
												<span class="help-block">
													<strong>{{ $errors->first('edcFemaleVoter') }}</strong>
												</span>
											@endif
										</div>

										<div class="form-group Fields3 fields">
											<input type="text" name="edcTotal" placeholder="Total" class="form-control edcTotal" readonly/>
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
	<?php } ?>	  
	</div>
	<!-- END CONTAINER -->
	<script type="text/javascript">
		function isNumber(evt) {
			var charCode = (evt.which) ? evt.which : event.keyCode
			if (charCode != 43 && charCode > 31 && (charCode < 48 || charCode > 57))
			return false;
			return true;
		}
		$(document).ready(function(){
			$(".commonArmy").on('change',function(){
				var armyMale = $('.armyMaleClass').val();
				var armyFemale = $('.armyFemaleClass').val();
				if(armyMale==""){
					armyMale1=0;
				}else{
					armyMale1=armyMale;
				}

				if(armyFemale==""){
					armyFemale1=0;
				}else{
					armyFemale1=armyFemale;
				}
				var totalArmy=parseInt(armyMale1) + parseInt(armyFemale1);
				$('.armyTotal').val(totalArmy);
			});

			$(".commonEdc").on('change',function(){
				var edcMale = $('.edcMaleClass').val();
				var edcFemale = $('.edcFemaleClass').val();
				if(edcMale==""){
					edcMale1=0;
				}else{
					edcMale1=edcMale;
				}
				if(edcFemale==""){
					edcFemale1=0;
				}else{
					edcFemale1=edcFemale;
				}
				var totalEdc=parseInt(edcMale1) + parseInt(edcFemale1);
				$('.edcTotal').val(totalEdc);
			});
		});
	</script>
@endsection

