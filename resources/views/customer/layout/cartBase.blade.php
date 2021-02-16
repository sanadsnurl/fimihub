@include('customer.include.header')
<style>
    .rating_star .fa {
        position: relative;
        font-size: 14px;
        color: #fff;
    }

    .rating_star .fa-star-percentage {
        position: absolute;
        left: 0;
        top: 0;
        overflow: hidden;
    }

    .rating_star .fa-star {
        color: #fff;
    }

    .img-wrap {
        margin-top: -8px
    }
</style>
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
                                    <img src="{{$resto_data->picture ?? asset('asset/customer/assets/images/resto_thumbnail.png')}}"
                                        alt="image" class="w-100">
                                </div>
                                <div class="col-md-8">
                                    <h4>{{$resto_data->name ?? ''}}</h4>
                                    <p>{{$resto_data->address ?? ''}}</p>
                                </div>
                            </div>
                        </div>

                        @foreach($menu_data as $m_data)
                        <form action="" id="menu_form-{{$m_data->id ?? ''}}">

                            <div class="food_detials_strip nonveg_food_strip">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="food_strip_lft">
                                            <h5>{{$user_data->currency ?? ''}} {{$m_data->price ?? ''}}</h5>
                                            <span class="red_dots"></span>
                                            @if($m_data->dish_type == 2)
                                            <h4 class="green_dot">{{$m_data->name ?? ''}}</h4>
                                            @else
                                            <h4>{{$m_data->name ?? ''}}</h4>
                                            @endif

                                            @if(!empty($m_data->cart_variant_id))
                                            @foreach($m_data->variant_data as $v_data)
                                            @if($m_data->cart_variant_id == $v_data->id)
                                            <p>{{$v_data->cat_name ?? '--'}}: <span
                                                    class="size">{{$v_data->name}}</span></p>
                                            <input type="hidden" name="{{$m_data->id ?? NULL}}-variant"
                                                id="{{$m_data->id ?? NULL}}-variant"
                                                value="{{$m_data->cart_variant_id ?? NULL}}">

                                            @endif
                                            @endforeach
                                            @endif

                                            @if(!empty($m_data->product_adds_id))
                                            @foreach($m_data->add_on as $adds_data)
                                            @foreach($adds_data as $ad_data)
                                            @if(in_array($ad_data->id,($m_data->product_adds_id) ?? [],FALSE))
                                            <p>{{$ad_data->cat_name ?? '--'}}: <span
                                                    class="size">{{$ad_data->name}}</span></p>
                                            <input type="hidden" name="custom_data[]"
                                                id="cheese-{{$m_data->id}}-{{$ad_data->id ?? ''}}"
                                                value="{{$ad_data->id ?? NULL}}">

                                            @endif
                                            @endforeach
                                            @endforeach
                                            @endif
                                        </div>
                                    </div>
                                    {{-- <input type="text" name="custom_data" id="custom_data" value="{{(array)$m_data->product_adds_id ?? NULL}}">
                                    --}}
                                    <div class="col-md-4">
                                        <div class="food_strip_rht">
                                            @if(request()->is('trackOrder'))
                                            QTY - {{$m_data->quantity ?? '0'}}
                                            @else
                                            <button type="button" class="minus_btn"
                                                onClick="increment_quantity('{{base64_encode($m_data->id)}}',1)">-</button>
                                            <input type="text" value="{{$m_data->quantity ?? '0'}}"
                                                id="input-quantity-{{$m_data->id}}" readonly>
                                            <button type="button" class="pluse_btn"
                                                onClick="increment_quantity('{{base64_encode($m_data->id)}}',2)">+</button>
                                            @endif

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </form>
                        @endforeach

                        @if(request()->is('cart'))
                        {{-- <div class="aply_cupon ">
                            <a href="javascript:void(0)" class="d-flex accord_btn">
                                <span><img src="{{asset('asset/customer/assets/images/cuppon_icon.svg')}}" alt="icon">
                        APPLY COUPON</span>
                        <img src="{{asset('asset/customer/assets/images/arrow_right.svg')}}" alt="arrow">
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
                    </div> --}}
                    @endif

                    <div class="bill_details">
                        <h4>Bill Details</h4>
                        <div class="total_item pb-1">
                            <span> Total Item's </span>
                            <span><span id="item_count">{{$item ?? '0'}}</span></span>
                        </div>
                        <div class="total_item pb-1">
                            <span> Item Sub-Total </span>
                            <span>{{$user_data->currency ?? ''}} <span
                                    id="sub_total">{{number_format((float)$total_amount, 2) ?? '0'}}</span></span>
                        </div>
                        @if($resto_data->discount != 0 || $resto_data->discount != Null)

                        <div class="partner_fee">
                            <span>Restaurent Discount <img src="{{asset('asset/customer/assets/images/info_icon.svg')}}"
                                    alt="info"></span>
                            <span>{{$user_data->currency ?? ''}} {{$resto_data->discount ?? '0'}}</span>
                        </div>
                        @endif

                        @if($resto_data->tax != 0 || $resto_data->tax != Null)
                        <div class="partner_fee">
                            <span>Restaurant Tax &nbsp;&nbsp;<img
                                    src="{{asset('asset/customer/assets/images/info_icon.svg')}}" alt="info"></span>
                            <span>{{$user_data->currency ?? ''}} {{$resto_data->tax ?? '0'}}</span>
                        </div>
                        @endif
                        @if($service_data->service_tax != 0 || $service_data->service_tax != Null)
                        <div class="partner_fee">
                            <span> GCT ({{$service_data->tax}} %)&nbsp;&nbsp;<img
                                    src="{{asset('asset/customer/assets/images/info_icon.svg')}}" alt="info"></span>
                            <span>{{$user_data->currency ?? ''}} <span
                                    id="service_tax">{{number_format((float)$service_data->service_tax, 2) ?? '0'}}</span></span>
                        </div>
                        @endif
                        <div class="charges_tax">
                            <span>FiMi Hub Delivery
                                fee <img src="{{asset('asset/customer/assets/images/info_icon.svg')}}"
                                    alt="info"></span>
                            <span>{{$user_data->currency ?? ''}}
                                <span id="delivery_charge">{{$order_data->delivery_fee ?? '--'}}</span></span>
                        </div>
                    </div>
                    <input type="hidden" class="input-quantity" id="input-quantity"
                        value="{{base64_encode($resto_data->id)}}">
                    @if(request()->is('cart'))
                    <a href="{{url('checkoutPage')}}">
                        <div class="to_pay_box d-flex align-items-center">
                            <span class="proceed_to_pay">Proceed to Payment</span>
                            <span>{{$user_data->currency ?? ''}} <span
                                    id="total_amount">{{number_format((float)$total_amount_last, 2) ?? '0'}}</span></span>
                        </div>
                    </a>
                    @elseif(request()->is('trackOrder'))
                    <div class="total_price d-flex align-items-center pb-3">
                        <span>Total</span>
                        <span>{{$user_data->currency ?? ''}} <span
                                id="total_amount">{{number_format((float)$total_amount_last, 2) ?? '0'}}</span></span>
                    </div>
                    @if(in_array($order_data->order_status,array(9,10)))
                    <div class="to_pay_box button_box_hlp d-flex align-items-center">
                        <button type="button">Help</button>
                        <button type="button" data-toggle="modal" data-target="#review">Rate and Review</button>
                    </div>
                    @elseif(in_array($order_data->order_status,array(7)))

                    <div class="to_pay_box d-flex align-items-center">
                        <div class="d-flex align-items-start">
                            <div class="d-flex align-items-center">
                                <div>
                                    <img src="{{$order_event_data->rider_details->picture ?? asset('asset/customer/assets/images/user_dp.png')}}"
                                        alt="user">
                                </div>
                                <div>

                                    <p>{{$order_event_data->rider_details->name ?? '---'}}</p>
                                    <div class="img-wrap">
                                        {{-- <span class="js-star-rating rating_star" data-rating="4.5">
                                                <span class="fa fa-star-o"></span>
                                                <span class="fa fa-star-o"></span>
                                                <span class="fa fa-star-o"></span>
                                                <span class="fa fa-star-o"></span>
                                                <span class="fa fa-star-o"></span>
                                            </span> --}}
                                    </div>
                                </div>

                            </div>

                        </div>
                        <a href="tel:{{$order_event_data->rider_details->mobile ?? '---'}}" class="call_btn">Call</a>
                    </div>
                    @endif

                    @else
                    <div class="to_pay_box d-flex align-items-center">
                        <span>Total</span>
                        <span>{{$user_data->currency ?? ''}} <span
                                id="total_amount">{{number_format((float)$total_amount_last, 2) ?? '0'}}</span></span>
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
    function increment_quantity(menu_id, click_type = false) {
        var resto_id = $("#input-quantity").val();
        var menu_decode_id = atob(menu_id);
        var inputQuantityElement = $("#input-quantity-" + menu_decode_id);
        var variant_menu = $(".small-" + menu_decode_id).val();
        var menu_form_data = JSON.stringify($('#menu_form-' + menu_decode_id).serializeArray());
        var item_count = $("#item_count");
        var total_amount = $("#total_amount");
        var service_tax = $("#service_tax");
        var sub_total = $("#sub_total");
        var cart_flex = document.getElementById('cart_flex');
        var notfi_cart = document.getElementById('notfi_cart');
        $.ajax({
            url: "addMenuItem",
            data: "menu_id=" + menu_id + "&resto_id=" + resto_id + "&menu_data=" + menu_form_data +
                "&click_event=" + click_type,
            type: 'get',
            beforeSend: function() {
                $("#loading-overlay").show();
            },
            success: function(response) {
                // alert("something went wrong");
                var delivery_charge = parseFloat($("#delivery_charge").text());
                var total_amnt = (response.total_amount + response.service_data.service_tax);
                total_amnt = total_amnt.toFixed(2);
                var service_taxs = response.service_data.service_tax.toFixed(2);
                var sub_totals = response.sub_total.toFixed(2);
                if (isNaN(delivery_charge)) {} else {
                    total_amnt = parseFloat(total_amnt) + delivery_charge;
                }
                total_amnt = total_amnt.toFixed(2);
                if (response.quantity == 0) {
                    // $(remove_all_count).val(0);
                }
                if (response.items == 0) {
                    var url = window.location.protocol + '//' + window.location.host + '/cart';
                    console.log(url);
                    window.location.href = url;
                }
                $(inputQuantityElement).val(response.quantity);
                // $(sub_total).val(response.total_amount);
                $(total_amount).val(total_amnt);
                $(inputQuantityElement).html(response.quantity);
                $(item_count).html(response.items);
                $(notfi_cart).html(response.items);
                $(sub_total).html(response.total_amount);
                $(service_tax).html(service_taxs);
                $(total_amount).html(total_amnt);
                $("#loading-overlay").hide();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $("#loading-overlay").hide();
                alert("something went wrong");
            }
        });
    }
    /* display rating in form of stars */
    $.fn.makeStars = function() {
        $(this).each(function() {
            var rating = $(this).data('rating'),
                starNumber = $(this).children().length,
                fullStars = Math.floor(rating),
                halfStarPerc = (rating - fullStars) * 100;
            if (rating > 0) {
                $(this).children().each(function(index) {
                    $(this).addClass('fa-star');
                    $(this).removeClass('fa-star-o');
                    return ((index + 1) < fullStars);
                });
            }
            if (halfStarPerc !== 0 && fullStars < starNumber) {
                var halfStar = $(this).children(":nth-child(" + parseInt(fullStars + 1, 10) + ")");
                $('<span class="fa fa-star fa-star-percentage"></span>').width(halfStarPerc + '%').appendTo(
                    halfStar);
            }
        });
    };
    $(document).ready(function() {
        $('.js-star-rating').makeStars();
    });
</script>
