(function() {
    
    "use strict";

    var $overview = $('.series');
    var $series = $('.serie');

    $series.hover(function(e){
        $(this).addClass('selected');
        $overview.addClass('darken');
    }, function(e) {
        $(this).removeClass('selected');
        $overview.removeClass('darken');
    });

}());
