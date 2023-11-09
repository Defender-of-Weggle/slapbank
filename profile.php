<?php

include "functions.inc.php";
html_header('Profile');
?>
    <style>
        * {
            box-sizing: border-box;
        }
    </style>
<?php


if(!isset($_SESSION["login"]))
{
    echo "<br><br>Fuck off, log in!<br><br><form action='login.php'><input type='submit' value='Log in, Dipshit!'>";
    exit();
}
$profileID = $_GET["profileID"] ?? $userID = getSessionUserID();
$userID = getSessionUserID();
$userName = getUserName($profileID);
$userRoles = getUserRole($userID);
$userRole = intval($userRoles[0]);
$tempUserRole = intval($userRoles[1]);
$age = getUserAge($profileID);
$profileText = getUserProfileText($profileID);
$userTitle = $_POST["selectTitle"] ?? getUserTitle($profileID);
$userMail = getUserMail($userID) ?? "";
$userBirthday = getUserBirthday($userID);
$upload = $_POST["upload"] ?? null;
$oldPassword = $_POST["oldPassword"] ?? "";
$newPassword = $_POST["newPassword"] ?? "";
$newUserBirthday = $_POST["newUserBirthday"] ?? "";
$newUserProfilText = $_POST["newProfileText"] ?? "";
$newUserMail = $_POST["newMailAdress"] ?? "";

$hideAge = hideAge($profileID);
if (isset($_POST["changeUsername"])) {
    $hideAge = $_POST["hideAge"] ?? 0;
}



if (isset($_POST["addRandomSlapper"]))
{
    calloutRandomSlapper($userID, $userRole);
    echo "Random Slapper was added";
}

if (isset($_POST["changePassword"]))
{
    updatePassword($oldPassword, $newPassword, $userID);
    echo "<br>Updating Password successful";
}

if (isset($_POST["newUserBirthday"]))
{
    updateUserBirthday($userID, $newUserBirthday);
    echo "Birthday has been set";
}

if (isset($_POST["changeUsername"]))
{
    $newUserName = $_POST["newUserName"];
    updateUserName($userID, $newUserName, $hideAge, $userTitle);
    echo "Username/Title updated";
}
if (isset($_POST["newProfileText"]))
{
    updateProfileText($userID, $newUserProfilText);
    echo "Profiletext updated, blyad";
}

if (isset($_POST["updateMail"]))
{
    updateUserMail($userID, $newUserMail);
    echo "Mail adress updated";
}


if (!empty($upload))
{
    $target_dir = __DIR__."/profilePics/";
    $target_file = $target_dir . basename($_FILES["ava"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["ava"]["tmp_name"]);
        if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }


// Check file size
    if ($_FILES["ava"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

// Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

// Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
//    $_FILES["ava"] = $userID;
    } else {
        $name = $userID . "." . $imageFileType;
        $target_file = $target_dir . $name;
        if (move_uploaded_file($_FILES["ava"]["tmp_name"], $target_file)) {
            echo "The file ". htmlspecialchars( basename( $_FILES["ava"]["name"])). " has been uploaded.";
//            (move_uploaded_file($_FILES["ava"]["tmp_name"], $name));
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }


}


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
                       if ($tempUserRole == 2) {
                           echo ", Temporary Slapman";
                       }

                echo "</p>";
            ?>
        </div>
    <div class="column">


<!--        <img height="300px" width="400px" style="object-fit: cover" src="../slap/profilePics/--><?php //$profileID ?><!--.gif" alt="Profile Picture">-->
        <?php
        $profilePicture = match (true){
            file_exists(__DIR__."/profilePics/$profileID.png") => $profilePicture = "<img height='300px' width='300px' style='object-fit: cover' src='profilePics/$profileID.png' alt='Profile Picture'>",
            file_exists(__DIR__."/profilePics/$profileID.jpg") => $profilePicture = "<img height='300px' width='300px' style='object-fit: cover' src='profilePics/$profileID.jpg' alt='Profile Picture'>",
            file_exists(__DIR__."/profilePics/$profileID.gif") => $profilePicture = "<img height='300px' width='300px' style='object-fit: cover' src='profilePics/$profileID.gif' alt='Profile Picture'>",
            file_exists(__DIR__."/profilePics/$profileID.jpeg") => $profilePicture = "<img height='300px' width='300px' style='object-fit: cover' src='profilePics/$profileID.jpeg' alt='Profile Picture'>",
            default => $profilePicture = "<img height='300px' width='300px' style='object-fit: cover' src='profilePics/default.jpg' alt='Profile Picture'>"


        };

        echo $profilePicture;
        ?>

        <h3>About me, <?php echo $userName ?>:</h3>
        <p>
            <?php
            $profileText = match (true) {
                $profileID === $userID => $aboutMe = "<form action='profile.php' method='post'><textarea rows='10' cols='40' placeholder='$profileText' name='newProfileText'></textarea><br><input type='submit'></form>",
                empty($profileText) => $aboutMe = "Nothing to be told, yet",
                default => $aboutMe = $profileText

            };
            echo $aboutMe;

//            if (!empty($profileText)) {
//                echo $profileText;
//            }
//            else
//            {
//                echo "Nothing to be told, yet";
//            }
            ?>
        </p>
    </div>

                    <div class="column">
                        <h3>Some Statistics of you</h3>
                    <?php getPersonalStatistics($profileID); ?>
                    </div>
    </div>
<?php
if ($profileID === $userID){


?>

<div class="row">
    <div class="column">
        <h3>Edit Profile:</h3>
        <p>Change Avatar:</p>
        <form action="profile.php" method="post" enctype="multipart/form-data">
            <input type="hidden" hidden="hidden" value="upload" name="upload" id="upload">
            <input type="file" name="ava" id="ava"><br><br>
            <input type="submit">
        </form>
        <br><br>
        <p>Change Name:</p>
        <form action="profile.php" method="post">
            <input type="hidden" name="changeUsername" value="1">
            <input type="text" value="<?php echo $userName ?>" name="newUserName"><br><br>
            hide age?
            <input type="checkbox" name="hideAge" value="1" <?php echo ($hideAge ? 'checked' : '')?>>
            <br>
            <p>Change title</p>
           <p>
               <select name="selectTitle">
                <?php getAvailableUserTitlesSelectOptions($userID, $userTitle); ?>
            </select>
           </p>
            <input type="submit">
        </form>
    </div>
    <div class="column">
    <p>Change/set Mail:</p>
    <form action="profile.php" method="post" name="updateMail">
        <input name="newMailAdress" readonly="readonly" placeholder="Useless@anyway.here" size="16" type="email""><br>
        <input type="submit" disabled="disabled" value="disabled">
    </form>
    <br><br>
    <p>Set Birthday:</p>
        <?php
        if (checkSetupBirthday($userID) == "0000-00-00"){ ?>
    <form action="profile.php" method="post" name="updateBirthday">
        <input type="date" value="<?php $userBirthday ?>" name="newUserBirthday"><br>
        <input type="submit"><br>
    </form>
    </div>
    <div class="column">
        <p>Change your password:</p>
        <form action="profile.php" name="changePassword" method="post">
            <input type="password" name="oldPassword"> Old Password<br>
            <input type="password" name="newPassword"> new Password<br><br>
            <input type="submit"><br><br>
        </form>
        <?php
        }
        else
        {
            echo "Your Birthday is set, no more updates on it";
        }
        ?>
    </div>
    <div class="column">
        <?php
        if ($userRole === 1)
        {
            ?>
                Add random slapper for the day
            <form action="profile.php" method="post" name="calloutRandomSlapper">
                <input type="hidden" value="1" name="addRandomSlapper">
                <input type="submit">
            </form>

            <?php
        }
        ?>


    </div>
</div>





<?php
}
    ?>







<?php
html_footer();
?>