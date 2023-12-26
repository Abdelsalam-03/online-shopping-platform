<?php 

    // prevent session fixation
    // adding extra protection layer
    ini_set('session.use_strict_mode', 1);

    // setting the cookies accessable over http protocol only
    // prevent js access
    ini_set('session.cookie_httponly', 1);
    
    // send cookie to same site only
    ini_set('session.cookie_samesite', 'lax');


    define("SESSION_TIMEOUT", 60 * 60 * 5);
    define("SESSION_INTERVAL", 30 * 30);

    session_start();
    
    if (isset($_SESSION["logInTime"]) && time() - $_SESSION["logInTime"] >= SESSION_TIMEOUT) {
        logOut();
    }

    function autoRegenerateId(){
        if (isset($_SESSION['lastTimeRegenerated'])) {
            if (time() - $_SESSION['lastTimeRegenerated'] >= SESSION_INTERVAL) {
                session_regenerate_id(true);
                $_SESSION['lastTimeRegenerated'] = time();
            }
        } else {
            session_regenerate_id(true);
            $_SESSION['lastTimeRegenerated'] = time();
            $_SESSION["logInTime"] = time();
        }
    }

    function logOut(){
        session_regenerate_id(true);
        session_unset();
        session_destroy();
        header("location: home.php");
        exit;
    }

    autoRegenerateId();

?>