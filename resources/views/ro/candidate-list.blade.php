@extends('layouts.main')

@section('content')
	<!-- START CONTAINER -->
	<div class="container-widget">
  
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					<div class="panel-title pageTitle">Final list of Contesting Candidates</div>
					<div class="panel-body">
						<ul class="candidateList clearfix">
							@foreach($getNomination as $getNominations)

							<li>
								<?php
									$proPic = $getNominations->profile_pic;
									$uid = $getNominations->uid;
									$uidEnc = eci_encrypt($uid);

								?>
								<a href="{{url('ro/candidate-affidavit') }}/{{$getNominations->cand_sl_no}}/{{$getNominations->cons_code}}" target="_blank">
									<img src="{{ URL::asset('images/candidate/profilePicture/'.$proPic)}}" />
								</a>
								<h3>
									<a href="{{ url('ro/candidate-detail/'.$uidEnc) }}">{{ $getNominations->name }}</a>
								</h3>
								<p>{{ $getNominations->cand_party }}</p>
							</li>
							@endforeach

							<?php if(count($getNomination) == 0){?>
								Final list of Contesting Candidates will be available on 21 January, 2017
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
			var candidatePic = $('.candidateList li a > img').width();
			$('.candidateList li a > img').css('height', candidatePic);
		});
	</script>
@endsection