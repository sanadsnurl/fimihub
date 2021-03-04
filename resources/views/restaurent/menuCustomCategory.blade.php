@include('restaurent.include.sideNav')
@include('restaurent.include.header')
<!--Data Tables -->
<link href="{{asset('asset/admin/assets/plugins/bootstrap-datatable/css/dataTables.bootstrap4.min.css')}}"
    rel="stylesheet" type="text/css">
<link href="{{asset('asset/admin/assets/plugins/bootstrap-datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet"
    type="text/css">
<link href="{{asset('asset/admin/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" />
<div class="clearfix"></div>

<div class="content-wrapper">
    <div class="container-fluid">

        <!-- End Breadcrumb-->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form role="form" method="POST" action="{{ url('Restaurent/addCustomCategory')}}"
                            id="personal-info" enctype="multipart/form-data">
                            @csrf
                            <h4 class="form-header text-uppercase">
                                <i class="fa fa-cutlery"></i>
                                Add Category

                            </h4>
                            @if(Session::has('message'))
                            <div class="error" style="text-align:center;">
                                <h4 class="error">{{ Session::get('message') }}</h4>
                            </div>

                            @endif
                            <script type="text/javascript">
                                function show(aval) {
                                    if (aval == "-1") { //if -1 then show it
                                        option_other.style.display = '';
                                    } else { //for everything else hide it
                                        option_other.style.display = 'none';
                                    }
                                }
                            </script>

                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label b">Add-On Category List</label>
                                <div class="col-sm-10">
                                    <select name="custom_cat_id" id="" class="form-control single-select"
                                        onchange="java_script_:show(this.options[this.selectedIndex].value)">
                                        <option value="">== Select Food Category ==</option>
                                        @if(!empty($cat_data))
                                        @foreach($cat_data as $c_data)
                                        <option value="{{$c_data->id}}">{{$c_data->name}}</option>
                                        @endforeach
                                        @endif
                                        <option value="-1">Other</option>
                                    </select>

                                    @if($errors->has('custom_cat_id'))
                                    <div class="error">{{ $errors->first('custom_cat_id') }}</div>
                                    @endif
                                </div>

                            </div>

                            <div class="form-group row" style="display:none" id="option_other">
                                <label for="input-1" class="col-sm-2 col-form-label">Category Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="input-1" name="cat_name">
                                    @if($errors->has('cat_name'))
                                    <div class="error">{{ $errors->first('cat_name') }}</div>
                                    @endif
                                </div>

                            </div>
                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Customization Type</label>
                                <div class="col-sm-10">

                                    <select name="customization_variant" id="input-1" class="form-control">
                                        <option value="">== Select Customization Type ==</option>
                                        <option value="1">Add-on</option>
                                        <option value="2">Menu Variant</option>
                                    </select>
                                    @if($errors->has('customization_variant'))
                                    <div class="error">{{ $errors->first('customization_variant') }}</div>
                                    @endif
                                </div>

                            </div>
                            <div class="form-group row">
                                <label for="input-4" class="col-sm-2 col-form-label">Is Required ?</label>
                                <div class="demo-checkbox ml-4">
                                    <input type="radio" id="user-checkbox" class="filled-in chk-col-primary" value="1"
                                        name="is_required">
                                    <label for="user-checkbox">Yes</label>
                                </div>
                                <div class="demo-checkbox">
                                    <input type="radio" id="user-checkbox1" class="filled-in chk-col-primary" value="2"
                                        name="is_required">
                                    <label for="user-checkbox1">No</label>
                                </div>
                                @if($errors->has('is_required'))
                                <div class="error">{{ $errors->first('is_required') }}</div>
                                @endif
                            </div>
                            <div class="form-group row">
                                <label for="input-4" class="col-sm-2 col-form-label">Multiple Select</label>
                                <div class="demo-checkbox ml-4">
                                    <input type="radio" id="user-checkboxxx" class="filled-in chk-col-primary" value="1"
                                        name="multiple_select">
                                    <label for="user-checkboxxx">Yes</label>
                                </div>
                                <div class="demo-checkbox">
                                    <input type="radio" id="user-checkbox1x" class="filled-in chk-col-primary" value="2"
                                        name="multiple_select">
                                    <label for="user-checkbox1x">No</label>
                                </div>
                                @if($errors->has('multiple_select'))
                                <div class="error">{{ $errors->first('multiple_select') }}</div>
                                @endif
                            </div>
                            <div class="form-footer">
                                <input type="submit" class="btn btn-primary" value="Add category"></input>
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
                    <div class="card-header"><i class="fa fa-table"></i>Add-On Category List</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <!-- <th>S.no</th> -->
                                        <th>Action</th>
                                        <th>S.No.</th>
                                        <th>Category Name</th>
                                        <th>Customization Variant</th>
                                        <th>Is Required</th>
                                        <th>Multiple Select</th>
                                        <!-- <th>About</th> -->
                                        <!-- <th>Discount (%)</th> -->
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
@include('restaurent.include.footer')
<!-- Bootstrap core JavaScript-->
<script src="{{asset('asset/admin/assets/js/jquery.min.js')}}"></script>
<!-- waves effect js -->
<script src="{{asset('asset/admin/assets/js/waves.js')}}"></script>
<script src="{{asset('asset/admin/assets/plugins/select2/js/select2.min.js')}}"></script>
<script src="{{asset('asset/admin/assets/plugins/jquery-multi-select/jquery.multi-select.js')}}"></script>
<script src="{{asset('asset/admin/assets/plugins/jquery-multi-select/jquery.quicksearch.js')}}"></script>

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
            ajax: "{{url('Restaurent/menuCustomCategory')}}",
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
                    data: 'cat_name',
                    name: 'cat_name'
                },
                {
                    data: 'customization_variant',
                    name: 'customization_variant'
                },
                {
                    data: 'is_required',
                    name: 'is_required'
                },
                {
                    data: 'multiple_select',
                    name: 'multiple_select'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
            ]
        });
        table.buttons().container()
            .appendTo('#example_wrapper .col-md-6:eq(0)');
        $('.single-select').select2();
    });
</script>
<!--End content-wrapper-->
