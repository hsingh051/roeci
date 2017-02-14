@extends('layouts.main')
@section('content')
	 <!-- START CONTAINER -->
	<div class="container-widget">  
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					@if(Session::has('bidsRepeatErr'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('bidsRepeatErr') }}</p>
					@endif
					@if(Session::has('maxDigitErr'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('maxDigitErr') }}</p>
					@endif
					@if(Session::has('numericErr'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('numericErr') }}</p>
					@endif
					@if(Session::has('requireErr'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('requireErr') }}</p>
					@endif
					<div class="panel-title pageTitle titleBtn text-center">
						<span>Add Polling Stations</span>
						<a href="{{URL::previous()}}" class="btn btn-default formBackBtn">Back</a>
					</div>
					<div class="panel-body">
						<div class="noticeCandi">
							<form enctype="multipart/form-data" method="post" action="{{url('deo/polStationExcelSubmit') }}" id="pollCsvForm">
							<input type="hidden" name="_token" value="<?= csrf_token(); ?>">

								<div class="form-group{{ $errors->has('psExcelPollStation') ? ' has-error' : '' }}">
									<label for="psExcelPollStation" class="form-label">Excel Sheet</label>
									<input name="psExcelPollStation" type="file" id="psExcelPollStation" class="form-control"/>
									@if ($errors->has('psExcelPollStation'))
										<span class="help-block">
											<strong>{{ $errors->first('psExcelPollStation') }}</strong>
										</span>
									@endif
								</div>

								<button type="submit" class="btn btn-default">UPLOAD CSV</button> 
								<div class="text-center link-button">
									<a href="{{ URL::asset('csvSamplePollStation/PollingStationsLudhiana.csv') }}">Download Sample</a>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>		
		</div>  
	</div>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#pollCsvForm").validate({
				rules: {
					psExcelPollStation: {
						required: true, 
						extension: "csv"
					}
				},
				messages: {
					psExcelPollStation: {
						extension: 'Please upload a csv file!' 
					}
				}
			});
		});
	</script>
@endsection

