<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from codervent.com/rocker/color-version/pages-404.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 21 Sep 2018 20:05:51 GMT -->

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

</head>

<body class="bg-error">

    <!-- Start wrapper-->
    <div id="wrapper">

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="text-center error-pages">
                        <h1 class="error-title text-primary"> 404</h1>
                        <h2 class="error-sub-title text-dark">404 not found</h2>

                        <p class="error-message text-dark text-uppercase">Sorry, an error has occured, Requested page
                            not found!</p>

                        <div class="mt-4">
                            <a href="{{url('adminfimihub/dashboard')}}" class="btn btn-primary btn-round shadow-primary m-1">Go To Home </a>

                        </div>


                    </div>
                </div>
            </div>
        </div>

    </div>
    <!--wrapper-->

    @include('admin.include.footer')
