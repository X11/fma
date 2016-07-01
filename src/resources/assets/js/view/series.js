(function() {
    
    "use strict";

    if (window.VIEW != "series") return;

    var $overview = $('.series');
    var $series = $('.series .serie');

    $series.hover(function(e){
        $series.addClass('selected');
        $overview.addClass('darken');
    }, function(e) {
        $series.removeClass('selected');
        $overview.removeClass('darken');
    });

}());
