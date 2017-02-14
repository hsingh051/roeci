@extends('layouts.main')
@section('content')
<?php
	$encryptCons= (isset($encryptCons))? $encryptCons : "";
	$encryptConsGet= (isset($_GET['consCode']))? $_GET['consCode'] : "";
?>
    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">
				<div class="searchBar">
					<form class="topSearchForm" enctype="multipart/form-data" method="get" action="{{url('deo/postal-ballot-sub') }}">
					<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
						<div class="form-group{{ $errors->has('consCode') ? ' has-error' : '' }}">
							<select name="consCode" class="form-control" id="consCode">
								<option value="">Select Constituency</option>
								@foreach($constituency as $constituencies)
								<?php $consEnc=eci_encrypt($constituencies->cons_code); ?>
								<option value="{{$consEnc}}" <?php if($encryptCons==$consEnc){echo "selected";} ?>>{{ $constituencies->cons_name }}</option>
								@endforeach
							</select>
							@if ($errors->has('consCode'))
							<span class="help-block">
								<strong>{{ $errors->first('consCode') }}</strong>
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
		<?php if($encryptCons!=="" || $encryptConsGet!=="" ){ ?>
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

