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
        <!--End Row-->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header"><i class="fa fa-table"></i> Order List
                        @if(Session::has('message'))
                        <div class="error" style="text-align:center;">
                            <h4 class="error">{{ Session::get('message') }}</h4>
                        </div>

                        @endif

                        <a href="{{url('adminfimihub/customerOrder')}}" >
                            <span class="btn btn-danger" style="float: right;">Refresh</span>

                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <!-- <th>S.no</th> -->
                                        <th>Action</th>

                                        <th>S.No.</th>
                                        <th>Order Id</th>
                                        <th>Restaurant Name</th>
                                        <th>Restaurant Mobile</th>
                                        <th>Customer Mobile</th>
                                        <th>Customer Name</th>
                                        <th>Order Status</th>
                                        <th>Dish</th>
                                        <th>Total Amount</th>
                                        <th>Payment Method</th>
                                        <th>Order Date</th>

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
            ajax: "{{url('adminfimihub/customerOrder')}}",
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
                    data: 'order_id',
                    name: 'order_id'
                },
                {
                    data: 'restaurent_details',
                    render: function(data, type, row) {
                        if (row.restaurent_details) {
                            return row.restaurent_details.name;
                        }
                        return 'N.A';
                    },
                },
                {
                    data: 'restaurent_details.official_number',
                    render: function(data, type, row) {
                        if (row.restaurent_details) {
                            console.log( row.restaurent_details);
                            return row.restaurent_details.official_number;
                        }
                        return 'N.A';
                    },
                },
                {
                    data: 'mobile',
                    name: 'mobile'
                },
                {
                    data: 'customer_name',
                    name: 'customer_name'
                },
                {
                    data: 'order_status',
                    name: 'order_status'
                },
                {
                    data: 'ordered_menu',
                    name: 'ordered_menu'
                },
                {
                    data: 'total_amount',
                    name: 'total_amount'
                },
                {
                    data: 'payment_type',
                    name: 'payment_type'
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
