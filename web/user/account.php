<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 8/27/2016
 * Time: 10:47 PM
 */

// Go up 1 directory
define("BASE_HREF", '../'); // Set relative path
chdir(__DIR__ . '/' . BASE_HREF);

// Enable class autoloader for this page instance
spl_autoload_extensions('.class.php');
spl_autoload_register();

// Register Exception Handler
\System\Exception\ExceptionHandler::register();

// Register Exception Handler
\System\Exception\ExceptionHandler::register();

// Start or resume the session
session_start();

$SessionManager = new \User\Session\SessionManager();


if(!$SessionManager->isLoggedIn()) {
    header('Location: ' . BASE_HREF . 'login.php?message=session has ended');
    die();
}

// Render View
$View = new User\View\UserView($SessionManager->getSessionUser()->getUID());
$View->handleRequest();
