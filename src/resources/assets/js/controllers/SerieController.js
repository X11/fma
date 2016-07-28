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
                $testImg.src = src.replace('-' + matches[1] + '.jpg', '-' + (add + parseInt(matches[1])) + '.jpg');
                $img.hide();
            }
        }
    },
});
