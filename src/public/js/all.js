(function() {
    var timeout = 10;

    $(document).ready(function(){
        $('[data-counter]').each(function(){
            var $this = $(this);
            var countTo = $this.data('counter');
            var time = $this.data('counterTime');
            var cur = 0;
            var division = time / timeout;
            var add = Math.ceil(countTo / division);

            function up() {
                cur += add;
                if (cur < countTo)
                    $this.html(cur);
                else if(cur > countTo)
                    return $this.html(countTo);

                setTimeout(up, timeout);
            }
            setTimeout(up, timeout);
        });
    });
}());

$('a.is-danger').on('click', function(e) {
    return window.confirm("Are you sure?");
});

(function() {
    "use strict";

    $(".nav-toggle").click(function(){
        $(this).parent().find('.nav-menu').toggleClass('is-active');
    });

}());

(function() {

    "use strict";

    var $checkbox = $('#hide-more');
    var $posters = $('.more');
    var checked = localStorage.getItem('hide-more-value') == "true";
    $checkbox.prop('checked', checked);
    toggleMore(checked);

    $checkbox.on('change', function(e) {
        var checked = $checkbox.prop('checked');
        toggleMore(checked);
        localStorage.setItem('hide-more-value', checked.toString());
    });

    function toggleMore(state) {
        if (state)
            $posters.hide();
        else
            $posters.show();
    }

}());

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

(function() {

    "use strict";

    var $checkbox = $('#hide-watched');
    var $columns = $('.is-watched');
    var checked = localStorage.getItem('hide-watched-value') == "true";
    $checkbox.prop('checked', checked);
    toggleWatched(checked);

    $checkbox.on('change', function(e) {
        var checked = $checkbox.prop('checked');
        toggleWatched(checked);
        localStorage.setItem('hide-watched-value', checked.toString());
    });

    function toggleWatched(state) {
        if (state)
            $columns.hide();
        else
            $columns.show();
    }

}());

jQuery.fn.exists = function(){return jQuery(this).length>0;};

/**
 * jQuery Unveil
 * A very lightweight jQuery plugin to lazy load images
 * http://luis-almeida.github.com/unveil
 *
 * Licensed under the MIT license.
 * Copyright 2013 Luís Almeida
 * https://github.com/luis-almeida
 */

;(function($) {

  $.fn.unveil = function(threshold, callback) {

    var $w = $(window),
        th = threshold || 0,
        retina = window.devicePixelRatio > 1,
        attrib = retina? "data-src-retina" : "data-src",
        images = this,
        loaded;

    this.one("unveil", function() {
      var source = this.getAttribute(attrib);
      source = source || this.getAttribute("data-src");
      if (source) {
        this.setAttribute("src", source);
        if (typeof callback === "function") callback.call(this);
      }
    });

    function unveil() {
      var inview = images.filter(function() {
        var $e = $(this);
        if ($e.is(":hidden")) return;

        var wt = $w.scrollTop(),
            wb = wt + $w.height(),
            et = $e.offset().top,
            eb = et + $e.height();

        return eb >= wt - th && et <= wb + th;
      });

      loaded = inview.trigger("unveil");
      images = images.not(loaded);
    }

    $w.on("scroll.unveil resize.unveil lookup.unveil", unveil);

    unveil();

    return this;

  };

})(window.jQuery || window.Zepto);

(function() {
    var token = $('meta[name="csrf-token"]').attr('content');

    function setEpisodeMark(watched, id){
        $.ajax({
            url: "/episode/" + id + '/watched?_token=' + token,
            cache: false,
            method: watched ? 'POST' : 'DELETE',
            json: true,
            success: function(res){
                //alert(res.status);
                $.notify(res.status, "success");
            }
        });
    }

    $('.mark-episode').each(function(){
        var $button = $(this);
        var initial = $(this).data('watchedInitial');
        var content = $(this).data('watchedContent').split('|');
        var classes = $(this).data('watchedClass').split('|');
        var episode = $(this).data('watchedEpisode');
        var season = $(this).data('watchedSeason');

        var state = initial ? 1 : 0;
        $button.html(content[state]);
        $button.removeClass('is-loading');
        $button.addClass(classes[state]);

        $button.click(function(){
            $button.removeClass(classes[state]);
            state = state === 0 ? 1 : 0;
            setEpisodeMark(state, episode);
            $button.html(content[state]);
            $button.addClass(classes[state]);
            return false;
        });
    });

    $('.mark-season').each(function(){
        var $button = $(this);
        var season = $(this).data('watchedSeason');

        $button.click(function(){
            var i = 0;
            $('.mark-episode[data-watched-season="' + season + '"]').each(function(){
                var $but = $(this);
                setTimeout(function(){
                    $but.click();
                }, 100*i);
                i++;
            });
        });
    });
}());

(function() {
    var token = $('meta[name="csrf-token"]').attr('content');

    function setSerieMark(state, id){
        $.ajax({
            url: "/watchlist/" + id + '?_token=' + token,
            cache: false,
            method: state ? 'POST' : 'DELETE',
            json: true,
            success: function(res){
                //alert(res.status);
                $.notify(res.status, "success");
            }
        });
    }

    $('.mark-serie').each(function(){
        var $button = $(this);
        var initial = $(this).data('markInitial');
        var content = $(this).data('markContent').split('|');
        var classes = $(this).data('markClass').split('|');
        var serie = $(this).data('markSerie');

        var state = initial ? 1 : 0;
        $button.html(content[state]);
        $button.removeClass('is-loading');
        $button.addClass(classes[state]);

        $button.click(function(){
            $button.removeClass(classes[state]);
            state = state === 0 ? 1 : 0;
            setSerieMark(state, serie);
            $button.html(content[state]);
            $button.addClass(classes[state]);
            return false;
        });
    });
}());

(function() {

    "use strict";

    $("#selectAll").click(function(){
        var checked = $(this).prop('checked');
        $(this).parents('form').find('input[type="checkbox"]').each(function(){
            $(this).prop('checked', checked);
        });
    });
    
}());

(function() {
    
    "use strict";

    var $overview = $('.series');
    var $series = $('.serie');

    $series.hover(function(e){
        $(this).addClass('selected');
        $overview.addClass('darken');
    }, function(e) {
        $(this).removeClass('selected');
        $overview.removeClass('darken');
    });

}());

// Setup all listeners
$('.tabs a[tab-href]').on('click', function() {
    // Split the target to get the main & sub id
    var target = $(this).attr('tab-href');
    var parts = target.split('/');

    // Find the good tab
    $('#'+ parts[0] + '> *').each(function(){
        var c = $(this);
        c.removeClass('is-active');
        if (parts[1] == c.attr('tab-id')) c.addClass('is-active');
    });

    // Remove all active
    $(this).parents('ul').find('li').each(function(){
        $(this).removeClass('is-active');
    });

    // Set active to the good one
    $(this).parent('li').addClass('is-active');

    history.replaceState(parts, "", '#' + target);
});

window.addEventListener('load', function(e) {
    var hash = location.hash.slice(1);
    $('[tab-href="' + hash + '"]').click();
});

//# sourceMappingURL=all.js.map
