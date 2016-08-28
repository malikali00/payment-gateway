<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 8/27/2016
 * Time: 10:47 PM
 */

// Enable class autoloader
spl_autoload_extensions('.class.php');
spl_autoload_register();

// Start or resume the session
session_start();

// Render View
$View = new View\Login\Auth();
if($_POST) try {
    $username = $View->validateUsername($_POST);
    $password = $View->validatePassword($_POST);

    // try log in
    $SessionManager = new User\SessionManager();
    $NewUser = $SessionManager->login($username, $password);

} catch (Exception $ex) {

    // If error, render view with exception
    $View->setException($ex);
}
$View->renderHTML();