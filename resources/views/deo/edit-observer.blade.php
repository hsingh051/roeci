@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
	<div class="container-widget">  
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					<div class="panel-title pageTitle titleBtn text-center">
						<span>Edit Observer</span>
						<a href="{{URL::previous()}}" class="formBackBtn btn btn-default">Back</a>
					</div>
					<div class="panel-body">
						<?php
							$obsType=$editObs->type;
							$obsPic=$editObs->profile_image;

							$obsuid=$editObs->uid;
							$obsuidEnc=eci_encrypt($obsuid)


						?>
						<div class="noticeCandi">
							<form enctype="multipart/form-data" method="post" action="{{url('deo/updateObserver') }}" >
							<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
							<input type="hidden" name="uidHide" value="<?php echo $obsuidEnc; ?>">

								<div class="form-group{{ $errors->has('obsNameEdit') ? ' has-error' : '' }}">
									<label class="form-label">Observer Name</label>
									<input type="text" name="obsNameEdit" value="{{ $editObs->name }}" class="form-control" placeholder="Observer Name" required/>
									@if ($errors->has('obsNameEdit'))
										<span class="help-block">
											<strong>{{ $errors->first('obsNameEdit') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('obsEmailEdit') ? ' has-error' : '' }}">
									<label class="form-label">Email Address</label>
									<input type="text" value="{{ $editObs->email }}" name="obsEmailEdit" class="form-control" placeholder="Email Address" required/>
									@if ($errors->has('obsEmailEdit'))
										<span class="help-block">
											<strong>{{ $errors->first('obsEmailEdit') }}</strong>
										</span>
									@endif
								</div>
								<div class="form-group{{ $errors->has('obsPhoneEdit') ? ' has-error' : '' }}">
									<label class="form-label">Mobile Number</label>
									<input type="text" value="{{ $editObs->phone }}" onkeypress="return isNumber(event)" name="obsPhoneEdit" class="form-control" placeholder="Mobile Number" required/>
									@if ($errors->has('obsPhoneEdit'))
										<span class="help-block">
											<strong>{{ $errors->first('obsPhoneEdit') }}</strong>
										</span>
									@endif
								</div>
								<div class="form-group{{ $errors->has('obsPicNew') ? ' has-error' : '' }}">
									<label class="form-label">Image</label>
									<?php if($obsPic!==""){ ?>

										<div id="oldImgMain" class="removePic">
											<img src="{{ URL::asset('images/observer/'.$obsPic)}}" />
											<div id="crossImg" class="rIcons"><i class="fa fa-times"></i></div>	
										</div>
										<input id="oldImgId" value="<?php echo $obsPic; ?>" name="obsPicOld" type="hidden" class="form-control"/>

										<div id="newImgMain" style="display: none;">
											<input name="obsPicNew" type="file" class="form-control"/>
										</div>
										@if ($errors->has('obsPicNew'))
											<span class="help-block">
												<strong>{{ $errors->first('obsPicNew') }}</strong>
											</span>
										@endif

									<?php } else { ?>

										<div id="newImgMain">
											<input name="obsPicNew" type="file" class="form-control"/>
										</div>
										@if ($errors->has('obsPicNew'))
											<span class="help-block">
												<strong>{{ $errors->first('obsPicNew') }}</strong>
											</span>
										@endif

									<?php }?>
								</div>
								<div class="form-group{{ $errors->has('obAddressEdit') ? ' has-error' : '' }}">
									<label class="form-label">Address</label>
									<textarea name="obAddressEdit" class="form-control" placeholder="Address" required>{{ $editObs->address }}</textarea>
									@if ($errors->has('obAddressEdit'))
										<span class="help-block">
											<strong>{{ $errors->first('obAddressEdit') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('obTypeEdit') ? ' has-error' : '' }}">
									<label class="form-label">Observer Type</label>
									<select name="obTypeEdit" type="file" class="form-control" required/>
										<option value="">Select</option>
										<option value="General Observer" <?php if($obsType=="General Observer"){ echo "selected"; } ?>>General Observer</option>
										<option value="Expenditure Observer" <?php if($obsType=="Expenditure Observer"){ echo "selected"; } ?>>Expenditure Observer</option>
										<option value="Police Observer" <?php if($obsType=="Police Observer"){ echo "selected"; } ?>>Police Observer</option>
										<option value="Awareness Oserver" <?php if($obsType=="Awareness Oserver"){ echo "selected"; } ?>>Awareness Oserver</option>
									</select>
									@if ($errors->has('obTypeEdit'))
										<span class="help-block">
											<strong>{{ $errors->first('obTypeEdit') }}</strong>
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
	<script type="text/javascript">
		function isNumber(evt) {
			var charCode = (evt.which) ? evt.which : event.keyCode
			if (charCode != 43 && charCode > 31 && (charCode < 48 || charCode > 57))
			return false;
			return true;
		}
		$(document).ready(function(){
			$("#crossImg").on('click',function(){
	       		$("#newImgMain").show();
				$("#oldImgMain").hide();
				$("#oldImgId").val('');
	        });
	    });
	</script>
	<!-- END CONTAINER -->
@endsection

