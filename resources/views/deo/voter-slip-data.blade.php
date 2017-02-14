@extends('layouts.main')
@section('content')
<?php 
$consCodeCheck= (isset($_GET['cons_code']))? $_GET['cons_code'] : "";
$encryptConsCode= (isset($encryptCons))? $encryptCons : "";

if(isset($_GET['cons_code']))
{
	$cons_code = $consCodeCheck;
}
else
{
	$cons_code = $encryptConsCode;
}
?>
    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">
				<div class="searchBar">
					<form class="topSearchForm" enctype="multipart/form-data" method="get" action="{{url('deo/voterslipDataResult') }}">
					<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
						<div class="form-group{{ $errors->has('cons_code') ? ' has-error' : '' }}">
							<select name="cons_code" class="form-control" id="cons_code">
									<option value="">Select Constituency</option>
								@foreach($constituency as $constituencies)
									<?php $consCode=eci_encrypt($constituencies->cons_code); ?>
									<option value="{{$consCode}}" <?php if($consCode==$cons_code){ echo "selected"; } ?> >{{ $constituencies->cons_name }}</option>
								@endforeach
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

