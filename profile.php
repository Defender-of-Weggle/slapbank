<?php
include "functions.inc.php";
html_header('News');
?>
    <style>
        * {
            box-sizing: border-box;
        }
    </style>
<?php

initSession();

if(!isset($_SESSION["login"]))
{
    echo "<br><br>Fuck off, log in!<br><br><form action='login.php'><input type='submit' value='Log in, Dipshit!'>";
    exit();
}
$profileID = $_GET["profileID"];
$userID = getSessionUserID();
$userName = getUserName($profileID);
$userRole = getUserRole($profileID);
$age = getUserAge($profileID);
$profileText = getUserProfileText($profileID);
$hideAge = hideAge($profileID);
$userTitle = getUserTitle($profileID);

?>
<div class="row">


    <div class="column" style="text-align: left">
            <?php echo "Name: " . $userName . "</p>";


                echo "<p>Age: ";
                if ($hideAge == 1) {
                    echo "none of your business, fucker";
                }
                else
                {
                    echo $age;
                }
                echo "</p>";

                    echo "<p>Title: " . $userTitle . "</p>";

                        echo "<p>Role: ";

                       echo $UserRole = match (true){
                              $userRole === 1 => "Admin",
                              $userRole === 2 => "Executive Slapper",
                              default => "Regular Slapvictim"

                        };
                echo "</p>";
            ?>
        </div>
    <div class="column">


        <img height="300px" width="400px" style="object-fit: cover" src="../slap/profilePics/default.jpg" alt="Profile Picture">


        <h3>About me:</h3>
        <p>
            <?php
            if (!empty($profileText)) {
                echo $profileText;
            }
            else
            {
                echo "Nothing to be told, yet";
            }
            ?>
        </p>
    </div>

                    <div class="column">
                        <h3>Some Statistics of you</h3>
                    <?php getAvailableUserTitles($profileID); ?>
                    </div>
    </div>













<?php
html_footer();
?>