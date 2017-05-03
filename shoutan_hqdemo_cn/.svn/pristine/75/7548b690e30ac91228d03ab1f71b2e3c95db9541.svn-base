!function($) {
    $.extend( $.easing, {
        easeOutCubic: function (x, t, b, c, d) {
            return c*((t=t/d-1)*t*t + 1) + b;
        },
        easeInExpo: function (x, t, b, c, d) {
            return (t==0) ? b : c * Math.pow(2, 10 * (t/d - 1)) + b;
        },
        easeOutExpo: function (x, t, b, c, d) {
            return (t==d) ? b+c : c * (-Math.pow(2, -10 * t/d) + 1) + b;
        }
    });




    $(document).ready(function(){


        $(".swiper-container").swiper({
            loop: true,
            autoplay: 3000
        });


    });



    $(window).load(function(){


    });

}(window.jQuery);



