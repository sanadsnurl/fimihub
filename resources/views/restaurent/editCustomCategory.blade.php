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
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form role="form" method="POST" action="{{ url('Restaurent/editCustomCatProcess')}}"
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
                                <label for="input-1" class="col-sm-2 col-form-label">Add-On Category List</label>
                                <div class="col-sm-10">
                                    <input type="hidden" value="{{$resto_cat_data[0]->id}}" name="id">
                                    <select name="custom_cat_id" id="" class="form-control" disabled
                                        onchange="java_script_:show(this.options[this.selectedIndex].value)">
                                        <option value="">== Select Food Category ==</option>
                                        @if(!empty($cat_data))
                                        @foreach($cat_data as $c_data)
                                        <option value="{{$c_data->id}}"
                                            {{$resto_cat_data[0]->custom_cat_id == $c_data->id ? 'selected':''}}>
                                            {{$c_data->name}}</option>
                                        @endforeach
                                        @endif
                                    </select>

                                    @if($errors->has('custom_cat_id'))
                                    <div class="error">{{ $errors->first('custom_cat_id') }}</div>
                                    @endif
                                </div>

                            </div>

                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Customization Type</label>
                                <div class="col-sm-10">

                                    <select name="customization_variant" id="input-1" class="form-control">
                                        <option value="">== Select Customization Type ==</option>
                                        <option value="1" {{$resto_cat_data[0]->customization_variant == 1 ? 'selected':''}}>Add-on</option>
                                        <option value="2" {{$resto_cat_data[0]->customization_variant == 2 ? 'selected':''}}>Menu Variant</option>
                                    </select>
                                    @if($errors->has('customization_variant'))
                                    <div class="error">{{ $errors->first('customization_variant') }}</div>
                                    @endif
                                </div>

                            </div>

                            <div class="form-footer">
                                <input type="submit" class="btn btn-primary" value="Add category"></input>

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
