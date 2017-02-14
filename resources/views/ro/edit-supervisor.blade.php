@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
	<div class="container-widget">  
		<div class="row" id="editPollStationMain">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
	            	@if(Session::has('supvUpError'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('supvUpError') }}</p>
					@endif
					<div class="panel-title pageTitle titleBtn text-center">
						<span>Edit Sector Officer</span>
						<!-- <a href="{{ url('/ro/supervisor-list') }}" class="btn btn-default formBackBtn">Back</a> -->
					</div>
					<div class="panel-body">
						<div class="noticeCandi">
							<form method="post" action="{{url('ro/upSupervisorSub') }}">
							<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
							<input type="hidden" name="supervisorId" value="{{ $supervisorUID }}">

								<?php
								$svUpUid1=$editSupv->uid;
								$svUpUid=eci_encrypt($svUpUid1)
								?>
								<input type="hidden" name="uidSVup" value="<?php echo $svUpUid; ?>">

								<div class="form-group{{ $errors->has('svNameUp') ? ' has-error' : '' }}">
									<label for="svName" class="form-label">Sector Officer Name</label>
									<input type="text" name="svNameUp" id="svName" class="form-control" placeholder="Sector Officer Name" value="{{ $editSupv->name }}"/>
									@if ($errors->has('svNameUp'))
										<span class="help-block">
											<strong>{{ $errors->first('svNameUp') }}</strong>
										</span>
									@endif
								</div>
								<div class="form-group{{ $errors->has('svPhoneUp') ? ' has-error' : '' }}">
									<label for="svPhone" class="form-label">Phone Number</label>
									<input name="svPhoneUp" type="text" onkeypress="return isNumber(event)" id="svPhone" class="form-control" placeholder="Phone Number" value="{{ $editSupv->phone }}"/>

									<input name="svPhoneHidden" type="hidden" value="{{ $editSupv->phone }}"/>

									@if ($errors->has('svPhoneUp'))
										<span class="help-block">
											<strong>{{ $errors->first('svPhoneUp') }}</strong>
										</span>
									@endif
								</div>
								<div class="form-group{{ $errors->has('svDesigUp') ? ' has-error' : '' }}">
									<label for="svDesignation" class="form-label">Designation</label>
									<input name="svDesigUp" type="text" id="svDesignation" class="form-control" placeholder="Designation" value="{{ $editSupv->designation }}"/>
									@if ($errors->has('svDesigUp'))
										<span class="help-block">
											<strong>{{ $errors->first('svDesigUp') }}</strong>
										</span>
									@endif
								</div>
								<div class="form-group{{ $errors->has('svDptUp') ? ' has-error' : '' }}">
									<label for="svDepartment" class="form-label">Department</label>
									<input type="text" name="svDptUp" id="svDepartment" class="form-control" placeholder="Department" value="{{ $editSupv->organisation }}"/>
									@if ($errors->has('svDptUp'))
										<span class="help-block">
											<strong>{{ $errors->first('svDptUp') }}</strong>
										</span>
									@endif
								</div>

								<div class="clearfix addPStationBtn">
									<a id="addPollingNew" href="javascript:void(0);" class="btn btn-default">Add Polling Station</a>
								</div>
								<table id="example0" class="table table-bordered apsTable">
									<thead>
										<tr>
											<th>Booth ID</th>
											<th>Assigned Polling Station</th>
											<th>ACTIONS</th>
										</tr>
									</thead>
									<tbody class="tableBodyClass">

										<?php $abc=0; ?>
										@foreach($assignedPollStation as $assignedPollStations)
										<?php 
										$pollIdInc=eci_encrypt($assignedPollStations->poll_booth_id); 
										$pollIdIncMd5=md5($assignedPollStations->poll_booth_id);

										?>
										<tr class="trMain<?php echo $pollIdIncMd5; ?>">
											<td>{{ $assignedPollStations->ps_id }}
											<td>{{ $assignedPollStations->poll_building }}
												@if($assignedPollStations->poll_building_detail)
													({{ $assignedPollStations->poll_building_detail }})
												@endif
												
												<input type="hidden" name="svPollStations[]" value="{{ $pollIdInc }}">
											</td>
											<td class="text-center">
												<a href="javascript:void(0);" data-pollBooth="mainPollId<?php echo $pollIdIncMd5; ?>" class="delPoll" data-rel="trMain<?php echo $pollIdIncMd5; ?>">
													<i class="fa fa-times" aria-hidden="true"></i>
												</a>
											</td>
										</tr>
										<?php $abc++; ?>
										@endforeach
										@if ($errors->has('svPollStations'))
										<span class="help-block">
											<strong>{{ $errors->first('svPollStations') }}</strong>
										</span>
										@endif
									</tbody>
								</table>								
								<button type="submit" class="btn btn-default">Update</button>
							</form>
						</div>
					</div>
				</div>
			</div>		
		</div>
		<div id="addPollStationMain" class="container-widget" style="display:none;">  
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-widget heightWidget">
						<div class="panel-title pageTitle titleBtn clearfix">
							<span>Add Polling Station</span>
							<div class="panel-btn">
								<a href="javascript:void(0);" id="addSelectedPolls" class="btn btn-default">Add Selected Polls</a>
								<a href="javascript:void(0);" id="backToForm" class="btn btn-default">Back</a>
							</div>
						</div>
						<div class="panel-body">	
							<div id="addPollStation">
								<table id="example0" class="table table-bordered apsTableView">
									<thead>
										<tr>
											<th></th>
											<th>Booth Number</th>
											<th>Polling Station</th>
										</tr>
									</thead>
									<tfoot>
										<tr>
											<th></th>
											<th>Booth Number</th>
											<th>Polling Station</th>
										</tr>
									</tfoot>
									<tbody>
										<?php $def=0; ?>
										@foreach($pollStation as $pollStations)
										<?php 
										$pollIdIncAll=eci_encrypt($pollStations->poll_booth_id); 
										$pollIdIncAllMd5=md5($pollStations->poll_booth_id);
										?>
										<tr class="trAddMain<?php echo $def; ?>">
											<td>
												<input class="newCheckBox newCheckBox<?php echo $def; ?> mainPollId<?php echo $pollIdIncAllMd5; ?>" id="mainPollId<?php echo $pollIdIncAllMd5; ?>" type="checkbox" name="newPollStation"
												<?php 
												$countAssignedPolls=count($assignedPollStation); 
												if($countAssignedPolls>0){ 
													foreach($assignedPollStation as $assignedPollStations){
											            $polls[]=$assignedPollStations->poll_booth_id;
											        } 
											        $assignedPolls=implode(",",$polls);
													$findPoll = $pollStations->poll_booth_id;
													if (preg_match('/\b' . $findPoll . '\b/', $assignedPolls)) {
														echo "checked";
													}
												}
												?> value="{{ $pollIdIncAll }}" rel="{{ $supervisorUID }}" data-count="<?php echo $def; ?>">

												<input type="hidden" class="psId<?php echo $def; ?>" value="{{ $pollStations->ps_id }}">

												<input type="hidden" class="newPollName<?php echo $def; ?>" value="{{ $pollStations->poll_building }}
												@if($pollStations->poll_building_detail)
													({{ $pollStations->poll_building_detail }})
												@endif">
												<input data-unique="<?php echo $pollIdIncAllMd5; ?>" type="hidden" class="newPollId<?php echo $def; ?>" id="newPollId<?php echo $def; ?>" value="{{ $pollIdIncAll }}">
											</td>
											<td> {{ $pollStations->ps_id }} </td>
											<td>
												{{ $pollStations->poll_building }}
												@if($pollStations->poll_building_detail)
													({{ $pollStations->poll_building_detail }})
												@endif
											</td>
										</tr>
										<?php $def++; ?>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>						  
	</div>
	<!-- END CONTAINER -->
	<script type="text/javascript">
		function isNumber(evt) {
			var charCode = (evt.which) ? evt.which : event.keyCode
			if (charCode != 43 && charCode > 31 && (charCode < 48 || charCode > 57))
			return false;
			return true;
		}

		$(document).ready(function(){
			$(document).on('click', '.delPoll', function () {
				var answer=confirm('Do you want to delete Polling Station?');
				if(answer){
					var pollIdClass=$(this).attr("data-pollBooth");
					$(this).parent().parent().remove();
					$('#'+pollIdClass).attr('checked', false);
				}
				else{
					return false;
				}
			});
			
			$("#addPollingNew").click(function(){
				$("#editPollStationMain").hide();
				$("#addPollStationMain").show();
				$('html,body').animate({scrollTop: 0}, 700);
			});

			$("#backToForm").click(function(){
				$("#editPollStationMain").show();
				$("#addPollStationMain").hide();
			});

			$("#addSelectedPolls").click(function(){
				$("#editPollStationMain").show();
				$("#addPollStationMain").hide();
			});

			$(".newCheckBox").change(function() {
                var ischecked= $(this).is(':checked');
                var countRowG=$(this).attr("data-count");
                if(ischecked){
                	var polid=$(this).val();
                	var supUid=$(this).attr('rel');
                	var countRow=$(this).attr("data-count");
					var pollAjaxID, supAjaxID, token, url, data;
					token = $('input[name=_token]').val();
					pollAjaxID = polid;
					supAjaxID = supUid;
					url = '<?php echo url("/"); ?>/ro/checkAssignedPoll';
					data = {
						pollAjaxID: pollAjaxID,
						supAjaxID: supAjaxID,
					};
					$.ajax({
						url: url,
						headers: {'X-CSRF-TOKEN': token},
						data: data,
						type: 'POST',
						datatype: 'JSON',
						success: function (resp) {
							$.each(resp.repPollRes, function (key, value) {
								var result=value.repPollStatus;
								if(result==1){
									var answer=confirm('This Polling Station is already assigned to other supervisor, Do you want to update.?');
									if(!answer){
										$('.newCheckBox'+countRow).attr('checked', false);
									}else{
										var newPollName=$('.newPollName'+countRow).val();
										var newPollId=$('.newPollId'+countRow).val();
										var psIdNum=$('.psId'+countRow).val();
										var newPollIdMd5=$('.newPollId'+countRow).attr("data-unique");
										$('.tableBodyClass').append('<tr class="trMain'+newPollIdMd5+'"><td>'+psIdNum+'</td><td>'+newPollName+'</td><input type="hidden" name="svPollStations[]" value="'+newPollId+'"><td class="text-center"><a href="javascript:void(0);" data-pollBooth="mainPollId'+newPollIdMd5+'" class="delPoll delPoll'+newPollIdMd5+'" data-rel="trMain'+newPollIdMd5+'"><i class="fa fa-times" aria-hidden="true"></i></a></td></tr>');
									}
								}else{
										var newPollName=$('.newPollName'+countRow).val();
										var newPollId=$('.newPollId'+countRow).val();
										var newPollIdMd5=$('.newPollId'+countRow).attr("data-unique");
										var psIdNum=$('.psId'+countRow).val();
										$('.tableBodyClass').append('<tr class="trMain'+newPollIdMd5+'"><td>'+psIdNum+'</td><td>'+newPollName+'</td><input type="hidden" name="svPollStations[]" value="'+newPollId+'"><td class="text-center"><a href="javascript:void(0);" data-pollBooth="mainPollId'+newPollIdMd5+'" class="delPoll delPoll'+newPollIdMd5+'" data-rel="trMain'+newPollIdMd5+'"><i class="fa fa-times" aria-hidden="true"></i></a></td></tr>');
								}
							});
						}
					});
                }
                else{
                	var remPollIdMd5=$('.newPollId'+countRowG).attr("data-unique");
                	$(".trMain"+remPollIdMd5).remove();
                }
            });
		});
	</script>
@endsection

