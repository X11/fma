(function() {

    "use strict";

    /**
     * Homepage
     *
     */
    $('#hide-watched').check('hide-watched-value', function(){
        $('.is-watched').hide();
    }, function(){
        $('.is-watched').show();
    });

    /**
     * Calendar
     *
     */
    $("#show-important").check('calendar-show-special', function(){
        $('.calendar-item:not(.is-watching):not(.is-premier):not(.is-returning)').addClass('is-hidden');
    }, function(){
        $('.calendar-item').removeClass('is-hidden');
    });

}());
