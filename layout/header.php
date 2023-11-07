<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo $pageTitle ?? 'Ze Slapbank'; ?></title>
        <link rel="stylesheet" href="stylesheet.css">
    <style>

        body {
            margin-top: 50px;
        }

        /* unvisited link */
        a:link {
            color: red;
        }

        /* visited link */
        a:visited {
            color: red;
        }

        a:hover {
            color: greenyellow;
        }

        nav > ul
        {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            list-style-type: none;
            margin: 0;
            padding: 0;
            height: 50px;
            overflow: hidden;
            background-color: #333;
        }
        li .left{
            float: left;

        }

        li a{
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }
    </style>
</head>
<body>
<?php
initSession();



?>



<nav>
    <ul>
        <li><a class="left" href="index.php"> Overview </a></li>
        <li><a class="left" href="transaction.php"> Transaction </a></li>
        <li><a class="left" href="history.php"> History</a></li>
        <li><a class="left" href="members.php"> Members</a></li>
        <li><a class="left" href="news.php"> News </a></li>
        <li><a style="float:right" href="logout.php"> Logout </a></li>
        <li><a style="float: right" href="profile.php"> Profile </a></li>
    </ul>
</nav>