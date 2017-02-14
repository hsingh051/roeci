var locations = [
	['Bharat Nagar', 30.9050, 75.8469, 4],
	['Model Gram', 30.9052, 75.8745, 5],
	['Ganesh Nagar', 30.8978, 75.8389, 3],
	['Niran Kari Nagar', 30.8955, 75.8628, 2],
	['Brown Road', 30.909, 75.8601, 1]
];

var map = new google.maps.Map(document.getElementById('polling-stations-map'), {
	zoom: 14,
	center: new google.maps.LatLng(30.9010, 75.8573),
	mapTypeId: google.maps.MapTypeId.ROADMAP
});

var infowindow = new google.maps.InfoWindow();

var marker, i;

for (i = 0; i < locations.length; i++) {  
	marker = new google.maps.Marker({
		position: new google.maps.LatLng(locations[i][1], locations[i][2]),
		map: map
	});

	google.maps.event.addListener(marker, 'click', (function(marker, i) {
		return function() {
			infowindow.setContent(locations[i][0]);
			infowindow.open(map, marker);
		}
	})(marker, i));
}