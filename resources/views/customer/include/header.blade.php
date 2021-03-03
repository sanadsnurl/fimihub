<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.6/css/intlTelInput.css">
    <link rel="stylesheet" href="{{asset('asset/customer/dist/main.css')}}">
    <link rel="icon" href="{{asset('asset/customer/assets/images/logo.png')}}">
    <!-- Start of HubSpot Embed Code -->
    <script type="text/javascript" id="hs-script-loader" async defer src="//js.hs-scripts.com/9482503.js"></script>
    <!-- End of HubSpot Embed Code -->
    <title>Fimihub</title>
</head>

<body>
    <div id="loading-overlay">
        <div class="loading-icon"></div>
    </div>
    <script>
        window.onload = function() {
            showPosition();
        };
    </script>
    <header class="header">
        <div class="md_container">
            <div class="inner-wrap">
                <div class="left-block">
                    <ul>
                        @if(Session::has('user'))

                        <li>
                            <div class="logo-wrap">
                                <a href="{{url('/home')}}">
                                    <img src="{{asset('asset/customer/assets/images/logo.png')}}" alt="logo">
                                </a>
                            </div>
                        </li>
                        <li>
                            <a href="#" class="location-link show-sidepanel" id="addressPanel"><img
                                    src="{{asset('asset/customer/assets/images/location.svg')}}" alt="location">
                                <span id="result" data-toggle="tooltip" title="">Location</span>
                                </span></a>
                        </li>
                        @else
                        <li>
                            <div class="logo-wrap">
                                <a href="{{url('/')}}">
                                    <img src="{{asset('asset/customer/assets/images/logo.png')}}" alt="logo">
                                </a>
                            </div>
                        </li>
                        <li>
                            <a href="#" class="location-link show-sidepanel" id="addressPanel"><img
                                    src="{{asset('asset/customer/assets/images/location.svg')}}" alt="location">
                                <span id="result" data-toggle="tooltip" title="">Location</span>
                            </a>
                        </li>
                        @endif

                    </ul>
                </div>
                <nav class="nav-menu">
                    <ul>
                        @if(Session::has('user'))
                        {{-- <li>
                            <a href="#" class="icon-link">
                                <img src="{{asset('asset/customer/assets/images/search_purple.svg')}}" alt="search">
                        </a>
                        </li> --}}
                        <li>
                            <a href="{{url('/cart')}}" class="icon-link cart_nofti">
                                <img src="{{asset('asset/customer/assets/images/cart.svg')}}" alt="cart">
                                <span class="notfi_cart" id="notfi_cart"> {{$user_data->cart_item_count ?? '!'}}

                                </span>
                            </a>
                        </li>

                        <li class="dropdown">
                            <!-- <a href="javascript:void(0)"><img src="./assets/images/user.svg" alt="user"> SIGN IN</a> -->
                            <button type="button" class="dropdown-toggle notifc_btn" data-toggle="dropdown">
                                <a href="#" class="icon-link cart_nofti">
                                    <img src="{{asset('asset/customer/assets/images/notification.svg')}}"
                                        alt="notification">
                                    <span>{{$user_data->notification_active_count ?? '!'}} </span>
                                </a>
                            </button>
                            <div class="dropdown-menu notification_dropdown">
                                <div class="notification_head d-flex align-items-center justify-content-between">
                                    <h4>NOTIFICATION</h4>

                                </div>
                                <div class="notification_body">
                                    <div class="nofication_content">
                                        <ul>
                                            @if(isset($user_data->notification_data) &&
                                            !empty($user_data->notification_data))
                                            @foreach($user_data->notification_data as $not_data)
                                            @php
                                                $str2 = substr($not_data->txn_id, 3);
                                                $order_id = ($str2 + 1)-1;
                                            @endphp
                                            <a href="{{url('/trackOrder')}}{{'?odr_id='}}{{base64_encode($order_id) ?? ''}}" class="g">

                                                <li class="d-flex align-items-center active">
                                                    <i class="fas fa-user-circle"></i>
                                                    <div>
                                                        <p>{{$not_data->title ?? ''}} | Order-Id {{$not_data->txn_id ?? ''}}
                                                        </p>
                                                        <span
                                                            class="time">{{date('d F Y',strtotime($not_data->created_at))}}</span>
                                                    </div>
                                                </li>

                                            </a>
                                            @endforeach
                                            @else
                                            <li class="d-flex align-items-center active">
                                                <i class="fas fa-user-circle"></i>
                                                <div>
                                                    <p>No New Notification</p>
                                                </div>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                                {{-- <div class="notification_ftr text-center">
                                    <a href="#">View all notification</a>
                                </div> --}}
                            </div>
                        </li>
                        <li>
                            <a href="#" class="icon-link user">
                                <img src="{{$user_data->picture ?? asset('asset/customer/assets/images/user_icon2.png')}}"
                                    alt="user">
                                {{$user_data->name ?? ''}}
                            </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="{{url('myAccount')}}"> <img
                                            src="{{asset('asset/customer/assets/images/user.svg')}}" alt="user"> My
                                        Account</a>
                                </li>
                                <li>
                                    <a href="{{url('logout')}}"> <img
                                            src="{{asset('asset/customer/assets/images/logout_icon.svg')}}" alt="user">
                                        Log Out</a>
                                </li>
                            </ul>
                        </li>
                        <li></li>
                        @else
                        <li>
                            <a href="{{url('partnerWithUs')}}">Sell with us</a>
                        </li>
                        <!-- <li>
                            <a href="#">Ride with us</a>
                        </li> -->
                        <li>
                            <a href="{{url('cart')}}" class="icon-link ">
                                <img src="{{asset('asset/customer/assets/images/cart.svg')}}" alt="cart">

                            </a>
                        </li>
                        <li>
                            <a href="{{url('login')}}"><img src="{{asset('asset/customer/assets/images/user.svg')}}"
                                    alt="user"> SIGN IN</a>
                        </li>
                        <li>
                            <a href="{{url('register')}}"> <img
                                    src="{{asset('asset/customer/assets/images/logout.svg')}}" alt="sign up"> SIGN
                                UP</a>
                        </li>
                        @endif

                    </ul>
                </nav>
                <div class="toggle-menu">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </div>
            </div>
        </div>
    </header>
