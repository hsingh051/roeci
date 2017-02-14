@extends('layouts.main')
@section('content')
<?php
	$state=Auth::user()->state_id;
	$dist = Auth::user()->dist_code;
?>
	<div class="container-widget">  
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					<div class="panel-title pageTitle titleBtn text-center">
						<span>Add New Video</span>
					</div>
					<div class="panel-body">
						<div class="noticeCandi">
							<form enctype="multipart/form-data" method="post" action="{{url('deo/add-afterPoll-video-sub') }}" >
							<input type="hidden" name="_token" value="<?= csrf_token(); ?>">

								<div class="form-group{{ $errors->has('consCode') ? ' has-error' : '' }}">
									<label class="form-label">Select Constituency</label>
									<select name="consCode" type="file" class="form-control"/>
										<?php echo get_constituencies($state,$dist); ?>
									</select>
									@if ($errors->has('consCode'))
										<span class="help-block">
											<strong>{{ $errors->first('consCode') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('videoUrl') ? ' has-error' : '' }}">
									<label class="form-label">Video URL</label>
									<input type="text" name="videoUrl" class="form-control" placeholder="Video URL" value="{{ old('videoUrl') }}"/>
									@if ($errors->has('videoUrl'))
										<span class="help-block">
											<strong>{{ $errors->first('videoUrl') }}</strong>
										</span>
									@endif
								</div>

								<div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
									<label class="form-label">Description</label>
									<textarea name="description" class="form-control" placeholder="Description">{{ old('description') }}</textarea>
									@if ($errors->has('description'))
										<span class="help-block">
											<strong>{{ $errors->first('description') }}</strong>
										</span>
									@endif
								</div>

								<button type="submit" class="btn btn-default">Submit</button>
							</form>
						</div>
					</div>
				</div>
			</div>		
		</div>  
	</div>
	<!-- END CONTAINER -->
@endsection

