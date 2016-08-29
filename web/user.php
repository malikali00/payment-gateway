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

// Enable class autoloader for this page instance
spl_autoload_extensions('.class.php');
spl_autoload_register();

// Start or resume the session
session_start();

if(isset($_GET['id'])) {
    $View = new \View\User\UserView($_GET['id'], @$_GET['action']);
    $View->handleRequest();

} else {
    $View = new View\User\UserListView();
    $View->handleRequest();
}
