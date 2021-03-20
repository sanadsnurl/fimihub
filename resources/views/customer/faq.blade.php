@extends('customer.layout.myAccountBase')

@section('title', 'My Account')

@section('content')
<div class="content-col">
    <div class="info-box">
        <div class="form-title">
            <h5>FAQ's</h5>
        </div>
        <div class="inner-wrap">
            <div class="accordion" id="accordionExample">
                @foreach($faq_data as $f_data)
                <div class="card">
                    <div class="card-header" id="heading{{$f_data->id}}">
                        <h2 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse"
                                data-target="#collapse{{$f_data->id}}" aria-expanded="true"
                                aria-controls="collapse{{$f_data->id}}">
                                {{$f_data->heading ?? ''}}
                            </button>
                        </h2>
                    </div>
                    @if($loop->iteration ==1)
                    <div id="collapse{{$f_data->id}}" class="collapse show " aria-labelledby="heading{{$f_data->id}}"
                        data-parent="#accordionExample">

                        @else
                        <div id="collapse{{$f_data->id}}" class="collapse " aria-labelledby="heading{{$f_data->id}}"
                            data-parent="#accordionExample">

                            @endif

                            <div class="card-body">
                                {{$f_data->content ?? ''}}
                            </div>
                        </div>
                    </div>
                    @endforeach


                </div>
            </div>
        </div>
    </div>
    @endsection
