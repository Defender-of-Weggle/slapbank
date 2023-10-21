<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <Title>Schellenkontostand</Title>
    <link rel="stylesheet" href="stylesheet.css">
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
$Operator = $_POST["Operator"] ?? "Einzahlen";
if(!isset($_SESSION["login"]))
{
    echo "Fuck off, log in!<br><br><form action='login.php'><input type='submit' value='Log in, Dipshit!'>";
    exit();
}
if (isset($_POST["DB"]))
{
    transaction($_POST["Operator"], $_POST["Slaps"], $_POST["Comment"], $UserName, $UserSlapTake);
    if ($Operator === "Einzahlen")
    {
        echo "Thanks for the Deposit.<br>";
        echo "$UserName surprises $UserSlapTake with $Slaps Slaps, sweet.";
    }
    else
    {
        echo "Thanks for your Payout<br>";
        echo "$UserName slaps $UserSlapTake $Slaps time/s, neat.";
    }
}
?>

<form action="main.php">
    <input type="hidden" hidden="hidden" value="<?php $UserName?>">
    <input type="submit" value="Back to the Slapcave">
</form>


<br><br>
<form action="logout.php">
    <input type="submit" value="Logout">
</form>

</body>
</html>