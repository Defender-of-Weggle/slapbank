<!DOCTYPE html>
<html lang="de">
<head>
    <link rel="stylesheet" href="stylesheet.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    session_start();
    include "functions.inc.php";

    ?>
    <meta charset="utf-8">
    <title>Login or Register</title>
</head>
<body>
<h2>Login:</h2>
<form action="login.php" method="post">
    <input name="Login" value="2" hidden="hidden">
    <p><input name="UserName"> Username</p>
    <p><input name="Password" type="password"> Password</p>
    <p><input type="submit" value="Login"> <input type="reset"></p>
</form>


<br>
<?php
if (isset($_POST["Login"]) && $_POST["UserName"] && $_POST["Password"])
{
    Login($_POST["UserName"], $_POST["Password"]);
    setSessionUserName($_POST["UserName"]);
    setSessionUserID(getUserID($_POST["UserName"]));
} else {
    echo "Login to proceed<br><br>";
}

?>
<br>


<h2>Registrierung</h2>
<form action="login.php" method="post">
    <input name="register" value="1" hidden="hidden">
    <p><input name="NewUserName"> Username</p>
    <p><input name="NewPassword" type="password"> Password</p>
    <p><input type="submit" value="Register"> <input type="reset"></p>
</form>
<br>

<?php


if (isset($_POST["register"]))
    Register($_POST["NewUserName"], $_POST["NewPassword"]);

?>
</body>
</html>