@extends('layouts.main')
@section('content')
	<?php
	$distCodeCheck= (isset($_GET['dist_code']))? $_GET['dist_code'] : "";
	$encryptDistCode= (isset($encryptDist))? $encryptDist : "";
	?>
	<script>
		$(document).ready(function() {
			$('#example0').DataTable();
		});
	</script>
    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">
				<div class="searchBar">
					<form class="topSearchForm" enctype="multipart/form-data" method="get" action="{{url('ceo/electionObserverSearch') }}">
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
						<div class="form-group">
							<button type="submit" class="btn btn-default">Submit</button>
						</div>
					</form>
				</div>
			</div>
		</div>  
		<?php if(($distCodeCheck!=="") || ($encryptDistCode!=="")) { ?>
		
		<div class="container-widget">
			<div class="row">
				<div class="col-md-12">
					<ul class="nav nav-pills evm-tabs" role="tablist">
						<li role="presentation" class="active"><a href="#GO" aria-controls="GO" role="tab" data-toggle="tab">General Observer</a></li>
						<li role="presentation"><a href="#EO" aria-controls="EO" role="tab" data-toggle="tab">Expenditure Observer</a></li>
						<li role="presentation"><a href="#PO" aria-controls="PO" role="tab" data-toggle="tab">Police Observer</a></li>
						<li role="presentation"><a href="#AO" aria-controls="AO" role="tab" data-toggle="tab">Awareness Observer</a></li>
					</ul>
				</div>
			</div>

			<div class="tab-content evm-tab-content">
				<div role="tabpanel" class="tab-pane active" id="GO">
					<div class="row">
						<!-- Nominations -->
						<div class="col-md-12">
							<div class="panel panel-widget heightWidget">
								<div class="panel-title pageTitle">General Observers</div>
								<div class="panel-body">
									<table class="table table-bordered tablefilter">
										<thead>
											<tr>
												<th>Constituency</th>
												<th>Name</th>
												<th>Phone Number</th>
											</tr>
										</thead>
										<tfoot>
											<tr>
												<th>Constituency</th>
												<th>Name</th>
												<th>Phone Number</th>
											</tr>
										</tfoot>
										<tbody>
											@foreach ($generalObserver as $value)
											<tr>
												<td>{{ $value->cons_name }}</td>
												<td><a href="{{ url('/ceo/observer-profile') }}<?php echo "/".eci_encrypt($value->id);?>">{{ $value->name }}</a></td>
												<td>{{ $value->phone }}</td>
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<!-- End Nominations -->
					</div>
				</div>
			
				<div role="tabpanel" class="tab-pane" id="EO">
					<div class="row">
						<!-- Nominations -->
						<div class="col-md-12">
							<div class="panel panel-widget heightWidget">
								<div class="panel-title pageTitle">Expenditure Observers</div>
								<div class="panel-body">
									<table class="table table-bordered tablefilter1">
										<thead>
											<tr>
												<th>Constituency</th>
												<th>Name</th>
												<th>Phone Number</th>
											</tr>
										</thead>
										<tfoot>
											<tr>
												<th>Constituency</th>
												<th>Name</th>
												<th>Phone Number</th>
											</tr>
										</tfoot>
										<tbody>
											@foreach ($expenditureObserver as $value)
											<tr>
												<td>{{ $value->cons_name }}</td>
												<td><a href="{{ url('/ceo/observer-profile') }}<?php echo "/".eci_encrypt($value->id);?>">{{ $value->name }}</a></td>
												<td>{{ $value->phone }}</td>
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<!-- End Nominations -->
				</div>
			
				<div role="tabpanel" class="tab-pane" id="PO">
					<div class="row">
						<!-- Nominations -->
						<div class="col-md-12">
							<div class="panel panel-widget heightWidget">
								<div class="panel-title pageTitle">Police Observers</div>
								<div class="panel-body">
									<table class="table table-bordered tablefilter2">
										<thead>
											<tr>
												<th>Constituency</th>
												<th>Name</th>
												<th>Phone Number</th>
											</tr>
										</thead>
										<tfoot>
											<tr>
												<th>Constituency</th>
												<th>Name</th>
												<th>Phone Number</th>
											</tr>
										</tfoot>
										<tbody>
											@foreach ($policeObserver as $value)
											<tr>
												<td>{{ $value->cons_name }}</td>
												<td><a href="{{ url('/ceo/observer-profile') }}<?php echo "/".eci_encrypt($value->id);?>">{{ $value->name }}</a></td>
												<td>{{ $value->phone }}</td>
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<!-- End Nominations -->
				</div>
			
				<div role="tabpanel" class="tab-pane" id="AO">
					<div class="row">
						<!-- Nominations -->
						<div class="col-md-12">
							<div class="panel panel-widget heightWidget">
								<div class="panel-title pageTitle">Awareness Observers</div>
								<div class="panel-body">
									<table class="table table-bordered tablefilter3">
										<thead>
											<tr>
												<th>Constituency</th>
												<th>Name</th>
												<th>Phone Number</th>
											</tr>
										</thead>
										<tfoot>
											<tr>
												<th>Constituency</th>
												<th>Name</th>
												<th>Phone Number</th>
											</tr>
										</tfoot>
										<tbody>
											@foreach ($awarenessObserver as $value)
											<tr>
												<td>{{ $value->cons_name }}</td>
												<td><a href="{{ url('/ceo/observer-profile') }}<?php echo "/".eci_encrypt($value->id);?>">{{ $value->name }}</a></td>
												<td>{{ $value->phone }}</td>
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<!-- End Nominations -->
				</div>
			</div> 
		</div>
	<!-- END CONTAINER -->
		<?php } ?>
	</div>
	<!-- END CONTAINER -->
@endsection

