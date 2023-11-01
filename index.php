<?php
include "functions.inc.php";

html_header('Overview');
?>
    <style>
        * {
            box-sizing: border-box;
        }
    </style>
<?php

initSession();

if(!isset($_SESSION["login"]))
{
    echo "<br><br>Fuck off, log in!<br><br><form action='login.php'><input type='submit' value='Log in, Dipshit!'>";
    exit();
}

$userID = getSessionUserID() ?? "";



$userName = getSessionUserName() ?? "";
$contingent = getContingent($userID) ?? "n.a.";
$balance = EigenerKontostand($userID) ?? "n.a.";



?>



<div class="row">
    <div class="column">
    <p>Latest Transactions:</p>
    <p>Deposit: </p>
<?php fetchLatestDeposit() ?>
    <br><br>
    <p>Withdrawal: </p>>
    <?php fetchLatestWithdrawal() ?>

</div>
<div class="column">
<h1> Your own Stats:</h1>
<p>Your limit for today is <?php echo $contingent; ?></p>
<p>Your Slap balance is: <?php echo $balance; ?></p>

latest deposit:<br>
<?php @fetchLatestPersonalDeposit($userID) ?><br><br>
Latest Withdrawal:<br>
<?php @fetchLatestPersonalWithdrawal($userID) ?>
<br><br>
</div>

<div class="column">
    <p>Birthdays:</p>
    <?php getBirthdaysForIndex(); ?>



    </div>
</div>

<?php
html_footer();
