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
					<form class="topSearchForm" enctype="multipart/form-data" method="get" action="{{url('ceo/evm-malfunction-sub') }}">
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
		<?php if(($distCodeCheck!=="") && ($consCodeCheck!=="") || ($encryptConsCode!=="") && ($encryptDistCode!=="")) { ?>
		<div class="row">
			<div class="col-md-12">
				<ul class="nav nav-pills evm-tabs" role="tablist">
					<li role="presentation" class="active"><a href="#CU" aria-controls="CU" role="tab" data-toggle="tab">Malfunction Pending</a></li>
					<li role="presentation"><a href="#BU" aria-controls="BU" role="tab" data-toggle="tab">Malfunction Resolved</a></li>
				</ul>
			</div>
		</div>
		
		<div class="tab-content evm-tab-content">
			<div role="tabpanel" class="tab-pane active" id="CU">				 
				<div class="row">
					<!-- Nominations -->
					<div class="col-md-12">
						<div class="panel panel-widget heightWidget">
							<div class="panel-body">
								<table class="table table-bordered tablefilter">
									<thead>
										<tr>
											<th>BID</th>
											<th>Poll Building</th>
											<th>Name</th>
											<th>Comment</th>									
										</tr>
									</thead>
									<tfoot>
										<tr>
											<th>BID</th>
											<th>Poll Building</th>
											<th>Name</th>
											<th>Comment</th>									
										</tr>
									</tfoot>
									<tbody>
										@foreach($mallfunctions as $mallfunction)
										<tr>
											<td>{{ $mallfunction->bid }}</td>
											<td>{{ $mallfunction->poll_building }}</td>
											<td>{{ $mallfunction->name }}</td>
											<td>{{ $mallfunction->comment }}</td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div role="tabpanel" class="tab-pane" id="BU">
				<div class="row">
					<!-- Nominations -->
					<div class="col-md-12">
						<div class="panel panel-widget heightWidget">
							<div class="panel-body">
								<table class="table table-bordered tablefilter1">
									<thead>
										<tr>
											<th>BID</th>
											<th>Poll Building</th>
											<th>Name</th>
											<th>Reply</th>
										</tr>
									</thead>
									<tfoot>
										<tr>
											<th>BID</th>
											<th>Poll Building</th>
											<th>Name</th>
											<th>Reply</th>
										</tr>
									</tfoot>
									<tbody>
										@foreach($mallfunctions_resolve as $mallfunctions_resolves)
										<tr>
											<td>{{ $mallfunctions_resolves->bid }}</td>
											<td>{{ $mallfunctions_resolves->poll_building }}</td>
											<td>{{ $mallfunctions_resolves->name }}</td>
											<td>{{ $mallfunctions_resolves->reply }}</td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php  } ?>
	</div>
	<!-- END CONTAINER -->
@endsection