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
					<form class="topSearchForm" enctype="multipart/form-data" method="get" action="{{url('deo/evm-malfunction-sub') }}">
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
		<?php } ?>
	</div>
	<!-- END CONTAINER -->	
@endsection
