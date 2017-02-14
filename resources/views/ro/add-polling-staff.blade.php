@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
	<script type="text/javascript">
		$(function() 
		{
			$('#formDiv2').hide(); 
			$('#formDiv3').hide(); 
			$('#pollingFormname').change(function()
			{
				if($('#pollingFormname').val() == '2') 
				{
					$('#formDiv1').hide(); 
					$('#formDiv2').show(); 
					$('#formDiv3').hide(); 
				}
				else if($('#pollingFormname').val() == '3') 
				{
					$('#formDiv1').hide(); 
					$('#formDiv2').hide(); 
					$('#formDiv3').show(); 
				}
				else {
					$('#formDiv1').show(); 
					$('#formDiv2').hide(); 
					$('#formDiv3').hide(); 
				} 
			});
		});
	</script>
	<div class="container-widget">
		<!--<div class="row">
			<div class="col-md-12">					
				<div class="searchBar">
					<form method="post" action="{{url('ro/selectrandomizationForm') }}">
						<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
						Add User in : <select id="pollingFormname" name="pollingFormname" class="form-control">
							<option value="">Select Randomization</option>
							<option value="1" selected="selected">First Randomization</option>
							<option value="2">Second Randomization</option>
							<option value="3">Third Randomization</option>
						</select>
					</form>
				</div>
			</div>
		</div> -->
		
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					<div class="panel-title pageTitle titleBtn text-center">
						<span>Add Polling Staff</span>
					</div>
					<div class="panel-body">
						<div class="noticeCandi">
							<div class="form-group">
								<label for="pollingFormname" class="form-label">Upload Randomization</label>
								<select id="pollingFormname" name="pollingFormname" class="form-control">
									<option value="">Select Randomization</option>
									<option value="1" selected="selected">First</option>
									<option value="2">Second</option>
									<option value="3">Third</option>
								</select>
							</div>
						</div>
						<div class="noticeCandi" id="formDiv1">
							<a href="1" style="float:right;">Sample data file format</a>
							<form enctype="multipart/form-data" method="post" id="uploadPollingstaffexcel" action="{{url('ro/addPollingstaffexcel') }}">
								<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
								<div class="form-group{{ $errors->has('addPollingstaff') ? ' has-error' : '' }}">
									<label class="form-label">Upload File</label>
									<input name="addPollingstaff" type="file" id="addPollingstaff" class="form-control" required />
									@if ($errors->has('addPollingstaff'))
										<span class="help-block">
											<strong>{{ $errors->first('addPollingstaff') }}</strong>
										</span>
									@endif
								</div>
								<button type="submit" class="btn btn-default">UPLOAD CSV</button> 
							</form>
						</div>
						
						<div class="noticeCandi" id="formDiv2">
							<a href="2" style="float:right;">Sample data file format</a>
							<form enctype="multipart/form-data" method="post" id="uploadPollingstaffexcel2" action="{{url('ro/addPollingstaffexcel2') }}" >
								<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
								<div class="form-group{{ $errors->has('addPollingstaffexcel2') ? ' has-error' : '' }}">
									<label for="addPollingstaffexcel2" class="form-label">Upload File</label>
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
						
						<div class="noticeCandi" id="formDiv3">
							<a href="3" style="float:right;">Sample data file format</a>
							<form enctype="multipart/form-data" method="post" id="uploadPollingstaffexcel3" action="{{url('ro/addPollingstaffexcel3') }}" >
								<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
								<div class="form-group{{ $errors->has('addPollingstaffexcel3') ? ' has-error' : '' }}">
									<label for="addPollingstaffexcel3" class="form-label">Upload File</label>
									<input name="addPollingstaffexcel3" type="file" id="addPollingstaffexcel3" class="form-control" required />
									@if ($errors->has('addPollingstaffexcel3'))
										<span class="help-block">
											<strong>{{ $errors->first('addPollingstaffexcel3') }}</strong>
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
		$("#uploadPollingstaffexcel").validate({
			rules: {
		        addPollingstaff: {
		            required: true, 
		            extension: "csv"
		        }
		    },
		    messages: {
		        addPollingstaff: {
		            extension: 'Please upload a csv file!' 
		        }
		    }
		});
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
			$("#uploadPollingstaffexcel3").validate({
			rules: {
		        addPollingstaffexcel3: {
		            required: true, 
		            extension: "csv"
		        }
		    },
		    messages: {
		        addPollingstaffexcel3: {
		            extension: 'Please upload a csv file!' 
		        }
		    }
		});
	});
</script>
	<!-- END CONTAINER -->
@endsection

