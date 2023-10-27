<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <title>News</title>
    <link rel="stylesheet" href="stylesheet.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<?php
include "functions.inc.php";
include "layout.php";
initSession();
$userID = getSessionUserID();
$userName = getSessionUserName();
$userRole = getUserRole($userID);
if(!isset($_SESSION["login"]))
{
    echo "Fuck off, log in!<br><br><form action='login.php'><input type='submit' value='Zum Login'>";
    exit();
}


fetchNewsPosts();



?>
</body>
</html>