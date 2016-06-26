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
