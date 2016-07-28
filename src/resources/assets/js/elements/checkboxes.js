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
     * Calender
     *
     */
    $("#show-important").check('calender-show-special', function(){
        $('.calender-item:not(.is-watching,is-premier,is-returning)').addClass('is-hidden');
    }, function(){
        $('.calender-item').removeClass('is-hidden');
    });

}());
