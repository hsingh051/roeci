@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					<div class="panel-title pageTitle text-center">ALLOT SYMBOLS</div>
					<div class="panel-body">
						<div class="noticeCandi">
							<form method="post">
								<div class="form-group">
									<label class="form-label">Choose Party</label>
									<select class="form-control">
										<option>Select Party</option>
										<option>Shiromani Akali Dal</option>
										<option>Indian National Congress</option>
										<option>Aam Aadmi Party</option>
										<option>BJP</option>
										<option>Independent politician</option>
									</select>
								</div>
								<div class="form-group">
									<label class="form-label">Choose Candidate</label>
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
									<label class="form-label">Choose Candidate</label>
									<input type="file" class="form-control" />
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

