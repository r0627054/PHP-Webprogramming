/**
 * asks all the users to the ajax controller
 * shows them properly on the webpage
 */
function updateUserTable(){
    $.ajax({
        type: "GET",
        url: "ajaxController.php?action=allUsers",
    }).done(function( data ) {
        //find table body and empty it
        $tbody = $( "#usersBody" );
        $tbody.empty();
        $noElement = 1;
        //print all data and put in rows in table
        var usersObj = jQuery.parseJSON( data );
        $.each( usersObj, function(index, userArray ) {
            //creating html elements with document.createElement
            //is the fastest seen from benchmarks
            $tr = $(document.createElement('tr'));
            $th = $(document.createElement('th'));
            $th.append($noElement++);
            $tr.append($th);
            $colnumber = 1;
            $.each(userArray, function (index2, value) {
                $td = $(document.createElement('td'));
                $td.append(value);
                if($colnumber == 3){
                    $td.addClass("username");
                }
                $tr.append($td);
                $colnumber++;
            });
            $tr.append("                       <td>\n" +
                "                            <button class=\"btn btn-primary a-btn-slide-text viewButton\">\n" +
                "                                <span class=\"glyphicon glyphicon-eye-open\" aria-hidden=\"true\"></span>\n" +
                "                                <span><strong>View</strong></span>\n" +
                "                            </button>\n" +
                "                            <button class=\"btn btn-primary a-btn-slide-text deleteButton\">\n" +
                "                                <span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span>\n" +
                "                                <span><strong>Delete</strong></span>\n" +
                "                            </button>\n" +
                "                            <button class=\"btn btn-primary a-btn-slide-text mailButton\">\n" +
                "                                <span class=\"glyphicon glyphicon-send\" aria-hidden=\"true\"></span>\n" +
                "                                <span><strong>Email</strong></span>\n" +
                "                            </button>\n" +
                "                        </td>");
            $tbody.append($tr);
        });
    });
}

/**
 * When document ready
 */
$(document).ready(function () {
    //because a user can update himself
    $("#userDetailWrap").hide();


    $params = {};
    $params['action'] = 'load-users';
    loadusers($params);
    function loadusers(data) {
        if ($('#overviewTable').hasClass('dataTable') == false) {
            $('#overviewTable').dataTable({
                "processing": true,
                "serverSide": true,
                "pageLength": 10,
                "responsive": true,
                "ajax": {
                    "url": "userOverviewTable.php",
                    "type": "POST",
                    "data": data
                },
                /*"aoColumnDefs": [{
                    "bSortable": false,
                    "aTargets": ['nosort']
                }],*/
                "columnDefs": [
                    { className: "username", "targets": [ 2 ] },
                    { "orderable": false, "targets": 3 }
                ],
                "searching": false,
                "info":     false,
                "processing": false
            });
        }
    }



    $(document).on('click', '.btn.btn-primary.a-btn-slide-text.deleteButton', function () {
        //searches the row which the button is in.
        var $closeRow = $(this).closest("tr");
        //get the username of the row
        var $username = $closeRow.find(".username").text();

        //delete previous message + set new text
        $deleteMessage = $( "#deleteMessage" );
        $deleteMessage.empty();
        $deleteMessage.append('Are you sure you want to delete ' + $username + '?');
        $('#deleteConfirmUser').removeClass().addClass( "btn btn-success delete"+$username );
        // show delete modal
        $('#deleteUserModal').modal('show');

        $(document).on('click', '#deleteConfirmUser', function () {
            $classname = $('#deleteConfirmUser').attr('class');
            $correctclassname = "btn btn-success delete"+$username;
            if($classname == $correctclassname){

                var param = new Object();
                param.action ="delete";
                param.username = $username;

                //delete request is not supported by 000webhost
                //make use of POST request instead
                /*$.ajax({
                    url: 'ajaxController.php',
                    type: 'DELETE',
                    data: param,
                    success: function() {
                        $('#deleteUserModal').modal('hide');
                        updateUserTable();
                    }
                });*/
                $.ajax({
                    type: "POST",
                    url: "ajaxController.php",
                    data: { "action" : "delete", "username" : $username, "deleteUserToken" : $('#adminDeleteUserToken').val() },
                }).done(function() {
                    $('#deleteUserModal').modal('hide');
                    //updateUserTable();
                    $('#overviewTable').DataTable().ajax.reload();
                });

            }
        });
    });

    $(document).on('click', '.btn.btn-primary.a-btn-slide-text.viewButton', function () {
        $("#userDetailWrap").fadeIn(2000);
        //searches the row which the button is in.
        var $closeRow = $(this).closest("tr");
        //get the username of the row
        var $username = $closeRow.find(".username").text();
        $.ajax({
            type: "POST",
            url: "ajaxController.php",
            data: { "action" : "getDetail", "username" : $username },
            dataType: "json",
        }).done(function( data ) {
            $('#firstname-detail').empty().append(data[0]).hide().fadeIn(2000);
            $('#firstname-edit').val(data[0]);

            $('#surname-detail').empty().append(data[1]).hide().fadeIn(2000);
            $('#surname-edit').val(data[1]);

            $('#email-detail').empty().append(data[2]).hide().fadeIn(2000);
            $('#email-edit').val(data[2]);

            $('#username-detail').empty().append(data[3]).hide().fadeIn(2000);
            $('#username-edit').val(data[3]);

            $('#telephone-detail').empty().append(data[4]).hide().fadeIn(2000);
            $('#tel-edit').val(data[4]);

            $('#birthdate-detail').empty().append(data[5]).hide().fadeIn(2000);
            $('#birthdate-edit').val(data[5]);

            $('#country-detail').empty().append(data[6]).hide().fadeIn(2000);
            $('#country-edit').val(data[6]);

            $('#gender-detail').empty().append(data[7]).hide().fadeIn(2000);
            $('#gender-edit').val(data[7]);

            $('#role-detail').empty().append(data[8]).hide().fadeIn(2000);
            $('#role-edit').val(data[8]);

            if(data[9]){
                $isEnabledString = "User is enabled";
                $('#enabled-edit').val('1');
            }else{
                $isEnabledString = "User is disabled";
                $('#enabled-edit').val('0');
            }
            $('#enabled-detail').empty().append($isEnabledString).hide().fadeIn(2000);


            $('html,body').animate({
                scrollTop: $("#userDetail").offset().top
            });

            if( $('.alert.alert-danger').length )
            {
                $('.alert.alert-danger').remove();
            }
        });
    });

    $(document).on('click', '#editUserButton', function () {
        //finish last and other animations befor showing the modal
        //otherwise the animations will overlay and give undefined behaviour
        if( $('.alert.alert-danger').length )
        {
            $('.alert.alert-danger').remove();
        }
        $("#userDetailWrap").finish();
        $('#editUserModal').modal('show');
    });

    $(document).on('click', '#saveEditedUser', function () {
        $.ajax({
            type: "POST",
            url: "ajaxController.php",
            data: { "action" : "updateUser", "firstname" : $('#firstname-edit').val(),
            "surname" : $('#surname-edit').val(), "email" : $('#email-edit').val(),
            "username" : $('#username-edit').val(), "password" : $('#pwd-edit').val(),
                "tel" : $('#tel-edit').val(), "birthdate" : $('#birthdate-edit').val(),
                "country" :$('#country-edit').val(), "gender" : $('#gender-edit').val(),
                "role" : $('#role-edit').val(), "oldUsername" : $('#username-detail').text(),
                "adminUpdateUserToken" : $('#adminUpdateUserToken').val(), "enabled" : $('#enabled-edit').val() },
            dataType: "json",
        }).done(function( data ) {
            if(data['updated']== 'true'){
                //updateUserTable();
                $('#overviewTable').DataTable().ajax.reload();
                $('#editUserModal').modal('hide');
                $('#userDetailWrap').hide();
            }else{
                if( $('.alert.alert-danger').length )
                {
                    $('.alert.alert-danger').remove();
                }
                $alertBlock = $('<div />', {"class": 'alert alert-danger',});
                $.each( data['errors'], function( key, value ) {
                    if(value.trim()){
                        $alertBlock.append("<p>" + value + "</p>");
                    }
                });
                $('#updateUserForm').prepend($alertBlock);
            }
        });
    });

});













