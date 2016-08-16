Controllers.SourcesController = new Creator.controller({
    elements: {
        "#linkList": "$linkList",
        "#linkTemplate": "$linkTemplate",
        "#linkItemTemplate": "$linkItemTemplate",
        "#magnetList": "$magnetList",
        "#magnetTemplate": "$magnetTemplate",
        ".sources-loading": "$sourcesLoading",
    },

    events: {

    },

    init: function() {
        $.request('GET',
            '/episode/' + window.episodeId + '/sources' + window.location.search,
            null,
            function(data) {
                this.$sourcesLoading.hide();
                this.renderMagnets(data.magnets);
                this.renderLinks(data.links);
            }.bind(this),
            function(data) {
                console.log("Something went wrong");
                $.notify("Something went wrong loading the sources", "error");
            }.bind(this)
        );
    },

    renderMagnets: function(magnets) {
        if (magnets.length > 0) {
            var template = this.$magnetTemplate.html();
            magnets.map(function(magnet) {
                magnet.seeds = magnet.seeds > 9999 ? (Math.round(magnet.seeds / 1000)) + 'K+' : magnet.seeds;
                return magnet;
            }).forEach(function(magnet) {
                var item = template;
                for (var key in magnet) {
                    item = item.replace((new RegExp('__' + key + '__', 'g')), magnet[key]);
                }
                this.$magnetList.append(item);
            }.bind(this));
        } else {
            this.$magnetList.html('No magnets found');
        }
    },

    renderLinks: function(links) {
        if (Object.keys(links).length > 0) {
            var template = this.$linkTemplate.html();
            var itemtemplate = this.$linkItemTemplate.html();
            for (var key in links) {
                (function(links, key){ // jshint ignore:line
                    var item = template;
                    item = item.replace((new RegExp('__key__', 'g')), key);
                    var items = "";
                    links.forEach(function(link){
                        var item = itemtemplate;
                        for (var key in link) {
                            item = item.replace((new RegExp('__' + key + '__', 'g')), link[key]);
                        }
                        items += item;
                    });
                    item = item.replace((new RegExp('__list__', 'g')), items);
                    this.$linkList.append(item);
                }.bind(this))(links[key], key);
            }
        } else {
            this.$linkList.html('No Links found');
        }
    }
});
