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
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form role="form" method="POST" action="{{ url('adminfimihub/addSlider')}}" id="personal-info"
                            enctype="multipart/form-data">
                            @csrf
                            <h4 class="form-header text-uppercase">
                                <i class="fa fa-cutlery"></i>
                                Add Slider
                            </h4>
                            @if(Session::has('message'))
                            <div class="error" style="text-align:center;">
                                <h4 class="error">{{ Session::get('message') }}</h4>
                            </div>

                            @endif

                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Heading</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="input-1" name="text1"
                                        value="{{old('text1')}}">
                                    @if($errors->has('text1'))
                                    <div class="error">{{ $errors->first('text1') }}</div>
                                    @endif
                                </div>

                            </div>
                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Content</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="input-1" name="text2"
                                        value="{{old('text2')}}">
                                    @if($errors->has('text2'))
                                    <div class="error">{{ $errors->first('text2') }}</div>
                                    @endif
                                </div>

                            </div>
                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Link</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="input-1" name="link"
                                        value="{{old('link')}}">
                                    @if($errors->has('link'))
                                    <div class="error">{{ $errors->first('link') }}</div>
                                    @endif
                                </div>

                            </div>
                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Image</label>
                                <div class="col-sm-10">
                                    <input type="file" class="form-control" id="input-1" name="media"
                                        >
                                    @if($errors->has('media'))
                                    <div class="error">{{ $errors->first('media') }}</div>
                                    @endif
                                </div>

                            </div>
                            <!-- <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Discount (%)</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="input-1" name="discount"
                                        value="{{old('discount')}}  ">
                                    @if($errors->has('discount'))
                                    <div class="error">{{ $errors->first('discount') }}</div>
                                    @endif
                                </div>

                            </div> -->

                            <div class="form-footer">
                                <input type="submit" class="btn btn-primary" value="Save Slider"></input>

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
                    <div class="card-header"><i class="fa fa-table"></i> Slider List</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>S.No.</th>
                                        <th>Slider Image</th>
                                        <th>Heading</th>
                                        <th>Content</th>
                                        <th>Link</th>
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
            processing: true,
            serverSide: true,
            paging: true,
            dom: 'lBfrtip',
            buttons: ['copy', 'excel', 'pdf', 'print'],
            ajax: "{{url('adminfimihub/slider')}}",
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
                    data: 'media',
                    name: 'media'
                },
                {
                    data: 'text1',
                    name: 'text1'
                },
                {
                    data: 'text2',
                    name: 'text2'
                },
                {
                    data: 'link',
                    name: 'link'
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
