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
                        <form role="form" method="POST" action="{{ url('adminfimihub/changePaidStatus')}}"
                            id="personal-info" enctype="multipart/form-data">
                            @csrf
                            <h4 class="form-header text-uppercase">
                                <i class="fa fa-cutlery"></i>
                                Change Order Paid Status
                            </h4>
                            @if(Session::has('message'))
                            <div class="error" style="text-align:center;">
                                <h4 class="error">{{ Session::get('message') }}</h4>
                            </div>

                            @endif
                            <div class="form-group row">
                                <label for="input-1" class="col-sm-3 col-form-label">Order ID</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="input-1" name="commission"
                                        value="{{$order_data->order_id ?? ''}}" readonly>
                                    @if($errors->has('commission'))
                                    <div class="error">{{ $errors->first('commission') }}</div>
                                    @endif
                                </div>

                            </div>
                            <div class="form-group row">
                                <label for="input-1" class="col-sm-3 col-form-label">Amount</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="input-1" name="total_amount"
                                        value="{{$order_data->total_amount ?? ''}}" readonly>
                                    @if($errors->has('total_amount'))
                                    <div class="error">{{ $errors->first('total_amount') }}</div>
                                    @endif
                                </div>

                            </div>
                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Refrence Number/Txn-ID</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="input-1" name="txn_id"
                                        value="{{old('txn_id')}}">
                                    <input type="hidden" class="form-control" id="input-1" name="id"
                                        value="{{$order_data->id ?? ''}}">
                                    <input type="hidden" class="form-control" id="input-1" name="user_id"
                                        value="{{$order_data->user_id ?? ''}}">
                                    @if($errors->has('txn_id'))
                                    <div class="error">{{ $errors->first('txn_id') }}</div>
                                    @endif
                                </div>

                            </div>

                            <div class="form-footer">
                                <input type="submit" class="btn btn-primary" value="Update"></input>
                                <a href="{{url()->previous()}}" >
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
@include('admin.include.footer')
<!-- Bootstrap core JavaScript-->
<script src="{{asset('asset/admin/assets/js/jquery.min.js')}}"></script>
<!-- waves effect js -->
<script src="{{asset('asset/admin/assets/js/waves.js')}}"></script>

<!--End content-wrapper-->
