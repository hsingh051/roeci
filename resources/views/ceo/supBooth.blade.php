@extends('layouts.main')
@section('content')
	<!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<!-- Nominations -->
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					<?php 
						if(!empty(Session::get('supBoothMsz'))){
							echo "<strong><span style='font-weight:700;color: #a94442;'>".Session::get('supBoothMsz')."</span></strong>";
		            		Session::forget('supBoothMsz');	
						}
	            	?>
					<div class="panel-title pageTitle text-center">
                        <span>Supervisor Booth</span>
                    </div>
                   
					<div class="panel-body">
						<div class="noticeCandi">

							<form enctype="multipart/form-data" method="post" action="{{url('ceo/supBoothSub') }}">
							<input type="hidden" name="_token" value="<?= csrf_token(); ?>">

								<div class="form-group">
									<label class="form-label">Excel Sheet</label>
									<input type="file" name="supViseBooth" class="form-control">
								</div>

								<button type="submit" class="btn btn-default">Send</button>
							</form>
						</div>
					</div>
				</div>
			</div>
			<!-- End Nominations -->
		
		</div>  
	</div>
	<!-- END CONTAINER -->
@endsection

