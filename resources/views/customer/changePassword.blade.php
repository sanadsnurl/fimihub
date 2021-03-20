@extends('customer.layout.myAccountBase')

@section('title', 'My Account')

@section('content')


<div class="content-col">
    <div class="info-box">
        <div class="form-title">
            <h5>CHANGE PASSWORD</h5>
        </div>
        <div class="inner-wrap">
            <div class="title-wrap">
                <p>Kindly reset your password</p>
                @if(Session::has('message'))
                <div class="error" style="text-align:center;">
                    <h4 class="error">{{ Session::get('message') }}</h4>
                </div>
                
                @endif
            </div>
            <form role="form" method="POST" action="{{ url('/changePassword') }}" class="form change-pwd">
                @csrf
                <div class="input-wrap">
                    <input type="password" placeholder="Current Password" name="current_password">
                    @if($errors->has('password'))
                    <div class="error">{{ $errors->first('current_password') }}</div>
                    @endif
                </div>
                <div class="input-wrap">
                    <input type="password" placeholder="New Password" name="password">
                    @if($errors->has('password'))
                    <div class="error">{{ $errors->first('password') }}</div>
                    @endif
                </div>
                <div class="input-wrap">
                    <input type="password" placeholder="Confirm New Password" name="password_confirmation">
                    @if($errors->has('password'))
                    <div class="error">{{ $errors->first('password_confirmation') }}</div>
                    @endif
                </div>
                <div class="btn-wrap">
                    <input type="submit" class="btn btn-purple" value="Change Password">
                </div>
            </form>
        </div>
    </div>
</div>
@endsection