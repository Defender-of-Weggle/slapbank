<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <Title>Schellenkontostand</Title>
    <link rel="stylesheet" href="stylesheet.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<?php
include "functions.inc.php";
include "layout.php";
session_start ();
$userName = getSessionUserName();

//print_R($_SESSION);
//print_R($_POST);
$userSlapTake = $_POST["userSlapTake"] ?? 'Unbekannter Nutzer';
$slaps = $_POST["slaps"] ?? 0;
$operator = $_POST["operator"] ?? "Deposit";
if(!isset($_SESSION["login"]))
{
    echo "Fuck off, log in!<br><br><form action='login.php'><input type='submit' value='Log in, Dipshit!'>";
    exit();
}
if (isset($_POST["DB"]))
{
    transaction($_POST["operator"], $_POST["slaps"], $_POST["comment"], $userName, $userSlapTake);
    if ($operator === "Deposit")
    {
        echo "Thanks for the Deposit.<br><br>";
        echo "$userName surprises $userSlapTake with a deposit of $slaps Slaps.<br><br>";
    }
    else
    {
        echo "Thanks for your Payout<br><br>";
        echo "$userName slaps $userSlapTake $slaps time/s in the face.<br><br>";
    }
}
?>

<form action="main.php">
    <input type="hidden" hidden="hidden" value="<?php $userName?>">
    <input type="submit" value="Back to the Slapcave">
</form>


<br><br>
<!--<form action="logout.php">-->
<!--    <input type="submit" value="Logout">-->
<!--</form>-->

</body>
</html>