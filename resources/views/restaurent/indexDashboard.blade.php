@include('restaurent.include.sideNav')
@include('restaurent.include.header')
<div class="clearfix"></div>

<div class="content-wrapper">
    <div class="container-fluid">

        <!--Start Dashboard Content-->

        <div class="row mt-3">
            <div class="col-12 col-lg-6 col-xl-3">
                <div class="card bg-pattern-primary">
                    <div class="card-body">
                        <div class="media">
                            <div class="media-body text-left">
                                <h4 class="text-white">{{$order_stats->order_count ?? '0'}}</h4>
                                <span class="text-white">Total Order's</span>
                            </div>
                            <div class="align-self-center w-circle-icon rounded-circle bg-contrast">
                                <i class="icon-people text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 col-xl-3">
                <div class="card bg-pattern-danger">
                    <div class="card-body">
                        <div class="media">
                            <div class="media-body text-left">
                                <h4 class="text-white">{{$order_stats->pending_order_count ?? '0'}}</h4>
                                <span class="text-white">Pending Order's</span>
                            </div>
                            <div class="align-self-center w-circle-icon rounded-circle bg-contrast">
                                <i class="icon-user-following text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 col-xl-3">
                <div class="card bg-pattern-success">
                    <div class="card-body">
                        <div class="media">
                            <div class="media-body text-left">
                                <h4 class="text-white">87.5%</h4>
                                <span class="text-white">Total Revenue</span>
                            </div>
                            <div class="align-self-center w-circle-icon rounded-circle bg-contrast">
                                <i class="icon-pie-chart text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 col-xl-3">
                <div class="card bg-pattern-warning">
                    <div class="card-body">
                        <div class="media">
                            <div class="media-body text-left">
                                <h4 class="text-white">{{$order_stats->dish_count ?? '0'}}</h4>
                                <span class="text-white">Total Dish</span>
                            </div>
                            <div class="align-self-center w-circle-icon rounded-circle bg-contrast">
                                <i class="icon-user text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--End Row-->


        <div class="row">
            <!-- <div class="col-12 col-lg-4 col-xl-4">
                <div class="card bg-pattern-dark">
                    <div class="card-header bg-transparent text-white border-light">
                        Last Week Revenue
                        <div class="card-action">
                            <div class="dropdown">
                                <a href="javascript:void();" class="dropdown-toggle dropdown-toggle-nocaret"
                                    data-toggle="dropdown">
                                    <i class="icon-options text-white"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="javascript:void();">Action</a>
                                    <a class="dropdown-item" href="javascript:void();">Another action</a>
                                    <a class="dropdown-item" href="javascript:void();">Something else here</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="javascript:void();">Separated link</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="dashboard-chart-3" height="240"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-8 col-xl-8">
                <div class="card bg-pattern-dark">
                    <div class="card-header bg-transparent text-white border-light">
                        Orders Summary
                        <div class="card-action">
                            <div class="dropdown">
                                <a href="javascript:void();" class="dropdown-toggle dropdown-toggle-nocaret"
                                    data-toggle="dropdown">
                                    <i class="icon-options text-white"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="javascript:void();">Action</a>
                                    <a class="dropdown-item" href="javascript:void();">Another action</a>
                                    <a class="dropdown-item" href="javascript:void();">Something else here</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="javascript:void();">Separated link</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="dashboard-chart-4" height="240"></canvas>
                    </div>
                </div>
            </div> -->
            <!-- <div class="col-12 col-lg-4 col-xl-4">
                <div class="card bg-pattern-dark">
                    <div class="card-header bg-transparent text-white border-light">
                        Top Selling Categories
                        <div class="card-action">
                            <div class="dropdown">
                                <a href="javascript:void();" class="dropdown-toggle dropdown-toggle-nocaret"
                                    data-toggle="dropdown">
                                    <i class="icon-options text-white"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="javascript:void();">Action</a>
                                    <a class="dropdown-item" href="javascript:void();">Another action</a>
                                    <a class="dropdown-item" href="javascript:void();">Something else here</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="javascript:void();">Separated link</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="dashboard-chart-5" height="240"></canvas>
                    </div>
                </div>
            </div> -->
        </div>
        <!--End Row-->

        <!--End Dashboard Content-->

    </div>
    <!-- End container-fluid-->

</div>
<!--End content-wrapper-->
@include('restaurent.include.footer')