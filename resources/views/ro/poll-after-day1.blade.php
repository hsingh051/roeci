@extends('layouts.app')
@section('content')
    <!-- START CONTAINER -->
	<div class="container-widget">
		
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					<div class="panel-title pageTitle text-center">
						Polling + 1Day
					</div>
					<div class="panel-body">
						<div class="noticeCandi">
							<form method="post">
								<div class="form-group">
									<label class="form-label">Consolidated Report of Dispatch Center</label>
									<input type="file" class="form-control" />
								</div>
								<div class="form-group">
									<label class="form-label">Scrutiny Report</label>
									<input type="file" class="form-control" />
								</div>
								<div class="form-group">
									<label class="form-label" style="display:block;">All Parties Reached</label>
									<div class="checkbox checkbox-inline checkbox-primary">
										<input type="checkbox" id="parties-reached-yes" value="option1">
										<label for="parties-reached-yes"> Yes </label>
									</div>
									<div class="checkbox checkbox-inline checkbox-primary">
										<input type="checkbox" id="parties-reached-no" value="option1">
										<label for="parties-reached-no"> No </label>
									</div>
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

