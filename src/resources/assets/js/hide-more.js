(function() {

    "use strict";

    var $checkbox = $('#hide-more');
    var $posters = $('.more');
    var checked = localStorage.getItem('hide-more-value') == "true";
    $checkbox.prop('checked', checked);
    toggleMore(checked);

    $checkbox.on('change', function(e) {
        var checked = $checkbox.prop('checked');
        toggleMore(checked);
        localStorage.setItem('hide-more-value', checked.toString());
    });

    function toggleMore(state) {
        if (state)
            $posters.hide();
        else
            $posters.show();
    }

}());
