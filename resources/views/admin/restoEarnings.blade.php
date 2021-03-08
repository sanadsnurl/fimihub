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
                    <div class="card-header"><i class="fa fa-money"></i> My Earnings
                        <span class="btn btn-success mr-5"
                        style="float: right;font-weight: bold;font-size: medium;">Total Earning :
                        {{$total_earning->resto_earning ?? 0}}</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <!-- <th>S.no</th> -->

                                        <th>S.No.</th>
                                        <th>Order Id</th>
                                        <th>Earning</th>
                                        <th>Total Amount Paid</th>
                                        <th>Delivery Fee</th>
                                        <th>GCT (in %)</th>
                                        <th>Total GCT (in $)</th>
                                        <th>Commission (in %)</th>
                                        <th>Total Commission (in $)</th>
                                        <th>Created At</th>

                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tr>
                                    <!-- <th>S.no</th> -->

                                    <th>TOTAL</th>
                                    <th> -- </th>
                                    <th>{{$total_earning->resto_earning ?? 0}}</th>
                                    <th>--</th>
                                    <th>--</th>
                                    <th>{{round($total_earning->cgt_tax,2) ?? 0}}</th>


                                    <th> -- </th>
                                    <th> -- </th>
                                    <th> -- </th>
                                    <th> -- </th>

                                </tr>
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
        var table = $('#example').DataTable({
            lengthChange: true,
            processing: true,
            serverSide: true,
            paging: true,
            dom: 'lBfrtip',
            buttons: ['copy', 'excel', 'pdf', 'print'],
            ajax: "{{url('adminfimihub/restoEarnings?resto_user_id=').base64_encode($resto_user_id)}}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'id'
                },
                {
                    data: 'order_id',
                    name: 'order_id'
                },
                {
                    data: 'resto_earning',
                    name: 'resto_earning'
                },
                {
                    data: 'total_amount',
                    name: 'total_amount'
                },
                {
                    data: 'delivery_fee',
                    name: 'delivery_fee'
                },
                {
                    data: 'service_tax',
                    name: 'service_tax'
                },
                {
                    data: 'total_tax',
                    name: 'total_tax'
                },
                {
                    data: 'service_commission',
                    name: 'service_commission'
                },
                {
                    data: 'total_commission',
                    name: 'total_commission'
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
