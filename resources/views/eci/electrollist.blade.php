@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget">
					<div class="panel-title pageTitle">
                        <span>EVM</span>
                    </div>
					<div class="panel-body">
						<table class="table table-bordered tablefilter">
							<thead>
								<tr>
									<!-- <th>PART NAME</th> -->
									<th>SERIAL NUMBER</th>
									<th>EPIC NUMBER</th>
									<th>VOTER NAME</th>
									<th>RELATION TYPE</th>
									<th>RELATION NAME</th>
									<th>DATE OF BIRTH</th>
									<th>AGE</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<!-- <th>PART NAME</th> -->
									<th>SERIAL NUMBER</th>
									<th>EPIC NUMBER</th>
									<th>VOTER NAME</th>
									<th>RELATION TYPE</th>
									<th>RELATION NAME</th>
									<th>DATE OF BIRTH</th>
									<th>AGE</th>
								</tr>
							</tfoot>
							<tbody>
								@foreach($votersList as $votersLists)
								<?php
									if(@$votersLists->idcardNo){
										$iCardNo = $votersLists->idcardNo;
										$ps_id = $votersLists->ps_id;
										$name = $votersLists->fm_nameEn." ".$votersLists->LastNameEn;
										$age = $votersLists->age;
										$dob = date("d F, Y",strtotime($votersLists->dob));
										$mobileno = $votersLists->mobileno;
										$slnoinpart = $votersLists->slnoinpart;
										$rlnType = $votersLists->rlnType;
										$rln_name =  $votersLists->rln_Fm_NmEn." ".$votersLists->rln_L_NmEn;
										$age = $votersLists->age;
										$dob = date("d F, Y",strtotime($votersLists->dob));
									}elseif(@$votersLists->IDCARD_NO){
										$iCardNo = $votersLists->IDCARD_NO;
										$ps_id = $votersLists->PART_NO;
										$name = $votersLists->Fm_NameEn." ".$votersLists->LastNameEn;
										$dob = date("d F, Y",strtotime($votersLists->dob));
										$age = $votersLists->AGE;
										$mobileno = $votersLists->Mobileno;
										$slnoinpart = $votersLists->SLNOINPART;
										$rlnType = $votersLists->RLN_TYPE;
										$rln_name = $votersLists->Rln_Fm_NmEn. " ".$votersLists->RLn_L_NmEn;
										$age = $votersLists->AGE;
										$dob = date("d F, Y",strtotime($votersLists->dob));
									}
									$iCardNoEnc = eci_encrypt($iCardNo);
								?>
								<tr>
									
									<td>{{ $slnoinpart }}</td>
									<td><a href="{{ url('/eci/voter-detail/'.$iCardNoEnc) }}">{{ $iCardNo }}</a></td>
									<td>{{ $name }}</td>
									<td>
									<?php
										if(($rlnType)=="F"){
											echo "FATHER";
										}
										if(($rlnType)=="H"){
											echo "HUSBAND";
										}
									?>
									</td>
									<td>{{ $rln_name }}</td>
									<td>{{ $dob }}</td>
									<td>{{ $age }}</td>
								</tr>	
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>		
		</div> 		
	</div>
	<!-- END CONTAINER -->
@endsection