$(document).ready(function () {
    var switches = $(":checkbox");

    switches.click(function () {

        var id = $(this).attr('data-id')
        var url = 'switch/' + id;

        // AJAX Request
        $.ajax({
            context: this,
            url: url,
            type: 'POST',

            success: function (response) {
                console.log(response);
                if (response.success === 1) {

                    alert("Changement de statut utilisateur validé");

                } else {
                    alert('Problème technique.');
                }
            }
        });
    });
});