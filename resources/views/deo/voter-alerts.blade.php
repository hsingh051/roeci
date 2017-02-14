@extends('layouts.main')
@section('content')
	<div class="container-widget">  
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-widget heightWidget">
					<div class="panel-title pageTitle text-center">
						<span>Voter Alerts</span>
					</div>
					<div class="panel-body">
						<div class="noticeCandi">
							<form method="post" action="{{ url('deo/send-voter-alerts') }}">
								<input type="hidden" name="_token" value="<?= csrf_token(); ?>">							
								<div class="form-group">
									<label class="form-label">Subject</label>
									<input type="text" class="form-control" placeholder="Subject" name="subject" value="{{ old('subject') }}"/>
									@if ($errors->has('subject'))
										<span class="help-block">
											<strong>{{ $errors->first('subject') }}</strong>
										</span>
									@endif
								</div>
								<div class="form-group">
									<label class="form-label">Message</label>
									<textarea rows="3" cols="30" class="form-control" placeholder="Message" name="msg" />{{ old('msg') }}</textarea>
									@if ($errors->has('msg'))
										<span class="help-block">
											<strong>{{ $errors->first('msg') }}</strong>
										</span>
									@endif
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

