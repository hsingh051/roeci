@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
	<div class="container-widget">  
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					@if(Session::has('webCastingErr'))
					<p class="alert alert-danger">{{ Session::get('webCastingErr') }}</p>
					@endif
					@if(Session::has('webCastingSuccess'))
					<p class="alert alert-success">{{ Session::get('webCastingSuccess') }}</p>
					@endif
					<div class="panel-title pageTitle titleBtn text-center">
						<span>Update Web Casting</span>
						<a href="{{URL::previous()}}" class="btn btn-default formBackBtn">Back</a>
					</div>
					<div class="panel-body">
						<div class="noticeCandi">
							<form enctype="multipart/form-data" method="post" action="{{url('deo/webCastingSub') }}" id="addsupcsv">
							<input type="hidden" name="_token" value="<?= csrf_token(); ?>">


								<div class="form-group{{ $errors->has('webCasting') ? ' has-error' : '' }}">
									<label class="form-label">Import CSV</label>
									<input type="file" name="webCasting" class="form-control" placeholder="Import CSV" />
									@if ($errors->has('webCasting'))
										<span class="help-block">
											<strong>{{ $errors->first('webCasting') }}</strong>
										</span>
									@endif
								</div>
								<button type="submit" class="btn btn-default">Submit</button>
								<!-- <div class="text-center link-button">
									<a href="{{ URL::asset('csvSamplePollBoothAware/BAG-SampleCsv.csv') }}">Download Sample</a>
								</div> -->
							</form>
						</div>
					</div>
				</div>
			</div>		
		</div>  
	</div>
	<!-- END CONTAINER -->
	<script type="text/javascript">
		$(document).ready(function(){
			$("#addsupcsv").validate({
				rules: {
					webCasting: {
						required: true, 
						extension: "csv"
					}
				},
				messages: {
					webCasting: {
						extension: 'Please upload a csv file!' 
					}
				}
			});
		});
	</script>
@endsection

