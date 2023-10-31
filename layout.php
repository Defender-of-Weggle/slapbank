<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="utf-8">
<title>Ze Slapbank</title>
<style>

    /* unvisited link */
    .navbar a:link {
        color: red;
    }

    /* visited link */
    .navbar a:visited {
        color: red;
    }

    .navbar a:hover {
        color: greenyellow;
    }

    a.logout
    {
        position: fixed;
        padding-right: 5px;
        right: 0;
        color: red;
    }

    ul.navbar
    {
        display: inline-block;
        width: 100%;
        list-style-type: none;
    }


</style>

</head>

<ul class="navbar">
    <li><a class="navbar" href="index.php"> Overview </a></li>
    <li><a class="navbar" href="main.php"> Transaction </a></li>
    <li><a class="navbar" href="news.php"> News </a></li>
    <li><a class="logout" href="logout.php">Logout</a></li>
</ul>

<body>

</body>
</html>