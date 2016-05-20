(function() {

    "use strict";

    var token = $('meta[name="csrf-token"]').attr('content');

    function updateFilters(filters){
        $.ajax({
            url: "/account/setting/?_token=" + token,
            cache: false,
            method: 'POST',
            json: true,
            data: JSON.stringify({
                watchlist_filters: filters
            }),
            contentType: 'application/json; charset=utf-8',
            dataType: 'json',
            success: function(res){
                $.notify("Filters updated", "success");
                location.reload();
            }
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
}());
