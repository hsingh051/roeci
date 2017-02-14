@extends('layouts.main')
@section('content')
<?php 
	$bid = eci_encrypt($polling_detail->bid);
?>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC90VHLYFtiZmut_W3ELil_aLYtej_QmSk"></script>
    <!-- START CONTAINER -->
	<div class="container-widget">	  
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget">
					<div class="panel-title pageTitle titleBtn clearfix">
						<span>POLLING DETAIL</span>
						<div class="panel-btn formRightBtns">
							<a href="{{url('deo/booth-awareness-group') }}/{{ $bid }}" class="btn btn-default">Booth Awareness Group</a>
							<a href="{{url('deo/polling-parties-details') }}/{{ $bid }}" class="btn btn-default">Polling Party</a>
							<a href="{{url('deo/booth-photos') }}/{{ $bid }}" class="btn btn-default">Photos</a>
							<a href="{{URL::previous()}}" class="btn btn-default">Back</a>
						</div>
					</div>
					<div class="panel-body">
						<div class="PSMap">
							<div id="polling-detail-map"></div>
						</div>					
					</div>
				</div>
			</div>			
		</div>  
	</div>
	<script>
	  var myCenter = new google.maps.LatLng(<?php echo $polling_detail->latitude;?>, <?php echo $polling_detail->longitude;?>);
	  var mapCanvas = document.getElementById("polling-detail-map");
	  var mapOptions = {center: myCenter, zoom: 12};
	  var map = new google.maps.Map(mapCanvas, mapOptions);
	  var infowindow = new google.maps.InfoWindow();
	  var marker = new google.maps.Marker({position:myCenter});


	    var contentString = "<?php echo $polling_detail->poll_building;?>";

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