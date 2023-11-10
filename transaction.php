<?php
include "functions.inc.php";
html_header('Slap Balance');

initSession();

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
$adding = formularOperatorAdding($userRole, $tempUserRole) ?? "";



?>
<style>
    .slider{
        -webkit-appearance: none;
        appearance: none;
        background-color: #222222;
        color: chartreuse;
        height: 15px;
        width: 70px;
    }
    .slider::-webkit-slider-thumb {
        -webkit-appearance: none; /* Override default look */
        appearance: none;
        width: 10px; /* Set a specific slider handle width */
        height: 10px; /* Slider handle height */
        background: chartreuse; /* Green background */
        cursor: grab; /* Cursor on hover */
    }
    .transaction {
        width: 70%;
        height: 100%;

    }

</style>


<h1>Slap Balance:
<?php EigenerKontostand(getSessionUserID());
?>
</h1>

<h2>
    Share the pain:
</h2>


<form action="transaction_result.php" method="post">
<?php echo "<input type='hidden' name='UserName' value='". getSessionUserName() ."'>";?>
            <input type="hidden" name="DB">
    <p><label for="userSlapTake">Choose your victim.</label></p>
        <select name="userIDSlapTake">
        <?php UserWahl();?>
        </select>
    <br><br><p><label for="comment">Reason?(Optional, sometimes you just have to)</label></p>
    <textarea name="comment" rows="5" cols="20" placeholder="This sucker ate my cookie! MINE!"></textarea><br><br>
    <p><label for="slaps">Amount of Slaps</label></p>

    <p><input type="range" name="slaps" min="1" max="<?php echo $contingent;?>" value="1" class="slider" id="myRange"> <span id="demo"></span> Slaps</p>



    <script>
        var slider = document.getElementById("myRange");
        var output = document.getElementById("demo");
        output.innerHTML = slider.value;

        slider.oninput = function() {
            output.innerHTML = this.value;
        }
    </script>

<?php echo " $contingent left today"; ?><br><br>
    <p><label for="operator">Type of transaction</label> </p>
    <p><select name="operator">
            <option value="Deposit"> Deposit</option>
            <?php echo $adding; ?>
        </select></p><br>

    <input type="submit" value="Slap it!"> <input type="reset">
</form>

<?php
html_footer();