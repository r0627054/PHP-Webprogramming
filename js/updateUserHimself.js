
$(document).ready(function () {

    $(document).on('click', '#saveEditedUser', function () {
        $.ajax({
            type: "POST",
            url: "ajaxController.php",
            data: {
                "action": "updateUserHimself", "firstname": $('#firstname-edit').val(),
                "surname": $('#surname-edit').val(), "email": $('#email-edit').val(),
                "username": $('#username-edit').val(), "password": $('#pwd-edit').val(),
                "tel": $('#tel-edit').val(), "birthdate": $('#birthdate-edit').val(),
                "country": $('#country-edit').val(), "gender": $('#gender-edit').val(),
                "userUpdateSelfToken" :$('#userUpdateSelfToken').val()
            },
            dataType: "json",
        }).done(function (data) {
            if (data['updated'] == 'true') {
                $('#firstname-detail').empty().append(data['firstname']);
                $('#firstname-edit').val(data['firstname']);

                $('#surname-detail').empty().append(data['surname']);
                $('#surname-edit').val(data['surname']);

                $('#email-detail').empty().append(data['email']);
                $('#email-edit').val(data['email']);

                $('#username-detail').empty().append(data['username']);
                $('#username-edit').val(data['username']);

                $('#telephone-detail').empty().append(data['tel']);
                $('#tel-edit').val(data['tel']);

                $('#birthdate-detail').empty().append(data['birthdate']);
                $('#birthdate-edit').val(data['birthdate']);

                $('#country-detail').empty().append(data['country']);
                $('#country-edit').val(data['country']);

                $('#gender-detail').empty().append(data['gender']);
                $('#gender-edit').val(data['gender']);

                $('#editUserModal').modal('hide');
            } else {
                if ($('.alert.alert-danger').length) {
                    $('.alert.alert-danger').remove();
                }
                $alertBlock = $('<div />', {"class": 'alert alert-danger',});
                $.each(data['errors'], function (key, value) {
                    if (value.trim()) {
                        $alertBlock.append("<p>" + value + "</p>");
                    }
                });
                $('#updateUserForm').prepend($alertBlock);
            }
        });
    });

    $(document).on('click', '#saveNewPass', function () {
        $.ajax({
            type: "POST",
            url: "ajaxController.php",
            data: {
                "action": "updateUserPass", "newPass1": $('#newPass1').val(),
                "newPass2": $('#newPass2').val(),
                "newPassToken" :$('#newPassToken').val()
            },
            dataType: "json",
        }).done(function (data) {
            if (data['updated'] == 'true') {
                $('#changePassModal').modal('hide');
            } else {
                if ($('.alert.alert-danger').length) {
                    $('.alert.alert-danger').remove();
                }
                $alertBlock = $('<div />', {"class": 'alert alert-danger',});
                $.each(data['errors'], function (key, value) {
                    if (value.trim()) {
                        $alertBlock.append("<p>" + value + "</p>");
                    }
                });
                $('#changePasswordForm').prepend($alertBlock);
            }
            console.log(data);
        });
    });




});