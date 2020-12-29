<style>
    @charset "UTF-8";

    .star-cb-group {
        font-size: 0;
        unicode-bidi: bidi-override;
        direction: rtl;
    }

    .star-cb-group * {
        font-size: 1rem;
    }

    .star-cb-group>input {
        display: none;
    }

    .star-cb-group>input+label {
        /* only enough room for the star */
        display: inline-block;
        /* overflow: hidden; */
        /* text-indent: 9999px; */
        /* width: 1em; */
        white-space: nowrap;
        cursor: pointer;
    }

    .star-cb-group>input+label:before {
        display: inline-block;
        content: "☆";
        color: #888;
        font-size: 72px;
    }

    .star-cb-group>input:checked~label:before,
    .star-cb-group>input+label:hover~label:before,
    .star-cb-group>input+label:hover:before {
        content: "★";
        color: #7d3b8a;
    }

    .star-cb-group>.star-cb-clear+label {
        text-indent: -9999px;
        width: .5em;
        margin-left: -.5em;
    }

    .star-cb-group>.star-cb-clear+label:before {
        width: .5em;
    }

    .star-cb-group:hover>input+label:before {
        content: "☆";
        color: #888;
        text-shadow: none;
    }

    .star-cb-group:hover>input+label:hover~label:before,
    .star-cb-group:hover>input+label:hover:before {
        content: "★";
        color: #7d3b8a;
    }

    fieldset {
        border: 0;
        text-align: center;
    }

    #log {
        margin: 1em auto;
        width: 5em;
        text-align: center;
        background: transparent;
    }
</style>

<footer class="footer">
    <div class="md_container">
        <div class="row-wrap">
            <div class="col col-content">
                <h4><a href="./index.html">Fimihub</a></h4>
                <div class="content-wrap">
                    <p>FiMi Hub is the most convenient way for customers to get the food
                        they love, items they want, tasks they need done, or “Go for a lime”
                        anytime!</p>
                    <p>© 2020-2021, FiMi Hub</p>
                </div>
            </div>
            <div class="col col-links">
                <h5>FAQ</h5>
                <ul class="links">
                    <li>
                        <a href="#">Q&A for Merchants (comming soon)</a>
                    </li>
                    <li>
                        <a href="#">Q&A for Customers (comming soon)</a>
                    </li>
                    <li>
                        <a href="#">Q&A for Delivery & Shopper Partners (comming soon)</a>
                    </li>

                </ul>
            </div>
            <div class="col col-links">
                <h5>Other Page content</h5>
                <ul class="links">
                    <li>
                        <a href="#">Privacy Policy (comming soon)</a>
                    </li>
                    <li>
                        <a href="#">Terms & Conditions (comming soon)</a>
                    </li>
                    <li>
                        <a href="#">Merchant Terms & Conditions (comming soon)</a>
                    </li>
                    <li>
                        <a href="#">About Us (comming soon)</a>
                    </li>
                    <li>
                        <a href="#">Credit/Debit Card Security Policy (comming soon)</a>
                    </li>
                </ul>
            </div>
            <div class="col col-info">
                <h5>Contacts</h5>
                <p>Main Street, Claremont P.O. St. Ann, Jamaica</p>
                {{-- <p>+1 (234) 567-89-90</p> --}}
                <p>support@fimihub.com</p>
            </div>
            <div class="col col-form">
                <h5>Newsletter</h5>
                <form role="form" method="POST" action="{{ url('/subscribeProcess') }}" class="subscribe-form">
                    @csrf
                    <div class="field-group">
                        <input type="email" placeholder="Enter Email ID" name="email">
                        @if($errors->has('name'))
                        <div class="error">{{ $errors->first('name') }}</div>
                        @endif
                        <input type="submit" class="btn btn-purple btn-submit mt-1" value="Subscribe">
                        <!-- <a href="#" class="btn btn-purple btn-submit">Subscribe</a> -->
                    </div>
                </form>
                <ul class="social-links">
                    {{-- <li>
                        <a href="https://www.facebook.com/fimi.hub.3">
                            <img src="{{url('asset/customer/assets/images/twitter.svg')}}" alt="facebook">
                        </a>
                    </li> --}}
                    <li>
                        <a href="https://www.facebook.com/fimi.hub.3">
                            <img src="{{url('asset/customer/assets/images/facebook.svg')}}" alt="facebook">
                        </a>
                    </li>
                    {{-- <li>
                        <a href="#">
                            <img src="{{url('asset/customer/assets/images/instagram.svg')}}" alt="instagram">
                        </a>
                    </li> --}}
                </ul>
            </div>
        </div>
    </div>
</footer>
<div class="side-panel left" data-panel-id="addressPanel">
    <div class="inner-sidebar">
        <div class="title">
            <div class="icon close-sidepanel">
                <img src="{{url('asset/customer/assets/images/cross.svg')}}" alt="cross">
            </div>
            <h4>Save delivery address</h4>
        </div>
        <div id="address-map-container" style="width:105%;height:360px; margin-bottom: -115px;">
            <div style="width: 100%; height: 60%;" id="address-map"></div>
        </div>
        <form role="form" method="POST" action="{{ url('/saveAddress') }}" onSubmit="return checkform()"
            class="form save_adrs">
            @csrf
            <!-- <div class="form-group">
                <label for="address_address">Address</label>
                <input type="text" id="address-input" name="address_address" class="form-control map-input">

            </div> -->

            <div class="field-wrap">
                <label for="address_address">Address</label>
                <div class="address_box_dyn">
                    <input type="text" id="address-input" name="address_address" placeholder="Address"
                        class="map-input">
                    <button type="button" class="show_address"><i class="fa fa-crosshairs"></i></button>
                    <span id="add" class="errors"></span>

                </div>
                <input type="hidden" name="address_latitude" id="address-latitude" value="0" />
                <input type="hidden" name="address_longitude" id="address-longitude" value="0" />
                @if($errors->has('address_address'))
                <div class="error">{{ $errors->first('address') }}</div>
                @endif
                @if(Session::has('address_error'))
                <div class="error">{{ Session::get('address_error') }}</div>
                @endif
            </div>
            <div class="field-wrap">
                <label>Door/ Flat No</label>
                <input type="text" name="flat_no" placeholder="Door/ Flat No" id="flat">
                @if($errors->has('flat_no'))
                <div class="error">{{ $errors->first('flat_no') }}</div>
                @endif
                <span id="flaterr" class="errors"></span>
            </div>
            <div class="field-wrap">
                <label>Landmark</label>
                <input type="text" name="landmark" placeholder="Landmark" id="landmrk">
                @if($errors->has('landmark'))
                <div class="error">{{ $errors->first('landmark') }}</div>
                @endif
                <span id="landmarkerr" class="errors"></span>

            </div>
            <input type="submit" class="btn btn-purple" value="Save Address">
            <!-- <button type="submit" class="btn btn-purple">Save Address</button> -->
        </form>
    </div>
</div>
<script type="text/javascript" src="{{url('asset/customer/assets/scripts/plugins/jquery-3.4.1.min.js')}}"></script>
<script type="text/javascript" src="{{url('asset/customer/assets/scripts/plugins/slick.min.js')}}"></script>
<script type="text/javascript" src="{{url('asset/customer/assets/scripts/plugins/wow.js')}}"></script>
<script type="text/javascript" src="{{url('asset/customer/assets/scripts/main.js')}}"></script>
</body>

</html>
<!-- Subscribe Success MODAL -->
<div class="modal fade auth_modals successfull_mdl" id="forgot_psw">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal_body_head text-center">
                    <h3>
                        @if(Session::has('modal_message'))
                        {{ Session::get('modal_message') }}
                        @endif
                    </h3>
                </div>
                <div class="modal_body_content modal_body_content2 successfull_mdl_bdy text-center">
                    <img src="{{url('asset/customer/assets/images/check.png')}}" alt="checkmark">
                    <div class="forgot_psw_btn">
                        <button type="button" class="btn_purple hover_effect1 auth_btn"
                            data-dismiss="modal">BACK</button>
                    </div>

                    </p>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- order successfull modal -->
<div class="modal fade thankyou_mdl" id="thankyou">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h3>Order Confirmed!</h3>
                <img src="{{url('asset/customer/assets/images/cup_icon.svg')}}" alt="cup">
                <h3 class="mt-3 mb-3">THANK YOU!</h3>
                <p>Your order was successfully placed <br>and being prepared for delivery.</p>
                <div class="d-flex align-items-center justify-content-center">
                    <a href="{{url('/trackOrder')}}@if(Session::has('order_id')){{'?odr_id='}}{{Session::get('order_id')}}
                    @endif
                ">
                        <button type="button" class="btn_purple auth_btn hover_effect1 track_order_btn">TRACK YOUR
                            ORDER</button></a>
                    <a href="{{url('/home')}}" class="btn_purple auth_btn hover_effect1 backhome_btn">BACK TO HOME</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="side-panel right" data-panel-id="filterPanel">
    <div class="inner-sidebar">
        <div class="title">
            <div class="icon close-sidepanel">
                <img src="{{url('asset/customer/assets/images/cross.svg')}}" alt="cross">
            </div>
            <h4>Filters</h4>
        </div>
        <form action="#" class="form">
            <div class="radio-btn-block">
                <h5>Sort by</h5>
                <div class="radio-btn-wrap">
                    <input type="radio" id="fromAtoZ" name="sort" value="fromAtoZ">
                    <label for="fromAtoZ">A-Z</label>
                </div>
                <div class="radio-btn-wrap">
                    <input type="radio" id="fromZtoA" name="sort" value="fromZtoA">
                    <label for="fromZtoA">Z-A</label>
                </div>
                <div class="radio-btn-wrap">
                    <input type="radio" id="minOrderAmt" name="sort" value="minOrderAmt">
                    <label for="minOrderAmt">Minimum Order Amount</label>
                </div>
                <div class="radio-btn-wrap">
                    <input type="radio" id="fastDelivery" name="sort" value="fastDelivery">
                    <label for="fastDelivery">Fastest Delivery</label>
                </div>
                <div class="radio-btn-wrap">
                    <input type="radio" id="highToLow" name="sort" value="highToLow">
                    <label for="highToLow">Ratings: High to Low</label>
                </div>
                <div class="radio-btn-wrap">
                    <input type="radio" id="lowToHigh" name="sort" value="lowToHigh">
                    <label for="lowToHigh">Ratings: Low to High</label>
                </div>
            </div>
            <div class="radio-btn-block">
                <h5>Filter By</h5>
                <div class="radio-btn-wrap">
                    <input type="radio" id="freeDelivery" name="filter" value="freeDelivery">
                    <label for="freeDelivery">Free Delivery</label>
                </div>
                <div class="radio-btn-wrap">
                    <input type="radio" id="newlyAdded" name="filter" value="newlyAdded">
                    <label for="newlyAdded">Newly Added</label>
                </div>
            </div>
            <div class="btn-grp">
                <button type="button" class="btn btn-purple close-sidepanel">Cancel</button>
                <button type="submit" class="btn btn-purple">Apply Filter</button>
            </div>
        </form>
    </div>
</div>
<!-- reviews modal -->
@if(isset($order_event_data) && $order_event_data!=NULL && isset($order_event_data->rider->id) && isset($order_event_data->restaurant->id))

<div class="modal fade review_mdl" id="review">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <h3 class="text-center">Rate and Review</h3>
                <div class="mdl_top">
                    <div class="row">
                        <div class="col-md-4">
                            <img src="{{$resto_data->picture ?? url('asset/customer/assets/images/resto_thumbnail.png')}}"
                                alt="image" class="w-100">
                        </div>
                        <div class="col-md-8">
                            <h4>{{$resto_data->name ?? ''}}</h4>
                            <p>{{$resto_data->address ?? ''}}</p>
                        </div>
                    </div>
                </div>
                <form role="form" method="POST" action="{{ url('/feedback') }}">
                    @csrf
                    <fieldset>
                        <span class="star-cb-group">
                            <input type="radio" id="rating-5" name="restaurant_rating" value="5" />
                            <label for="rating-5"></label>

                            <input type="radio" id="rating-4" name="restaurant_rating" value="4" />
                            <label for="rating-4"></label>

                            <input type="radio" id="rating-3" name="restaurant_rating" value="3" />
                            <label for="rating-3"></label>

                            <input type="radio" id="rating-2" name="restaurant_rating" value="2" />
                            <label for="rating-2"></label>

                            <input type="radio" id="rating-1" name="restaurant_rating" value="1" />
                            <label for="rating-1"></label>

                        </span>
                        @if($errors->has('restaurant_rating'))
                        <div class="error">{{ $errors->first('restaurant_rating') }}</div>
                        @endif
                    </fieldset>

                    <input type="text" class="" name="resto_feedback" placeholder="Type Your message....">
                    <input type="hidden" class="" name="resto_event_id" value="{{$order_event_data->restaurant->id}}">
                    <input type="hidden" class="" name="rider_event_id" value="{{$order_event_data->rider->id}}">
                    @if($errors->has('resto_feedback'))
                    <div class="error" style="text-align-last: center;">{{ $errors->first('resto_feedback') }}</div>
                    @endif
                    <h4>Rate Rider</h4>
                    <div class="rider_review">
                        <p class="text-center"><img
                                src="{{$order_event_data->rider_details->picture ?? url('asset/customer/assets/images/user_dp.png')}}"
                                alt="image"> {{$order_event_data->rider_details->name ?? '---'}}</p>
                    </div>
                    <fieldset>
                        <span class="star-cb-group">
                            <input type="radio" id="ratings-5" name="rider_rating" value="5" />
                            <label for="ratings-5"></label>

                            <input type="radio" id="ratings-4" name="rider_rating" value="4" />
                            <label for="ratings-4"></label>

                            <input type="radio" id="ratings-3" name="rider_rating" value="3" />
                            <label for="ratings-3"></label>

                            <input type="radio" id="ratings-2" name="rider_rating" value="2" />
                            <label for="ratings-2"></label>

                            <input type="radio" id="ratings-1" name="rider_rating" value="1" />
                            <label for="ratings-1"></label>

                        </span>
                        @if($errors->has('rider_rating'))
                        <div class="error">{{ $errors->first('rider_rating') }}</div>
                        @endif
                    </fieldset>
                    <input type="submit" class="btn_purple auth_btn hover_effect1 submit_btn" value="Submit">
                </form>

            </div>
        </div>
    </div>
</div>
@endif

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.15.0/jquery.validate.min.js"></script>

<script type="text/javascript" src="{{url('asset/customer/assets/scripts/mapInput.js')}}"></script>
<script type="text/javascript" src="{{url('asset/customer/assets/scripts/currentLocation.js')}}"></script>
<script
    src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initialize"
    async defer></script>
@if(Session::has('modal_check_subscribe'))
<script>
    $(window).on('load', function() {
        $('#forgot_psw').modal('show');
    })
</script>
@endif
@if(Session::has('modal_check_order'))
<script>
    $(window).on('load', function() {
        $('#thankyou').modal('show');
    });
</script>
@endif

<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
    // $('.accord_btn').click(function() {
    //     $(this).next('.apply_cpn_box').slideToggle();
    //     $(this).find('span').next('img').toggleClass('rotate_icon');
    // })
    function checkform() {
        let add = document.getElementById('address-input').value;
        let flat = document.getElementById('flat').value;
        let landmrk = document.getElementById('landmrk').value;
        let lat = document.getElementById('address-latitude').value;
        let log = document.getElementById('address-longitude').value;
        let err = true;
        if (add == '') {
            document.getElementById('add').innerHTML = 'Address field required';
            document.getElementById('add').style.display = 'block';
            err = false;
        } else if (lat == '' || lat == 0 || log == '' || log == 0) {
            document.getElementById('add').innerHTML = 'Invalid address';
            document.getElementById('add').style.display = 'block';
            err = false;
        }
        if (flat == '') {
            document.getElementById('flaterr').innerHTML = 'Flat field required';
            document.getElementById('flaterr').style.display = 'block';
            err = false;
        }
        if (landmrk == '') {
            document.getElementById('landmarkerr').innerHTML = 'Landmark field required';
            document.getElementById('landmarkerr').style.display = 'block';
            err = false;
        }
        // If the script gets this far through all of your fields
        // without problems, it's ok and you can submit the form
        if (err == true) {
            return true;
        } else {
            return false;
        }
    }
    $('.save_adrs input').on('blur', function() {
        $(this).nextAll('span').hide();
    })
</script>
