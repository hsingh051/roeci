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
							<form enctype="multipart/form-data" method="post" action="{{url('ro/polStationExcelSubmit') }}" >
							<input type="hidden" name="_token" value="<?= csrf_token(); ?>">

								<?php $uidEnc=eci_encrypt($svDetails->uid) ?>
								<input type="hidden" name="uidSVexcel" value="<?php echo $uidEnc; ?>">

								<div class="form-group{{ $errors->has('svExcelPollStation') ? ' has-error' : '' }}">
									<label for="svExcelPollStation" class="form-label">Excel Sheet</label>
									<input name="svExcelPollStation" type="file" id="svExcelPollStation" class="form-control"  required/>
									@if ($errors->has('svExcelPollStation'))
										<span class="help-block">
											<strong>{{ $errors->first('svExcelPollStation') }}</strong>
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
@endsection

