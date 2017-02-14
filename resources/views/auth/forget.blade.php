@extends('layouts.applogin')

<!-- Main Content -->
@section('content')
<div class="login-form">
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <form role="form" method="POST" action="{{ url('/resetnumber') }}" autocomplete="off">
        {{ csrf_field() }}
        <div class="top">
            <img src="{{ URL::asset('images/siteLogo.png')}}" alt="icon" class="icon" />
            <h1>Forgot Password</h1>
        </div>
        <span class="help-block">
            <strong>{{ Session::get('invalid') }}</strong>
        </span>
        <div class="form-area">
            <div class="group{{ $errors->has('phone') ? ' has-error' : '' }}">
                <input id="phone" type="text" class="form-control" name="phone" autocomplete="off" placeholder="Mobile Number" value="{{ old('phone') }}" required>
                <i class="fa fa-phone"></i>

                @if ($errors->has('phone'))
                    <span class="help-block">
                        <strong>{{ $errors->first('phone') }}</strong>
                    </span>
                @endif
            </div>
            <button type="submit" class="btn btn-default btn-block">Send OTP to Mobile Number</button>       
        </div>
    </form>
</div>
@endsection

