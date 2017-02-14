@extends('layouts.main')
@section('content')
<!-- START CONTAINER -->
	<div class="container-widget">  
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget">
					<div class="panel-title pageTitle titleBtn text-center">
						<span>Consolidated Report</span>
						<!-- <a href="{{URL::previous()}}" class="btn btn-default formBackBtn">Back</a> -->
					</div>
					<div class="panel-body">
						<div class="noticeCandi">
							<form enctype="multipart/form-data" method="post" action="{{url('ro/addP1ConsReportSub') }}" >
							<input type="hidden" name="_token" value="<?= csrf_token(); ?>">

								<div class="form-group{{ $errors->has('poleInterruption') ? ' has-error' : '' }}">
									<label class="form-label">Interruption or obstruction of poll due to riots, open violence, natural calamity or any other cause</label>
									<div class="radio radio-inline">
										<input type="radio" id="poleInterruptionYes" value="1" name="poleInterruption" @if(old('poleInterruption') ==  1) checked="checked" @endif>
										<label for="poleInterruptionYes">Yes</label>
									</div>
									<div class="radio radio-inline">
										<input type="radio" id="poleInterruptionNo" value="0" name="poleInterruption" @if(old('poleInterruption') ==  0) checked="checked" @endif>
										<label for="poleInterruptionNo">No</label>
									</div>
									@if ($errors->has('poleInterruption'))
										<span class="help-block">
										<strong>{{ $errors->first('poleInterruption') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('unlawfullEvm') ? ' has-error' : '' }}">
									<label class="form-label">Vitiation of the poll by any of the EVMs having been unlawfully taken out of the custody of the presiding officer, accidentally or unintentionally lost or destroyed or damaged or tampered with</label>
									<div class="radio radio-inline">
										<input type="radio" id="unlawfullEvmYes" value="1" name="unlawfullEvm" @if(old('unlawfullEvm') ==  1) checked="checked" @endif>
										<label for="unlawfullEvmYes">Yes</label>
									</div>
									<div class="radio radio-inline">
										<input type="radio" id="unlawfullEvmNo" value="0" name="unlawfullEvm" @if(old('unlawfullEvm') ==  0) checked="checked" @endif>
										<label for="unlawfullEvmNo">No</label>
									</div>
									@if ($errors->has('unlawfullEvm'))
										<span class="help-block">
										<strong>{{ $errors->first('unlawfullEvm') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('unlawfullVoter') ? ' has-error' : '' }}">
									<label class="form-label">Votes having been unlawfully recorded by any person in the EVMs</label>
									<div class="radio radio-inline">
										<input type="radio" id="unlawfullVoterYes" value="1" name="unlawfullVoter" @if(old('unlawfullVoter') ==  1) checked="checked" @endif>
										<label for="unlawfullVoterYes">Yes</label>
									</div>
									<div class="radio radio-inline">
										<input type="radio" id="unlawfullVoterNo" value="0" name="unlawfullVoter" @if(old('unlawfullVoter') ==  0) checked="checked" @endif>
										<label for="unlawfullVoterNo">No</label>
									</div>
									@if ($errors->has('unlawfullVoter'))
										<span class="help-block">
										<strong>{{ $errors->first('unlawfullVoter') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('boothCapturing') ? ' has-error' : '' }}">
									<label class="form-label">Booth capturing</label>
									<div class="radio radio-inline">
									   <input type="radio" id="boothCapturingYes" value="1" name="boothCapturing" @if(old('boothCapturing') ==  1) checked="checked" @endif>
									   <label for="boothCapturingYes">Yes</label>
								    </div>
								   <div class="radio radio-inline">
									   <input type="radio" id="boothCapturingNo" value="0" name="boothCapturing" @if(old('boothCapturing') ==  0) checked="checked" @endif>
									   <label for="boothCapturingNo">No</label>
								   </div>
									@if ($errors->has('boothCapturing'))
										<span class="help-block">
											<strong>{{ $errors->first('boothCapturing') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('seriousComp') ? ' has-error' : '' }}">
									<label class="form-label">Serious complaint</label>
									<div class="radio radio-inline">
									   <input type="radio" id="seriousCompYes" value="1" name="seriousComp" @if(old('seriousComp') ==  1) checked="checked" @endif>
									   <label for="seriousCompYes">Yes</label>
								    </div>
								   <div class="radio radio-inline">
									   <input type="radio" id="seriousCompNo" value="0" name="seriousComp" @if(old('seriousComp') ==  0) checked="checked" @endif>
									   <label for="seriousCompNo">No</label>
								   </div>
									@if ($errors->has('seriousComp'))
										<span class="help-block">
											<strong>{{ $errors->first('seriousComp') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('violence') ? ' has-error' : '' }}">
									<label class="form-label">Violence and breach of law and order</label>
									<div class="radio radio-inline">
									   <input type="radio" id="violenceYes" value="1" name="violence" @if(old('violence') ==  1) checked="checked" @endif>
									   <label for="violenceYes">Yes</label>
								    </div>
								   <div class="radio radio-inline">
									   <input type="radio" id="violenceNo" value="0" name="violence" @if(old('violence') ==  0) checked="checked" @endif>
									   <label for="violenceNo">No</label>
								   </div>
									@if ($errors->has('violence'))
										<span class="help-block">
										<strong>{{ $errors->first('violence') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('mistake') ? ' has-error' : '' }}">
									<label class="form-label">Mistake and irregularities, which have a bearing on the elections</label>
									<div class="radio radio-inline">
										<input type="radio" id="mistakeYes" value="1" name="mistake" @if(old('mistake') ==  1) checked="checked" @endif>
										<label for="mistakeYes">Yes</label>
									</div>
									<div class="radio radio-inline">
										<input type="radio" id="mistakeNo" value="0" name="mistake" @if(old('mistake') ==  0) checked="checked" @endif>
										<label for="mistakeNo">No</label>
									</div>
									@if ($errors->has('mistake'))
										<span class="help-block">
										<strong>{{ $errors->first('mistake') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('weather') ? ' has-error' : '' }}">
									<label class="form-label" for="weather">Weather conditions</label>
									<input type="text" name="weather" class="form-control" id="weather" placeholder="Weather conditions" />
									@if ($errors->has('weather'))
										<span class="help-block">
										<strong>{{ $errors->first('weather') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('pollPercentage') ? ' has-error' : '' }}">
									<label class="form-label" for="pollPercentage">Poll percentage</label>
									<input type="text" name="pollPercentage" class="form-control" id="pollPercentage" placeholder="Poll percentage"/>
									@if ($errors->has('pollPercentage'))
										<span class="help-block">
										<strong>{{ $errors->first('pollPercentage') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('pre_scrutiny') ? ' has-error' : '' }}">
									<label class="form-label">Whether all the diaries of Presiding Officers have been scrutinized and irregularities if any detected</label>
									<div class="radio radio-inline">
									   <input type="radio" id="pre_scrutinyYes" value="1" name="pre_scrutiny" @if(old('pre_scrutiny') ==  1) checked="checked" @endif>
									   <label for="pre_scrutinyYes">Yes</label>
								    </div>
								   <div class="radio radio-inline">
									   <input type="radio" id="pre_scrutinyNo" value="0" name="pre_scrutiny" @if(old('pre_scrutiny') ==  0) checked="checked" @endif>
									   <label for="pre_scrutinyNo">No</label>
								   </div>
									@if ($errors->has('pre_scrutiny'))
										<span class="help-block">
										<strong>{{ $errors->first('pre_scrutiny') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('recommendations_repoll') ? ' has-error' : '' }}">
									<label class="form-label">Recommendations regarding repoll / fresh poll, if any</label>
									<div class="radio radio-inline">
									   <input type="radio" id="recommendations_repollYes" value="1" name="recommendations_repoll" @if(old('recommendations_repoll') ==  1) checked="checked" @endif>
									   <label for="recommendations_repollYes">Yes</label>
								    </div>
								   <div class="radio radio-inline">
									   <input type="radio" id="recommendations_repollNo" value="0" name="recommendations_repoll" @if(old('recommendations_repoll') ==  0) checked="checked" @endif>
									   <label for="recommendations_repollNo">No</label>
								   </div>
								   @if ($errors->has('recommendations_repoll'))
										<span class="help-block">
										<strong>{{ $errors->first('recommendations_repoll') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group">
									<label class="form-label" for="remarks">Any other remarks</label>
									<textarea rows="3" cols="30" class="form-control" name="remarks" id="remarks" placeholder="Any other remarks"></textarea>
								</div>

								<button type="submit" class="btn btn-default">Submit</button>
							</form>
						</div>
					</div>
				</div>
			</div>	
		</div>  
	</div>
	<script type="text/javascript">
		jQuery("#pollPercentage").keypress(function (evt) { 
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode != 46 && charCode > 31 && (charCode < 43 || charCode > 57)){
			return false;
		}else{ 
			return true;
		}
		});



		// $('#pollPercentage').on('keydown keyup', function(e){
		// if ($(this).val() > 100 
		// 	&& e.keyCode != 46 // delete
		// 	&& e.keyCode != 8 // backspace
		// ) {
		// e.preventDefault();
		// $(this).val(100);
		// }
	</script>
@endsection