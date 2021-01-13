@include('admin.include.sideNav')
@include('admin.include.header')

<div class="clearfix"></div>

<div class="content-wrapper">
    <div class="container-fluid">

        <!-- End Breadcrumb-->
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form role="form" method="POST" action="{{ url('adminfimihub/editServiceProcess')}}"
                            id="personal-info" enctype="multipart/form-data">
                            @csrf
                            <h4 class="form-header text-uppercase">
                                <i class="fa fa-cutlery"></i>
                                Edit Service
                            </h4>
                            @if(Session::has('message'))
                            <div class="error" style="text-align:center;">
                                <h4 class="error">{{ Session::get('message') }}</h4>
                            </div>

                            @endif
                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="input-1" name="name" value="{{$service_data->name ?? ''}}">
                                    <input type="hidden" class="form-control" id="input-1" name="id" value="{{$service_data->id ?? ''}}">
                                    @if($errors->has('name'))
                                    <div class="error">{{ $errors->first('name') }}</div>
                                    @endif
                                </div>

                            </div>


                            <div class="form-group row">
                                <label for="input-1" class="col-sm-3 col-form-label">Commission (in %)</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="input-1" name="commission"
                                        value="{{$service_data->commission ?? ''}}">
                                    @if($errors->has('commission'))
                                    <div class="error">{{ $errors->first('commission') }}</div>
                                    @endif
                                </div>

                            </div>
                            <div class="form-group row">
                                <label for="input-1" class="col-sm-3 col-form-label">Tax (in %)</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="input-1" name="tax"
                                        value="{{$service_data->tax ?? ''}}">
                                    @if($errors->has('tax'))
                                    <div class="error">{{ $errors->first('tax') }}</div>
                                    @endif
                                </div>

                            </div>
                            <div class="form-group row">
                                <label for="input-1" class="col-sm-3 col-form-label">Flat rate</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="input-1" name="flat_delivery_charge"
                                        value="{{$service_data->flat_delivery_charge ?? ''}}">
                                    @if($errors->has('flat_delivery_charge'))
                                    <div class="error">{{ $errors->first('flat_delivery_charge') }}</div>
                                    @endif
                                </div>

                            </div>
                            <div class="form-group row">
                                <label for="input-1" class="col-sm-3 col-form-label">Flat Km</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="input-1" name="on_km"
                                        value="{{$service_data->on_km ?? ''}}">
                                    @if($errors->has('on_km'))
                                    <div class="error">{{ $errors->first('on_km') }}</div>
                                    @endif
                                </div>

                            </div>
                            <div class="form-group row">
                                <label for="input-1" class="col-sm-3 col-form-label">After Flat Km (Per/Km ,$)</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="input-1" name="after_flat_delivery_charge"
                                        value="{{$service_data->after_flat_delivery_charge ?? ''}}">
                                    @if($errors->has('after_flat_delivery_charge'))
                                    <div class="error">{{ $errors->first('after_flat_delivery_charge') }}</div>
                                    @endif
                                </div>

                            </div>
                            <div class="form-group row">
                                <label for="input-1" class="col-sm-3 col-form-label">Rider Commmission (in %)</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="input-1" name="rider_commission"
                                        value="{{$service_data->rider_commission ?? ''}}">
                                    @if($errors->has('rider_commission'))
                                    <div class="error">{{ $errors->first('rider_commission') }}</div>
                                    @endif
                                </div>

                            </div>

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
@include('admin.include.footer')
<!-- Bootstrap core JavaScript-->
<script src="{{asset('asset/admin/assets/js/jquery.min.js')}}"></script>
<!-- waves effect js -->
<script src="{{asset('asset/admin/assets/js/waves.js')}}"></script>

<!--End content-wrapper-->
