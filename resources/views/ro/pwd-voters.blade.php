@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<!-- Nominations -->
			<div class="col-md-12">
				<div class="panel panel-widget">
					<div class="panel-title pageTitle titleBtn clearfix">
						<span>PWD Database</span>
					</div>
					<div class="panel-body">
						<table class="table table-bordered tablefilter">
							<thead>
								<tr>
									<th>SERIAL NUMBER</th>
									<th>EPIC NUMBER</th>
									<th>VOTER NAME</th>
									<th>RELATION TYPE</th>
									<th>RELATION NAME</th>
									<th>DATE OF BIRTH</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>SERIAL NUMBER</th>
									<th>EPIC NUMBER</th>
									<th>VOTER NAME</th>
									<th>RELATION TYPE</th>
									<th>RELATION NAME</th>
									<th>DATE OF BIRTH</th>
								</tr>
							</tfoot>
							<tbody>
								@foreach ($getPwdVoter as $getPwdVoters)
								<tr>
									<td>{{ $getPwdVoters->slnoinpart }}</td>
									<td>
										<?php $iCardNoEnc = eci_encrypt($getPwdVoters->IDCARD_NO); ?>
										<a href="{{ url('/ro/voter-detail/'.$iCardNoEnc) }}">{{ $getPwdVoters->IDCARD_NO }}</a>
									</td>
									<td>{{ $getPwdVoters->Fm_NameEn }} {{ $getPwdVoters->LastNameEn }}</td>
									<td>
									<?php
										if($getPwdVoters->RLN_TYPE == "H"){
											echo "HUSBAND";
										}
										if($getPwdVoters->RLN_TYPE == "F"){
											echo "FATHER ";
										}
									?>
									</td>
									<td>{{ $getPwdVoters->Rln_Fm_NmEn }} {{ $getPwdVoters->RLn_L_NmEn }}</td>
									<td>{{ $getPwdVoters->dob }}</td>
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
	<!-- END CONTAINER -->
@endsection


