<?php
define('BASE_PATH', dirname(dirname(__DIR__)));
define('APP_PATH', BASE_PATH . '/app');
define('PUBLIC_PATH', BASE_PATH . '/public');

// URL configuration
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST']);

// Application settings
define('DEBUG_MODE', true);
error_reporting(DEBUG_MODE ? E_ALL : 0);
ini_set('display_errors', DEBUG_MODE ? '1' : '0');

// Ensure errors are displayed during setup
ini_set('display_errors', '1');
error_reporting(E_ALL); 