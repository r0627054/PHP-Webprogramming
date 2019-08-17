<?php
/**
 * Created by PhpStorm.
 * User: Dries
 * Date: 24/10/2017
 * Time: 16:33
 */

require 'mailer/PHPMailerAutoload.php';

/**
 * Class Mail
 */
class Mail {

    private $mail;

    /**
     * Mail constructor.
     */
    function __construct(){
        $this->mail = new PHPMailer;
        $this->setVariables();
    }

    /**
     * Sets the correct variables, to connect to the mail server.
     */
    private function setVariables(){
        $this->mail->isSMTP();
        //$this->mail->SMTPDebug = 2;
        $this->mail->SMTPDebug = 0;
        //$this->mail->SMTPDebug = 1;
        //$this->mail->SMTPDebug = 4;
        $this->mail->Host = 'smtp.gmail.com';
        //$this->mail->SMTPDebug = 0;
        $this->mail->Port = 587;
        //Set the encryption system to use - ssl (deprecated) or tls
        $this->mail->SMTPSecure = 'tls';
        //Whether to use SMTP authentication
        $this->mail->SMTPAuth = true;
        //Username to use for SMTP authentication - use full email address for gmail
        $this->mail->Username = "username@gmail.com";
        //Password to use for SMTP authentication
        $this->mail->Password = "password";
        //Set who the message is to be sent from
        $this->mail->setFrom('username@gmail.com', 'Dries, CardiffMet');
        //Set an alternative reply-to address
        $this->mail->addReplyTo('Username@gmail.com', 'Dries, CardiffMet');
    }

    /**
     * Sends a reset email to the given user.
     * @param $username username of user.
     */
    function sendResetPassword($username){
        $Db = new Database();
        $details = $Db->getUserDetailWithoutPassword($username);
        $fullname = $details[0] . ' ' . $details[1];
        //Set who the message is to be sent to
        $this->mail->addAddress($details[2], $fullname);


        //Set the subject line
        $this->mail->Subject = 'Hello ' . $details[0] . ', you wanted to reset your password?';
        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body

        $this->mail->msgHTML($this->getHTMLPassReset($details[0], $details[3], 'http://' . rtrim( $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF'])) .'/' . 'passwordReset.php'));

        if (!$this->mail->send()) {
            echo "Something went wrong sending your message!";
        } else {
            echo "Your message is successfully sent.";
        }
    }

    /**
     * Generates the html for the email body.
     *
     * @param $firstname firstname of user.
     * @param $username username of user.
     * @param $action the action of what need to happen
     * @return bool|mixed|string
     */
    private function getHTMLPassReset($firstname, $username, $action){
        $random = $this->generateRandomString(40);
        $Db = new Database();
        $Db->addPassResetToUser($username,$random);
        $message = file_get_contents('mails/resetPassword.html');
        $message = str_replace('%firstname%', $firstname, $message);
        $message = str_replace('%username%', $username, $message);

        $message = str_replace('%action%', $action , $message);
        $message = str_replace('%secret%', $random, $message);
        return $message;
    }

    /**
     * Returns a randomly generated string.
     * @param int $length the length of the string
     * @return string random generated string
     */
    private function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }



}
