@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
	<div class="container-widget">  
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					<!-- @if(Session::has('boothAwareErr'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('boothAwareErr') }}</p>
					@endif -->
					<div class="panel-title pageTitle titleBtn text-center">
						<span>Add Booth Awareness Group</span>
						<a href="{{ url('/deo/booth-aware-list') }}" class="btn btn-default formBackBtn">Back</a>
					</div>
					<div class="panel-body">
						<div class="noticeCandi">
							<form enctype="multipart/form-data" method="post" action="{{url('deo/boothAwareCsvSub') }}" id="addsupcsv">
							<input type="hidden" name="_token" value="<?= csrf_token(); ?>">


								<div class="form-group{{ $errors->has('boothAwareCsv') ? ' has-error' : '' }}">
									<label class="form-label">Import CSV</label>
									<input type="file" name="boothAwareCsv" class="form-control" placeholder="Import CSV" />
									@if ($errors->has('boothAwareCsv'))
										<span class="help-block">
											<strong>{{ $errors->first('boothAwareCsv') }}</strong>
										</span>
									@endif
								</div>
								<button type="submit" class="btn btn-default">Submit</button>
								<div class="text-center link-button">
									<a href="{{ URL::asset('csvSamplePollBoothAware/BAG-SampleCsv.csv') }}">Download Sample</a>
								</div>
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
					boothAwareCsv: {
						required: true, 
						extension: "csv"
					}
				},
				messages: {
					boothAwareCsv: {
						extension: 'Please upload a csv file!' 
					}
				}
			});
		});
	</script>
@endsection

