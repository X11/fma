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
