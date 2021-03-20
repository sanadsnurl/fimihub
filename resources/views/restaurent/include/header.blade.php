<!--Start topbar header-->
<header class="topbar-nav">
    <nav class="navbar navbar-expand fixed-top gradient-scooter">
        <ul class="navbar-nav mr-auto align-items-center">
            <li class="nav-item">
                <a class="nav-link toggle-menu" href="javascript:void();">
                    <i class="icon-menu menu-icon"></i>
                </a>
            </li>
        </ul>

        <ul class="navbar-nav align-items-center right-nav-link">

            <!-- <li class="nav-item language">
                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret waves-effect" data-toggle="dropdown"
                    href="#"><i class="flag-icon flag-icon-gb"></i></a>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li class="dropdown-item"> <i class="flag-icon flag-icon-gb mr-2"></i> English</li>
                    <li class="dropdown-item"> <i class="flag-icon flag-icon-fr mr-2"></i> French</li>
                    <li class="dropdown-item"> <i class="flag-icon flag-icon-cn mr-2"></i> Chinese</li>
                    <li class="dropdown-item"> <i class="flag-icon flag-icon-de mr-2"></i> German</li>
                </ul>
            </li> -->
            <li class="nav-item">
                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" data-toggle="dropdown" href="#">
                    <span class="user-profile"><i class="icon-user fa-1x mr-3 "></i></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li class="dropdown-item user-details">
                        <a href="javaScript:void();">
                            <div class="media">
                                <div class="avatar"><i class="icon-user fa-2x mr-3 text-danger"></i>
                                </div>
                                <div class="media-body">
                                    <h6 class="mt-2 user-title">{{$data->name}}</h6>
                                    <p class="user-subtitle">{{$data->email}}</p>
                                </div>
                            </div>
                        </a>
                    </li>

                    <li class="dropdown-divider"></li>
                    <a href="{{url('Restaurent/resetPassword')}}">
                        <li class="dropdown-item">
                            <i class="icon-power mr-2"></i> Reset Password
                        </li>
                    </a>
                    <li class="dropdown-divider"></li>
                    <a href="{{url('Restaurent/logout')}}">
                        <li class="dropdown-item">
                            <i class="icon-power mr-2"></i> Logout
                        </li>
                    </a>
                </ul>
            </li>
        </ul>
    </nav>
</header>
<!--End topbar header-->
