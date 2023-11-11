<?php

include "db_connect.inc.php";


function Login($userName, $password)
{
//    $UserName = $_POST["UserName"];
//    $Password = $_POST["Password"];

//    $con = new mysqli("", "root", "", "slap");
    global $con;
    if ($con->connect_error)
    {
        exit("Connection error occured");
    }
        $ps = $con->prepare("SELECT userName, password FROM user WHERE userName = ? AND password = SHA2(?, 256)");
        $ps->bind_param("ss", $_POST["userName"], $_POST["password"]);
        $ps->execute();
        $ps->bind_result($userName, $password);
        $ps->store_result();
        if ($ps->num_rows == 1)
        {
            $_SESSION["login"]="1";
            echo "Good day, $userName!<br>";
            echo "<br>";
            echo "<form action='index.php' method='post'>";
            echo "<input type='hidden' hidden='hidden' name='UserName' value='$userName'>";
            echo "<input type='submit' value='Check your Slap balance'>";
        }
        else
            echo "<a href='login.php'>Login failed</a>";

}






//function Login($UserName, $Password)
//{
//    $UserName = $_POST["UserName"];
//    $Password = $_POST["Password"];
//    $con = new mysqli("", "root", "", "slap");
//    $sql = "SELECT UserName, Password FROM user WHERE UserName = $UserName AND Password = $Password";
//    $res = $con->query($sql);
//    $dsatz = $res->fetch_assoc();
//    if ($con->affected_rows = 0)
//    {
//        echo "<a href='login.php'>Login fehlgeschlagen</a>";
//    }
//    else
//    {
//        echo "Willkommen $UserName!<br>";
//            echo "<br>";
//            echo "<form action='transaction.php' method='post'>";
//            echo "<input type='hidden' hidden='hidden' value='$UserName'";
//            echo "<input type='submit' value='Zum Schellenkonto'>";
//    }
//    $res->close();
//    $con->close();
//}




function Register($userName, $password)
{
    global $con;
    $ps = $con->prepare("INSERT INTO user(userName, password)VALUES (?, SHA2 (?, 256))");
    $ps->bind_param("ss", $_POST["newUserName"], $_POST["newPassword"]);
    $ps->execute();
    if ($ps->affected_rows > 0)
    {
        echo "Hello there, $userName!";
    }
    else
    {
        echo "<a href='login.php'>Whoopsie! please try again.</a>";
    }
}


function EigenerKontostand($userID)
{
    global $con;
    $sql = "SELECT SUM(slaps) AS Kontostand FROM transaction WHERE userIDSlapTake = '$userID'";
    $res = $con->query($sql);
    $dsatz = $res->fetch_assoc();
//    echo $dsatz["Kontostand"];
    $kontostand = $dsatz["Kontostand"];
    return $kontostand;
}



function UserWahl()
{
    global $con;
    $sql = "SELECT userName, userID FROM user";
    $res = $con->query($sql);
    while ($dsatz = $res->fetch_assoc())
    {
?><option name="userIDSlapTake" value="<?php echo $dsatz["userID"]?>"><?php echo htmlentities($dsatz["userName"]);
        echo " (";

        $userID = $dsatz["userID"];
        $kontostand = EigenerKontostand($userID);
        echo $kontostand;
        echo ")";
        ?></option><?php
    }

    $res->close();
    $con->close();
}

function transaction(string $operator, int $slaps, string $comment, int $userIDSlapGive, int $userIDSlapTake)
{
    $victimBalance = EigenerKontostand($userIDSlapTake);
    $contingent = getContingent($userIDSlapGive);
    $slapsLeft = $contingent - $slaps;
    if ($slaps > $contingent OR $slaps <= 0)
    {
        exit("Fuck You!");
    }
    if ($operator === "Payout")
    {
        if ($slaps > $victimBalance)
        {
            exit("You cant slap someone into negative Balance!");
        }
        else
        {
            $slaps = $slaps * -1;
        }
    }
    global $con;
        $ps = $con->prepare("INSERT INTO transaction (operator, slaps, comment, userIDSlapGive, userIDSlapTake) VALUES(?, ?, ?, ?, ?)");
//    $ps = $con->prepare("INSERT INTO transaction (Operator, Slaps, Comment, UserSlapGive, UserSlapTake) VALUES ?, ?, ?, ?, ?");
    $ps->bind_param("sisii", $operator, $slaps, $comment, $userIDSlapGive, $userIDSlapTake);
    $ps->execute();
    if ($ps->affected_rows > 0)
    {
        echo "Transaction done.<br><br>";
    }
    else
    {
        echo "An error occured, blyad!";
    }

    updateContingent($slapsLeft, $userIDSlapGive);

//    $ps->close();
//    $con->close();




}

function userSlapTakeDefinition($userIDSlapTake)
{
    global $con;
    $res = $con->query("SELECT userName FROM user WHERE userID = '$userIDSlapTake'");
    $userSlapTake = $res->fetch_array();
    return htmlentities($userSlapTake[0]);
}

function initSession() {
    if (session_status() != PHP_SESSION_ACTIVE) {
        session_start();
    }
}

function setSessionUserName(string $UserName) {
    initSession();
    $_SESSION['userName'] = htmlentities($UserName);
}

function getSessionUserName() : string|null {
    if (session_status() === PHP_SESSION_ACTIVE) {
        return htmlentities($_SESSION['userName']);
    }

    return null;
}

function setSessionUserID(int $userID) {
    initSession();
    $_SESSION['userID'] = $userID;
}

function getSessionUserID() : int|null {
    if (session_status() === PHP_SESSION_ACTIVE) {
        return $_SESSION['userID'];
    }

    return null;
}

function getUserID($userName): int
{
    global $con;
    $res = $con->query("SELECT userID FROM user WHERE userName = '$userName'");
    $userID = $res->fetch_array();
    return $userID[0];
}

function getContingent($userID): int
{
    global $con;
    $res = $con->query("SELECT contingent FROM user WHERE userID = '$userID'");
    $contingent = $res->fetch_array();
    return $contingent[0];
}

function getUserRole($userID)
{
    global $con;
    $res = $con->query("SELECT userRole, tempUserRole FROM user WHERE userID = '$userID'");
    $userRole = $res->fetch_row();
    return $userRole;
}



function getTempUserRole($userID): int
{
    global $con;
    $res = $con->query("SELECT tempUserRole FROM user WHERE userID = '$userID'");
    $tempUserRole = $res->fetch_array();
    return $tempUserRole[0];
}



function formularOperatorAdding(int $userRole, int $tempUserRole): string
{

    $adding = match (true)
    {
        $userRole === 1 => "<option value='Payout'> Execute that Fucker</option>",

        $userRole === 2 => "<option value='Payout'> Execute that Fucker</option>",

        $tempUserRole === 2 => "<option value='Payout'> Execute that Fucker</option>",

        default => "",
    };
    return $adding;


}

function updateContingent(int $slapsLeft,int $userIDSlapGive)
{
    global $con;
    $con->query("UPDATE user SET contingent = '$slapsLeft' WHERE userID = '$userIDSlapGive'");
    $con->close();
}

function getUserName($userID)
{
    global $con;
    $result = $con->query("SELECT userName FROM user WHERE userID = '$userID'");
    $userName = $result->fetch_array();
    return htmlentities($userName[0]);
}

function fetchNewsPosts()
{
    global $con;
    $sql = "SELECT * FROM news ORDER BY date DESC ";
    $result = $con->query($sql);
    while ($dsatz = $result->fetch_assoc())
    {

        $newsID = $dsatz["newsID"];
        $userID = $dsatz["userID"];
        $title = $dsatz["title"];
        $content = $dsatz["content"];
        $postDate = $dsatz["date"];
        $postDate = new DateTime($postDate);
        $userName = getUserName($userID);


        echo "<table class='newstable'>";

        echo "<tr>";
                echo "<td style='width: 10%'># $newsID</td>";
//                echo " by <a href='.profile.php?profileID=$userID'>$userName</a></td>";
                echo "<td style='font-size: 22px' 'text-align: center' 'width: 50%'>$title</td>";
                echo "<td style='width: 30%'>" . $postDate->format("D, d M Y H:i:s") . "</td>";
        echo "</tr>";
        echo "<tr style='min-height: 50px'><td style=text-align: 'center' 'width: 80%' colspan='4'>$content</td></tr>";




        echo "</table><br><br><br>";





    }
}


function newPostSend($userID, $title, $content)
{
    global $con;
    $ps = $con->prepare("INSERT INTO news (userID, title, content) VALUES(?, ?, ?)");
    $ps->bind_param("iss", $userID, $title, $content);
    $ps->execute();
    if ($ps->affected_rows == 1)
    {
        echo "Upload was a fucking success";
    }
}

function fetchLatestDeposit()
{
    global $con;
    $sql = "SELECT * FROM transaction WHERE operator = 'Deposit' ORDER BY date DESC";
    $result = $con->query($sql);
    $latestDeposit = $result->fetch_array();
    $dateOfDeposit = $latestDeposit[6];
    $dateOfDeposit = new DateTime($dateOfDeposit);
    $slapGiveName = getUserName($latestDeposit[4]);
    $slapTakeName = getUserName($latestDeposit[5]);


if (!empty($latestDeposit)) {

    echo $dateOfDeposit->format("D, d M Y H:i:s") . "<br>";
    echo "Transaction Nr. " . $latestDeposit[0] . "<br>" . "<a href='profile.php?profileID=$latestDeposit[4]'>$slapGiveName</a>" . " deposits " . $latestDeposit[2] . " Slaps to " . "<a href='profile.php?profileID=$latestDeposit[5]'>$slapTakeName</a>";
    }
    else
    {
        echo "No deposits yet? dafuq are you waiting for?";
    }

}

function fetchLatestWithdrawal()
{
    global $con;
    $sql = "SELECT * FROM transaction WHERE operator = 'Payout' ORDER BY date DESC";
    $result = $con->query($sql);
    $latestDeposit = $result->fetch_array();
    $dateOfDeposit = $latestDeposit[6];
    $dateOfDeposit = new DateTime($dateOfDeposit);
    $slapGiveName = getUserName($latestDeposit[4]);
    $slapTakeName = getUserName($latestDeposit[5]);
    $slaps = $latestDeposit[2] * -1;

    if (!empty($latestDeposit)) {
        echo $dateOfDeposit->format("D, d M Y H:i:s") . "<br>";
        echo "Transaction Nr. " . $latestDeposit[0] . "<br>" . "<a href='profile.php?profileID=$latestDeposit[4]'>$slapGiveName</a>" . " slapped " . "<a href='profile.php?profileID=$latestDeposit[5]'>$slapTakeName</a>" . " " . $slaps . " times";
    }
    else
    {
        echo "No Payouts yet, jesus, slap each other already!";
    }

}

function fetchLatestPersonalDeposit($userID)
{
    global $con;
    $sql = "SELECT * FROM transaction WHERE operator = 'Deposit' AND userIDSlapGive = '$userID' ORDER BY date DESC";
    $result = $con->query($sql);
    $latestDeposit = $result->fetch_array();
    $dateOfDeposit = $latestDeposit[6];
    $dateOfDeposit = new DateTime($dateOfDeposit);
    $slapGiveName = getUserName($latestDeposit[4]);
    $slapTakeName = getUserName($latestDeposit[5]);

    if (!empty($latestDeposit))
    {
        echo $dateOfDeposit->format("D, d M Y H:i:s") . "<br>";
        echo "Transaction Nr. " . $latestDeposit[0] . "<br>" . "You" . " depositted " . $latestDeposit[2] . " Slaps to " . "<a href='profile.php?profileID=$latestDeposit[5]'>$slapTakeName</a>";
    }
    else
    {
        echo "No deposit of you yet, change it!";
    }
}

function fetchLatestPersonalWithdrawal($userID)
{
    global $con;
    $sql = "SELECT * FROM transaction WHERE operator = 'Payout' AND userIDSlapGive = '$userID' ORDER BY date DESC";
    $result = $con->query($sql);
    $latestDeposit = $result->fetch_array();
    $dateOfDeposit = $latestDeposit[6];
    $dateOfDeposit = new DateTime($dateOfDeposit);
    $slapGiveName = getUserName($latestDeposit[4]);
    $slapTakeName = getUserName($latestDeposit[5]);
    $slaps = $latestDeposit[2] * -1;

    if (!empty($latestDeposit))
    {

        echo $dateOfDeposit->format("D, d M Y H:i:s") . "<br>";
        echo "Transaction Nr. " . $latestDeposit[0] . "<br>" . "You" . " slapped " . "<a href='profile.php?profileID=$latestDeposit[5]'>$slapTakeName</a>" . " " . $slaps . " times";
    }
    else
    {
        echo "You didnt slap anyone yet :/";
    }


}

function getBirthdaysForIndex()
{
    global $con;
    $sql = "SELECT userID, userName, birthday, hideAge FROM user WHERE DAY(birthday) = DAY(CURRENT_DATE()) AND MONTH(birthday) = MONTH(CURRENT_DATE())";
    $result = $con->query($sql);

    // by michl
    //return $result->fetch_all(MYSQLI_ASSOC);

    if ($result->num_rows === 0) {
        echo "No birthdays today, sucker";
    }
    ?>
    <ul>
        <?php
    while ($dsatz = $result->fetch_assoc())
    {
        $userID = $dsatz["userID"];
        $userName = htmlentities($dsatz["userName"]);
        $hideAge = $dsatz["hideAge"];
        $age = getUserAge($userID);
        if ($hideAge == 1) {

            echo "- <a href='profile.php?profileID=$userID'>$userName</a> turns...uhm, old enough i guess. Happy birthday retard!<br><br>";

        }
        else {

            echo "- <a href='profile.php?profileID=$userID'>$userName</a> turns $age today! Happy birthday scumbag!<br><br>";

        }
    }
    ?>
    </ul>
    <?php
}


//function getUserAge($userID)
//{
//    global $con;
//    $sql = "SELECT birthday FROM user WHERE userID = '$userID'";
//    $result = $con->query($sql);
//    $birthday = $result->fetch_array();
//    $birthday = $birthday[0];
//
//
//    date_default_timezone_set("Europe/Berlin");
//    $dateOfBirth = new DateTime($birthday);
//    $currentDate = new DateTime(date("Y") . "-" . date("m") . "-" . date("d"));
//    $interval = $currentDate->diff($dateOfBirth);
//
//    $age = $interval->format("%Y");
//    return $age;
//
//
//
//}


function getSetting(string $key)
{
    global $con;

    $sql = "SELECT `value` FROM settings WHERE `key` = '$key'";
    $result = $con->query($sql);

    if ($result->num_rows) {
        return $result->fetch_column(0);
    }

    return null;
}

function html_header($title = 'Ze Slapbank'): void
{
    include __DIR__ . '/layout/header.php';
}

function html_footer(): void
{
    include __DIR__ . '/layout/footer.php';
}

function userNextToLogout($profileName = 'Unknown'): void
{
    include __DIR__ . '/layout/header.php';
}

function getUserAge($userID)
{
    global $con;
    $sql = "SELECT TIMESTAMPDIFF (YEAR, birthday, CURDATE()) AS age FROM user WHERE userID = '$userID'";
    $result = $con->query($sql);
    $age = $result->fetch_assoc();
    return $age["age"];

}

function getUserProfileText($userID)
{
    global $con;
    $sql = "SELECT profileText FROM user WHERE userID = '$userID'";
    $result = $con->query($sql);
    $profileText = $result->fetch_assoc();
    return htmlentities($profileText["profileText"]);
}

function hideAge($userID)
{
    global $con;
    $sql = "SELECT hideAge FROM user WHERE userID = '$userID'";
    $result = $con->query($sql);
    $hideAge = $result->fetch_assoc();
    return $hideAge["hideAge"];
}

function getPersonalStatistics($userID)
{
    $balance = EigenerKontostand($userID);


    global $con;
    $sql = "SELECT SUM(slaps) AS slapsGiven FROM transaction WHERE userIDSlapGive = '$userID' AND operator = 'Payout'";
    $result = $con->query($sql);
    $slapsGiven = $result->fetch_assoc();

    $slapsGiven = $slapsGiven["slapsGiven"];
    $slapsGiven = $slapsGiven * -1;

    $sql = "SELECT COUNT(*) FROM transaction WHERE userIDSlapGive = '$userID' AND operator = 'Payout'";
    $result = $con->query($sql);
    $amountOfPayouts = $result->fetch_column(0);


    $sql = "SELECT SUM(slaps) AS slapsGifted FROM transaction WHERE userIDSlapGive = '$userID' AND operator = 'Deposit'";
    $result = $con->query($sql);
    $slapsDeposited = $result->fetch_assoc();
    $slapsDeposited = $slapsDeposited["slapsGifted"];

    $sql = "SELECT COUNT(*) FROM transaction WHERE userIDSlapGive = '$userID' AND operator = 'Deposit'";
    $result = $con->query($sql);
    $amountOfDeposits = $result->fetch_column(0);


//    switch (true){
//        case $slapsDeposited > 400 == $titleForDeposits = "Generous Gifter";
//        case $slapsDeposited > 600 == $titleForDeposits = "";
//        case $amountOfDeposits > 10 == $titleForAmountOfDeposits = "";
//        case $amountOfDeposits > 50 == $titleForAmountOfDeposits = "";
//        case $slapsGiven > 50 == $titleForGivenSlaps = "Slaps like a Kid";
//        case $slapsGiven > 50 == $titleForGivenSlaps = "";
//        case $amountOfPayouts > 10 == $titleForAmountOfPayouts = "fresh meat, needs beating";
//        case $amountOfPayouts > 50 == $titleForAmountOfPayouts = "";
//        case $balance > 100 == $titleForBalance = "Hoarder of Slaps";
//        case $balance > 200 == $titleForBalance = "The Jew of Slaps";
//    }
//    http://localhost/slap/profile.php?profileID=8

    echo "Slaps deposited<br>" . $slapsDeposited . "<br>amount of deposits<br>" . $amountOfDeposits . "<br>slaps given<br>" . $slapsGiven . "<br>amount of payouts<br>" . $amountOfPayouts . "<br>balance<br>" . $balance;

}

function getUserTitle($userID)
{
    global $con;
    $sql = "SELECT userTitle FROM user WHERE userID = '$userID'";
    $result = $con->query($sql);
    $userTitle = $result->fetch_assoc();
    return $userTitle["userTitle"];
}

function updateUserName($userID, $newUserName, $hideAge, $newTitle)
{
    global $con;
    $ps = $con->prepare("UPDATE user SET username = ?, hideage = ?, userTitle = ? WHERE userID = ?");
    $ps->bind_param("sisi", $newUserName, $hideAge, $newTitle, $userID);
    $ps->execute();
}

function updateProfileText($userID, $newProfileText)
{
    global $con;
    $ps = $con->prepare("UPDATE user SET profileText = ? WHERE userID = ?");
    $ps->bind_param("si", $newProfileText, $userID);
    $ps->execute();
}

function getUserMail($userID)
{
    global $con;
    $sql = "SELECT email FROM user WHERE userID = '$userID'";
    $result = $con->query($sql);
    $userMail = $result->fetch_assoc();
    return $userMail["email"];

}

function updateUserMail($userID, $newUserMail)
{
    global $con;
    $ps = $con->prepare("UPDATE user set email = ? WHERE userID = ?");
    $ps->bind_param("si", $newUserMail, $userID);
    $ps->execute();
}

function getUserBirthday($userID)
{
    global $con;
    $sql = "SELECT birthday FROM user WHERE userID = '$userID'";
    $result = $con->query($sql);
    $userBirthday = $result->fetch_assoc();
    $userBirthday = $userBirthday["birthday"];
    $userBirthday = new DateTime($userBirthday);
    $userBirthday->format("d/m/y");
    return $userBirthday;
}

function updateUserBirthday($userID, $newUserBirthday)
{
    global $con;
    $ps = $con->prepare("UPDATE user set birthday = ? WHERE userID = ?");
    $ps->bind_param("si", $newUserBirthday, $userID);
    $ps->execute();
}


function getAvailableUserTitlesSelectOptions($userID, $actualTitle)
{
    global $con;

    $balance = EigenerKontostand($userID);

    $sql = "SELECT SUM(slaps) AS slapsGiven FROM transaction WHERE userIDSlapGive = '$userID' AND operator = 'Payout'";
    $result = $con->query($sql);
    $slapsGiven = $result->fetch_assoc();

    $slapsGiven = $slapsGiven["slapsGiven"];
    $slapsGiven = $slapsGiven * -1;

    $sql = "SELECT COUNT(*) FROM transaction WHERE userIDSlapGive = '$userID' AND operator = 'Payout'";
    $result = $con->query($sql);
    $amountOfPayouts = $result->fetch_column(0);

    $sql = "SELECT SUM(slaps) AS slapsGifted FROM transaction WHERE userIDSlapGive = '$userID' AND operator = 'Deposit'";
    $result = $con->query($sql);
    $slapsDeposited = $result->fetch_assoc();
    $slapsDeposited = $slapsDeposited["slapsGifted"];

    $sql = "SELECT COUNT(*) FROM transaction WHERE userIDSlapGive = '$userID' AND operator = 'Deposit'";
    $result = $con->query($sql);
    $amountOfDeposits = $result->fetch_column(0);

    $titles = [
        "slapsDeposited" => [
            "pathetic victim" => 0,
            "fresh meat, needs beating" => 10,
            "Slaps like a Kid" => 40,
            "Hoarder of Slaps" => 100,
            "The Jew of Slaps" => 200,
            "Generous Gifter" => 400,
            "The Michael Moore of Slaps" => 10000
        ],
        "slapsGiven" => [

        ],
        "amountOfPayouts" => [

        ],
        "amountOfDeposits" => [

        ],
        "balance" => [

        ]
    ];

    $output = '';
    $hasSelected = false;

    foreach ($titles["slapsDeposited"] as $title => $neededDepositedSlaps) {
        if ($slapsDeposited >= $neededDepositedSlaps) {
            if ($title === $actualTitle) {
                $output .= "<option selected>$title</option>";
                $hasSelected = true;
            } else {
                $output .= "<option>$title</option>";
            }
        }
    }

    foreach ($titles["slapsGiven"] as $title => $neededGivenSlaps) {
        if ($slapsGiven >= $neededGivenSlaps) {
            if ($title === $actualTitle) {
                $output .= "<option selected>$title</option>";
                $hasSelected = true;
            } else {
                $output .= "<option>$title</option>";
            }
        }
    }

    foreach ($titles["amountOfPayouts"] as $title => $neededAmountOfPayouts) {
        if ($amountOfPayouts >= $neededAmountOfPayouts) {
            if ($title === $actualTitle) {
                $output .= "<option selected>$title</option>";
                $hasSelected = true;
            } else {
                $output .= "<option>$title</option>";
            }
        }
    }

    foreach ($titles["amountOfDeposits"] as $title => $neededAmountOfDeposits) {
        if ($amountOfDeposits >= $neededAmountOfDeposits) {
            if ($title === $actualTitle) {
                $output .= "<option selected>$title</option>";
                $hasSelected = true;
            } else {
                $output .= "<option>$title</option>";
            }
        }
    }

    foreach ($titles["balance"] as $title => $minimumBalance) {
        if ($balance >= $minimumBalance) {
            if ($title === $actualTitle) {
                $output .= "<option selected>$title</option>";
                $hasSelected = true;
            } else {
                $output .= "<option>$title</option>";
            }
        }
    }

    if ($hasSelected) {
        $output = "<option selected></option>" . $output;
    } else {
        $output = "<option></option>" . $output;
    }

    echo $output;
}

function updatePassword($oldPassword, $newPassword, $userID)
{
    global $con;
    $sql = "SELECT password FROM user WHERE userID = '$userID'";
    $result = $con->query($sql);
    $password = $result->fetch_assoc();
    $password = $password["password"];
    if ($password === $oldPassword)
    {
        $ps = $con->prepare("UPDATE user SET password = SHA2(?, 256) WHERE userID = ?");
        $ps->bind_param("si", $newPassword, $userID);
        $ps->execute();
    }
    else
    {
        echo "Failed to update Password";
    }

}


function calloutRandomSlapper($userID, $userRole)
{
    global $con;
    $sql = "SELECT userRole FROM user WHERE userID = '$userID'";
    $roleFromDB = $con->query($sql);
    $roleFromDB = $roleFromDB->fetch_column(0);
    if ($roleFromDB == $userRole)
    {
        $sql = "UPDATE user SET tempUserRole=2
WHERE NOT (DAY(birthday) = DAY(CURRENT_DATE()) AND MONTH(birthday) = MONTH(CURRENT_DATE()))
AND userRole = 3
ORDER BY RAND()
LIMIT 1";
        $con->query($sql);
    }
}

function getAdminMembers()
{
    global $con;
    $sql = "SELECT username, userID FROM user WHERE userRole = '1'";
    $result = $con->query($sql);
    while ($dsatz = $result->fetch_assoc())
    {
        $name = htmlentities($dsatz["username"]);
        $userID = $dsatz["userID"];
        echo "<li><a href='profile.php?profileID=$userID'> $name </a></li>";
    }
}

function getSlapperMembers()
{
    global $con;
    $sql = "SELECT username, userID FROM user WHERE userRole = '2'";
    $result = $con->query($sql);
    while ($dsatz = $result->fetch_assoc())
    {
        $name = htmlentities($dsatz["username"]);
        $userID = $dsatz["userID"];
        echo "<li><a href='profile.php?profileID=$userID'> $name </a></li>";
    }
}

function getRegularMembers()
{
    global $con;
    $sql = "SELECT username, userID FROM user WHERE userRole = '3'";
    $result = $con->query($sql);
    while ($dsatz = $result->fetch_assoc())
    {
        $name = htmlentities($dsatz["username"]);
        $userID = $dsatz["userID"];
        echo "<li><a href='profile.php?profileID=$userID'> $name </a></li>";
    }
}

function getTempSlapperMembers()
{
    global $con;
    $sql = "SELECT username, userID FROM user WHERE tempUserRole = '2'";
    $result = $con->query($sql);
    while ($dsatz = $result->fetch_assoc())
    {
        $name = htmlentities($dsatz["username"]);
        $userID = $dsatz["userID"];
        echo "<li><a href='profile.php?profileID=$userID'> $name </a></li>";
    }
}



function getLastTransactions($page = 0)
{
    $limit = 20;
    $offset = 20;

    $transactions = [
        "currentPage" => null,
        "previousPage" => null,
        "nextPage" => null,
        "dataRows" => []
    ];

    global $con;

    $sql = "SELECT COUNT(id) FROM transaction";
    $result = $con->query($sql);
    $countOfTransactions = $result->fetch_column(0);
    $maxPage = floor($countOfTransactions / 20);

    if ($page >= 0 && $page <= $maxPage) {
        $current_offset = $page * $offset;

        $sql = "SELECT * FROM transaction ORDER BY `date` DESC LIMIT $limit OFFSET $current_offset";
        $result = $con->query($sql);

        $transactions['currentPage'] = $page;
        $transactions['previousPage'] = $page > 0 ? $page -1 : null;
        $transactions['nextPage'] = $page < $maxPage ? $page +1 : null;

        while ($dsatz = $result->fetch_assoc())
        {
            $operator = $dsatz["operator"];
            $slaps = $dsatz["slaps"];
            if ($operator === "Payout") {
                $slaps = $slaps * -1;
            }

            $userIDSlapGive = $dsatz["userIDSlapGive"];
            $userNameSlapGive = getUserName($userIDSlapGive);

            $userIDSlapTake = $dsatz["userIDSlapTake"];
            $userNameSlapTake = getUserName($userIDSlapTake);

            $date = $dsatz["date"];
            $date = new DateTime($date);
            $formattedDate = $date->format("d.m.Y H:i:s");

            $transactions['dataRows'][] = [
                "id" => $dsatz["id"],
                "operator" => $operator,
                "slaps" => $slaps,
                "comment" => $dsatz["comment"],
                "userNameSlapGive" => $userNameSlapGive,
                "userNameSlapTake" => $userNameSlapTake,
                "userIDSlapGive" => $userIDSlapGive,
                "userIDSlapTake" => $userIDSlapTake,
                "formattedDate" => $formattedDate
            ];
        }
    }

    return $transactions;
}

function checkSetupBirthday($userID)
{
    global $con;
    $sql = "SELECT birthday FROM user WHERE userID = '$userID'";
    $result = $con->query($sql);
    $birthday = $result->fetch_column(0);
    return $birthday;
}


function getLatestMember()
{
    global $con;
    $sql = "SELECT MAX(userID) FROM user";
    $result = $con->query($sql);
    $latestUser = $result->fetch_column(0);
    return htmlentities($latestUser);


}

?>











