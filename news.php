<?php
include "functions.inc.php";
html_header('News');

//initSession();

if(!isset($_SESSION["login"]))
{
    echo "<br><br>Fuck off, log in!<br><br><form action='login.php'><input type='submit' value='Log in, Dipshit!'>";
    exit();
}

$userID = SessionManagement::getSessionUserID();
$userName = SessionManagement::getSessionUserName();
$userRoles = getUserRole($userID);
$userRole = intval($userRoles[0]);
$tempUserRole = intval($userRoles[1]);


$title = $_POST["title"] ?? "";
$content = $_POST["content"] ?? "";



if (!empty($title))
{
newPostSend($userID, $title, $content);
}


if ($userRole === 1)
{
    ?>
            <form action="news.php" method="post">
                <p>
                <label for="title">Title for new Post</label>
                </p><p><br>
                <input name="title" size="25" placeholder="This title sucks, so far">
                </p>


                <textarea name="content" cols="50" rows="20" placeholder="Fuck all of this"></textarea><br><br>

                <input type="submit"> <input type="reset">



            </form>
    <?php
}
echo "<br><br>";


fetchNewsPosts();


html_footer();
?>