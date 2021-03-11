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
                        <form method="POST" action="{{ url('adminfimihub/editCategory') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Category Name</label>
                                <div class="col-lg-9">
                                    <input class="form-control" type="text" name="name" value="{{$cat_data->name ?? ''}}" >
                                    <input class="form-control" type="hidden" name="id" value="{{$cat_data->id ?? ''}}">
                                    @if($errors->has('name'))
                                    <div class="error" style="color:red;">{{ $errors->first('name') }}</div>
                                    @endif
                                </div>

                            </div>


                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label"></label>
                                <div class="col-lg-9">
                                    <button type="submit" class="btn btn-info">Update</button>

                                    <a href="{{url()->previous()}}" >
                                        <span class="btn btn-danger">Back</span>
                                    </a>
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
