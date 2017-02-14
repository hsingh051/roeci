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
							<option value="2">Second Randomization</option>
							<option value="3" selected="selected">Third Randomization</option>
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
						<span>Add Polling Staff 3</span>
						<!--<div class="panel-btn"><a href="javascript:void(0);" class="btn btn-default">Generate Report</a></div> -->
					</div>
					<div class="panel-body">
						<div class="noticeCandi">
							<form enctype="multipart/form-data" method="post" action="{{url('ro/addPollingstaffexcel3') }}" >
								<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
								<div class="form-group{{ $errors->has('addPollingstaffexcel3') ? ' has-error' : '' }}">
									<label for="addPollingstaffexcel3" class="form-label">Excel Sheet</label>
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
	<!-- END CONTAINER -->
@endsection

