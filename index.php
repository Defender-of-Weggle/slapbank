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

//initSession();
if(!isset($_SESSION["login"]))
{
    echo "<br><br>Fuck off, log in!<br><br><form action='login.php'><input type='submit' value='Log in, Dipshit!'><br><br><br>
    \"this website is a slap bank account - when you want to reach out and virually bitch slap that special someone\"<br>
    Nex, 8th November 2023";
    exit();
}

$userID = getSessionUserID() ?? "";



$userName = getSessionUserName() ?? "";
$contingent = getContingent($userID) ?? "n.a.";
$balance = EigenerKontostand($userID) ?? "n.a.";
$latestMemberID = getLatestMember();
$latestMember = getUserName(getLatestMember());



?>



<div class="row">
    <div class="column">
    <p>Latest Transactions:</p>
    <p>Deposit: </p>
<?php @fetchLatestDeposit() ?>
    <br><br>
    <p>Withdrawal: </p>>
    <?php @fetchLatestWithdrawal() ?>

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
<div class="row">
        <div class="column">
            <h4>How this stupid thing works, more or less</h4>
            <ul><li>Everybody starts with a contingent of 25 Slaps</li>
                <li></li>
                <li><strong>The Roles</strong></li>
                <li>Default role - Deposit only</li>
                <li>Deposits use contingents</li>
                <li>On birthday will become a slapman for the day</li>
                <li>On birthday will get contingent added worth the age</li>
                <li> </li>
                    <li>Executive role - The "both is good" guy</li>
                <li>Deposits and execution of slaps uses contingent</li>
                <li>On birthday will get contingent added worth the age</li>
                <li></li><li><strong>General stuff</strong></li>
                <li>Server refreshes contingents everyday as followed:</li>
                <li>Your contingent will be increased by 10 every day</li>
                <li>When you reach a total of 30, it will shrink down to 3</li>
            </ul>

        </div>
        <div class="column">

        </div>

    <div class="column">
            Latest registration:<br><br>
        <a href="profile.php?profileID?=<?php $latestMemberID ?>"><?php echo $latestMember;?></a>

    </div>




</div>
<?php
html_footer();
