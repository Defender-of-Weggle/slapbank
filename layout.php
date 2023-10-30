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

    ul.navbar {
        float: left;
        position: fixed;
        top: 0;
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background-color: black;
        color: red;
        font-size: 120%;

    }

    .navbar li {
        float: left;
        display: inline-block;
        font-size: 20px;
        padding: 20px;
    }


</style>

</head>

<ul class="navbar">
    <li><a href="index.php"> Overview</a></li>
    <li><a href="main.php"> Transaction </a></li>
    <li><a href="news.php"> News </a></li>
    <li><a class="logout" href="logout.php">Logout</a></li>
</ul>

<body>

</body>
</html>