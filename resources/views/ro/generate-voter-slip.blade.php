<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title>Voter Slip</title>
	<style type="text/css">
		*, *:before, *:after{
			-webkit-box-sizing: border-box;
			   -moz-box-sizing: border-box;
					box-sizing: border-box;
		}
		.clearfix:before, .clearfix:after {
			content: " ";
			display: table;
		}
		.clearfix:after {
			clear: both;
		}

		a {
		  color: #5D6975;
		  text-decoration: underline;
		}
		img{
			height: auto;
			max-width: 100%;
			vertical-align: middle;
		}

		body {
			background: #FFFFFF;
			color: #000000;	
			font-family: Arial, sans-serif; 
			font-size: 12px; 
			line-height: 18px;
			position: relative;	 
		}

		.container{
			margin: 0 auto;
			width: 780px;
		}

		header {
			margin-top: 70px;
			margin-bottom: 15px;
		}

		.leftLogo{
			float: left;
			padding-right: 12px;
			width: 90px;
		}
		.middleHeader{
			border: 1px solid #000000;
			float: left;
			padding: 10px 10px 8px;
			text-align: center;
			width: 600px;
		}
		.rightLogo{
			float: right;
			padding-left: 12px;
			width: 90px;
		}

		h1 {
			color: #000000;
			font-size: 20px;
			line-height: 20px;
			font-weight: bold;  
			margin: 0 0 15px;
			text-transform: uppercase;
		}
		h2{
			color: #000000;
			font-size: 18px;
			line-height: 18px;
			font-weight: bold;  
			margin: 0;
		}

		.bottomHdr{
			margin-bottom: 10px;
			text-align: center;
		}
		.bhText{
			border: 1px solid #000000;
			color: #000000;
			display: inline-block;
			font-size: 18px;
			line-height: 18px;
			font-weight: bold;  
			padding: 10px 30px;
			text-transform: uppercase;
		}

		.content{
			margin-bottom: 25px;
		}
		.leftContent{
			float: left;
			margin-right: 10px;
			width: 250px;
		}
		.voterPic{
			border: 1px solid #000000;
			margin-bottom: 22px;
		}

		.rightContent{
			float: left;
			width: 520px;
		}



		table {
			width: 100%;
			border-collapse: collapse;
			border-spacing: 0;
			margin-bottom: 0;
		}

		.slipTable{
			border-left: 1px solid #000000;
			border-top: 1px solid #000000;
		}
		.slipTable td{
			border-bottom: 1px solid #000000;
			border-right: 1px solid #000000;
			color: #000000;
			font-size: 12px;
			font-weight: 400;
			line-height: 16px;
			padding: 4px 8px;
			vertical-align: middle;
		}


		.votingDate{
			border: 1px solid #000000;
			font-size: 15px;
			font-weight: bold;
			padding: 15px 10px 22px;
		}

		.vdatePoll{
			margin-bottom: 25px;
		}
		.vdplabel{
			float: left;
			width: 100px;
		}
		.vdpValue{
			border-bottom: 1px solid #000000;
			float: left;	
			height: 19px;
			width: 128px;
		}

		.vdtlabel{
			float: left;
			width: 65px;
		}
		.vdtValue{
			border-bottom: 1px solid #000000;
			float: left;
			height: 19px;
			width: 66px;
		}
		.vdtTo{
			float: left;
			text-align: center;
			width: 25px;
		}

		.deOffice{
			margin: 0 auto 20px;
			width: 540px;
		}
		.deLabel{
			float: left;
			font-size: 17px;
			font-weight: bold;
			text-transform: uppercase;
			width: 260px;
		}
		.deValue{
			border-bottom: 1px solid #000000;
			float: left;
			font-size: 15px;
			font-weight: bold;
			height: 19px;
			text-align: center;
			width: 280px;
		}


		.deWebsite{
			margin: 0 auto 70px;
			width: 700px;
		}
		.deoWeb{
			float: left;
			font-size: 17px;
			font-weight: bold;
			width: 125px;
		}
		.deoValue{
			border-bottom: 1px solid #000000;
			float: left;
			font-size: 15px;
			font-weight: bold;
			height: 19px;
			width: 200px;
		}
		.deoHelp{
			float: left;
			font-size: 17px;
			font-weight: bold;
			text-align: center;
			width: 160px;
		}

		.bpTitle{
			margin-bottom: 10px;
			text-align: center;
		}
		.bpTitle h3{
			border: 1px solid #000000;
			color: #000000;
			display: inline-block;
			font-size: 18px;
			font-weight: bold;
			margin: 0;
			padding: 5px 35px;
		}

		.backMap{
			float: left;
			margin-right: 10px;
			width: 385px;
		}
		.backContent{	
			float: left;
			width: 385px;
		}
		.backContList{
			border: 1px solid #000000;
			height: 435px;
			padding: 15px 10px;
		}
		.backContList ul{
			margin: 0;
			padding: 0 0 5px 15px;
		}
		.backContList ul li{
			color: #000000;
			font-size: 15px;
			line-height: 25px;
			margin-bottom: 8px;
		}
		.noVoterText{
			font-size: 15px;
			text-align: center;
			font-family: cursive;
		}

		#mapContent{
			width: 250px;
		}
		#mapContent h3{
			font-size: 18px;
			margin: 5px 0 15px;
		}
		#mapContent p{
			color: #000000;
			line-height: 28px;
			margin: 0 0 15px;
			font-weight: 400;
		}
		.mapSee{
			text-align: right;
		}
		.mapSee a{
			color: #000000;
			font-weight: 400;
			text-decoration: none;
		}
		.mapSee a img{
			margin-left: 3px;
			vertical-align: top;
		}
		.pageMargins{
			height: 480px;
			width: 100%;
		}
	</style>
	<script type="text/javascript" src="{{ URL::asset('js/jquery.min.js')}}"></script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC90VHLYFtiZmut_W3ELil_aLYtej_QmSk"></script>
</head>
<body>
	<div class="container">
		<header class="clearfix">
			<div class="leftLogo">
				<img src="{{ URL::asset('images/logo200.png')}}" />
			</div>
			<div class="middleHeader">
				<h1>Election Commision Of India</h1>
				<h2>General Election to Legislative Assembly of <u>Punjab</u> - 2017</h2>
			</div>
			<div class="rightLogo">
				<img src="{{ URL::asset('images/logo200.png')}}" />
			</div>
		</header>
		<div class="bottomHdr">
			<div class="bhText">ਵੋਟਰ ਪਰਚੀ/Voter Slip</div>
		</div>
		<div class="content clearfix">
			<div class="leftContent">
				<div class="voterPic">
					<img src="{{ URL::asset('images/observerPic.jpg')}}" />
				</div>
				<div class="votingDate">
					<div class="vdatePoll clearfix">
						<div class="vdplabel">Date of Poll :</div>
						<div class="vdpValue"></div>
					</div>
					<div class="vdateTiming clearfix">
						<div class="vdtlabel">Timing :</div>
						<div class="vdtValue"></div>
						<div class="vdtTo">to</div>
						<div class="vdtValue"></div>
					</div>
				</div>
			</div>
			<div class="rightContent">
				<table class="slipTable">
					<tr>
						<td>ਰਾਜ / State</td>
						<td>Punjab</td>
					</tr>
					<tr>
						<td>ਚੋਣ ਹਲਕਾ <br />Assembly Constituency</td>
						<td>Punjab</td>
					</tr>
					<tr>
						<td rowspan="2">ਨਾਮ <br />Name</td>
						<td>ਵਿਜੈ</td>
					</tr>
					<tr>
						<td>Vijay</td>
					</tr>
					<tr>
						<td>ਲਿੰਗ / Gender</td>
						<td>M</td>
					</tr>
					<tr>
						<td>ਵੋਟਰ ਦੀ ਲੜੀ ਨੰ <br />EPIC NO</td>
						<td>TEF3492451</td>
					</tr>
					<tr>
						<td rowspan="2">ਪਿਤਾ / ਪਤੀ ਦਾ ਨਾਮ <br />Father's / Husband's Name</td>
						<td>ਸੂਰਜ</td>
					</tr>
					<tr>
						<td>Suraj</td>
					</tr>
					<tr>
						<td>ਭਾਗ ਨੰ / Part Number</td>
						<td>21</td>
					</tr>
					<tr>
						<td>ਭਾਗ ਨਾਮ / Part Name</td>
						<td>Prathamik Vidhayay Bauka</td>
					</tr>
					<tr>
						<td>ਪੋਲਿੰਗ ਸਥਾਨ / Polling Station</td>
						<td>Samrala Chownk</td>
					</tr>
					<tr>
						<td>ਪੋਲਿੰਗ ਮਿਤੀ <br />Polling Date</td>
						<td><strong>No election scheduled currently</strong></td>
					</tr>
					<tr>
						<td>Last Updated On</td>
						<td>16/12/2016</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="deOffice clearfix">
			<div class="deLabel">District Election Office:</div>
			<div class="deValue">(District Name)</div>
		</div>
		<div class="deWebsite clearfix">
			<div class="deoWeb">DEO Website:</div>
			<div class="deoValue"></div>
			<div class="deoHelp">DEO Helpline No.:</div>
			<div class="deoValue"></div>
		</div>
		
		<!-- Back Page -->
		<div class="pageMargins">&nbsp;</div>
		
		<div class="backPage clearfix">
			<div class="backMap">
				<div class="bpTitle"><h3>Map of the Polling Station</h3></div>
				<div id="voter-slip-map" style="height: 435px;width:100%;border:1px solid #000000;"></div>
			</div>
			<div class="backContent">
				<div class="bpTitle"><h3>Important Information for Voters</h3></div>
				<div class="backContList">
					<ul>
						<li>BLO: ....................(Name and Contact No)</li>
						<li>All the voters who are in the queue at the closing time of the poll shall be allowed to caste their vote</li>
						<li>There are separate queues for women; Senior citizens are given priority for voting</li>
						<li>Blind and infirm voter can be permitted to take an adult companion to the voting compartment for recording the vote</li>
						<li>Gadgets like mobile phones and cameras are not allowed inside the polling booth</li>
						<li>Offering or accepting money or any other gratification to vote for particular candidate is a corrupt practice under law</li>
					</ul>
					<div class="noVoterText">No Voter to be left behind; Every Vote Counts</div>
				</div>
			</div>
		</div>
	</div>
	<script>

      var myCenter = new google.maps.LatLng(30.9043, 75.8404);
		var mapCanvas = document.getElementById('voter-slip-map');
		var mapOptions = {center: myCenter, zoom: 15};
		var map = new google.maps.Map(mapCanvas, mapOptions);
		var infowindow = new google.maps.InfoWindow();
		var marker = new google.maps.Marker({position:myCenter});

		var contentString = '<div id="mapContent">'+
            '<h3>Polling Station</h3>'+
            '<p><b>Bharat Nagar Chownk</b><br />Ludhiana, Punjab</p>'+
			'<div class="mapSee"><a href="javascript:void(0);">See your election officials <img src="{{ URL::asset("images/externalLink.png")}}" /></a></div>'
            '</div>';
		
		var infowindow = new google.maps.InfoWindow({
		content: contentString
		});

		marker.addListener('click', function() {
			infowindow.open(map, marker);
		});
		marker.setMap(map);

    </script>	
</body>
</html>