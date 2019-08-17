<?php
/**
 * Created by PhpStorm.
 * User: Dries
 * Date: 24/10/2017
 * Time: 15:29
 */


$status = $_SERVER['REDIRECT_STATUS'];
$codes = array(
    400 => array('ERROR 400', 'The request cannot be fulfilled due to bad syntax.'),
    403 => array('ERROR 403', 'The server has refused to fulfil your request.'),
    404 => array('ERROR 404', 'The page you requested was not found on this server.'),
    405 => array('ERROR 405', 'The method specified in the request is not allowed for the specified resource.'),
    408 => array('ERROR 408', 'Your browser failed to send a request in the time allowed by the server.'),
    500 => array('ERROR 500', 'The request was unsuccessful due to an unexpected condition encountered by the server.'),
    502 => array('ERROR 502', 'The server received an invalid response while trying to carry out the request.'),
    504 => array('ERROR 504', 'The upstream server failed to send a request in the time allowed by the server.'),
);

$title = $codes[$status][0];
$message = $codes[$status][1];
if ($title == false || strlen($status) != 3) {
    $message = 'Something went wrong!';
    $title = 'Error';
}

?>
<!--Author: W3layouts
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE html>
<html>
<head>
    <title>Dries webapp | Error</title>
    <link rel="stylesheet" href="css/errorStylesheet.css">
    <!-- For-Mobile-Apps-and-Meta-Tags -->
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<div class="main w3l">
    <h2>OOPS</h2>
    <h1><?php echo $title;?></h1>
    <h3><?php echo $message;?></h3>
    <a href="index.php" class="back">BACK TO HOME</a>
    <div class="footer agileits">
        <p>Copyright © 2016 Simple Error Page. All Rights Reserved | Design by <a href="http://w3layouts.com" target="_blank">W3layouts</a></p>
    </div>
</div>

</body>
</html>
