(function() {

    "use strict";

    var token = $('meta[name="csrf-token"]').attr('content');

    function updateFilters(filters){
        $.request("POST", '/account/settings', JSON.stringify({
                                                                watchlist_filters: filters
                                                            }), function(res){
                                                                location.reload();
                                                            }, function(res){
                                                                $.notify("Something went wrong", "error");
                                                            });
    }

    $('[watchlist-filter]').on('change', function(){
        var filters = [];
        $('[watchlist-filter]').each(function(){
            var $checkbox = $(this);
            var checked = $checkbox.prop('checked');
            if (!checked){
                filters.push(parseInt($checkbox.attr('watchlist-filter')));
            }
        });

        updateFilters(filters);
    });

    $('[watchlist-reset-filters]').click(function(){
        $('[watchlist-filter]').each(function(){
            $(this).prop('checked', 'true');
        });
        updateFilters([]);
    });

    $("#selectAll").click(function(){
        var checked = $(this).prop('checked');
        $(this).parents('form').find('input[type="checkbox"]').each(function(){
            $(this).prop('checked', checked);
        });
    });
}());
