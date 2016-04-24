(function() {
    "use strict";

    $(".header-toggle").click(function(){
        $(this).parent().find('.header-right').toggleClass('is-active');
    });

}());
