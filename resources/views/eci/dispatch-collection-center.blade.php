@extends('layouts.main')
@section('content')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC90VHLYFtiZmut_W3ELil_aLYtej_QmSk"></script>
<?php
$distCodeCheck= (isset($_GET['dist_code']))? $_GET['dist_code'] : ""; 
$consCodeCheck= (isset($_GET['cons_code']))? $_GET['cons_code'] : "";
$getSelectType= (isset($_GET['centerType']))? $_GET['centerType'] : "";
$encryptConsCode= (isset($encryptCons))? $encryptCons : "";
$encryptDistCode= (isset($encryptDist))? $encryptDist : "";
$consList= (isset($constituency))? $constituency : "";
$dispatch=eci_encrypt('DISPATCH');
$collection=eci_encrypt('COLLECTION');
$selectedType= (isset($consTypeEnc))? $consTypeEnc : "";
?>
    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">
				<div class="searchBar">
					<form class="topSearchForm" enctype="multipart/form-data" method="get" action="{{url('eci/dispatchCollectionCenterSub') }}">
					<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
						<div class="form-group{{ $errors->has('dist_code') ? ' has-error' : '' }}">
							<select name="dist_code" class="form-control poll_dist_code">
									<option value="">Select district</option>
								@foreach($district as $districts)
									<?php $distCode=eci_encrypt($districts->dist_code); ?>
									<option value="{{$distCode}}" <?php if($distCode==$encryptDistCode){ echo "selected"; } ?> >{{ $districts->dist_name }}</option>
								@endforeach
							</select>
							@if ($errors->has('dist_code'))
							<span class="help-block">
								<strong>{{ $errors->first('dist_code') }}</strong>
							</span>
							@endif
						</div>
						<div class="form-group{{ $errors->has('cons_code') ? ' has-error' : '' }}">
							<select name="cons_code" class="form-control poll_cons_code">
								<option value="">Select Constituency</option>
								@if($consList)
									@foreach($consList as $constituencies)
										<?php $consCode=eci_encrypt($constituencies->cons_code); ?>
										<option value="{{$consCode}}" <?php if($consCode==$encryptConsCode){ echo "selected"; } ?> >{{ $constituencies->cons_name }}</option>
									@endforeach
								@endif
							</select>
							@if ($errors->has('cons_code'))
							<span class="help-block">
								<strong>{{ $errors->first('cons_code') }}</strong>
							</span>
							@endif
						</div>
						<div class="form-group{{ $errors->has('centerType') ? ' has-error' : '' }}">
							<select name="centerType" class="form-control" id="centerType">
								<option value="">Select Center</option>
								<option value="{{$dispatch}}" <?php if($selectedType==$dispatch){echo "selected";} ?>>Dispatch Center</option>
								<option value="{{$collection}}" <?php if($selectedType==$collection){echo "selected";} ?>>Collection Center</option>
							</select>
							@if ($errors->has('centerType'))
							<span class="help-block">
								<strong>{{ $errors->first('centerType') }}</strong>
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
		<?php 
		if(($distCodeCheck!=="") && ($consCodeCheck!=="") && ($getSelectType!=="") || ($encryptConsCode!=="") && ($encryptDistCode!=="") && ($selectedType!=="")) { 
			
			if($selectedType==$dispatch){ ?>
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-widget heightWidget">
						<div class="panel-title pageTitle titleBtn clearfix">
							Dispatch Center
						</div>
						@if($centerDetail)
						<div class="panel-body">
							<div class="voterDetail clearfix">
								<div class="vInfo">
									<div class="voterPro">
										<ul>
											<li><b>Dispatch Center Name:</b>{{ $centerDetail->dispatch_name }}</li>
											<li><b>Dispatch Center Address:</b>{{ $centerDetail->dispatch_address }}</li>
										</ul>
									</div>
								</div>
								<div class="vMap">
									<div id="polling-detail-map"></div>
								</div>	
							</div>
						</div>
						@else
						<div class="panel-body">
							<div class="voterDetail clearfix">
								<div class="vInfo">
									<div class="voterPro">
										<p>No Records Found</p>
									</div>
								</div>	
							</div>
						</div>
						@endif
					</div>	
				</div>  
			</div>
			@if($centerDetail)
			<script>
				var myCenter = new google.maps.LatLng(<?php echo $centerDetail->dispatch_latitude; ?>, <?php echo $centerDetail->dispatch_longitude; ?>);
				var mapCanvas = document.getElementById("polling-detail-map");
				var mapOptions = {center: myCenter, zoom: 12};
				var map = new google.maps.Map(mapCanvas, mapOptions);
				var infowindow = new google.maps.InfoWindow();
				var marker = new google.maps.Marker({position:myCenter});
				var contentString = "<?php echo $centerDetail->dispatch_address; ?>";
				var infowindow = new google.maps.InfoWindow({
				content: contentString
				});
				marker.addListener('click', function() {
					infowindow.open(map, marker);
				});
				marker.setMap(map);
			</script>
			@endif
			<?php
			}
			if($selectedType==$collection) {
			?>
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-widget heightWidget">
						<div class="panel-title pageTitle titleBtn clearfix">
							Collection Center
						</div>
						@if($centerDetail)
						<div class="panel-body">
							<div class="voterDetail clearfix">
								<div class="vInfo">
									<div class="voterPro">
										<ul>
											<li><b>Collection Center Name:</b>{{ $centerDetail->collection_name }}</li>
											<li><b>Collection Center Address:</b>{{ $centerDetail->collection_address }}</li>
										</ul>
									</div>
								</div>
								<div class="vMap">
									<div id="polling-detail-map"></div>
								</div>	
							</div>
						</div>
						@else
						<div class="panel-body">
							<div class="voterDetail clearfix">
								<div class="vInfo">
									<div class="voterPro">
										<p>No Records Found</p>
									</div>
								</div>	
							</div>
						</div>
						@endif
					</div>	
				</div>  
			</div>
			@if($centerDetail)
			<script>
				var myCenter = new google.maps.LatLng(<?php echo $centerDetail->collection_latitude; ?>, <?php echo $centerDetail->collection_longitude; ?>);
				var mapCanvas = document.getElementById("polling-detail-map");
				var mapOptions = {center: myCenter, zoom: 12};
				var map = new google.maps.Map(mapCanvas, mapOptions);
				var infowindow = new google.maps.InfoWindow();
				var marker = new google.maps.Marker({position:myCenter});
				var contentString = "<?php echo $centerDetail->collection_address; ?>";
				var infowindow = new google.maps.InfoWindow({
				content: contentString
				});
				marker.addListener('click', function() {
					infowindow.open(map, marker);
				});
				marker.setMap(map);
			</script>
			@endif
			<?php
			} 
		}
		?>
	</div>
	<!-- END CONTAINER -->
@endsection