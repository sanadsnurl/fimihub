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
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form role="form" method="POST" action="{{ url('Restaurent/editMainCategoryProcess')}}" id="personal-info"
                            enctype="multipart/form-data">
                            @csrf
                            <h4 class="form-header text-uppercase">
                                <i class="fa fa-cutlery"></i>
                                Edit Category

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
                                <label for="input-1" class="col-sm-2 col-form-label">Category List</label>
                                <div class="col-sm-10">
                                    <input type="hidden" name="id" value="{{$dish_cat_details->id}}">
                                    <select name="menu_category_id" id="" class="form-control"
                                        onchange="java_script_:show(this.options[this.selectedIndex].value)">
                                        <option value="">-- Select Food Category --</option>
                                        @if(!empty($cat_data))
                                        @foreach($cat_data as $c_data)
                                        <option value="{{$c_data->id}}"
                                            {{$dish_cat_details->menu_category_id == $c_data->id ? 'selected':''}}>{{$c_data->name}}</option>
                                        @endforeach
                                        @endif
                                        <option value="-1">Other</option>
                                    </select>

                                    @if($errors->has('menu_category_id'))
                                    <div class="error">{{ $errors->first('menu_category_id') }}</div>
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
                                <input type="submit" class="btn btn-primary" value="Update"></input>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- End container-fluid-->

</div>




<!-- End container-fluid-->


<!--End content-wrapper-->
@include('restaurent.include.footer')
