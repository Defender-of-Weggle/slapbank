<!DOCTYPE html>
<html lang="de" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <Title>Slap Balance</Title>
    <link rel="stylesheet" href="stylesheet.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
    <p><label for="UserSlapTake">Choose your victim.</label></p>
        <select name="UserSlapTake">
        <?php UserWahl();?>
        </select>
    <br><br><p><label for="Comment">Reason?(Optional, sometimes you just have to)</label></p>
    <textarea name="Comment" rows="5" cols="20" placeholder="This sucker ate my cookie! MINE!"></textarea><br>
    <p><label for="Slaps">Amount of Slaps</label></p>
    <input name="Slaps" size="1"><br>
    <p><label for="Operator">Hitting or saving up?</label> </p>
    <p><select name="Operator">
            <option value="Deposit"> Deposit</option>
            <option value="Payout"> Execute that Fucker</option>
        </select></p><br>
    <input type="submit" value="Slap it!"> <input type="reset">
</form>
<br><br>

</form>
</body>
</html>