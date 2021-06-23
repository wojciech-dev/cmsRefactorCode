<?php

/**
 * Front controller
 *
 * PHP version 7.0
 * Composer
 */
require dirname(__DIR__) . '/vendor/autoload.php';

/**
 * Error and Exception handling
 */

// Whoops error handling
$whoops = new Whoops\Run();
// Set Whoops as the default error and exception handler used by PHP:
$whoops->register();
$whoops->pushHandler(new Whoops\Handler\PrettyPageHandler());

require_once('../App/Routes.php');
