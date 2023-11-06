<?php
include "functions.inc.php";

html_header('Overview');

?>

<div class="row">
    <div class="column">
        <h3>....:</h3>

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
                                        <td><?php echo $row['comment'] ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right; font-weight: bold">benefactor:</td>
                                        <td><?php echo $row['userNameSlapGive'] ?></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: right; font-weight: bold">recipient:</td>
                                        <td><?php echo $row['userNameSlapTake'] ?></td>
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

        <h3>....</h3>


    </div>







</div>



<?php
html_footer();