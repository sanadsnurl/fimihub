@extends('customer.layout.myAccountBase')

@section('title', 'My Account')

@section('content')


<div class="content-col">
    <div class="info-box">
        <div class="form-title">
            <h5>PERSONAL INFORMATION</h5>

        </div>
        <div class="inner-wrap">
            <form role="form" method="POST" action="{{ url('/updateProfile') }}" class="form" enctype="multipart/form-data">
                @csrf
                <div class="img-upload">
                    <div class="user-img">
                        <input type="file" name="picture">

                        <img src="{{$user_data->picture ?? asset('asset/customer/assets/images/img_upload.svg')}}"
                            alt="img upload">
                        @if($errors->has('picture'))
                        <div class="error">{{ $errors->first('picture') }}</div>
                        @endif
                    </div>
                    <label>Upload Image</label>
                </div>
                <div class="input-wrap">
                    <input type="text" placeholder="Full Name" name="name" value="{{$user_data->name ?? ''}}">
                    @if($errors->has('name'))
                    <div class="error">{{ $errors->first('name') }}</div>
                    @endif
                </div>

                <div class="input-wrap">
                    <input type="email" placeholder="Email" name="email" value="{{$user_data->email ?? ''}}">
                    @if($errors->has('email'))
                    <div class="error">{{ $errors->first('email') }}</div>
                    @endif
                </div>
                <div class="btn-wrap">
                    <input type="submit" class="btn btn-purple" value="Modify">
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
