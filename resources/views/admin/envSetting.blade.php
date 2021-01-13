@include('admin.include.sideNav')
@include('admin.include.header')
<!--Data Tables -->
<link href="{{url('asset/admin/assets/plugins/bootstrap-datatable/css/dataTables.bootstrap4.min.css')}}"
    rel="stylesheet" type="text/css">
<link href="{{url('asset/admin/assets/plugins/bootstrap-datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet"
    type="text/css">

<div class="clearfix"></div>

<div class="content-wrapper">
    <div class="container-fluid">

        <form method="POST" action="{{ url('adminfimihub/envSetting') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Abbreviation</label>
                <div class="col-lg-9">
                    <input class="form-control" type="text" name="type" >
                    @if($errors->has('type'))
                    <div class="error" style="color:red;">{{ $errors->first('type') }}</div>
                    @endif
                </div>

            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Key</label>
                <div class="col-lg-9">
                    <input class="form-control" type="text" name="key"  >
                    @if($errors->has('key'))
                    <div class="error" style="color:red;">{{ $errors->first('key') }}</div>
                    @endif
                </div>

            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label">Value</label>
                <div class="col-lg-9">
                    <input class="form-control" type="type" name="value">
                    @if($errors->has('value'))
                    <div class="error" style="color:red;">{{ $errors->first('value') }}</div>
                    @endif
                </div>

            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label form-control-label"></label>
                <div class="col-lg-9">
                    <button type="submit" class="btn btn-info">Submit</button>
                </div>

            </div>



        </form>


        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header"><i class="fa fa-table"></i>Env list</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="exampleenv" class="table table-bordered">
                                <thead>
                                    <tr>
                                        {{-- <th>Action</th> --}}
                                        <th>S.No.</th>
                                        <th>Abbreviation</th>
                                        <th>Key</th>
                                        <th>Value</th>

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
        // $('#defaultdatatable').DataTable();
        var table = $('#exampleenv').DataTable({
            lengthChange: true,
            processing: true,
            serverSide: true,
            paging: true,
            dom: 'lBfrtip',
            buttons: ['copy', 'excel', 'pdf', 'print'],
            ajax: "{{url('adminfimihub/envSetting')}}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'id'
                },
                {
                    data: 'type',
                    name: 'Abbreviation'
                },
                {
                    data: 'key',
                    name: 'Key'
                },
                {
                    data: 'value',
                    name: 'Value'
                }
            ]
        });
        table.buttons().container()
            .appendTo('#example_wrapper .col-md-6:eq(0)');
    });
    </script>
