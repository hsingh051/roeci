@extends('layouts.main')
@section('content')
<?php
$distCodeCheck= (isset($_GET['dist_code']))? $_GET['dist_code'] : ""; 
$encryptDistCode= (isset($encryptDist))? $encryptDist : "";
$consList= (isset($constituency))? $constituency : "";

if(isset($_GET['dist_code']))
{
	$dist_code = $distCodeCheck;
}
else
{
	$dist_code = $encryptDistCode;
}
?>
    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">
				<div class="searchBar">
					<form class="topSearchForm" enctype="multipart/form-data" method="get" action="{{url('ceo/policeDataResult') }}">
						<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
						<div class="form-group{{ $errors->has('dist_code') ? ' has-error' : '' }}">
							<select name="dist_code" class="form-control poll_dist_code_ceo">
									<option value="">Select district</option>
								@foreach($district as $districts)
									<?php $distCode=eci_encrypt($districts->dist_code); ?>
									<option value="{{$distCode}}" <?php if($distCode==$dist_code){ echo "selected"; } ?> >{{ $districts->dist_name }}</option>
								@endforeach
							</select>
							@if ($errors->has('dist_code'))
							<span class="help-block">
								<strong>{{ $errors->first('dist_code') }}</strong>
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
		<?php if($distCodeCheck!==""){ ?>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget">
					<div class="panel-title pageTitle">
						<span>Police Data</span>
					</div>
					<div class="panel-body">
						<table class="table table-bordered tablefilter">
							<thead>
								<tr>
                                	<th>District Name</th>
									<th>Cumulative disposal from 03.01.2017 including the date of reporting</th>
									<th>New NBW issued during the period</th>
									<th>Pending as on 03.01.2017</th>
									<th>ARMS</th>
									<th>AMMUNITION</th>
									<th>OTHERS</th>									
									<th>Updated Time</th>								
								</tr>
							</thead>
                            <tfoot>
								<tr>
                                	<th>District Name</th>
									<th>Cumulative disposal from 03.01.2017 including the date of reporting</th>
									<th>New NBW issued during the period</th>
									<th>Pending as on 03.01.2017</th>
									<th>ARMS</th>
									<th>AMMUNITION</th>
									<th>OTHERS</th>									
									<th>Updated Time</th>								
								</tr>
							</tfoot>
							<tbody>
                            	<?php foreach($policeData as $policeData1){ 
								?>
                                        <tr>
                                            <td><?php echo $policeData1->dist_name;?></td>
                                            <td><?php echo $policeData1->nbw_total;?></td>
                                            <td><?php echo $policeData1->nbw_resolved;?></td>
                                            <td><?php echo $policeData1->nbw_pending;?></td>
                                            <td><?php echo $policeData1->arm_total;?></td>
											<td><?php echo $policeData1->arm_resolved;?></td>
                                            <td><?php echo $policeData1->arm_pending;?></td>
                                            <td><?php echo $policeData1->updated_at;?></td>
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

