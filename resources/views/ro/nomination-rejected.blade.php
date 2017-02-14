@extends('layouts.main')

@section('content')
	<!-- START CONTAINER -->
	<div class="container-widget">
  
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					<div class="panel-title pageTitle">List of Rejected Candidates</div>
					<div class="panel-body">
						<ul class="candidateList clearfix">
							@foreach($getNomination as $getNominations)

							<li>
								<?php
									//dd($getNominations);
									// $proPic = $getNominations->profile_pic;
									// $uid = $getNominations->uid;
									// $uidEnc = eci_encrypt($uid);

								?>
								
								<h3>Name - {{ $getNominations['CandidateName'] }} </h3>
								<p>Party - {{ $getNominations['PartyName'] }} </p>
							</li>
							@endforeach

							<?php if(count($getNomination) == 0){?>
								 No Data Found
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