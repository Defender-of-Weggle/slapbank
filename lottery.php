<?php
include "functions.inc.php";
html_header('Lottery');

if(!isset($_SESSION["login"]))
{
    echo "<br><br>Fuck off, log in!<br><br><form action='login.php'><input type='submit' value='Log in, Dipshit!'>";
    exit();
}
$userID = getSessionUserID();
$jackpot = getCurrentJackpot()["currentJackpot"];
$lastJackpotUpdate = getCurrentJackpot()["lastUpdated"];
$lastJackpotUpdate = new DateTime($lastJackpotUpdate);
if (empty($jackpot)) {
    $jackpot = 11;
    updateJackpot($jackpot);
}

$contingent = getContingent($userID);

//if (isset($_POST["slaps"])) {
//    $slaps = $_POST["slaps"];
//    slapLottery($userID, $slaps);
//}

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
        width: 80px;
    }
    .slider::-webkit-slider-thumb {
        -webkit-appearance: none; /* Override default look */
        appearance: none;
        width: 10px; /* Set a specific slider handle width */
        height: 10px; /* Slider handle height */
        background: chartreuse; /* Green background */
        cursor: grab; /* Cursor on hover */
    }
</style>

<div class="row">
    <div class="column">
        <h4>Currently in Jackpot: <span class="redFont"><?php echo $jackpot; ?></span></h4>
        <p>Last updated: <?php echo $lastJackpotUpdate->format("d/m/y H:i"); ?></p><br>

        <h4>Your own win statistic:</h4>
            <p>Coming soon, or not</p><br>
        <h4>What can be won?</h4><br>
        Increase your slap limit by:
        <p>1, 5, 7, 10 or with bit of luck, the Jackpot</p>
        <p>All regular members can also win the temporary Slapper role for the rest of the day</p>



    </div>

    <div class="column">
        <h2>Win some Slaps to share</h2>
        <br><br>
        <p>1 Slap = 1 ticket to win, or lose your soul to me</p>
        <p>Yes, it's the same slaps you can transfer through transactions</p>
        <br><br>
        <form action="lottery.php" method="post" name="lottery">
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
            <input type="submit" value="Slap your Luck">
        </form>
        <?php
            if (isset($_POST["slaps"])) {
            $slaps = $_POST["slaps"];
            slapLottery($userID, $slaps);
            }
        ?>

    </div>


    <div class="column">

        <h4>top 3 Winners:</h4>
        <p>Coming soon, or not</p>


    </div>



</div>
