<?php
include 'functions.inc.php';
include "db_connect.inc.php";

html_header('Income Blyad');

date_default_timezone_set('Europe/Berlin');
$timeStamp = date('d M Y H:i:s');

global $con;
$con->query("UPDATE user SET tempUserRole=3;");


$counterFilePath = "counter.txt";
$counter = (file_exists($counterFilePath) ? file_get_contents($counterFilePath) : 0) + 1;
file_put_contents($counterFilePath, $counter);

$logDayPhrase = "__________________________________________ Day: " . $counter . " __________________________________________\n";
$logFile = fopen("incomelog.txt", "a") or die("Unable to open File, blyad!");
fwrite($logFile, $logDayPhrase);
fclose($logFile);


global $con;
$sql = "SELECT contingent, userName, userID FROM user";
$res = $con->query($sql);

$incomeLimitationThreshold = getSetting('incomeLimitationThreshold');
$incomeStandard = getSetting('incomeStandard');
$incomeAfterLimitation = getSetting('incomeAfterLimitation');

while ($dsatz = $res->fetch_assoc())
{
    $logParagraph = $timeStamp . " ID: " . $dsatz["userID"] . " Username: " . $dsatz["userName"] . " Contingent old to new: " . $dsatz["contingent"] . " => ";

    $userID = $dsatz["userID"];



    $update = match (true)
    {
        $dsatz["contingent"] > $incomeLimitationThreshold => $update = $incomeAfterLimitation,

        default => $update = $incomeStandard

    };
    $update = $update + $dsatz["contingent"];
    $logParagraph .= $update . "\n";
    echo $logParagraph . "<br>";
//    echo "incomelimitationthreshol = " .$incomeLimitationThreshold . "<br>" . "Incomeafterlimitation = " . $incomeAfterLimitation . "<br>" . "IncomeStandard = " . $incomeStandard . "<br>";
    $logFile = fopen("incomelog.txt", "a") or die("Unable to open File, blyad..");
    fwrite($logFile, $logParagraph);
    fclose($logFile);

    $con->query("UPDATE user SET contingent = '$update' WHERE userID = '$userID'");
}

global $con;
$sql = "SELECT userID, userName, birthday, tempUserRole FROM user WHERE DAY(birthday) = DAY(CURRENT_DATE()) AND MONTH(birthday) = MONTH(CURRENT_DATE())";
$result = $con->query($sql);


if ($result->num_rows === 0) {
    echo "No birthdays today, sucker";
}

while ($dsatz = $result->fetch_assoc()) {
    $userID = $dsatz["userID"];
    $userName = $dsatz["userName"];
    $age = getUserAge($userID);
    $contingent = getContingent($userID);
    $newContingent = $contingent + $age;
    $tempUserRole = $dsatz["tempUserRole"];


    $birthdayContingent = $timeStamp . " ID: " . $userID . " Username: " . $userName . " turned $age, the gifted contingent: " . $contingent . " => " . $newContingent;
//    echo $birthdayContingent . "<br>";
    $birthdayContingent .= "\n";

    $userID = $dsatz["userID"];
//    $birthdayContingent = $userName . "turned " . $age . " today, old contingent: " . $contingent . " new: " . $newContingent . "\n";
    $logFile = fopen("incomelog.txt", "a") or die("Unable to open File, blyad..");
    fwrite($logFile, $birthdayContingent);
    fclose($logFile);

//    echo $userName . " turned " . $age . " today, old contingent: " . $contingent . " new: " . $newContingent;

    $con->query("UPDATE user SET contingent = '$newContingent', tempUserRole = 2 WHERE userID = '$userID'");


    calloutRandomSlapper(1, 1);

}

html_footer();

?>