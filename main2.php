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
$UserName = getSessionUserName();

//print_R($_SESSION);
//print_R($_POST);
$UserSlapTake = $_POST["UserSlapTake"] ?? 'Unbekannter Nutzer';
$Slaps = $_POST["Slaps"] ?? 0;
$Operator = $_POST["Operator"] ?? "Deposit";
if(!isset($_SESSION["login"]))
{
    echo "Fuck off, log in!<br><br><form action='login.php'><input type='submit' value='Log in, Dipshit!'>";
    exit();
}
if (isset($_POST["DB"]))
{
    transaction($_POST["Operator"], $_POST["Slaps"], $_POST["Comment"], $UserName, $UserSlapTake);
    if ($Operator === "Deposit")
    {
        echo "Thanks for the Deposit.<br>";
        echo "$UserName surprises $UserSlapTake with a deposit of $Slaps Slaps.<br>";
    }
    else
    {
        echo "Thanks for your Payout<br><br>";
        echo "$UserName slaps $UserSlapTake $Slaps time/s in the face.<br><br>";
    }
}
?>

<form action="main.php">
    <input type="hidden" hidden="hidden" value="<?php $UserName?>">
    <input type="submit" value="Back to the Slapcave">
</form>


<br><br>
<!--<form action="logout.php">-->
<!--    <input type="submit" value="Logout">-->
<!--</form>-->

</body>
</html>