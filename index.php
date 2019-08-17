<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once('authentication.php');
    include_once ('includes/secureInput.php');
    $error = authentication::loginUser(trim_secure_input($_POST["username"]),trim_secure_input($_POST["password"]), trim_secure_input($_POST['g-recaptcha-response']), trim_secure_input($_POST['loginUserToken']));
}
include_once('csrf.php');
$loginUsertoken = Csrf::generateToken('loginUser');
?>

<!DOCTYPE html>
<html lang="en">
<?php
include_once('page.php');
$loginpage = new Page('index', 'Dries webapp | index', 'css/loginStylesheet.css');
$loginpage->displayHead();
?>

<body id="indexbody">
<div class="container-fluid">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" accept-charset="utf-8" id="login-form" >
        <h2 id="form-header">Please sign in</h2>
        <?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($error) ){ ?>
            <div class="alert alert-danger">
                        <p><?php echo $error; ?></p>
            </div>
        <?php } ?>
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" class="form-control" name="username" id="username">
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" id="password" name="password" autocomplete="off">
        </div>
        <div class="form-group">
            <!-- replace KEY with the google recaptcha data-set key-->
            <div class="g-recaptcha" data-sitekey="KEY"></div>
        </div>
        <input type="hidden" name="loginUserToken" value="<?php echo $loginUsertoken;?>">
        <button type="submit" class="btn btn-primary btn-lg btn-block">Sign in</button>
    </form>
</div>
</body>
</html>