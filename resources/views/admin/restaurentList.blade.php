<!--Data Tables -->
<link href="{{asset('asset/admin/assets/plugins/bootstrap-datatable/css/dataTables.bootstrap4.min.css')}}"
    rel="stylesheet" type="text/css">
<link href="{{asset('asset/admin/assets/plugins/bootstrap-datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet"
    type="text/css">

@include('admin.include.sideNav')
@include('admin.include.header')
<div class="clearfix"></div>

<div class="content-wrapper">
    <div class="container-fluid">
        <!-- End Breadcrumb-->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header"><i class="fa fa-table"></i> Restaurant List
                        @if(Session::has('message'))
                        <div class="error" style="text-align:center;">
                            <h4 class="error">{{ Session::get('message') }}</h4>
                        </div>

                        @endif
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>S.No.</th>
                                        <th>Properietor Name</th>
                                        <th>Restaurant Name</th>
                                        <th>About</th>
                                        <th>Email</th>
                                        <th>Contact Number</th>
                                        <th>Address</th>
                                        <th>Open Time</th>
                                        <th>Close Time</th>
                                        <th>Create At</th>

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
                ajax: "{{url('adminfimihub/retaurantList')}}",
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
                        data: 'prop_name',
                        name: 'prop_name'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'about',
                        name: 'about'
                    },
                    {
                        data: 'user_email',
                        name: 'user_email'
                    },
                    {
                        data: 'user_mobile',
                        name: 'user_mobile'
                    },
                    {
                        data: 'address',
                        name: 'address'
                    },
                    {
                        data: 'open_time',
                        name: 'open_time'
                    },
                    {
                        data: 'close_time',
                        name: 'close_time'
                    },

                    {
                        data: 'user_created_at',
                        name: 'user_created_at'
                    },
                ]
            });
            table.buttons().container()
                .appendTo('#example_wrapper .col-md-6:eq(0)');
        });
    </script>
    <!--End content-wrapper-->
