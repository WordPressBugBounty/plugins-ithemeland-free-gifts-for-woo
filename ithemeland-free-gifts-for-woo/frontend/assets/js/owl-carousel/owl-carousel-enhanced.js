jQuery(function ($) {
    'use strict';
    try {
        $(document.body).on('it-enhanced-carousel', function () {
            var owl_carousels = $('.it-owl-carousel-items');
            if (!owl_carousels.length) {
                return;
            }

            owl_carousels.each(function (e) {
                $(this).owlCarousel({
                    margin: 10,
                    responsiveClass: true,
                    autoplayHoverPause: true,
                    autoplayTimeout: parseInt(it_gift_carousel_ajax.speed),
                    loop: ('true' == it_gift_carousel_ajax.loop),
                    dots: ('true' == it_gift_carousel_ajax.dots),
                    nav: ('true' == it_gift_carousel_ajax.nav),
                    responsive: {
                        0: {
                            items: parseInt(it_gift_carousel_ajax.mobile),
                        },
                        600: {
                            items: parseInt(it_gift_carousel_ajax.tablet),
                        },
                        1000: {
                            items: parseInt(it_gift_carousel_ajax.desktop),
                        }
                    }
                });
            });
        });

        $(document.body).on('updated_wc_div', function () {
            $(document.body).trigger('it-enhanced-carousel');
        });

        $(document.body).trigger('it-enhanced-carousel');
    } catch (err) {
        window.console.log(err);
    }

});
