<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo $pageTitle ?? 'Ze Slapbank'; ?>
    </title>
    <link rel="stylesheet" href="./layout/stylesheet.css">


    <?php

    spl_autoload_register(function ($className) {
        include './classes/' . $className . '.php';
    });


    SessionManagement::initSession();
    ?>
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




        html, body {
            margin: 0;
            height: 100%;
        }

        * {
            font-family: "Courier New", "Lucida Console", monospace;
            box-sizing: border-box;
        }

        .top-nav {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            background-color: #222222;
            /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
            color: #FFF;
            height: 82px;
            padding: 1em;
        }

        .menu {
            display: flex;
            flex-direction: row;
            list-style-type: none;
            margin: 0;
            padding: 0;
            text-decoration: none;
        }

        .menu > li {
            margin: 0 1rem;
            overflow: hidden;
            text-decoration: none;
        }

        .menu-button-container {
            display: none;
            height: 100%;
            width: 30px;
            cursor: pointer;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        #menu-toggle {
            display: none;
        }

        .menu-button,
        .menu-button::before,
        .menu-button::after {
            display: block;
            background-color: #fff;
            position: absolute;
            height: 4px;
            width: 30px;
            transition: transform 400ms cubic-bezier(0.23, 1, 0.32, 1);
            border-radius: 2px;
        }

        .menu-button::before {
            content: '';
            margin-top: -8px;
        }

        .menu-button::after {
            content: '';
            margin-top: 8px;
        }

        #menu-toggle:checked + .menu-button-container .menu-button::before {
            margin-top: 0px;
            transform: rotate(405deg);
        }

        #menu-toggle:checked + .menu-button-container .menu-button {
            background: rgba(255, 255, 255, 0);
        }

        #menu-toggle:checked + .menu-button-container .menu-button::after {
            margin-top: 0px;
            transform: rotate(-405deg);
        }

        @media (max-width: 700px) {
            .menu-button-container {
                display: flex;
            }
            .menu {
                position: absolute;
                top: 0;
                margin-top: 50px;
                left: 0;
                flex-direction: column;
                width: 100%;
                justify-content: center;
                align-items: center;

            }
            #menu-toggle ~ .menu li {
                height: 0;
                margin: 0;
                padding: 0;
                border: 0;
                transition: height 400ms cubic-bezier(0.23, 1, 0.32, 1);
            }
            #menu-toggle:checked ~ .menu li {
                border: 1px solid #333;
                height: 2.5em;
                padding: 0.5em;
                transition: height 400ms cubic-bezier(0.23, 1, 0.32, 1);
            }
            .menu > li {
                display: flex;
                justify-content: center;
                margin: 0;
                padding: 0.5em 0;
                width: 100%;
                color: white;
                background-color: #222;
            }

            .menu > li:not(:last-child) {
                border-bottom: 1px solid #444;
            }
        }


    </style>


</head>
<body>
<section class="top-nav">
    <div>
        <a href="index.php"><img src="./layout/banner.jpg" width="200" height="82" style="float: left"></a>
    </div>
    <input id="menu-toggle" type="checkbox" />
    <label class='menu-button-container' for="menu-toggle">
        <div class='menu-button'></div>
    </label>
    <ul class="menu">
        <li><a style="text-decoration: none" href="index.php"> Overview </a></li>
        <li><a style="text-decoration: none" href="transaction.php"> Transaction </a></li>
        <li><a style="text-decoration: none" href="history.php"> History</a></li>
        <li><a style="text-decoration: none" href="lottery.php"> Lottery</a></li>
        <li><a style="text-decoration: none" href="members.php"> Members</a></li>
        <li><a style="text-decoration: none" href="news.php"> News </a></li>
        <li><a style="text-decoration: none" href="profile.php"> Profile </a></li>
        <li><a style="text-decoration: none" href="logout.php"> Logout </a></li>
    </ul>
</section>









<?php

//spl_autoload_register(function ($className) {
//    include './classes/' . $className . '.php';
//});




