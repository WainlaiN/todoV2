$(document).ready(function() {
    $("#add_resume1").on("click", function(e) {
        e.preventDefault;
        //your ajax here
        $("#success").html('<div class="alert alert success">Basic details saved successfully.<div>');
        $('#basicdetails').val('');
        $('.btn.btn-home.save-button')
            .removeClass('disabled')
            .prop('disabled', false);
        $('#add_resume1').prop('disabled', true);
    });
});