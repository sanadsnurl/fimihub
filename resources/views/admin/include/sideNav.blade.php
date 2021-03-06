<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from codervent.com/rocker/color-version/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 21 Sep 2018 19:45:16 GMT -->

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Fimihub - Admin</title>
    <!--favicon-->
    <link rel="icon" href="{{asset('asset/customer/assets/images/logo.png')}}">

    <!-- Vector CSS -->
    <link href="{{asset('asset/admin/assets/plugins/vectormap/jquery-jvectormap-2.0.2.css')}}" rel="stylesheet" />
    <!-- simplebar CSS-->
    <link href="{{asset('asset/admin/assets/plugins/simplebar/css/simplebar.css')}}" rel="stylesheet" />
    <!-- Bootstrap core CSS-->
    <link href="{{asset('asset/admin/assets/css/bootstrap.min.css')}}" rel="stylesheet" />
    <!-- animate CSS-->
    <link href="{{asset('asset/admin/assets/css/animate.css')}}" rel="stylesheet" type="text/css" />
    <!-- Icons CSS-->
    <link href="{{asset('asset/admin/assets/css/icons.css')}}" rel="stylesheet" type="text/css" />
    <!-- Sidebar CSS-->
    <link href="{{asset('asset/admin/assets/css/sidebar-menu.css')}}" rel="stylesheet" />
    <!-- Custom Style-->
    <link href="{{asset('asset/admin/assets/css/app-style.css')}}" rel="stylesheet" />

    @if (request()->segment(2) == 'trackOrder')
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
            integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.6/css/intlTelInput.css">
        <link rel="stylesheet" href="{{asset('asset/customer/dist/main.css')}}">
        <link rel="icon" href="{{asset('asset/customer/assets/images/logo.png')}}">
    @endif
</head>

<body>

    <!-- Start wrapper-->
    <div id="wrapper">

        <!--Start sidebar-wrapper-->
        <div id="sidebar-wrapper" data-simplebar="" data-simplebar-auto-hide="true">
            <div class="brand-logo">
                <a href="{{url('adminfimihub/dashboard')}}">
                    <img src="{{asset('asset/customer/assets/images/logo.png')}}" class="logo-icon" alt="logo icon"
                        height="35px" width="25px">
                    <h5 class="logo-text">Fimihub Admin</h5>
                </a>
            </div>
            <ul class="sidebar-menu do-nicescrol">
                <li class="sidebar-header">MAIN NAVIGATION</li>
                <li>
                    <a href="{{url('adminfimihub/dashboard')}}" class="waves-effect">
                        <i class="icon-home"></i> <span>Dashboard</span>

                    </a>
                </li>
                @if($data->role == NULL || in_array(2,$data->role))
                <li>
                    <a href="#" class="waves-effect">
                        <i class="icon-user-following"></i> <span>Restaurant </span> <i
                            class="fa fa-angle-right pull-right"></i>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="{{url('adminfimihub/addRestaurent')}}"><i class="fa fa-circle-o"></i>Add
                                Restaurant</a></li>
                        <li><a href="{{url('adminfimihub/retaurantList')}}"><i class="fa fa-circle-o"></i>Restaurant
                                List</a></li>
                        <li><a href="{{url('adminfimihub/pendingRetaurant')}}"><i class="fa fa-circle-o"></i>New
                                Request</a></li>
                        <li><a href="{{url('adminfimihub/menuCategory')}}"><i class="fa fa-circle-o"></i>Category</a>
                        </li>

                    </ul>
                </li>
                @endif
                @if($data->role == NULL || in_array(1,$data->role))
                <li>
                    <a href="#" class="waves-effect">
                        <i class="icon-disc"></i> <span>Rider </span> <i class="fa fa-angle-right pull-right"></i>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="{{url('adminfimihub/riderList')}}"><i class="fa fa-circle-o"></i>Rider
                                List</a></li>
                        <li><a href="{{url('adminfimihub/pendingRider')}}"><i class="fa fa-circle-o"></i>New
                                Request</a></li>
                        <li><a href="{{url('adminfimihub/nearByRider')}}"><i class="fa fa-circle-o"></i>Rider Location</a></li>
                    </ul>
                </li>
                @endif
                @if($data->role == NULL)
                <li>
                    <a href="#" class="waves-effect">
                        <i class="icon-user"></i> <span>Customer </span> <i
                            class="fa fa-angle-right pull-right"></i>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="{{url('adminfimihub/userList')}}"><i class="fa fa-circle-o"></i>Customer List</a>
                        </li>

                    </ul>
                </li>
                @endif
                @if($data->role == NULL || in_array(3,$data->role))
                <li>
                    <a href="{{url('adminfimihub/customerOrder')}}" class="waves-effect">
                        <i class="icon-list"></i> <span>Order's</span>

                    </a>
                </li>
                @endif
                @if($data->role == NULL)
                <li>
                    <a href="{{url('adminfimihub/serviceList')}}" class="waves-effect">
                        <i class="icon-grid"></i> <span>Services</span>

                    </a>
                </li>
                @endif
                @if($data->role == NULL)
                {{-- <li>
                    <a href="{{url('adminfimihub/paymentMethod')}}" class="waves-effect">
                        <i class="icon-credit-card"></i> <span>Payment</span>

                    </a>
                </li> --}}
                @endif
                @if($data->role == NULL)
                <li>
                    <a href="#" class="waves-effect">
                        <i class="icon-magic-wand"></i> <span>CMS </span> <i class="fa fa-angle-right pull-right"></i>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="{{url('adminfimihub/getFaq')}}"><i class="fa fa-circle-o"></i>FAQ's</a></li>
                        <li><a href="{{url('adminfimihub/tnc')}}"><i class="fa fa-circle-o"></i>T&C</a></li>
                        <li><a href="{{url('adminfimihub/aboutUs')}}"><i class="fa fa-circle-o"></i>About Us</a></li>
                        <li><a href="{{url('adminfimihub/legalInfo')}}"><i class="fa fa-circle-o"></i>Legal Info</a>
                        </li>
                        <li><a href="{{url('adminfimihub/slider')}}"><i class="fa fa-circle-o"></i>Slider</a></li>

                    </ul>
                </li>
                @endif
                @if($data->role == NULL)
                <li>
                    <a href="{{url('adminfimihub/envSetting')}}" class="waves-effect">
                        <i class="icon-wrench"></i> <span>Config</span>

                    </a>
                </li>
                @endif
                @if($data->role == NULL)
                <li>
                    <a href="{{url('adminfimihub/getSubAdmin')}}" class="waves-effect">
                        <i class="icon-wrench"></i> <span>Sub-Admin</span>

                    </a>
                </li>
                @endif
                <li>
                    <a href="{{url('adminfimihub/logout')}}" class="waves-effect">
                        <i class="icon-logout"></i> <span>Logout</span>

                    </a>
                </li>

            </ul>

        </div>
        <!--End sidebar-wrapper-->
