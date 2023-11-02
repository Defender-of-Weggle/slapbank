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
        $ps = $con->prepare("SELECT userName, password FROM user WHERE userName = ? AND password = ?");
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
    $ps = $con->prepare("INSERT INTO user(userName, password)VALUES (?, ?)");
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
?><option name="userIDSlapTake" value="<?php echo $dsatz["userID"]?>"><?php echo $dsatz["userName"];
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
    if ($slaps > $contingent)
    {
        exit("Fuck you, your deposit limit for today is $contingent");
    }
    if ($operator === "Payout")
    {
        if ($slaps > $victimBalance)
        {
            exit("You cant slap someone into negative Balance!$victimBalance ");
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
    return $userSlapTake[0];
}

function initSession() {
    if (session_status() != PHP_SESSION_ACTIVE) {
        session_start();
    }
}

function setSessionUserName(string $UserName) {
    initSession();
    $_SESSION['userName'] = $UserName;
}

function getSessionUserName() : string|null {
    if (session_status() === PHP_SESSION_ACTIVE) {
        return $_SESSION['userName'];
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

function getUserRole($userID): int
{
    global $con;
    $res = $con->query("SELECT userRole, tempUserRole FROM user WHERE userID = '$userID'");
    $userRole = $res->fetch_array();
    return $userRole[0];
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
    return $userName[0];
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
                echo "<td style='width: 10%'>Post Nr.: $newsID </td>";
                echo "<td style='margin-left: 20px' 'width: 10%'>Autor: $userName </td>";
                echo "<td style='font-size: 22px' 'text-align: center' 'width: 50%'>$title</td>";
                echo "<td style='width: 30%'>" . $postDate->format("D, d M Y H:i:s") . "</td>";
        echo "</tr>";
        echo "<tr style='min-height: 100px'><td style=text-align: 'center' 'width: 80%' colspan='4'>$content</td></tr>";




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


    echo $dateOfDeposit->format("D, d M Y H:i:s") . "<br>";
    echo "Transaction Nr. " . $latestDeposit[0] . "<br>" . $slapGiveName . " deposits " . $latestDeposit[2] . " Slaps to " . $slapTakeName;
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


    echo $dateOfDeposit->format("D, d M Y H:i:s") . "<br>";
    echo "Transaction Nr. " . $latestDeposit[0] . "<br>" . $slapGiveName . " slapped " . $slapTakeName . " " . $slaps . " times";
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

    if (!empty($dateOfDeposit))
    {
        echo $dateOfDeposit->format("D, d M Y H:i:s") . "<br>";
        echo "Transaction Nr. " . $latestDeposit[0] . "<br>" . "You" . " depositted " . $latestDeposit[2] . " Slaps to " . $slapTakeName;
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
        echo "Transaction Nr. " . $latestDeposit[0] . "<br>" . "You" . " slapped " . $slapTakeName . " " . $slaps . " times";
    }
    else
    {
        echo "You didnt slap anyone yet :/";
    }


}

function getBirthdaysForIndex()
{
    global $con;
    $sql = "SELECT userID, userName, birthday FROM user WHERE DAY(birthday) = DAY(CURRENT_DATE()) AND MONTH(birthday) = MONTH(CURRENT_DATE())";
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
        $userName = $dsatz["userName"];
        $age = getUserAge($userID);

        echo "<li>";
        echo "$userName turns $age today! Happy birthday!<br>";
        echo "</li>";
    }
    ?>
    </ul>
    <?php
}


function getUserAge($userID)
{
    global $con;
    $sql = "SELECT birthday FROM user WHERE userID = '$userID'";
    $result = $con->query($sql);
    $birthday = $result->fetch_array();
    $birthday = $birthday[0];


    date_default_timezone_set("Europe/Berlin");
    $dateOfBirth = new DateTime($birthday);
    $currentDate = new DateTime(date("Y") . "-" . date("m") . "-" . date("d"));
    $interval = $currentDate->diff($dateOfBirth);

    $age = $interval->format("%Y");
    return $age;



}


function getSetting(string $key)
{
    global $con;

    $sql = "SELECT `value` FROM settings WHERE `key` = 'incomeLimitationThreshold'";
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

function userNextToLogout($userName = 'Unknown'): void
{
    include __DIR__ . '/layout/header.php';
}

?>












