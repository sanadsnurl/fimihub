@include('restaurent.header')
<!-- Start wrapper-->
<div id="wrapper">
    <div
        class="card border-primary border-top-sm border-bottom-sm card-authentication1 mx-auto my-5 animated bounceInDown">
        <div class="card-body">
            <div class="card-content p-2">
                <div class="text-center">
                    <img src="{{asset('asset/customer/assets/images/logo.png')}}">
                </div>
                <div class="card-title text-uppercase text-center py-3">Forget Password
                    @if(Session::has('message'))
                    <div class="error" style="text-align:center;font-size:16px;">
                        {{ Session::get('message') }}</div>
                    @endif
                </div>
                <form role="form" method="POST" action="{{ url('/Restaurent/forgetPasswordProcess') }}">
                    @csrf
                    <div class="form-group">
                        <div class="position-relative has-icon-right">
                            <label for="exampleInputUsername" class="sr-only">User-Id</label>
                            <input type="text" id="exampleInputUsername" name="phone_number"
                                class="form-control form-control-rounded" placeholder="Mobile">
                            <div class="form-control-position">
                                <i class="icon-user"></i>
                            </div>
                        </div>
                        @if($errors->has('phone_number'))
                        <div class="error">{{ $errors->first('phone_number') }}</div>
                        @endif
                    </div>

                    <input type="submit"
                        class="btn btn-primary shadow-primary btn-round btn-block waves-effect waves-light" value="Send OTP">
                </form>
            </div>
        </div>
    </div>

    <!--Start Back To Top Button-->
    <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
    <!--End Back To Top Button-->
</div>
<!--wrapper-->
