@include('customer.include.header')
<style>
    .rating_star .fa {
        position: relative;
        font-size: 32px;
        color: #7d3b8a;
    }

    .rating_star .fa-star-percentage {
        position: absolute;
        left: 0;
        top: 0;
        overflow: hidden;
    }

    .rating_star .fa-star {
        color: #7d3b8a;
    }
</style>
<section class="banner restaurant-detail food-banner no-padding">
    <div class="slider-wrap">
        <div class="slide-item">
            <div class="bg-img">
                <img src="{{asset('asset/customer/assets/images/restaurant_detail_banner.png')}}" alt="banner">
            </div>
        </div>

        <div class="slide-item">
            <div class="bg-img">
                <img src="{{asset('asset/customer/assets/images/restaurant_detail_banner.png')}}" alt="banner">
            </div>
        </div>
        <div class="slide-item">
            <div class="bg-img">
                <img src="{{asset('asset/customer/assets/images/restaurant_detail_banner.png')}}" alt="banner">
            </div>
        </div>
        <div class="slide-item">
            <div class="bg-img">
                <img src="{{asset('asset/customer/assets/images/restaurant_detail_banner.png')}}" alt="banner">
            </div>
        </div>
    </div>
</section>
<section class="order-block">
    <div class="container">
        <div class="restaurant-info">
            <div class="info-wrap">
                <h3>{{$resto_data->name ?? ''}}</h3>
                <h5>Cuisine 1, Cuisine 2, etc.</h5>
                <span class="location">{{$resto_data->address ?? ''}}</span>
            </div>
            <div class="rating-wrap">
                <div class="col-wrap">
                    @if($rating_data->rating_count >10)
                    <h5>{{$rating_data->rating_count ?? '--'}} Rating</h5>
                    <div class="img-wrap">
                        <span class="js-star-rating rating_star" data-rating="{{$rating_data->rating ?? '4'}}">
                            <span class="fa fa-star-o"></span>
                            <span class="fa fa-star-o"></span>
                            <span class="fa fa-star-o"></span>
                            <span class="fa fa-star-o"></span>
                            <span class="fa fa-star-o"></span>
                        </span>
                    </div>
                    @else
                    <h5>Rating</h5>
                    <span class="btn btn-danger" style="border-radius: 60%;
                    background-color: #7D3B8A;
                    padding: 7px;
                    border: 1px #7D3B8A;
                    ">NEW</span>
                    @endif
                </div>
                <div class="col-wrap">
                    <h5>Minimum Order Value</h5>
                    <h4>{{$user_data->currency ?? ''}} {{$resto_data->avg_cost ?? ''}}</h4>
                </div>
                <div class="col-wrap">
                    <h5>Preparation Time</h5>
                    <h4 class="eta">{{$resto_data->avg_time ?? ''}} Min</h4>
                </div>
            </div>
            <span class="collapse-tab">Details</span>
            <div class="about-wrap">
                <div class="col-wrap">
                    <h4>About</h4>
                    <p>{{$resto_data->about ?? ''}}</p>
                </div>
                <div class="col-wrap">
                    <h4>Other Details</h4>
                    <p>{{$resto_data->other_details ?? ''}}</p>
                </div>
            </div>
        </div>
        <div class="order-menu-row">
            <div class="col-menu">
                <div class="menu-block sticky">
                    <h3>Our Menus</h3>
                    <ul>
                        @foreach($menu_cat as $m_cat)
                        <li>
                            <a href="#{{$m_cat->cat_id}}">{{$m_cat->cat_name}}</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-order">
                <div class="filter-row">
                    {{-- <div class="btn-grp">
                        <span class="cstm_box cstm_checkbox mr-4">
                            <input type="checkbox" id="veg" checked>
                            <label for="veg">Veg Only</label>
                        </span>
                        <span class="cstm_box cstm_checkbox">
                            <input type="checkbox" id="NonVeg_veg" checked>
                            <label for="NonVeg_veg">NonVeg Only</label>
                        </span>

                    </div> --}}
                    {{-- <span class="filter-btn show-sidepanel" id="filterPanel">Apply Filter</span> --}}
                </div>

                <div class="cart-block sticky" id="cart_flex" @if($total_amount !=0)
                    style="display:flex; position: relative;" @endif>
                    <div class="col-left">
                        <h4>
                            <span class="totalItems" id="item_count">{{$item ?? '0'}}</span> Items
                            <span class="sep">|</span>
                            {{$user_data->currency ?? ''}} <span class="totalPrice"
                                id="total_amount">{{$total_amount ?? '0'}}</span>
                        </h4>
                        <p>{{$resto_data->name ?? ''}}</p>
                    </div>
                    <div class="col-right">
                        <h4><a href="{{url('/cart')}}">View Cart <img
                                    src="{{asset('asset/customer/assets/images/cart_white.svg')}}" alt="cart white"></a>
                        </h4>
                    </div>
                </div>

                @foreach($menu_cat as $m_cat)
                <div class="category-block" id="{{$m_cat->cat_id}}">
                    <h5>{{$m_cat->cat_name}}</h5>
                    @foreach($menu_data as $m_data)
                    @if($m_data->cat_name == $m_cat->cat_name)
                    <form action="" id="menu_form-{{$m_data->id ?? ''}}">
                        <div class="card-wrap">
                            <div class="inner-row">
                                <div class="img-wrap">
                                    <img src="{{$m_data->picture ?? url('asset/customer/assets/images/food_thumb2.png')}}"
                                        alt="food1">
                                </div>
                                <div class="text-wrap">
                                    <h6 class="price">
                                        @if($m_data->price == 0 || $m_data->price == NULL)
                                        Free
                                        @else
                                        {{$user_data->currency ?? ''}}
                                        {{$m_data->price ?? '' }}
                                        @endif
                                    </h6>
                                    @if($m_data->dish_type == 2)
                                    <h4 class="green_dot"><i class="fa fa-stop-circle-o"
                                            style="font-size:18px;color:green"></i>
                                        {{$m_data->name ?? ''}}</h5>
                                        @else
                                        <h5 class="red_dot"><i class="fa fa-stop-circle-o"
                                                style="font-size:18px;color:red"></i>
                                            {{$m_data->name ?? ''}}</h5>
                                        @endif
                                        <p>{{$m_data->about ?? ''}}</p>
                                </div>
                                <ul class="add-to-cart">
                                    <div onClick="increment_quantity('{{base64_encode($m_data->id)}}',1)">
                                        <li>-</li>
                                    </div>
                                    <li class="product_qty" id="input-quantity-{{$m_data->id}}">{{$m_data->quantity ?? '0'}}</li>
                                    <div onClick="increment_quantity('{{base64_encode($m_data->id)}}',2)">
                                        <li>+</li>
                                    </div>
                                </ul>

                            </div>

                            <div class="dropdown-grp">
                                {{-- Product variant --}}

                                @if(count($m_data->variant_data) )
                                <div class="opt-dropdown">
                                    <span class="selected">
                                        {!! $m_data->variant_data_cat->cat_name ?? 'Select' !!}
                                    </span>
                                    <div class="menu">
                                        <fieldset id="{{$m_data->name ?? ''}}">
                                            @if(count($m_data->variant_data))
                                            @foreach($m_data->variant_data as $v_data)
                                            <label class="size" for="small-{{$m_data->id ?? ''}}-{{$v_data->id ?? ''}}">
                                                <input type="radio" class="small-{{$m_data->id ?? ''}}"
                                                    onClick="increment_quantity('{{base64_encode($m_data->id)}}')"
                                                    id="small-{{$m_data->id ?? ''}}-{{$v_data->id ?? ''}}"
                                                    name="{{$m_data->id ?? ''}}-variant" value="{{$v_data->id ?? ''}}"
                                                    @if(!empty($m_data->cart_variant_id))
                                                {{$v_data->id == $m_data->cart_variant_id ? 'checked' : ''}}
                                                @elseif($loop->index==0)
                                                checked
                                                @endif
                                                >
                                                {{$v_data->name ?? '' }} <span class="price">
                                                    @if($v_data->price == 0 || $v_data->price == NULL)
                                                    Free
                                                    @else
                                                    {{$user_data->currency ?? ''}}
                                                    {{$v_data->price ?? '' }}
                                                    @endif
                                                </span>
                                            </label>
                                            @endforeach
                                            @endif
                                        </fieldset>
                                    </div>
                                </div>
                                @endif
                                {{-- Customization --}}
                                @php
                                $flag = 0;
                                foreach($m_data->add_ons_cat as $add_cat){
                                    if(!empty($add_cat)){
                                        $flag = 1;

                                    }
                                }

                                    // var_dump($m_data->add_ons_cat);
                                @endphp
                                @if(isset($m_data->add_ons_cat) && $flag ==1)
                                <div class="opt-dropdown" style="dislay:none;">
                                    <span class="selected">
                                        Popular Add-Ons
                                    </span>
                                    <div class="menu">
                                        <div class="accordion" id="accordionExample">
                                            {{-- @if(count($m_data->add_ons_cat)) --}}
                                            @foreach($m_data->add_ons_cat as $add_cat)
                                            @if(!empty($add_cat))
                                            <div class="card">
                                                <div class="card-header" id="heading-{{$m_data->id}}-{{$add_cat->cats_id}}">
                                                    <h2 class="mb-0">
                                                        <a class="btn btn-link  @if($add_cat->is_required == 1)
                                                            active_required
                                                             @endif" data-toggle="collapse"
                                                            data-target="#collapse-{{$m_data->id}}-{{$add_cat->cats_id}}"
                                                            aria-expanded="true"
                                                            aria-controls="collapse-{{$m_data->id}}-{{$add_cat->cats_id}}">
                                                            {{$add_cat->cat_name ?? ''}}
                                                            @if($add_cat->is_required == 1)
                                                            <span class="error">(*required)</span>
                                                            @endif
                                                        </a>
                                                    </h2>
                                                </div>

                                                <div id="collapse-{{$m_data->id}}-{{$add_cat->cats_id}}" class="collapse
                                                @if($loop->index == 0)
                                                show
                                                @endif
                                                " aria-labelledby="heading-{{$m_data->id}}-{{$add_cat->cats_id}}"
                                                    data-parent="#accordionExample">
                                                    <div class="card-body @if($add_cat->is_required == 1)
                                                        active_required_vij
                                                         @endif">
                                                        @foreach($m_data->add_on as $add_onss)
                                                        @if(count($add_onss))
                                                        @foreach($add_onss as $add_ons)
                                                        @if($add_cat->cat_name == $add_ons->cat_name)
                                                        <label for="cheese-{{$m_data->id}}-{{$add_ons->id ?? ''}}">
                                                            @if($add_cat->multiple_select == 1)
                                                            <input type="checkbox"
                                                                onClick="increment_quantity('{{base64_encode($m_data->id)}}')"
                                                                id="cheese-{{$m_data->id}}-{{$add_ons->id ?? ''}}"
                                                                name="custom_data[]" value="{{$add_ons->id ?? ''}}"
                                                                class="extras"

                                                                @if(in_array($add_ons->id,($m_data->product_adds_id) ??
                                                            [],FALSE)) checked @endif>
                                                            @else
                                                            <input type="radio"
                                                                onClick="increment_quantity('{{base64_encode($m_data->id)}}')"
                                                                id="cheese-{{$m_data->id}}-{{$add_ons->id ?? ''}}"
                                                                name="custom_data[{{$add_cat->cats_id ?? '1'}}]" value="{{$add_ons->id ?? ''}}"
                                                                class="extras"
                                                                @if(in_array($add_ons->id,($m_data->product_adds_id) ??
                                                            [],FALSE)) checked @endif>
                                                            @endif

                                                            {{$add_ons->name ?? '' }}
                                                            <span class="price">
                                                                @if($add_ons->price == 0 || $add_ons->price == NULL)
                                                                Free
                                                                @else
                                                                {{$user_data->currency ?? ''}}
                                                                {{$add_ons->price ?? '' }}
                                                                @endif
                                                            </span>
                                                        </label>
                                                        @endif
                                                        @endforeach
                                                        @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            @endforeach
                                            {{-- @endif --}}
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </form>

                    @endif
                    @endforeach
                </div>
                @endforeach
                <input type="hidden" class="input-quantity" id="input-quantity"
                    value="{{base64_encode($resto_data->id)}}">

            </div>
</section>
@include('customer.include.footer')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    function increment_quantity(menu_id, click_type = false) {

        var resto_id = $("#input-quantity").val();
        var menu_decode_id = atob(menu_id);
        var inputQuantityElement = $("#input-quantity-" + menu_decode_id);
        var isSendRequest = 0;
        inputQuantityElement.parents(".card-wrap").find(".active_required_vij input[type=checkbox]").each(function( index, value) {
            if($(this).prop('checked') == true) {
                isSendRequest = 1;
            }
            if(isSendRequest == 0) {
            alert("Please select atleast one required Addon")
            // console.log('request not sended');
            return false;
        }
        });


        // console.log('request sended');


        var variant_menu = $(".small-" + menu_decode_id).val();
        var menu_form_data = JSON.stringify($('#menu_form-' + menu_decode_id).serializeArray());
        var item_count = $("#item_count");
        var total_amount = $("#total_amount");
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
                console.log(response);
                if (response.items > 0) {
                    cart_flex.style.display = 'flex';
                } else {
                    cart_flex.style.display = 'none';
                }
                if (response.quantity > 0) {
                    // inputQuantityElement.parents(".card-wrap").find(".active_required").each(function(item, index){
                    //     let required_option = $(item).parents(".card").find("label .extras:checked").length
                    //     console.log(required_option);
                    //     if(required_option < 1) {
                    //         $(item).parents(".card").find("label .extras").each(function(subItem, index){
                    //             $(subItem).change(function(){
                    //                 if($(this).is(":checked")) {
                    //                     console.log('test');
                    //                 }
                    //             })
                    //         })
                    //     }
                    // })
                }
                $(inputQuantityElement).html(response.quantity);
                $(item_count).html(response.items);
                $(notfi_cart).html(response.items);
                $(total_amount).html(response.total_amount);
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
        setTimeout(()=> {
        $('.col-order .category-block .active_required_vij').each(function( index, value) {

            $(this).find("input").each(function(newIndex,NewValue) {
                if(newIndex == 0) {
                    $(this).prop('checked',true)
                }

                });
            });

     },100)

            $('.col-order .category-block .active_required_vij input').click(function() {
        let elm = $(this).prev('input')
        setTimeout(()=> {
            if(elm.is(':checked')) {
                $('.custom_card_bdy .demo_checkbox input').prop('checked',true);
            }else {
                $('.custom_card_bdy .demo_checkbox input').prop('checked',false);
            }
        },100)
    });

        // $(".active_required").each(function(){
        //     if($(this).parents(".card-wrap").find(".product_qty").text() == 0) {
        //         $(this).parents(".card").find(".extras").first().prop("checked", true);
        //     }
        // })

        // $(".active_required").parents(".card").find(".card-body").each(function(){
        //     let checkbox = $(this).find(".extras");
        //     let checkboxLength = checkbox.length;
        //     $(checkbox).change(function(){
        //         if(checkboxLength < 1) {
        //             $(this).prop("checked", true);
        //             alert("Please select at least one option!");
        //         }
        //     })
        // })
    });
</script>
