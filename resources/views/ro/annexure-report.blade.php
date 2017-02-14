@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
	<div class="container-widget">  
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					@if(Session::has('anexMessage'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('anexMessage') }}</p>
					@endif
					<div class="panel-title pageTitle text-center">Annexure Report</div>
					<div class="panel-body">
						<div class="noticeCandi">


							<form enctype="multipart/form-data" method="post" action="{{url('ro/annexureReportSub') }}">
							<input type="hidden" name="_token" value="<?= csrf_token(); ?>">


								<div class="form-group{{ $errors->has('annexure') ? ' has-error' : '' }}">
									<label class="form-label">Annexure Report</label>
									<input type="file" name="annexure" class="form-control" />
									<?php
									if(!empty($annexureReport)){
										$reportName=$annexureReport->doc_name;
									?>
										<div class="text-center link-button">
											<a href="{{ URL::asset('files/'.$reportName) }}">Download Report</a>
										</div>
									<?php
									}
									?>
								</div>

								@if ($errors->has('annexure'))
									<span class="help-block">
										<strong>{{ $errors->first('annexure') }}</strong>
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
	<!-- END CONTAINER -->
@endsection