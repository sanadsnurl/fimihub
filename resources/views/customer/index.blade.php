@include('customer.include.header')
<section class="banner no-padding">
    <div class="slider-wrap">
        @foreach($slider_data as $s_data)
        <div class="slide-item">
            <div class="bg-img">
                <img src="{{url('asset/customer/assets/images/banner.png')}}" alt="banner">
            </div>
            <div class="content-wrap">
                <div class="container">
                    <div class="inner-wrap">
                        <div class="text-wrap">
                            <h1>Discover Restaurant & Delicious Food</h1>
                            <p>It's very easy to create stylish and beautiful prototypes for your future projects, both
                                graphical and dynamic.</p>
                        </div>
                        <div class="search-bar">
                            <div class="location-selector">
                                <span>Delhi NCR</span>
                            </div>
                            <div class="search-input">
                                <input type="text" placeholder="Search for restaurant, groceries,essentials & errand">
                                <div class="search-btn"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                            <img src="{{url('asset/customer/assets/images/groceries.svg')}}" alt="groceries">
                        </div>
                        <h5>Groceries, Essentials</h5>
                    </div>
                </a>
            </div>
            <div class="col-wrap">
                <a href="{{url('/home')}}">
                    <div class="inner-wrap">
                        <div class="icon">
                            <img src="{{url('asset/customer/assets/images/food.svg')}}" alt="food">
                        </div>
                        <h5>Food Delivery</h5>
                    </div>
                </a>
            </div>
            <div class="col-wrap">
                <a href="#">
                    <div class="inner-wrap">
                        <div class="icon">
                            <img src="{{url('asset/customer/assets/images/delivery.svg')}}" alt="delivery">
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
                        <img src="{{url('asset/customer/assets/images/secure.svg')}}" alt="secure">
                    </div>
                    <h6>Delivered in 45 mins</h6>
                    <p>The quickest way to get things delivered</p>
                </div>
            </div>
            <div class="col-wrap">
                <div class="card-wrap">
                    <div class="icon">
                        <img src="{{url('asset/customer/assets/images/map.svg')}}" alt="map">
                    </div>
                    <h6>Safety First</h6>
                    <p>Ensuring best practices to keep you and our partners safe at every step!</p>
                </div>
            </div>
            <div class="col-wrap">
                <div class="card-wrap">
                    <div class="icon">
                        <img src="{{url('asset/customer/assets/images/time.svg')}}" alt="time">
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
                        <img src="{{url('asset/customer/assets/images/member.png')}}" alt="member">
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
                        <img src="{{url('asset/customer/assets/images/sell.png')}}" alt="sell">
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
                        <li><a href="#"><img src="{{url('asset/customer/assets/images/play_store.jpg')}}"
                                    alt="play store"></a></li>
                        <li><a href="#"><img src="{{url('asset/customer/assets/images/app_store.jpg')}}"
                                    alt="app store"></a></li>
                    </ul>
                </div>
            </div>
            <div class="col-img">
                <div class="img-wrap">
                    <img src="{{url('asset/customer/assets/images/fimihub.jpeg')}}" alt="fimihub">
                </div>
            </div>
        </div>
    </div>
</section>
@include('customer.include.footer')
