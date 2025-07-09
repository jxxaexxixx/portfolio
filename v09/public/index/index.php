<?php

/**
 * Front controller
 *
 * PHP version 7.0
 */

/**
 * Composer
 */
require dirname(__DIR__) . '/../vendor/autoload.php';


/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

/**
 * Routing
 */

$pageArr=[

    ['chat', 'ClientCon','Render'],
    ['admin', 'AdminCon','Render'],
    ['', 'MainCon','Render'],

];


$router = new Core\Router();
$router->RouterPackage($pageArr);
