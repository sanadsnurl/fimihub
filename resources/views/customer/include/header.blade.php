<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{url('asset/customer/dist/main.css')}}">
    <link rel="icon" href="{{url('asset/customer/assets/images/logo.png')}}">
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
                                    <img src="{{url('asset/customer/assets/images/logo.png')}}" alt="logo">
                                </a>
                            </div>
                        </li>
                        <li>
                            <a href="#" class="location-link show-sidepanel" id="addressPanel"><img
                                    src="{{url('asset/customer/assets/images/location.svg')}}" alt="location">
                                    <span id="result" data-toggle="tooltip" title="Hooray!">Location</span>
                                    </span></a>
                        </li>
                        @else
                        <li>
                            <div class="logo-wrap">
                                <a href="{{url('/')}}">
                                    <img src="{{url('asset/customer/assets/images/logo.png')}}" alt="logo">
                                </a>
                            </div>
                        </li>
                        <li>
                            <a href="#" class="location-link show-sidepanel" id="addressPanel"><img
                                    src="{{url('asset/customer/assets/images/location.svg')}}" alt="location">
                                <span id="result" data-toggle="tooltip" title="Hooray!">Location</span>
                            </a>
                        </li>
                        @endif


                    </ul>
                </div>
                <nav class="nav-menu">
                    <ul>
                        @if(Session::has('user'))
                        <li>
                            <a href="#" class="icon-link">
                                <img src="{{url('asset/customer/assets/images/search_purple.svg')}}" alt="search">
                            </a>
                        </li>
                        <li>
                            <a href="{{url('/cart')}}" class="icon-link cart_nofti">
                                <img src="{{url('asset/customer/assets/images/cart.svg')}}" alt="cart">
                                <span class="notfi_cart" >8
                                    {{-- @if($item <100)
                                    {{$item ?? '0'}}
                                    @else
                                    99+
                                    @endif --}}
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="icon-link cart_nofti">
                                <img src="{{url('asset/customer/assets/images/notification.svg')}}" alt="notification">
                                <span>9</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="icon-link user">
                                <img src="{{$user_data->picture ?? url('asset/customer/assets/images/user_icon2.png')}}"
                                    alt="user">
                                {{$user_data->name ?? ''}}
                            </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="{{url('myAccount')}}"> <img
                                            src="{{url('asset/customer/assets/images/user.svg')}}" alt="user"> My
                                        Account</a>
                                </li>
                                <li>
                                    <a href="{{url('logout')}}"> <img
                                            src="{{url('asset/customer/assets/images/logout_icon.svg')}}" alt="user">
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
                                <img src="{{url('asset/customer/assets/images/cart.svg')}}" alt="cart">

                            </a>
                        </li>
                        <li>
                            <a href="{{url('login')}}"><img src="{{url('asset/customer/assets/images/user.svg')}}"
                                    alt="user"> SIGN IN</a>
                        </li>
                        <li>
                            <a href="{{url('register')}}"> <img src="{{url('asset/customer/assets/images/logout.svg')}}"
                                    alt="sign up"> SIGN UP</a>
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


