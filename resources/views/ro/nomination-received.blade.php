@extends('layouts.main')

@section('content')
	<link rel="stylesheet" href="{{ URL::asset('dateTimePicker/css/bootstrap-datetimepicker.min.css')}}" />
	<link rel="stylesheet" href="{{ URL::asset('dateTimePicker/css/jquery-ui.css')}}" />
	<!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					@if(Session::has('nominationSucc'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('nominationSucc') }}</p>
					@endif
					<div class="panel-title pageTitle">Nomination Received</div>
					<div class="panel-body">
						<ul class="candidateList clearfix">

							@foreach($getNomination as $getNominations)

							<li>
								<?php
									$proPic = $getNominations->profile_pic;
									$uid = $getNominations->uid;
									$uidEnc = eci_encrypt($uid);

								?>
								<a href="{{ url('ro/candidate-detail/'.$uidEnc) }}">
									<img width="400" height="400" src="{{ URL::asset('images/candidate/profilePicture/'.$proPic)}}" />
								</a>
								<h3>
									<a href="{{ url('ro/candidate-detail/'.$uidEnc) }}">{{ $getNominations->name }}</a>
								</h3>
								<p>{{ $getNominations->cand_party }}</p>
							</li>
							@endforeach

							<?php if(count($getNomination) == 0){?>
								<li style="text-align:left">Data for Nomination will be update soon</li>
							<?php }?>
						</ul>
					</div>
				</div>
			</div>	
		</div>  
	</div>
	<!-- END CONTAINER -->
	<script type="text/javascript" src="{{ URL::asset('dateTimePicker/js/jquery-ui.js')}}"></script>
    <script type="text/javascript" src="{{ URL::asset('dateTimePicker/js/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{ URL::asset('dateTimePicker/js/bootstrap-datetimepicker.min.js')}}"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$("#datepicker-13").click(function(){
				$( "#datepicker-13" ).datepicker();
				$( "#datepicker-13" ).datepicker("show");	
			});
			$('#datepicker-13').keypress(function(e) {
			    return false
			});
			var dateToday = new Date();
			var dates = $("#datepicker-13").datepicker();
			
			var candidatePic = $('.candidateList li a > img').width();
			$('.candidateList li a > img').css('height', candidatePic);
		});
	</script>
@endsection