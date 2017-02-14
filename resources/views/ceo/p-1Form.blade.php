@extends('layouts.main')
@section('content')
	<!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<!-- Nominations -->
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					<div class="panel-title pageTitle text-center">
                        <span>P-1 Day</span>
                    </div>
					<div class="panel-body">
						<div class="noticeCandi">
							<form enctype="multipart/form-data" method="get" action="{{url('ceo/p-1Sub') }}">
							<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
								<div class="form-group{{ $errors->has('distCode') ? ' has-error' : '' }}">
									<label class="form-label">Select District</label>
									<select id="district" name="distCode" class="form-control">
										<option value="">Select District</option>
										@foreach($DEOlist as $DEOlists)
										<?php
										$distCode=$DEOlists->dist_code;
										$distEnc=eci_encrypt($distCode);
										?>
										<option value="<?php echo $distEnc; ?>">{{ $DEOlists->dist_name }}</option>
										@endforeach
									</select>
									@if ($errors->has('distCode'))
										<span class="help-block">
											<strong>{{ $errors->first('distCode') }}</strong>
										</span>
									@endif
								</div>
								<div class="form-group{{ $errors->has('consCode') ? ' has-error' : '' }}">
									<label class="form-label">Select Constituency</label>
									<select id="consList" name="consCode" class="form-control">
										<option value="">Select Constituency</option>
									</select>
									@if ($errors->has('consCode'))
										<span class="help-block">
											<strong>{{ $errors->first('consCode') }}</strong>
										</span>
									@endif
								</div>
								<div id="consTest">

								</div>
								<button type="submit" class="btn btn-default">Send</button>
							</form>
						</div>
					</div>
				</div>
			</div>
			<!-- End Nominations -->
		</div>  
	</div>
	<!-- END CONTAINER -->
	<script type="text/javascript">
		$(document).ready(function(){
			$("#district").change(function(){
				$('#consList').empty();
				$('#consList').append('<option value="">Select Constituency</option>');
				var distId=$(this).val();
				var districtID, token, url, data;
				token = $('input[name=_token]').val();
				districtID = distId;
				url = '<?php echo url("/"); ?>/ceo/getCons';
				data = {districtID: districtID};
				$.ajax({
					url: url,
					headers: {'X-CSRF-TOKEN': token},
					data: data,
					type: 'POST',
					datatype: 'JSON',
					success: function (resp) {
						$.each(resp.consDetail, function (key, value) {
						$('#consList').append('<option value='+value.cons_code+'>'+ value.cons_name +'</option>');
						});
					}
				});	
			});
		});
	</script>
@endsection

