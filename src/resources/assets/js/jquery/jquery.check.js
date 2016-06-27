;(function($) {

    "use strict";

    $.fn.check = function(key, trueState, falseState){

        var $checkbox = this;
        var checked = localStorage.getItem(key) == "true";

        var checkSwitch = $.switch(trueState, falseState);
        checkSwitch.set(checked);
        $checkbox.attr('checked', checked);

        $checkbox.on('change', function(e) {
            var checked = $checkbox.prop('checked');
            localStorage.setItem('hide-watched-value', checked.toString());
            checkSwitch.set(checked);
        });


        return this;
    };

})(window.jQuery || window.Zepto);
