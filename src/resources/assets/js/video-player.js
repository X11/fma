(function() {

    var INTERVAL = 10000;
    var status = {
        'Done': 100,
        'Transfering': 75,
        'Downloading': 50,
        "Initializing": 25,
    };

    $('.file-download').each(function(){
        var fileId = $(this).data('fileid');
        var $status = $(this).find('.status');
        var $progress = $(this).find('.progress');

        $progress.attr('value', status[$status.text()]);

        if($status.text() == "Done")
            return;

        setTimeout(getFile, 5000);

        function getFile(){
            $.ajax({
                url: "/episodefile/" + fileId,
                cache: false,
                success: function(data){
                    $status.html(data.status);
                    $progress.attr('value', status[data.status]);
                    if (data.file)
                        location.reload();
                    else
                        setTimeout(getFile, INTERVAL);
                }
            });
        }
    });
}());
