<?php
/**
 * Created by PhpStorm.
 * User: Dries
 * Date: 28/10/2017
 * Time: 21:04
 */

/**
 * Class Csrf
 * check if tokens are correct
 * generate a csrf token
 */
class Csrf
{
    /**
     * Generates the token for the given form in that session or it gives back the token if one is already generated.
     *
     * @param $formName the name of the form for which the token is generated
     * @return string the token that is generated for the given form for that session.
     */
    static function generateToken( $formName )
    {
        $tokenName = $formName . 'token';
        if(!isset($_SESSION))
        {
            session_start();
        }
        if(!isset($_SESSION[$tokenName])){
            $token = bin2hex(random_bytes(32));
            $_SESSION[$tokenName] = $token;
            return $token;
        }else {
            return $_SESSION[$tokenName];
        }
    }

    /**
     *
     * Checks whether the token maps the with the correct token of the form.
     *
     * @param $token The token that was returned by the client.
     * @param $formName The form name for which the token needs to be checked.
     * @return bool if the check is valid
     */
    static function checkToken( $token, $formName )
    {
        if(!isset($_SESSION))
        {
            session_start();
        }
        $tokenName = $formName . 'token';
        $isValid = ($token == ($_SESSION[$tokenName] ));
        if(!$isValid){
            error_log('CSRF attack!!');
            exit();
        }else{
            return $isValid;
        }
    }
}




