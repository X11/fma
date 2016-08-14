;(function($) {

    "use strict";

    $.fn.check = function(key, trueState, falseState){

        var $checkbox = $(this);
        var checked = localStorage.getItem(key) == "true";

        var checkSwitch = $.switch(trueState, falseState);
        checkSwitch.set(checked);
        $checkbox.attr('checked', checked);

        $checkbox.on('change', function(e) {
            var checked = $checkbox.prop('checked');
            localStorage.setItem(key, checked.toString());
            checkSwitch.set(checked);
        });

        return this;
    };

})(window.jQuery || window.Zepto);

jQuery.fn.exists = function(){return jQuery(this).length>0;};

;(function($) {

    "use strict";

    var token = $('meta[name="csrf-token"]').attr('content');

    $.request = function(method, url, data, success, error) {
        return $.ajax({
                    url: url + (url.search('&') === -1 ? '?' : '&') + '_token=' + token,
                    cache: false,
                    method: method,
                    contentType: 'application/json; charset=utf-8',
                    dataType: 'json',
                    json: true,
                    data: data || '',
                    success: success,
                    error: error
                });
    };

})(window.jQuery || window.Zepto);

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
                if (source.slice(0, 2) == "//" && this.nodeName == "IMG"){
                    var img = new Image();
                    img.src = "https:" + source;
                    img.onload = function(){
                        this.setAttribute('src', "https:" + source);
                    }.bind(this);
                    img.onerror = function(){
                        this.setAttribute('src', "http:" + source);
                    }.bind(this);
                } else {
                    this.setAttribute("src", source);
                }
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

var Creator = {};

/**
 * Creates a new controller given an data set of elements, events and functions
 *
 * Example controller
 * {
 *      elements: {
 *          "#button": "$button",
 *          ".buttons": "$buttons"
 *      },
 *      events: {
 *          "$button": {
 *              click: function(){},
 *          }
 *      },
 *      init: function(){},
 *      customSuff: function(){}
 * }
 *
 * @param Object
 *
 * @return Object
 */
Creator.controller = function(options){

    // Initialize the controller
    var Controller = function(){
        this.options = options;

        if(this.elements) this._refreshElements();

        this.init.apply(this, arguments);
    };

    // Shorter name for working on the prototype;
    Controller.fn = Controller.prototype;
    Controller.fn.elements = {};

    // Default init function does nothing, Can be overwriten by giving an 'init' property with the options;
    Controller.fn.init = function(){};

    // Shorten the search area for selectors;
    Controller.fn.$ = function(selector){
        return $(selector, this.el);
    };

    // Populates the elements from the options
    Controller.fn._refreshElements = function(){
        for (var key in this.elements){
            // Bind the element to the controller
            this[this.elements[key]] = this.$(key);

            // Do we have events for this element?
            if (this.events && this.events[this.elements[key]]){

                // Add the events for the element
                for (var event in this.events[this.elements[key]]){
                    this[this.elements[key]].on(event, this.events[this.elements[key]][event].bind(this));
                }
            }
        }
    };

    // Include all of the options into the new controller
    if (options){
        for (var key in options){
            Controller.fn[key] = options[key];
        }
    }

    return Controller;
};

/**
 * Creates an trigger when a specific elements gets loaded into the DOM
 *
 * @param Object
 *
 * @return Object
 */
Creator.trigger = function(options){

    var Trigger = function(){
        this.options = options;

        Triggers.register(this.options.id, this.options.on.bind(this));

        this.init.apply(this, arguments);
    };

    // Shorter name for working on the prototype;
    Trigger.fn = Trigger.prototype;

    // Default init function does nothing, Can be overwriten by giving an 'init' property with the options;
    Trigger.fn.init = function(){};

    // Default do nothing
    Trigger.fn.on = function(){};

    Trigger.fn.done = false;

    if (options){
        for (var key in options){
            Trigger.fn[key] = options[key];
        }
    }

    return (new Trigger(options));
};


/**
 * Holds all the triggers
 *
 */
var Triggers = {

    /**
     * Holds all the triggers
     *
     * @var Object
     */
    triggers: {},

    /**
     * Register an trigger with a callback
     *
     * @param string
     * @param Function
     */
    register: function(id, cb){
        this.triggers[id] = cb;  
    },

    /**
     * Remove an trigger
     *
     * @param string
     */
    unregister: function(id){
        delete this.triggers[id];
    },

    /**
     * Executes a trigger
     *
     * @param string
     */
    execute: function(id){
        if (this.triggers[id]){
            this.triggers[id]();
        }
    },

    /**
     * Shortname for execute
     *
     * @param string
     */
    exec: function(id){
        this.execute(id);
    },

    /**
     * Listens for events when to check for triggers
     */
    listen: function(){
        window.addEventListener('load', this.checkTriggers.bind(this));
    },

    /**
     * Handle the triggers
     */
    checkTriggers: function(){
        var self = this;
        $('[triggers]').each(function(){
            var id = $(this).attr('triggers');
            self.execute(id);
            self.unregister(id);
        });
    }
};

// Bootstrap
Triggers.listen();

var Controllers = {};

Controllers.HeaderController = Creator.controller({
    elements: {
        '#header': 'el',
        '.nav-toggle': '$navToggle',
        '.nav-menu': '$navMenu',
    },
    events: {
        '$navToggle': {
            click: function(){
                this.$navMenu.toggleClass('is-active');
            }
        }
    },
    init: function(){
        this.oldPos = 0;
        $(window).on('scroll', this.onScroll.bind(this));
    },
    onScroll: function(e){
        if (this.oldPos < e.originalEvent.pageY && e.originalEvent.pageY > 100){
            this.el.addClass('hide');
        } else {
            this.el.removeClass('hide');
        }
        this.oldPos = e.originalEvent.pageY;
    }
});

Controllers.SerieController = new Creator.controller({
    elements: {
        ".serie-fanart img": "$fanartImage",
        ".serie-poster img": "$posterImage",
        ".videos .overlay": "$videoOverlays",
    },

    events: {
        "$fanartImage": {
            dblclick: function(){
                this.imageModal(this.$fanartImage);
            }
        },
        "$posterImage": {
            dblclick: function(){
                this.imageModal(this.$posterImage);
            }
        },
        "$videoOverlays": {
            click: function(e){
                var $modal = $('#video-modal');
                $modal.addClass('is-active');

                var $frame = $modal.find('iframe');
                $frame.attr('src', $(e.currentTarget).attr('iframe-src'));

                $modal.find('.modal-close').one('click', function(){
                    $modal.removeClass('is-active');
                    $frame.attr('src', '');
                });
            }
        }
    },

    init: function(){

    },

    imageModal: function(clickSource){
        var $modal = $('#image-modal');
        $modal.addClass('is-active');

        var $img = $modal.find('img');
        $img.attr('src', clickSource.attr('src'));

        $modal.find('.modal-close').one('click', function(){
            $modal.removeClass('is-active');
        });

        $modal.find('.modal-prev').on('click', prev); function prev() { setNumber(-1); }
        $modal.find('.modal-next').on('click', next); function next() { setNumber(1); }

        var $testImg = new Image();
        $testImg.onerror = function(){
            $img.attr('src', $testImg.src);
            $img.show();
        };
        $testImg.onload = function(){
            $img.attr('src', $testImg.src);
            $img.show();
        };

        function setNumber(add) {
            var src = $img.attr('src');
            var matches = /([\d]+)\.jpg$/.exec(src);
            if (matches){
                if (matches[1] == '1' && add == -1) return;
                $testImg.src = src.replace('-' + matches[1] + '.jpg', '-' + (add + parseInt(matches[1])) + '.jpg');
                $img.hide();
            }
        }
    },
});

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

(function() {

    "use strict";

    /**
     * Homepage
     *
     */
    $('#hide-watched').check('hide-watched-value', function(){
        $('.is-watched').hide();
    }, function(){
        $('.is-watched').show();
    });

    /**
     * Calendar
     *
     */
    $("#show-important").check('calendar-show-special', function(){
        $('.calendar-item:not(.is-watching):not(.is-premier):not(.is-returning)').addClass('is-hidden');
    }, function(){
        $('.calendar-item').removeClass('is-hidden');
    });

}());

(function() {

    /**
     * Add an extra confirmation box on danger buttons
     *
     */
    $('a.is-danger, button.is-danger').on('click', function(e) {
        return window.confirm("Are you sure?");
    });
}());

(function() {

    "use strict";

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

(function() {
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
}());

(function() {

    var IMAGE_URL = "//thetvdb.com/banners/";
    var IMAGE_CACHE_URL = "//thetvdb.com/banners/_cache/";
    var check;
    switch(window.tvdb_load_hd){
        case 'not_on_mobile':
            check = function(){
                return !(/Mobi/i.test(navigator.userAgent));
            };
            break;
        case 'size':
            check = function(element){
                return element.width() >= 300;
            };
            break;
        case 'always':
            check = function(){return true;};
            break;
        default:
            check = function(){return false;};
    }

    $('img[data-src^="' + IMAGE_CACHE_URL + '"]').on('load', function(){
        var elm = $(this);
        var src = elm.attr('src');

        src = src.slice(0, 5) === "https" ? src.slice(6) : src.slice(5);
        if (src.slice(0, IMAGE_CACHE_URL.length) == IMAGE_CACHE_URL){
            if (check(elm)){
                var after = src.slice(IMAGE_CACHE_URL.length);

                var img = new Image();
                img.src = "https:" + IMAGE_URL + after;
                img.onload = function(){
                    elm[0].src = this.getAttribute('src');
                    //console.log("Loaded HD", this.getAttribute('src'));
                };
                img.onerror = function(){
                    img.src = "http:" + IMAGE_URL + after;
                };
            }
        }
    });
}());

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

//# sourceMappingURL=all.js.map
