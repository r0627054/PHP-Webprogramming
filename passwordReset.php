<?php
if ((!isset($_REQUEST['username'])) || (!isset($_REQUEST['secret'])) ) {
    header('Location:'.'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) .'/' . 'index.php');
    exit();
}

include_once('page.php');
include_once('includes/secureInput.php');
include_once('database.php');

if (($_SERVER["REQUEST_METHOD"] == "POST" ) && (isset($_POST['username'])) && (isset($_POST['secret'])
        && (isset($_POST['password1']) && (isset($_POST['password2']))) ) ) {
    # check pwd is not empty.
    # check if pwd contains at least 8 character
    # check if pwd contains less then 20 character
    # check if pwd contains at least one number
    # check if pwd contains at least one letter
    # check if pwd contains at least one CAPS letter
    $error = "";
    if (empty($_POST['password1']) || empty($_POST['password2']) || $_POST['password1'] != $_POST['password2']) {
        $error = "Values must be the same!";
    } else {
        $pwd = trim_secure_input($_POST['password1']);
        if(strlen($pwd) >= 20 ) {
            $error .= "Password is too long! Not longer than 20 characters. " ;
        }
        if( strlen($pwd) <= 8 ) {
            $error .= "Password is too short! Use more than 8 characters. ";
        }
        if( !preg_match("#[0-9]+#", $pwd) ) {
            $error .= "Password must include at least one number! ";
        }
        if( !preg_match("#[a-z]+#", $pwd) ) {
            $error .= "Password must include at least one letter! ";
        }
        if( !preg_match("#[A-Z]+#", $pwd) ) {
            $error .= "Password must include at least one Capital letter! ";
        }
    }
    $dB = new Database();
    if(!($dB->checkPassReset(htmlspecialchars($_POST['username']), htmlspecialchars($_POST['secret'])  ) )) {
        $error .= "Something went wrong, you tried to hack me!";
    }

    if(empty($error)){
        $dB->changePasswordUser(htmlspecialchars($_POST['username']),$pwd);
        $dB->deletePassReset(htmlspecialchars($_POST['username']));
        header('Location:'.'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) .'/' . 'index.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<?php
$loginpage = new Page('passwordReset', 'Dries webapp | password reset', 'css/loginStylesheet.css');
$loginpage->displayHead();
?>
<body id="indexbody">
<div class="container-fluid">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" accept-charset="utf-8" id="login-form" >
        <h2 id="form-header">Change password</h2>
        <?php if (($_SERVER["REQUEST_METHOD"] == "POST" ) && !empty($error) ){ ?>
            <div class="alert alert-danger">
                <p><?php echo $error; ?></p>
            </div>
        <?php } ?>
        <div class="form-group">
            <label for="password1">Password:</label>
            <input type="password" class="form-control" name="password1" id="password1" autocomplete="off">
        </div>
        <div class="form-group">
            <label for="password2">confirm password:</label>
            <input type="password" class="form-control" id="password2" name="password2" autocomplete="off">
        </div>
        <input type="hidden" name="username" value="<?php echo $_REQUEST['username']; ?>">
        <input type="hidden" name="secret" value="<?php echo $_REQUEST['secret'] ?>">
        <button type="submit" class="btn btn-primary btn-lg btn-block">Sign in</button>
    </form>
</div>
</body>
</html>