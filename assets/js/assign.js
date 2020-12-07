$(document).ready(function () {
    var switches = $(".assign");

    switches.click(function () {



        var id = $(this).attr('data-id')
        var url = '/task/assign/' + id;

        alert(url)


        // AJAX Request
        $.ajax({
            context: this,
            url: url,
            type: 'POST',

            success: function (response) {
                console.log(response);
                if (response.success === 1) {

                    alert("Cette tâche vous a été assigné");
                    location.reload();

                } else {
                    alert('Problème technique.');
                }
            }
        });
    });
});