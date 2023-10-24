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

?>
</body>
</html>