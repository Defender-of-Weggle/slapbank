<?php

class SessionManagement
{

    private $sessionUserID;
    private $sessionUserName;


    public static function initSession()
    {
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public static function setSessionUserName(string $UserName) {
        initSession();
        $_SESSION['userName'] = htmlentities($UserName);
    }

    public static function getSessionUserName() : string|null {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return htmlentities($_SESSION['userName']);
        }

        return null;
    }


    public static function setSessionUserID(int $userID) {
        initSession();
        $_SESSION['userID'] = $userID;
    }


    public static function getSessionUserID() : int|null {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return $_SESSION['userID'];
        }

        return null;
    }



}