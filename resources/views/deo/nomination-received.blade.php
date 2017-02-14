@extends('layouts.main')

@section('content')
	<link rel="stylesheet" href="{{ URL::asset('dateTimePicker/css/bootstrap-datetimepicker.min.css')}}" />
	<link rel="stylesheet" href="{{ URL::asset('dateTimePicker/css/jquery-ui.css')}}" />
	<!-- START CONTAINER -->
	<?php 
	$consCodeCheck= (isset($_GET['cons_code']))? $_GET['cons_code'] : "";
	$encryptConsCode= (isset($encryptCons))? $encryptCons : "";
	?>
    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">
				<div class="searchBar">
					<form class="topSearchForm" enctype="multipart/form-data" method="get" action="{{url('deo/nomination-received') }}">
					<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
						<div class="form-group{{ $errors->has('cons_code') ? ' has-error' : '' }}">
							<select name="cons_code" class="form-control" id="cons_code">
									<option value="">Select Constituency</option>
								@if($constituency)
								@foreach($constituency as $constituencies)
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
						<div class="form-group">
							<button type="submit" class="btn btn-default">Submit</button>
						</div>						
					</form>
				</div>
			</div>
		</div>  
		<?php if(($consCodeCheck!=="") || ($encryptConsCode!=="")){ ?>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget">
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
								<a href="{{ url('deo/candidate-detail/'.$uidEnc) }}">
									<img width="400" height="400" src="{{ URL::asset('images/candidate/profilePicture/'.$proPic)}}" />
								</a>
								<h3>
									<a href="{{ url('deo/candidate-detail/'.$uidEnc) }}">{{ $getNominations->name }}</a>
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
		<?php } ?>
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
			
			var candidatePic = $('.candidateList li a > img').width();
			$('.candidateList li a > img').css('height', candidatePic);
		});
	</script>
@endsection