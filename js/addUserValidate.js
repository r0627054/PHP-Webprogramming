/**
 * validation on add user form
 * Made use of JQuery validation
 * written own methods
 */
$(document).ready(function () {

    $("#adduserform").validate({
        rules: {
            firstname: {
                required: true,
                lettersAndSpaces: true
            },
            surname:{
                required: true,
                lettersAndSpaces: true
            },
            email: {
                required: true,
                email: true
            },
            username:{
                required: true,
                letterOrNumber: true
            },
            pwd: {
                minlength: 8,
                maxlength: 20,
                required: true,
                pwcheck: true
            },
            tel: {
                required: true,
                digits: true
            },
            birthdate: {
                required: true,
                cutomDate: true
            }
        },
        messages: {
            firstname: {
                required: "Firstname is required",
                lettersAndSpaces: "Only letters and white spaces are allowed for first name."
            },
            surname: {
                required: "Surname is required",
                lettersAndSpaces: "Only letters and white spaces are allowed for surname."
            },
            email: {
                required: "Email is required",
                email: "Invalid format for the email."
            },
            username:{
                required: "Username is required.",
                letterOrNumber: "Only letters and numbers are allowed for username."
            },
            pwd: {
                minlength: "Password is too short! Use more than 8 characters.",
                maxlength: "Password is too long! Not longer than 20 characters.",
                required: "Password is required.",
                pwcheck: "Password needs at least 1 capital, lowercase letter and 1 digit."
            },
            tel: {
                required: "Telephone number is required.",
                digits: "Please enter a valid telephone number."
            },
            birthdate: {
                required: "Birth date is required.",
                cutomDate: "Please enter a valid birthdate (yyyy-mm-dd)."
            }
        },
        errorElement: "em",
        errorPlacement: function ( error, element ) {
            // Add the `help-block` class to the error element
            error.addClass( "help-block" );
            // Add `has-feedback` class to the parent div.form-group
            // in order to add icons to inputs
            element.parents( ".col-sm-9" ).addClass( "has-feedback" );
                error.insertAfter( element );
            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if ( !element.next( "span" )[ 0 ] ) {
                $( "<span class='glyphicon glyphicon-remove form-control-feedback'></span>" ).insertAfter( element );
            }
        },
        success: function ( label, element ) {
            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if ( !$( element ).next( "span" )[ 0 ] ) {
                $( "<span class='glyphicon glyphicon-ok form-control-feedback'></span>" ).insertAfter( $( element ) );
            }
        },
        highlight: function ( element, errorClass, validClass ) {
            $( element ).parents( ".col-sm-9" ).addClass( "has-error" ).removeClass( "has-success" );
            $( element ).next( "span" ).addClass( "glyphicon-remove" ).removeClass( "glyphicon-ok" );
        },
        unhighlight: function ( element, errorClass, validClass ) {
            $( element ).parents( ".col-sm-9" ).addClass( "has-success" ).removeClass( "has-error" );
            $( element ).next( "span" ).addClass( "glyphicon-ok" ).removeClass( "glyphicon-remove" );
        }
    });
    $.validator.addMethod("pwcheck", function(value) {
        return /[A-Z]/.test(value)// has a capital letter
            && /[a-z]/.test(value) // has a lowercase letter
            && /\d/.test(value) // has a digit
    });
    $.validator.addMethod("letterOrNumber", function(value) {
        // only contains letters and/or numbers
        return /^[a-zA-Z0-9]+$/.test(value)
    });
    $.validator.addMethod("cutomDate", function(value) {
        // only valid dates as (yyyy-mm-dd) allowed
        return /^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/.test(value)
    });
    $.validator.addMethod("lettersAndSpaces", function(value) {
        // only letters and white spaces are allowed
        return /^[a-zA-Z\s]+$/.test(value)
    });
});