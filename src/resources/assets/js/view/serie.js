(function() {

    /**
     * Toggle tracking state from series
     *
     */
    $('.mark-serie').each(function(){
        var $button = $(this);
        var initial = $(this).data('markInitial');
        var content = $(this).data('markContent').split('|');
        var classes = $(this).data('markClass').split('|');
        var id = $(this).data('markSerie');

        var stateSwitch = $.switch(function(){
                                        $button.removeClass(classes[0]);
                                        $button.html(content[1]);
                                        $button.addClass(classes[1]);

                                        $.request('POST', '/serie/' + id + '/track', null, function(res){
                                            $.notify(res.status, "success");
                                        });
                                    }, function(){
                                        $button.removeClass(classes[1]);
                                        $button.html(content[0]);
                                        $button.addClass(classes[0]);

                                        $.request('DELETE', '/serie/' + id + '/track', null, function(res){
                                            $.notify(res.status, "success");
                                        });
                                    }, initial ? true : false);

        $button.removeClass('is-loading');
        var i = initial ? 1 : 0;
        $button.html(content[i]);
        $button.addClass(classes[i]);

        $button.click(function(){
            stateSwitch.set(!stateSwitch.get());
            return false;
        });
    });


    /**
     * Mark episode as watched
     *
     */
    $('.mark-episode').each(function(){

        var $button = $(this);
        var $container = $button;

        var initial = $(this).data('watchedInitial');
        var content = $(this).data('watchedContent');
        if (content) content = content.split('|');

        var classes = $(this).data('watchedClass').split('|');
        var episode = $(this).data('watchedEpisode');
        var season = $(this).data('watchedSeason');

        var parent = $(this).data('watchedParent');
        if (parent) $container = $button.closest(parent);

        var stateSwitch = $.switch(function(){
                                        $container.removeClass(classes[0]);
                                        if (content) $container.html(content[1]);
                                        $container.addClass(classes[1]);

                                        $.request('POST', '/episode/' + episode + '/watched', null, function(res){
                                            $.notify(res.status, "success");
                                        });
                                    }, function(){
                                        $container.removeClass(classes[1]);
                                        if (content) $container.html(content[0]);
                                        $container.addClass(classes[0]);

                                        $.request('DELETE', '/episode/' + episode + '/watched', null, function(res){
                                            $.notify(res.status, "success");
                                        });
                                    }, initial ? true : false);

        $button.removeClass('is-loading');
        var i = initial ? 1 : 0;
        if (content) $container.html(content[i]);
        $container.addClass(classes[i]);

        $button.click(function(){
            stateSwitch.set(!stateSwitch.get());
            return false;
        });
    });

    /**
     * Mark season as watched
     *
     */
    $('.mark-season').each(function(){
        var $button = $(this);
        var season = $(this).data('watchedSeason');

        $button.click(function(){
            var i = 0;
            $('.is-aired .mark-episode[data-watched-season="' + season + '"]').each(function(){
                var $but = $(this);
                setTimeout(function(){
                    $but.click();
                }, 100*i);
                i++;
            });
        });
    });

}());
