(function() {
    "use strict";

    $(".nav-toggle").click(function(){
        $(this).parent().find('.nav-menu').toggleClass('is-active');
    });

}());
