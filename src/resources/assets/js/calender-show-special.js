(function() {

    "use strict";

    var $checkbox = $('#show-important');
    var checked = localStorage.getItem('calender-show-special') == "true";
    $checkbox.prop('checked', checked);
    toggleWatched(checked);

    $checkbox.on('change', function(e) {
        var checked = $checkbox.prop('checked');
        toggleWatched(checked);
        localStorage.setItem('calender-show-special', checked.toString());
    });

    function toggleWatched(state) {
        if (state)
            $('.calender-item:not(.is-watching,is-premier,is-returning)').addClass('is-hidden');
        else
            $('.calender-item').removeClass('is-hidden');
    }

}());
