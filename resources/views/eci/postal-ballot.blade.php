@extends('layouts.main')
@section('content')
<?php
$distCodeCheck= (isset($_GET['dist_code']))? $_GET['dist_code'] : ""; 
$consCodeCheck= (isset($_GET['cons_code']))? $_GET['cons_code'] : "";
$encryptConsCode= (isset($encryptCons))? $encryptCons : "";
$encryptDistCode= (isset($encryptDist))? $encryptDist : "";
$consList= (isset($constituency))? $constituency : "";
?>
    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">
				<div class="searchBar">
					<form class="topSearchForm" enctype="multipart/form-data" method="get" action="{{url('eci/postal-ballot-sub') }}">
					<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
						<div class="form-group{{ $errors->has('dist_code') ? ' has-error' : '' }}">
							<select name="dist_code" class="form-control poll_dist_code">
									<option value="">Select district</option>
								@foreach($district as $districts)
									<?php $distCode=eci_encrypt($districts->dist_code); ?>
									<option value="{{$distCode}}" <?php if($distCode==$encryptDistCode){ echo "selected"; } ?> >{{ $districts->dist_name }}</option>
								@endforeach
							</select>
							@if ($errors->has('dist_code'))
							<span class="help-block">
								<strong>{{ $errors->first('dist_code') }}</strong>
							</span>
							@endif
						</div>
						<div class="form-group{{ $errors->has('cons_code') ? ' has-error' : '' }}">
							<select name="cons_code" class="form-control poll_cons_code">
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
		<?php 
		if(($distCodeCheck!=="") && ($consCodeCheck!=="") || ($encryptConsCode!=="") && ($encryptDistCode!=="")) { ?>
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-widget heightWidget">
						@if($postBallot)
						<div class="panel-body">
							<table class="table table-bordered dataTable tablefilter">
								<thead>
									<tr>
										<th>Type</th>
										<th>Male</th>
										<th>Female</th>
										<th>Total</th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<th>Type</th>
										<th>Male</th>
										<th>Female</th>
										<th>Total</th>
									</tr>
								</tfoot>
								<tbody>
									<tr>
										<td>Army Voters</td>
										<td>{{ $postBallot->army_voters_male }}</td>
										<td>{{ $postBallot->army_voters_female }}</td>
										<td>
										<?php
											$a=$postBallot->army_voters_male;
											$b=$postBallot->army_voters_female;
											$c=($a+$b);
											echo $c;
										?>
										</td>
									</tr>
									<tr>
										<td>EDC Voters</td>
										<td>{{ $postBallot->edc_voters_male }}</td>
										<td>{{ $postBallot->edc_voters_female }}</td>
										<td>
										<?php
											$d=$postBallot->edc_voters_male;
											$e=$postBallot->edc_voters_female;
											$f=($d+$e);
											echo $f;
										?>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						@else
						<div class="panel-body">
							<p>No records found</p>
						</div>
						@endif
					</div>
				</div>
			</div>
		<?php } ?>
	</div>
	<!-- END CONTAINER -->
@endsection