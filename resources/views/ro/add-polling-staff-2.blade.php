@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">					
				<div class="searchBar">
					<form method="post" action="{{url('ro/selectrandomizationForm') }}">
						<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
						<select name="pollingFormname" class="form-control">
							<option value="">Select Randomization</option>
							<option value="1">First Randomization</option>
							<option value="2" selected="selected">Second Randomization</option>
							<option value="3">Third Randomization</option>
						</select>
						<input type="submit" value="Add User" class="btn btn-default" />
					</form>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget">
					<div class="panel-title pageTitle titleBtn clearfix">
						<span>Add Polling Staff 2</span>
						<!--<div class="panel-btn"><a href="javascript:void(0);" class="btn btn-default">Generate Report</a></div> -->
					</div>
					<div class="panel-body">
						<div class="noticeCandi">
							<form enctype="multipart/form-data" method="post" action="{{url('ro/addPollingstaffexcel2') }}" id="uploadPollingstaffexcel2" >
								<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
								<div class="form-group{{ $errors->has('addPollingstaffexcel2') ? ' has-error' : '' }}">
									<label for="addPollingstaffexcel2" class="form-label">Excel Sheet</label>
									<input name="addPollingstaffexcel2" type="file" id="addPollingstaffexcel2" class="form-control" required />
									@if ($errors->has('addPollingstaffexcel2'))
										<span class="help-block">
											<strong>{{ $errors->first('addPollingstaffexcel2') }}</strong>
										</span>
									@endif
								</div>
								<button type="submit" class="btn btn-default">UPLOAD CSV</button> 
							</form>
						</div>
					</div>
				</div>
			</div>		
		</div>
	</div>
<script type="text/javascript">
	$(document).ready(function(){
		$("#uploadPollingstaffexcel2").validate({
			rules: {
		        addPollingstaffexcel2: {
		            required: true, 
		            extension: "csv"
		        }
		    },
		    messages: {
		        addPollingstaffexcel2: {
		            extension: 'Please upload a csv file!' 
		        }
		    }
		});
	});
</script>
	<!-- END CONTAINER -->
@endsection

