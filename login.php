<?php
include "functions.inc.php";

html_header('Login or Register');
//session_start();


?>

<h2>Login:</h2>
<form action="index.php" method="post">
    <input name="login" value="2" hidden="hidden">
    <p><input name="userName"> Username</p>
    <p><input name="password" type="password"> Password</p>
    <p><input type="submit" value="Login"> <input type="reset"></p>
</form>


<br>



<br>


<h2>Register</h2>
<form action="login.php" method="post">
    <input name="register" value="1" hidden="hidden">
    <p><input name="newUserName"> Username</p>
    <p><input name="newPassword" type="password"> Password</p>
    <p><input type="submit" value="Register"> <input type="reset"></p>
</form>
<br>

<?php
if (isset($_SESSION['user'])){
    var_dump($_SESSION["user"]);
}

if (isset($_POST["register"])) {
    $newUserName = $_POST["newUserName"] ?? null;
    $newUserPassword = $_POST["newPassword"] ?? null;
    if (!is_null($newUserName) && !is_null($newUserPassword)){
        $dbConnection = Database::getConnection();
        $userRepo = new UserRepository($dbConnection);
        $user = $userRepo->createUser($newUserName, $newUserPassword);
    }
    else{
        //handle error, something is missing
    }

}

html_footer();
?>