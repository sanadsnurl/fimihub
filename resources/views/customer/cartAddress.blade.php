@extends('customer.layout.cartBase')

@section('title', 'My Account')

@section('content')
<div class="col-md-7 padd_rht">
    <div class="card_lft card card_addrs">
        <div class="card_addrs_tp">
            <h3>Select delivery address</h3>
            <p>You have a saved address in this location</p>

        </div>

        <div class="card_addrs_btm">
            @if(Session::has('message'))
            <div class="error" style="text-align:center;">
                <h4 class="error">{{ Session::get('message') }}</h4>
            </div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="addrs_box new_addrs_box h-100">
                        <div>
                            <h4>Add New Address</h4>
                            <!-- <p>W End Rd, West End, Jamaica</p> -->
                        </div>
                        <div class="addrs_action_btns">
                            <button type="button" class="btn_purple edit_btn hover_effect1 show-sidepanel"
                                id="addressPanel">Add New</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Select Different Address
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            @foreach($user_address as $user_add)
                            @if($user_add->default_status == 1)

                            @else

                            <a class="dropdown-item"
                                href="{{url('addDefaultAddress')}}{{'?add_id='}}{{base64_encode($user_add->id)}}"> <span
                                    class="name">{{$user_data->name}}</span> &nbsp; <b>|</b> &nbsp; <span
                                    class="address">{{$user_add->flat_no ?? ''}} {{$user_add->address ?? ''}}</span></a>

                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                @foreach($user_address as $user_add)
                @if($user_add->default_status == 1)
                <div class="col-md-12 address_pad">
                    <div class="addrs_box saved_addrs address_border">
                        <h4>{{$user_data->name}} <i>(Default)</i></h4>
                        <p>{{$user_add->flat_no ?? ''}} {{$user_add->address ?? ''}}</p>
                        <p>{{$user_add->landmark ?? ''}}</p>
                        <br>
                        <!-- <span><img src="{{asset('asset/customer/assets/images/watch.svg')}}" alt="watch">20 Min</span> -->
                        <div class="addrs_action_btns">
                            <a href="{{url('deleteAddress')}}{{'?add_id='}}{{base64_encode($user_add->id)}}" class="f">
                                <button type="button" class="btn_purple edit_btn mr-2 hover_effect1">Delete</button>
                            </a>
                        </div>
                    </div>
                </div>
                @else
                {{-- <div class="col-md-6 address_pad">
                    <div class="addrs_box saved_addrs">
                        <h4>{{$user_data->name}}</h4>
                <p>{{$user_add->flat_no ?? ''}} {{$user_add->address ?? ''}}</p>
                <p>{{$user_add->landmark ?? ''}}</p>
                <br>
                <!-- <span><img src="{{asset('asset/customer/assets/images/watch.svg')}}" alt="watch">20 Min</span> -->
                <div class="addrs_action_btns">
                    <a href="{{url('deleteAddress')}}{{'?add_id='}}{{base64_encode($user_add->id)}}" class="f">
                        <button type="button" class="btn_purple edit_btn mr-2 hover_effect1">Delete</button>
                    </a>
                    <a href="{{url('addDefaultAddress')}}{{'?add_id='}}{{base64_encode($user_add->id)}}" class="f">
                        <button type="button" class="btn_purple deliver_btn hover_effect1">Deliver Here</button>
                    </a>
                </div>
            </div>
        </div> --}}
        @endif
        @endforeach

    </div>
</div>
</div>
</div>

@endsection
