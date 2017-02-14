@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
    
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					@if(Session::has('PolicedataSucc'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('PolicedataSucc') }}</p>
					@endif
					<div class="panel-title pageTitle text-center">
						<span>Police Data</span>
					</div>
					<div class="panel-body">
						<div class="voterSlip">
							<form method="post" action="{{url('deo/add-policedata') }}">
								<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
								<div class="form-group mb5 clearfix">
									<div class="Fields4 fields ">
										<label class="form-label"> </label>
									</div>
									<div class="Fields4 fields text-center">
										<label class="form-label">Cumulative disposal from 03.01.2017 including the date of reporting</label>
									</div>
									<div class="Fields4 fields text-center">
										<label class="form-label">New NBW issued during the period</label>
									</div>
									<div class="Fields4 fields text-center">
										<label class="form-label">Pending as on 03.01.2017</label>
									</div>
								</div>
								<?php 
									foreach($policeData as $policeData1)
									{
									}
								?>
								<div class="form-group mb10 clearfix">
									<div class="Fields4 fields text-right">
										<label class="form-label mt8">NBW's</label>
									</div>
									<div class="Fields4 fields">
										<input type="text" name="nbw_total" class="form-control" value="<?php if(isset($policeData1->nbw_total)){ echo $policeData1->nbw_total; } else {?> {{ old('nbw_total') }} <?php } ?>" />
										@if ($errors->has('nbw_total'))
											<span class="help-block">
												<strong>{{ $errors->first('nbw_total') }}</strong>
											</span>
										@endif
									</div>
									<div class="Fields4 fields">
										<input type="text" name="nbw_resolved" class="form-control" value="<?php if(isset($policeData1->nbw_resolved)){ echo $policeData1->nbw_resolved; } else { ?> {{ old('nbw_resolved') }} <?php } ?>" />
										@if ($errors->has('nbw_resolved'))
											<span class="help-block">
												<strong>{{ $errors->first('nbw_resolved') }}</strong>
											</span>
										@endif
									</div>
									<div class="Fields4 fields">
										<input type="text" name="nbw_pending" class="form-control" value="<?php if(isset($policeData1->nbw_pending)){ echo $policeData1->nbw_pending; } else {?> {{ old('nbw_pending') }} <?php } ?>" />
										@if ($errors->has('nbw_pending'))
											<span class="help-block">
												<strong>{{ $errors->first('nbw_pending') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<br><br>
								<div class="form-group mb5 clearfix">
									<div class="Fields4 fields ">
										<label class="form-label"> </label>
									</div>
									<div class="Fields4 fields text-center">
										<label class="form-label">ARMS</label>
									</div>
									<div class="Fields4 fields text-center">
										<label class="form-label">AMMUNITION</label>
									</div>
									<div class="Fields4 fields text-center">
										<label class="form-label">OTHERS</label>
									</div>
								</div>
								<div class="form-group clearfix">
									<div class="Fields4 fields text-right">
										<label class="form-label mt8">Arms & Ammunition</label>
									</div>
									<div class="Fields4 fields">
										<input type="text" name="arm_total" class="form-control" value="<?php if(isset($policeData1->arm_total)){ echo $policeData1->arm_total; } else {?> {{ old('arm_total') }} <?php } ?>" />
										@if ($errors->has('arm_total'))
											<span class="help-block">
												<strong>{{ $errors->first('arm_total') }}</strong>
											</span>
										@endif
									</div>
									<div class="Fields4 fields">
										<input type="text" name="arm_resolved" class="form-control" value="<?php if(isset($policeData1->arm_resolved)){ echo $policeData1->arm_resolved; } else {?> {{ old('arm_resolved') }} <?php } ?>" />
										@if ($errors->has('arm_resolved'))
											<span class="help-block">
												<strong>{{ $errors->first('arm_resolved') }}</strong>
											</span>
										@endif
									</div>
									<div class="Fields4 fields">
										<input type="text" name="arm_pending" class="form-control" value="<?php if(isset($policeData1->arm_pending)){ echo $policeData1->arm_pending; } else {?> {{ old('arm_pending') }} <?php } ?>" />
										@if ($errors->has('arm_pending'))
											<span class="help-block">
												<strong>{{ $errors->first('arm_pending') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<button type="submit" class="btn btn-default"><?php if(isset($policeData1->nbw_pending)){ echo "Update"; } else { echo "Submit"; } ?></button>
							</form>
						</div>
					</div>
				</div>
			</div>		
		</div>
	</div>
@endsection