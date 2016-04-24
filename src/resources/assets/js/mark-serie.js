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
