; (function ($) {
    "use strict";

    var SwpTimelineSlider = function ($scope) {

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


    };



    $(window).on("elementor/frontend/init", function () {

        elementorFrontend.hooks.addAction(
            "frontend/element_ready/swp-timeline-four.default",
            SwpTimelineSlider
        );

        elementorFrontend.hooks.addAction(
            "frontend/element_ready/swp-timeline-blog-three.default",
            SwpTimelineSlider
        );
    });


})(jQuery);
