@extends('layouts.main')

@section('content')
	<!-- START CONTAINER -->
	<div class="container-widget">	  
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					<div class="panel-body">
						<div class="voterPro">
							<ul>
                            	
								<li><b>Complainant Number:</b><?php echo $details['ComplainNo'];?></li>
                                <?php if(@$details['ComplainantType']){?><li><b>Complainant Type:</b><?php echo $details['ComplainantType'];?></li><?php }?>
								<?php if(@$details['PartyComplainent']){?><li><b>Political Party Of The Complainant:</b><?php echo $details['PartyComplainent'];?></li><?php }?>
								<li><b>Name Of Complainant:</b><?php echo $details['NameOfComplainent'];?></li>
								<li><b>Mobile No Of Complainant:</b><?php echo $details['MobilOfCompalinent'];?></li>
								<?php if(@$details['EmailIDOfComplainent']){?><li><b>Email-ID Of Complainant:</b><?php echo $details['EmailIDOfComplainent'];?></li><?php }?>
								<?php if(@$details['AddressOfComplainent']){?><li><b>Address Of Complainant:</b><?php echo $details['AddressOfComplainent'];?></li><?php }?>
								<?php if(@$details['EmailIDOfComplainent']){?><li><b>EPIC NO:</b><?php echo $details['EmailIDOfComplainent'];?></li><?php }?>
								<?php if(@$details['type']){?><li><b>Type Of Complaint:</b><?php echo $details['type'];?></li><?php }?>
								<?php if(@$details['nature']){?><li><b>Nature Of Complaint:</b><?php echo $details['nature'];?></li><?php }?>
								<!--<li><b>District:</b>Ludhiana</li>-->
								<!--<li><b>Assembly Consituency:</b>Sahnewal</li>-->
								<!--<li><b>Polling Station Number & Name:</b>25 - Sasrali</li>-->
								<!--<li><b>Complaint Against Political Party:</b>Bharatiya Janata Party</li>-->
								<?php if(@$details['ComplainAgainstName']){?><li><b>Complaint Against (Name):</b><?php echo $details['ComplainAgainstName'];?></li><?php }?>
								<!--<li><b>Complaint To Be Lodged Before:</b>CEO (State Level)</li>-->
								<li><b>Date Of Violation:</b><?php echo $details['cdate'];?></li>
								<li><b>Brief Description of Complaint:</b><?php echo $details['ComplainDescription'];?></li>
								<li><b>Status:</b><?php echo $details['status'];?></li>
							</ul>
						</div>
					</div>
				</div>
			</div>			
		</div>  
	</div>
	<!-- END CONTAINER -->
@endsection