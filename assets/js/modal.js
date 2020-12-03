$(document).ready(function(e) {
    // Initializing our modal.
    $('#myModal').modal({
        backdrop: 'static',
        keyboard: false,
        show: false,
    });

    $(document).on("click", ".modalButton", function() {

        var id = $(this).attr('data-id')
        var url = 'task/' + id;


                $('#myModal').modal('show');


        $(".modal-body").html("<p>" + ClickedButton + "</p> <p>Some text in the modal.</p> ");
        $('#myModal').modal('show');
    });

});