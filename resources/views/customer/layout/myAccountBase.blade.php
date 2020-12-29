@include('customer.include.header')
<section class="dashboard">
    <div class="intro">
        <div class="md_container">
            <h4>My Account</h4>
        </div>
    </div>
    <div class="md_container">
        <div class="row-wrap">
            <div class="side-menu-col">
                <div class="user-info">
                    <div class="img-wrap">
                        <img src="{{$user_data->picture ?? url('asset/customer/assets/images/user_icon2.png')}}"
                            alt="user">
                    </div>
                    <div class="info-wrap">
                        <h6>{{$user_data->name ?? ''}}</h6>
                        <p>{{$user_data->email ?? ''}}</p>
                        <p>{{$user_data->mobile ?? ''}}</p>
                    </div>
                    <div class="toggle-menu">
                        <span class="dot"></span>
                        <span class="dot"></span>
                        <span class="dot"></span>
                    </div>
                </div>
                <div class="menu">
                    <ul>
                        <li>
                            <a href="{{url('myAccount')}}" class="{{ request()->is('myAccount') ? 'active' : ''}}">Account Information</a>
                        </li>
                        <li>
                            <a href="{{url('myOrder')}}" class="{{ request()->is('myOrder') ? 'active' : ''}}">My Orders</a>
                        </li>
                        <li>
                            <a href="{{url('changePassword')}}" class="{{ request()->is('changePassword') ? 'active' : ''}}">Change Password</a>
                        </li>
                        <li>
                            <a href="{{url('termsCondition')}}" class="{{ request()->is('termsCondition') ? 'active' : ''}}">Terms and Conditions</a>
                        </li>
                        <li>
                            <a href="{{url('FAQ')}}" class="{{ request()->is('FAQ') ? 'active' : ''}}">FAQ's</a>
                        </li>
                        <li>
                            <a href="{{url('saveaddress')}}" class="{{ request()->is('saveaddress') ? 'active' : ''}}">Manage Saved Addresses</a>
                        </li>
                        <li>
                            <a href="{{url('contactUs')}}" class="{{ request()->is('contactUs') ? 'active' : ''}}">Contact Us</a>
                        </li>
                        <li>
                            <a href="{{url('legalInformation')}}" class="{{ request()->is('legalInformation') ? 'active' : ''}}">Privacy Ploicy</a>
                        </li>
                        <li>
                            <a href="{{url('aboutUs')}}" class="{{ request()->is('aboutUs') ? 'active' : ''}}">About Us</a>
                        </li>
                        <li>
                            <a href="{{url('logout')}}"><img
                                    src="{{url('asset/customer/assets/images/logout_icon.svg')}}" alt="logout">
                                Logout</a>
                        </li>
                    </ul>
                </div>
            </div>

            @yield('content')

        </div>
    </div>
</section>
@include('customer.include.footer')
