<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 8/28/2016
 * Time: 1:26 PM
 */

use Config\SiteConfig;
use Config\DBConfig;
use View\Theme\SPG\SPGViewTheme;

// Database Config
DBConfig::$DB_HOST = 'localhost';
DBConfig::$DB_USERNAME = 'spg';
DBConfig::$DB_PASSWORD = 'Uj3QgkMg';

// Site Config
SiteConfig::$SITE_NAME = "Simon Payments Gateway";
SiteConfig::$DEFAULT_THEME = new SPGViewTheme();