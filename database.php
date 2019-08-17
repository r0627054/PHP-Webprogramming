<?php
/**
 * Created by PhpStorm.
 * User: Dries
 * Date: 18/10/2017
 * Time: 20:25
 */

/**
 * Class Database
 */
class Database
{
    private $servername = "localhost";
    private $usrname = "root";
    private $password = "";
    private $dbname = "cardiffMet";

    /*
     * Local testing database
     */

    /**
     * Database constructor.
     */
    public function __construct()
    {
        $this->connection = $this->createConnection();
        if($this->connection->connect_error){
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    /**
     * Creates a database connection
     *
     * @return mysqli object for the database connection.
     */
    public function createConnection(){
        return new mysqli($this->servername,$this->usrname,$this->password,$this->dbname);
    }

    /**
     * Deletes the user with the given username.
     *
     * @param $username username of the user.
     * @return bool True if the user is deleted, False if the user could not be deleted.
     */
    public function deleteUser($username){
        $statement = $this->connection->prepare("DELETE FROM user WHERE username = ?");
        $statement->bind_param('s', $username);
        $statement->execute();
        if ( $statement->affected_rows ) {
            return true;
        }
        return false;
    }

    /**
     * Adds a user with the given parameters to the databse.
     *
     * @param $firstname firstname of the user.
     * @param $surname username of the user.
     * @param $email email of the user.
     * @param $username username of the user.
     * @param $password password of the user.
     * @param $tel telephone number of the user.
     * @param $birthdate date of birth of the user.
     * @param $country country of the user.
     * @param $gender gender of the user.
     * @param $role role of the user.
     */
    public function addUser($firstname, $surname, $email, $username, $password, $tel, $birthdate, $country, $gender, $role){
        //http://php.net/manual/en/faq.passwords.php
        //http://php.net/manual/en/function.password-hash.php
        //The used algorithm, cost and salt are returned as part of the hash.
        //no seperate salte column needed
        $hash_pwd = password_hash($password, PASSWORD_BCRYPT);
        $statement = $this->connection->prepare("INSERT INTO user (firstname,surname,email,username,password,tel,birthdate,country,gender,role) VALUES (?,?,?,?,?,?,?,?,?,?)");
        $statement->bind_param('ssssssssss', $firstname,$surname,$email,$username,$hash_pwd, $tel, $birthdate,$country,$gender,$role);
        $statement->execute();
    }

    /**
     * Sets a token for requesting a reset of the user.
     *
     * @param $username username of the user
     * @param $passReset token to be  set to the passReset column
     */
    public function addPassResetToUser($username,$passReset){
        $statement = $this->connection->prepare("UPDATE user SET passReset=? WHERE username=?");
        $statement->bind_param('ss', $passReset, $username);
        $statement->execute();
    }

    /**
     * Changes the password of the user with the given username.
     *
     * @param $username username of the user.
     * @param $password new password of the user.
     */
    public function changePasswordUser($username, $password){
        $hash_pwd = password_hash($password, PASSWORD_BCRYPT);
        $statement = $this->connection->prepare("UPDATE user SET password=? WHERE username=?");
        $statement->bind_param('ss', $hash_pwd, $username);
        $statement->execute();
    }

    /**
     * Updates the user (also with new password) with the given parameters
     *
     * @param $firstname firstname of the user.
     * @param $surname surname of the user.
     * @param $email email of the user.
     * @param $tel telephone number of the user.
     * @param $birthdate date of birth of the user.
     * @param $country country of the user.
     * @param $gender gender of the user.
     * @param $role role of the user.
     * @param $oldUsername the old username of the user.
     * @param $newUsername the new username of the user.
     * @param $password password of the user.
     * @param $enabled whether the user is enabled.
     */
    public function updateUserWithNewPassword($firstname, $surname, $email, $tel, $birthdate, $country, $gender, $role, $oldUsername, $newUsername, $password, $enabled){
        $hash_pwd = password_hash($password, PASSWORD_BCRYPT);
        $statement = $this->connection->prepare("UPDATE user SET password=?, firstname=?, surname=?, email=?, tel=?, birthdate=?, country=?, gender=?, role=?, username=?, enabled=? WHERE username=?");
        $statement->bind_param('ssssssssssss', $hash_pwd, $firstname,$surname,$email, $tel, $birthdate,$country,$gender,$role, $newUsername, $enabled, $oldUsername);
        $statement->execute();
    }

    /**
     * Updates the user without the password, but with all the rest of the parameters.
     *
     * @param $firstname firstname of the user.
     * @param $surname surname of the user.
     * @param $email email of the user.
     * @param $tel telephone number of the user.
     * @param $birthdate date of birth of the user.
     * @param $country country of the user.
     * @param $gender gender of the user.
     * @param $role role of the user.
     * @param $oldUsername old username of the user.
     * @param $newUsername new username of the user.
     * @param $enabled whether the user is enabled.
     */
    public function updateUserAndKeepPassword($firstname, $surname, $email, $tel, $birthdate, $country, $gender, $role, $oldUsername, $newUsername, $enabled){
        $statement = $this->connection->prepare("UPDATE user SET firstname=?, surname=?, email=?, tel=?, birthdate=?, country=?, gender=?, role=?, username=?, enabled=? WHERE username=?");
        $statement->bind_param('sssssssssss', $firstname,$surname,$email, $tel, $birthdate,$country,$gender,$role, $newUsername, $enabled, $oldUsername);
        $statement->execute();
    }

    /**
     * The user can update himself with the following parameters.
     *
     * @param $firstname firstname of the user.
     * @param $surname surname of the user.
     * @param $email email of the user.
     * @param $tel telephone number of the user.
     * @param $birthdate date of birth of the user.
     * @param $country country of the user.
     * @param $gender gender of the user.
     * @param $role role of the user.
     * @param $oldUsername old username of the user.
     * @param $newUsername new username of the user.
     * @param $password new password of the user.
     */
    public function updateSelfWithNewPassword($firstname, $surname, $email, $tel, $birthdate, $country, $gender, $role, $oldUsername, $newUsername, $password){
        $hash_pwd = password_hash($password, PASSWORD_BCRYPT);
        $statement = $this->connection->prepare("UPDATE user SET password=?, firstname=?, surname=?, email=?, tel=?, birthdate=?, country=?, gender=?, role=?, username=? WHERE username=?");
        $statement->bind_param('sssssssssss', $hash_pwd, $firstname,$surname,$email, $tel, $birthdate,$country,$gender,$role, $newUsername, $oldUsername);
        $statement->execute();
    }

    /**
     * The user can update himself with the following parameters but with the same password.
     *
     * @param $firstname firstname of the user.
     * @param $surname surname of the user.
     * @param $email email of the user.
     * @param $tel telephone number of the user.
     * @param $birthdate date of birth of the user.
     * @param $country country of the user.
     * @param $gender gender of the user.
     * @param $role role of the user.
     * @param $oldUsername old username of the user.
     * @param $newUsername new username of the user.
     */
    public function updateSelfAndKeepPassword($firstname, $surname, $email, $tel, $birthdate, $country, $gender, $role, $oldUsername, $newUsername){
        $statement = $this->connection->prepare("UPDATE user SET firstname=?, surname=?, email=?, tel=?, birthdate=?, country=?, gender=?, role=?, username=? WHERE username=?");
        $statement->bind_param('ssssssssss', $firstname,$surname,$email, $tel, $birthdate,$country,$gender,$role, $newUsername, $oldUsername);
        $statement->execute();
    }

    /**
     *  Returns de basic user information of all the users (firstname/surname/username)
     *
     * @return array array with the firstname - surname - username of all the users
     */
    public function AllBasicInfoUsers(){
        $statement = $this->connection->prepare("SELECT firstname, surname, username FROM user");
        $statement->execute();
        $statement->bind_result($firstname,$surname,$username);

        while ($statement->fetch()) {
            $output[]=array($firstname, $surname, $username);
        }
        return $output;
    }

    /**
     * Returns all the information of all the users of the database.
     * @return array with all the information of all the users.
     */
    public function AllUsersInfo(){
        $statement = $this->connection->prepare("SELECT * FROM user");
        $statement->execute();
        $statement->bind_result($firstname,$surname,$email,$username,$password, $tel, $birthdate,$country,$gender,$role, $passReset, $enabled);

        while ($statement->fetch()) {
            $output[]=array($firstname,$surname,$email,$username,$password, $tel, $birthdate,$country,$gender,$role, $passReset, $enabled);
        }
        return $output;
    }

    /**
     * Checks whether a username is already in use.
     *
     * @param $username username of a user.
     * @return bool True if the username is already in use, else false.
     */
    public function isUsernameAlreadyUsed($username){
        $statement = $this->connection->prepare("SELECT * FROM user WHERE username = ?");
        $statement->bind_param('s',$username);
        $statement->execute();
        $statement->store_result();

        if($statement->num_rows > 0) {
            return true;
        }else {
            return false;
        }
    }

    /**
     * Gets all the user information without the password of the requested user.
     *
     * @param $username username of the requested user info.
     * @return array all the user information without the password of the requested user.
     */
    public function getUserDetailWithoutPassword($username){
        $statement = $this->connection->prepare("SELECT * FROM user WHERE username = ?");
        $statement->bind_param('s',$username);
        $statement->execute();
        $statement->bind_result($firstname,$surname,$email,$username,$password, $tel, $birthdate,$country,$gender,$role, $passReset, $enabled);

        if($statement->fetch()){
            $output=array($firstname,$surname,$email,$username, $tel, $birthdate,$country,$gender,$role, $enabled);
        }
        return $output;
    }

    /**
     * Gets all the user information of the requested user.
     *
     * @param $username username of the requested user info.
     * @return array all the user information of the requested user.
     */
    public function getUserDetail($username){
        $statement = $this->connection->prepare("SELECT * FROM user WHERE username = ?");
        $statement->bind_param('s',$username);
        $statement->execute();
        $statement->bind_result($firstname,$surname,$email,$username,$password, $tel, $birthdate,$country,$gender,$role, $passReset, $enabled);

        if($statement->fetch()){
            $output=array($firstname,$surname,$email,$username, $tel,$password, $birthdate,$country,$gender,$role, $passReset, $enabled);
        }
        return $output;
    }

    /**
     * Checks if the password reset token is correct.
     *
     * @param $username username of the user
     * @param $passResetGiven password reset token which is given by the user
     * @return bool True if the password reset token given and in the database are the same, else false.
     */
    public function checkPassReset($username, $passResetGiven){
        $statement = $this->connection->prepare("SELECT passReset FROM user WHERE username = ?");
        $statement->bind_param('s',$username);
        $statement->execute();
        $statement->bind_result($passReset);
        if($statement->fetch()){
            $output=$passReset;
        }
        $isCorrect = false;
        if($output == $passResetGiven ){
            $isCorrect = true;
        }
        return $isCorrect;
    }

    /**
     * Deletes the password reset token of a given user.
     * @param $username username of a user.
     */
    public function deletePassReset($username){
        $statement = $this->connection->prepare("UPDATE user SET passReset= NULL WHERE username=?");
        $statement->bind_param('s', $username);
        $statement->execute();
    }

    /**
     * Counts the amount of users
     * @return mixed the number of users in the database.
     */
    public function getTotalUserCount(){
        $statement = $this->connection->prepare("SELECT COUNT(*) FROM user");
        $statement->execute();
        $statement->bind_result($result);
        if($statement->fetch()){
            $output=$result;
        }
        return $output;
    }

    /**
     * Returns a sorted limited set of users.
     *
     * @param $start the start position
     * @param $length the number of records
     * @param $sort how sorted
     * @return array the firsname, surname and username of set of users are returned.
     */
    public function getDataTableUsers($start, $length, $sort){
        $statement = $this->connection->prepare("SELECT firstname, surname, username FROM user order by " . $sort .  " LIMIT ?, ?");
        $statement->bind_param('ss',$start,$length);
        $statement->execute();
        $statement->bind_result($firstname,$surname, $username);
        while($statement->fetch()){
            $output[]=array($firstname,$surname,$username);
        }
        return $output;
    }

}