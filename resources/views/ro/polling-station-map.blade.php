@extends('layouts.app')
@section('content')
    <!-- START CONTAINER -->
	<div class="container-widget">	  
		<div class="row">
			<!-- Nominations -->
			<div class="col-md-12">
				<div class="panel panel-widget">
					<div class="panel-title pageTitle">POLLING STATIONS MAP</div>
					<div class="panel-body">
						<div class="PSMap">
							<h3>Ludhiana East</h3>
							<img src="{{ URL::asset('images/mapPic.jpg')}}" />
						</div>							
					</div>
				</div>
			</div>
			<!-- End Nominations -->
		
		</div>  
	</div>
	<!-- END CONTAINER -->
@endsection

