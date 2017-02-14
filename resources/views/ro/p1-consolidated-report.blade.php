@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
	<div class="container-widget">		
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
				@if(Session::has('addP1ConsMsz'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('addP1ConsMsz') }}</p>
				@endif
				<?php if($count==0){ ?>
					<div class="panel-title pageTitle titleBtn clearfix">
						<span>Consolidated Report</span>
						<div class="panel-btn">
							<a href="{{ url('/ro/p1-consolidated-report-add') }}" class="btn btn-default">Add Report</a>
						</div>
					</div>
				<?php }else{ ?>
					<div class="panel-title pageTitle titleBtn clearfix">
						<span>Consolidated Report</span>
						<div class="panel-btn">
							<a href="{{ url('/ro/p1-consolidated-report-update') }}" class="btn btn-default">Update</a>
						</div>
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
				<?php } ?>
				</div>
			</div>		
		</div>
	</div>
	<!-- END CONTAINER -->
@endsection