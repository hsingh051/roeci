@extends('layouts.main')
@section('content')
<!-- google maps api -->
<?php //print_r(count($polling_stations)); die;?>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC90VHLYFtiZmut_W3ELil_aLYtej_QmSk"></script>
	

    <!-- START CONTAINER -->
	<div class="container-widget">	  
		<div class="row">
			<!-- Nominations -->
			<div class="col-md-12">
				<div class="panel panel-widget">
					<div class="panel-title pageTitle titleBtn clearfix">
						<span>POLLING STATIONS MAP</span>
						<!-- <div class="panel-btn"><a href="{{URL::previous()}}" class="btn btn-default">Back</a></div> -->
					</div>
					<div class="panel-body">
						<div class="PSMap">
							<div id="polling-stations-map"></div>
							<div class="mapPointers">
								<ul>
									<li>
										<a href="{{ url('/')}}/ro/polling-stations-map/Vulnerable">
											<img src="{{ URL::asset('images/vulnerableMarker.png')}}" /> Vulnerable
										</a>
									</li>
									<li>
										<a href="{{ url('/')}}/ro/polling-stations-map/Critical">
											<img src="{{ URL::asset('images/criticalMarker.png')}}" /> Critical
										</a>
									</li>
									<li>
										<a href="{{ url('/')}}/ro/polling-stations-map/Auxiliary">
											<img src="{{ URL::asset('images/auxiliaryMarker.png')}}" /> Auxiliary
										</a>
									</li>
									<li>
										<a href="{{ url('/')}}/ro/polling-stations-map/Model">
											<img src="{{ URL::asset('images/modelMarker.png')}}" /> Model
										</a>
									</li>
									<li>
										<a href="{{ url('/')}}/ro/polling-stations-map/Notified">
											<img src="{{ URL::asset('images/notifiedMarker.png')}}" /> Notified
										</a>
									</li>
								</ul>
							</div>
						</div>							
					</div>
				</div>
			</div>
			<!-- End Nominations -->
		
		</div>  
	</div>
	
	<script type="text/javascript">
	<?php 
		$notified = array();
		$auxiliary = array();
		$vulnerable = array();
		$critical = array();
		$model = array();
		$latitudes = array();
		$longitudes = array();
		$i= 1;
		if(count($polling_stations)>=1){
			foreach ($polling_stations as $value) {
				if($value->poll_type == "Auxiliary"){
					$auxiliary[] .= "['".trim($value->poll_building)."', ".trim($value->latitude).", ".trim($value->longitude).", ".$i."]";
				}elseif($value->poll_type == "Vulnerable"){
					$vulnerable[] .= "['".trim($value->poll_building)."', ".trim($value->latitude).", ".trim($value->longitude).", ".$i."]";
				}elseif($value->poll_type == "Critical"){
					$critical[] .= "['".trim($value->poll_building)."', ".trim($value->latitude).", ".trim($value->longitude).", ".$i."]";
				}elseif($value->poll_type == "Model"){
					$model[] .= "['".trim($value->poll_building)."', ".trim($value->latitude).", ".trim($value->longitude).", ".$i."]";
				}else{
					$notified[] .= "['".trim($value->poll_building)."', ".trim($value->latitude).", ".trim($value->longitude).", ".$i."]";
				}
				$i++;

				$latitudes[] = $value->latitude;
				$longitudes[] = $value->longitude;
			}
			$clat = (float)number_format(array_sum($latitudes)/count($latitudes),4);
		    $clong = (float)number_format(array_sum($longitudes)/count($longitudes),4);
		}else{
			$clat = "30.9050";
			$clong = "75.8469";
		}
		
		//dd(implode(',',$vulnerable));
		//dd($notified);
	?>
		var auxiliary_poll = [
							//['Bharat Nagar', 30.9050, 75.8469, 4],
							<?php echo implode(",",$auxiliary);?>							
						];
		var vulnerable_poll = [
							//['Model Gram', 30.9052, 75.8745, 5],
							<?php echo implode(",",$vulnerable);?>	
						];
		var critical_poll = [							
							//['Ganesh Nagar', 30.8978, 75.8389, 3],
							<?php echo implode(",",$critical);?>							
						];
		var model_poll = [
							<?php echo implode(",",$model);?>
							
						];
		var notified_poll = [
								<?php echo implode(",",$notified);?>
							];
		//alert(notified_poll);
		var map = new google.maps.Map(document.getElementById('polling-stations-map'), {
			zoom: 11,
			center: new google.maps.LatLng(<?php echo $clat;?>, <?php echo $clong;?>),
			mapTypeId: google.maps.MapTypeId.ROADMAP
		});

		var infowindow = new google.maps.InfoWindow();

		var marker, i;

		//url('/').'/images/observer/'.$getobserver->profile_image;
		var iconBase = "<?php echo url('/');?>/images/";
		//alert(vulnerable_poll);
		for (i = 0; i < auxiliary_poll.length; i++) {  
			marker = new google.maps.Marker({
				position: new google.maps.LatLng(auxiliary_poll[i][1], auxiliary_poll[i][2]),
				map: map,
				icon: iconBase + 'auxiliaryMarker.png'
			});

			google.maps.event.addListener(marker, 'click', (function(marker, i) {
				return function() {
					infowindow.setContent(auxiliary_poll[i][0]);
					infowindow.open(map, marker);
				}
			})(marker, i));
		}
		
		for (i = 0; i < critical_poll.length; i++) {  
			marker = new google.maps.Marker({
				position: new google.maps.LatLng(critical_poll[i][1], critical_poll[i][2]),
				map: map,
				icon: iconBase + 'criticalMarker.png'
			});

			google.maps.event.addListener(marker, 'click', (function(marker, i) {
				return function() {
					infowindow.setContent(critical_poll[i][0]);
					infowindow.open(map, marker);
				}
			})(marker, i));
		}
		for (i = 0; i < model_poll.length; i++) {  
			marker = new google.maps.Marker({
				position: new google.maps.LatLng(model_poll[i][1], model_poll[i][2]),
				map: map,
				icon: iconBase + 'modelMarker.png'
			});

			google.maps.event.addListener(marker, 'click', (function(marker, i) {
				return function() {
					infowindow.setContent(model_poll[i][0]);
					infowindow.open(map, marker);
				}
			})(marker, i));
		}
		for (i = 0; i < notified_poll.length; i++) {  
			marker = new google.maps.Marker({
				position: new google.maps.LatLng(notified_poll[i][1], notified_poll[i][2]),
				map: map,
				icon: iconBase + 'notifiedMarker.png'
			});

			google.maps.event.addListener(marker, 'click', (function(marker, i) {
				return function() {
					infowindow.setContent(notified_poll[i][0]);
					infowindow.open(map, marker);
				}
			})(marker, i));
		}
		for (i = 0; i < vulnerable_poll.length; i++) {  
			//alert(vulnerable_poll[i][1]);
			//alert(vulnerable_poll[i][2]);
			marker = new google.maps.Marker({
				position: new google.maps.LatLng(vulnerable_poll[i][1], vulnerable_poll[i][2]),
				map: map,
				icon: iconBase + 'vulnerableMarker.png'
			});

			google.maps.event.addListener(marker, 'click', (function(marker, i) {
				return function() {
					infowindow.setContent(vulnerable_poll[i][0]);
					infowindow.open(map, marker);
				}
			})(marker, i));
		}
	</script>
	<!-- END CONTAINER -->
	
@endsection