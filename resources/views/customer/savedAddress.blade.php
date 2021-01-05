@extends('customer.layout.myAccountBase')

@section('title', 'My Account')

@section('content')

<div class="content-col">
    <div class="info-box">
        <div class="form-title">
            <h5>Manage Address</h5>
        </div>
        <div class="inner-wrap">
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

                @foreach($user_address as $user_add)
                @if($user_add->default_status == 1)
                <div class="col-md-6 address_pad">
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
                <div class="col-md-6 address_pad">
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
                            <a href="{{url('addDefaultAddress')}}{{'?add_id='}}{{base64_encode($user_add->id)}}"
                                class="f">
                                <button type="button" class="btn_purple deliver_btn hover_effect1">Deliver
                                    Here</button></a>
                        </div>
                    </div>
                </div>
                @endif
                @endforeach

            </div>
        </div>
    </div>
</div>
@endsection
