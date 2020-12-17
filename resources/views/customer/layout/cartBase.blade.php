@include('customer.include.header')
<section class="cart_login">
    <div class="container sm_container">
        <div class="cart_login_inr">
            <div class="progress_box">
                <ul class="steps">
                    @if(request()->is('trackOrder'))
                    <h3>Order Tracking</h3>
                    @else
                    <li class="step2 active"><span></span>
                        <p>Address</p>
                    </li>
                    <li class="step3 {{ request()->is('checkoutPage') ? 'active' : ''}}"><span></span>
                        <p>Payment</p>
                    </li>
                    @endif
                </ul>
            </div>
            <div class="row">

                @yield('content')

                <div class="col-md-5 padd_lft">
                    <div class="card_rht card pb-0">
                        <div class="card_rht_top">
                            <div class="row">
                                <div class="col-md-4">
                                    <img src="{{url('asset/customer/assets/images/food.png')}}" alt="image"
                                        class="w-100">
                                </div>
                                <div class="col-md-8">
                                    <h4>{{$resto_data->name ?? ''}}</h4>
                                    <p>{{$resto_data->address ?? ''}}</p>
                                </div>
                            </div>
                        </div>

                        @foreach($menu_data as $m_data)
                        <div class="food_detials_strip nonveg_food_strip">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="food_strip_lft">
                                        <h5>{{$user_data->currency ?? ''}} {{$m_data->price ?? ''}}</h5>
                                        <span class="red_dots"></span>
                                        @if($m_data->dish_type == 2)
                                        <h4 class="green_dot">{{$m_data->name ?? ''}}</h4>
                                        @else
                                        <h4 >{{$m_data->name ?? ''}}</h4>
                                        @endif
                                        <p>{{$m_data->about ?? ''}}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="food_strip_rht">
                                        @if(request()->is('trackOrder'))
                                        QTY - {{$m_data->quantity ?? '0'}}
                                        @else
                                        <button type="button" class="minus_btn"
                                            onClick="decrement_quantity('{{base64_encode($m_data->id)}}')">-</button>
                                        <input type="text" value="{{$m_data->quantity ?? '0'}}"
                                            id="input-quantity-{{$m_data->id}}" readonly>
                                        <button type="button" class="pluse_btn"
                                            onClick="increment_quantity('{{base64_encode($m_data->id)}}')">+</button>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        @if(request()->is('cart'))
                        <div class="aply_cupon ">
                            <a href="javascript:void(0)" class="d-flex accord_btn">
                                <span><img src="{{url('asset/customer/assets/images/cuppon_icon.svg')}}" alt="icon">
                                    APPLY COUPON</span>
                                <img src="{{url('asset/customer/assets/images/arrow_right.svg')}}" alt="arrow">
                            </a>
                            <div class="apply_cpn_box">
                                <form action="">
                                    <div class="d-flex align-items-center">
                                        <input type="text" class="form-control">
                                        <input type="submit">
                                    </div>
                                    <span class="success">
                                        <i class="fa fa-check-circle"></i>
                                        <i class="fa fa-times-circle"></i>
                                        Coupon applied successfully
                                    </span>
                                </form>
                            </div>
                        </div>
                        @endif


                        <div class="bill_details">
                            <h4>Bill Details</h4>
                            <div class="total_item pb-1">
                                <span> Total Item's </span>
                                <span><span id="item_count">{{$item ?? '0'}}</span></span>
                            </div>
                            <div class="total_item pb-1">
                                <span> Item Sub-Total </span>
                                <span>{{$user_data->currency ?? ''}} <span id="sub_total">{{number_format((float)$sub_total, 2) ?? '0'}}</span></span>
                            </div>
                            @if($resto_data->discount != 0 || $resto_data->discount != Null)

                            <div class="partner_fee">
                                <span>Restaurent Discount <img
                                        src="{{url('asset/customer/assets/images/info_icon.svg')}}" alt="info"></span>
                                <span>{{$user_data->currency ?? ''}} {{$resto_data->discount ?? '0'}}</span>
                            </div>
                            @endif

                            @if($resto_data->tax != 0 || $resto_data->tax != Null)
                            <div class="partner_fee">
                                <span>Restaurant Tax &nbsp;&nbsp;<img
                                        src="{{url('asset/customer/assets/images/info_icon.svg')}}" alt="info"></span>
                                <span>{{$user_data->currency ?? ''}} {{$resto_data->tax ?? '0'}}</span>
                            </div>
                            @endif
                            @if($service_data->service_tax != 0 || $service_data->service_tax != Null)
                            <div class="partner_fee">
                                <span> Tax ({{$service_data->tax}} %)&nbsp;&nbsp;<img
                                        src="{{url('asset/customer/assets/images/info_icon.svg')}}" alt="info"></span>
                                <span>{{$user_data->currency ?? ''}} <span
                                        id="service_tax">{{number_format((float)$service_data->service_tax, 2) ?? '0'}}</span></span>
                            </div>
                            @endif
                            <div class="charges_tax">
                                <span>Delivery partner fee <img
                                        src="{{url('asset/customer/assets/images/info_icon.svg')}}" alt="info"></span>
                                <span>{{$user_data->currency ?? ''}}
                                    <span>{{$resto_data->delivery_charge ?? '0'}}</span></span>
                            </div>
                        </div>
                        <input type="hidden" class="input-quantity" id="input-quantity"
                            value="{{base64_encode($resto_data->id)}}">
                        @if(request()->is('cart'))
                        <a href="{{url('checkoutPage')}}">
                            <div class="to_pay_box d-flex align-items-center">
                                <span>To pay</span>
                                <span>{{$user_data->currency ?? ''}} <span
                                        id="total_amount">{{number_format((float)$total_amount, 2) ?? '0'}}</span></span>
                            </div>
                        </a>
                        @elseif(request()->is('trackOrder'))
                        <div class="total_price d-flex align-items-center">
                            <span>Total</span>
                            <span>{{$user_data->currency ?? ''}} <span
                                id="total_amount">{{number_format((float)$total_amount, 2) ?? '0'}}</span></span>
                        </div>

                        <div class="to_pay_box button_box_hlp d-flex align-items-center">
                            <button type="button">Help</button>
                            <button type="button" data-toggle="modal" data-target="#review">Rate and Review</button>
                        </div>
                        @else
                        <div class="to_pay_box d-flex align-items-center">
                            <span>Total</span>
                            <span>{{$user_data->currency ?? ''}} <span
                                    id="total_amount">{{number_format((float)$total_amount, 2) ?? '0'}}</span></span>
                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('customer.include.footer')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
function increment_quantity(menu_id) {
    var resto_id = $("#input-quantity").val();
    var menu_decode_id = atob(menu_id);
    var inputQuantityElement = $("#input-quantity-" + menu_decode_id);
    var item_count = $("#item_count");
    var total_amount = $("#total_amount");
    var service_tax = $("#service_tax");
    var sub_total = $("#sub_total");

    $.ajax({
        url: "addMenuItem",
        data: "menu_id=" + menu_id + "&resto_id=" + resto_id,
        type: 'get',
        beforeSend: function() {
            $("#loading-overlay").show();
        },
        success: function(response) {
            var total_amnt = (response.total_amount + response.service_data.service_tax);
            total_amnt = total_amnt.toFixed(2);
            var service_taxs = response.service_data.service_tax.toFixed(2);
            var sub_totals = response.sub_total.toFixed(2);

            $(inputQuantityElement).val(response.quantity);
            $(item_count).html(response.items);
            $(sub_total).html(sub_totals);
            $(total_amount).html(total_amnt);
            $(service_tax).html(service_taxs);
            $("#loading-overlay").hide();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            $("#loading-overlay").hide();
            alert("something went wrong");
        }
    });
}

function decrement_quantity(menu_id) {
    var resto_id = $("#input-quantity").val();
    var menu_decode_id = atob(menu_id);
    var inputQuantityElement = $("#input-quantity-" + menu_decode_id);
    var item_count = $("#item_count");
    var total_amount = $("#total_amount");
    var service_tax = $("#service_tax");
    var sub_total = $("#sub_total");

    $.ajax({
        url: "subtractMenuItem",
        data: "menu_id=" + menu_id + "&resto_id=" + resto_id,
        type: 'get',
        beforeSend: function() {
            $("#loading-overlay").show();
        },
        success: function(response) {
            var total_amnt = (response.total_amount + response.service_data.service_tax);
            total_amnt = total_amnt.toFixed(2);
            var service_taxs = response.service_data.service_tax.toFixed(2);
            var sub_totals = response.sub_total.toFixed(2);

            $(inputQuantityElement).val(response.quantity);
            $(item_count).html(response.items);
            $(sub_total).html(sub_totals);
            $(total_amount).html(total_amnt);
            $(service_tax).html(service_taxs);
            $("#loading-overlay").hide();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            $("#loading-overlay").hide();
            alert("something went wrong");
        }
    });
}

</script>
