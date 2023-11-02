<?php
include "functions.inc.php";
html_header('News');

initSession();

if(!isset($_SESSION["login"]))
{
    echo "<br><br>Fuck off, log in!<br><br><form action='login.php'><input type='submit' value='Log in, Dipshit!'>";
    exit();
}

$userID = getSessionUserID();
$userName = getSessionUserName();
$userRole = getUserRole($userID);













html_footer();
?>