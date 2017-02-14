@extends('layouts.main')
@section('content')
	<div class="container-widget">  
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					<div class="panel-title pageTitle text-center">
						<span>Send SMS</span>
					</div>
					<div class="panel-body">
						<div class="noticeCandi">
							<form method="post" action="{{ url('deo/login-media-alerts') }}">
								<input type="hidden" name="_token" value="<?= csrf_token(); ?>">							
								
								<button type="submit" class="btn btn-default">Send to Media</button>
							</form>
						</div>
						<div class="noticeCandi">
							<form method="post" action="{{ url('deo/login-candidate-alerts') }}">
								<input type="hidden" name="_token" value="<?= csrf_token(); ?>">							
								
								<button type="submit" class="btn btn-default">Send to Candidate</button>
							</form>
						</div>
					</div>
				</div>
			</div>		
		</div>  
	</div>
@endsection

