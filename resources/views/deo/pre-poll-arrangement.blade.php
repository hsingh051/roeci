@extends('layouts.main')
@section('content')
<?php
$consCodeCheck= (isset($_GET['cons_code']))? $_GET['cons_code'] : "";
$encryptConsCode= (isset($encryptCons))? $encryptCons : "";
$consList= (isset($constituency))? $constituency : "";
?>
    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">
				<div class="searchBar">
					<form class="topSearchForm" enctype="multipart/form-data" method="get" action="{{url('deo/prePollSub') }}">
					<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
						<div class="form-group{{ $errors->has('cons_code') ? ' has-error' : '' }}">
							<select name="cons_code" class="form-control poll_cons_code_ceo">
								<option value="">Select Constituency</option>
									@if($consList)
									@foreach($consList as $constituencies)
										<?php $consCode=eci_encrypt($constituencies->cons_code); ?>
										<option value="{{$consCode}}" <?php if($consCode==$encryptConsCode){ echo "selected"; } ?> >{{ $constituencies->cons_name }}</option>
									@endforeach
									@endif
							</select>
							@if ($errors->has('cons_code'))
							<span class="help-block">
								<strong>{{ $errors->first('cons_code') }}</strong>
							</span>
							@endif
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-default">Submit</button>
						</div>
					</form>
				</div>
			</div>
		</div>  
		<?php if(($consCodeCheck!=="") || ($encryptConsCode!=="")) { ?>
		<div class="row">
			<!-- Nominations -->
			<div class="col-md-12">
				<div class="panel panel-widget">
					<div class="panel-title pageTitle">Pre-Poll Arrangement</div>
					<div class="panel-body">
						<?php if(!empty($prePollSec) && !empty($prePollTans)) { ?>
							<div class="pdfList">
								<ul>
									@if($prePollSec)
										<?php $secPlanName=$prePollSec->doc_name; ?>
										<li>	
											<a href="{{ URL::asset('files/'.$secPlanName) }}">
												<img src="{{ URL::asset('images/pdfIcon.jpg') }}"/>
												<p>Download Sectoral Plan</p>
											</a>
										</li>
									@endif
									@if($prePollTans)
										<?php $transPlanName=$prePollTans->doc_name; ?>
										<li>
											<a href="{{ URL::asset('files/'.$transPlanName) }}">
												<img src="{{ URL::asset('images/pdfIcon.jpg') }}"/>
												<p>Download Transportation Route Plan</p>
											</a>
										</li>	
									@endif
								</ul>
							</div>
						<?php } else { ?>
							<p>Route plan will be uploaded soon.</p>
						<?php } ?>
					</div>
				</div>
			</div>	
		</div>
		<?php  } ?>
	</div>
	<!-- END CONTAINER -->
@endsection