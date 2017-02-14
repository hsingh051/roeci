@extends('layouts.main')
@section('content')
	<script type="text/javascript" src="{{ URL::asset('js/jquery.fancybox.js?v=2.1.5')}}"></script>
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('css/fancybox/jquery.fancybox.css?v=2.1.5')}}" media="screen" />
	
	<script type="text/javascript">
		$(document).ready(function(){
			$('.fancybox').fancybox();
		});

		$(document).ready(function(){
			var candidatePic = $('.candidateList li a > img').width();
			$('.candidateList li a > img').css('height', candidatePic);
		});
	</script>
	
    <!-- START CONTAINER -->
	<div class="container-widget">		
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					<div class="panel-title pageTitle titleBtn clearfix">
						<!-- <div class="panel-btn">
							<a href="{{URL::previous()}}" class="btn btn-default">Back</a>
						</div> -->
					</div>
					<div class="panel-body">
						<div class="imageGallery">
							<ul class="candidateList clearfix">
								<?php
									foreach($images as $pollImages12)
									{
										$pollImages12->Image1;
										if(isset($pollImages12->Image1))
										{
								?>
									<li>
										<a class="fancybox" href="data:image/gif;base64,<?php echo $pollImages12->Image1; ?>" data-fancybox-group="gallery">
											<img src="data:image/gif;base64,<?php echo $pollImages12->Image1; ?>">
										</a>
									</li>
								<?php
										}
										if(isset($pollImages12->Image2))
										{
								?>
									<li>
										<a class="fancybox" href="data:image/gif;base64,<?php echo $pollImages12->Image2; ?>" data-fancybox-group="gallery">
											<img src="data:image/gif;base64,<?php echo $pollImages12->Image2; ?>">
										</a>
									</li>
								<?php
										}
										if(isset($pollImages12->Image3))
										{
								?>
									<li>
										<a class="fancybox" href="data:image/gif;base64,<?php echo $pollImages12->Image3; ?>" data-fancybox-group="gallery">
											<img src="data:image/gif;base64,<?php echo $pollImages12->Image3; ?>">
										</a>
									</li>
								<?php
										}
										if(isset($pollImages12->Image4))
										{
								?>
									<li>
										<a class="fancybox" href="data:image/gif;base64,<?php echo $pollImages12->Image4; ?>" data-fancybox-group="gallery">
											<img src="data:image/gif;base64,<?php echo $pollImages12->Image4; ?>">
										</a>
									</li>
								<?php
										}
										if(isset($pollImages12->Image5))
										{
								?>
									<li>
										<a class="fancybox" href="data:image/gif;base64,<?php echo $pollImages12->Image5; ?>" data-fancybox-group="gallery">
											<img src="data:image/gif;base64,<?php echo $pollImages12->Image5; ?>">
										</a>
									</li>
								<?php
										}
										if(isset($pollImages12->Image6))
										{
								?>
									<li>
										<a class="fancybox" href="data:image/gif;base64,<?php echo $pollImages12->Image6; ?>" data-fancybox-group="gallery">
											<img src="data:image/gif;base64,<?php echo $pollImages12->Image6; ?>">
										</a>
									</li>
								<?php
										}
										if(isset($pollImages12->Image7))
										{
								?>
									<li>
										<a class="fancybox" href="data:image/gif;base64,<?php echo $pollImages12->Image7; ?>" data-fancybox-group="gallery">
											<img src="data:image/gif;base64,<?php echo $pollImages12->Image7; ?>">
										</a>
									</li>
								<?php
										}
									}
								?>
							</ul>
						</div>
					</div>
				</div>	
			</div>  
		</div>  
	</div>
	<!-- END CONTAINER -->
@endsection

