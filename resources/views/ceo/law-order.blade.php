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
					<form class="topSearchForm" enctype="multipart/form-data" method="get" action="{{url('ceo/law-order-sub') }}">
					<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
						<div class="form-group{{ $errors->has('dist_code') ? ' has-error' : '' }}">
							<select name="dist_code" class="form-control poll_dist_code_ceo">
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
		<?php if(($distCodeCheck!=="" && $consCodeCheck!=="") || ($encryptDistCode!=="" && $encryptConsCode!=="")) { ?>
		
		<div class="row">
					<!-- Nominations -->
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					<div class="panel-body">
						<table class="table table-bordered tablefilter">
						<thead>
							<tr>
								<th>Poll Building</th>
								<th>Comment</th>
								<th>Time of Poll Interrupted (From)</th>
								<th>Time of Poll Interrupted (To)</th>
								<th>PRO Name</th>
								<th>PRO Phone</th>
								<th>Supervisor Name</th>
								<th>Supervisor Phone</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th>Poll Building</th>
								<th>Comment</th>
								<th>Time of Poll Interrupted (From)</th>
								<th>Time of Poll Interrupted (To)</th>
								<th>PRO Name</th>
								<th>PRO Phone</th>
								<th>Supervisor Name</th>
								<th>Supervisor Phone</th>
							</tr>
						</tfoot>
						<tbody>	
						@foreach($laworderlist as $laworderlists)								
							<tr>
								<td>{{$laworderlists->poll_building}}</td>
								<td>{{$laworderlists->comment}}</td>
								<td>{{$laworderlists->action_from}}</td>
								<td>{{$laworderlists->action_to}}</td>
								<td>{{$laworderlists->pro_name}}</td>
								<td>{{$laworderlists->pro_number}}</td>
								<td>{{$laworderlists->sup_name}}</td>
								<td>{{$laworderlists->sup_num}}</td>
							</tr>
							@endforeach
						</tbody>
					</table>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>
	</div>
	<!-- END CONTAINER -->	
@endsection
