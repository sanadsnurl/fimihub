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
                    <div class="card-header"><i class="fa fa-table"></i> Rider List
                        @if(Session::has('message'))
                        <div class="error" style="text-align:center;">
                            <h4 class="error">{{ Session::get('message') }}</h4>
                        </div>

                        @endif

                        <a href="{{url()->previous()}}" style="float: right" >
                            <span class="btn btn-danger">Back</span>
                        </a>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>S.No.</th>
                                        <th>Type</th>
                                        <th>Name</th>
                                        <th>Mobile</th>
                                        <th>Email</th>
                                        <th>Vehicle Number</th>
                                        <th>Vehicle Registration</th>
                                        <th>Vehicle Image</th>
                                        <th>Model Name</th>
                                        <th>color</th>
                                        <th>ID-Proof</th>
                                        <th>Address</th>
                                        <th>Zip Code</th>
                                        <th>Driving License</th>
                                        <th>Background Check</th>
                                        <th>Food Permit</th>
                                        <th>DL Start Date</th>
                                        <th>DL End Date</th>
                                        <th>Insurance Policy</th>
                                        <th>Insurance Company</th>
                                        <th>Insurance Start Date</th>
                                        <th>Insurance End Date</th>
                                        <th>Registration Start Date</th>
                                        <th>Registration End Date</th>
                                        <th>Bank Name</th>
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

            var table = $('#example1').DataTable({
                lengthChange: true,
                processing: true,
                serverSide: true,
                paging: true,
                dom: 'lBfrtip',
                buttons: ['copy', 'excel', 'pdf', 'print'],
                ajax: "{{url('adminfimihub/riderList')}}",
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
                        data: 'role',
                        name: 'role'
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
                        data: 'vehicle_details',
                        render: function(data, type, row) {
                            if (row.vehicle_details) {
                                return row.vehicle_details.vehicle_number;
                            }
                            return 'N.A';
                        },
                    },
                    {
                        data: 'vehicle_details',
                        render: function(data, type, row) {
                            if (row.vehicle_details) {
                                return row.vehicle_details.registration_number;
                            }
                            return 'N.A';
                        },
                    },
                    {
                        data: 'vehicle_image',
                        name: 'vehicle_image',
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: 'vehicle_details',
                        render: function(data, type, row) {
                            if (row.vehicle_details) {
                                return row.vehicle_details.model_name;
                            }
                            return 'N.A';
                        },
                    },
                    {
                        data: 'vehicle_details',
                        render: function(data, type, row) {
                            if (row.vehicle_details) {
                                return row.vehicle_details.color;
                            }
                            return 'N.A';
                        },
                    },
                    {
                        data: 'id_proof',
                        name: 'id_proof'
                    },
                    {
                        data: 'vehicle_details',
                        render: function(data, type, row) {
                            if (row.vehicle_details) {
                                return row.vehicle_details.address;
                            }
                            return 'N.A';
                        },
                    },
                    {
                        data: 'vehicle_details',
                        render: function(data, type, row) {
                            if (row.vehicle_details) {
                                return row.vehicle_details.pincode;
                            }
                            return 'N.A';
                        },
                    },
                    {
                        data: 'driving_license',
                        name: 'driving_license'
                    },
                    {
                        data: 'background_check',
                        name: 'background_check'
                    },
                    {
                        data: 'food_permit',
                        name: 'food_permit'
                    },
                    {
                        data: 'vehicle_details',
                        render: function(data, type, row) {
                            if (row.vehicle_details) {
                                return row.vehicle_details.dl_start_date;
                            }
                            return 'N.A';
                        },
                    },
                    {
                        data: 'vehicle_details',
                        render: function(data, type, row) {
                            if (row.vehicle_details) {
                                return row.vehicle_details.dl_end_date;
                            }
                            return 'N.A';
                        },
                    },
                    {
                        data: 'vehicle_details',
                        render: function(data, type, row) {
                            if (row.vehicle_details) {
                                return row.vehicle_details.policy_company;
                            }
                            return 'N.A';
                        },
                    },
                    {
                        data: 'vehicle_details',
                        render: function(data, type, row) {
                            if (row.vehicle_details) {
                                return row.vehicle_details.insurance_company;
                            }
                            return 'N.A';
                        },
                    },
                    {
                        data: 'vehicle_details',
                        render: function(data, type, row) {
                            if (row.vehicle_details) {
                                return row.vehicle_details.insurance_start_date;
                            }
                            return 'N.A';
                        },
                    },
                    {
                        data: 'vehicle_details',
                        render: function(data, type, row) {
                            if (row.vehicle_details) {
                                return row.vehicle_details.insurance_end_date;
                            }
                            return 'N.A';
                        },
                    },
                    {
                        data: 'vehicle_details',
                        render: function(data, type, row) {
                            if (row.vehicle_details) {
                                return row.vehicle_details.registraion_start_date;
                            }
                            return 'N.A';
                        },
                    },
                    {
                        data: 'vehicle_details',
                        render: function(data, type, row) {
                            if (row.vehicle_details) {
                                return row.vehicle_details.registraion_end_date;
                            }
                            return 'N.A';
                        },
                    },
                    {
                        data: 'rider_bank_details',
                        render: function(data, type, row) {
                            if (row.rider_bank_details) {
                                return row.rider_bank_details.bank_name;
                            }
                            return 'N.A';
                        },
                    },
                    {
                        data: 'rider_bank_details',
                        render: function(data, type, row) {
                            if (row.rider_bank_details) {
                                return row.rider_bank_details.account_number;
                            }
                            return 'N.A';
                        },
                    },
                    {
                        data: 'rider_bank_details',
                        render: function(data, type, row) {
                            if (row.rider_bank_details) {
                                return row.rider_bank_details.holder_name;
                            }
                            return 'N.A';
                        },
                    },
                    {
                        data: 'rider_bank_details',
                        render: function(data, type, row) {
                            if (row.rider_bank_details) {
                                return row.rider_bank_details.branch_name;
                            }
                            return 'N.A';
                        },
                    },
                    {
                        data: 'rider_bank_details',
                        render: function(data, type, row) {
                            if (row.rider_bank_details) {
                                return row.rider_bank_details.ifsc_code;
                            }
                            return 'N.A';
                        },
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
