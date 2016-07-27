(function() {
    "use strict";

    /**
     * Handle navbar click on mobile
     *
     */
    $(".nav-toggle").click(function(){
        $(this).parent().find('.nav-menu').toggleClass('is-active');
    });

    var oldPos = 0;
    var $header = $('.header');
    $(window).scroll(function(e){
        if (oldPos < e.originalEvent.pageY && e.originalEvent.pageY > 100){
            $header.addClass('hide');
        } else {
            $header.removeClass('hide');
        }
        oldPos = e.originalEvent.pageY;
    });
}());
