<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <title>Logout</title>
    <link rel="stylesheet" href="stylesheet.css">
</head>
<body>
<?php

session_start();
session_destroy();
echo "Logout successful, in case you reconsidered... go ahead,<br><br><br>";
echo "<form action='login.php'><input type='submit' value='log back in'>";
?>
</body>
</html>