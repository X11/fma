(function() {

    /**
     * Add an extra confirmation box on danger buttons
     *
     */
    $('a.is-danger, button.is-danger').on('click', function(e) {
        return window.confirm("Are you sure?");
    });
}());
