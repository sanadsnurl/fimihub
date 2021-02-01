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
                        <form role="form" method="GET" action="{{ url('Restaurent/rejectOrder')}}" id="personal-info"
                            enctype="multipart/form-data">
                            @csrf
                            <h4 class="form-header text-uppercase">
                                <i class="fa fa-cutlery"></i>
                                Reject Order

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
                            <div class="form-group row" >
                                <label for="input-1" class="col-sm-2 col-form-label">Order Id</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="input-1" name="id" value="{{$order_data->order_id}}" disabled>
                                    @if($errors->has('id'))
                                    <div class="error">{{ $errors->first('id') }}</div>
                                    @endif
                                </div>

                            </div>
                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Reasons</label>
                                <div class="col-sm-10">
                                    <input type="hidden" name="odr_id" value="{{ base64_encode($order_data->id)}}">
                                    <select name="reason_id" id="" class="form-control"
                                        onchange="java_script_:show(this.options[this.selectedIndex].value)" required>
                                        <option value="">-- Select Reason --</option>
                                        @if(!empty($reason_list))
                                        @foreach($reason_list as $r_data)
                                        <option value="{{$r_data->id}}"
                                        >{{$r_data->reason}}</option>
                                        @endforeach
                                        @endif
                                        {{-- <option value="-1">Other</option> --}}
                                    </select>

                                    @if($errors->has('reason_id'))
                                    <div class="error">{{ $errors->first('reason_id') }}</div>
                                    @endif
                                </div>

                            </div>
                            <div class="form-footer">
                                <input type="submit" class="btn btn-primary" value="Reject"></input>

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
