@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget">
					<div class="panel-title pageTitle">
						<span>Political Parties: District Head</span>
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
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<select class="form-control">
										<option>Select Party</option>
										<option>Shiromani Akali Dal</option>
										<option>Indian National Congress</option>
										<option>Bharatiya Janata Party</option>
										<option>Bahujan Samaj Party</option>
										<option>Aam Aadmi Party</option>
										<option>CPI</option>
										<option>(CPI(M)</option>
										<option>Nationalist Congress Party</option>
									</select>
									</td>
									<td></td>
									<td><input type="text" class="form-control" placeholder="Search District Head Name" /></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td>Shiromani Akali Dal</td>
									<td>261/18, Guru Sagar Bihar, Salem Tabri, Ludhiana</td>
									<td>Sh. Madan Lal Bagga, District Head(Urban)</td>
									<td>mlbagga01818@gmail.com</td>
									<td>9417201818</td>
									<td>9216721818</td>
									<td></td>
								</tr>
								<tr>
									<td>Shiromani Akali Dal</td>
									<td>186B, Model Town Extention, Ludhiana</td>
									<td>S. Harbhajan Singh Dang, District Head(Urban)</td>
									<td></td>
									<td>9417201818</td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td>Shiromani Akali Dal</td>
									<td>Ward No. 7, G.T. Road, Near Gurudwara Guru Arjan Dev, Sahnewal</td>
									<td>S. Santa Singh Umedpuri, District Head(Rural)</td>
									<td></td>
									<td>9872571071</td>
									<td>9815351671</td>
									<td></td>
								</tr>
								<tr>
									<td>Indian National Congress</td>
									<td>Congress Bhawan, Near Clock Tower, Ludhiana</td>
									<td>Sh. Gurpreet Gogi, District Head(Urban)</td>
									<td>gurpreetbassi1967@gmail.com</td>
									<td>9814706061</td>
									<td></td>
									<td>0161-2721996</td>
								</tr>
								<tr>
									<td>Indian National Congress</td>
									<td>Congress Bhawan, Near Clock Tower, Ludhiana</td>
									<td>Sh. Lapraan, District Head(Urban)</td>
									<td></td>
									<td>9814706061</td>
									<td></td>
									<td>0161-2721996</td>
								</tr>
								<tr>
									<td>Bharatiya Janata Party</td>
									<td>5 Jawahar Market, Second Flor, Near Clock Tower, Ludhiana</td>
									<td>Sh. Ravinder Arora, District Head</td>
									<td>r.aroraassociates@hotmail.com</td>
									<td>9815022900</td>
									<td></td>
									<td>0161-2740946</td>
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

