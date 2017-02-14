@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
    <?php $aws_s3_files_url = Config::get('constants.AWS_FILES_URL');?>
	<div class="container-widget">  
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					@if(Session::has('prePollMsz'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('prePollMsz') }}</p>
					@endif
					<div class="panel-title pageTitle text-center">
						Pre-Poll Arrangement
					</div>
					<div class="panel-body">
						<div class="noticeCandi">
							<form enctype="multipart/form-data" id="addprepoll" method="post" action="{{url('ro/prePollSub') }}" >
							<input type="hidden" name="_token" value="<?= csrf_token(); ?>">

								<div class="form-group{{ $errors->has('sectorPlan') ? ' has-error' : '' }}">
									<label class="form-label">Sectoral Plan</label>
									<input type="file" name="sectorPlan" class="form-control">
									@if ($errors->has('sectorPlan'))
										<span class="help-block">
											<strong>{{ $errors->first('sectorPlan') }}</strong>
										</span>
									@endif
								
									<?php
									$countSec=count($secPlan);
									if($countSec>0){
										foreach ($secPlan as $secPlans) {
										$planNameSec=$secPlans->doc_name;
										?>
											<div class="text-right">
												
												<a href="{{$aws_s3_files_url.$planNameSec}}">Download Sectoral Plan</a>
											</div>
										<?php
										}
									}
									?>
								</div>
								<div class="form-group{{ $errors->has('transRoutePlan') ? ' has-error' : '' }}">
									<label class="form-label">Transportation Route Plan</label>
									<input type="file" name="transRoutePlan" class="form-control">
									@if ($errors->has('transRoutePlan'))
										<span class="help-block">
											<strong>{{ $errors->first('transRoutePlan') }}</strong>
										</span>
									@endif
								
									<?php
									$countTrns=count($transPlan);
									if($countTrns>0){
										foreach ($transPlan as $transPlans) {
										$planNameTrans=$transPlans->doc_name;
										?>
											<div class="text-right">
											<a href="{{ $aws_s3_files_url.$planNameTrans }}">Download Transportation Route Plan</a>
											</div>
										<?php
										}
									}
									?>
								</div>

								<div class="form-group{{ $errors->has('consMap') ? ' has-error' : '' }}">
									<label class="form-label">Constituency Map</label>
									<input type="file" name="consMap" class="form-control">
									@if ($errors->has('consMap'))
										<span class="help-block">
											<strong>{{ $errors->first('consMap') }}</strong>
										</span>
									@endif
								
									<?php
									$countTrns=count($consMap);
									if($countTrns>0){
										foreach ($consMap as $consMaps) {
										$planNameCons=$consMaps->doc_name;
										?>
											<div class="text-right">
											<a href="{{ $aws_s3_files_url.$planNameCons}}">Download Constituency Map</a>
											</div>
										<?php
										}
									}
									?>
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
	$(document).ready(function(){
		$("#addprepoll").validate({
			rules: {
		        sectorPlan: {
		            extension: "pdf"
		        },
		        transRoutePlan: {
		            extension: "csv,xls,xlsx"
		        },
		        consMap: {
		            extension: "pdf"
		        }
		    },
		    messages: {
		        sectorPlan: {
		            extension: 'Please upload a pdf file!' 
		        },
		        transRoutePlan: {
		            extension: 'Please upload a csv,xls,xlsx file!' 
		        },
		        consMap: {
		            extension: 'Please upload a pdf file!' 
		        }
		    }
		});
	});
</script>
	<!-- END CONTAINER -->
@endsection

