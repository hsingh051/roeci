@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget">
					<div class="panel-title pageTitle">
						<span>Complaint</span>
					</div>
					<div class="panel-body">
						<table class="table table-bordered tablefilter">
							<thead>
								<tr>
                                	<th>Complaint No</th>
									<th>Type Of Complaint</th>
									<th>Nature Of Complaint</th>
									<th>Date Of Violation</th>									
									<th>Status</th>								
								</tr>
							</thead>
                            <tfoot>
								<tr>
                                	<th>Complaint No</th>
									<th>Type Of Complaint</th>
									<th>Nature Of Complaint</th>
									<th>Date Of Violation</th>									
									<th>Status</th>								
								</tr>
							</tfoot>
							<tbody>
                            	<?php foreach($complaints as $com){ 
								?>
                                        <tr>
                                            <td><a href="{{ url('/eci/complaint-detail/') }}<?php echo "/".$com['complainno'];?>"><?php echo $com['complainno'];?></a></td>
                                            <td><?php echo $com['type'];?></td>
                                            <td><?php if(isset($com['nature'])) echo $com['nature'];?></td>
                                            <td><?php echo $com['comdate'];?></td>
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

