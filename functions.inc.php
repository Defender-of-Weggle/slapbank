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
            echo "<form action='main.php' method='post'>";
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
//            echo "<form action='main.php' method='post'>";
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
    echo $dsatz["Kontostand"];
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
//        $username = $dsatz["userName"];
        $userID = $dsatz["userID"];
        EigenerKontostand($userID);
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
            exit("You cant slap someone into negative Balance!");
        }
        $slaps = $slaps * -1;
    }
    global $con;
        $ps = $con->prepare("INSERT INTO transaction (operator, slaps, comment, userIDSlapGive, userIDSlapTake) VALUES(?, ?, ?, ?, ?)");
//    $ps = $con->prepare("INSERT INTO transaction (Operator, Slaps, Comment, UserSlapGive, UserSlapTake) VALUES ?, ?, ?, ?, ?");
    $ps->bind_param("sisii", $operator, $slaps, $comment, $userIDSlapGive, $userIDSlapTake);
    $ps->execute();
    if ($ps->affected_rows > 0)
    {
        echo "Transaction done, nice.<br><br>";
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
    $res = $con->query("SELECT userRole FROM user WHERE userID = '$userID'");
    $userRole = $res->fetch_array();
    return $userRole[0];
}

function formularOperatorAdding(int $userRole): string
{

    if ($userRole === 1)
    {
        $adding = "";
    }
    elseif ($userRole === 2 || 3)
    {
        $adding = "<option value='Payout'> Execute that Fucker</option>";
    }

return $adding;
}

function updateContingent(int $slapsLeft,int $userIDSlapGive)
{
    global $con;
    $con->query("UPDATE user SET contingent = '$slapsLeft' WHERE userID = '$userIDSlapGive'");
    $con->close();
}

function fetchNewsPosts()
{
    global $con;
    $sql = "SELECT * FROM news";
    $result = $con->query($sql);
    while ($dsatz = $result->fetch_assoc())
    {
        $newsID = $dsatz["newsID"];
        $userID = $dsatz["userID"];
        $title = $dsatz["title"];
        $content = $dsatz["content"];
        $postDate = $dsatz["date"];
        $postDate = new DateTime($postDate);

        echo $newsID . $userID . $postDate->format("dD, d M Y H:i:s") . $title . $content;

    }
}


?>













