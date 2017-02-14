@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
	<div class="container-widget">  
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					<div class="panel-title pageTitle text-center">SEND NOTICE TO CANDIDATE</div>
					<div class="panel-body">
						<div class="noticeCandi">
							<form method="post">
								<div class="form-group">
									<label class="form-label">Candidate List</label>
									<select class="form-control">
										<option>Select Candidate</option>
										<option>Ravneet Singh Bittu</option>
										<option>Sukhbir Singh Badal</option>
										<option>Bikram Singh Majithia</option>
										<option>Manpreet Singh Ayali</option>
										<option>Ranjit Singh Dhillon</option>
										<option>Captain Amarinder Singh</option>
										<option>Simarjit Singh Bains</option>
										<option>Harvinder Singh Phoolka</option>
									</select>
								</div>
								<div class="form-group">
									<label class="form-label">Message</label>
									<textarea class="form-control" placeholder="Type Your Message Here"></textarea>
								</div>
								<button type="submit" class="btn btn-default">Send</button>
							</form>
						</div>
					</div>
				</div>
			</div>		
		</div>  
	</div>
	<!-- END CONTAINER -->
@endsection

