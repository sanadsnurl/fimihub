@include('admin.include.sideNav')
@include('admin.include.header')
<div class="clearfix"></div>

<div class="content-wrapper">
    <div class="container-fluid">
        <!-- Breadcrumb-->
        <div class="row pt-2 pb-2">
            <div class="col-sm-9">
                <h4 class="page-title">User Profile</h4>
            </div>
        </div>
        <!-- End Breadcrumb-->
        <div class="row">
            <div class="col-lg-4">
                <div class="profile-card-3">
                    <div class="card">
                        <div class="user-fullimage">
                            <img src="{{$user_profile_data->profile_picture ?? asset('asset/admin/assets/images/avatars/user.png')}}"
                                alt="user avatar" class="card-img-top">
                            <div class="details" style="background-color: black; padding: 2px; opacity: 0.7;">
                                <h5 class="mb-1 text-white ml-3">
                                    {{$user_data->name ?? ''}}
                                </h5>
                                <h6 class="text-white ml-3">
                                    {{$user_data->country_code ?? ''}} {{$user_data->mobile ?? ''}}
                                </h6>
                            </div>
                        </div>
                        <div class="card-body text-center">


                            <hr>
                            <a href="javascript:void():"
                                class="btn btn-danger  btn-sm btn-round waves-effect waves-light m-1">Block</a>
                            <a href="javascript:void():"
                                class="btn btn-outline-dark btn-sm btn-round waves-effect waves-light m-1">Transactions</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-tabs-primary top-icon nav-justified">
                            <li class="nav-item">
                                <a href="javascript:void();" data-target="#profile" data-toggle="pill"
                                    class="nav-link active"><i class="icon-user"></i> <span
                                        class="hidden-xs">Profile</span></a>
                            </li>
                            <!-- <li class="nav-item">
                                <a href="javascript:void();" data-target="#messages" data-toggle="pill"
                                    class="nav-link"><i class="icon-wallet"></i> <span
                                        class="hidden-xs">Wallet</span></a>
                            </li> -->
                            <li class="nav-item">
                                <a href="javascript:void();" data-target="#edit" data-toggle="pill" class="nav-link"><i
                                        class="icon-note"></i> <span class="hidden-xs">Edit</span></a>
                            </li>
                        </ul>
                        <div class="tab-content p-3">
                            <div class="tab-pane active" id="profile">
                                <h5 class="mb-3">User Profile</h5>
                                @if(Session::has('message'))
                                <div class="error" style="text-align:center;font-size:21px;">
                                    {{ Session::get('message') }}</div>
                                @endif
                                <div class="row">

                                    <table class="table table-hover table-striped mx-5">
                                        <tbody>

                                            <tr>
                                                <td>
                                                    <span
                                                        class="float-right font-weight-bold">{{$user_data->name ?? ''}}</span>
                                                    Name
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span
                                                        class="float-right font-weight-bold">{{$user_profile_data->dob ?? 'N.A'}}</span>
                                                    D.O.B
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span class="float-right font-weight-bold">
                                                        @if(empty($user_data->mobile_verified_at))
                                                        <div class="status">
                                                            {{$user_data->country_code ?? ''}}
                                                            {{$user_data->mobile ?? ''}}
                                                            <span style="color:red; font-weight:600;">not-verified <i
                                                                    class="icon-info"></i></span>
                                                        </div>
                                                        @endif
                                                        @if(!empty($user_data->mobile_verified_at))
                                                        <div class="status">
                                                            {{$user_data->country_code ?? ''}}
                                                            {{$user_data->mobile ?? ''}}
                                                            <span style="color:green; font-weight:600;">verified <i
                                                                    class="icon-check"></i></span>
                                                        </div>
                                                        @endif
                                                    </span> Mobile
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span class="float-right font-weight-bold">
                                                        @if(empty($user_data->email_verified_at))
                                                        <div class="status">
                                                            {{$user_data->email ?? 'N.A'}}
                                                            <span style="color:red; font-weight:600;">not-verified <i
                                                                    class="icon-info"></i></span>
                                                        </div>
                                                        @endif
                                                        @if(!empty($user_data->email_verified_at))
                                                        <div class="status">
                                                            {{$user_data->email ?? 'N.A'}}
                                                            <span style="color:green; font-weight:600;">verified <i
                                                                    class="icon-check"></i></span>
                                                        </div>
                                                        @endif
                                                    </span> Email

                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span class="float-right font-weight-bold">
                                                        @if(!empty($user_profile_data->gender))
                                                        @if($user_profile_data->gender==1)
                                                        Male
                                                        @endif
                                                        @if($user_profile_data->gender==2)
                                                        Female
                                                        @endif
                                                        @if($user_profile_data->gender==1)
                                                        Others
                                                        @endif

                                                        @endif
                                                        @if(empty($user_profile_data->gender))
                                                        N.A
                                                        @endif</span>
                                                    Gender
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span class="float-right font-weight-bold">
                                                        {{$data->currency}} <span
                                                            class="amt">{{number_format((float)$wallet_data->open_balance, 2, '.', '')}}</span>
                                                        <!-- {{$wallet_data->open_balance ?? ''}} -->
                                                    </span>Wallet
                                                    Balance
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!--/row-->
                            </div>
                            <div class="tab-pane" id="messages">
                                <table class="table table-hover table-striped">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <span
                                                    class="float-right font-weight-bold">{{$wallet_data->open_balance ?? ''}}</span>Total
                                                Balance
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="float-right font-weight-bold">Yesterday</span> There has
                                                been a request on your account since that was..
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="float-right font-weight-bold">9/10</span> Porttitor vitae
                                                ultrices quis, dapibus id dolor. Morbi venenatis lacinia rhoncus.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="float-right font-weight-bold">9/4</span> Vestibulum
                                                tincidunt ullamcorper eros eget luctus.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <span class="float-right font-weight-bold">9/4</span> Maxamillion ais
                                                the fix for tibulum tincidunt ullamcorper eros.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="edit">
                                @if(Session::has('message'))
                                <div class="error" style="text-align:center;font-size:21px;">
                                    {{ Session::get('message') }}</div>
                                @endif
                                <form role="form" method="POST" action="{{ url('adminQbeez/userUpdate') }}">
                                    @csrf
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label form-control-label">Name</label>
                                        <div class="col-lg-9">
                                            <input class="form-control" type="text" name="name"
                                                value="{{$user_data->name ?? ''}}">
                                            @if($errors->has('name'))
                                            <div class="error" style="color:red;">{{ $errors->first('name') }}</div>
                                            @endif
                                        </div>

                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label form-control-label">D.O.B</label>
                                        <div class="col-lg-9">
                                            <input class="form-control" type="date" name="dob"
                                                value="{{$user_profile_data->dob ?? ''}}">
                                            @if($errors->has('dob'))
                                            <div class="error" style="color:red;">{{ $errors->first('dob') }}</div>
                                            @endif
                                        </div>

                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label form-control-label">Email</label>
                                        <div class="col-lg-9">
                                            <input class="form-control" type="email" name="email"
                                                value="{{$user_data->email ?? ''}}">
                                            @if($errors->has('email'))
                                            <div class="error" style="color:red;">{{ $errors->first('email') }}</div>
                                            @endif
                                        </div>

                                    </div>

                                    <div class="form-group row">
                                        <label class="col-lg-3 col-form-label form-control-label"></label>
                                        <div class="col-lg-9">
                                            <!-- <input type="reset" class="btn btn-secondary" value="Cancel"> -->
                                            <input class="form-control" type="hidden" name="id"
                                                value="{{$user_data->id ?? ''}}">
                                            <input type="submit" class="btn btn-primary" value="Save Changes">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
    <!-- End container-fluid-->
</div>
<!--End content-wrapper-->
@include('admin.include.footer')
