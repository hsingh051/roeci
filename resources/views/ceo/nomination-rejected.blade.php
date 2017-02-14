@extends('layouts.main')

@section('content')
	<!-- START CONTAINER -->
	<?php
	$distCodeCheck= (isset($_GET['dist_code']))? $_GET['dist_code'] : ""; 
	$consCodeCheck= (isset($_GET['cons_code']))? $_GET['cons_code'] : "";
	$encryptConsCode= (isset($encryptCons))? $encryptCons : "";
	$encryptDistCode= (isset($encryptDist))? $encryptDist : "";
	$consList= (isset($constituency))? $constituency : "";
	?>
    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">
				<div class="searchBar">
					<form class="topSearchForm" enctype="multipart/form-data" method="get" action="{{url('ceo/nomination-rejected-search') }}">
					<input type="hidden" name="_token" value="<?= csrf_token(); ?>">

						<div class="form-group{{ $errors->has('dist_code') ? ' has-error' : '' }}">
							<select name="dist_code" class="form-control poll_dist_code_ceo">
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
							<select name="cons_code" class="form-control poll_cons_code_ceo">
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

						<div class="form-group">
							<button type="submit" class="btn btn-default">Submit</button>
						</div>

					</form>
				</div>
			</div>
		</div>

		<?php if((($distCodeCheck!=="") && ($consCodeCheck!=="")) || (($encryptConsCode!=="") && ($encryptDistCode!==""))) { ?>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget">
					<div class="panel-title pageTitle">Nomination Rejected</div>
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
									<img src="{{ URL::asset('images/candidate/profilePicture/'.$proPic)}}" />
								</a>
								<h3>
									<a href="{{ url('ro/candidate-detail/'.$uidEnc) }}">{{ $getNominations->name }}</a>
								</h3>
								<p>{{ $getNominations->cand_party }}</p>
							</li>
							@endforeach

							<?php if(count($getNomination) == 0){?>
								<li style="text-align:left">No Data Found.</li>
							<?php }?>
						</ul>
					</div>
				</div>
			</div>	
		</div>
		<?php  } ?>
	</div>
	<!-- END CONTAINER -->
	
	<script type="text/javascript">
		$(document).ready(function(){			
			var candidatePic = $('.candidateList li a > img').width();
			$('.candidateList li a > img').css('height', candidatePic);
		});
	</script>
@endsection