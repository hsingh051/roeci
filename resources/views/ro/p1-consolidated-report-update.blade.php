@extends('layouts.main')
@section('content')
<!-- START CONTAINER -->
	<div class="container-widget">  
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget">
					<div class="panel-title pageTitle titleBtn text-center">
						<span>Update Consolidated Report</span>
						<!-- <a href="{{URL::previous()}}" class="btn btn-default formBackBtn">Back</a> -->
					</div>
					<div class="panel-body">
						<div class="noticeCandi">
							<form enctype="multipart/form-data" method="post" action="{{url('ro/updateP1ConsReportSub') }}" >
							<input type="hidden" name="_token" value="<?= csrf_token(); ?>">

								<div class="form-group{{ $errors->has('poleInterruptionUp') ? ' has-error' : '' }}">
									<label class="form-label">Interruption or obstruction of poll due to riots, open violence, natural calamity or any other cause</label>
									<div class="radio radio-inline">
										<input type="radio" id="poleInterruptionUpYes" value="1" name="poleInterruptionUp" <?php if(($upConsReport->interruption)==1){ echo "checked"; } ?>>
										<label for="poleInterruptionUpYes">Yes</label>
									</div>
									<div class="radio radio-inline">
										<input type="radio" id="poleInterruptionUpNo" value="0" name="poleInterruptionUp" <?php if(($upConsReport->interruption)==0){ echo "checked"; } ?>>
										<label for="poleInterruptionUpNo">No</label>
									</div>
									@if ($errors->has('poleInterruptionUp'))
										<span class="help-block">
										<strong>{{ $errors->first('poleInterruptionUp') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('unlawfullEvmUp') ? ' has-error' : '' }}">
									<label class="form-label">Vitiation of the poll by any of the EVMs having been unlawfully taken out of the custody of the Presiding Officer, accidentally or unintentionally lost or destroyed or damaged or tampered with</label>
									<div class="radio radio-inline">
										<input type="radio" id="unlawfullEvmUpYes" value="1" name="unlawfullEvmUp" <?php if(($upConsReport->vitiation_evm_unlawfully)==1){ echo "checked"; } ?>>
										<label for="unlawfullEvmUpYes">Yes</label>
									</div>
									<div class="radio radio-inline">
										<input type="radio" id="unlawfullEvmUpNo" value="0" name="unlawfullEvmUp" <?php if(($upConsReport->vitiation_evm_unlawfully)==0){ echo "checked"; } ?>>
										<label for="unlawfullEvmUpNo">No</label>
									</div>
									@if ($errors->has('unlawfullEvmUp'))
										<span class="help-block">
										<strong>{{ $errors->first('unlawfullEvmUp') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('unlawfullVoterUp') ? ' has-error' : '' }}">
									<label class="form-label">Votes having been unlawfully recorded by any person in the EVMs</label>
									<div class="radio radio-inline">
										<input type="radio" id="unlawfullVoterUpYes" value="1" name="unlawfullVoterUp" <?php if(($upConsReport->votes_unlawfully)==1){ echo "checked"; } ?>>
										<label for="unlawfullVoterUpYes">Yes</label>
									</div>
									<div class="radio radio-inline">
										<input type="radio" id="unlawfullVoterUpNo" value="0" name="unlawfullVoterUp" <?php if(($upConsReport->votes_unlawfully)==0){ echo "checked"; } ?>>
										<label for="unlawfullVoterUpNo">No</label>
									</div>
									@if ($errors->has('unlawfullVoterUp'))
										<span class="help-block">
										<strong>{{ $errors->first('unlawfullVoterUp') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('boothCapturingUp') ? ' has-error' : '' }}">
									<label class="form-label">Booth capturing</label>
									<div class="radio radio-inline">
										<input type="radio" id="boothCapturingUpYes" value="1" name="boothCapturingUp" <?php if(($upConsReport->booth_capturing)==1){ echo "checked"; } ?>>
										<label for="boothCapturingUpYes">Yes</label>
									</div>
									<div class="radio radio-inline">
										<input type="radio" id="boothCapturingUpNo" value="0" name="boothCapturingUp" <?php if(($upConsReport->booth_capturing)==0){ echo "checked"; } ?>>
										<label for="boothCapturingUpNo">No</label>
									</div>
									@if ($errors->has('boothCapturingUp'))
										<span class="help-block">
										<strong>{{ $errors->first('boothCapturingUp') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('seriousCompUp') ? ' has-error' : '' }}">
									<label class="form-label">Serious Complaint</label>
									<div class="radio radio-inline">
										<input type="radio" id="seriousCompUpYes" value="1" name="seriousCompUp" <?php if(($upConsReport->serious_complaint)==1){ echo "checked"; } ?>>
										<label for="seriousCompUpYes">Yes</label>
									</div>
									<div class="radio radio-inline">
										<input type="radio" id="seriousCompUpNo" value="0" name="seriousCompUp" <?php if(($upConsReport->serious_complaint)==0){ echo "checked"; } ?>>
										<label for="seriousCompUpNo">No</label>
									</div>
									@if ($errors->has('seriousCompUp'))
										<span class="help-block">
										<strong>{{ $errors->first('seriousCompUp') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('violenceUp') ? ' has-error' : '' }}">
									<label class="form-label">Violence and breach of law and order</label>
									<div class="radio radio-inline">
										<input type="radio" id="violenceUpYes" value="1" name="violenceUp" <?php if(($upConsReport->violence_law_order)==1){ echo "checked"; } ?>>
										<label for="violenceUpYes">Yes</label>
									</div>
									<div class="radio radio-inline">
										<input type="radio" id="violenceUpNo" value="0" name="violenceUp" <?php if(($upConsReport->violence_law_order)==0){ echo "checked"; } ?>>
										<label for="violenceUpNo">No</label>
									</div>
									@if ($errors->has('violenceUp'))
										<span class="help-block">
										<strong>{{ $errors->first('violenceUp') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('mistakeUp') ? ' has-error' : '' }}">
									<label class="form-label">Mistake and irregularities, which have a bearing on the elections</label>
									<div class="radio radio-inline">
										<input type="radio" id="mistakeUpYes" value="1" name="mistakeUp" <?php if(($upConsReport->mistake_irregularities)==1){ echo "checked"; } ?>>
										<label for="mistakeUpYes">Yes</label>
									</div>
									<div class="radio radio-inline">
										<input type="radio" id="mistakeUpNo" value="0" name="mistakeUp" <?php if(($upConsReport->mistake_irregularities)==0){ echo "checked"; } ?>>
										<label for="mistakeUpNo">No</label>
									</div>
									@if ($errors->has('mistakeUp'))
										<span class="help-block">
										<strong>{{ $errors->first('mistakeUp') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('weatherUp') ? ' has-error' : '' }}">
									<label class="form-label" for="weatherUp">Weather Conditions</label>
									<input type="text" value="{{ $upConsReport->weather_conditions }}" name="weatherUp" class="form-control" id="weatherUp" placeholder="Weather conditions" />
									@if ($errors->has('weatherUp'))
										<span class="help-block">
										<strong>{{ $errors->first('weatherUp') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('pollPercentageUp') ? ' has-error' : '' }}">
									<label class="form-label" for="pollPercentageUp">Poll Percentage</label>
									<input type="number" value="{{ $upConsReport->poll_percentage }}" name="pollPercentageUp" class="form-control" id="pollPercentageUp" placeholder="Poll percentage" min="0" max="100" />
									@if ($errors->has('pollPercentageUp'))
										<span class="help-block">
										<strong>{{ $errors->first('pollPercentageUp') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('pre_scrutinyUp') ? ' has-error' : '' }}">
									<label class="form-label">Whether all the diaries of Presiding Officers have been scrutinized and irregularities if any detected</label>
									<div class="radio radio-inline">
										<input type="radio" id="pre_scrutinyUpYes" value="1" name="pre_scrutinyUp" <?php if(($upConsReport->pre_scrutiny)==1){ echo "checked"; } ?>>
										<label for="pre_scrutinyUpYes">Yes</label>
									</div>
									<div class="radio radio-inline">
										<input type="radio" id="pre_scrutinyUpNo" value="0" name="pre_scrutinyUp" <?php if(($upConsReport->pre_scrutiny)==0){ echo "checked"; } ?>>
										<label for="pre_scrutinyUpNo">No</label>
									</div>
									@if ($errors->has('pre_scrutinyUp'))
										<span class="help-block">
										<strong>{{ $errors->first('pre_scrutinyUp') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('recommendations_repollUp') ? ' has-error' : '' }}">
									<label class="form-label">Recommendations regarding repoll / fresh poll, if any</label>
									<div class="radio radio-inline">
										<input type="radio" id="recommendations_repollUpYes" value="1" name="recommendations_repollUp" <?php if(($upConsReport->recommendations_repoll)==1){ echo "checked"; } ?>>
										<label for="recommendations_repollUpYes">Yes</label>
									</div>
									<div class="radio radio-inline">
										<input type="radio" id="recommendations_repollUpNo" value="0" name="recommendations_repollUp" <?php if(($upConsReport->recommendations_repoll)==0){ echo "checked"; } ?>>
										<label for="recommendations_repollUpNo">No</label>
									</div>
									@if ($errors->has('recommendations_repollUp'))
										<span class="help-block">
										<strong>{{ $errors->first('recommendations_repollUp') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group">
									<label class="form-label" for="remarksUp">Any other remarks</label>
									<textarea rows="3" cols="30" class="form-control" name="remarksUp" id="remarksUp" placeholder="Any other remarks">{{ $upConsReport->remarks }}</textarea>
								</div>

								<button type="submit" class="btn btn-default">Update</button>
							</form>
						</div>
					</div>
				</div>
			</div>	
		</div>  
	</div>
	<script type="text/javascript">
		jQuery("#pollPercentageUp").keypress(function (evt) { 
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode != 46 && charCode > 31 && (charCode < 43 || charCode > 57)){
			return false;
		}else{ 
			return true;
		}
		});
	</script>
@endsection