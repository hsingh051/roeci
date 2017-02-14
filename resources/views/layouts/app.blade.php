<!DOCTYPE html>
<html lang="en">
<head>
<!-- sdds-->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="cache-control" content="private, max-age=0, no-cache">
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="expires" content="0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="css/app.css" rel="stylesheet">
	<link rel="stylesheet" href="{{ URL::asset('css/root.css')}}" />

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
    <script>if (top!=self) top.location.href=self.location.href</script>
    
</head>
<body>
	<!-- START TOP -->
	<div id="top" class="clearfix">

		<div class="applogo">
			<a href="{{ url('/') }}" class="logo">ECI RO-NET</a>
		</div>

		<!-- Start Sidebar Show Hide Button -->
		<a href="#" class="sidebar-open-button"><i class="fa fa-bars"></i> Menu</a>
		<a href="#" class="sidebar-open-button-mobile"><i class="fa fa-bars"></i> Menu</a>
		<!-- End Sidebar Show Hide Button -->
		
		<ul class="top-right">
			<li class="dropdown link">
				<a href="#" data-toggle="dropdown" class="dropdown-toggle profilebox"><b>{{ Auth::user()->name }} </b><span class="caret"></span></a>
				<ul class="dropdown-menu dropdown-menu-list dropdown-menu-right">
					<li><a href="{{ url('/logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            <i class="fa falist fa-power-off"></i> Logout
                        </a></li>
				</ul>
			</li>
			<form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
				{{ csrf_field() }}
			</form>
		</ul>

	</div>
	<!-- END TOP -->
	
	<!-- START SIDEBAR -->
<!-- 	<div class="sidebar clearfix">
		<ul class="sidebar-panel nav">
			<li><a href="{{ url('/dashboard') }}">Dashboard</a></li>
			<li><a href="{{ url('/electoral-rolls') }}">Electoral Rolls</a></li>
			<li><a href="#">Teams</a></li>
			<li><a href="{{ url('/nominations') }}">Nominations</a></li>
			<li><a href="{{ url('/evm-vvpat') }}">EVM & VVPAT</a></li>
			<li><a href="#">Polling Staff</a></li>
			<li><a href="{{ url('/training') }}">Training</a></li>
			<li><a href="{{ url('/election-material') }}">Election Material</a></li>
			<li><a href="{{ url('/polling-station') }}">Polling Stations</a></li>
			<li><a href="#">Pre-Poll Arrangement</a></li>
			<li><a href="#">Poll - 1 Day</a></li>
			<li><a href="{{ url('/poll-day') }}">Poll Day</a></li>
			<li><a href="#">Polling + 1</a></li>
		</ul>
	</div> -->
	<!-- END SIDEBAR -->
	
	<!-- START CONTENT -->
	<div class="content">
		<!-- Start Page Header -->
		<div class="page-header">
			<img src="{{ URL::asset('images/siteLogo.png')}}" class="pageLogo" />
		</div>
		<!-- End Page Header -->
		
        @yield('content')
		
		<!-- Start Footer -->
		<div class="row footer">
			<div class="col-md-6 text-left">
				© Copyright 2016, Election Commission Of India.
			</div>
			<div class="col-md-6 text-right">
				Design and Developed by 01 Synergy
			</div> 
		</div>
		<!-- End Footer -->
	</div>
	<!-- End Content -->

    <!-- Scripts -->
	<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="js/app.js"></script>
	<script type="text/javascript" src="{{ URL::asset('js/bootstrap/bootstrap.min.js')}}"></script> 
	<script type="text/javascript" src="{{ URL::asset('js/bootstrap-toggle/bootstrap-toggle.min.js')}}"></script> 
	<script type="text/javascript" src="{{ URL::asset('js/plugins.js')}}"></script> 
	<!-- Chartist -->
	<!-- main file -->
	<script type="text/javascript" src="js/chartist/chartist.js"></script>
	<!-- demo codes -->
	<script type="text/javascript" src="js/chartist/chartist-plugin.js"></script>

	<!-- Data Tables -->
	<script src="js/datatables/datatables.min.js"></script>

	<!-- jQuery UI -->
	<script type="text/javascript" src="js/jquery-ui/jquery-ui.min.js"></script>
	
    <script type="text/javascript">
        $(function(){
            console.log($('#resend_otp'));
            $('#resend_otp').on('submit',function(e){
                e.preventDefault();
                console.log($(this).serialize());
                $.post('http://localhost/eci/public/resendotp', $(this).serialize(), function(data) { 
                        
                });

               
            });

        });
    </script>
</body>
</html>
