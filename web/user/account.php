<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 8/27/2016
 * Time: 10:47 PM
 */
// Enable error reporting for this page
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Go up 1 directory
chdir('..');

// Enable class autoloader for this page instance
spl_autoload_extensions('.class.php');
spl_autoload_register();

// Register Exception Handler
\System\Exception\ExceptionHandler::register();

// Start or resume the session
session_start();

$SessionManager = new \User\Session\SessionManager();

// Render View
$View = new User\View\UserView(-1 ?: $SessionManager->getSessionUser()->getID());
$View->handleRequest();
