(function($) {
    // header
    var toggleEl = $(".header .inner-wrap .toggle-menu");
    var nav = $(".header .inner-wrap .nav-menu");
    toggleEl.click(function() {
        $(this).toggleClass("show");
        nav.slideToggle();
    });

    // banner slider
    var slider = $(".banner .slider-wrap");
    slider.slick({
        infinite: true,
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        dots: true
    });

    // toggle sidepanel
    var showBtn = $(".show-sidepanel");
    var closeBtn = $(".close-sidepanel");
    showBtn.click(function(e) {
        e.preventDefault();
        e.stopPropagation();
        let id = $(this).attr("id");
        console.log('check', id);
        $(".side-panel[data-panel-id=" + id + "]").addClass("show");
    })
    closeBtn.click(function() {
        $(".side-panel").removeClass("show");
    })
    $(".side-panel .inner-sidebar").click(function(e) {
        e.stopPropagation();
    })
    $(document).click(function() {
        $(".side-panel").removeClass("show");
    })

    // manage order quantity
    var incBtn = $(".order-block .order-menu-row .col-order .card-wrap .add-to-cart #inc");
    var decBtn = $(".order-block .order-menu-row .col-order .card-wrap .add-to-cart #dec");
    var qty = $(".order-block .order-menu-row .col-order .card-wrap .add-to-cart #qty");
    var cartBlock = $(".order-block .cart-block");
    var totalItemsEl = $(".order-block .cart-block h4 .totalItems");
    var totalPriceEl = $(".order-block .cart-block h4 .totalPrice");
    var totalQty = 0;
    var totalPrice = 0;

    incBtn.click(function() {
        let inititalQty = +$(this).parent().find("#qty").text();
        inititalQty += 1;
        totalQty += 1;
        totalPrice = +$(this).parents(".card-wrap").find(".text-wrap h6").text().replace("$ ", "") * totalQty;
        $(this).parent().find("#qty").text(inititalQty);
        totalItemsEl.text(totalQty);
        totalPriceEl.text(totalPrice);
        if (totalQty > 0) {
            cartBlock.addClass("show");
        } else {
            cartBlock.removeClass("show");
        }
    })
    decBtn.click(function() {
        let inititalQty = +$(this).parent().find("#qty").text();
        inititalQty -= 1;
        if (inititalQty < 0) {
            inititalQty = 0;
        }
        totalQty -= 1;
        $(this).parent().find("#qty").text(inititalQty);
        totalItemsEl.text(totalQty);
        if (totalQty > 0) {
            cartBlock.addClass("show");
        } else {
            cartBlock.removeClass("show");
        }
    })

    //order category menuBlock
    var menuBlock = $(".order-block .order-menu-row .col-menu .menu-block");
    var categoryTabs = menuBlock.find("ul li a");

    menuBlock.click(function() {
        if (window.matchMedia("(max-width: 767px)").matches) {
            $(this).find("ul").slideToggle();
        }
    });

    categoryTabs.click(function(e) {
        e.preventDefault();
        let tabId = $(this).attr("href");
        let categoryBlock = $(tabId);
        categoryTabs.removeClass("active");
        $(this).addClass("active");
        $("html, body").animate({
            scrollTop: categoryBlock.offset().top
        }, 1000);
    })

    // img upload
    var imgUploadInput = $(".dashboard .row-wrap .content-col .user-form .form .img-upload .user-img input");
    imgUploadInput.change(function(event) {
        var imageSrc = URL.createObjectURL(event.target.files[0]);
        $(this).next().attr("src", imageSrc);
    })

    // my orders tabs
    var tabs = $(".dashboard .row-wrap .content-col .tabs li");
    var activeTabId = $(".dashboard .row-wrap .content-col .tabs li.active").attr("id");
    $(".dashboard .row-wrap .content-col .tab-content[data-tab-id='" + activeTabId + "']").show()
    tabs.click(function() {
        let tabId = $(this).attr("id");
        tabs.removeClass("active");
        $(this).addClass("active");
        $(".dashboard .row-wrap .content-col .tab-content").hide();
        $(".dashboard .row-wrap .content-col .tab-content[data-tab-id='" + tabId + "']").fadeIn();
    })

    // toggle my account menu
    var toggleBtn = $(".dashboard .row-wrap .side-menu-col .user-info .toggle-menu");
    var menu = $(".dashboard .row-wrap .side-menu-col .menu");

    toggleBtn.click(function() {
        $(this).toggleClass("open");
        menu.slideToggle();
    });


    // OTP Timer
    var timerEl = $(".timer"),
        resendBtn = $(".resend_link"),
        minutes = 0,
        seconds = 30;

    // generate url
    var getUrl = window.location.href;
    var f_url = getUrl.replace('register', '').replace('login', '') + 'resendOTP';

    var timer = setInterval(function() {
        // clear Interval after two minutes
        if (minutes == 0 && seconds == 0) {
            clearInterval(timer);
            resendBtn.addClass("enabled");
            $('.resend_link').attr('href', f_url)
        }

        // countdown
        let duration;
        if (seconds.toString().length == 1) {
            duration = minutes + ":" + "0" + seconds;
        } else {
            duration = minutes + ":" + seconds;
        }
        if (seconds == 0) {
            seconds = 60
            minutes -= 1
        }
        seconds--
        timerEl.text(duration);
    }, 1000);

})(jQuery);


// jump cursor to next input
$('.otp_verification form input').keyup(function() {
    if (this.value.length == this.maxLength) {
        $(this).next('input').focus();
    }

    if (this.value.length == '') {
        $(this).prev('input').focus();
    }
})

// customize collpase link
var customizeLink = $(".collapsible a");

customizeLink.click(function() {
    $(this).toggleClass("show");
})
