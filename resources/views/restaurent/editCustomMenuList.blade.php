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
                        <form role="form" method="POST" action="{{ url('Restaurent/editCustomMenuProcess')}}" id="personal-info"
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
                                        value="{{$custom_menu_data->name ?? '' }}">
                                        <input type="hidden" class="form-control" id="input-1" name="id"
                                        value="{{$custom_menu_data->id ?? '' }}">
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
                                        <option value="{{$c_data->id}}" {{$custom_menu_data->resto_custom_cat_id == $c_data->id ? 'selected':''}}>{{$c_data->cat_name}}</option>
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
                                    value="{{$custom_menu_data->price ?? ''}}">
                                    @if($errors->has('price'))
                                    <div class="error">{{ $errors->first('price') }}</div>
                                    @endif
                                </div>

                            </div>


                            <div class="form-footer">
                                <input type="submit" class="btn btn-primary" value="Update"></input>
                                <a href="{{url('Restaurent/menuCustomList')}}" >
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
