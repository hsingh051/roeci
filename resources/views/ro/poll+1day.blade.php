@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
	<div class="container-widget">		
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					<div class="panel-title pageTitle titleBtn clearfix">
						<span>Annexure Report</span>
						<div class="panel-btn">
							<a href="{{ url('/ro/p1day-update') }}" class="btn btn-default">Update</a>
						</div>
					</div>
					<div class="panel-body">
						<table class="table table-bordered">
							<tbody>
								<tr>
									<td class="w4">1</td>
									<td class="w80">Interruption or obstruction of poll due to riots, open violence, natural calamity or any other cause</td>
									<td>Yes</td>
								</tr>
								<tr>
									<td>2</td>
									<td>Vitiation of the poll by any of the EVMs having been unlawfully taken out of the custody of the presiding officer, accidentally or unintentionally lost or destroyed or damaged or tampered with</td>
									<td>Yes</td>
								</tr>
								<tr>
									<td>3</td>
									<td>Votes having been unlawfully recorded by any person in the EVMs</td>
									<td>No</td>
								</tr>
								<tr>
									<td>4</td>
									<td>Booth capturing</td>
									<td>No</td>
								</tr>
								<tr>
									<td>5</td>
									<td>Serious complaint</td>
									<td>Yes</td>
								</tr>
								<tr>
									<td>6</td>
									<td>Violence and breach of law and order</td>
									<td>No</td>
								</tr>
								<tr>
									<td>7</td>
									<td>Mistake and irregularities, which have a bearing on the elections</td>
									<td>No</td>
								</tr>
								<tr>
									<td>8</td>
									<td>Weather conditions</td>
									<td>Rainy</td>
								</tr>
								<tr>
									<td>9</td>
									<td>Poll percentage</td>
									<td>100%</td>
								</tr>
								<tr>
									<td>10</td>
									<td>Whether all the diaries of Presiding Officers have been scrutinized and irregularities if any detected</td>
									<td>Yes</td>
								</tr>
								<tr>
									<td>11</td>
									<td>Recommendations regarding repoll / fresh poll, if any</td>
									<td>No</td>
								</tr>
								<tr>
									<td>12</td>
									<td>Any other remarks</td>
									<td>Remarks Text Here</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>		
		</div>
	</div>
	<!-- END CONTAINER -->
@endsection

