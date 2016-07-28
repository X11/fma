Controllers.SeriesController = new Creator.controller({
    elements: {
        ".series": "$overview",
        ".series .serie": "$series"
    },
    events: {
        "$overview": {
            mouseover: function(){
                this.$series.addClass('selected');
                this.$overview.addClass('darken');
            },
            mouseout: function(){
                this.$series.removeClass('selected');
                this.$overview.removeClass('darken');
            }
        },
    },
    init: function(){},
});
