<?php
include "functions.inc.php";

html_header('Overview');

if(!isset($_SESSION["login"]))
{
    echo "<br><br>Fuck off, log in!<br><br><form action='login.php'><input type='submit' value='Log in, Dipshit!'>";
    exit();
}

?>
    <style>
        * {
            box-sizing: border-box;
        }
    </style>


<div class="row">

    <div class="column"></div>

    <div class="column">




        <ul style="list-style-type: none">
            <li><h2>List of members</h2></li>
            Admins:
            <?php getAdminMembers();
            echo "<li>Slapping Members</li>";
            getSlapperMembers();
            echo "<li>Temporary Slappers";
            getTempSlapperMembers();
            echo "<li>Regular Members</li>";
            getRegularMembers();?>
        </ul>
</div>
<div class="column"></div>

</div>


<?php
html_footer();