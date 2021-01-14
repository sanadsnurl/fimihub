@extends('customer.layout.cartBase')

@section('title', 'My Account')

@section('content')
<script src="{{asset('asset/customer/assets/scripts/plugins/creditly.js')}}"></script>
<div class="col-md-7 padd_rht">
    <div id="map"></div>
        <input type="hidden" id="user_lat" name="user_lat" value="{{$user_add_def->latitude ?? ''}}">
        <input type="hidden" id="user_long" name="user_long" value="{{$user_add_def->longitude ?? ''}}">
        <input type="hidden" id="resto_lat" name="resto_lat" value="{{$resto_add_def[0]->latitude ?? ''}}">
        <input type="hidden" id="resto_long" name="resto_long" value="{{$resto_add_def[0]->longitude ?? ''}}">
        <input type="hidden" id="flat_rate" name="flat_rate" value="{{$service_data->flat_delivery_charge ?? ''}}">
        <input type="hidden" id="flat_km" name="flat_km" value="{{$service_data->on_km ?? ''}}">
        <input type="hidden" id="after_flat_rate" name="after_flat_rate"
            value="{{$service_data->after_flat_delivery_charge ?? ''}}">

    <div class="card_lft card payment_method_card">
        <h3>Choose payment method</h3>

        <div class="payment_options">
            @if($errors->has('payment'))
            <div class="error" style="text-align:center;">
                <h4 class="error">{{ $errors->first('payment') }}</h4>
            </div>
            @endif
            @if($errors->has('delivery_fee'))
            <div class="error" style="text-align:center;">
                <h4 class="error">{{ $errors->first('delivery_fee') }}</h4>
            </div>
            @endif
            <div class="error" style="text-align:center;">

                <span id="add_error" class="error" style="text-align:center;font-size: 20px;
            font-weight: 600;">

                </span>
            </div>
            <form role="form" method="POST" action="{{ url('/addPaymentMethod') }}">
                @csrf
                <input type="radio" name="payment" id="stripe" value="1">
                <label for="stripe" id="bank_transfer">
                    <img src="{{asset('asset/customer/assets/images/bank.svg')}}" class="mr-2" style="height: 25px;"
                        alt="cash on delivery">
                    Bank Transfer
                    {{-- <img src="{{asset('asset/customer/assets/images/stripe.svg')}}" alt="stripe"> --}}
                </label>
                <div class="bank_content content">
                    <p>Please make your Bank Transfer using the following
                        banking details below. When you are through, please
                        send us the confirmation number and amount
                        via whatsapp (876-518-7786) or via email at
                        support@fimihub.com</p>

                        <ul>
                            <li><strong>Bank Name :</strong> NCB</li>
                            <li><strong>Bank Branch :</strong>   St. Ann’s Bay</li>
                            <li><strong>Account Name :</strong> Fimilocal Systems</li>
                            <li><strong>Account # JMD :</strong> 544509462</li>
                            <li><strong> Account # USD:</strong> 544509470</li>
                        </ul>
                </div>

                <input type="radio" name="payment" id="paypal" value="2">
                <label for="paypal">
                    <img src="{{asset('asset/customer/assets/images/paypal.svg')}}" alt="paypal">
                </label>

                <input type="radio" name="payment" id="cash" value="4">
                <label for="cash" id="cashondelivery">
                    <img src="{{asset('asset/customer/assets/images/cash-delivery.svg')}}" class="mr-2"
                        alt="cash on delivery">
                    CASH ON DELIVERY
                </label>

                <input type="radio" name="payment" id="atlantic" value="4">
                <label for="atlantic" id="atlantic">
                    <img src="{{asset('asset/customer/assets/images/bank.svg')}}" class="mr-2" style="height: 25px;"
                        alt="cash on delivery">
                        First Atlantic Commerce
                </label>
                <div class="content">
                    <div class="creditly-wrapper">
                        <div class="credit-card-wrapper">
                            <div class="first-row form-group">
                            <div class="col-12 col-sm-8 controls">
                                <label class="control-label">Card Number</label>
                                <input class="number credit-card-number form-control"
                                type="text" name="number"
                                inputmode="numeric" autocomplete="cc-number" autocompletetype="cc-number" x-autocompletetype="cc-number"
                                placeholder="&#149;&#149;&#149;&#149; &#149;&#149;&#149;&#149; &#149;&#149;&#149;&#149; &#149;&#149;&#149;&#149;">
                            </div>
                            <div class="col-12 col-sm-4 controls">
                                <label class="control-label">CVV</label>
                                <input class="security-code form-control"·
                                inputmode="numeric"
                                type="text" name="security-code"
                                placeholder="&#149;&#149;&#149;">
                            </div>
                            </div>
                            <div class="second-row form-group">
                            <div class="col-12 col-sm-8 controls">
                                <label class="control-label">Name on Card</label>
                                <input class="billing-address-name form-control"
                                type="text" name="name"
                                placeholder="John Smith">
                            </div>
                            <div class="col-12 col-sm-4 controls">
                                <label class="control-label">Expiration</label>
                                <input class="expiration-month-and-year form-control"
                                type="text" name="expiration-month-and-year"
                                placeholder="MM / YY">
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="delivery_fee" id="delivery_charge_input" value="">
                <input type="submit" class="btn_purple auth_btn hover_effect1 paynow_btn" value="Pay Now">
            </form>
        </div>
    </div>
</div>
@endsection
