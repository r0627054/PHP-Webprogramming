<?php
/**
 * User: Dries
 * Date: 21/10/2017
 * Time: 23:07
 */

$firstname_error = "";
$surname_error = "";
$email_error = "";
$username_error = "";
$pwd_error = "";
$tel_error = "";
$birthdate_error = "";
$country_error = "";
$gender_error = "";
$role_error = "";
$already_used_error ="";

$firstname = "";
$surname  = "";
$email = "";
$username  = "";
$pwd  = "";
$tel  = "";
$birthdate  = "";
$country  = "";
$gender  = "";
$role = "";

# check first name if not empty.
# check first name if it only exists of letters.
if (empty($firstnameInput)) {
    $firstname_error = "First name is required.";
} else {
    $firstname = trim_secure_input($firstnameInput);
    if (!preg_match("/^[a-zA-Z ]*$/", $firstname)) {
        $firstname_error = "Only letters and white spaces are allowed for first name.";
    }
}

# check surname if not empty.
# check surname if it only exists of letters.
if (empty($surnameInput)) {
    $surname_error = "Surname is required.";
} else {
    $surname = trim_secure_input($surnameInput);
    if (!preg_match("/^[a-zA-Z ]*$/", $surname)) {
        $surname_error = "Only letters and white space are allowed for surname.";
    }
}

# check email if not empty.
# check email if it only exists of numbers.
if (empty($emailInput)) {
    $email_error = "Email is required.";
} else {
    $email = trim_secure_input($emailInput);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Invalid format for the email.";
    }
}

# check username is not empty.
# check username only exists of numbers and letters.
# check username already exists
if (empty($usernameInput)) {
    $username_error = "Username is required.";
} else {
    $username = trim_secure_input($usernameInput);
    if (!preg_match("/^[a-zA-Z0-9]+$/", $username)) {
        $username_error = "Only letters and numbers are allowed for username.";
    }
    if($Db->isUsernameAlreadyUsed($username)){
        $already_used_error = "This username is already in use, please take another one.";
    }
}


# check pwd is not empty.
# check if pwd contains at least 8 character
# check if pwd contains less then 20 character
# check if pwd contains at least one number
# check if pwd contains at least one letter
# check if pwd contains at least one CAPS letter
if (empty($pwdInput)) {
    $pwd_error = "Password is required.";
} else {
    $pwd = trim_secure_input($pwdInput);
    if(strlen($pwd) >= 20 ) {
        $pwd_error .= "Password is too long! Not longer than 20 characters. " ;
    }
    if( strlen($pwd) <= 8 ) {
        $pwd_error .= "Password is too short! Use more than 8 characters. ";
    }
    if( !preg_match("#[0-9]+#", $pwd) ) {
        $pwd_error .= "Password must include at least one number! ";
    }
    if( !preg_match("#[a-z]+#", $pwd) ) {
        $pwd_error .= "Password must include at least one letter! ";
    }
    if( !preg_match("#[A-Z]+#", $pwd) ) {
        $pwd_error .= "Password must include at least one Capital letter! ";
    }
}

# check first name is not empty.
# check first name if it only exists of numbers.
if (empty($telInput)) {
    $tel_error = "Telephone number is required.";
} else {
    $tel = trim_secure_input($telInput);
    if (!preg_match("/^[0-9]*$/", $tel)) {
        $tel_error = "Please enter a valid telephone number.";
    }
}

# check birth date is not empty.
# check birth date is in correct format
# some browsers format start with input type: date, this is set to text
if (empty($birthdateInput)) {
    $birthdate_error = "Birth date is required.";
} else {
    $birthdate = trim_secure_input($birthdateInput);
    if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$birthdate)) {
        $birthdate_error = "Please enter a valid birthdate (yyyy-mm-dd).";
    }
}

# check country is not empty.
# check if country consists of 2 capital letters
if (empty($countryInput)) {
    $country_error = "Country is required.";
} else {
    $country = trim_secure_input($countryInput);
    if (!preg_match("/^[A-Z]{2}$/", $country)) {
        $country_error = "Please enter a valid country.";
    }
}

# check gender is not empty
# check if gender has a valid value of 'male' or 'female'
#                           or 'other' or 'preferNotToSay'
if (empty($genderInput)) {
    $gender_error = "Gender is required.";
} else {
    $gender = trim_secure_input($genderInput);
    if (!($gender == 'male' || $gender == 'female' ||
        $gender == 'other' || $gender == 'preferNotToSay')) {
        $gender_error = "Please select a valid gender option!";
    }
}

# check gender is not empty
# check if gender has a valid value of 'male' or 'female'
#                           or 'other' or 'preferNotToSay'
if (empty($roleInput)) {
    $role_error = "Role is required.";
} else {
    $role = trim_secure_input($roleInput);
    if (!($role == 'admin' || $role == 'user')) {
        $gender_error = "Please select a valid gender option!";
    }
}
?>