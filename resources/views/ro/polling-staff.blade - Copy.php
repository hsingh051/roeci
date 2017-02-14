@extends('layouts.main')
@section('content')
    <!-- START CONTAINER -->
	<div class="container-widget">
		<div class="row">
			<div class="col-md-12">					
				<div class="searchBar">
					<select class="form-control">
						<option>Select Randomization</option>
						<option>First Randomization</option>
						<option>Second Randomization</option>
						<option>Third Randomization</option>
					</select>
					<select class="form-control">
						<option>Select Polling Station</option>
						<option>Sahnewal</option>
						<option>Sherpur</option>
						<option>Dholewal</option>
						<option>Dugri</option>
						<option>Model Town</option>
					</select>
					<input type="submit" value="Show" class="btn btn-default" />
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget">
					<div class="panel-title pageTitle titleBtn clearfix">
						<span>Polling Staff</span>
						<div class="panel-btn"><a href="javascript:void(0);" class="btn btn-default">Generate Report</a></div>
					</div>
					<div class="panel-body">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>Supervisor</th>
									<th>Age</th>
									<th>Department</th>
									<th>Contact No.</th>
									<th>Assigned Polling Station</th>
									<th class="w22">Polling Party Details</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><input type="text" class="form-control" placeholder="Search Supervisor"></td>
									<td><input type="text" class="form-control" placeholder="Search Age"></td>
									<td><input type="text" class="form-control" placeholder="Search Department"></td>
									<td></td>
									<td><input type="text" class="form-control" placeholder="Search Polling Station"></td>
									<td></td>
								</tr>
								<tr>
									<td>Preet Pal Singh</td>
									<td>25</td>
									<td>Department1</td>
									<td>9874562321</td>
									<td>Polling Station 1</td>
									<td>PRO: Munish Kumar<br>PO1: Satish Kumar<br>PO2: Jatin Kumar<br><a href="{{ url('/ro/polling-parties-details') }}">Click here for more details</a></td>
								</tr>
								<tr>
									<td>Harsh Bala</td>
									<td>26</td>
									<td>Department2</td>
									<td>9874562321</td>
									<td>Polling Station 2</td>
									<td>PRO: Munish Kumar<br>PO1: Satish Kumar<br>PO2: Jatin Kumar<br><a href="{{ url('/ro/polling-parties-details') }}">Click here for more details</a></td>
								</tr>
								<tr>
									<td>Satvinder Singh</td>
									<td>27</td>
									<td>Department3</td>
									<td>9874562321</td>
									<td>Polling Station 3</td>
									<td>PRO: Munish Kumar<br>PO1: Satish Kumar<br>PO2: Jatin Kumar<br><a href="{{ url('/ro/polling-parties-details') }}">Click here for more details</a></td>
								</tr>
								<tr>
									<td>Ranjot Singh</td>
									<td>28</td>
									<td>Department4</td>
									<td>9874562321</td>
									<td>Polling Station 4</td>
									<td>PRO: Munish Kumar<br>PO1: Satish Kumar<br>PO2: Jatin Kumar<br><a href="{{ url('/ro/polling-parties-details') }}">Click here for more details</a></td>
								</tr>
								<tr>
									<td>Gagandeep Singh</td>
									<td>29</td>
									<td>Department5</td>
									<td>9874562321</td>
									<td>Polling Station 5</td>
									<td>PRO: Munish Kumar<br>PO1: Satish Kumar<br>PO2: Jatin Kumar<br><a href="{{ url('/ro/polling-parties-details') }}">Click here for more details</a></td>
								</tr>
								<tr>
									<td>Ramandeep Singh</td>
									<td>30</td>
									<td>Department6</td>
									<td>9874562321</td>
									<td>Polling Station 6</td>
									<td>PRO: Munish Kumar<br>PO1: Satish Kumar<br>PO2: Jatin Kumar<br><a href="{{ url('/ro/polling-parties-details') }}">Click here for more details</a></td>
								</tr>
								<tr>
									<td>Amit Kumar</td>
									<td>31</td>
									<td>Department7</td>
									<td>9874562321</td>
									<td>Polling Station 7</td>
									<td>PRO: Munish Kumar<br>PO1: Satish Kumar<br>PO2: Jatin Kumar<br><a href="{{ url('/ro/polling-parties-details') }}">Click here for more details</a></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>		
		</div>
	</div>
	<!-- END CONTAINER -->
@endsection

