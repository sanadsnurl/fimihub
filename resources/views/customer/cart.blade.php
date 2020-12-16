@include('customer.include.header')

<section class="cart_box">
    <div class="container text-center">
        <img src="{{url('asset/customer/assets/images/empty-cart.png')}}" alt="image">
        <h3>Your cart is empty</h3>
        <p>You can go to home page to view more restaurants</p>
        <a href="{{url('home')}}" class="btn_purple hover_effect1">SEE RESTAURANTS NEAR YOU</a>
    </div>
</section>
@include('customer.include.footer')
