<?php
include "functions.inc.php";
html_header('Schellenkontostand');
//session_start ();

if(!isset($_SESSION["login"]))
{
    echo "<br><br>Fuck off, log in!<br><br><form action='login.php'><input type='submit' value='Log in, Dipshit!'>";
    exit();
}


$userName = htmlentities(getSessionUserName());



//print_R($_SESSION);
//print_R($_POST);
$userIDSlapTake = $_POST["userIDSlapTake"] ?? 'Unbekannter Nutzer';
$slaps = $_POST["slaps"] ?? 0;
$operator = $_POST["operator"] ?? "Deposit";

if (isset($_POST["DB"]))
{
    $operator = ($_POST["operator"]);
    $slaps = ($_POST["slaps"]);
    $comment = ($_POST["comment"]);
    $userID = getSessionUserID();
    $userIDSlapTake = ($_POST["userIDSlapTake"]);
    $userSlapTake = userSlapTakeDefinition($userIDSlapTake);
    transaction($_POST["operator"], $_POST["slaps"], $_POST["comment"], $userID, $userIDSlapTake);
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

<form action="transaction.php">
    <input type="hidden" hidden="hidden" value="<?php $userName?>">
    <input type="submit" value="Back to the Slapcave">
</form>


<br><br>
<!--<form action="logout.php">-->
<!--    <input type="submit" value="Logout">-->
<!--</form>-->

<?php
html_footer();