(function() {
    
    "use strict";

    var checkbox = $('#hide-posters');
    var posters = $('.serie-poster');
    var checked = localStorage.getItem('hide-posters-value') == "true";
    checkbox.prop('checked', checked);
    togglePosters(checked);

    checkbox.on('change', function(e) {
        checked = checkbox.prop('checked');
        togglePosters(checked);
        localStorage.setItem('hide-posters-value', checked.toString());
    });

    function togglePosters(state) {
        if (state)
            posters.hide();
        else
            posters.show();
    }

}());
