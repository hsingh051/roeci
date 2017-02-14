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
									<th>PART NAME</th>
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
									<th>PART NAME</th>
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
								<?php
									$i=1; 
									foreach($voterlist as $list){
										if($list->rlnType == 'H'){
											$relation = "Husband";
										}
										else{
											$relation = "Father";
										}
										$iCardNoEnc=eci_encrypt($list->idcardNo);
								?>
										<tr>
											<td>{{$list->part_name}}</td>
											<td>{{$list->slnoinpart}}</td>
											<td><a href="{{ url('/ceo/voter-detail/'.$iCardNoEnc) }}">{{$list->idcardNo}}</a></td>
											<td>{{$list->fm_nameEn}} {{$list->LastNameEn}}</td>
											<td><?php echo $relation; ?></td>
											<td>{{$list->rln_Fm_NmEn}} {{$list->rln_L_NmEn}}</td>
											<td>{{$list->dob}}</td>
											<td>{{$list->age}}</td>
										</tr>
								<?php $i++; }?>
							</tbody>
						</table>
					</div>
				</div>
			</div>		
		</div> 		
	</div>
	<!-- END CONTAINER -->
@endsection