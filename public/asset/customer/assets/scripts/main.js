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
});

$('.payment_options label').click(function() {
    setTimeout(function() {
        if ($('#bank_transfer').prev('input').is(':checked')) {
            $('#bank_transfer').next('.bank_content').slideDown();

        } else {
            $('#bank_transfer').next('.bank_content').slideUp();
        }
    }, 100)
})

$('.payment_options label').click(function(e) {
    e.stopPropagation();
    $(this).next(".content").slideToggle();
})

 // sticky menu sidebar
 var sticky = $(".order-block .order-menu-row .sticky");

 $(window).on("scroll", function(e){
    sticky.each(function(e){
         let scrolled = $(window).scrollTop();
         let startPos = $(this).parents(".order-menu-row").offset().top - $(".header").height() - 20;
         let endPos = $(this).parents(".order-menu-row").offset().top + $(this).parents(".order-menu-row").height() - $(this).height() - 90;
         if(scrolled > startPos && scrolled < endPos) {
            if(window.matchMedia("(min-width: 576px)").matches) {
                $(this).css({
                    position: "fixed",
                    top: 120,
                    bottom: "auto",
                    width: $(this).parent().width()
                })
            }else {
                if($(this).hasClass("menu-block")) {
                    $(this).css({
                        position: "fixed",
                        top: 0,
                        bottom: "auto",
                        width: $(this).parent().width()
                    })
                }else {
                    $(this).css({
                        position: "fixed",
                        top: 55,
                        bottom: "auto",
                        width: $(this).parent().width()
                    })
                }
            }
        } 
        else if(scrolled > endPos) {
            $(this).css({
                position: "absolute",
                bottom: 0,
                top: "auto"
            })
        } 
        else {
            $(this).css({
                position: "relative",
                bottom: 0,
                top: 0
            })
        }
     })
 });

// product size dropdown
$(".order-block .order-menu-row .card-wrap .opt-dropdown .selected").click(function(){
    $(this).parent().toggleClass("open");
    $(this).next(".menu").slideToggle();
})

$(".order-block .order-menu-row .card-wrap .opt-dropdown .size").click(function(){
    let sizePrice = $(this).find(".price").text();
    $(this).parents(".card-wrap").find(".text-wrap h6.price").text(sizePrice);
})

// about tab collapsible
$(".order-block .restaurant-info .collapse-tab").click(function(){
    $(this).next().slideToggle();
})

$(function() {
    var creditly = Creditly.initialize(
        '.creditly-wrapper .expiration-month-and-year',
        '.creditly-wrapper .credit-card-number',
        '.creditly-wrapper .security-code',
        '.creditly-wrapper .card-type');

    $(".payment_options .paynow_btn").click(function(e){
        if($(".payment_options #atlantic").is(":checked")) {
            let output = creditly.validate();
            if(!output) {
                e.preventDefault();
            }
        }
    })    
});
