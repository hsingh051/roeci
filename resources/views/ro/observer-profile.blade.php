@extends('layouts.main')
@section('content')
	<!-- START CONTAINER -->
	<div class="container-widget">		
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget oberserverPro">
					<div class="panel-title pageTitle titleBtn clearfix">
						<span>{{ $observerdata->type }}</span>
						<!-- <div class="panel-btn">
							<a href="{{ url('/ro/election-observers') }}" class="btn btn-default">Back</a>
						</div> -->
					</div>
					<div class="panel-body">
						<div class="candidateDetails clearfix">
							<div class="Info">
								<ul>
									<li><b>Name:</b> <?php echo $observerdata->name;?></li>
									<li><b>Contact No:</b> <?php echo $observerdata->phone;?></li>
									<li><b>Email:</b> <?php echo $observerdata->email;?></li>
									<li><b>Address:</b><?php echo $observerdata->address;?></li>
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

