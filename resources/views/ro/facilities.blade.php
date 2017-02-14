@extends('layouts.main')
@section('content')
<!-- START CONTAINER -->
	<div class="container-widget">	
		<div class="row">
			<!-- Nominations -->
			<div class="col-md-12">
				<div class="panel panel-widget">
					<div class="panel-title pageTitle titleBtn clearfix">
						<span>Facilities</span>
					</div>
					<div class="panel-body heightWidget">
						<div class="candidateDetails clearfix cNoBorder">
							<div class="Info">
								<ul>
									<li><b>Web Casting:</b>@if($polling_facility->feasible_2g3g == 'Yes') Available | <a target="_blank" href="http://udsstream.com"><u>Watch Live</u></a> @else Not Available @endif</li>
									<li><b>Videography:</b>@if($polling_facility->videography == '1') Available @else Not Available @endif</li>
									<li><b>Micro Oberservers:</b>@if($polling_facility->crpf == '1') Available @else Not Available @endif</li>
									<li><b>CRPF:</b> @if($polling_facility->micro_observer == '1') Available @else Not Available @endif</li>
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