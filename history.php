<?php
include "functions.inc.php";

html_header('History');


if(!isset($_SESSION["login"]))
{
    echo "<br><br>Fuck off, log in!<br><br><form action='login.php'><input type='submit' value='Log in, Dipshit!'>";
    exit();
}
$sessionUserID = getSessionUserID();

?>

<style>
    .redFont{
        color: red;
        font-weight: bold;
    }
</style>




<div class="row">
    <div class="column">
        <br><br>
    <span>* Only benefactor and recipient can read read the comment</span>

    </div>
    <div class="column" style="text-align: left">
        <h2>last 20 transactions</h2>

        <?php

            $transactionPage = 0;

            //seitenzählung beginnt mit 0!!! 0 ist immer erste seite...

            $transactionData = getLastTransactions($transactionPage);

            //print_r($transactionData);

            if ($dataRows = $transactionData['dataRows']) {
                ?>

                    <ul>
                        <?php

                            foreach ($dataRows as $row) {
                                echo "<li style='list-style-type: none; border-style: solid; border-width: 1px; margin-bottom: 10px;'>";
                                echo "<div style='width: 100%; display: flex; justify-content: space-between; background-color: chartreuse; color: black; font-weight: bold'>";
                                echo "<span>#{$row['id']}</span>";
                                echo "<span>{$row['formattedDate']}</span>";
                                echo "</div>";
                                echo "<div style='padding: 5px'>";
                                ?>

                                <table style="border-spacing: 5px 10px;">
                                    <tr>
                                        <td style="text-align: right; font-weight: bold">type:</td>
                                        <td><?php echo $row['operator'] ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right; font-weight: bold">amount:</td>
                                        <td><?php echo $row['slaps'] ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right; font-weight: bold">comment:</td>
                                        <td><?php


                                            switch ($row["hideComment"]){
                                                case 0:
                                                    $comment = $row["comment"];
                                                    break;
                                                case 1:
                                                $comment = match (true) {
                                                    $sessionUserID == $row["userIDSlapGive"] => $comment = $row['comment'],

                                                    $sessionUserID == $row["userIDSlapTake"] => $comment = $row['comment'],

//                                                    $sessionUserID == $row["userIDSlapGive"] or $row["userIDSlapTake"] => $comment = $row['comment'],

                                                    $sessionUserID !== $row["userIDSlapGive"] => $comment = "<p class='redFont'>Confidential comment, get lost*</p>",

                                                    $sessionUserID !== $row["userIDSlapTake"]  => $comment = "<p class='redFont'>Confidential comment, get lost*</p>",

//                                                case $sessionUserID == $row["userIDSlapGive"] OR $row["userIDSlapTake"] AND $row["hideComment"] == 1:
//                                                    echo $row['comment'];
//                                                    break;
//
//                                                case $sessionUserID !== $row["userIDSlapGive"] OR $row["userIDSlapTake"] AND $row["hideComment"] == 1:
//                                                    echo "<p class='redFont'>Confidential comment, get lost</p>";
//                                                    break;
//


                                                };
                                            }


                                            echo $comment;

//                                            if ($row["hideComment"] === 1)
//                                            {
//                                                echo "<p class='redFont'>Confidential comment, get lost</p>";
//                                            }
//                                            elseif ($sessionUserID === $row["userIDSlapGive"] OR $row["userIDSlapTake"])
//                                            {
//                                                echo $row['comment'];
//                                            }

                                            ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right; font-weight: bold">benefactor:</td>
                                        <td><?php echo "<a href='profile.php?profileID=" . $row['userIDSlapGive'] . "'>" . $row['userNameSlapGive'] . "</a>" ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right; font-weight: bold">recipient:</td>
                                        <td><?php echo "<a href='profile.php?profileID=" . $row['userIDSlapTake'] . "'>" .  $row['userNameSlapTake'] . "</a>" ?></td>
                                    </tr>

                                </table>

                                <?php
                                echo "</div>";
                                echo "</li>";
                            }

                        ?>
                    </ul>

                <?php
            } else {
                echo "<div>no transactions here :/</div>";
            }

        ?>






    </div>
    <div class="column">





    </div>







</div>



<?php
html_footer();