@include('admin.header')
<style>
.resend_link {
    cursor: not-allowed;
}

.resend_link.enabled {
    color: #7d3b8a;
    font-weight: 700;
    cursor: pointer;
}


.disabled_btn {
    background-color: #a0a0a0;
    cursor: not-allowed;
}
</style>
<!-- Start wrapper-->
<div id="wrapper">
    <div
        class="card border-primary border-top-sm border-bottom-sm card-authentication1 mx-auto my-5 animated bounceInDown">
        <div class="card-body">
            <div class="card-content p-2">
                <div class="text-center">
                    <img src="{{asset('asset/customer/assets/images/logo.png')}}">
                </div>
                <div class="card-title text-uppercase text-center py-3">OTP Verfication
                    @if(Session::has('message'))
                    <div class="error" style="text-align:center;font-size:16px;">
                        {{ Session::get('message') }}</div>
                    @endif
                </div>
                <form role="form" method="POST" action="{{ url('/Restaurent/verifyOtp') }}">
                    @csrf
                    <div class="form-group">
                        <div class="position-relative has-icon-right">
                            <label for="exampleInputUsername" class="sr-only">OTP</label>
                            <input type="text" id="exampleInputUsername" name="otp"
                                class="form-control form-control-rounded" placeholder="OTP">
                            <div class="form-control-position">

                            </div>
                        </div>
                        @if($errors->has('otp'))
                        <div class="error">{{ $errors->first('otp') }}</div>
                        @endif
                    </div>

                    <div class="form-row mr-0 ml-0">
                        <div class="form-group col-6">

                        </div>
                        <div class="form-group col-6 text-right">
                            <a href="javascript:void(0);" class="resend_link">Resend OTP</a>
                            <span class="timer">0:00</span>
                        </div>
                    </div>
                    <input type="submit"
                        class="btn btn-primary shadow-primary btn-round btn-block waves-effect waves-light"
                        value="Verify">
                </form>
            </div>
        </div>
    </div>

    <!--Start Back To Top Button-->
    <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
    <!--End Back To Top Button-->
</div>
<!--wrapper-->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="{{asset('asset\admin\assets\js\index.js')}}"></script>
