@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
	<div class="container-widget"> 
		<div class="row">
			<!-- Nominations -->
			<div class="col-md-12">
				<div class="panel panel-widget">
					<div class="panel-title pageTitle">LAW & ORDER</div>
					<div class="panel-body">
						<div class="table-scroll">
							<table class="table table-bordered tablefilter">
								<thead>
									<tr>
										<th>Poll Building</th>
										<th>Comment</th>
										<th>Time of Poll Interrupted (From)</th>
										<th>Time of Poll Interrupted (To)</th>
										<th>PRO Name</th>
										<th>PRO Phone</th>
										<th>Supervisor Name</th>
										<th>Supervisor Phone</th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<th>Poll Building</th>
										<th>Comment</th>
										<th>Time of Poll Interrupted (From)</th>
										<th>Time of Poll Interrupted (To)</th>
										<th>PRO Name</th>
										<th>PRO Phone</th>
										<th>Supervisor Name</th>
										<th>Supervisor Phone</th>
									</tr>
								</tfoot>
								<tbody>	
								@foreach($laworderlist as $laworderlists)								
									<tr>
										<td>{{$laworderlists->poll_building}}</td>
										<td>{{$laworderlists->comment}}</td>
										<td>{{$laworderlists->action_from}}</td>
										<td>{{$laworderlists->action_to}}</td>
										<td>{{$laworderlists->pro_name}}</td>
										<td>{{$laworderlists->pro_number}}</td>
										<td>{{$laworderlists->sup_name}}</td>
										<td>{{$laworderlists->sup_num}}</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>	
		</div>
	</div>
	<!-- END CONTAINER -->
@endsection