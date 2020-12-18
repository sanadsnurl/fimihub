@extends('customer.layout.cartBase')

@section('title', 'My Account')

@section('content')
<div class="col-md-7 padd_rht">
    <div class="card_lft card card_track">
        <div class="track_map">
            <img src="{{url('asset/customer/assets/images/trackmap.png')}}" class="w-100" alt="map">
        </div>
        <div class="track_addrs_strip d-flex">
            <div>
                <p>{{$resto_data->name ?? ''}}</p>
                <p>Order ID- {{$order_data->order_id ?? ''}}</p>
            </div>
            <p>Arriving Today at 5:20</p>
        </div>
        <div class="order_progress">
            <div class="row">
                <div class="col-md-9">
                    <ul class="order_track_step">
                        <li class="{{ in_array($order_data->order_status,array(1)) ? 'order_cancel' : 'order_status_hide'}} " ><span></span> Failed</li>
                        <li class="{{ in_array($order_data->order_status,array(2)) ? 'order_cancel' : 'order_status_hide'}} "><span></span> Cancelled</li>
                        <li class="{{ in_array($order_data->order_status,array(4)) ? 'order_cancel' : 'order_status_hide rest_den'}} "><span></span> Restaurant Denied</li>
                        <li class="{{ in_array($order_data->order_status,array(5,6,7,8,9,10,11,12)) ? 'active' : ''}}"><span></span> Order placed</li>
                        <li class="{{ in_array($order_data->order_status,array(6,7,8,9,10,11,12)) ? 'active' : ''}}"><span></span> Order packed</li>
                        <li class="{{ in_array($order_data->order_status,array(8)) ? 'order_cancel' : 'order_status_hide'}} "><span></span> Cancelled</li>
                        <li class="{{ in_array($order_data->order_status,array(7,8,9,10,11,12)) ? 'active' : ''}}"><span></span> Order has been picked by {{$order_event_data->rider_details->name ?? '---'}}</li>
                        <li class="{{ in_array($order_data->order_status,array(9,10)) ? 'active' : ''}} m-0"><span></span> Order delivered</li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <img src="{{url('asset/customer/assets/images/burger_image.png')}}" class="w-100" alt="burger">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
