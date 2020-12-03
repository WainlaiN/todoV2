$(document).ready(function(e) {

    $(document).on("click", ".modalButton", function() {

        var id = $(this).attr('data-id')
        $('#myModal' + id).modal('show');

    });

});