$(document).ready(function () {
    $(document).on('click', '.btn.btn-primary.a-btn-slide-text.mailButton', function () {
        //searches the row which the button is in.
        var $closeRow = $(this).closest("tr");
        //get the username of the row
        var $username = $closeRow.find(".username").text();
        $.ajax({
            type: "POST",
            url: "ajaxController.php",
            data: { "action" : "sendMail", "username" : $username },
            dataType: "text",
        }).done(function( data ) {
                alert(data);
                console.log(data);
        });
    });
});
