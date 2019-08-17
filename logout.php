<?php
/**
 * Created by PhpStorm.
 * User: Dries
 * Date: 24/10/2017
 * Time: 13:56
 */
include_once('authentication.php');
$error = authentication::logoutUser();
header('Location:'. rtrim('http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF'])) .'/' . 'index.php');
exit();
?>