@extends('layouts.main')
@section('content')
<?php
$distCodeCheck= (isset($_GET['dist_code']))? $_GET['dist_code'] : ""; 
$consCodeCheck= (isset($_GET['cons_code']))? $_GET['cons_code'] : "";
$encryptConsCode= (isset($encryptCons))? $encryptCons : "";
$encryptDistCode= (isset($encryptDist))? $encryptDist : "";
$consList= (isset($constituency))? $constituency : "";

if($distCodeCheck=="")
{
	$distCodeCheck = $encryptDist;
}
if($consCodeCheck=="")
{
	$consCodeCheck = $encryptCons;
}
?>
    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">
				<div class="searchBar">
					<form class="topSearchForm" enctype="multipart/form-data" method="get" action="{{url('ceo/voterslipDataResult') }}">
						<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
						<div class="form-group{{ $errors->has('dist_code') ? ' has-error' : '' }}">
							<select name="dist_code" class="form-control poll_dist_code_ceo">
									<option value="">Select district</option>
								@foreach($district as $districts)
									<?php $distCode=eci_encrypt($districts->dist_code); ?>
									<option value="{{$distCode}}" <?php if($distCode==$distCodeCheck){ echo "selected"; } ?> >{{ $districts->dist_name }}</option>
								@endforeach
							</select>
							@if ($errors->has('dist_code'))
							<span class="help-block">
								<strong>{{ $errors->first('dist_code') }}</strong>
							</span>
							@endif
						</div>


						<div class="form-group{{ $errors->has('cons_code') ? ' has-error' : '' }}">
							<select name="cons_code" class="form-control poll_cons_code_ceo">
								<option value="">Select Constituency</option>
								@if($consList)
									@foreach($consList as $constituencies)
										<?php $consCode=eci_encrypt($constituencies->cons_code); ?>
										<option value="{{$consCode}}" <?php if($consCode==$consCodeCheck){ echo "selected"; } ?> >{{ $constituencies->cons_name }}</option>
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
		<?php if($consCodeCheck!==""){ ?>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget">
					<div class="panel-title pageTitle">
						<span>Voters Slip Data</span>
					</div>
					<div class="panel-body">
						<table class="table table-bordered tablefilter">
							<thead>
								<tr>
                                	<th>constituency Name</th>
									<th>Date</th>
									<th>Total Voters Slip</th>
									<th>No. of Slips Distributed</th>
									<th>No. of Slips Pending</th>
									<th>Date</th>
									<th>Total Voters Slip</th>
									<th>No. of Slips Distributed</th>
									<th>No. of Slips Pending</th>
									<th>Date</th>
									<th>Total Voters Slip</th>
									<th>No. of Slips Distributed</th>
									<th>No. of Slips Pending</th>														
								</tr>
							</thead>
                            <tfoot>
								<tr>
                                	<th>constituency Name</th>
									<th>Date</th>
									<th>Total Voters Slip</th>
									<th>No. of Slips Distributed</th>
									<th>No. of Slips Pending</th>
									<th>Date</th>
									<th>Total Voters Slip</th>
									<th>No. of Slips Distributed</th>
									<th>No. of Slips Pending</th>
									<th>Date</th>
									<th>Total Voters Slip</th>
									<th>No. of Slips Distributed</th>
									<th>No. of Slips Pending</th>								
								</tr>
							</tfoot>
							<tbody>
                            	<?php foreach($voterslipData as $voterslipData1){ 
								?>
                                        <tr>
                                            <td><?php echo $voterslipData1->cons_name;?></td>
                                            <td><?php echo $voterslipData1->date1;?></td>
                                            <td><?php echo $voterslipData1->total_voter_slip1;?></td>
                                            <td><?php echo $voterslipData1->slip_distributed1;?></td>
                                            <td><?php echo $voterslipData1->slip_pending1;?></td>
											<td><?php echo $voterslipData1->date2;?></td>
                                            <td><?php echo $voterslipData1->total_voter_slip2;?></td>
                                            <td><?php echo $voterslipData1->slip_distributed2;?></td>
                                            <td><?php echo $voterslipData1->slip_pending2;?></td>
											<td><?php echo $voterslipData1->date3;?></td>
                                            <td><?php echo $voterslipData1->total_voter_slip3;?></td>
                                            <td><?php echo $voterslipData1->slip_distributed3;?></td>
                                            <td><?php echo $voterslipData1->slip_pending3;?></td>
                                        </tr> 
                                <?php }?>
							</tbody>
						</table>
					</div>
				</div>
			</div>		
		</div>
		<?php } ?>
	</div>
	<!-- END CONTAINER -->
@endsection

