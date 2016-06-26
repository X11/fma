(function() {
    "use strict";

    /**
     * Handle navbar click on mobile
     *
     */
    $(".nav-toggle").click(function(){
        $(this).parent().find('.nav-menu').toggleClass('is-active');
    });

}());
