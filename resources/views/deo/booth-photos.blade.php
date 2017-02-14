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
						<div class="panel-btn">
							<a href="{{URL::previous()}}" class="btn btn-default">Back</a>
						</div>
					</div>
					<div class="panel-body">
						<div class="imageGallery">
							<ul class="clearfix">
								<li><a class="fancybox" href="<?php echo get_aws_images_url().'poll_booths/'.str_pad($state_id, 3, '0', STR_PAD_LEFT).'/'.str_pad($dist_code, 3, '0', STR_PAD_LEFT).'/AC'.str_pad($cons_code, 3, '0', STR_PAD_LEFT).'/'.str_pad($ps_id, 3, '0', STR_PAD_LEFT).'.jpg'; ?>" data-fancybox-group="gallery"><img src="<?php echo get_aws_images_url().'poll_booths/'.str_pad($state_id, 3, '0', STR_PAD_LEFT).'/'.str_pad($dist_code, 3, '0', STR_PAD_LEFT).'/AC'.str_pad($cons_code, 3, '0', STR_PAD_LEFT).'/'.str_pad($ps_id, 3, '0', STR_PAD_LEFT).'.jpg'; ?>" alt="" /></a></li>
							
							</ul>
						</div>
					</div>
				</div>
	
			</div>  
		</div>  
	</div>
	<!-- END CONTAINER -->
@endsection

