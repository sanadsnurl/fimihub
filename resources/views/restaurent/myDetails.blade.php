@include('restaurent.include.sideNav')
@include('restaurent.include.header')
<div class="clearfix"></div>

<div class="content-wrapper">
    <div class="container-fluid">

        <!-- End Breadcrumb-->
        <div class="row">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-body">
                        <form role="form" method="POST" action="{{ url('Restaurent/addRestaurentDetails')}}"
                            id="personal-info" enctype="multipart/form-data">
                            @csrf
                            <h4 class="form-header text-uppercase">
                                <i class="fa fa-info-circle"></i>
                                Restaurant Details
                            </h4>
                            @if(Session::has('message'))
                            <div class="error" style="text-align:center;">
                                <h4 class="error">{{ Session::get('message') }}</h4>
                            </div>

                            @endif
                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Restaurant Picture</label>
                                <div class="col-sm-10">
                                    <input type="file" class="form-control" id="input-1" name="picture">
                                    @if($errors->has('picture'))
                                    <div class="error">{{ $errors->first('picture') }}</div>
                                    @endif
                                </div>

                            </div>
                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Restaurant Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="input-1" name="name"
                                        value="{{$resto_data->name ?? old('name')}}">
                                    @if($errors->has('name'))
                                    <div class="error">{{ $errors->first('name') }}</div>
                                    @endif
                                </div>

                            </div>

                            <div class="form-group row">
                                <label for="input-3" class="col-sm-2 col-form-label">About Us</label>
                                <div class="col-sm-10">
                                    <textarea name="about" class="form-control" cols="100"
                                        rows="3">{{$resto_data->about ?? old('about')}}</textarea>

                                    @if($errors->has('about'))
                                    <div class="error">{{ $errors->first('about') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="input-3" class="col-sm-2 col-form-label">Other Details</label>
                                <div class="col-sm-10">
                                    <textarea name="other_details" class="form-control" cols="100"
                                        rows="3">{{$resto_data->other_details ?? old('other_details')}}</textarea>

                                    @if($errors->has('other_details'))
                                    <div class="error">{{ $errors->first('other_details') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="input-4" class="col-sm-2 col-form-label">Official Number</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="input-4" name="official_number"
                                        value="{{$resto_data->official_number ?? old('official_number')}}">
                                    @if($errors->has('official_number'))
                                    <div class="error">{{ $errors->first('official_number') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="input-4" class="col-sm-2 col-form-label">Restaurant Type</label>
                                <div class="demo-checkbox ml-4">
                                    <input type="radio" id="user-checkbox" class="filled-in chk-col-primary" value="2"
                                        name="resto_type" @if(isset($resto_data->resto_type))
                                    {{$resto_data->resto_type == 2 ? 'checked' : ''}}
                                    @else
                                    {{old('resto_type') == 2 ? 'checked':''}}
                                    @endif
                                    >
                                    <label for="user-checkbox">Veg</label>
                                </div>
                                <div class="demo-checkbox">
                                    <input type="radio" id="user-checkbox1" class="filled-in chk-col-primary" value="1"
                                        name="resto_type" @if(isset($resto_data->resto_type))
                                    {{$resto_data->resto_type == 1 ? 'checked' : ''}}
                                    @else
                                    {{old('resto_type') == 1 ? 'checked':''}}
                                    @endif
                                    >
                                    <label for="user-checkbox1">Non-Veg</label>
                                </div>
                                <div class="demo-checkbox">
                                    <input type="radio" id="user-checkbox2" class="filled-in chk-col-primary" value="3"
                                        name="resto_type" @if(isset($resto_data->resto_type))
                                    {{$resto_data->resto_type == 3 ? 'checked' : ''}}
                                    @else
                                    {{old('resto_type') == 3 ? 'checked':''}}
                                    @endif>
                                    <label for="user-checkbox2">Both</label>
                                </div>
                                @if($errors->has('resto_type'))
                                <div class="error">{{ $errors->first('resto_type') }}</div>
                                @endif
                            </div>

                            <div class="form-group row">
                                <label for="input-4" class="col-sm-2 col-form-label">Average Cost</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="input-4" name="avg_cost"
                                        value="{{$resto_data->avg_cost ?? old('avg_cost')}}">
                                    @if($errors->has('avg_cost'))
                                    <div class="error">{{ $errors->first('avg_cost') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="input-4" class="col-sm-2 col-form-label">Average Time</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="input-4" name="avg_time"
                                        value="{{$resto_data->avg_time ?? old('avg_time')}}">
                                    @if($errors->has('avg_time'))
                                    <div class="error">{{ $errors->first('avg_time') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="input-4" class="col-sm-2 col-form-label">Open Time</label>
                                <div class="col-sm-10">
                                    <input type="time" class="form-control" id="input-4" name="open_time"
                                        value="{{$resto_data->open_time ?? old('open_time')}}">
                                    @if($errors->has('open_time'))
                                    <div class="error">{{ $errors->first('open_time') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="input-4" class="col-sm-2 col-form-label">Close Time</label>
                                <div class="col-sm-10">
                                    <input type="time" class="form-control" id="input-4" name="close_time"
                                        value="{{$resto_data->close_time ?? old('close_time')}}">
                                    @if($errors->has('close_time'))
                                    <div class="error">{{ $errors->first('close_time') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="input-4 address_address" class="col-sm-2 col-form-label">Address</label>
                                <div class="col-sm-8">
                                    <input type="text" data-id="address-input" name="address_address" placeholder="Address"
                                        class="map-input form-control" value="{{$resto_add->address ?? old('address')}}">

                                    <input type="hidden" name="address_latitude" id="address-latitude"
                                        value="{{$resto_add->latitude ?? old('latitude')}}" />
                                    <input type="hidden" name="address_longitude" id="address-longitude"
                                        value="{{$resto_add->longitude ?? old('longitude')}}" />
                                    @if($errors->has('address_address'))
                                    <div class="error">{{ $errors->first('address_address') }}</div>
                                    @endif
                                    @if(Session::has('address_error'))
                                    <div class="error">{{ Session::get('address_error') }}</div>
                                    @endif
                                </div>
                            </div>
                            {{-- <div class="form-group row">
                                <div class="col-sm-6">
                                    <div id="address-map-container"
                                        style="width:10%;height:100px; margin-bottom: -115px;">
                                        <div style="width: 100%; height: 60%;" id="address-map"></div>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="form-group row">
                                <label for="input-4" class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-6">
                                    <div id="address-map-container"
                                        style="width:100%;height:220px; margin-bottom: -87px;">
                                        <div style="width: 100%; height: 60%;" id="address-map"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="input-4" class="col-sm-2 col-form-label">Zip Code</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="input-4" name="pincode"
                                        value="{{$resto_data->pincode ?? old('pincode')}}">
                                    @if($errors->has('pincode'))
                                    <div class="error">{{ $errors->first('pincode') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-footer">
                                <input type="submit" class="btn btn-primary" value="Save Data">

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--End Row-->

    </div>
    <!-- End container-fluid-->

</div>
<!--End content-wrapper-->
@include('restaurent.include.footer')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script type="text/javascript" src="{{asset('asset/customer/assets/scripts/mapInput.js')}}"></script>
<script
    src="https://maps.googleapis.com/maps/api/js?key={{ Config('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initialize"
    async defer></script>
