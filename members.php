<?php
include "functions.inc.php";

html_header('Overview');

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