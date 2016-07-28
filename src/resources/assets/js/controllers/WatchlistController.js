Controllers.WatchlistController = new Creator.controller({
    elements: {
        "#watchlist_filter_search_style": "$style",
        "#watchlist_filter_search": "$search",
        "[watchlist-reset-filters]": "$resetFilters",
        "[watchlist-filter]": "$filters"
    },

    events: {
        "$search": {
            keyup: function(){
                if (this.$search.val() === ''){
                    this.$style.html('');
                    localStorage.setItem('watchlist_filter_search', '');
                } else {
                    this.$style.html('[watchlist-serie]{display:none;}[watchlist-serie*="' + this.$search.val() + '"]{display:block;}');
                    localStorage.setItem('watchlist_filter_search', this.$search.val());
                }
            }
        },
        "$resetFilters": {
            click: function(){
                this.$filters.each(function(){
                    $(this).prop('checked', 'true');
                });
                this.updateFilters([]);
            }
        },
        "$filters": {
            change: function(){
                var filters = [];
                this.$filters.each(function(){
                    var $checkbox = $(this);
                    var checked = $checkbox.prop('checked');
                    if (!checked){
                        filters.push(parseInt($checkbox.attr('watchlist-filter')));
                    }
                });

                this.updateFilters(filters);
            }
        }
    },

    init: function(){
        var value = localStorage.getItem('watchlist_filter_search') || '';
        if (value !== '') this.$style.html('[watchlist-serie]{display:none;}[watchlist-serie*="' + value + '"]{display:block;}');
        this.$search.val(value);
    },

    updateFilters: function(filters){
        $.request(  "POST", 
                    '/account/settings', 
                    JSON.stringify({
                        watchlist_filters: filters
                    }), function(res){
                        location.reload();
                    }, function(res){
                        $.notify("Something went wrong", "error");
                    });
    }
});
