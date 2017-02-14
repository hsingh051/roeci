@extends('layouts.main')
@section('content')
<?php
	$selectedCons= (isset($_GET['cons_code']))? $_GET['cons_code'] : "";
	$encryptConsCode= (isset($encConsCode))? $encConsCode : "";
	$consList= (isset($constituency))? $constituency : "";
?>
	
	<!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">
				<div class="searchBar">
					<form class="topSearchForm" enctype="multipart/form-data" method="get" action="{{url('deo/law-order-sub') }}">
					<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
						<div class="form-group{{ $errors->has('cons_code') ? ' has-error' : '' }}">
							<select name="cons_code" class="form-control" id="cons_code">
								<option value="">Select Assembly Constituency</option>
								@foreach($consList as $data)
								<?php
									$cons_code=eci_encrypt($data->cons_code);
								?>
								<option value="{{ $cons_code }}" <?php if($encryptConsCode == $cons_code) { echo"selected"; } ?> >{{ $data->cons_name }}
								</option>
								@endforeach
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
		<?php if(($selectedCons!=="") || ($encryptConsCode!=="")) { ?>
		
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
