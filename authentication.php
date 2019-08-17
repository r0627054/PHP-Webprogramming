<?php
/**
 * Created by PhpStorm.
 * User: Dries Janse
 * Date: 22/10/2017
 * Time: 17:47
 */

require_once('database.php');

/**
 * Class authentication
 * handles all the authentication and authorisation of users
 */
class authentication
{
    /**
     * Tries to login the user. If he cannot login in, a proper error message is shown.
     *
     * @param $username the username entered by the user
     * @param $password the password entered by the user
     * @param $gRecaptchaResponse the RecaptchaResponse
     * @param $loginToken the login token
     * @return string an error message if the user cannot get logged in
     */
    static function loginUser($username, $password, $gRecaptchaResponse, $loginToken){
        include_once('csrf.php');
      if(isset($username) && isset($password) && isset($loginToken) && Csrf::checkToken($loginToken, 'loginUser')){
        if(isset($gRecaptchaResponse) && !empty($gRecaptchaResponse)){
            $secret = '6LeMsTUUAAAAAGNA3oM_NBo2Iz55PBY-QS93tYyV';
            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='. $gRecaptchaResponse);
            $responseData = json_decode($verifyResponse);
            if($responseData->success){
                $Db = new Database();
                //check if username exists
                if($Db->isUsernameAlreadyUsed($username)){
                    $user = $Db->getUserDetail($username);
                    //check if user is enabled
                        if($user[11]) {
                            //check if username and password are correct
                            if (password_verify($password, $user[5])) {
                                session_start();
                                $_SESSION["username"] = $username;
                                $role = $user[9];
                                if ($role == 'admin') {
                                    header('Location:' . 'http://' . rtrim($_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF'])) . '/' . 'userOverview.php');
                                    exit();
                                } else if ($role == 'user') {
                                    header('Location:' . 'http://' . rtrim($_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF'])) . '/' . 'userProfilePage.php');
                                    exit();
                                } else {
                                    return 'Something went wrong, you do not have a valid role';
                                }
                            } else {
                                return 'The password for ' . $username . ' is not correct!';
                            }
                        }else{
                            return 'Your account is disabled, ask the administrator.';
                        }
                }else{
                    return 'The username or password is incorrect!';
                }
            }else{
                return 'ReCAPTCHA failed, please try again.';
            }
        }else{
            return 'Please click on the reCAPTCHA box.';
        }
      }else{
          return 'Something went wrong, please try again.';
      }

    }

    /**
     * Logs out the user(destroys the session).
     */
    static function logoutUser(){
        if(!isset($_SESSION))
        {
            session_start();
        }
        session_destroy();
    }

    /**
     * If a user is not authorized to access something, they are redirected to the index page.
     * This is for non-ajax requests.
     *
     * @param $roles the roles needed to be authorized.
     */
    static function checkIfAuthorized($roles){
        if(!(session_start() == PHP_SESSION_NONE)){
            header('Location:'.'http://' . rtrim($_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF'])) .'/' . 'index.php');
            exit();
        }
        $Db = new Database();
        $user = $Db->getUserDetail($_SESSION["username"]);
        $userRole = $user[9];
        $isInList = false;
        foreach ($roles as $role){
            if($role == $userRole){
                $isInList = true;
            }
        }
        if((!$isInList) || (!$user[11]) ){
            header('Location:'.'http://' . rtrim($_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF'])) .'/' . 'index.php');
            exit();
        }
    }

    /**
     * If a user is not authorized to access something, they execution of the current process is terminated.
     * This method is for ajax requests.
     *
     * @param $roles the roles needed to be authorized.
     */
    static function checkAjaxAuthorized($roles){
        if(!isset($_SESSION))
        {
            session_start();
        }
        if(!isset($_SESSION["username"])){
            error_log('Someone who was not authorized tried to execute and ajax command! (non user)');
            exit();
        }
        $Db = new Database();
        $user = $Db->getUserDetail($_SESSION["username"]);
        $userRole = $user[9];
        $isInList = false;
        foreach ($roles as $role){
            if($role == $userRole){
                $isInList = true;
            }
        }
        if((!$isInList) || (!$user[11])){
            error_log('Someone who was not authorized tried to execute and ajax command! (user)');
            exit();
        }
    }

}