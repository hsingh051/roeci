@extends('layouts.main')
@section('content')
<!-- START CONTAINER -->
	<div class="container-widget">	
		<div class="row">
			<!-- Nominations -->
			<div class="col-md-12">
				<div class="panel panel-widget">
					@if(Session::has('traningSucc'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('traningSucc') }}</p>
					@endif
					<div class="panel-title pageTitle titleBtn clearfix">
						<span>Training</span>
						<div class="panel-btn">
							<a href="{{url('deo/add-traning') }}" class="btn btn-default">Add New Training</a>
						</div>
					</div>
					<div id="notice"></div>
					<div class="panel-body">
						<table id="tableview" class="table table-bordered traningTable">
							<thead>
								<tr>
									<th class="w45">Training Type</th>
									<th>Date</th>
									<th>Time From</th>
									<th>Time To</th>
									<th>Venue</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Training Type</th>
									<th>Date</th>
									<th>Time From</th>
									<th>Time To</th>
									<th>Venue</th>
									<th>Actions</th>
								</tr>
							</tfoot>
							<tbody>
								</tr>
								@foreach ($traningList as $traningLists)
								<?php
									$id=$traningLists->id;
									$idEnc=eci_encrypt($id);
								?>
								<tr id="<?php echo "tr".$idEnc; ?>">
									<td>{{ $traningLists->name }}</td>
									<td>{{ date("d F, Y",strtotime($traningLists->date)) }}</td>
									<td>{{ date("h:i A",strtotime($traningLists->from_time)) }}</td>
									<td>{{ date("h:i A",strtotime($traningLists->to_time)) }}</td>
									<td>{{ $traningLists->location }}</td>
									<td>
										<a href="{{ url('deo/edit-traning') }}/<?php echo $idEnc; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
										<a class="delTranings" id="<?php echo $idEnc; ?>" rel="<?= csrf_token(); ?>"><i class="fa fa-times" aria-hidden="true"></i></a>
									</td>
								</tr>
								@endforeach	
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
<!-- END CONTAINER -->
<script type="text/javascript">
	$(document).ready(function(){
		$(".delTraning").click(function(){
			var answer=confirm('Do you want to delete?');
			if(!answer){
				return false;
			}
		});
	});
</script>
@endsection