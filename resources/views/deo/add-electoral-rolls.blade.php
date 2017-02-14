@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
	<div class="container-widget">  
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					@if(Session::has('voterErr'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('voterErr') }}</p>
					@endif
					<div class="panel-title pageTitle titleBtn text-center">
						<span>Add Electoral Rolls</span>
						<a href="{{URL::previous()}}" class="btn btn-default formBackBtn">Back</a>
					</div>
					<div class="panel-body">
						<div class="noticeCandi">
							<form enctype="multipart/form-data" method="post" action="{{url('deo/addElectoralSub') }}" >
							<input type="hidden" name="_token" value="<?= csrf_token(); ?>">

								<div class="form-group{{ $errors->has('electoralRollCsv') ? ' has-error' : '' }}">
									<label class="form-label">Import CSV</label>
									<input type="file" name="electoralRollCsv" class="form-control" />
									@if ($errors->has('electoralRollCsv'))
										<span class="help-block">
											<strong>{{ $errors->first('electoralRollCsv') }}</strong>
										</span>
									@endif
								</div>
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

