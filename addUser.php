<?php
/**
 * User: Dries Janse
 * Date: 18/10/2017
 * Time: 23:04
 */
# only admin can view this page
# check if user is an admin
include_once('authentication.php');
$roles = array('admin');
authentication::checkIfAuthorized($roles);

# generate csrf token if none exists
include_once('csrf.php');
$token = Csrf::generateToken('addUserForm');

# check if admin added a user
# check if no error occured
# show error or add user an go to overview page
require_once('includes/secureInput.php');
require_once('database.php');
$Db = new Database();
if ($_SERVER["REQUEST_METHOD"] == "POST" ) {
    $firstnameInput = $_POST["firstname"];
    $surnameInput  = $_POST["surname"];
    $emailInput = $_POST["email"];
    $usernameInput  = $_POST["username"];
    $pwdInput  = $_POST["pwd"];
    $telInput  = $_POST["tel"];
    $birthdateInput  = $_POST["birthdate"];
    $countryInput  = $_POST["country"];
    $genderInput  = $_POST["gender"];
    $roleInput = $_POST["role"];

    require_once('includes/userInputvalidation.php');


    # create an array with all the errors
    $errors = array("firstname_error"=>$firstname_error, "surname_error"=>$surname_error, "email_error"=>$email_error,
        "username_error" => $username_error, "pwd_error" => $pwd_error, "tel_error" => $tel_error,
        "birthday_error" =>$birthdate_error, "country_error" => $country_error, "gender_error" =>$gender_error,
        "role_error" =>$role_error, "already_used_error" =>$already_used_error);
    //Csrf::checkToken($_POST['addUserFormToken'], 'addUserform');
    # if no error occurred, save user and go to user overview page
    if((!is_error_occurred($errors)) && Csrf::checkToken($_POST["addUserFormToken"], 'addUserForm')){
        $Db->addUser($firstname,$surname,$email,$username,$pwd,$tel,$birthdate,$country,$gender,$role);
        header("Location:userOverview.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<?php
include_once('page.php');
$addUserPage = new AdminPage('addUser','Dries webapp | add user', 'css/adminStylesheet.css');
$addUserPage->displayHead();
?>

<body>

<!-- START navigation bar -->
<?php
$addUserPage->displayAdminNavigation();
?>
<!-- END navigation bar -->

<!-- START welcome section -->
<?php
$addUserPage->displayWelcomeAnimation();
?>
<!-- END welcome section -->
<!-- START main section with form -->
<main class="container-fluid" id="adduser">
    <div class="row">
        <div class="col-sm-3"></div>

        <div class="col-sm-6" id="addUserFromWrap">
            <h2>Add user</h2><br>
            <?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && is_error_occurred($errors) ){ ?>
                <div class="alert alert-danger">
               <?php foreach ($errors as $name => $error){
                    if(!empty($error)){ ?>
                    <p><?php echo $error; ?></p>
                   <?php }
                } ?>
                </div>
           <?php } ?>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" accept-charset="utf-8" id="adduserform" novalidate>
                <div class="form-group row <?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($errors['firstname_error'])) echo ' has-error';?>">
                    <label for="firstname" class="col-sm-3 col-form-label">First name:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Enter first name" value="<?php if ($_SERVER["REQUEST_METHOD"] == "POST" ) echo $firstname;?>" >
                    </div>
                </div>

                <div class="form-group row <?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($errors['surname_error'])) echo ' has-error';?>">
                    <label for="surname" class="col-sm-3 col-form-label">Surname:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="surname" name="surname" placeholder="Enter surname" value="<?php if ($_SERVER["REQUEST_METHOD"] == "POST" ) echo $surname;?>">
                    </div>
                </div>

                <div class="form-group row <?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($errors['email_error'])) echo ' has-error';?>">
                    <label for="email" class="col-sm-3 col-form-label">Email:</label>
                    <div class="col-sm-9">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" value="<?php if ($_SERVER["REQUEST_METHOD"] == "POST" ) echo $email;?>">
                    </div>
                </div>

                <div class="form-group row <?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($errors['username_error'])) echo ' has-error';?>">
                    <label for="username" class="col-sm-3 col-form-label">Username:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" value="<?php if ($_SERVER["REQUEST_METHOD"] == "POST" ) echo $username;?>">
                    </div>
                </div>

                <div class="form-group row <?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($errors['pwd_error'])) echo ' has-error';?>">
                    <label for="pwd" class="col-sm-3 col-form-label">Password:</label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control" id="pwd" name="pwd" placeholder="Enter password">
                    </div>
                </div>

                <div class="form-group row <?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($errors['tel_error'])) echo ' has-error';?>">
                    <label for="tel" class="col-sm-3 col-form-label">Telephone:</label>
                    <div class="col-sm-9">
                        <input type="tel" class="form-control" id="tel" name="tel" placeholder="Enter telephone" value="<?php if ($_SERVER["REQUEST_METHOD"] == "POST" ) echo $tel;?>">
                    </div>
                </div>

                <div class="form-group row <?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($errors['birthday_error'])) echo ' has-error';?>">
                    <label for="birthdate" class="col-sm-3 col-form-label">Date of birth (yyyy-mm-dd)</label>
                    <div class="col-sm-9">
                        <input class="form-control" type="text" id="birthdate" name="birthdate" placeholder="yyyy-mm-dd" value="<?php if ($_SERVER["REQUEST_METHOD"] == "POST" ) echo $birthdate;?>">
                    </div>
                </div>

                <div class="form-group row" <?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($errors['country_error'])) echo ' has-error';?>>
                    <label for="country" class="col-sm-3 col-form-label">Select location:</label>
                    <div class="col-sm-9">
                        <select class="form-control" id="country" name="country">
                            <option value="AF" <?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='AF') echo ' selected';?>>Afghanistan</option>
                            <option value="AX"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='AX') echo ' selected';?>>Åland Islands</option>
                            <option value="AL"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='AL') echo ' selected';?>>Albania</option>
                            <option value="DZ"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='DZ') echo ' selected';?>>Algeria</option>
                            <option value="AS"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='AS') echo ' selected';?>>American Samoa</option>
                            <option value="AD"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='AD') echo ' selected';?>>Andorra</option>
                            <option value="AO"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='AO') echo ' selected';?>>Angola</option>
                            <option value="AI"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='AI') echo ' selected';?>>Anguilla</option>
                            <option value="AQ"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='AQ') echo ' selected';?>>Antarctica</option>
                            <option value="AG"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='AG') echo ' selected';?>>Antigua and Barbuda</option>
                            <option value="AR"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='AR') echo ' selected';?>>Argentina</option>
                            <option value="AM"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='AM') echo ' selected';?>>Armenia</option>
                            <option value="AW"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='AW') echo ' selected';?>>Aruba</option>
                            <option value="AU"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='AU') echo ' selected';?>>Australia</option>
                            <option value="AT"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='AT') echo ' selected';?>>Austria</option>
                            <option value="AZ"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='AZ') echo ' selected';?>>Azerbaijan</option>
                            <option value="BS"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='BS') echo ' selected';?>>Bahamas</option>
                            <option value="BH"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='BH') echo ' selected';?>>Bahrain</option>
                            <option value="BD"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='BD') echo ' selected';?>>Bangladesh</option>
                            <option value="BB"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='BB') echo ' selected';?>>Barbados</option>
                            <option value="BY"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='BY') echo ' selected';?>>Belarus</option>
                            <option value="BE"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='BE') echo ' selected';?>>Belgium</option>
                            <option value="BZ"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='BZ') echo ' selected';?>>Belize</option>
                            <option value="BJ"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='BJ') echo ' selected';?>>Benin</option>
                            <option value="BM"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='BM') echo ' selected';?>>Bermuda</option>
                            <option value="BT"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='BT') echo ' selected';?>>Bhutan</option>
                            <option value="BO"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='BO') echo ' selected';?>>Bolivia, Plurinational State of</option>
                            <option value="BQ"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='BQ') echo ' selected';?>>Bonaire, Sint Eustatius and Saba</option>
                            <option value="BA"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='BA') echo ' selected';?>>Bosnia and Herzegovina</option>
                            <option value="BW"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='BW') echo ' selected';?>>Botswana</option>
                            <option value="BV"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='BV') echo ' selected';?>>Bouvet Island</option>
                            <option value="BR"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='BR') echo ' selected';?>>Brazil</option>
                            <option value="IO"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='IO') echo ' selected';?>>British Indian Ocean Territory</option>
                            <option value="BN"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='BN') echo ' selected';?>>Brunei Darussalam</option>
                            <option value="BG"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='BG') echo ' selected';?>>Bulgaria</option>
                            <option value="BF"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='BF') echo ' selected';?>>Burkina Faso</option>
                            <option value="BI"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='BI') echo ' selected';?>>Burundi</option>
                            <option value="KH"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='KH') echo ' selected';?>>Cambodia</option>
                            <option value="CM"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='CM') echo ' selected';?>>Cameroon</option>
                            <option value="CA"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='CA') echo ' selected';?>>Canada</option>
                            <option value="CV"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='CV') echo ' selected';?>>Cape Verde</option>
                            <option value="KY"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='KY') echo ' selected';?>>Cayman Islands</option>
                            <option value="CF"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='CF') echo ' selected';?>>Central African Republic</option>
                            <option value="TD"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='TD') echo ' selected';?>>Chad</option>
                            <option value="CL"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='DL') echo ' selected';?>>Chile</option>
                            <option value="CN"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='CN') echo ' selected';?>>China</option>
                            <option value="CX"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='CX') echo ' selected';?>>Christmas Island</option>
                            <option value="CC"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='CC') echo ' selected';?>>Cocos (Keeling) Islands</option>
                            <option value="CO"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='CO') echo ' selected';?>>Colombia</option>
                            <option value="KM"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='KM') echo ' selected';?>>Comoros</option>
                            <option value="CG"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='CG') echo ' selected';?>>Congo</option>
                            <option value="CD"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='CD') echo ' selected';?>>Congo, the Democratic Republic of the</option>
                            <option value="CK"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='CK') echo ' selected';?>>Cook Islands</option>
                            <option value="CR"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='CR') echo ' selected';?>>Costa Rica</option>
                            <option value="CI"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='CI') echo ' selected';?>>Côte d'Ivoire</option>
                            <option value="HR"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='HR') echo ' selected';?>>Croatia</option>
                            <option value="CU"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='CU') echo ' selected';?>>Cuba</option>
                            <option value="CW"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='CW') echo ' selected';?>>Curaçao</option>
                            <option value="CY"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='CY') echo ' selected';?>>Cyprus</option>
                            <option value="CZ"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='CZ') echo ' selected';?>>Czech Republic</option>
                            <option value="DK"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='DK') echo ' selected';?>>Denmark</option>
                            <option value="DJ"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='DJ') echo ' selected';?>>Djibouti</option>
                            <option value="DM"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='DM') echo ' selected';?>>Dominica</option>
                            <option value="DO"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='DO') echo ' selected';?>>Dominican Republic</option>
                            <option value="EC"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='EC') echo ' selected';?>>Ecuador</option>
                            <option value="EG"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='EG') echo ' selected';?>>Egypt</option>
                            <option value="SV"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='SV') echo ' selected';?>>El Salvador</option>
                            <option value="GQ"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='QG') echo ' selected';?>>Equatorial Guinea</option>
                            <option value="ER"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='ER') echo ' selected';?>>Eritrea</option>
                            <option value="EE"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='EE') echo ' selected';?>>Estonia</option>
                            <option value="ET"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='ET') echo ' selected';?>>Ethiopia</option>
                            <option value="FK"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='FK') echo ' selected';?>>Falkland Islands (Malvinas)</option>
                            <option value="FO"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='FO') echo ' selected';?>>Faroe Islands</option>
                            <option value="FJ"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='FJ') echo ' selected';?>>Fiji</option>
                            <option value="FI"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='FI') echo ' selected';?>>Finland</option>
                            <option value="FR"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='FR') echo ' selected';?>>France</option>
                            <option value="GF"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='GF') echo ' selected';?>>French Guiana</option>
                            <option value="PF"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='PF') echo ' selected';?>>French Polynesia</option>
                            <option value="TF"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='TF') echo ' selected';?>>French Southern Territories</option>
                            <option value="GA"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='GA') echo ' selected';?>>Gabon</option>
                            <option value="GM"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='GM') echo ' selected';?>>Gambia</option>
                            <option value="GE"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='GE') echo ' selected';?>>Georgia</option>
                            <option value="DE"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='DE') echo ' selected';?>>Germany</option>
                            <option value="GH"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='GH') echo ' selected';?>>Ghana</option>
                            <option value="GI"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='GI') echo ' selected';?>>Gibraltar</option>
                            <option value="GR"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='GR') echo ' selected';?>>Greece</option>
                            <option value="GL"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='GL') echo ' selected';?>>Greenland</option>
                            <option value="GD"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='GD') echo ' selected';?>>Grenada</option>
                            <option value="GP"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='GP') echo ' selected';?>>Guadeloupe</option>
                            <option value="GU"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='GU') echo ' selected';?>Guam</option>
                            <option value="GT"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='GT') echo ' selected';?>>Guatemala</option>
                            <option value="GG"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='GG') echo ' selected';?>>Guernsey</option>
                            <option value="GN"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='GN') echo ' selected';?>>Guinea</option>
                            <option value="GW"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='GW') echo ' selected';?>>Guinea-Bissau</option>
                            <option value="GY"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='GY') echo ' selected';?>>Guyana</option>
                            <option value="HT"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='HT') echo ' selected';?>>Haiti</option>
                            <option value="HM"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='HM') echo ' selected';?>>Heard Island and McDonald Islands</option>
                            <option value="VA"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='VA') echo ' selected';?>>Holy See (Vatican City State)</option>
                            <option value="HN"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='HN') echo ' selected';?>>Honduras</option>
                            <option value="HK"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='HK') echo ' selected';?>>Hong Kong</option>
                            <option value="HU"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='HU') echo ' selected';?>>Hungary</option>
                            <option value="IS"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='IS') echo ' selected';?>>Iceland</option>
                            <option value="IN"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='IN') echo ' selected';?>>India</option>
                            <option value="ID"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='ID') echo ' selected';?>>Indonesia</option>
                            <option value="IR"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='IR') echo ' selected';?>>Iran, Islamic Republic of</option>
                            <option value="IQ"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='IQ') echo ' selected';?>>Iraq</option>
                            <option value="IE"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='IE') echo ' selected';?>>Ireland</option>
                            <option value="IM"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='IM') echo ' selected';?>>Isle of Man</option>
                            <option value="IL"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='IL') echo ' selected';?>>Israel</option>
                            <option value="IT"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='IT') echo ' selected';?>>Italy</option>
                            <option value="JM"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='JM') echo ' selected';?>>Jamaica</option>
                            <option value="JP"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='JP') echo ' selected';?>>Japan</option>
                            <option value="JE"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='JE') echo ' selected';?>>Jersey</option>
                            <option value="JO"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='JO') echo ' selected';?>>Jordan</option>
                            <option value="KZ"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='KZ') echo ' selected';?>>Kazakhstan</option>
                            <option value="KE"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='KE') echo ' selected';?>>Kenya</option>
                            <option value="KI"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='KI') echo ' selected';?>>Kiribati</option>
                            <option value="KP"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='KP') echo ' selected';?>>Korea, Democratic People's Republic of</option>
                            <option value="KR"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='KR') echo ' selected';?>>Korea, Republic of</option>
                            <option value="KW"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='KW') echo ' selected';?>>Kuwait</option>
                            <option value="KG"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='KG') echo ' selected';?>>Kyrgyzstan</option>
                            <option value="LA"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='LA') echo ' selected';?>>Lao People's Democratic Republic</option>
                            <option value="LV"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='LV') echo ' selected';?>>Latvia</option>
                            <option value="LB"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='LB') echo ' selected';?>>Lebanon</option>
                            <option value="LS"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='LS') echo ' selected';?>>Lesotho</option>
                            <option value="LR"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='LR') echo ' selected';?>>Liberia</option>
                            <option value="LY"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='LY') echo ' selected';?>>Libya</option>
                            <option value="LI"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='LI') echo ' selected';?>>Liechtenstein</option>
                            <option value="LT"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='LT') echo ' selected';?>>Lithuania</option>
                            <option value="LU"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='LU') echo ' selected';?>>Luxembourg</option>
                            <option value="MO"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='MO') echo ' selected';?>>Macao</option>
                            <option value="MK"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='MK') echo ' selected';?>>Macedonia, the former Yugoslav Republic of</option>
                            <option value="MG"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='MG') echo ' selected';?>>Madagascar</option>
                            <option value="MW"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='MW') echo ' selected';?>>Malawi</option>
                            <option value="MY"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='MY') echo ' selected';?>>Malaysia</option>
                            <option value="MV"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='MV') echo ' selected';?>>Maldives</option>
                            <option value="ML"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='ML') echo ' selected';?>>Mali</option>
                            <option value="MT"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='MT') echo ' selected';?>>Malta</option>
                            <option value="MH"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='MH') echo ' selected';?>>Marshall Islands</option>
                            <option value="MQ"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='MQ') echo ' selected';?>>Martinique</option>
                            <option value="MR"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='MR') echo ' selected';?>>Mauritania</option>
                            <option value="MU"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='MU') echo ' selected';?>>Mauritius</option>
                            <option value="YT"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='YT') echo ' selected';?>>Mayotte</option>
                            <option value="MX"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='MX') echo ' selected';?>>Mexico</option>
                            <option value="FM"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='FM') echo ' selected';?>>Micronesia, Federated States of</option>
                            <option value="MD"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='MD') echo ' selected';?>>Moldova, Republic of</option>
                            <option value="MC"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='MC') echo ' selected';?>>Monaco</option>
                            <option value="MN"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='MN') echo ' selected';?>>Mongolia</option>
                            <option value="ME"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='ME') echo ' selected';?>>Montenegro</option>
                            <option value="MS"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='MS') echo ' selected';?>>Montserrat</option>
                            <option value="MA"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='MA') echo ' selected';?>>Morocco</option>
                            <option value="MZ"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='MZ') echo ' selected';?>>Mozambique</option>
                            <option value="MM"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='MM') echo ' selected';?>>Myanmar</option>
                            <option value="NA"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='NA') echo ' selected';?>>Namibia</option>
                            <option value="NR"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='NR') echo ' selected';?>>Nauru</option>
                            <option value="NP"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='NP') echo ' selected';?>>Nepal</option>
                            <option value="NL"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='NL') echo ' selected';?>>Netherlands</option>
                            <option value="NC"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='NC') echo ' selected';?>>New Caledonia</option>
                            <option value="NZ"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='NZ') echo ' selected';?>>New Zealand</option>
                            <option value="NI"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='NI') echo ' selected';?>>Nicaragua</option>
                            <option value="NE"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='NE') echo ' selected';?>>Niger</option>
                            <option value="NG"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='NG') echo ' selected';?>>Nigeria</option>
                            <option value="NU"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='NU') echo ' selected';?>>Niue</option>
                            <option value="NF"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='NF') echo ' selected';?>>Norfolk Island</option>
                            <option value="MP"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='MP') echo ' selected';?>>Northern Mariana Islands</option>
                            <option value="NO"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='NO') echo ' selected';?>>Norway</option>
                            <option value="OM"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='OM') echo ' selected';?>>Oman</option>
                            <option value="PK"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='PK') echo ' selected';?>>Pakistan</option>
                            <option value="PW"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='PW') echo ' selected';?>>Palau</option>
                            <option value="PS"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='PS') echo ' selected';?>>Palestinian Territory, Occupied</option>
                            <option value="PA"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='PA') echo ' selected';?>>Panama</option>
                            <option value="PG"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='PG') echo ' selected';?>>Papua New Guinea</option>
                            <option value="PY"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='PY') echo ' selected';?>>Paraguay</option>
                            <option value="PE"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='PE') echo ' selected';?>>Peru</option>
                            <option value="PH"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='PH') echo ' selected';?>>Philippines</option>
                            <option value="PN"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='PN') echo ' selected';?>>Pitcairn</option>
                            <option value="PL"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && empty($country) && $country=='PL') echo ' selected';?>>Poland</option>
                            <option value="PT"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='PT') echo ' selected';?>>Portugal</option>
                            <option value="PR"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='PR') echo ' selected';?>>Puerto Rico</option>
                            <option value="QA"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='QA') echo ' selected';?>>Qatar</option>
                            <option value="RE"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='RE') echo ' selected';?>>Réunion</option>
                            <option value="RO"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='RO') echo ' selected';?>>Romania</option>
                            <option value="RU"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='RU') echo ' selected';?>>Russian Federation</option>
                            <option value="RW"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='RW') echo ' selected';?>>Rwanda</option>
                            <option value="BL"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='BL') echo ' selected';?>>Saint Barthélemy</option>
                            <option value="SH"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='SH') echo ' selected';?>>Saint Helena, Ascension and Tristan da Cunha</option>
                            <option value="KN"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='KN') echo ' selected';?>>Saint Kitts and Nevis</option>
                            <option value="LC"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='LC') echo ' selected';?>>Saint Lucia</option>
                            <option value="MF"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='MF') echo ' selected';?>>Saint Martin (French part)</option>
                            <option value="PM"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='PM') echo ' selected';?>>Saint Pierre and Miquelon</option>
                            <option value="VC"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='VC') echo ' selected';?>>Saint Vincent and the Grenadines</option>
                            <option value="WS"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='WS') echo ' selected';?>>Samoa</option>
                            <option value="SM"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='SM') echo ' selected';?>>San Marino</option>
                            <option value="ST"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='ST') echo ' selected';?>>Sao Tome and Principe</option>
                            <option value="SA"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='SA') echo ' selected';?>>Saudi Arabia</option>
                            <option value="SN"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='SN') echo ' selected';?>>Senegal</option>
                            <option value="RS"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='RS') echo ' selected';?>>Serbia</option>
                            <option value="SC"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='SC') echo ' selected';?>>Seychelles</option>
                            <option value="SL"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='SL') echo ' selected';?>>Sierra Leone</option>
                            <option value="SG"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='SG') echo ' selected';?>>Singapore</option>
                            <option value="SX"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='SX') echo ' selected';?>>Sint Maarten (Dutch part)</option>
                            <option value="SK"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='SK') echo ' selected';?>>Slovakia</option>
                            <option value="SI"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='SI') echo ' selected';?>>Slovenia</option>
                            <option value="SB"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='SB') echo ' selected';?>>Solomon Islands</option>
                            <option value="SO"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='SO') echo ' selected';?>>Somalia</option>
                            <option value="ZA"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='ZA') echo ' selected';?>>South Africa</option>
                            <option value="GS"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='GS') echo ' selected';?>>South Georgia and the South Sandwich Islands</option>
                            <option value="SS"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='SS') echo ' selected';?>>South Sudan</option>
                            <option value="ES"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='ES') echo ' selected';?>>Spain</option>
                            <option value="LK"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='LK') echo ' selected';?>>Sri Lanka</option>
                            <option value="SD"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='SD') echo ' selected';?>>Sudan</option>
                            <option value="SR"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='SR') echo ' selected';?>>Suriname</option>
                            <option value="SJ"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='SJ') echo ' selected';?>>Svalbard and Jan Mayen</option>
                            <option value="SZ"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='SZ') echo ' selected';?>>Swaziland</option>
                            <option value="SE"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='SE') echo ' selected';?>>Sweden</option>
                            <option value="CH"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='CH') echo ' selected';?>>Switzerland</option>
                            <option value="SY"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='SY') echo ' selected';?>>Syrian Arab Republic</option>
                            <option value="TW"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='TW') echo ' selected';?>>Taiwan, Province of China</option>
                            <option value="TJ"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='TJ') echo ' selected';?>>Tajikistan</option>
                            <option value="TZ"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='TZ') echo ' selected';?>>Tanzania, United Republic of</option>
                            <option value="TH"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='TH') echo ' selected';?>>Thailand</option>
                            <option value="TL"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='TL') echo ' selected';?>>Timor-Leste</option>
                            <option value="TG"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='TG') echo ' selected';?>>Togo</option>
                            <option value="TK"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='TK') echo ' selected';?>>Tokelau</option>
                            <option value="TO"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='TO') echo ' selected';?>>Tonga</option>
                            <option value="TT"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='TT') echo ' selected';?>>Trinidad and Tobago</option>
                            <option value="TN"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='TN') echo ' selected';?>>Tunisia</option>
                            <option value="TR"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='TR') echo ' selected';?>>Turkey</option>
                            <option value="TM"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='TM') echo ' selected';?>>Turkmenistan</option>
                            <option value="TC"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='TC') echo ' selected';?>>Turks and Caicos Islands</option>
                            <option value="TV"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='TV') echo ' selected';?>>Tuvalu</option>
                            <option value="UG"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='UG') echo ' selected';?>>Uganda</option>
                            <option value="UA"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='UA') echo ' selected';?>>Ukraine</option>
                            <option value="AE"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='AE') echo ' selected';?>>United Arab Emirates</option>
                            <option value="GB"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='GB') echo ' selected';?>>United Kingdom</option>
                            <option value="US"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='US') echo ' selected';?>>United States</option>
                            <option value="UM"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='UM') echo ' selected';?>>United States Minor Outlying Islands</option>
                            <option value="UY"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='UY') echo ' selected';?>>Uruguay</option>
                            <option value="UZ"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='UZ') echo ' selected';?>>Uzbekistan</option>
                            <option value="VU"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='VU') echo ' selected';?>>Vanuatu</option>
                            <option value="VE"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='VE') echo ' selected';?>>Venezuela, Bolivarian Republic of</option>
                            <option value="VN"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='VN') echo ' selected';?>>Viet Nam</option>
                            <option value="VG"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='VG') echo ' selected';?>>Virgin Islands, British</option>
                            <option value="VI"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='VI') echo ' selected';?>>Virgin Islands, U.S.</option>
                            <option value="WF"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='WF') echo ' selected';?>>Wallis and Futuna</option>
                            <option value="EH"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='EH') echo ' selected';?>>Western Sahara</option>
                            <option value="YE"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='YE') echo ' selected';?>>Yemen</option>
                            <option value="ZM"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='ZM') echo ' selected';?>>Zambia</option>
                            <option value="ZW"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($country) && $country=='ZW') echo ' selected';?>>Zimbabwe</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row <?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($errors['gender_error'])) echo ' has-error';?>">
                    <label for="gender" class="col-sm-3 col-form-label">Gender:</label>
                    <div class="col-sm-9">
                        <select class="form-control" id="gender" name="gender">
                            <option value="male" <?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($gender) && $gender=='male') echo ' selected';?>>Male</option>
                            <option value="female"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($gender) && $gender=='female') echo ' selected';?>>Female</option>
                            <option value="other"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($gender) && $gender=='other') echo ' selected';?>>Other</option>
                            <option value="preferNotToSay"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($gender) && $gender=='preferNotToSay') echo ' selected';?>>Prefer not to say</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row <?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($errors['role_error'])) echo ' has-error';?>">
                    <label for="role" class="col-sm-3 col-form-label">Role:</label>
                    <div class="col-sm-9">
                        <select class="form-control" id="role" name="role">
                            <option value="admin"  <?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($role) && $role=='admin') echo ' selected';?>>Admin</option>
                            <option value="user"<?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($role) && $role=='user') echo ' selected';?>>User</option>
                        </select>
                    </div>
                </div>

                <input type="hidden" name="addUserFormToken" value="<?php echo $token; ?>">
                <div class="form-group row">
                    <button type="submit" class="btn btn-default col-xs-12" id="createButton">Create a user</button>
                </div>
            </form>
        </div>
        <div class="col-xs-3"></div>

    </div>
</main>
<!-- END main section with form -->

<!-- START footer -->
<?php
$addUserPage->displayFooter();
?>
<!-- END footer -->

</body>

</html>
