@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
	<div class="container-widget">  
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					@if(Session::has('addPatwari'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('addPatwari') }}</p>
					@endif
					<div class="panel-title pageTitle titleBtn text-center">
						<span>Add Patwari</span>
					</div>
					<div class="panel-body">
						<div class="noticeCandi">
							<form enctype="multipart/form-data" method="post" action="{{url('deo/add-patwari-csv-sub') }}" id="addPatwariCsv">
							<input type="hidden" name="_token" value="<?= csrf_token(); ?>">


								<div class="form-group{{ $errors->has('patwariSheet') ? ' has-error' : '' }}">
									<label class="form-label">Import CSV</label>
									<input type="file" name="patwariSheet" class="form-control" placeholder="Import CSV" />
									@if ($errors->has('patwariSheet'))
										<span class="help-block">
											<strong>{{ $errors->first('patwariSheet') }}</strong>
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
	<script type="text/javascript">
		$(document).ready(function(){
			$("#addPatwariCsv").validate({
				rules: {
					patwariSheet: {
						required: true, 
						extension: "csv"
					}
				},
				messages: {
					patwariSheet: {
						extension: 'Please upload a csv file!' 
					}
				}
			});
		});
	</script>
@endsection

