@include('restaurent.include.sideNav')
@include('restaurent.include.header')
<style>
    .dish_details_box{
        box-shadow: 0 2px 5px rgb(0 0 0 / 20%);
        padding: 13px 17px;
        border-radius: 4px;
    }
</style>
<div class="clearfix"></div>

<div class="content-wrapper">
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header text-uppercase">Order Id - {{$order_data->order_id ?? ''}}
                        <a href="{{url('Restaurent/customerOrder')}}" style="float:right;">
                            <span class="btn btn-danger">Back</span>
                        </a>
                        <a href="{{url(Request::fullUrl())}}" style="float:right;">
                            <span class="btn btn-info">Refresh</span>
                        </a>

                    </div>
                    <div class="card-body">
                        <ul class="list-group">

                            <li class="list-group-item d-flex justify-content-between align-items-center active">
                                <b>Customer Name</b>
                                <span>{{$order_data->customer_name ?? ''}}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="row w-100">
                                        <div class="col-md-3">
                                            <b>Dish</b>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="row">
                                                @if(!empty($order_data->ordered_menu_added))
                                                @foreach ($order_data->ordered_menu_added as $ordered_menu)
                                                <div class="col-md-3">
                                                    <div class="dish_details_box h-100">

                                                        <p><strong>{{ucFirst($ordered_menu->name) . " x " . $ordered_menu->quantity}}</strong></p>
                                                        @if(!empty($ordered_menu->variant_data))

                                                            @foreach ($ordered_menu->variant_data as $v_data)
                                                                @if ($ordered_menu->cart_variant_id == $v_data->id)
                                                                <p><strong>{{ucFirst($v_data->cat_name)}}</strong>: {{$v_data->name}}</p>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    @if(!empty($ordered_menu->add_on))
                                                        @foreach ($ordered_menu->add_on as $add_datasa)
                                                            @foreach ($add_datasa as $add_data)
                                                            @if (in_array($add_data->id, ($ordered_menu->product_adds_id) ?? [], FALSE))
                                                                <p><strong>{{ucFirst($add_data->cat_name)}}</strong>: {{$add_data->name}}</p>
                                                            @endif
                                                            @endforeach
                                                        @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                                @endforeach
                                                @endif
                                                {{-- <div class="col-md-3">
                                                    <div class="dish_details_box h-100">
                                                        <span>{!! $order_data->ordered_menu ?? '' !!}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="dish_details_box h-100">
                                                        <span>{!! $order_data->ordered_menu ?? '' !!}</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="dish_details_box h-100">
                                                        <span>{!! $order_data->ordered_menu ?? '' !!}</span>
                                                    </div>
                                                </div> --}}

                                            </div>
                                        </div>
                                    </div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{-- <b>Total Amount</b>
                                <span>{{$data->currency ?? ''}} {{$order_data->total_amount ?? ''}}</span> --}}
                                <div class="col-md-3">
                                    <b>Total Amount</b>
                                </div>
                                <div class="col-md-9">
                                    <span>{{$data->currency ?? ''}} {{$order_data->total_amount ?? ''}}</span>
                                </div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{-- <b>Payment Method</b>
                                <span>{{$order_data->payment_type ?? ''}}</span> --}}
                                <div class="col-md-3">
                                    <b>Payment Method</b>
                                </div>
                                <div class="col-md-9">
                                    <span>{{$order_data->payment_type ?? ''}}</span>
                                </div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{-- <b>Order Status</b>
                                <span>{{$order_data->order_status ?? ''}}</span> --}}
                                <div class="col-md-3">
                                    <b>Order Status</b>
                                </div>
                                <div class="col-md-9">
                                    <span>{{$order_data->order_status ?? ''}}</span>
                                </div>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{-- <b>Customer Name</b>
                                <span >{{$order_data->customer_name ?? ''}}</span> --}}
                                <div class="col-md-3">
                                    <b>Customer Name.</b>
                                </div>
                                <div class="col-md-9">
                                    <span>{{ $order_data->customer_name ?? '--'}}</span>
                                </div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{-- <b>Customer Mobile</b>
                                <span >{{$order_data->mobile ?? ''}}</span> --}}
                                <div class="col-md-3">
                                    <b>Customer Mobile.</b>
                                </div>
                                <div class="col-md-9">
                                    <span>{{ $order_data->mobile ?? '--'}}</span>
                                </div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{-- <b>Flat No.</b>
                                <span>{{$add_datas->flat_no ?? '--'}} </span> --}}
                                <div class="col-md-3">
                                    <b>Flat No.</b>
                                </div>
                                <div class="col-md-9">
                                    <span>{{ $add_datas->flat_no ?? '--'}}</span>
                                </div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{-- <b>Landmark</b>
                                <span>{{$add_datas->landmark ?? '--'}} </span> --}}
                                <div class="col-md-3">
                                    <b>Landmark.</b>
                                </div>
                                <div class="col-md-9">
                                    <span>{{$add_datas->landmark ?? ''}}</span>
                                </div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{-- <b>Delivery Address</b>
                                <span>{{$add_datas->address ?? '--'}} </span> --}}

                                <div class="col-md-3">
                                    <b>Delivery Address</b>
                                </div>
                                <div class="col-md-9">
                                    <span>{{$add_datas->address ?? ''}}</span>
                                </div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{-- <b>Rider Name</b>
                                <span>{{$event_data->rider_details->name ?? 'Not Alloted Yet'}} </span> --}}
                                <div class="col-md-3">
                                    <b>Rider Name</b>
                                </div>
                                <div class="col-md-9">
                                    <span>{{$event_data->rider_details->name ?? 'Not Alloted Yet'}}</span>
                                </div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{-- <b>Rider Mobile</b>
                                <span>{{$event_data->rider_details->mobile ?? '--'}} </span> --}}
                                <div class="col-md-3">
                                    <b>Rider Mobile</b>
                                </div>
                                <div class="col-md-9">
                                    <span>{{$event_data->rider_details->mobile ?? '--'}}</span>
                                </div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{-- <b>Created At</b>
                                <span>{{$order_data->created_at ?? ''}}</span> --}}
                                <div class="col-md-3">
                                    <b>Order Date</b>
                                </div>
                                <div class="col-md-9">
                                    <span>{{date('d F Y', strtotime($order_data->created_at)) ?? '--'}}</span>
                                </div>
                            </li>

                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{-- <b>Created At</b>
                                <span>{{$order_data->created_at ?? ''}}</span> --}}
                                <div class="col-md-3">
                                    <b>Order time</b>
                                </div>
                                <div class="col-md-9">
                                    <span>{{ date('h:i A', strtotime($order_data->created_at)) ?? '--'}}</span>
                                </div>
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
@include('restaurent.include.footer')
