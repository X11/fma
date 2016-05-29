(function() {

    var IMAGE_URL = "http://thetvdb.com/banners/";
    var IMAGE_CACHE_URL = "http://thetvdb.com/banners/_cache/";
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
        if (src.slice(0, IMAGE_CACHE_URL.length) == IMAGE_CACHE_URL){
            if (check(elm)){
                var newSrc = IMAGE_URL + src.slice(IMAGE_CACHE_URL.length);
                var img = new Image();
                img.src = newSrc;
                img.onload = function(){
                    elm[0].src = newSrc;
                    console.log("Loaded HD", newSrc);
                };
            }
        }
    });
}());
