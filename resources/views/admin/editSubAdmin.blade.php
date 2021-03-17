@include('admin.include.sideNav')
@include('admin.include.header')
<!--Data Tables -->
<link href="{{asset('asset/admin/assets/plugins/bootstrap-datatable/css/dataTables.bootstrap4.min.css')}}"
    rel="stylesheet" type="text/css">
<link href="{{asset('asset/admin/assets/plugins/bootstrap-datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet"
    type="text/css">

<div class="clearfix"></div>

<div class="content-wrapper">
    <div class="container-fluid">

        <!-- End Breadcrumb-->
        <div class="row">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-body">
                        <form role="form" method="POST" action="{{ url('adminfimihub/editSubAdminProcess')}}" id="personal-info"
                            enctype="multipart/form-data">
                            @csrf
                            <h4 class="form-header text-uppercase">
                                <i class="fa fa-cutlery"></i>
                                Edit Category
                            </h4>
                            @if(Session::has('message'))
                            <div class="error" style="text-align:center;">
                                <h4 class="error">{{ Session::get('message') }}</h4>
                            </div>

                            @endif
                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="input-1" name="name"
                                        value="{{ old('name') ?? ($sub_admin->name ?? '')}}">
                                        <input type="hidden" name="id" value="{{$sub_admin->id ?? ''}}">
                                    @if($errors->has('name'))
                                    <div class="error">{{ $errors->first('name') }}</div>
                                    @endif
                                </div>

                            </div>
                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control" id="input-1" name="email"
                                        value="{{old('email') ?? ($sub_admin->email ?? '')}}" disabled>
                                    @if($errors->has('email'))
                                    <div class="error">{{ $errors->first('email') }}</div>
                                    @endif
                                </div>

                            </div>
                            <div class="form-group row">
                                <label for="input-4" class="col-sm-2 col-form-label">Role</label>
                                <div class="demo-checkbox ml-4">
                                    <input type="checkbox" id="user-checkbox" class="filled-in chk-col-primary"
                                        value="1" name="role[]" {{in_array(1,($sub_admin->role ?? [])) ? 'checked':''}}>
                                    <label for="user-checkbox">Rider Management</label>
                                </div>
                                <div class="demo-checkbox">
                                    <input type="checkbox" id="user-checkbox1" class="filled-in chk-col-primary"
                                        value="2" name="role[]" {{in_array(2,($sub_admin->role ?? [])) ? 'checked':''}}>
                                    <label for="user-checkbox1">Restaurant Management</label>
                                </div>
                                <div class="demo-checkbox ml-5">
                                    <input type="checkbox" id="user-checkbox11" class="filled-in chk-col-primary"
                                        value="3" name="role[]" {{in_array(3,($sub_admin->role ?? [])) ? 'checked':''}}>
                                    <label for="user-checkbox11">Order Management</label>
                                </div>
                                <br>
                                @if($errors->has('role'))
                                <div class="error">{{ $errors->first('role') }}</div>
                                @endif
                            </div>

                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Password</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" id="input-1" name="password"
                                         placeholder="Only Use In-case of Reset Password">
                                        <br>
                                        <span><h6>Note : Only Use In-case of Reset Password</h6> </span>
                                    @if($errors->has('password'))
                                    <div class="error">{{ $errors->first('password') }}</div>
                                    @endif
                                </div>

                            </div>

                            <div class="form-footer">
                                <input type="submit" class="btn btn-primary" value="Update"></input>
                                <a href="{{url('adminfimihub/getSubAdmin')}}" >
                                    <span class="btn btn-danger">Back</span>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- End container-fluid-->

</div>

<!-- End container-fluid-->

<!--End content-wrapper-->
@include('admin.include.footer')

<!--End content-wrapper-->
