<?php
/**
 * Created by PhpStorm.
 * User: Dries
 * Date: 21/10/2017
 * Time: 23:44
 */


# This function is used for securing the webpage against XSS
# 1. Strip unnecessary characters
# 2. un quote a quoted string
# 3. convert special character to HTML entities -> also used in form action
function trim_secure_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

# This function gives true if there is a value in the array that is not empty
# Used for checking if an error occurred
function is_error_occurred($arr){
    foreach ($arr as $key => $value){
        if(!empty($value)){
            return true;
        }
    }
    return false;
}
?>