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
                <div class="card-title text-uppercase text-center py-3">Sign In
                    @if(Session::has('message'))
                    <div class="error" style="text-align:center;font-size:16px;">
                        {{ Session::get('message') }}</div>
                    @endif
                </div>
                <form role="form" method="POST" action="{{ url('/Restaurent/login') }}">
                    @csrf
                    <div class="form-group">
                        <div class="position-relative has-icon-right">
                            <label for="exampleInputUsername" class="sr-only">User-Id</label>
                            <input type="text" id="exampleInputUsername" name="user_id"
                                class="form-control form-control-rounded" placeholder="Mobile">
                            <div class="form-control-position">
                                <i class="icon-user"></i>
                            </div>
                        </div>
                        @if($errors->has('user_id'))
                        <div class="error">{{ $errors->first('user_id') }}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <div class="position-relative has-icon-right">
                            <label for="exampleInputPassword" class="sr-only">Password</label>
                            <input type="password" id="exampleInputPassword" name="password"
                                class="form-control form-control-rounded" placeholder="Password">
                            <div class="form-control-position">
                                <i class="icon-lock"></i>
                            </div>
                        </div>
                        @if($errors->has('password'))
                        <div class="error">{{ $errors->first('password') }}</div>
                        @endif
                    </div>
                    <div class="form-row mr-0 ml-0">
                        <div class="form-group col-6">

                        </div>
                        <div class="form-group col-6 text-right">
                            <a href="authentication-reset-password.html">Reset Password</a>
                        </div>
                    </div>
                    <input type="submit"
                        class="btn btn-primary shadow-primary btn-round btn-block waves-effect waves-light" value="Log
                        In">
                </form>
            </div>
        </div>
    </div>

    <!--Start Back To Top Button-->
    <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>
    <!--End Back To Top Button-->
</div>
<!--wrapper-->
