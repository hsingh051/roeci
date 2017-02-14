@extends('layouts.main')
@section('content')
	<!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<!-- Nominations -->
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					<div class="panel-title pageTitle titleBtn text-center">
						<span>Add New DEO</span>
						<a href="{{ url('/ceo/addDeoForm') }}" class="btn btn-default formBackBtn">Back</a>
					</div>
                    <?php 
						if(!empty(Session::get('addCeoMsz'))){
							echo "<strong><span style='font-weight:700;color: #a94442;'>".Session::get('addCeoMsz')."</span></strong>";
		            		Session::forget('addCeoMsz');	
						}
		            ?>
					<div class="panel-body">
						<div class="noticeCandi">

							<form enctype="multipart/form-data" method="post" id="adddeocsv" action="{{url('ceo/addDeoSub') }}">
							<input type="hidden" name="_token" value="<?= csrf_token(); ?>">

								<!-- <div class="form-group">
									<label class="form-label">DEO Name</label>
									<input type="text" name="deoName" class="form-control">	
								</div>

								<div class="form-group">
									<label class="form-label">District</label>
									<select class="form-control" name="deoDist">
									   <option>Select Party</option>
										
									</select>
								</div>

								<div class="form-group">
									<label class="form-label">Address</label>
									<input type="text" name="deoAddress" class="form-control">
								</div>

								<div class="form-group">
									<label class="form-label">Email id</label>
									<input type="text" name="deoEmail" class="form-control">
								</div>

								<div class="form-group">
									<label class="form-label">Phone Number</label>
									<input type="text" name="deoPhone" class="form-control">
								</div>

								<div class="form-group">
									<label class="form-label">Password</label>
									<input type="text" name="deoPW" class="form-control">
								</div> -->

								<div class="form-group{{ $errors->has('filename') ? ' has-error' : '' }}">
									<label class="form-label">Excel</label>
									<input type="file" name="filename" class="form-control">
									@if ($errors->has('filename'))
										<span class="help-block">
											<strong>{{ $errors->first('filename') }}</strong>
										</span>
									@endif
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
<script type="text/javascript">
	$(document).ready(function(){
		$("#adddeocsv").validate({
			rules: {
		        filename: {
		            required: true, 
		            extension: "csv"
		        }
		    },
		    messages: {
		        filename: {
		            extension: 'Please upload a csv file!' 
		        }
		    }
		});
	});
</script>
	<!-- END CONTAINER -->
@endsection

