<?php
include "functions.inc.php";
html_header('Login or Register');
//session_start();
?>

<h2>Login:</h2>
<form action="login.php" method="post">
    <input name="login" value="2" hidden="hidden">
    <p><input name="userName"> Username</p>
    <p><input name="password" type="password"> Password</p>
    <p><input type="submit" value="Login"> <input type="reset"></p>
</form>


<br>
<?php
if (isset($_POST["login"]) && $_POST["userName"] && $_POST["password"])
{
    Login($_POST["userName"], $_POST["password"]);
    setSessionUserName($_POST["userName"]);
    setSessionUserID(getUserID($_POST["userName"]));
} else {
    echo "Login to proceed<br><br>";
}

?>
<br>


<h2>Registrierung</h2>
<form action="login.php" method="post">
    <input name="register" value="1" hidden="hidden">
    <p><input name="newUserName"> Username</p>
    <p><input name="newPassword" type="password"> Password</p>
    <p><input type="submit" value="Register"> <input type="reset"></p>
</form>
<br>

<?php


if (isset($_POST["register"]))
    Register($_POST["newUserName"], $_POST["newPassword"]);

html_footer();
?>