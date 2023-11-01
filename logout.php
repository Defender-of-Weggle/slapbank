<?php
include 'functions.inc.php';
html_header('Logout');

session_start();
session_destroy();
echo "<br><br>Logout successful, in case you reconsidered... go ahead,<br><br><br>";
echo "<form action='login.php'><input type='submit' value='log back in'>";

html_footer();
