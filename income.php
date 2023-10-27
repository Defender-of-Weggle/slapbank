<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <title>Income Blyad</title>
</head>
<body>
<?php
include "db_connect.inc.php";
date_default_timezone_set('Europe/Berlin');
$timeStamp = date('d M Y H:i:s');


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
while ($dsatz = $res->fetch_assoc())
{
    $logParagraph = $timeStamp . " ID: " . $dsatz["userID"] . " Username: " . $dsatz["userName"] . " Contingent old to new: " . $dsatz["contingent"] . " => ";

    $userID = $dsatz["userID"];
    $update = match (true)
    {
        $dsatz["contingent"]>30 => $update = $dsatz["contingent"] + 3,

        $dsatz["contingent"]<=30 => $update = $dsatz["contingent"] + 10,

        default => $update = $dsatz["contingent"] + 5

    };
    $logParagraph .= $update . "\n";
    echo $logParagraph . "<br>";
    $logFile = fopen("incomelog.txt", "a") or die("Unable to open File, blyad..");
    fwrite($logFile, $logParagraph);
    fclose($logFile);

    $con->query("UPDATE user SET contingent = '$update' WHERE userID = '$userID'");
}





?>
</body>
</html>