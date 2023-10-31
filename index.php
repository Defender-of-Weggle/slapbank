<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <title>Overview</title>
    <link rel="stylesheet" href="stylesheet.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<?php
include "functions.inc.php";
include "layout.php";
initSession();
$userID = getSessionUserID() ?? "";

if(!isset($_SESSION["login"]))
{
    echo "<br><br>Fuck off, log in!<br><br><form action='login.php'><input type='submit' value='Log in, Dipshit!'>";
    exit();
}

$userName = getSessionUserName() ?? "";
$contingent = getContingent($userID) ?? "n.a.";
$balance = EigenerKontostand($userID) ?? "n.a.";



?>



<div class="lastTransactions">
    Latest Transactions:<br><br>
    Deposit: <br>
<?php fetchLatestDeposit() ?>
    <br><br>
    Withdrawal: <br>
    <?php fetchLatestWithdrawal() ?>

</div>

<h1> Your own Stats:</h1><br>
<p>Your limit for today is <?php echo $contingent; ?></p>
<p>Your Slap balance is: <?php echo $balance; ?></p>

latest deposit:<br>
<?php @fetchLatestPersonalDeposit($userID) ?><br><br>
Latest Withdrawal:<br>
<?php @fetchLatestPersonalWithdrawal($userID) ?>
<br><br>



<?php
?>
</body>


</html>
