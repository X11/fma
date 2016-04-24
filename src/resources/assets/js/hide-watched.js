(function() {

    "use strict";

    var $checkbox = $('#hide-watched');
    var $columns = $('.is-watched');
    var checked = localStorage.getItem('hide-watched-value') == "true";
    $checkbox.prop('checked', checked);
    toggleWatched(checked);

    $checkbox.on('change', function(e) {
        var checked = $checkbox.prop('checked');
        toggleWatched(checked);
        localStorage.setItem('hide-watched-value', checked.toString());
    });

    function toggleWatched(state) {
        if (state)
            $columns.hide();
        else
            $columns.show();
    }

}());
