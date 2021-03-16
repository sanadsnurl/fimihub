<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from codervent.com/rocker/color-version/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 21 Sep 2018 19:45:16 GMT -->

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <meta name="csrf-token" content="{{csrf_token()}}" />
    <title>Fimihub - Restaurant</title>
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

</head>

<body>

    <!-- Start wrapper-->
    <div id="wrapper">

        <!--Start sidebar-wrapper-->
        <div id="sidebar-wrapper" data-simplebar="" data-simplebar-auto-hide="true">
            <div class="brand-logo">
                <a href="{{url('Restaurent/dashboard')}}">
                    <img src="{{asset('asset/customer/assets/images/logo.png')}}" class="logo-icon" alt="logo icon"
                        height="35px" width="25px">
                    <h5 class="logo-text">Fimihub</h5>
                </a>
            </div>
            <ul class="sidebar-menu do-nicescrol">
                <li class="sidebar-header">MAIN NAVIGATION</li>
                <li>
                    <a href="{{url('Restaurent/dashboard')}}" class="waves-effect">
                        <i class="icon-home"></i> <span>Dashboard</span>

                    </a>
                </li>
                <li>
                    <a href="{{url('Restaurent/customerOrder')}}" class="waves-effect">
                        <i class="icon-list"></i> <span>Orders</span>

                    </a>
                </li>
                <li>
                    <a href="#" class="waves-effect">
                        <i class="fa fa-cutlery"></i> <span>Restaurant </span> <i
                            class="fa fa-angle-right pull-right"></i>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="{{url('Restaurent/myDetails')}}"><i class="fa fa-circle-o"></i> Details</a></li>
                        <li><a href="{{url('Restaurent/menuCategory')}}"><i class="fa fa-circle-o"></i> Category</a></li>
                        <li><a href="{{url('Restaurent/menuList')}}"><i class="fa fa-circle-o"></i> Menu</a></li>


                    </ul>
                </li>
                <li>
                    <a href="#" class="waves-effect">
                        <i class="fa fa-glass"></i> <span>Add-On </span> <i
                            class="fa fa-angle-right pull-right"></i>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="{{url('Restaurent/menuCustomCategory')}}"><i class="fa fa-circle-o"></i> Category</a></li>
                        <li><a href="{{url('Restaurent/menuCustomList')}}"><i class="fa fa-circle-o"></i> Add-On Items</a></li>


                    </ul>
                </li>
                <li>
                    <a href="{{url('Restaurent/myEarnings')}}" class="waves-effect">
                        <i class="fa fa-money"></i> <span>My Earnings</span>

                    </a>
                </li>

                <li>
                    <a href="{{url('Restaurent/logout')}}" class="waves-effect">
                        <i class="icon-logout"></i> <span>Logout</span>

                    </a>
                </li>


            </ul>

        </div>
        <!--End sidebar-wrapper-->
<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/8.3.0/firebase-app.js"></script>
{{-- <script src="https://www.gstatic.com/firebasejs/7.23.0/firebase.js"></script> --}}

<!-- TODO: Add SDKs for Firebase products that you want to use
     https://firebase.google.com/docs/web/setup#available-libraries -->
<script src="https://www.gstatic.com/firebasejs/8.3.0/firebase-analytics.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.23.0/firebase-messaging.js"></script>
<script>
  // Your web app's Firebase configuration
  // For Firebase JS SDK v7.20.0 and later, measurementId is optional
  var firebaseConfig = {
    apiKey: "AIzaSyBJGjRapdLzCQzEHaryirAB6z9AxHv1E2E",
    authDomain: "fimihub-rider.firebaseapp.com",
    projectId: "fimihub-rider",
    storageBucket: "fimihub-rider.appspot.com",
    messagingSenderId: "325134169313",
    appId: "1:325134169313:web:27177c09890124edac33a0",
    measurementId: "G-QS48Z6L0JH"
  };

  firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();

    function initFirebaseMessagingRegistration() {
        messaging
            .requestPermission()
            .then(function () {
                return messaging.getToken()
            })
            .then(function(token) {
                console.log(token);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ route('savetoken') }}",
                    type: 'POST',
                    data: {
                        token: token,
                        _token: $('meta[name="csrf-token"]').attr('content'),
                    },
                    // dataType: 'JSON',
                    success: function (response) {
                        // console.log(response,'ham hai');
                        // alert('Token saved successfully.');
                    },
                    error: function (err) {
                        console.log('User Chat Token Error'+ err);
                    },
                });

            }).catch(function (err) {
                console.log('User Chat Token Error'+ err);
            });
     }

    messaging.onMessage(function(payload) {
        const noteTitle = payload.notification.title;
        const noteOptions = {
            body: payload.notification.body,
            icon: payload.notification.icon,
            url: payload.notification.url,
        };
        new Notification(noteTitle, noteOptions);
    });


</script>
