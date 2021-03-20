<link rel="icon" href="{{asset('asset/customer/assets/images/logo.png')}}">
<style>
    body {
        margin:0;
    }

    .not_found {
        min-height:100vh;
        box-sizing: border-box;
    }

    .not_found .container {
        max-width:85%;
        margin:0 auto;
    }

    .not_found img {
        max-width: 500px;
        display:block;
        margin:0 auto;
    }

    .not_found_inr {
        height: 100%;
        display: flex;
        align-items: center;
        flex-direction: column;
        justify-content: center;
    }


    .not_found_inr p {
        font-family: cursive;
        font-size: 26px;
        margin: 0;
        font-weight: 700;
        color: #ff4e5a;
    }

    .not_found_inr h2 {
        font-size: 72px;
        color: #1b2e35;
        margin: 25px 0;
        font-family: cursive
    }

    .not_found_inr a {
        color: #fff;
        background-color: #ff505b;
        text-decoration: none;
        font-family: cursive;
        padding: 15px 40px;
        border-radius: 6px;
        margin-top: 40px;
    }

</style>

<section class="not_found">
    <div class="container">
        <div class="not_found_inr">
            <img src="{{ asset('asset/customer/assets/images/notfound.jpg') }}" alt="not found" />
            <h2>Page not found !</h2>
            <p>We're sorry, the page you requested could not be found.</p>
            <a href="{{url('/home')}}">Go to home</a>
        </div>
    </div>
</section>
