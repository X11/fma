;(function($) {

    "use strict";

    var token = $('meta[name="csrf-token"]').attr('content');

    $.switch = function(trueState, falseState, state) {
        return {
            state: state || false,
            get: function(state){
                return this.state;
            },
            set: function(state){
                this.state = state;
                if (state){
                    this.trueState();
                } else {
                    this.falseState();
                }
            },
            trueState: trueState || function(){},
            falseState: falseState || function(){}
        };

    };

})(window.jQuery || window.Zepto);
