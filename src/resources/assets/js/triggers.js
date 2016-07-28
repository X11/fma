(function() {
    
    "use strict";

    Creator.trigger({
        id: 'initHeader',
        on: function(){
            new Controllers.HeaderController();
        }
    });

    Creator.trigger({
        id: 'initSerie',
        on: function(){
            new Controllers.SerieController();
        }
    });

    Creator.trigger({
        id: 'initEpisode',
        on: function(){

        }
    });

    Creator.trigger({
        id: 'initSeries',
        on: function(){
            new Controllers.SeriesController();
        }
    });

    Creator.trigger({
        id: 'initWatchlist',
        on: function(){
            new Controllers.WatchlistController();
        }
    });
}());
