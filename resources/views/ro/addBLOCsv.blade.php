@extends('layouts.main')
@section('content')
	<!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<!-- Nominations -->
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					<div class="panel-title pageTitle titleBtn text-center">
						<span>Add BLO</span>
					</div>
					<div class="panel-body">
						<div class="noticeCandi">

							<form enctype="multipart/form-data" method="post" action="{{url('ro/bloCsvSub') }}" id="addsupcsv">
							<input type="hidden" name="_token" value="<?= csrf_token(); ?>">

								<div class="form-group{{ $errors->has('filename') ? ' has-error' : '' }}">
									<label class="form-label">Upload CSV</label>
									<input type="file" name="filename" class="form-control">
									@if ($errors->has('filename'))
										<span class="help-block">
											<strong>{{ $errors->first('filename') }}</strong>
										</span>
									@endif									
								</div>
								
								<button type="submit" class="btn btn-default">Submit</button>
								<div class="text-center link-button">
									<a href="{{ URL::asset('files/addBlo.csv') }}">Download Sample</a>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<!-- End Nominations -->
		
		</div>  
	</div>
<script type="text/javascript">
	$(document).ready(function(){
		$("#addsupcsv").validate({
			rules: {
		        filename: {
		            required: true, 
		            extension: "csv"
		        }
		    },
		    messages: {
		        filename: {
		            extension: 'Please upload a csv file!' 
		        }
		    }
		});
	});
</script>
	<!-- END CONTAINER -->
@endsection

