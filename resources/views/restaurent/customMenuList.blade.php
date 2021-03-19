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
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form role="form" method="POST" action="{{ url('Restaurent/addCustomMenu')}}" id="personal-info"
                            enctype="multipart/form-data">
                            @csrf
                            <h4 class="form-header text-uppercase">
                                <i class="fa fa-cutlery"></i>
                                Add Customization
                                <a href="{{url('Restaurent/menuCustomCategory')}}" class="" target="_blank">
                                    <span class="btn btn-danger" style="float: right;">+ Add Customization
                                        Category</span>

                                </a>

                            </h4>
                            @if(Session::has('message'))
                            <div class="error" style="text-align:center;">
                                <h4 class="error">{{ Session::get('message') }}</h4>
                            </div>

                            @endif

                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Customization Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="input-1" name="name"
                                        value="{{old('name')}}">
                                    @if($errors->has('name'))
                                    <div class="error">{{ $errors->first('name') }}</div>
                                    @endif
                                </div>

                            </div>
                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Customization Category</label>
                                <div class="col-sm-10">
                                    <select name="resto_custom_cat_id" id="" class="form-control single-select">
                                        <option value="">-- Select Food Category --</option>
                                        @foreach($cat_data as $c_data)
                                        <option value="{{$c_data->id}}">{{$c_data->cat_name}}</option>
                                        @endforeach
                                    </select>

                                    @if($errors->has('resto_custom_cat_id'))
                                    <div class="error">{{ $errors->first('resto_custom_cat_id') }}</div>
                                    @endif
                                </div>

                            </div>

                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Price ($)</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="input-1" name="price"
                                        value="{{old('price')}}">
                                    @if($errors->has('price'))
                                    <div class="error">{{ $errors->first('price') }}</div>
                                    @endif
                                </div>

                            </div>

                            <!-- <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Discount (%)</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="input-1" name="discount">
                                    @if($errors->has('discount'))
                                    <div class="error">{{ $errors->first('discount') }}</div>
                                    @endif
                                </div>

                            </div> -->
                            <div class="form-footer">
                                <input type="submit" class="btn btn-primary" value="Save Dish"></input>
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
                    <div class="card-header"><i class="fa fa-table"></i> Add-On List
                        @if(Session::has('menu_message'))
                        <span class="error" style="text-align:center;font-size:16px;">
                            -> {{ Session::get('menu_message') }}</span>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>S.No.</th>
                                        <th>Customization Name</th>
                                        <th>Category</th>
                                        <th>Price</th>
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
            ajax: "{{url('Restaurent/menuCustomList')}}",
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
                    data: 'cat_name',
                    name: 'custom_menu_categories.name'
                },
                {
                    data: 'price',
                    name: 'price'
                },
                {
                    data: 'created_at',
                    name: 'menu_custom_list.created_at'
                },
            ]
        });
        table.buttons().container()
            .appendTo('#example_wrapper .col-md-6:eq(0)');
        $('.single-select').select2();

    });
</script>
<!--End content-wrapper-->
