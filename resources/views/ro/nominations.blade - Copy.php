@extends('layouts.app')
@section('content')
    <!-- START CONTAINER -->
	<div class="container-widget">
  
		<div class="row">
			<!-- Nominations -->
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					<div class="panel-title pageTitle">Nominations</div>
					<div class="panel-body">
						<ul class="evmList">
							<li><a href="{{ url('/candidate-list') }}">View Nomination List Of The Candidate</a></li>
							<li><a href="{{ url('/send-notice-candidate') }}">Send Notice To Candidate</a></li>
							<li><a href="{{ url('/allot-symbols') }}">Allot Symbols</a></li>
						</ul>
					</div>
				</div>
			</div>
			<!-- End Nominations -->		
		</div>  
	</div>
	<!-- END CONTAINER -->
@endsection

