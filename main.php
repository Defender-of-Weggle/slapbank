<!DOCTYPE html>
<html lang="de" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <Title>Slap Balance</Title>
    <link rel="stylesheet" href="stylesheet.css">
</head>
<body>
<?php
include "functions.inc.php";
include "layout.php";

initSession();
$userID = getSessionUserID();

if(!isset($_SESSION["login"]))
{
    echo "Fuck off, log in!<br><br><form action='login.php'><input type='submit' value='Zum Login'>";
    exit();
}

?>
<h1>Slap Balance:
<?php EigenerKontostand(getSessionUserName());
?>
</h1>

<h2>
    Share the pain:
</h2>
<form action="main2.php" method="post">
<?php echo "<input type='hidden' name='UserName' value='". getSessionUserName() ."'>";?>
            <input type="hidden" name="DB">
    <p>
        <select name="UserSlapTake">
        <?php UserWahl();?>
        </select> Who you wanna slap?
    </p>
    <p><input name="Comment"> Reason?(Optional, sometimes you just have to)</p>
    <p><input name="Slaps"> Amount of Slaps</p>
    <p><select name="Operator">
            <option name="Einzahlen"> Deposit</option>
            <option name="Auszahlen"> Execute that Fucker</option>
        </select></p>
    <input type="submit" value="Slap it!"> <input type="reset">
</form>

<br><br>
<form action="logout.php">
    <input type="submit" value="Logout">
</form>
</body>
</html>