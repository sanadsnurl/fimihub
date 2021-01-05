@include('customer.include.header')
<!-- signup banner -->
<section class="signup_banner auth_banner">
    <div class="container lg_container">
        <div class="auth_form_wraper">
            <div class="row">
                <div class="col-md-6 pr-0">
                    <div class="auth_form_left h-100">
                        <h3>Partner with us</h3>
                        @if(Session::has('message'))
                        <div class="error" style="text-align:center;">
                            <h4 class="error">{{ Session::get('message') }}</h4>
                        </div>
                        <br>
                        <br>
                        @endif
                        <form role="form" method="POST" action="{{ url('/partnerRegisterProcess') }}">
                            @csrf
                            <div class="form-group">
                                <label for="">Full Name</label>
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                                @if($errors->has('name'))
                                <div class="error">{{ $errors->first('name') }}</div>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="">Phone No.</label>
                                <input type="number" class="form-control" name="mobile" value="{{ old('mobile') }}">
                                @if($errors->has('mobile'))
                                <div class="error">{{ $errors->first('mobile') }}</div>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="">Email</label>
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                                @if($errors->has('email'))
                                <div class="error">{{ $errors->first('email') }}</div>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="">Password</label>
                                <input type="password" class="form-control" name="password"
                                    value="{{ old('password') }}">
                                @if($errors->has('password'))
                                <div class="error">{{ $errors->first('password') }}</div>
                                @endif
                            </div>
                            <div class="form-group margin_btm">
                                <label for="">Confirm password</label>
                                <input type="password" class="form-control" name="password_confirmation"
                                    value="{{ old('password_confirmation') }}">
                                @if($errors->has('password_confirmation'))
                                <div class="error">{{ $errors->first('password_confirmation') }}</div>
                                @endif
                            </div>

                            <input type="submit" class="signup_btn btn_purple hover_effect1 auth_btn"
                                value="SEND REQUEST">

                            <!-- <button type="button" class="signup_btn btn_purple hover_effect1 auth_btn"
                                data-toggle="modal" data-target="#restpassword">SIGN UP</button> -->

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
<!-- signup banner end -->

<!--============ modals ==========-->
<!-- otp verification modal -->
<div class="modal fade auth_modals" id="otpverification">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal_body_head text-center">
                    <h3>OTP VERIFICATION</h3>

                </div>
                <div class="modal_body_content text-center otp_verification">
                    <p>Please enter the One Time Password received on your registered mobile number or email to verify
                        your account.</p>
                    @if(Session::has('message'))
                    <div class="error" style="text-align:center;">
                        <b>{{ Session::get('message') }}</b>
                    </div>
                    @endif
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
                    <form role="form" method="POST" action="{{ url('/verifyOtp') }}">
                        @csrf
                        <input type="number" maxlength="1" name="num1">
                        <input type="number" maxlength="1" name="num2">
                        <input type="number" maxlength="1" name="num3">
                        <input type="number" maxlength="1" name="num4" class="mr-0">

                        <p class="text-right"><a href="javascript:void(0);" class="resend_link">Resend OTP </a> <span
                                class="timer">0:00</span></p>
                        <input type="submit" value="Verify" class="signup_btn btn_purple hover_effect1 auth_btn">
                        <!-- <button type="button" class="signup_btn btn_purple hover_effect1 auth_btn">Verify</button> -->
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
