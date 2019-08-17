<?php
/**
 * Created by PhpStorm.
 * User: Dries
 * Date: 19/10/2017
 * Time: 15:13
 * Description: The controller that handles all the ajax requests.
 */




require_once('authentication.php');
require_once('email.php');
require_once('csrf.php');
$Db = new Database();
//delete request is not supported by 000webhost
/*if($_SERVER['REQUEST_METHOD'] === 'DELETE'){
    parse_str(file_get_contents("php://input"),$_DELETE_VAR);
    switch ($_DELETE_VAR['action']){
        case "delete":
            $Db->deleteUser($_DELETE_VAR['username']);
            break;
    }
} else */if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    switch ($_GET['action']) {
        case "allUsers":
            # request to show users on the admin overview page
            $roles = array('admin');
            authentication::checkAjaxAuthorized($roles);

            $users = json_encode($Db-> AllBasicInfoUsers());
            echo $users;
            break;
    }
}else if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    switch ($_POST['action']) {
        case "getDetail":
            # get the details of one user, needed on admin overview page
            $roles = array('admin');
            authentication::checkAjaxAuthorized($roles);

            $users = json_encode($Db->getUserDetailWithoutPassword($_POST['username']));
            echo $users;
            break;
        case "updateUser":
            # update a user when all feelds are used correctly, the update the admin executes
            $roles = array('admin');
            authentication::checkAjaxAuthorized($roles);

            # check if csrf token is correctly
            Csrf::checkToken($_POST["adminUpdateUserToken"], 'adminUpdateUser');

            $firstnameInput = $_POST["firstname"];
            $surnameInput  = $_POST["surname"];
            $emailInput = $_POST["email"];
            $usernameInput  = $_POST["username"];
            $pwdInput  = $_POST["password"];
            $telInput  = $_POST["tel"];
            $birthdateInput  = $_POST["birthdate"];
            $countryInput  = $_POST["country"];
            $genderInput  = $_POST["gender"];
            $roleInput = $_POST["role"];
            $oldUsername = $_POST["oldUsername"];
            $isEnabled = $_POST["enabled"];
            require('includes/secureInput.php');
            require('includes/userInputvalidation.php');
            #if password not changed keep last password


            if($pwd_error == "Password is required."){
                $pwd_error = "";
            }
            if($already_used_error == "This username is already in use, please take another one."){
                $already_used_error = "";
            }
            $isEnabled_error = "";
            if(!(trim_secure_input($isEnabled) == '1' || trim_secure_input($isEnabled) == '0')){
                $isEnabled_error = 'Wrong value for enabled!';
            }
            $errors = array("firstname_error"=>$firstname_error, "surname_error"=>$surname_error, "email_error"=>$email_error,
                "username_error" => $username_error, "pwd_error" => $pwd_error, "tel_error" => $tel_error,
                "birthday_error" =>$birthdate_error, "country_error" => $country_error, "gender_error" =>$gender_error,
                "role_error" =>$role_error, "already_used_error" =>$already_used_error, "isEnabled_error" => $isEnabled_error);
            # if no error occurred, update user and go to user overview page
            if(!is_error_occurred($errors)){
                if(empty($pwd)){
                    $Db->updateUserAndKeepPassword($firstname,$surname,$email,$tel,$birthdate,$country,$gender,$role,$oldUsername,$username, $isEnabled);
                }else {
                    $Db->updateUserWithNewPassword($firstname,$surname,$email,$tel,$birthdate,$country,$gender,$role,$oldUsername,$username,$pwd, $isEnabled);
                }
                $respons['updated'] = 'true';
            }else{
                $respons['updated'] = 'false';
                $respons['errors'] = $errors;
            }
            echo json_encode($respons);
            break;
        case "sendMail":
            # send a password change email to the user
            $roles = array('admin');
            authentication::checkAjaxAuthorized($roles);

            $mail = new Mail();
            echo $mail->sendResetPassword($_POST['username']);
            break;
        case "updateUserHimself":
            # a user that wants to update him/her self
            if(!isset($_SESSION))
            {
                session_start();
            }
            Csrf::checkToken($_POST["userUpdateSelfToken"], 'userUpdateSelf');
            $roles = array('user');
            authentication::checkAjaxAuthorized($roles);

            $firstnameInput = $_POST["firstname"];
            $surnameInput  = $_POST["surname"];
            $emailInput = $_POST["email"];
            $usernameInput  = $_POST["username"];
            $telInput  = $_POST["tel"];
            $pwdInput  = $_POST["password"];
            $birthdateInput  = $_POST["birthdate"];
            $countryInput  = $_POST["country"];
            $genderInput  = $_POST["gender"];
            $oldUsername = $_SESSION["username"];
            $roleInput = 'user';
            require('includes/secureInput.php');
            require('includes/userInputvalidation.php');
            # if password not changed keep last password
            if($pwd_error == "Password is required."){
                $pwd_error = "";
            }
            if($already_used_error == "This username is already in use, please take another one."){
                $already_used_error = "";
            }
            $errors = array("firstname_error"=>$firstname_error, "surname_error"=>$surname_error, "email_error"=>$email_error,
                "username_error" => $username_error, "tel_error" => $tel_error, "pwd_error" => $pwd_error,
                "birthday_error" =>$birthdate_error, "country_error" => $country_error, "gender_error" =>$gender_error,
                "already_used_error" =>$already_used_error);
            # if no error occurred, update user
            if(!is_error_occurred($errors)){
                if(empty($pwd)){
                    $Db->updateSelfAndKeepPassword($firstname,$surname,$email,$tel,$birthdate,$country,$gender,$role,$oldUsername,$username );
                }else {
                    $Db->updateSelfWithNewPassword($firstname,$surname,$email,$tel,$birthdate,$country,$gender,$role,$oldUsername,$username,$pwd);
                }
                $respons['updated'] = 'true';
                $respons['firstname']= $firstname;
                $respons['surname']= $surname;
                $respons['email']= $email;
                $respons['tel']= $tel;
                $respons['birthdate']= $birthdate;
                $respons['country']= $country;
                $respons['gender']= $gender;
                $respons['role']= $role;
                $respons['username']= $username;
            }else{
                $respons['updated'] = 'false';
                $respons['errors'] = $errors;
            }
            echo json_encode($respons);
            break;
        case "updateUserPass":
            # a user that wants to update their password
            if(!isset($_SESSION))
            {
                session_start();
            }
            Csrf::checkToken($_POST["newPassToken"], 'userPassToken');
            $roles = array('user');
            authentication::checkAjaxAuthorized($roles);
            require('includes/secureInput.php');
            $newPass1 = trim_secure_input($_POST["newPass1"]);
            $newPass2 = trim_secure_input($_POST["newPass2"]);
            $username = $_SESSION["username"];
            $pwd_equal = "";
            $pwd_error = "";
            if($newPass1 != $newPass2){
                $pwd_equal = "Passwords are not the same!";
            }else {
                if (empty($newPass1)) {
                    $pwd_error = "Password is required.";
                } else {
                    if(strlen($newPass1) >= 20 ) {
                        $pwd_error .= "Password is too long! Not longer than 20 characters. " ;
                    }
                    if( strlen($newPass1) <= 8 ) {
                        $pwd_error .= "Password is too short! Use more than 8 characters. ";
                    }
                    if( !preg_match("#[0-9]+#", $newPass1) ) {
                        $pwd_error .= "Password must include at least one number! ";
                    }
                    if( !preg_match("#[a-z]+#", $newPass1) ) {
                        $pwd_error .= "Password must include at least one letter! ";
                    }
                    if( !preg_match("#[A-Z]+#", $newPass1) ) {
                        $pwd_error .= "Password must include at least one Capital letter! ";
                    }
                }
            }

            $errors = array("pwdEqual"=>$pwd_equal, "pwdError"=>$pwd_error);
            # if no error occurred, update password
            if(!is_error_occurred($errors)){
                $Db->changePasswordUser($username, $newPass1);
                $respons['updated'] = 'true';
            }else{
                $respons['updated'] = 'false';
                $respons['errors'] = $errors;
            }
            echo json_encode($respons);
            break;
        case "delete":
            # check if csrf token is correctly
            Csrf::checkToken($_POST["deleteUserToken"], 'adminDeleteUser');
            # delete a user with given username
            $roles = array('admin');
            authentication::checkAjaxAuthorized($roles);
            $Db->deleteUser($_POST["username"]);
            break;
    }
}