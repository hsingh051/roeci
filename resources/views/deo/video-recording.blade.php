@extends('layouts.main')
@section('content')
<?php
	$selectedCons= (isset($_GET['cons_code']))? $_GET['cons_code'] : "";
	$encryptConsCode= (isset($encConsCode))? $encConsCode : "";
	$consList= (isset($constituency))? $constituency : "";
?>
	<!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">
				<div class="searchBar">
					<form class="topSearchForm" enctype="multipart/form-data" method="get" action="{{url('deo/video-recording-sub') }}">
					<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
						<div class="form-group{{ $errors->has('cons_code') ? ' has-error' : '' }}">
							<select name="cons_code" class="form-control" id="cons_code">
								<option value="">Select Assembly Constituency</option>
								@foreach($consList as $data)
								<?php
									$cons_code=eci_encrypt($data->cons_code);
								?>
								<option value="{{ $cons_code }}" <?php if($encryptConsCode == $cons_code) { echo"selected"; } ?> >{{ $data->cons_name }}
								</option>
								@endforeach
							</select>
							@if ($errors->has('cons_code'))
							<span class="help-block">
								<strong>{{ $errors->first('cons_code') }}</strong>
							</span>
							@endif
						</div>
						
						<div class="form-group">
							<button type="submit" class="btn btn-default">Submit</button>
						</div>						
					</form>
				</div>
			</div>
		</div>  
		<?php if(($selectedCons!=="") || ($encryptConsCode!=="")) { ?>
		<div class="row">
			<!-- Nominations -->
			<div class="col-md-12">
				<div class="panel panel-widget">
					@if(Session::has('videoMsz'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('videoMsz') }}</p>
					@endif
					<div class="panel-title pageTitle titleBtn clearfix">
						<span>Video Recording</span>
						<div class="panel-btn">
							<a href="{{url('deo/add-afterPoll-video') }}" class="btn btn-default">Add New Video</a>
						</div>
					</div>
					<?php $countVideo=count($proVideo); if($countVideo>0) { ?>
						<div class="panel-body">
							<div class="videoList">
								<ul>
									@foreach ($proVideo as $proVideos)
										<li>
											<div class="Icon">
												<a href="{{ $proVideos->videoUrl }}">
													<img src="{{ URL::asset('images/vidIcon.jpg')}}" />
												</a>
											</div>
											<p>{{ $proVideos->videoDescription }}</p>
										</li>
									@endforeach
								</ul>
							</div>
						</div>
					<?php } else { ?>
						<div class="panel-body">
							<p>No Record Found.</p>
						</div>
					<?php } ?>
				</div>
			</div>
			<!-- End Nominations -->
		</div>  
		<?php } ?>
	</div>
	<!-- END CONTAINER -->	
@endsection
