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
                        <form role="form" method="POST" action="{{ url('adminfimihub/addSubAdmin')}}" id="personal-info"
                            enctype="multipart/form-data">
                            @csrf
                            <h4 class="form-header text-uppercase">
                                <i class="fa fa-cutlery"></i>
                                Add Sub-Admin
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
                                        value="{{old('name')}}">

                                    @if($errors->has('name'))
                                    <div class="error">{{ $errors->first('name') }}</div>
                                    @endif
                                </div>

                            </div>
                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control" id="input-1" name="email"
                                        value="{{old('email')}}">
                                    @if($errors->has('email'))
                                    <div class="error">{{ $errors->first('email') }}</div>
                                    @endif
                                </div>

                            </div>
                            <div class="form-group row">
                                <label for="input-4" class="col-sm-2 col-form-label">Role</label>
                                <div class="demo-checkbox ml-4">
                                    <input type="checkbox" id="user-checkbox" class="filled-in chk-col-primary"
                                        value="1" name="role[]">
                                    <label for="user-checkbox">Rider Management</label>
                                </div>
                                <div class="demo-checkbox">
                                    <input type="checkbox" id="user-checkbox1" class="filled-in chk-col-primary"
                                        value="2" name="role[]">
                                    <label for="user-checkbox1">Restaurant Management</label>
                                </div>
                                <div class="demo-checkbox ml-5">
                                    <input type="checkbox" id="user-checkbox11" class="filled-in chk-col-primary"
                                        value="3" name="role[]">
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
                                        value="{{old('password')}}">
                                    @if($errors->has('password'))
                                    <div class="error">{{ $errors->first('password') }}</div>
                                    @endif
                                </div>

                            </div>

                            <div class="form-footer">
                                <input type="submit" class="btn btn-primary" value="Add"></input>
                                <a href="{{url()->previous()}}" >
                                    <span class="btn btn-danger">Back</span>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--End Row-->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header"><i class="fa fa-table"></i> Category List</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>S.No.</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <!-- <th>Discount (%)</th> -->
                                        <th>Role</th>
                                        <th>Created At</th>
                                        <!-- <th>Action</th> -->

                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- End Row-->
    </div>
    <!-- End container-fluid-->

</div>

<!-- End container-fluid-->

<!--End content-wrapper-->
@include('admin.include.footer')
<!-- Bootstrap core JavaScript-->
<script src="{{asset('asset/admin/assets/js/jquery.min.js')}}"></script>
<!-- waves effect js -->
<script src="{{asset('asset/admin/assets/js/waves.js')}}"></script>
<!--Data Tables js-->
<script src="{{asset('asset/admin/assets/plugins/bootstrap-datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('asset/admin/assets/plugins/bootstrap-datatable/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('asset/admin/assets/plugins/bootstrap-datatable/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('asset/admin/assets/plugins/bootstrap-datatable/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('asset/admin/assets/plugins/bootstrap-datatable/js/jszip.min.js')}}"></script>
<script src="{{asset('asset/admin/assets/plugins/bootstrap-datatable/js/pdfmake.min.js')}}"></script>
<script src="{{asset('asset/admin/assets/plugins/bootstrap-datatable/js/vfs_fonts.js')}}"></script>
<script src="{{asset('asset/admin/assets/plugins/bootstrap-datatable/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('asset/admin/assets/plugins/bootstrap-datatable/js/buttons.print.min.js')}}"></script>
<script src="{{asset('asset/admin/assets/plugins/bootstrap-datatable/js/buttons.colVis.min.js')}}"></script>

<script>
    $(document).ready(function() {
        //Default data table
        //$('#default-datatable').DataTable();
        var table = $('#example').DataTable({
            lengthChange: true,
            processing: true,
            serverSide: true,
            paging: true,
            dom: 'lBfrtip',
            buttons: ['copy', 'excel', 'pdf', 'print'],
            ajax: "{{url('adminfimihub/getSubAdmin')}}",
            columns: [{
                        data: 'action',
                        name: 'action',
                        orderable: true,
                        searchable: false
                    },
                    {
                    data: 'DT_RowIndex',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'role',
                    name: 'role'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
            ]
        });
        table.buttons().container()
            .appendTo('#example_wrapper .col-md-6:eq(0)');
    });
</script>
<!--End content-wrapper-->
