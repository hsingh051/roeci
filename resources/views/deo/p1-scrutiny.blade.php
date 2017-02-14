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
					<form class="topSearchForm" enctype="multipart/form-data" method="get" action="{{url('deo/p1-scrutiny-search') }}">
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
					<div class="panel-title pageTitle">Scrutiny Report</div>
					<div class="panel-body">
						<?php if(!empty($scrutinyReport)) { ?>
							<div class="pdfList">
								<ul>
									@if($scrutinyReport)
										<?php $scrutinyReportName=$scrutinyReport->doc_name; ?>
										<li>	
											<a href="{{ URL::asset('files/'.$scrutinyReportName) }}">
												<img src="{{ URL::asset('images/pdfIcon.jpg') }}"/>
												<p>Download Scrutiny Report</p>
											</a>
										</li>
									@endif
								</ul>
							</div>
						<?php } else { ?>
							<p>Scrutiny Report is not Uploaded by RO.</p>
						<?php } ?>
					</div>
				</div>
			</div>	
		</div>
		<?php  } ?>
	</div>
	<!-- END CONTAINER -->
@endsection