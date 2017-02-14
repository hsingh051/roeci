@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
    <?php
    $state=get_state_id();
    $dist = Auth::user()->dist_code;
    ?>
	<div class="container-widget">  
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					@if(Session::has('boothAwareErr'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('boothAwareErr') }}</p>
					@endif
					<div class="panel-title pageTitle text-center">
						<span>Select Constituency</span>
					</div>
					<div class="panel-body">
						<div class="noticeCandi">
							<form enctype="multipart/form-data" method="get" action="{{url('deo/pol-1List') }}" >
							<input type="hidden" name="_token" value="<?= csrf_token(); ?>">

								<div class="form-group{{ $errors->has('constituency') ? ' has-error' : '' }}">
									<label class="form-label">Constituencies</label>
									<select name="constituency" class="form-control">
										<option value="">Select Constituency</option>
										@foreach($consSelection as $consSelections)
										<?php
											$encConsCode=$consSelections->cons_code;
											$encConsCode=eci_encrypt($consSelections->cons_code)
										?>
										<option value="<?php echo $encConsCode; ?>">{{ $consSelections->cons_name }}</option>
										@endforeach
									</select>
									@if ($errors->has('constituency'))
										<span class="help-block">
											<strong>{{ $errors->first('constituency') }}</strong>
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
	<!-- END CONTAINER -->
@endsection

