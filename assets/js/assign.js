$(document).ready(function () {
    var switches = $(".assign");

    switches.click(function () {

        var id = $(this).attr('data-id')
        var url = '/task/assign/' + id;

        // AJAX Request
        $.ajax({
            context: this,
            url: url,
            type: 'POST',

            success: function (response) {
                console.log(response);
                if (response.success === 1) {

                    location.reload();

                } else {
                    alert('Problème technique.');
                }
            }
        });
    });
});