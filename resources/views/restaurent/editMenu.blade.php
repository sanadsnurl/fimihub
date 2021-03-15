@include('restaurent.include.sideNav')
@include('restaurent.include.header')
<link href="{{asset('asset/admin/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" />

<div class="clearfix"></div>

<div class="content-wrapper">
    <div class="container-fluid">

        <!-- End Breadcrumb-->
        <div class="row">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-body">
                        <form role="form" method="POST" action="{{ url('Restaurent/editDishProcess')}}"
                            id="personal-info" enctype="multipart/form-data">
                            @csrf
                            <h4 class="form-header text-uppercase">
                                <i class="fa fa-cutlery"></i>
                                Edit Dish
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
                                    <input type="text" class="form-control" id="input-1" name="name"
                                        value="{{$menu_data->name ?? ''}}">
                                    <input type="hidden" class="form-control" id="input-1" name="id"
                                        value="{{$menu_data->id ?? ''}}">
                                    @if($errors->has('name'))
                                    <div class="error">{{ $errors->first('name') }}</div>
                                    @endif
                                </div>

                            </div>
                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Food Category</label>
                                <div class="col-sm-10">
                                    <select name="menu_category_id" id="" class="form-control single-select">
                                        <option value="">-- Select Food Category --</option>
                                        @foreach($cat_data as $c_data)
                                        <option value="{{$c_data->id}}"
                                            {{ ( $menu_data->menu_category_id == $c_data->cats_id ) ? 'selected' : '' }}>
                                            {{$c_data->cat_name}}</option>
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
                                    <select name="product_variant_id" id="" class="form-control single-select">
                                        <option value="">-- Select Food Variant --</option>
                                        @foreach($resto_cate_variant as $cs_data)
                                        <option value="{{$cs_data->id}}"
                                            {{ ( $menu_data->product_variant_id == $cs_data->id ) ? 'selected' : '' }}>
                                            {{$cs_data->cat_name}}</option>
                                        @endforeach
                                    </select>

                                    @if($errors->has('product_variant_id'))
                                    <div class="error">{{ $errors->first('product_variant_id') }}</div>
                                    @endif
                                </div>

                            </div>
                            <div class="form-group row">
                                <label for="input-4" class="col-sm-2 col-form-label">Add On</label>
                                @if(count($resto_cate_add_on))
                                @foreach($resto_cate_add_on as $css_data)
                                <div class="demo-checkbox ml-4">
                                    <input type="checkbox" id="user-checkboxs{{$css_data->id}}"
                                        class="filled-in chk-col-primary" value="{{$css_data->id}}"
                                        name="product_add_on_id[]"
                                        {{in_array($css_data->id,($menu_data->product_add_on_id ?? [])) ? 'checked':''}}>
                                    <label for="user-checkboxs{{$css_data->id}}">{{$css_data->cat_name}}</label>
                                </div>
                                @endforeach
                                @endif
                                @if($errors->has('product_add_on_id'))
                                <div class="error">{{ $errors->first('product_add_on_id') }}</div>
                                @endif
                            </div>
                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">About Dish</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="input-1" name="about"
                                        value="{{$menu_data->about ?? ''}}">
                                    @if($errors->has('about'))
                                    <div class="error">{{ $errors->first('about') }}</div>
                                    @endif
                                </div>

                            </div>
                            <div class="form-group row">
                                <label for="input-4" class="col-sm-2 col-form-label">Dish Type</label>
                                <div class="demo-checkbox ml-4">
                                    <input type="radio" id="user-checkbox" class="filled-in chk-col-primary" value="2"
                                        name="dish_type" @if(isset($menu_data->dish_type))
                                    {{$menu_data->dish_type == 2 ? 'checked' : ''}}
                                    @endif>
                                    <label for="user-checkbox">Veg</label>
                                </div>
                                <div class="demo-checkbox">
                                    <input type="radio" id="user-checkbox1" class="filled-in chk-col-primary" value="1"
                                        name="dish_type" @if(isset($menu_data->dish_type))
                                    {{$menu_data->dish_type == 1 ? 'checked' : ''}}
                                    @endif>
                                    <label for="user-checkbox1">Non-Veg</label>
                                </div>
                                <div class="demo-checkbox">
                                    <input type="radio" id="user-checkbox1" class="filled-in chk-col-primary" value="3"
                                        name="dish_type" @if(isset($menu_data->dish_type))
                                    {{$menu_data->dish_type == 3 ? 'checked' : ''}}
                                    @endif>
                                    <label for="user-checkbox1">Beverage</label>
                                </div>
                                @if($errors->has('dish_type'))
                                <div class="error">{{ $errors->first('dish_type') }}</div>
                                @endif
                            </div>
                            <div class="form-group row">
                                <label for="input-2" class="col-sm-2 col-form-label">Dish Open Day</label>
                                <div class=" row col-sm-9 ml-4">
                                    <div class="demo-checkbox">
                                        <input type="checkbox" id="user-checkboxday" class="filled-in chk-col-primary" value="Monday"
                                            name="open_day[]" {{in_array("Monday",(json_decode($menu_data->open_day) ?? [])) ? 'checked':''}}>
                                        <label for="user-checkboxday">Monday</label>
                                    </div>
                                    <div class="demo-checkbox">
                                        <input type="checkbox" id="user-checkboxday1" class="filled-in chk-col-primary" value="Tuesday"
                                            name="open_day[]" {{in_array("Tuesday",(json_decode($menu_data->open_day) ?? [])) ? 'checked':''}}>
                                        <label for="user-checkboxday1">Tuesday</label>
                                    </div>
                                    <div class="demo-checkbox">
                                        <input type="checkbox" id="user-checkboxday2" class="filled-in chk-col-primary" value="Wednesday"
                                            name="open_day[]" {{in_array("Wednesday",(json_decode($menu_data->open_day) ?? [])) ? 'checked':''}}>
                                        <label for="user-checkboxday2">Wednesday</label>
                                    </div>
                                    <div class="demo-checkbox">
                                        <input type="checkbox" id="user-checkboxday3" class="filled-in chk-col-primary" value="Thursday"
                                            name="open_day[]" {{in_array("Thursday",(json_decode($menu_data->open_day) ?? [])) ? 'checked':''}}>
                                        <label for="user-checkboxday3">Thursday</label>
                                    </div>
                                    <div class="demo-checkbox">
                                        <input type="checkbox" id="user-checkboxday4" class="filled-in chk-col-primary" value="Friday"
                                            name="open_day[]" {{in_array("Friday",(json_decode($menu_data->open_day) ?? [])) ? 'checked':''}}>
                                        <label for="user-checkboxday4">Friday</label>
                                    </div>
                                    <div class="demo-checkbox">
                                        <input type="checkbox" id="user-checkboxday5" class="filled-in chk-col-primary" value="Saturday"
                                            name="open_day[]" {{in_array("Saturday",(json_decode($menu_data->open_day) ?? [])) ? 'checked':''}}>
                                        <label for="user-checkboxday5">Saturday</label>
                                    </div>
                                    <div class="demo-checkbox">
                                        <input type="checkbox" id="user-checkboxday6" class="filled-in chk-col-primary" value="Sunday"
                                            name="open_day[]" {{in_array("Sunday",(json_decode($menu_data->open_day) ?? [])) ? 'checked':''}}>
                                        <label for="user-checkboxday6">Sunday</label>
                                    </div>
                                    @if($errors->has('open_day'))
                                    <div class="error">{{ $errors->first('open_day') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Open Time</label>
                                <div class="col-sm-10">
                                    <input type="time" class="form-control" name="open_time" value="{{$menu_data->open_time ?? ''}}">
                                    @if($errors->has('open_time'))
                                    <div class="error">{{ $errors->first('open_time') }}</div>
                                    @endif
                                </div>

                            </div>
                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Close Time</label>
                                <div class="col-sm-10">
                                    <input type="time" class="form-control" name="close_time" value="{{$menu_data->close_time ?? ''}}">
                                    @if($errors->has('close_time'))
                                    <div class="error">{{ $errors->first('close_time') }}</div>
                                    @endif
                                </div>

                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Price ($)</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="price"
                                        value="{{$menu_data->price ?? ''}}">
                                    @if($errors->has('price'))
                                    <div class="error">{{ $errors->first('price') }}</div>
                                    @endif
                                </div>

                            </div>

                            <div class="form-footer">
                                <input type="submit" class="btn btn-primary" value="Save Dish"></input>

                                <a href="{{url('Restaurent/menuList')}}" >
                                    <span class="btn btn-danger">Back</span>
                                </a>
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
<!-- Bootstrap core JavaScript-->
<script src="{{asset('asset/admin/assets/js/jquery.min.js')}}"></script>
<!-- waves effect js -->
<script src="{{asset('asset/admin/assets/js/waves.js')}}"></script>

<script src="{{asset('asset/admin/assets/plugins/select2/js/select2.min.js')}}"></script>
<script src="{{asset('asset/admin/assets/plugins/jquery-multi-select/jquery.multi-select.js')}}"></script>
<script src="{{asset('asset/admin/assets/plugins/jquery-multi-select/jquery.quicksearch.js')}}"></script>
<script>
    $(document).ready(function() {
        $('.single-select').select2();
    });
</script>

<!--End content-wrapper-->
