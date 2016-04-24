(function() {
    var timeout = 10;

    $(document).ready(function(){
        $('[data-counter]').each(function(){
            var $this = $(this);
            var countTo = $this.data('counter');
            var time = $this.data('counterTime');
            var cur = 0;
            var division = time / timeout;
            var add = Math.ceil(countTo / division);

            function up() {
                cur += add;
                if (cur < countTo)
                    $this.html(cur);
                else if(cur > countTo)
                    return $this.html(countTo);

                setTimeout(up, timeout);
            }
            setTimeout(up, timeout);
        });
    });
}());
