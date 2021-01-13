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
                        <form role="form" method="GET" action="{{ url('/home') }}" onSubmit="return checkform()"
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
                                                    placeholder="Search Location" id="location-input" class="map-input">

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
                                    <input type="text" id="filter_name" name="search_field" placeholder="Search for restaurant, food">

                                    <div class="search-btn">
                                        <input type="submit" value=" ">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @endforeach

    </div>
</section>
<section class="card-grid">
    <div class="container">
        <div class="grid-container">
            <div class="intro-row">
                <div class="intro-wrap">
                    <h3>Our Most Popular Restaurant</h3>
                    <h5>Fresh Food Gurantee</h5>
                </div>
                <!-- <span class="filter-btn show-sidepanel" id="filterPanel">Filter</span> -->
            </div>
            <div class="row-wrap">
                @foreach($resto_data as $r_data)
                <div class="col-wrap">
                    <div class="card-wrap">
                        <a href="{{url('restaurentDetails')}}{{'?resto_id='}}{{base64_encode($r_data->id)}}">
                            <div class="img-wrap">
                                <img src="{{$r_data->picture ?? asset('asset/customer/assets/images/resto_thumbnail.png')}}"
                                    alt="restaurant">
                                <div class="img-cutout"></div>
                                <span class="rating">4.1 (60+)</span>
                            </div>
                            <div class="text-wrap">
                                <h6>{{$r_data->name ?? ''}}</h6>
                                <span class="eta">{{$r_data->avg_time ?? '--'}} Min</span>
                                <p>{{$r_data->about ?? ''}}</p>
                            </div>
                        </a>
                    </div>
                </div>
                @endforeach

                <div class="btn-wrap">
                    <a href="#" class="btn btn-transparent btn-lg">See All</a>
                </div>
            </div>
            <div class="grid-container">
                <div class="intro-row">
                    <div class="intro-wrap">
                        <h3>Vegetarian Options</h3>
                        <h5>Fresh Food Gurantee</h5>
                    </div>
                    <!-- <span class="filter-btn">Filter</span> -->
                </div>
                <div class="row-wrap">
                    @foreach($veg as $r_data)
                    <div class="col-wrap">
                        <div class="card-wrap">
                            <a href="{{url('restaurentDetails')}}{{'?resto_id='}}{{base64_encode($r_data->id)}}">
                                <div class="img-wrap">
                                    <img src="{{$r_data->picture ?? asset('asset/customer/assets/images/resto_thumbnail.png')}}"
                                        alt="restaurant">
                                    <div class="img-cutout"></div>
                                    <span class="rating">4.1 (60+)</span>
                                </div>
                                <div class="text-wrap">
                                    <h6>{{$r_data->name ?? ''}}</h6>
                                    <span class="eta">{{$r_data->avg_time ?? '--'}} Min</span>
                                    <p>{{$r_data->about ?? ''}}</p>
                                </div>
                            </a>
                        </div>
                    </div>
                    @endforeach

                    <div class="btn-wrap">
                        <a href="#" class="btn btn-transparent btn-lg">See All</a>
                    </div>
                </div>
            </div>
            <div class="grid-container">
                <div class="intro-row">
                    <div class="intro-wrap">
                        <h3>Non - Vegetarian Options</h3>
                        <h5>Fresh Food Gurantee</h5>
                    </div>
                    <!-- <span class="filter-btn">Filter</span> -->
                </div>
                <div class="row-wrap">
                    @foreach($nonveg as $r_data)
                    <div class="col-wrap">
                        <div class="card-wrap">
                            <a href="{{url('restaurentDetails')}}{{'?resto_id='}}{{base64_encode($r_data->id)}}">
                                <div class="img-wrap">
                                    <img src="{{$r_data->picture ?? asset('asset/customer/assets/images/resto_thumbnail.png')}}"
                                        alt="restaurant">
                                    <div class="img-cutout"></div>
                                    <span class="rating">4.1 (60+)</span>
                                </div>
                                <div class="text-wrap">
                                    <h6>{{$r_data->name ?? ''}}</h6>
                                    <span class="eta">{{$r_data->avg_time ?? '--'}} Min</span>
                                    <p>{{$r_data->about ?? ''}}</p>
                                </div>
                            </a>
                        </div>
                    </div>
                    @endforeach

                    <div class="btn-wrap">
                        <a href="#" class="btn btn-transparent btn-lg">See All</a>
                    </div>
                </div>
            </div>
        </div>
</section>
@include('customer.include.footer')
