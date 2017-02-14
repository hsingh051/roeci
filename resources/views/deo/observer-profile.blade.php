@extends('layouts.main')
@section('content')
	<script>
		$(document).ready(function() {
			$('#example0').DataTable();
		});
	</script>
    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget">
					<div class="panel-title pageTitle titleBtn clearfix">
					</div>
					<div class="panel-body oberserverPro">
						<div class="candidateDetails clearfix">
							<div class="Info">
								<ul>
									<li><b>Name:</b> {{ $observerdata->name }}</li>
									<li><b>Contact No:</b> {{ $observerdata->phone }}</li>
									<li><b>Email:</b> {{ $observerdata->email }}</li>
									<li><b>Address:</b> {{ $observerdata->address }}</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
	
			</div>  
		</div>  
	</div>
	<!-- END CONTAINER -->
@endsection

