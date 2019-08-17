<?php
/**
 * Created by PhpStorm.
 * User: Dries
 * Date: 18/10/2017
 * Time: 14:04
 */

# check if user is authenticated
include_once('authentication.php');
$roles = array('admin');
authentication::checkIfAuthorized($roles);

# generate csrf tokens for the webpage
include_once('csrf.php');
$adminUpdateUsertoken = Csrf::generateToken('adminUpdateUser');
$deleteUserToken =Csrf::generateToken('adminDeleteUser');

?>
<!DOCTYPE html>
<html lang="en">
<?php
include_once ('page.php');
$overviewPage = new AdminPage('userOverview', 'Dries webapp | user overview', 'css/adminStylesheet.css' );
$overviewPage->displayHead();
?>
<body>

<!-- START navigation bar -->
<?php
$overviewPage->displayAdminNavigation();
?>
<!-- END navigation bar -->

<!-- START welcome section -->
<?php
$overviewPage->displayWelcomeAnimation();
?>
<!-- END welcome section -->

<!-- START main user overview section -->
<main class="container-fluid text-center" id="maininfo">
    <h2>All users</h2><br>
    <div class="row">
        <div class="col-sm-1"></div>
        <div class="col-sm-10">
            <table class="table table-responsive table-hover table-bordered" id="overviewTable">
                <thead>
                <tr class="bg-primary">
                    <th>firstname</th>
                    <th>surname</th>
                    <th>username</th>
                    <th>options</th>
                </tr>
                </thead>
                <tbody id="usersBody">
                <!--<?php
                require_once('database.php');
                $Db = new Database();
                $users = $Db->AllBasicInfoUsers();
                foreach ($users as $value) {?>
                    <tr>
                        <td><?php echo $value[0];?></td>
                        <td><?php echo $value[1]; ?></td>
                        <td class="username"><?php echo $value[2]; ?></td>
                        <td>
                            <button class="btn btn-primary a-btn-slide-text viewButton">
                                <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                                <span><strong>View</strong></span>
                            </button>
                            <button class="btn btn-primary a-btn-slide-text deleteButton">
                                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                <span><strong>Delete</strong></span>
                            </button>
                            <button class="btn btn-primary a-btn-slide-text mailButton">
                                <span class="glyphicon glyphicon-send" aria-hidden="true"></span>
                                <span><strong>Email</strong></span>
                            </button>
                        </td>
                    </tr><?php }?>-->
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-sm-1"></div>
</main>
<!-- END main user overview section -->

<!-- START user info section -->
<article class="container-fluid" id="userDetailWrap">
    <div class="col-sm-3"></div>
    <div class="col-sm-6" id="userDetail">
        <h2>User information</h2>
        <div class="row">
            <p class="col-xs-3">First name:</p>
            <p id="firstname-detail" class="col-xs-9">Dummie name</p>
        </div>

        <div class="row">
            <p class="col-xs-3">Surname:</p>
            <p id="surname-detail" class="col-xs-9">Dummie surname</p>
        </div>

        <div class="row">
            <p class="col-xs-3">Email:</p>
            <p id="email-detail" class="col-xs-9">Dummie email</p>
        </div>

        <div class="row">
            <p class="col-xs-3">Username:</p>
            <p id="username-detail" class="col-xs-9">Dummie username</p>
        </div>

        <div class="row">
            <p class="col-xs-3">Telephone:</p>
            <p id="telephone-detail" class="col-xs-9">Dummie telephone</p>
        </div>

        <div class="row">
            <p class="col-xs-3">Date of birth:</p>
            <p id="birthdate-detail" class="col-xs-9">Dummie dd/mm/YYYY</p>
        </div>

        <div class="row">
            <p class="col-xs-3">Country:</p>
            <p id="country-detail" class="col-xs-9">Dummie country</p>
        </div>

        <div class="row">
            <p class="col-xs-3">Gender:</p>
            <p id="gender-detail" class="col-xs-9">Dummie gender</p>
        </div>


        <div class="row">
            <p class="col-xs-3">Role:</p>
            <p id="role-detail" class="col-xs-9">Dummie role</p>
        </div>

        <div class="row">
            <p class="col-xs-3">Enabled:</p>
            <p id="enabled-detail" class="col-xs-9">Dummie enabled</p>
        </div>

        <br/><br/>
        <button id="editUserButton"  class="btn btn-primary a-btn-slide-text col-xs-12">
            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
            <span><strong>Edit</strong></span>
        </button>
    </div>
    <div class="col-sm-3"></div>
</article>
<!-- END user information section -->

<!-- START footer -->
<?php
$overviewPage->displayFooter();
?>
<!-- END footer -->

<!-- START edit user Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog"
     aria-labelledby="editUserModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title">
                    Edit user information
                </h4>
            </div>
            <!-- START modal body -->
            <div class="modal-body">
                <form id="updateUserForm">
                    <div class="form-group">
                        <label for="firstname-edit">First name:</label>
                        <input type="text" class="form-control" id="firstname-edit" name="firstname">
                    </div>

                    <div class="form-group">
                        <label for="surname-edit">Surname:</label>
                        <input type="text" class="form-control" id="surname-edit" name="surname">
                    </div>

                    <div class="form-group">
                        <label for="email-edit" class="col-form-label">Email:</label>
                        <input type="email" class="form-control" id="email-edit" name="email">
                    </div>

                    <div class="form-group">
                        <label for="username-edit" class="col-form-label">Username:</label>
                        <input type="text" class="form-control" id="username-edit" name="username">
                    </div>

                    <div class="form-group">
                        <label for="pwd-edit" class="col-form-label">Password:</label>
                        <input type="password" class="form-control" id="pwd-edit" name="pwd" placeholder="Enter new password.">
                    </div>

                    <div class="form-group">
                        <label for="tel-edit" class="col-form-label">Telephone:</label>
                        <input type="tel" class="form-control" id="tel-edit" name="tel">
                    </div>

                    <div class="form-group">
                        <label for="birthdate-edit" class="col-form-label">Date of birth</label>
                        <input class="form-control" type="text" id="birthdate-edit" name="birthdate" >
                    </div>

                    <div class="form-group">
                        <label for="country-edit" class="col-form-label">Select location:</label>
                        <select class="form-control" id="country-edit" name="country">
                            <option value="AF">Afghanistan</option>
                            <option value="AX">Åland Islands</option>
                            <option value="AL">Albania</option>
                            <option value="DZ">Algeria</option>
                            <option value="AS">American Samoa</option>
                            <option value="AD">Andorra</option>
                            <option value="AO">Angola</option>
                            <option value="AI">Anguilla</option>
                            <option value="AQ">Antarctica</option>
                            <option value="AG">Antigua and Barbuda</option>
                            <option value="AR">Argentina</option>
                            <option value="AM">Armenia</option>
                            <option value="AW">Aruba</option>
                            <option value="AU">Australia</option>
                            <option value="AT">Austria</option>
                            <option value="AZ">Azerbaijan</option>
                            <option value="BS">Bahamas</option>
                            <option value="BH">Bahrain</option>
                            <option value="BD">Bangladesh</option>
                            <option value="BB">Barbados</option>
                            <option value="BY">Belarus</option>
                            <option value="BE">Belgium</option>
                            <option value="BZ">Belize</option>
                            <option value="BJ">Benin</option>
                            <option value="BM">Bermuda</option>
                            <option value="BT">Bhutan</option>
                            <option value="BO">Bolivia, Plurinational State of</option>
                            <option value="BQ">Bonaire, Sint Eustatius and Saba</option>
                            <option value="BA">Bosnia and Herzegovina</option>
                            <option value="BW">Botswana</option>
                            <option value="BV">Bouvet Island</option>
                            <option value="BR">Brazil</option>
                            <option value="IO">British Indian Ocean Territory</option>
                            <option value="BN">Brunei Darussalam</option>
                            <option value="BG">Bulgaria</option>
                            <option value="BF">Burkina Faso</option>
                            <option value="BI">Burundi</option>
                            <option value="KH">Cambodia</option>
                            <option value="CM">Cameroon</option>
                            <option value="CA">Canada</option>
                            <option value="CV">Cape Verde</option>
                            <option value="KY">Cayman Islands</option>
                            <option value="CF">Central African Republic</option>
                            <option value="TD">Chad</option>
                            <option value="CL">Chile</option>
                            <option value="CN">China</option>
                            <option value="CX">Christmas Island</option>
                            <option value="CC">Cocos (Keeling) Islands</option>
                            <option value="CO">Colombia</option>
                            <option value="KM">Comoros</option>
                            <option value="CG">Congo</option>
                            <option value="CD">Congo, the Democratic Republic of the</option>
                            <option value="CK">Cook Islands</option>
                            <option value="CR">Costa Rica</option>
                            <option value="CI">Côte d'Ivoire</option>
                            <option value="HR">Croatia</option>
                            <option value="CU">Cuba</option>
                            <option value="CW">Curaçao</option>
                            <option value="CY">Cyprus</option>
                            <option value="CZ">Czech Republic</option>
                            <option value="DK">Denmark</option>
                            <option value="DJ">Djibouti</option>
                            <option value="DM">Dominica</option>
                            <option value="DO">Dominican Republic</option>
                            <option value="EC">Ecuador</option>
                            <option value="EG">Egypt</option>
                            <option value="SV">El Salvador</option>
                            <option value="GQ">Equatorial Guinea</option>
                            <option value="ER">Eritrea</option>
                            <option value="EE">Estonia</option>
                            <option value="ET">Ethiopia</option>
                            <option value="FK">Falkland Islands (Malvinas)</option>
                            <option value="FO">Faroe Islands</option>
                            <option value="FJ">Fiji</option>
                            <option value="FI">Finland</option>
                            <option value="FR">France</option>
                            <option value="GF">French Guiana</option>
                            <option value="PF">French Polynesia</option>
                            <option value="TF">French Southern Territories</option>
                            <option value="GA">Gabon</option>
                            <option value="GM">Gambia</option>
                            <option value="GE">Georgia</option>
                            <option value="DE">Germany</option>
                            <option value="GH">Ghana</option>
                            <option value="GI">Gibraltar</option>
                            <option value="GR">Greece</option>
                            <option value="GL">Greenland</option>
                            <option value="GD">Grenada</option>
                            <option value="GP">Guadeloupe</option>
                            <option value="GU">Guam</option>
                            <option value="GT">Guatemala</option>
                            <option value="GG">Guernsey</option>
                            <option value="GN">Guinea</option>
                            <option value="GW">Guinea-Bissau</option>
                            <option value="GY">Guyana</option>
                            <option value="HT">Haiti</option>
                            <option value="HM">Heard Island and McDonald Islands</option>
                            <option value="VA">Holy See (Vatican City State)</option>
                            <option value="HN">Honduras</option>
                            <option value="HK">Hong Kong</option>
                            <option value="HU">Hungary</option>
                            <option value="IS">Iceland</option>
                            <option value="IN">India</option>
                            <option value="ID">Indonesia</option>
                            <option value="IR">Iran, Islamic Republic of</option>
                            <option value="IQ">Iraq</option>
                            <option value="IE">Ireland</option>
                            <option value="IM">Isle of Man</option>
                            <option value="IL">Israel</option>
                            <option value="IT">Italy</option>
                            <option value="JM">Jamaica</option>
                            <option value="JP">Japan</option>
                            <option value="JE">Jersey</option>
                            <option value="JO">Jordan</option>
                            <option value="KZ">Kazakhstan</option>
                            <option value="KE">Kenya</option>
                            <option value="KI">Kiribati</option>
                            <option value="KP">Korea, Democratic People's Republic of</option>
                            <option value="KR">Korea, Republic of</option>
                            <option value="KW">Kuwait</option>
                            <option value="KG">Kyrgyzstan</option>
                            <option value="LA">Lao People's Democratic Republic</option>
                            <option value="LV">Latvia</option>
                            <option value="LB">Lebanon</option>
                            <option value="LS">Lesotho</option>
                            <option value="LR">Liberia</option>
                            <option value="LY">Libya</option>
                            <option value="LI">Liechtenstein</option>
                            <option value="LT">Lithuania</option>
                            <option value="LU">Luxembourg</option>
                            <option value="MO">Macao</option>
                            <option value="MK">Macedonia, the former Yugoslav Republic of</option>
                            <option value="MG">Madagascar</option>
                            <option value="MW">Malawi</option>
                            <option value="MY">Malaysia</option>
                            <option value="MV">Maldives</option>
                            <option value="ML">Mali</option>
                            <option value="MT">Malta</option>
                            <option value="MH">Marshall Islands</option>
                            <option value="MQ">Martinique</option>
                            <option value="MR">Mauritania</option>
                            <option value="MU">Mauritius</option>
                            <option value="YT">Mayotte</option>
                            <option value="MX">Mexico</option>
                            <option value="FM">Micronesia, Federated States of</option>
                            <option value="MD">Moldova, Republic of</option>
                            <option value="MC">Monaco</option>
                            <option value="MN">Mongolia</option>
                            <option value="ME">Montenegro</option>
                            <option value="MS">Montserrat</option>
                            <option value="MA">Morocco</option>
                            <option value="MZ">Mozambique</option>
                            <option value="MM">Myanmar</option>
                            <option value="NA">Namibia</option>
                            <option value="NR">Nauru</option>
                            <option value="NP">Nepal</option>
                            <option value="NL">Netherlands</option>
                            <option value="NC">New Caledonia</option>
                            <option value="NZ">New Zealand</option>
                            <option value="NI">Nicaragua</option>
                            <option value="NE">Niger</option>
                            <option value="NG">Nigeria</option>
                            <option value="NU">Niue</option>
                            <option value="NF">Norfolk Island</option>
                            <option value="MP">Northern Mariana Islands</option>
                            <option value="NO">Norway</option>
                            <option value="OM">Oman</option>
                            <option value="PK">Pakistan</option>
                            <option value="PW">Palau</option>
                            <option value="PS">Palestinian Territory, Occupied</option>
                            <option value="PA">Panama</option>
                            <option value="PG">Papua New Guinea</option>
                            <option value="PY">Paraguay</option>
                            <option value="PE">Peru</option>
                            <option value="PH">Philippines</option>
                            <option value="PN">Pitcairn</option>
                            <option value="PL">Poland</option>
                            <option value="PT">Portugal</option>
                            <option value="PR">Puerto Rico</option>
                            <option value="QA">Qatar</option>
                            <option value="RE">Réunion</option>
                            <option value="RO">Romania</option>
                            <option value="RU">Russian Federation</option>
                            <option value="RW">Rwanda</option>
                            <option value="BL">Saint Barthélemy</option>
                            <option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
                            <option value="KN">Saint Kitts and Nevis</option>
                            <option value="LC">Saint Lucia</option>
                            <option value="MF">Saint Martin (French part)</option>
                            <option value="PM">Saint Pierre and Miquelon</option>
                            <option value="VC">Saint Vincent and the Grenadines</option>
                            <option value="WS">Samoa</option>
                            <option value="SM">San Marino</option>
                            <option value="ST">Sao Tome and Principe</option>
                            <option value="SA">Saudi Arabia</option>
                            <option value="SN">Senegal</option>
                            <option value="RS">Serbia</option>
                            <option value="SC">Seychelles</option>
                            <option value="SL">Sierra Leone</option>
                            <option value="SG">Singapore</option>
                            <option value="SX">Sint Maarten (Dutch part)</option>
                            <option value="SK">Slovakia</option>
                            <option value="SI">Slovenia</option>
                            <option value="SB">Solomon Islands</option>
                            <option value="SO">Somalia</option>
                            <option value="ZA">South Africa</option>
                            <option value="GS">South Georgia and the South Sandwich Islands</option>
                            <option value="SS">South Sudan</option>
                            <option value="ES">Spain</option>
                            <option value="LK">Sri Lanka</option>
                            <option value="SD">Sudan</option>
                            <option value="SR">Suriname</option>
                            <option value="SJ">Svalbard and Jan Mayen</option>
                            <option value="SZ">Swaziland</option>
                            <option value="SE">Sweden</option>
                            <option value="CH">Switzerland</option>
                            <option value="SY">Syrian Arab Republic</option>
                            <option value="TW">Taiwan, Province of China</option>
                            <option value="TJ">Tajikistan</option>
                            <option value="TZ">Tanzania, United Republic of</option>
                            <option value="TH">Thailand</option>
                            <option value="TL">Timor-Leste</option>
                            <option value="TG">Togo</option>
                            <option value="TK">Tokelau</option>
                            <option value="TO">Tonga</option>
                            <option value="TT">Trinidad and Tobago</option>
                            <option value="TN">Tunisia</option>
                            <option value="TR">Turkey</option>
                            <option value="TM">Turkmenistan</option>
                            <option value="TC">Turks and Caicos Islands</option>
                            <option value="TV">Tuvalu</option>
                            <option value="UG">Uganda</option>
                            <option value="UA">Ukraine</option>
                            <option value="AE">United Arab Emirates</option>
                            <option value="GB">United Kingdom</option>
                            <option value="US">United States</option>
                            <option value="UM">United States Minor Outlying Islands</option>
                            <option value="UY">Uruguay</option>
                            <option value="UZ">Uzbekistan</option>
                            <option value="VU">Vanuatu</option>
                            <option value="VE">Venezuela, Bolivarian Republic of</option>
                            <option value="VN">Viet Nam</option>
                            <option value="VG">Virgin Islands, British</option>
                            <option value="VI">Virgin Islands, U.S.</option>
                            <option value="WF">Wallis and Futuna</option>
                            <option value="EH">Western Sahara</option>
                            <option value="YE">Yemen</option>
                            <option value="ZM">Zambia</option>
                            <option value="ZW">Zimbabwe</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="gender-edit" class="col-form-label">Gender:</label>
                        <select class="form-control" id="gender-edit" name="gender">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                            <option value="preferNotToSay">Prefer not to say</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="role-edit" class="col-form-label">Role:</label>
                        <select class="form-control" id="role-edit" name="role">
                            <option value="admin">admin</option>
                            <option value="user">user</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="enabled-edit" class="col-form-label">enabled:</label>
                        <select class="form-control" id="enabled-edit" name="enabled">
                            <option value="1">enabled</option>
                            <option value="0">disabled</option>
                        </select>
                    </div>
                    <input type="hidden" name="adminUpdateUser" id="adminUpdateUserToken" value="<?php echo $adminUpdateUsertoken ?>" >
                </form>
            </div>
            <!-- END modal body -->
            <!-- START modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">
                    Close
                </button>
                <button type="button" class="btn btn-primary" id="saveEditedUser">
                    Save changes
                </button>
            </div>
            <!-- END modal footer -->
        </div>
    </div>
</div>
<!-- END edit user modal -->


<!-- START delete user confirmation modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog"
     aria-labelledby="deleteUserModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title">
                    Delete user
                </h4>
            </div>
            <!-- START modal body -->
            <div class="modal-body">
                <input type="hidden" id="adminDeleteUserToken" name="adminDeleteUser" value="<?php echo $deleteUserToken ?>">
                <p id="deleteMessage">Are you sure you want to delete: {username}?</p>
            </div>
            <!-- END modal body -->
            <!-- START modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger"
                        data-dismiss="modal">
                    No, close!
                </button>
                <button type="button" class="btn btn-success" id="deleteConfirmUser">
                    Yes, delete!
                </button>
            </div>
            <!-- END modal footer -->
        </div>
    </div>
</div>
<!-- END delete user confirmation modal -->

</body>
</html>
