<?php

include "db_connect.inc.php";


function Login($UserName, $Password)
{
//    $UserName = $_POST["UserName"];
//    $Password = $_POST["Password"];

//    $con = new mysqli("", "root", "", "slap");
    global $con;
    if ($con->connect_error)
    {
        exit("Connection error occured");
    }
        $ps = $con->prepare("SELECT UserName, Password FROM user WHERE UserName = ? AND Password = ?");
        $ps->bind_param("ss", $_POST["UserName"], $_POST["Password"]);
        $ps->execute();
        $ps->bind_result($UserName, $Password);
        $ps->store_result();
        if ($ps->num_rows == 1)
        {
            $_SESSION["login"]="1";
            echo "Good day, $UserName!<br>";
            echo "<br>";
            echo "<form action='main.php' method='post'>";
            echo "<input type='hidden' hidden='hidden' name='UserName' value='$UserName'>";
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




function Register($UserName, $Password)
{
    global $con;
    $ps = $con->prepare("INSERT INTO user(UserName, Password)VALUES (?, ?)");
    $ps->bind_param("ss", $_POST["NewUserName"], $_POST["NewPassword"]);
    $ps->execute();
    if ($ps->affected_rows > 0)
    {
        echo "Hello there, $UserName!";
    }
    else
    {
        echo "<a href='login.php'>Whoopsie! please try again.</a>";
    }
}


function EigenerKontostand($UserName)
{
    global $con;
    $sql = "SELECT SUM(Slaps) AS Kontostand FROM transaction WHERE UserSlapTake = '$UserName'";
    $res = $con->query($sql);
    $dsatz = $res->fetch_assoc();
    echo $dsatz["Kontostand"];
}



function UserWahl()
{
    global $con;
    $sql = "SELECT UserName FROM user";
    $res = $con->query($sql);
    while ($dsatz = $res->fetch_assoc())
    {
?><option name="UserSlapTake" value="<?php echo $dsatz["UserName"]?>"><?php echo $dsatz["UserName"];
        echo " (";
        $Username = $dsatz["UserName"];
        EigenerKontostand($Username);
        echo ")";
        ?></option><?php
    }

    $res->close();
    $con->close();
}

function transaction(string $operator, int $slaps, string $comment, string $userSlapGive, string $userSlapTake)
{
    if ($operator === "Auszahlen")
    {
        $slaps = $slaps * -1;
    }
    global $con;
        $ps = $con->prepare("INSERT INTO transaction (Operator, Slaps, Comment, UserSlapGive, UserSlapTake) VALUES(?, ?, ?, ?, ?)");
//    $ps = $con->prepare("INSERT INTO transaction (Operator, Slaps, Comment, UserSlapGive, UserSlapTake) VALUES ?, ?, ?, ?, ?");
    $ps->bind_param("sisss", $operator, $slaps, $comment, $userSlapGive, $userSlapTake);
    $ps->execute();
    if ($ps->affected_rows > 0)
    {
        echo "Transaction done, nice.<br><br>";
    }
    else
    {
        echo "An error occured, blyad!";
    }
    $ps->close();
    $con->close();


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

function getUserID($UserName)
{
    global $con;
    $res = $con->query("SELECT UserID FROM user WHERE UserName = '$UserName'");
    $userID = $res->fetch_array();
    return $userID[0];
}
?>