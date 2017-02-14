@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
    <?php $aws_s3_files_url = Config::get('constants.AWS_FILES_URL');?>
	<div class="container-widget">  
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					@if(Session::has('scrutinyMessage'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('scrutinyMessage') }}</p>
					@endif
					<div class="panel-title pageTitle text-center">Scrutiny Report</div>
					<div class="panel-body">
						<div class="noticeCandi">
							<form enctype="multipart/form-data" method="post" action="{{url('ro/scrutinyReportSub') }}" id="p1scrutiny">
							<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
								<div class="form-group{{ $errors->has('scrutiny') ? ' has-error' : '' }}">
									<label class="form-label">Upload Scrutiny Report</label>
									<input type="file" name="scrutiny" class="form-control" />
									@if ($errors->has('scrutiny'))
										<span class="help-block">
											<strong>{{ $errors->first('scrutiny') }}</strong>
										</span>
									@endif
									<div class="text-center link-button">
										<a href="https://s3.amazonaws.com/eci360-files/ScrutinyReport.xls">Download Sample Scrutiny Report</a>
									</div>

									<?php
									if(!empty($scrutinyReport)){
										$reportName=$scrutinyReport->doc_name;
									?>
									<div class="text-center link-button">
										<a href="{{ $aws_s3_files_url.$reportName }}">Download Report</a>
									</div>
									<?php
									}
									?>
								</div>

								@if ($errors->has('scrutiny'))
									<span class="help-block">
										<strong>{{ $errors->first('scrutiny') }}</strong>
									</span>
								@endif

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
		$("#p1scrutiny").validate({
			rules: {
		        scrutiny: {
		            required: true, 
		            extension: "xls"
		        }
		    },
		    messages: {
		        scrutiny: {
		            extension: 'Please upload a xls file!' 
		        }
		    }
		});
	});
</script>
	<!-- END CONTAINER -->
@endsection