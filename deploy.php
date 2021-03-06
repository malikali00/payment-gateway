<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 8/27/2016
 * Time: 10:47 PM
 */

if(!isset($argv))
    die("Console Only");

//chdir('web');
// Enable class autoloader
//spl_autoload_extensions('.class.php');
//spl_autoload_register();

// Test command
$cmd_test = 'ssh access.simonpayments.com -p 30305 -t "cd /usr/share/nginx/spgdev; php test.php;"';

// Deploy command
$cmd_deploy = 'ssh access.simonpayments.com -p 30305 -t "cd /usr/share/nginx/spgdev; git pull origin dev;"';

// Check git status
exec('git status', $out, $ret);
if(strpos(implode("\n", $out), 'nothing to commit, working directory clean') === false) {
    echo "Commit and push code before deploying, n00b";
    exit(1);
}

// Local Test
echo "\nTesting locally...\n";
require 'test.php';

// Deploy

echo "\nDeploying remotely...\n";
exec($cmd_deploy, $out, $ret);
echo implode("\n", $out);
if(strpos(implode("\n", $out), 'error') !== false) {
    echo "Looks like there was an error";
    exit(1);
}

// Remote Test
echo "\nTesting remotely...\n";
$ret = system($cmd_test, $out);

// TODO revert on fail remotely?
// TODO check out production branch?