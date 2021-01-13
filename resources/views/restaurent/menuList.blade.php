@include('restaurent.include.sideNav')
@include('restaurent.include.header')
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
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-body">
                        <form role="form" method="POST" action="{{ url('Restaurent/addMenu')}}" id="personal-info"
                            enctype="multipart/form-data">
                            @csrf
                            <h4 class="form-header text-uppercase">
                                <i class="fa fa-cutlery"></i>
                                Add Dish
                                <a href="{{url('Restaurent/menuCategory')}}" class="" target="_blank">
                                    <span class="btn btn-danger" style="float: right;">+ Add Food Category</span>

                                </a>

                            </h4>
                            @if(Session::has('message'))
                            <div class="error" style="text-align:center;">
                                <h4 class="error">{{ Session::get('message') }}</h4>
                            </div>

                            @endif
                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Dish Picture</label>
                                <div class="col-sm-10">
                                    <input type="file" class="form-control" id="input-1" name="picture">
                                    @if($errors->has('picture'))
                                    <div class="error">{{ $errors->first('picture') }}</div>
                                    @endif
                                </div>

                            </div>

                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Dish Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="input-1" name="name">
                                    @if($errors->has('name'))
                                    <div class="error">{{ $errors->first('name') }}</div>
                                    @endif
                                </div>

                            </div>
                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Food Category</label>
                                <div class="col-sm-10">
                                    <select name="menu_category_id" id="" class="form-control">
                                        <option value="">-- Select Food Category --</option>
                                        @foreach($cat_data as $c_data)
                                        <option value="{{$c_data->id}}">{{$c_data->cat_name}}</option>
                                        @endforeach
                                    </select>

                                    @if($errors->has('menu_category_id'))
                                    <div class="error">{{ $errors->first('menu_category_id') }}</div>
                                    @endif
                                </div>

                            </div>
                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Food Variant</label>
                                <div class="col-sm-10">
                                    <select name="product_variant_id" id="" class="form-control">
                                        <option value="">-- Select Food Variant --</option>
                                        @foreach($resto_cate_variant as $cs_data)
                                        <option value="{{$cs_data->id}}">{{$cs_data->cat_name}}</option>
                                        @endforeach
                                    </select>

                                    @if($errors->has('product_variant_id'))
                                    <div class="error">{{ $errors->first('product_variant_id') }}</div>
                                    @endif
                                </div>

                            </div>
                            <div class="form-group row">
                                <label for="input-4" class="col-sm-2 col-form-label">Add On</label>
                                @foreach($resto_cate_add_on as $css_data)
                                <div class="demo-checkbox ml-4">
                                    <input type="checkbox" id="user-checkboxs{{$css_data->id}}"
                                        class="filled-in chk-col-primary" value="{{$css_data->id}}"
                                        name="product_add_on_id[]">
                                    <label for="user-checkboxs{{$css_data->id}}">{{$css_data->cat_name}}</label>
                                </div>
                                @endforeach

                                @if($errors->has('product_add_on_id'))
                                <div class="error">{{ $errors->first('product_add_on_id') }}</div>
                                @endif
                            </div>
                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">About Dish</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="input-1" name="about">
                                    @if($errors->has('about'))
                                    <div class="error">{{ $errors->first('about') }}</div>
                                    @endif
                                </div>

                            </div>
                            <div class="form-group row">
                                <label for="input-4" class="col-sm-2 col-form-label">Dish Type</label>
                                <div class="demo-checkbox ml-4">
                                    <input type="radio" id="user-checkbox" class="filled-in chk-col-primary" value="2"
                                        name="dish_type">
                                    <label for="user-checkbox">Veg</label>
                                </div>
                                <div class="demo-checkbox">
                                    <input type="radio" id="user-checkbox1" class="filled-in chk-col-primary" value="1"
                                        name="dish_type">
                                    <label for="user-checkbox1">Non-Veg</label>
                                </div>
                                @if($errors->has('dish_type'))
                                <div class="error">{{ $errors->first('dish_type') }}</div>
                                @endif
                            </div>

                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Price ($)</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="price">
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
                    <div class="card-header"><i class="fa fa-table"></i> Menu List
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
                                        <!-- <th>S.no</th> -->
                                        <th>Action</th>
                                        <th>S.No.</th>
                                        <th>Dish Name</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>About</th>
                                        <th>Dish Type</th>
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
            ajax: "{{url('Restaurent/menuList')}}",
            columns: [ {
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
                    name: 'cat_name'
                },
                {
                    data: 'price',
                    name: 'price'
                },
                {
                    data: 'about',
                    name: 'about'
                },
                {
                    data: 'dish_type',
                    name: 'dish_type'
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
