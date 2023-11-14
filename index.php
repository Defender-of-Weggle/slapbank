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


if (isset($_POST["login"]) && $_POST["userName"] && $_POST["password"]) {
    Login($_POST["userName"], $_POST["password"]);
    setSessionUserName($_POST["userName"]);
    setSessionUserID(getUserID($_POST["userName"]));
}


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
$latestMember = getUserName($latestMemberID);



?>



<div class="row">
    <div class="column">
    <p>Latest Transactions:</p>
    <p>Deposit: </p>
<?php @fetchLatestDeposit() ?>
    <br><br>
    <p>Withdrawal: </p>
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
                <li><strong style="color: red">The Roles</strong></li>
                <li><span style="color: red">Default role - Deposit only</span></li>
                <li>Deposits use contingents</li>

                <li> </li>
                <li><span style="color: red">Executive role - can deposit and slap</span></li>
                <li>Deposits and execution of slaps uses contingent</li>
                <li>On birthday will get contingent added worth the age</li>
                <li></li><li><strong style="color: red">General stuff</strong></li>
                <li><span style="color: red">New!</span> Daily Random Slapper(every 24 hours a new one) </li>
                <li>On birthday accounts will get contingent added worth their age</li>
                <li>Server refreshes contingents every day as followed:</li>
                <li>Your spending limit is 30 or lower: 10</li>
                <li>Your spending limit is Above 30: 3</li>
            </ul>

        </div>
        <div class="column">
            <h4>Rules</h4>
            <ul>
                <li>No multi accounts</li>
                <li>On purpose damn high set ages will be set to 1 digit once :)</li>
                <li>Birthday can only be set once</li>
            </ul>
        </div>

    <div class="column">
        <?php
        echo "Latest registration:<br><br>";
        echo "<a href='profile.php?profileID=$latestMemberID'>$latestMember</a>"
        ?>

    </div>




</div>
<?php
html_footer();
