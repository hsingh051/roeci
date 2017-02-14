@extends('layouts.main')
@section('content')
	<!-- START CONTAINER -->
	<?php
	$uid=$supDetail->uid;
	$uidEnc=eci_encrypt($uid)
	?>
	<div class="container-widget">  
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					@if(Session::has('rootPlanSucc'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('rootPlanSucc') }}</p>
					@endif
					@if(Session::has('rootPlanErr'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('rootPlanErr') }}</p>
					@endif
					<div class="panel-title pageTitle titleBtn text-center">
						<span>Add Route Plan</span>
						<!-- <a href="{{URL::previous()}}" class="btn btn-default formBackBtn">Back</a> -->
					</div>
					<div class="panel-body">
						<div class="noticeCandi">
							<form enctype="multipart/form-data" method="post" action="{{url('ro/routePlanSub') }}" >
							<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
							<input type="hidden" name="uidRootPlan" value="<?php echo $uidEnc; ?>">

								<div class="form-group{{ $errors->has('rootPlanPdf') ? ' has-error' : '' }}">
									<label class="form-label">Upload File</label>
									<input name="rootPlanPdf" type="file" id="rootPlanPdf" class="form-control"/>
									@if ($errors->has('rootPlanPdf'))
										<span class="help-block">
											<strong>{{ $errors->first('rootPlanPdf') }}</strong>
										</span>
									@endif
								</div>
								<button type="submit" class="btn btn-default">Submit</button> 

								<?php
									$planCount=count($addedRootPlan);
									if($planCount>0){
										foreach ($addedRootPlan as $addedRootPlans) {
											$planName=$addedRootPlans->doc_name;
										?>
											<div class="text-center link-button">
												<!-- <a target="_blank"  href=" URL::asset('route_plan/'.$planName)">Download Route Plan</a> -->
												<?php $aws_s3_files_url = Config::get('constants.AWS_FILES_URL');?>
												<a target="_blank"  href="{{ $aws_s3_files_url.$planName }}">Download Route Plan</a>
											</div>
										<?php
										}
									}
								?>
							</form>
						</div>
					</div>
				</div>
			</div>		
		</div>  
	</div>
@endsection

