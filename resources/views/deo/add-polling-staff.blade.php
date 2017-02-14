@extends('layouts.main')
@section('content')
	<?php 
		if(Session::get('selectPollRand')==1){
			$selectRandomPoll="1";
		}
		elseif(Session::get('selectPollRand')==2) {
			$selectRandomPoll="2";
		}
		elseif(Session::get('selectPollRand')==3) {
			$selectRandomPoll="3";
		}
		else {
			$selectRandomPoll="4";
		}
		Session::forget('selectPollRand');
	?>

    <!-- START CONTAINER -->
	<script type="text/javascript">
		$(function() 
		{
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

		$(document).ready(function(){
			$("#random1").validate({
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

			$("#random2").validate({
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

			$("#random3").validate({
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
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					<div class="panel-title pageTitle titleBtn text-center">
						<span>Add Polling Staff</span>
						<a href="{{ url('/deo/polling-staff') }}" class="btn btn-default formBackBtn">Back</a>
					</div>
					<div class="panel-body">
						<div class="noticeCandi">
							<div class="form-group">
								<label for="pollingFormname" class="form-label">Upload Randomization</label>
								<select id="pollingFormname" name="pollingFormname" class="form-control">
									<option value="1" <?php if($selectRandomPoll==1){echo "selected";} ?>>First</option>
									<option value="2" <?php if($selectRandomPoll==2){echo "selected";} ?>>Second</option>
									<option value="3" <?php if($selectRandomPoll==3){echo "selected";} ?>>Third</option>
								</select>
							</div>
						</div>
						<div <?php if($selectRandomPoll=="2" || $selectRandomPoll=="3"){echo "style='display:none'";} ?> class="noticeCandi" id="formDiv1">
							<a href="{{URL('/')}}/CsvSamples/staff_1.csv" style="float:right;">Sample data file format</a>
							<form enctype="multipart/form-data" method="post" action="{{url('deo/addPollingstaffexcel') }}" id="random1">
								<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
								<input type="hidden" name="selectPollRand" value="1">

								<div class="form-group{{ $errors->has('addPollingstaff') ? ' has-error' : '' }}">
									<label class="form-label">Upload File</label>
									<input name="addPollingstaff" type="file" id="addPollingstaff" class="form-control" />
									@if ($errors->has('addPollingstaff'))
										<span class="help-block">
											<strong>{{ $errors->first('addPollingstaff') }}</strong>
										</span>
									@endif
								</div>
								<button type="submit" class="btn btn-default">UPLOAD CSV</button> 
							</form>
						</div>
						<div <?php if($selectRandomPoll=="1" || $selectRandomPoll=="3" || $selectRandomPoll=="4"){echo "style='display:none'";} ?> class="noticeCandi" id="formDiv2">
							<a href="{{URL('/')}}/CsvSamples/staff_2.csv" style="float:right;">Sample data file format</a>
							<form enctype="multipart/form-data" method="post" action="{{url('deo/addPollingstaffexcel2') }}" id="random2">
								<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
								<input type="hidden" name="selectPollRand" value="2">
								<div class="form-group{{ $errors->has('addPollingstaffexcel2') ? ' has-error' : '' }}">
									<label for="addPollingstaffexcel2" class="form-label">Upload File</label>
									<input name="addPollingstaffexcel2" type="file" id="addPollingstaffexcel2" class="form-control" />
									@if ($errors->has('addPollingstaffexcel2'))
										<span class="help-block">
											<strong>{{ $errors->first('addPollingstaffexcel2') }}</strong>
										</span>
									@endif
								</div>
								<button type="submit" class="btn btn-default">UPLOAD CSV</button> 
							</form>
						</div>
						<div <?php if($selectRandomPoll=="1" || $selectRandomPoll=="2" || $selectRandomPoll=="4"){echo "style='display:none'";} ?> class="noticeCandi" id="formDiv3">
							<a href="{{URL('/')}}/CsvSamples/staff_3.csv" style="float:right;">Sample data file format</a>
							<form enctype="multipart/form-data" method="post" action="{{url('deo/addPollingstaffexcel3') }}" id="random3">
								<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
								<input type="hidden" name="selectPollRand" value="3">
								<div class="form-group{{ $errors->has('addPollingstaffexcel3') ? ' has-error' : '' }}">
									<label for="addPollingstaffexcel3" class="form-label">Upload File</label>
									<input name="addPollingstaffexcel3" type="file" id="addPollingstaffexcel3" class="form-control" />
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

