<?php
include "functions.inc.php";
html_header('Slap Transaction');

//initSession();

if(!isset($_SESSION["login"]))
{
    echo "<br><br>Fuck off, log in!<br><br><form action='login.php'><input type='submit' value='Log in, Dipshit!'>";
    exit();
}


$userID = getSessionUserID();

$userName = getSessionUserName();
$contingent = getContingent($userID);
$userRoles = getUserRole($userID);
$userRole = intval($userRoles[0]);
$tempUserRole = intval($userRoles[1]);
$adding = formularOperatorAdding($userRole, $tempUserRole) ?? "0";
$userIDTarget = @intval($_GET["userIDTarget"]) ?? "";



?>
<style xmlns="http://www.w3.org/1999/html">
    * {
        box-sizing: border-box;
    }
    .slider{
        -webkit-appearance: none;
        appearance: none;
        background-color: #222222;
        color: chartreuse;
        height: 15px;
        width: 120px;
    }
    .slider::-webkit-slider-thumb {
        -webkit-appearance: none; /* Override default look */
        appearance: none;
        width: 5px; /* Set a specific slider handle width */
        height: 10px; /* Slider handle height */
        background: chartreuse; /* Green background */
        cursor: grab; /* Cursor on hover */
    }
    .redFont{
        color: red;
        font-weight: bold;
    }
    @media (max-width: 700px){
        .transactionForm{
            width: 80%;
        }
    }


</style>

<div class="row">
    <div class="column">
        <span>Slap Balance:
            <?php echo EigenerKontostand(getSessionUserID());
            ?></span>


    </div>
    <table class="transactionForm" style="@media max-width: 700px {
    width: 100%;} justify-content: space-evenly">


<form action="transaction_result.php" method="post">
    <tr>
        <td>
                <?php echo "<input type='hidden' name='UserName' value='". getSessionUserName() ."'>";?>
                <input type="hidden" name="DB">
                <p><label for="userSlapTake">Share the pain with:</label></p>
        </td>
            <td>
                <select name="userIDSlapTake">
                <?php
               $userSelectionData = userWahl($userID);

               foreach ($userSelectionData["row"] as $row) {
                   $slapTakeUserID = intval($row["userID"]);
                   $userName = htmlentities($row["userName"]);
                   $userBalance = $row["balance"];



                   if ($userIDTarget === $slapTakeUserID){
                       echo "<option name='userIDSlapTake' selected='selected' value='$slapTakeUserID'>$userName ($userBalance)";
                   }
                   else {
                       echo "<option name='userIDSlapTake' value='$slapTakeUserID'>$userName ($userBalance)";
                   }
               }


                ?>
                </select>
            </td>
    </tr>

    <tr>
            <td>
                <br><br><p><label for="comment"> Comment</label></p>
            </td>
            <td>
                <textarea name="comment" rows="9" cols="20" placeholder="This sucker ate my cookie! MINE!"></textarea><br>
                <br>
                <input type="checkbox" name="hideComment">

                    Hide Comment?<br>

                <br>
                <br>
            </td>
    </tr>
        <tr>
                <td>
                    <p><label for="slaps">Amount of Slaps</label></p>
                </td>
            <td>

                <br>
                <p><input type="range" name="slaps" min="1" max="<?php echo $contingent;?>" value="1" class="slider" id="myRange"> <span id="demo"></span> Slaps</p>


                        <script>
                            var slider = document.getElementById("myRange");
                            var output = document.getElementById("demo");
                            output.innerHTML = slider.value;

                            slider.oninput = function() {
                                output.innerHTML = this.value;
                            }
                        </script>

                    <?php echo "<p class='redFont'> $contingent left today</p>"; ?><br><br>

            </td>
        </tr>
    <tr>
        <td>

            <p><label for="operator">Type of transaction</label> </p>

        </td>
        <td>
            <br>
                <p><select name="operator">
                        <option value="Deposit"> Deposit</option>
                        <?php echo $adding; ?>
                    </select></p><br>
        </td>
    </tr>
    <tr>
        <td>
            <input type="submit" autofocus="autofocus" value="Slap it!">
        </td>
        <td>
            <input type="reset">
                </form>
        </td>
    </tr>
    </table>
    </div>
</div>



    <div class="column">

    </div>


</div>
<?php
html_footer();