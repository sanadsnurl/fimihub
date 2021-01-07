@include('customer.include.header')
<!-- login banner -->
<section class="login_banner auth_banner">
    <div class="container lg_container">
        <div class="auth_form_wraper">
            <div class="row">
                <div class="col-md-6 pr-0">
                    <div class="auth_form_left login_form_lft h-100">
                        <h3>Sign In</h3>
                        <div class="form-group">
                            <p>Hello, <br> Welcome Back!</p>
                        </div>
                        @if(Session::has('message'))
                        <div class="error" style="text-align:center;">
                            <h4 class="error">{{ Session::get('message') }}</h4>
                        </div>
                        <br>
                        <br>
                        @endif
                        <form role="form" method="POST" action="{{ url('/loginProcess') }}">
                            @csrf
                            <div class="form-group">
                                <label for="">Phone No.</label>
                                <input type="text" class="form-control" name="user_id" value="{{ old('user_id') }}">
                                @if($errors->has('user_id'))
                                <div class="error">{{ $errors->first('user_id') }}</div>
                                @endif
                            </div>
                            <div class="form-group margin_btm">
                                <label for="">Password</label>
                                <input type="password" class="form-control" name="password"
                                    value="{{ old('password') }}">
                                @if($errors->has('password'))
                                <div class="error">{{ $errors->first('password') }}</div>
                                @endif
                            </div>
                            <p class="margin_btm text-right noAccount_txt forgot_psw"><a href="javascript:void(0);"
                                    data-toggle="modal" data-target="#forgot_psw">Forgot your password?</a></p>

                            <input type="submit" class="signin_btn btn_purple hover_effect1 auth_btn" value="SIGN IN">
                            <p class="text-center noAccount_txt">Don't have an account? <a
                                    href="{{url('register')}}">SIGN UP</a></p>
                        </form>
                    </div>
                </div>
                <div class="col-md-6 pl-0">
                    <div class="auth_form_right h-100">
                        <img src="{{asset('asset/customer/assets/images/signup-banner-right.png')}}" alt="pizza image">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- login banner end -->

<!-- fORGET PASSWORD MODAL -->
<div class="modal fade auth_modals" id="forgot_psw">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal_body_head text-center">
                    <h3>FORGOT PASSWORD</h3>
                </div>
                <div class="modal_body_content modal_body_content2 text-center">
                    <p>Please enter your registered mobile number and we will send you a verification code so that you
                        can reset your password.</p>
                    @if(Session::has('error_message'))
                    <div class="error" style="text-align:center;">
                        <h4 class="error">{{ Session::get('error_message') }}</h4>
                    </div>
                    <br>
                    <br>
                    @endif
                    <form role="form" method="POST" action="{{ url('/getOTP') }}">
                        @csrf
                        <div class="form-group">
                            <label for="">Phone No.</label>
                            <input type="number" class="form-control" name="phone_number"
                                value="{{ old('phone_number') }}">
                            @if($errors->has('phone_number'))
                            <div class="error">{{ $errors->first('phone_number') }}</div>
                            @endif
                        </div>

                        <div class="forgot_psw_btn d-flex">
                            <button type="button" class="backLogin_btn btn_purple hover_effect1 auth_btn"
                                data-dismiss="modal">Back to
                                Login</button>
                            <input type="submit" class="getOtp_btn btn_purple hover_effect1 auth_btn" value="Get OTP">
                        </div>

                        <p class="text-center noAccount_txt">Don't have an account? <a href="{{url('register')}}">SIGN
                                UP</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- otp verification modal -->
<div class="modal fade auth_modals" id="otpverification">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal_body_head text-center">
                    <h3>OTP VERIFICATION</h3>

                </div>
                <div class="modal_body_content text-center otp_verification">
                    <p>Please enter the One Time Password received on your registered mobile number to verify
                        your account.</p>

                    <h3>VERIFY YOUR ACCOUNT</h3>
                    @if(Session::has('error_message'))
                    <div class="error" style="text-align:center;">
                        <b>
                            <h4 class="error">{{ Session::get('error_message') }}</h4>
                        </b>
                    </div>
                    <br>
                    @endif
                    @if($errors->has('num1'))
                    <div class="error">{{ $errors->first('num1') }}</div>
                    @endif
                    @if($errors->has('num2'))
                    <div class="error">{{ $errors->first('num2') }}</div>
                    @endif
                    @if($errors->has('num3'))
                    <div class="error">{{ $errors->first('num3') }}</div>
                    @endif
                    @if($errors->has('num4'))
                    <div class="error">{{ $errors->first('num4') }}</div>
                    @endif
                    <form role="form" method="POST" action="{{ url('/verifyAccount') }}">
                        @csrf
                        <input type="number" maxlength="1" name="num1">
                        <input type="number" maxlength="1" name="num2">
                        <input type="number" maxlength="1" name="num3">
                        <input type="number" maxlength="1" name="num4" class="mr-0">

                        <p class="text-right"><a href="javascript:void(0);" class="resend_link">Resend OTP </a> <span
                                class="timer">0:00</span></p>
                        <input type="submit" value="Verify" class="signup_btn btn_purple hover_effect1 auth_btn">

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- reset password modal -->
<div class="modal fade auth_modals" id="restpassword">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal_body_head text-center">
                    <h3>RESET PASSWORD</h3>

                </div>
                <div class="modal_body_content text-center restpassword">
                    <h5>Kindly reset your password</h5>
                    @if(Session::has('error_message'))
                    <div class="error" style="text-align:center;">
                        <h4 class="error">{{ Session::get('error_message') }}</h4>
                    </div>
                    <br>
                    <br>
                    @endif
                    <form role="form" method="POST" action="{{ url('/forgetPasswordProcess') }}">
                        @csrf
                        <div class="form-group">
                            <label for="">Password</label>
                            <input type="password" class="form-control" name="password">
                            <button type="button" class="psw_show_btn"><img
                                    src="{{ asset('asset/customer/assets/images/eye.svg') }}" alt="eye"></button>
                        </div>
                        <div class="form-group">
                            <label for="">Confirm password</label>
                            <input type="password" class="form-control" name="password_confirmation">
                            <button type="button" class="psw_show_btn"><img
                                    src="{{ asset('asset/customer/assets/images/eye.svg') }}" alt="eye"></button>
                        </div>
                        <input type="submit" class="signup_btn btn_purple hover_effect1 auth_btn" value="Change">
                        <!-- <button type="button" class="signup_btn btn_purple hover_effect1 auth_btn">Done</button> -->
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- otp verification modal -->
<div class="modal fade auth_modals" id="otpverification2">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal_body_head text-center">
                    <h3>OTP VERIFICATION</h3>

                </div>
                <div class="modal_body_content text-center otp_verification">
                    <p>Please enter the One Time Password received on your registered mobile number to verify
                        your account.</p>

                    <h3>VERIFY YOUR ACCOUNT</h3>
                    @if(Session::has('error_message'))
                    <div class="error" style="text-align:center;">
                        <b>
                            <h4 class="error">{{ Session::get('error_message') }}</h4>
                        </b>
                    </div>
                    <br>
                    @endif
                    @if($errors->has('num1'))
                    <div class="error">{{ $errors->first('num1') }}</div>
                    @endif
                    @if($errors->has('num2'))
                    <div class="error">{{ $errors->first('num2') }}</div>
                    @endif
                    @if($errors->has('num3'))
                    <div class="error">{{ $errors->first('num3') }}</div>
                    @endif
                    @if($errors->has('num4'))
                    <div class="error">{{ $errors->first('num4') }}</div>
                    @endif
                    <form role="form" method="POST" action="{{ url('/verifyOtpLogin') }}">
                        @csrf
                        <input type="number" maxlength="1" name="num1">
                        <input type="number" maxlength="1" name="num2">
                        <input type="number" maxlength="1" name="num3">
                        <input type="number" maxlength="1" name="num4" class="mr-0">

                        <p class="text-right"><a href="javascript:void(0);" class="resend_link">Resend OTP </a> <span
                                class="timer">0:00</span></p>
                        <input type="submit" value="Verify" class="signup_btn btn_purple hover_effect1 auth_btn">

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('customer.include.footer')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@if(Session::has('modal_check'))
<script>
    $(window).on('load', function() {
        $('#otpverification').modal('show');
    })
</script>
@endif
@if(Session::has('forget_pwd_modal_check'))
<script>
    $(window).on('load', function() {
        $('#restpassword').modal('show');
    })
</script>
@endif
@if(Session::has('forget_pwd_snd_otp_modal_check'))
<script>
    $(window).on('load', function() {
        $('#forgot_psw').modal('show');
    })
</script>
@endif
@if(Session::has('modal_check2'))
<script>
    $(window).on('load', function() {
        $('#otpverification2').modal('show');
    })
</script>
@endif
