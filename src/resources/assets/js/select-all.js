(function() {

    "use strict";

    $("#selectAll").click(function(){
        var checked = $(this).prop('checked');
        $(this).parents('form').find('input[type="checkbox"]').each(function(){
            $(this).prop('checked', checked);
        });
    });
    
}());
