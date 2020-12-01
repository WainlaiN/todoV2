$(document).ready(function () {
    alert("test")
    $("#search").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $("#tasks li").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});