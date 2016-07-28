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
