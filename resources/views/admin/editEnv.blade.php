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
                        <form method="POST" action="{{ url('adminfimihub/editEnvProcess') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Abbreviation</label>
                                <div class="col-lg-9">
                                    <input class="form-control" type="text" name="type" value="{{$env_data->type ?? ''}}" disabled>
                                    <input class="form-control" type="hidden" name="id" value="{{$env_data->id ?? ''}}">
                                    @if($errors->has('type'))
                                    <div class="error" style="color:red;">{{ $errors->first('type') }}</div>
                                    @endif
                                </div>

                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Key</label>
                                <div class="col-lg-9">
                                    <input class="form-control" type="text" name="key" value="{{$env_data->key ?? ''}}" disabled>
                                    @if($errors->has('key'))
                                    <div class="error" style="color:red;">{{ $errors->first('key') }}</div>
                                    @endif
                                </div>

                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Value</label>
                                <div class="col-lg-9">
                                    <input class="form-control" type="type" name="value" value="{{$env_data->value ?? ''}}">
                                    @if($errors->has('value'))
                                    <div class="error" style="color:red;">{{ $errors->first('value') }}</div>
                                    @endif
                                </div>

                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label"></label>
                                <div class="col-lg-9">
                                    <button type="submit" class="btn btn-info">Update</button>
                                </div>

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
