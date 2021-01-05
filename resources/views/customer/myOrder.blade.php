@extends('customer.layout.myAccountBase')

@section('title', 'My Account')

@section('content')
<div class="content-col">
    <h5>My Orders</h5>
    <ul class="tabs">
        <li class="active" id="past-order">Past Order</li>
        <li id="ongoing">On Going</li>
    </ul>
    <div class="tab-content" data-tab-id="past-order">
        @if($order_data != NULL)
        @foreach($order_data as $order)
        <div class="card-wrap">

            <div class="col-left">
                <div class="text-with-img">
                    <div class="img-wrap">
                        <img src="{{$order->resto_picture ?? url('asset/customer/assets/images/resto_thumbnail.png')}}"
                            alt="order1">
                    </div>
                    <div class="text-wrap">
                        <h6>{{$order->resto_name ?? '--'}}</h6>
                        <span class="cat">{{$order->resto_address ?? '--'}}</span>
                        <span class="order-id">ORDER ID - {{$order->order_id ?? '--'}} |
                            {{date('D, M d, Y, h:i A', strtotime($order->created_at))}} </span>
                        <span class="qty">
                            @if($order->ordered_menu != NULL)
                            @foreach($order->ordered_menu as $ordered_menu)
                            @if($loop->iteration == 1)
                            {{$ordered_menu->name}} x {{$ordered_menu->quantity}}
                            @if(isset($ordered_menu->add_on_data) && $ordered_menu->add_on_data != NULL)
                            (
                            @foreach($ordered_menu->add_on_data as $a_data)
                            @if($a_data->quantity !=NULL)
                            @if($loop->iteration == 1)
                            {{$a_data->name}} x {{$a_data->quantity}}
                            @else
                            /{{$a_data->name}} x {{$a_data->quantity}}
                            @endif
                            @endif
                            @endforeach
                            )
                            @endif
                            @else
                            /{{$ordered_menu->name}} x {{$ordered_menu->quantity}}
                            @if(isset($ordered_menu->add_on_data) && $ordered_menu->add_on_data != NULL)
                            (
                            @foreach($ordered_menu->add_on_data as $a_data)
                            @if($a_data->quantity !=NULL)
                            @if($loop->iteration == 1)
                            {{$a_data->name}} x {{$a_data->quantity}}
                            @else
                            /{{$a_data->name}} x {{$a_data->quantity}}
                            @endif
                            @endif
                            @endforeach
                            )
                            @endif
                            @endif
                            @endforeach
                            @endif

                        </span>
                    </div>
                </div>
                <div class="btn-grp">
                    <a href="#" class="btn btn-purple">Reorder</a>
                    <a href="#" class="btn btn-purple">Help</a>
                    <a href="{{url('/trackOrder')}}{{'?odr_id='}}{{base64_encode($order->id)}}"
                        class="btn btn-purple">Details</a>
                </div>
            </div>
            <div class="col-right">
                <span class="status">

                    @if(in_array($order->order_status , array(1,2,4,8)))
                    Failed
                    @elseif($order->order_status == 9)
                    Delivered
                    @elseif($order->order_status == 10)
                    Refunded
                    @endif on {{date('D, M d, Y, h:i A', strtotime($order->updated_at))}}
                    <img src="{{asset('asset/customer/assets/images/delivered.svg')}}" alt="delivered">
                </span>
                <!-- <a href="#" class="show-sidepanel" id="orderPanel">View Details</a> -->
                <span class="amt">Total Paid: {{$user_data->currency ?? '$'}} {{$order->total_amount ?? ''}}</span>
            </div>
        </div>
        @endforeach
        <div class="container" style="display:inline;">
            <center> {{$order_data->links()}}</center>
        </div>
        @endif
    </div>

    <div class="tab-content" data-tab-id="ongoing">
        @if($current_order_data != NULL)
        @foreach($current_order_data as $c_order)
        <div class="card-wrap">
            <div class="col-left">
                <div class="text-with-img">
                    <div class="img-wrap">
                        <img src="{{$c_order->resto_picture ?? url('asset/customer/assets/images/resto_thumbnail.png')}}"
                            alt="order2">
                    </div>
                    <div class="text-wrap">
                        <h6>{{$c_order->resto_name ?? '--'}}</h6>
                        <span class="cat">{{$c_order->resto_address ?? '--'}}</span>
                        <span class="order-id">ORDER ID - {{$c_order->order_id ?? '--'}} |
                            {{date('D, M d, Y, h:i A', strtotime($c_order->created_at))}} </span>
                        <span class="qty">
                            @if($c_order->ordered_menu != NULL)
                            @foreach($c_order->ordered_menu as $ordered_menu)
                            @if($loop->iteration == 1)
                            {{$ordered_menu->name}} x {{$ordered_menu->quantity}}
                            @if(isset($ordered_menu->add_on_data) && $ordered_menu->add_on_data != NULL)
                            (
                            @foreach($ordered_menu->add_on_data as $a_data)
                            @if($a_data->quantity !=NULL)
                            @if($loop->iteration == 1)
                            {{$a_data->name}} x {{$a_data->quantity}}
                            @else
                            /{{$a_data->name}} x {{$a_data->quantity}}
                            @endif
                            @endif
                            @endforeach
                            )
                            @endif

                            @else
                            /{{$ordered_menu->name}} x {{$ordered_menu->quantity}}
                            @if(isset($ordered_menu->add_on_data) && $ordered_menu->add_on_data != NULL)
                            (
                            @foreach($ordered_menu->add_on_data as $a_data)
                            @if($a_data->quantity !=NULL)
                            @if($loop->iteration == 1)
                            {{$a_data->name}} x {{$a_data->quantity}}
                            @else
                            /{{$a_data->name}} x {{$a_data->quantity}}
                            @endif
                            @endif
                            @endforeach
                            )
                            @endif
                            @endif
                            @endforeach
                            @endif
                        </span>
                    </div>
                </div>
                <div class="btn-grp">
                    <a href="#" class="btn btn-purple">Reorder</a>
                    <a href="#" class="btn btn-purple">Help</a>
                    <a href="{{url('/trackOrder')}}{{'?odr_id='}}{{base64_encode($c_order->id)}}"
                        class="btn btn-purple">Track</a>
                </div>
            </div>
            <div class="col-right">
                <span class="status on-way">ETA: 10 Mins <img
                        src="{{asset('asset/customer/assets/images/delivered.svg')}}" alt="delivered"></span>
                <!-- <a href="#" class="show-sidepanel" id="orderPanel">View Details</a> -->
                <span class="amt">Total Paid: {{$user_data->currency ?? '$'}} {{$c_order->total_amount ?? ''}}</span>
            </div>
        </div>
        @endforeach
        @endif
    </div>
</div>
<div class="side-panel right" data-panel-id="orderPanel">
    <div class="inner-sidebar">
        <div class="title">
            <div class="icon close-sidepanel">
                <img src="{{asset('asset/customer/assets/images/cross.svg')}}" alt="cross">
            </div>
            <h4>Order #59908365721</h4>
        </div>
        <ul class="order-info">
            <li>
                <div class="order-route">
                    <div class="route-info">
                        <h6>Kungawo</h6>
                        <span>A,70, 3rd Floor W End Rd, Jamaica</span>
                    </div>
                    <div class="route-info">
                        <h6>W End Rd, West End, Jamaica</h6>
                        <span>A,70, 3rd Floor</span>
                    </div>
                </div>
            </li>
            <li>
                <div class="text-row">
                    <div class="left">
                        <span class="info">Delivered on Fri, Dec 20, 2019, 03:19 PM by Brushe soe</span>
                    </div>
                    <div class="right">
                        <span class="badge-icon">On time</span>
                    </div>
                </div>
            </li>
            <li>
                <div class="text-row">
                    <div class="left">
                        <span class="text-gray">1 Item</span>
                        <span class="info nonveg">Curry goat/mutton/chicken x 2</span>
                        <span class="text-gray">Full</span>
                    </div>
                    <div class="right">
                        <h6>$20.00</h6>
                    </div>
                </div>
            </li>
            <li>
                <div class="text-row">
                    <div class="left">
                        <h6>Item Total</h6>
                        <span class="text-gray">Delivery Fee</span>
                    </div>
                    <div class="right">
                        <h6>$20.00</h6>
                        <span class="text-gray">$5.00</span>
                    </div>
                </div>
            </li>
            <li>
                <div class="text-row">
                    <div class="left">
                        <h6>Bill Total</h6>
                        <span class="text-gray">Paid via Credit/Debit card</span>
                    </div>
                    <div class="right">
                        <h6>$25.00</h6>
                    </div>
                </div>
            </li>
        </ul>
        <div class="btn-grp">
            <button type="button" class="btn btn-purple">Rate and Review</button>
            <button type="button" class="btn btn-purple">Tip for Driver</button>
        </div>
    </div>
</div>
@endsection
