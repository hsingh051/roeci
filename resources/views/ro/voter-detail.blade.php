@extends('layouts.main')
@section('content')
	<script type="text/javascript" src="{{ URL::asset('js/jquery.fancybox.js?v=2.1.5')}}"></script>
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('css/fancybox/jquery.fancybox.css?v=2.1.5')}}" media="screen" />	
	<script type="text/javascript">
		$(document).ready(function(){
			$('.fancybox').fancybox();
		});
	</script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC90VHLYFtiZmut_W3ELil_aLYtej_QmSk"></script>
	<script>
		$(document).ready(function() {
			$('#example0').DataTable();
		});
	</script>
    <!-- START CONTAINER -->
	<div class="container-widget">		
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					<div class="panel-title pageTitle titleBtn clearfix">
						<div class="panel-btn">
							<!-- <a href="{{ url('/ro/generate-voter-slip') }}" class="btn btn-default">Generate Voter Slip</a> -->
							<!-- <a href="{{URL::previous()}}" class="btn btn-default">Back</a> -->
						</div>
					</div>
					<div class="panel-body">
						<div class="voterDetail clearfix">
							<div class="vInfo">
								<div class="voterPro">
									<ul>
										<li><b>Voter Name:</b>{{ $voterDetail->fm_nameEn }} {{ $voterDetail->LastNameEn }}</li>
										<li><b>AC No & Name:</b>{{ $voterDetail->cons_code }} & {{ $pollDayDetail->cons_name }} </li>
										<li><b>Sex:</b><?php if( ($voterDetail->sex)=="M" ){ echo "Male"; }else{ echo "Female"; } ?></li>
										<li><b>EPIC Number:</b>{{ $voterDetail->idcardNo }}</li>
										<li>
										<?php
											if( ($voterDetail->rlnType)=="F" ){
											echo "<b>Father Name:</b>";
											}
											if( ($voterDetail->rlnType)=="H" ){
											echo "<b>Husband Name:</b>";
											}
										?>
										{{ $voterDetail->rln_Fm_NmEn }} {{ $voterDetail->rln_L_NmEn }}
										</li>
										<li><b>Part No:</b> {{ $voterDetail->ps_id }}</li>
										<li><b>Serial No:</b> {{ $voterDetail->slnoinpart }}</li>

										<li><b>Polling Booth Address:</b> 
											{{ $pollDayDetail->poll_building }}, {{ $pollDayDetail->locality }}
										</li>
									</ul>
								</div>
							</div>
							<div class="vMap">
								<div id="polling-detail-map"></div>
							</div>	
						</div>
						<div class="imageGallery">
							<ul class="clearfix">
								<li><a class="fancybox" href="<?php echo get_aws_images_url().'poll_booths/'.str_pad($voterDetail->state_id, 3, '0', STR_PAD_LEFT).'/'.str_pad($voterDetail->dist_code, 3, '0', STR_PAD_LEFT).'/AC'.str_pad($voterDetail->cons_code, 3, '0', STR_PAD_LEFT).'/'.str_pad($voterDetail->ps_id, 3, '0', STR_PAD_LEFT).'.jpg'; ?>" data-fancybox-group="gallery">
								<img src="<?php echo get_aws_images_url().'poll_booths/'.str_pad($voterDetail->state_id, 3, '0', STR_PAD_LEFT).'/'.str_pad($voterDetail->dist_code, 3, '0', STR_PAD_LEFT).'/AC'.str_pad($voterDetail->cons_code, 3, '0', STR_PAD_LEFT).'/'.str_pad($voterDetail->ps_id, 3, '0', STR_PAD_LEFT).'.jpg'; ?>" alt="" />
								</a>
								</li>
							
							</ul>
						</div>
					</div>
				</div>	
			</div>  
		</div>  
	</div>
	<script>
	  var myCenter = new google.maps.LatLng(<?php echo $pollDayDetail->latitude;?>, <?php echo $pollDayDetail->longitude;?>);
	  var mapCanvas = document.getElementById("polling-detail-map");
	  var mapOptions = {center: myCenter, zoom: 12};
	  var map = new google.maps.Map(mapCanvas, mapOptions);
	  var infowindow = new google.maps.InfoWindow();
	  var marker = new google.maps.Marker({position:myCenter});


	    var contentString = "<?php echo $pollDayDetail->poll_building.", ".$pollDayDetail->locality;?>";

	    var infowindow = new google.maps.InfoWindow({
	      content: contentString
	    });

	    
	    marker.addListener('click', function() {
	      infowindow.open(map, marker);
	    });
      

	  marker.setMap(map);
	  
	</script>
	<!-- END CONTAINER -->
@endsection

