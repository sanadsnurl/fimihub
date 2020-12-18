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
                    <div class="card-header"><i class="fa fa-table"></i> Rider List</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="table table-bordered">
                                <thead>
                                    <tr>
                                        {{-- <th>Action</th> --}}
                                        <th>S.No.</th>
                                        <th>Name</th>
                                        <th>Mobile</th>
                                        <th>Email</th>
                                        <th>Vehicle Number</th>
                                        <th>Vehicle Image</th>
                                        <th>Model Name</th>
                                        <th>color</th>
                                        <th>ID-Proof</th>
                                        <th>Address</th>
                                        <th>Pincode</th>
                                        <th>Driving License</th>
                                        <th>DL Start Date</th>
                                        <th>DL End Date</th>
                                        <th>Registration Start Date</th>
                                        <th>Registration End Date</th>
                                        <th>Account Number</th>
                                        <th>Holder Name</th>
                                        <th>IFSC</th>
                                        <th>Branch Name</th>
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
    <script src="{{url('asset/admin/assets/js/jquery.min.js')}}"></script>
    <!-- waves effect js -->
    <script src="{{url('asset/admin/assets/js/waves.js')}}"></script>
    <!--Data Tables js-->
    <script src="{{url('asset/admin/assets/plugins/bootstrap-datatable/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{url('asset/admin/assets/plugins/bootstrap-datatable/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{url('asset/admin/assets/plugins/bootstrap-datatable/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{url('asset/admin/assets/plugins/bootstrap-datatable/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{url('asset/admin/assets/plugins/bootstrap-datatable/js/jszip.min.js')}}"></script>
    <script src="{{url('asset/admin/assets/plugins/bootstrap-datatable/js/pdfmake.min.js')}}"></script>
    <script src="{{url('asset/admin/assets/plugins/bootstrap-datatable/js/vfs_fonts.js')}}"></script>
    <script src="{{url('asset/admin/assets/plugins/bootstrap-datatable/js/buttons.html5.min.js')}}"></script>
    <script src="{{url('asset/admin/assets/plugins/bootstrap-datatable/js/buttons.print.min.js')}}"></script>
    <script src="{{url('asset/admin/assets/plugins/bootstrap-datatable/js/buttons.colVis.min.js')}}"></script>

    <script>
    $(document).ready(function() {
        //Default data table
        $('#default-datatable').DataTable();

        var table = $('#example').DataTable({
            lengthChange: true,
            processing: true,
            serverSide: true,
            paging: true,
            dom: 'lBfrtip',
            buttons: ['copy', 'excel', 'pdf', 'print'],
            ajax: "{{url('adminfimihub/riderList')}}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'mobile',
                    name: 'mobile'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'vehicle_details.vehicle_number',
                    name: 'vehicle_details.vehicle_number'
                },
                {
                    data: 'vehicle_image',
                    name: 'vehicle_image',
                    orderable: true,
                    searchable: false
                },
                {
                    data: 'vehicle_details.model_name',
                    name: 'vehicle_details.model_name'
                },
                {
                    data: 'vehicle_details.color',
                    name: 'vehicle_details.color'
                },
                {
                    data: 'id_proof',
                    name: 'id_proof'
                },
                {
                    data: 'vehicle_details.address',
                    name: 'vehicle_details.address'
                },
                {
                    data: 'vehicle_details.pincode',
                    name: 'vehicle_details.pincode'
                },
                {
                    data: 'driving_license',
                    name: 'driving_license'
                },
                {
                    data: 'vehicle_details.dl_start_date',
                    name: 'vehicle_details.dl_start_date'
                },
                {
                    data: 'vehicle_details.dl_end_date',
                    name: 'vehicle_details.dl_end_date'
                },
                {
                    data: 'vehicle_details.registraion_start_date',
                    name: 'vehicle_details.registraion_start_date'
                },
                {
                    data: 'vehicle_details.registraion_end_date',
                    name: 'vehicle_details.registraion_end_date'
                },
                {
                    data: 'rider_bank_details.account_number',
                    name: 'rider_bank_details.account_number'
                },
                {
                    data: 'rider_bank_details.holder_name',
                    name: 'rider_bank_details.holder_name'
                },
                {
                    data: 'rider_bank_details.branch_name',
                    name: 'rider_bank_details.branch_name'
                },
                {
                    data: 'rider_bank_details.ifsc_code',
                    name: 'rider_bank_details.ifsc_code'
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
