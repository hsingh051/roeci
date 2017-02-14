@extends('layouts.main')
@section('content')
<!-- START CONTAINER -->
	<div class="container-widget">  
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget">
					<div class="panel-title pageTitle text-center">
						<span>ANNEXURE REPORT</span>
						<!-- <a href="{{URL::previous()}}" class="btn btn-default formBackBtn">Back</a> -->
					</div>
					<div class="panel-body">
						<div class="noticeCandi">
							<form method="post">
								<div class="form-group">
									<label class="form-label">Interruption or obstruction of poll due to riots, open violence, natural calamity or any other cause</label>
									<div class="radio radio-inline">
									   <input type="radio" id="p1day1Yes" value="1" name="p1day1-yes-no" checked>
									   <label for="p1day1Yes">Yes</label>
								    </div>
								   <div class="radio radio-inline">
									   <input type="radio" id="p1day1No" value="0" name="p1day1-yes-no">
									   <label for="p1day1No">No</label>
								   </div>
								</div>
								<div class="form-group">
									<label class="form-label">Vitiation of the poll by any of the EVMs having been unlawfully taken out of the custody of the presiding officer, accidentally or unintentionally lost or destroyed or damaged or tampered with</label>
									<div class="radio radio-inline">
									   <input type="radio" id="p1day2Yes" value="1" name="p1day2-yes-no" checked>
									   <label for="p1day2Yes">Yes</label>
								    </div>
								   <div class="radio radio-inline">
									   <input type="radio" id="p1day2No" value="0" name="p1day2-yes-no">
									   <label for="p1day2No">No</label>
								   </div>
								</div>
								<div class="form-group">
									<label class="form-label">Votes having been unlawfully recorded by any person in the EVMs</label>
									<div class="radio radio-inline">
									   <input type="radio" id="p1day3Yes" value="1" name="p1day3-yes-no" checked>
									   <label for="p1day3Yes">Yes</label>
								    </div>
								   <div class="radio radio-inline">
									   <input type="radio" id="p1day3No" value="0" name="p1day3-yes-no">
									   <label for="p1day3No">No</label>
								   </div>
								</div>
								<div class="form-group">
									<label class="form-label">Booth capturing</label>
									<div class="radio radio-inline">
									   <input type="radio" id="p1day4Yes" value="1" name="p1day4-yes-no" checked>
									   <label for="p1day4Yes">Yes</label>
								    </div>
								   <div class="radio radio-inline">
									   <input type="radio" id="p1day4No" value="0" name="p1day4-yes-no">
									   <label for="p1day4No">No</label>
								   </div>
								</div>
								<div class="form-group">
									<label class="form-label">Serious complaint</label>
									<div class="radio radio-inline">
									   <input type="radio" id="p1day5Yes" value="1" name="p1day5-yes-no" checked>
									   <label for="p1day5Yes">Yes</label>
								    </div>
								   <div class="radio radio-inline">
									   <input type="radio" id="p1day5No" value="0" name="p1day5-yes-no">
									   <label for="p1day5No">No</label>
								   </div>
								</div>
								<div class="form-group">
									<label class="form-label">Violence and breach of law and order</label>
									<div class="radio radio-inline">
									   <input type="radio" id="p1day6Yes" value="1" name="p1day6-yes-no" checked>
									   <label for="p1day6Yes">Yes</label>
								    </div>
								   <div class="radio radio-inline">
									   <input type="radio" id="p1day6No" value="0" name="p1day6-yes-no">
									   <label for="p1day6No">No</label>
								   </div>
								</div>
								<div class="form-group">
									<label class="form-label">Mistake and irregularities, which have a bearing on the elections</label>
									<div class="radio radio-inline">
									   <input type="radio" id="p1day7Yes" value="1" name="p1day7-yes-no" checked>
									   <label for="p1day7Yes">Yes</label>
								    </div>
								   <div class="radio radio-inline">
									   <input type="radio" id="p1day7No" value="0" name="p1day7-yes-no">
									   <label for="p1day7No">No</label>
								   </div>
								</div>
								<div class="form-group">
									<label class="form-label" for="p1day8">Weather conditions</label>
									<input type="text" class="form-control" id="p1day8" placeholder="Weather conditions" />
								</div>
								<div class="form-group">
									<label class="form-label" for="p1day9">Poll percentage</label>
									<input type="text" class="form-control" id="p1day9" placeholder="Poll percentage" />
								</div>
								<div class="form-group">
									<label class="form-label">Whether all the diaries of Presiding Officers have been scrutinized and irregularities if any detected</label>
									<div class="radio radio-inline">
									   <input type="radio" id="p1day10Yes" value="1" name="p1day10-yes-no" checked>
									   <label for="p1day10Yes">Yes</label>
								    </div>
								   <div class="radio radio-inline">
									   <input type="radio" id="p1day10No" value="0" name="p1day10-yes-no">
									   <label for="p1day10No">No</label>
								   </div>
								</div>
								<div class="form-group">
									<label class="form-label">Recommendations regarding repoll / fresh poll, if any</label>
									<div class="radio radio-inline">
									   <input type="radio" id="p1day11Yes" value="1" name="p1day11-yes-no" checked>
									   <label for="p1day11Yes">Yes</label>
								    </div>
								   <div class="radio radio-inline">
									   <input type="radio" id="p1day11No" value="0" name="p1day11-yes-no">
									   <label for="p1day11No">No</label>
								   </div>
								</div>
								<div class="form-group">
									<label class="form-label" for="p1day12">Any other remarks</label>
									<input type="text" class="form-control" id="p1day12" placeholder="Any other remarks" />
								</div>
								<button type="submit" class="btn btn-default">Submit</button>
							</form>
						</div>
					</div>
				</div>
			</div>	
		</div>  
	</div>	
@endsection