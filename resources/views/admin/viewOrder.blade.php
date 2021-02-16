@include('admin.include.sideNav')
@include('admin.include.header')
<div class="clearfix"></div>

<div class="content-wrapper">
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header text-uppercase">Order Id - {{$order_data->order_id ?? ''}}
                        <a href="{{url('adminfimihub/customerOrder')}}" style="float:right;">
                            <span class="btn btn-danger">Back</span>
                        </a>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">

                            <li class="list-group-item d-flex justify-content-between align-items-center active">
                                <b>Customer Name</b>
                                <span >{{$order_data->customer_name ?? ''}}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <b>Dish</b>
                                <span >{!! $order_data->ordered_menu ?? '' !!}</span>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <b>Payment Method</b>
                                <span >{{$order_data->payment_type ?? ''}}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <b>Order Status</b>
                                <span >{{$order_data->order_status ?? ''}}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <b>Flat No.</b>
                                <span >{{$add_datas->flat_no ?? '--'}} </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <b>Landmark</b>
                                <span >{{$add_datas->landmark ?? '--'}} </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <b>Delivery Address</b>
                                <span >{{$add_datas->address ?? '--'}} </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <b>Restaurant Name</b>
                                <span >{{$order_data->restaurentDetails->name ?? ''}}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <b>Rider Name</b>
                                <span >{{$event_data->rider_details->name ?? 'Not Alloted Yet'}} </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <b>Rider Mobile</b>
                                <span >{{$event_data->rider_details->mobile ?? '--'}} </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <b>Food Commission</b>
                                <span > {{$order_data->service_commission ?? ''}}%</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <b>Tax </b>
                                <span >{{$order_data->service_tax ?? ''}}%</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <b>Total Amount</b>
                                <span >{{$data->currency ?? ''}} {{$order_data->total_amount ?? ''}}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <b>Created At</b>
                                <span >{{$order_data->created_at ?? ''}}</span>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!--End Row-->

<!--End content-wrapper-->
@include('admin.include.footer')

