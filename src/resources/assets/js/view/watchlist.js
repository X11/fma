(function() {

    "use strict";

    /**
     * Update filters
     *
     * @param Array filters
     */
    function updateFilters(filters){
        $.request("POST", '/account/settings', JSON.stringify({
                                                                watchlist_filters: filters
                                                            }), function(res){
                                                                location.reload();
                                                            }, function(res){
                                                                $.notify("Something went wrong", "error");
                                                            });
    }

    /**
     * Handle on filter click
     *
     */
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

    /**
     * Remove all filters
     *
     */
    $('[watchlist-reset-filters]').click(function(){
        $('[watchlist-filter]').each(function(){
            $(this).prop('checked', 'true');
        });
        updateFilters([]);
    });

    /*
    $("#selectAll").click(function(){
        var checked = $(this).prop('checked');
        $(this).parents('form').find('input[type="checkbox"]').each(function(){
            $(this).prop('checked', checked);
        });
    });
    */

    /**
     * Find filters by name
     *
     */
    var $style = $('#watchlist_filter_search_style');
    var value = localStorage.getItem('watchlist_filter_search') || '';
    if (value !== '') $style.html('[watchlist-serie]{display:none;}[watchlist-serie*="' + value + '"]{display:block;}');

    $('#watchlist_filter_search').on('keyup', function(){
        if (this.value === ''){
            $style.html('');
            localStorage.setItem('watchlist_filter_search', '');
        } else {
            $style.html('[watchlist-serie]{display:none;}[watchlist-serie*="' + this.value + '"]{display:block;}');
            localStorage.setItem('watchlist_filter_search', this.value);
        }
    });

}());
