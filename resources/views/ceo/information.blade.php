@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget">
					<div class="panel-title pageTitle">
						<span>Information</span>
					</div>
					<div class="panel-body">
						<table class="table table-bordered tablefilter">
							<thead>
								<tr>
                                	<th>Information No</th>
									<th>Type Of Information</th>
									<th>Nature Of Information</th>
									<th>Status</th>								
								</tr>
							</thead>
                            <tfoot>
								<tr>
                                	<th>Information No</th>
									<th>Type Of Information</th>
									<th>Nature Of Information</th>
									<th>Status</th>								
								</tr>
							</tfoot>
							<tbody>
								<!--<tr>
									<td><a href="{{ url('/deo/complaint-detail') }}">Poll Related</a></td>
									<td>Law & Order Issue</td>
									<td><span class="red-text">Pending</span></td>
								</tr>--> 
								<?php foreach($complaints as $com){ 
								//echo "<pre>";
//								print_r($com);
//								echo "</pre>";
								?>
                                        <tr>
                                            <td><a href="{{ url('/deo/complaint-detail/') }}<?php echo "/".$com['complainno'];?>"><?php echo $com['complainno'];?></a></td>
                                            <td><?php echo $com['type'];?></td>
                                            <td><?php if(@$com['nature']){echo $com['nature'];}?></td>
                                            <td><?php echo $com['status'];?></td>
                                        </tr> 
                                <?php }?>
							</tbody>
						</table>
					</div>
				</div>
			</div>		
		</div>
	</div>
	<!-- END CONTAINER -->
@endsection

