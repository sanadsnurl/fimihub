@include('customer.include.header')
<section class="banner no-padding">
    <div class="slider-wrap">
        @foreach($slider_data as $s_data)
        @if($s_data->media != NULL)
        <div class="slide-item">
            <div class="bg-img">
                <img src="{{url($s_data->media)}}" alt="banner">
            </div>
            <div class="content-wrap">
                <div class="container">
                    <div class="inner-wrap">
                        <div class="text-wrap">
                            <h1>{{$s_data->text1 ?? ''}}</h1>
                            <p>{{$s_data->text2 ?? ''}}</p>
                        </div>
                        <br>
                        @if($s_data->link != NULL)
                        <a href="{{$s_data->link ?? ''}}" class="btn btn-lg btn-white">See More</a>
                        @endif
                        {{-- <form role="form" method="POST" action="{{ url('/saveAddress') }}" onSubmit="return checkform()"
                            class="form save_adrs">
                            @csrf
                            <div class="search-bar">
                                <div id="address-map-container" style="width:0%;height:0px; margin-bottom: 0px;">
                                    <div style="width: 0%; height: 0%;" id="location-map"></div>
                                </div>

                                <div class="location-selector">
                                    <span>
                                        <!-- <input id="autocomplete" placeholder="Enter your address" onFocus="geolocate()"
                                            type="text"> -->

                                        <div class="field-wrap">
                                            <div class="address_box_dyn">
                                                <div class="error">Invalid Location</div>
                                                <input type="text" data-id="location-input" name="address_address"
                                                    placeholder="Search Location" class="map-input">

                                            </div>
                                            <input type="hidden" name="address_latitude" id="location-latitude"
                                                value="0" />
                                            <input type="hidden" name="address_longitude" id="location-longitude"
                                                value="0" />
                                            @if($errors->has('address_address'))
                                            <div class="error">{{ $errors->first('address') }}</div>
                                            @endif
                                            @if(Session::has('address_error'))
                                            <div class="error">{{ Session::get('address_error') }}</div>
                                            @endif
                                        </div>
                                    </span>
                                </div>

                                <div class="search-input">
                                    <input type="text" id="filter_name" placeholder="Search for restaurant, food">

                                    <div class="search-btn">
                                        <input type="submit" value=" ">
                                    </div>
                                </div>
                            </div>
                        </form> --}}
                    </div>
                </div>
            </div>
        </div>
        @endif
        @endforeach

    </div>
</section>
<section class="col-three-icon">
    <div class="container">
        <div class="intro">
            <h3>What would you like to do?</h3>
            <h4>Fresh & Local</h4>
        </div>
        <div class="row-wrap">
            <div class="col-wrap">
                <a href="#">
                    <div class="inner-wrap">
                        <div class="icon">
                            <img src="{{asset('asset/customer/assets/images/groceries.svg')}}" alt="groceries">
                        </div>
                        <h5>Groceries, Essentials</h5>
                    </div>
                </a>
            </div>
            <div class="col-wrap">
                <a href="{{url('/home')}}">
                    <div class="inner-wrap">
                        <div class="icon">
                            <img src="{{asset('asset/customer/assets/images/food.svg')}}" alt="food">
                        </div>
                        <h5>Food Delivery</h5>
                    </div>
                </a>
            </div>
            <div class="col-wrap">
                <a href="#">
                    <div class="inner-wrap">
                        <div class="icon">
                            <img src="{{asset('asset/customer/assets/images/delivery.svg')}}" alt="delivery">
                        </div>
                        <h5>Errand</h5>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>
<section class="col-three-card">
    <div class="container">
        <div class="row-wrap">
            <div class="col-wrap">
                <div class="card-wrap">
                    <div class="icon">
                        <img src="{{asset('asset/customer/assets/images/secure.svg')}}" alt="secure">
                    </div>
                    <h6>Delivered in 45 mins</h6>
                    <p>The quickest way to get things delivered</p>
                </div>
            </div>
            <div class="col-wrap">
                <div class="card-wrap">
                    <div class="icon">
                        <img src="{{asset('asset/customer/assets/images/map.svg')}}" alt="map">
                    </div>
                    <h6>Safety First</h6>
                    <p>Ensuring best practices to keep you and our partners safe at every step!</p>
                </div>
            </div>
            <div class="col-wrap">
                <div class="card-wrap">
                    <div class="icon">
                        <img src="{{asset('asset/customer/assets/images/time.svg')}}" alt="time">
                    </div>
                    <h6>Available 24x7</h6>
                    <p>Day or night, get it delivered</p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="img-with-content">
    <div class="container">
        <div class="outer-wrap">
            <div class="row-wrap">
                <div class="col-img">
                    <div class="img-wrap">
                        <img src="{{asset('asset/customer/assets/images/member.png')}}" alt="member">
                    </div>
                </div>
                <div class="col-content">
                    <div class="content-wrap">
                        <h4>Become a Fimihub Partner</h4>
                        <p>List your business on the fastest growing hyperlocal delivery service</p>
                        <a href="{{url('partnerWithUs')}}" class="btn btn-white">Know More</a>
                    </div>
                </div>
            </div>
            <div class="row-wrap img-right">
                <div class="col-img">
                    <div class="img-wrap">
                        <img src="{{asset('asset/customer/assets/images/sell.png')}}" alt="sell">
                    </div>
                </div>
                <div class="col-content">
                    <div class="content-wrap">
                        <h4>Sell on Fimihub</h4>
                        <p>List your business on the fastest growing hyperlocal delivery service</p>
                        <a href="{{url('partnerWithUs')}}" class="btn btn-white">Know More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="cta no-padding">
    <div class="container">
        <div class="row-wrap">
            <div class="col-content">
                <div class="content-wrap">
                    <h2>Fimihub helps you to order food more easily</h2>
                    <ul class="links">
                        <li><a href="#"><img src="{{asset('asset/customer/assets/images/play_store.jpg')}}"
                                    alt="play store"></a></li>
                        <li><a href="#"><img src="{{asset('asset/customer/assets/images/app_store.jpg')}}"
                                    alt="app store"></a></li>
                    </ul>
                </div>
            </div>
            <div class="col-img">
                <div class="img-wrap">
                    <img src="{{asset('asset/customer/assets/images/fimihub.jpeg')}}" alt="fimihub">
                </div>
            </div>
        </div>
    </div>
</section>

@include('customer.include.footer')

