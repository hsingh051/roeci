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
					<form class="topSearchForm" enctype="multipart/form-data" method="get" action="{{url('eci/p1ConsolidatedReportSearch') }}">
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
		<?php if(($distCodeCheck!=="" && $consCodeCheck!=="") || ($encryptDistCode!=="" && $encryptConsCode!=="")) { ?>
		<div class="row">
			<!-- Nominations -->
			<div class="col-md-12">
				<div class="panel panel-widget">
					
					<div class="panel-body">
						<?php if(!empty($consReport)) { ?>
							<div class="panel-title pageTitle titleBtn clearfix">
								<span>Consolidated Report</span>
							</div>
							<div class="panel-body">
								<table class="table table-bordered">
									<tbody>
										<tr>
											<td class="w4">1</td>
											<td class="w80">Interruption or obstruction of poll due to riots, open violence, natural calamity or any other cause</td>
											<td><?php if(($consReport->interruption)==1){ echo "Yes"; }else{ echo "No"; } ?></td>
										</tr>
										<tr>
											<td>2</td>
											<td>Vitiation of the poll by any of the EVMs having been unlawfully taken out of the custody of the presiding officer, accidentally or unintentionally lost or destroyed or damaged or tampered with</td>
											<td><?php if(($consReport->vitiation_evm_unlawfully)==1){ echo "Yes"; }else{ echo "No"; } ?></td>
										</tr>
										<tr>
											<td>3</td>
											<td>Votes having been unlawfully recorded by any person in the EVMs</td>
											<td><?php if(($consReport->votes_unlawfully)==1){ echo "Yes"; }else{ echo "No"; } ?></td>
										</tr>
										<tr>
											<td>4</td>
											<td>Booth capturing</td>
											<td><?php if(($consReport->booth_capturing)==1){ echo "Yes"; }else{ echo "No"; } ?></td>
										</tr>
										<tr>
											<td>5</td>
											<td>Serious complaint</td>
											<td><?php if(($consReport->serious_complaint)==1){ echo "Yes"; }else{ echo "No"; } ?></td>
										</tr>
										<tr>
											<td>6</td>
											<td>Violence and breach of law and order</td>
											<td><?php if(($consReport->violence_law_order)==1){ echo "Yes"; }else{ echo "No"; } ?></td>
										</tr>
										<tr>
											<td>7</td>
											<td>Mistake and irregularities, which have a bearing on the elections</td>
											<td><?php if(($consReport->mistake_irregularities)==1){ echo "Yes"; }else{ echo "No"; } ?></td>
										</tr>
										<tr>
											<td>8</td>
											<td>Weather conditions</td>
											<td>{{ $consReport->weather_conditions }}</td>
										</tr>
										<tr>
											<td>9</td>
											<td>Poll percentage</td>
											<td>{{ $consReport->poll_percentage }}</td>
										</tr>
										<tr>
											<td>10</td>
											<td>Whether all the diaries of Presiding Officers have been scrutinized and irregularities if any detected</td>
											<td><?php if(($consReport->pre_scrutiny)==1){ echo "Yes"; }else{ echo "No"; } ?></td>
										</tr>
										<tr>
											<td>11</td>
											<td>Recommendations regarding repoll / fresh poll, if any</td>
											<td><?php if(($consReport->recommendations_repoll)==1){ echo "Yes"; }else{ echo "No"; } ?></td>
										</tr>
										<tr>
											<td>12</td>
											<td>Any other remarks</td>
											<td>{{ $consReport->remarks }}</td>
										</tr>
									</tbody>
								</table>
							</div>
						<?php } else { ?>
							<p>Consolidated Report is not Uploaded by RO.</p>
						<?php } ?>
					</div>
				</div>
			</div>	
		</div>
		<?php  } ?>
	</div>
	<!-- END CONTAINER -->
@endsection