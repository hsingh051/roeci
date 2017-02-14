@extends('layouts.main')
@section('content')
	<script type="text/javascript" src="{{ URL::asset('js/jquery.fancybox.js?v=2.1.5')}}"></script>
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('css/fancybox/jquery.fancybox.css?v=2.1.5')}}" media="screen" />
	
	<script type="text/javascript">
		$(document).ready(function(){
			$('.fancybox').fancybox();
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
							<ul class="clearfix">
								<li><a class="fancybox" href="{{ URL::asset('images/poll_booths/053/011/AC057/001.jpg')}}" data-fancybox-group="gallery"><img src="{{ URL::asset('images/poll_booths/053/011/AC057/001.jpg')}}" alt="" /></a></li>

								<li><a class="fancybox" href="{{ URL::asset('images/poll_booths/053/011/AC057/001.jpg')}}" data-fancybox-group="gallery"><img src="{{ URL::asset('images/poll_booths/053/011/AC057/001.jpg')}}" alt="" /></a></li>
							
							</ul>
						</div>
					</div>
				</div>
	
			</div>  
		</div>  
	</div>
	<!-- END CONTAINER -->
@endsection

