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
<h2>List of members</h2><br><br>
    <table>


        <tr><th>Admins:<th></tr>
            <?php getAdminMembers();
            echo "<tr><td>Slapping Members</td></tr>";
            getSlapperMembers();
            echo "<tr><td>Temporary Slappers</td></tr>";
            getTempSlapperMembers();
            echo "<tr><td>Regular Members</td></tr>";
            getRegularMembers();?>
    </table>
</div>
<div class="column"></div>

</div>


<?php
html_footer();