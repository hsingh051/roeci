@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget">
					@if(Session::has('polDistHeadSucc'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('polDistHeadSucc') }}</p>
					@endif
					<div class="panel-title pageTitle titleBtn clearfix">
						<span>Political Parties: District Head</span>
						<div class="panel-btn"><a href="{{ url('/deo/add-political-parties-district-head') }}" class="btn btn-default">Add New District Head</a></div>
					</div>
					<div class="panel-body">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>Party Name</th>
									<th>Party Office Address</th>
									<th>District Head Name</th>
									<th>Email Address</th>
									<th>Primary Mobile No.</th>
									<th>Secondary Mobile No.</th>
									<th>Office Number</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<select class="form-control">
										<option value="">Select Party</option>
										@foreach($allPolParty as $allPolParties)
										<option>{{ $allPolParties->party_name }}</option>
										@endforeach
									</select>
									</td>
									<td></td>
									<td><input type="text" class="form-control" placeholder="Search District Head Name" /></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								@foreach($polPartyList as $polPartyLists)
								<tr>
									<td>{{ $polPartyLists->party_name }}</td>
									<td>{{ $polPartyLists->office_address }}</td>
									<td>{{ $polPartyLists->name }}</td>
									<td>{{ $polPartyLists->email }}</td>
									<td>{{ $polPartyLists->phone }}</td>
									<td>{{ $polPartyLists->sphone }}</td>
									<td>{{ $polPartyLists->office_phone }}</td>
									<td>
										<?php
											$uidEncrypt=eci_encrypt($polPartyLists->uid);
										?>
										<a href="{{ url('deo/editPPdistHead') }}/<?php echo $uidEncrypt; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
										<a class="delPPDH" href="{{ url('deo/delPPdistHead') }}/<?php echo $uidEncrypt; ?>"><i class="fa fa-times" aria-hidden="true"></i></a>
									</td>
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
	<script type="text/javascript">
		$(document).ready(function(){
			$(".delPPDH").click(function(){
				var answer=confirm('Do you want to delete?');
				if(answer){
					return true;
				}else{
					return false;
				}
			});
		});
	</script>
@endsection

