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
                            <?php 
                            	$countComp=count($complaints);
                            	if($countComp>0){
	                            	foreach($complaints as $com){ 
									?>
	                                    <tr>
	                                        <td>
	                                        	<?php
	                                        	$complainno= (isset($com['complainno']))? $com['complainno'] : "";
	                                        	?>
	                                        	@if($complainno)
	                                        	<a href="{{ url('/ro/complaint-detail/') }}<?php echo "/".$com['complainno'];?>"><?php echo $com['complainno'];?></a>
	                                        	@endif
	                                        </td>

	                                        <td>
	                                        <?php
	                                        	$type= (isset($com['type']))? $com['type'] : "";
	                                        	echo $type;
	                                        ?>
	                                        </td>
	                                        <td>
		                                    <?php
			                                    $nature= (isset($com['nature']))? $com['nature'] : "";
			                                    echo $nature;
		                                    ?>
		                                    </td>
	                                        <td>
		                                        <?php 
		                                        	$comdate= (isset($com['comdate']))? $com['comdate'] : "";
		                                        	echo $comdate;
		                                        ?>
		                                    </td>
	                                        <td>
                                        	<?php
                                        		$status= (isset($com['status']))? $com['status'] : "";
                                        		echo $status;
                                        	?>
	                                        </td>
	                                    </tr> 
	                                <?php
	                                }
	                    		}
	                        ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>		
		</div>
	</div>
	<!-- END CONTAINER -->
@endsection

