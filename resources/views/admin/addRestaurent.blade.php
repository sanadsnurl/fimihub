@include('admin.include.sideNav')
@include('admin.include.header')

<div class="clearfix"></div>

<div class="content-wrapper">
    <div class="container-fluid">

        <!-- End Breadcrumb-->
        <div class="row">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-body">
                        <form role="form" method="POST" action="{{ url('adminfimihub/addRestaurent')}}"
                            id="personal-info">
                            @csrf
                            <h4 class="form-header text-uppercase">
                                <i class="fa fa-user-circle-o"></i>
                                Add Restaurent
                            </h4>
                            @if(Session::has('message'))
                            <div class="error" style="text-align:center;">
                                <h4 class="error">{{ Session::get('message') }}</h4>
                            </div>

                            @endif
                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Proprietor Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="input-1" name="name" value="{{ old('name') }}">
                                    @if($errors->has('name'))
                                    <div class="error">{{ $errors->first('name') }}</div>
                                    @endif
                                </div>

                            </div>

                            <div class="form-group row">
                                <label for="input-3" class="col-sm-2 col-form-label">E-mail</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control" id="input-3" name="email" value="{{ old('email') }}">
                                    @if($errors->has('email'))
                                    <div class="error">{{ $errors->first('email') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="input-4" class="col-sm-2 col-form-label">Contact Number</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="input-4" name="mobile" value="{{ old('mobile') }}">
                                    @if($errors->has('mobile'))
                                    <div class="error">{{ $errors->first('mobile') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="input-4" class="col-sm-2 col-form-label">Password</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" id="input-4" name="password" value="{{ old('password') }}">
                                    @if($errors->has('password'))
                                    <div class="error">{{ $errors->first('password') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="input-4" class="col-sm-2 col-form-label">Confirm Password</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" id="input-4"
                                        name="password_confirmation" value="{{ old('password_confirmation') }}">
                                    @if($errors->has('password_confirmation'))
                                    <div class="error">{{ $errors->first('password_confirmation') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-footer">
                                <input type="submit" class="btn btn-primary" value="Add Restaurent"></input>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--End Row-->

    </div>
    <!-- End container-fluid-->

</div>
<!--End content-wrapper-->
@include('admin.include.footer')