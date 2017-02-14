@extends('layouts.main')
@section('content')
<!-- START CONTAINER -->
	<div class="container-widget">	
		<div class="row">
			<!-- Nominations -->
			<div class="col-md-12">
				<div class="panel panel-widget">
					@if(Session::has('roSucc'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('roSucc') }}</p>
					@endif

					@if(Session::has('repeatErrRo'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('repeatErrRo') }}</p>
					@endif

					@if(Session::has('requireErrRo'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('requireErrRo') }}</p>
					@endif

					@if(Session::has('validPhoneRo'))
					<p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('validPhoneRo') }}</p>
					@endif

					<div class="panel-title pageTitle titleBtn clearfix">
						<span>Ro List</span>
						<div class="panel-btn">
							<a href="{{url('deo/add-ro') }}" class="btn btn-default">Add New RO</a>
							<a href="{{url('deo/add-ro-csv') }}" class="btn btn-default">Import CSV</a>
						</div>
					</div>
					<div id="notice"></div>
					<div class="panel-body">
						<table id="example0" class="table table-bordered tablefilter">
							<thead>
								<tr>
									<th>RO NAME</th>
									<th>CONSTITUENCY</th>
									<th>CONTACT NUMBER</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>RO NAME</th>
									<th>CONSTITUENCY</th>
									<th>CONTACT NUMBER</th>
									<td></td>
								</tr>
							</tfoot>
							<tbody>
								</tr>
								@foreach ($getRoConst as $getRos)
								<?php
									$roId=eci_encrypt($getRos->uid);
								?>
								<tr>
									<td><a href="{{ url('deo/supervisor-list') }}/<?php echo $roId; ?>">{{ $getRos->name }}</a></td>
									<td>{{ $getRos->cons_name }}</td>
									<td>{{ $getRos->phone }}</td>
									<td>
										<a href="{{url('deo/editRo') }}/<?php echo $roId; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>

										<a class="delLRoBtns" id="<?php echo $roId; ?>" rel="<?= csrf_token(); ?>"><i class="fa fa-times" aria-hidden="true"></i></a>
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
		$(".delLRoBtn").click(function(){
			var answer=confirm('Do you want to delete RO?');
			if(answer){
				return true;
			}else{
				return false;
			}
		});
	});
</script>
@endsection