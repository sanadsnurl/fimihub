<!--Data Tables -->
<link href="{{url('asset/admin/assets/plugins/bootstrap-datatable/css/dataTables.bootstrap4.min.css')}}"
    rel="stylesheet" type="text/css">
<link href="{{url('asset/admin/assets/plugins/bootstrap-datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet"
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
                    <div class="card-header"><i class="fa fa-table"></i> New Request List</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <!-- <th>S.no</th> -->
                                        <th>S.No.</th>
                                        <th>Properietor Name</th>
                                        <th>Email</th>
                                        <th>Contact Number</th>
                                        <th>Create At</th>
                                        <th>Action</th>

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
        $('#default-datatable').DataTable();

        var table = $('#example').DataTable({
            lengthChange: true,
            processing:true,
            serverSide:true,
            paging: true,
            dom: 'lBfrtip',
            buttons: ['copy', 'excel', 'pdf', 'print'],
            ajax: "{{url('adminfimihub/pendingRetaurant')}}",
            columns: [{
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
                    data: 'mobile',
                    name: 'mobile'
                },

                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: true,
                    searchable: false
                },


            ]
        });

        table.buttons().container()
            .appendTo('#example_wrapper .col-md-6:eq(0)');

    });
    </script>
    <!--End content-wrapper-->
