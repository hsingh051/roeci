@extends('layouts.main')

@section('content')
	<!-- START CONTAINER -->
	<div class="container-widget">  
		<div class="row">
			<div class="col-md-12">
				<div class="searchBar">
					<form class="topSearchForm" enctype="multipart/form-data" method="get" action="{{url('eci/candidate-list-search') }}">
						<input type="hidden" name="_token" value="<?= csrf_token(); ?>">
						<div class="form-group{{ $errors->has('dist_code') ? ' has-error' : '' }}">
							<select name="dist_code" class="form-control poll_dist_code" id="poll_dist_code" >
								<option value="">Select District</option>
								@foreach($district as $districts)
									<?php $dist_code=eci_encrypt($districts->dist_code); ?>
									<option value="{{$dist_code}}" <?php if($dist_code==$encryptDist){ echo "selected"; } ?> >{{ $districts->dist_name }}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group{{ $errors->has('cons_code') ? ' has-error' : '' }}">
							<select name="cons_code" class="form-control poll_cons_code" id="cons_code">
									<option value="">Select Constituency</option>
								@foreach($constituency as $constituencies)
									<?php $consCode=eci_encrypt($constituencies->cons_code); ?>
									<option value="{{$consCode}}" <?php if($consCode==$encryptCons){ echo "selected"; } ?> >{{ $constituencies->cons_name }}</option>
								@endforeach
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
				<div class="panel panel-widget">
					<div class="panel-title pageTitle">CANDIDATES LIST</div>
					<div class="panel-body">
						<ul class="candidateList clearfix">
							@foreach($getNomination as $getNominations)

							<li>
								<?php
									$proPic = $getNominations->profile_pic;
									$uid = $getNominations->id;
									$uidEnc = eci_encrypt($uid);
									$state_id =  get_state_id();
								?>
								<a href="{{url('eci/candidate-affidavit') }}/{{$getNominations->cand_sl_no}}/{{$getNominations->cons_code}}" target="_blank">
									<img src="{{ URL::asset('images/candidate/profilePicture/'.$proPic)}}" />
								</a>
								<h3>
									<a href="#">{{ $getNominations->candidatename }}</a>
								</h3>
								<p>{{ $getNominations->partyname }}</p>
							</li>
							@endforeach

							<?php if(count($getNomination) == 0){?>
								<li style="text-align:left;">No Data Found.</li>
							<?php }?>
							
						</ul>
					</div>
				</div>
			</div>	
		</div>  
	</div>
	<!-- END CONTAINER -->
	
	<script type="text/javascript">
		$(document).ready(function(){			
			var candidatePic = $('.candidateList > li').width();
			$('.candidateList li a > img').css('height', candidatePic);
		});
	</script>
@endsection