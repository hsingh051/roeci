@extends('layouts.main')
@section('content')
	<script type="text/javascript">
		$(document).ready(function(){
			$('#addMoreStateBtn').click(function(){
				$('.CloneStateHead:first').clone().appendTo('#addMoreState');
			});
		});
	</script>
    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					<div class="panel-title pageTitle titleBtn text-center">
						<span>Add New Political Party State Head</span>
						<a href="{{URL::previous()}}" class="btn btn-default formBackBtn">Back</a>
					</div>
					<div class="panel-body">
						<div class="noticeCandi">
							<form method="post">
								<div class="form-group">
									<?php 	//use App\Http\Controllers\HomeController; 
											//echo HomeController::get_political_parties(); 
										//App:make("HomeController")->get_political_parties();
										
									?>

									<label class="form-label">Party Name</label>

									<select class="form-control">
										<?php echo get_political_parties('53');?>
									</select>
								</div>
								<div class="form-group">
									<label class="form-label">Office Address</label>
									<input type="text" class="form-control" placeholder="Enter Office Address" />
								</div>								
								<div class="form-group">
									<label class="form-label">Office Number</label>
									<input type="text" class="form-control" placeholder="Enter Office Number" />
								</div>
								<div id="addMoreState" class="addMoreBox">
									<div class="CloneStateHead cloneBox">
										<div class="form-group">
											<label class="form-label">State Head Name</label>
											<input type="text" class="form-control" placeholder="Enter State Head Name" />
										</div>
										<div class="form-group">
											<label class="form-label">Primary Mobile No.</label>
											<input type="text" class="form-control" placeholder="Enter Primary Mobile No." />
										</div>
										<div class="form-group">
											<label class="form-label">Secondary Mobile No.</label>
											<input type="text" class="form-control" placeholder="Enter Secondary Mobile No." />
										</div>
										<div class="form-group">
											<label class="form-label">Email Address</label>
											<input type="text" class="form-control" placeholder="Enter Email Address" />
										</div>		
									</div>
								</div>
								<div class="form-group addMoreForm">
									<a href="javascript:void(0);" id="addMoreStateBtn"><i class="fa fa-plus-square"></i> Add More State Head</a>
								</div>
								<button type="submit" class="btn btn-default">Submit</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		
		</div>  
	</div>
	<!-- END CONTAINER -->
@endsection

