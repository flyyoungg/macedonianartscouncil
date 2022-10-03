; (function ($) {
    "use strict";

    $(document).ready(function () {


        /* -----------------------------------------------------
           Variables
       ----------------------------------------------------- */
        var leftArrow = '<i class="far fa-arrow-alt-circle-left"></i>';
        var rightArrow = '<i class="far fa-arrow-alt-circle-right"></i>';

        /*------------------------------------------------
            sponsor-slider
        ------------------------------------------------*/
        $('.swp-timeline-slider-1').owlCarousel({
            loop: true,
            margin: 30,
            nav: true,
            dots: true,
            smartSpeed: 1500,
            navText: [leftArrow, rightArrow],
            responsive: {
                0: {
                    items: 1,
                },
                576: {
                    items: 1,
                },
                768: {
                    items: 2,
                },
                992: {
                    items: 3,
                },
                1200: {
                    items: 3,
                },
            }
        });

    });
})(jQuery);