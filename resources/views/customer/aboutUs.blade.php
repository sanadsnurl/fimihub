@extends('customer.layout.myAccountBase')

@section('title', 'My Account')

@section('content')
<div class="content-col">
    <div class="info-box">
        <div class="form-title">
            <h5>ABOUT US</h5>
        </div>
        <div class="inner-wrap">
            <div class="text-wrap">
                @foreach($about_data as $a_data)
                <p>
                    {{$a_data->content}}
                </p>
                @endforeach

            </div>
        </div>
    </div>
</div>
@endsection
