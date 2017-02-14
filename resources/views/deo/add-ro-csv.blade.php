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
						<span>Add New RO</span>
						<!-- <a href="{{ url('/deo/ro-list') }}" class="btn btn-default formBackBtn">Back</a> -->
					</div>
					<div class="panel-body">
						<div class="noticeCandi">
							<form enctype="multipart/form-data" method="post" action="{{url('deo/addRoCsvSub') }}" id="addRoCsv" >
							<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
								<div class="form-group{{ $errors->has('roCsvData') ? ' has-error' : '' }}">
									<label class="form-label">Import CSV</label>
									<input type="file" name="roCsvData" class="form-control" placeholder="Import CSV" />
									@if ($errors->has('roCsvData'))
										<span class="help-block">
											<strong>{{ $errors->first('roCsvData') }}</strong>
										</span>
									@endif
								</div>
								<button type="submit" class="btn btn-default">Submit</button>
								<div class="text-center link-button">
									<a href="{{ URL::asset('CsvSamples/AddRoCsv.csv') }}">Download Sample</a>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>		
		</div>  
	</div>
<script type="text/javascript">
	$(document).ready(function(){
		$("#addRoCsv").validate({
			rules: {
		        roCsvData: {
		            required: true, 
		            extension: "csv"
		        }
		    },
		    messages: {
		        roCsvData: {
		            extension: 'Please upload a csv file!' 
		        }
		    }
		});
	});
</script>
	<!-- END CONTAINER -->
@endsection

