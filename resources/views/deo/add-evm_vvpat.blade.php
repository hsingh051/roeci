@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
	<?php 
		if(Session::get('selectRand')==1){
			$selectPoll="1";
		}elseif(Session::get('selectRand')==2) {
			$selectPoll="2";
		}else {
			$selectPoll="3";
		}
		Session::forget('selectRand');
	?>

	<script type="text/javascript">
		$(function() 
		{ 
			$('#pollingFormname').change(function()
			{
				if($('#pollingFormname').val() == '2') 
				{
					$('#formDiv1').hide(); 
					$('#formDiv2').show(); 
				}
				else {
					$('#formDiv1').show(); 
					$('#formDiv2').hide();
				} 
			});
		});

		$(document).ready(function(){
			$("#firstRand").validate({
				rules: {
					firstRandomization: {
						required: true, 
						extension: "csv"
					}
				},
				messages: {
					firstRandomization: {
						extension: 'Please upload a csv file!' 
					}
				}
			});

			$("#secondRand").validate({
				rules: {
					secondRandomization: {
						required: true, 
						extension: "csv"
					}
				},
				messages: {
					secondRandomization: {
						extension: 'Please upload a csv file!' 
					}
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
						<span>Upload EVM-VVPAT Randomization</span>
						<a href="{{ url('/deo/evm-vvpat') }}" class="btn btn-default formBackBtn">Back</a>
					</div>
					<div class="panel-body">
						<div class="noticeCandi">
							<div class="form-group">
								<label for="pollingFormname" class="form-label">Upload Randomization</label>
								<select id="pollingFormname" name="pollingFormname" class="form-control">
									<option value="1" <?php if($selectPoll==1){ echo "selected"; } ?>>First</option>
									<option value="2" <?php if($selectPoll==2){ echo "selected"; } ?>>Second</option>
								</select>
							</div>
						</div>

						<div <?php if($selectPoll==2){ echo "style='display:none'"; } ?> class="noticeCandi" id="formDiv1">
							<form enctype="multipart/form-data" method="post" action="{{url('deo/randomFirstSub') }}" id="firstRand">
							<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
							<input type="hidden" name="selectRand" value="1">

								<div class="form-group{{ $errors->has('firstRandomization') ? ' has-error' : '' }}">
									<label class="form-label">Upload File1</label>
									<input name="firstRandomization" type="file" id="firstRandomization" class="form-control" />
									@if ($errors->has('firstRandomization'))
										<span class="help-block">
											<strong>{{ $errors->first('firstRandomization') }}</strong>
										</span>
									@endif
								</div>
								<button type="submit" class="btn btn-default">UPLOAD CSV</button>

								<div class="text-center link-button">
									<a href="{{URL('/')}}/CsvSamples/AddFirstEvm.csv">Download Sample</a>
								</div>
							</form>
						</div>
						
						<div <?php if($selectPoll==1){ echo "style='display:none'"; } if($selectPoll==3){ echo "style='display:none'"; } ?> class="noticeCandi" id="formDiv2">
							<form enctype="multipart/form-data" method="post" action="{{url('deo/randomSecondSub') }}" id="secondRand">
							<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
							<input type="hidden" name="selectRand" value="2">

								<div class="form-group{{ $errors->has('secondRandomization') ? ' has-error' : '' }}">
									<label for="secondRandomization" class="form-label">Upload File2</label>
									<input name="secondRandomization" type="file" id="secondRandomization" class="form-control" />
									@if ($errors->has('secondRandomization'))
										<span class="help-block">
											<strong>{{ $errors->first('secondRandomization') }}</strong>
										</span>
									@endif
								</div>
								<button type="submit" class="btn btn-default">UPLOAD CSV</button>
								<div class="text-center link-button">
									<a href="{{URL('/')}}/CsvSamples/AddSecondEvm.csv">Download Sample</a>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>		
		</div>
	</div>
	<!-- END CONTAINER -->
@endsection

