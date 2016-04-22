(function ($) {
    "use strict";
    $('.fwdd-testimonials').flexslider({
        //declare the slider items
        selector: ".testimonials > li",
        animation: "slide",
        //do not add navigation for paging control of each slide
        controlNav: false,
        slideshow: true,
        //Allow height of the slider to animate smoothly in horizontal mode
        //smoothHeight: true,
        start: function () {
            $('.testimonials').children('li').css({
                'opacity': 1,
                'position': 'relative'
            });
        }
    });
})(jQuery);

