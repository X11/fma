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
