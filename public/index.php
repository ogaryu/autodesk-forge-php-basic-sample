<?php
/**
 * Created by PhpStorm.
 * User: Ryuji Ogasawara
 * Date: 2016/09/30
 * Time: 午前11:32
 */

// set a constant that holds the project's folder path, like "/var/www/".
// define the directory separator
define('DS', DIRECTORY_SEPARATOR);
// DIRECTORY_SEPARATOR adds a slash to the end of the path
define('ROOT', dirname(__DIR__) . DS);
// set a constant that holds the project's "application" folder, like "/var/www/application".
define('APP', ROOT . 'application' . DS);
define('LIB', ROOT . 'library' . DS);

// load application config (error reporting etc.)
require APP . 'config/config.php';

// start to dispatch
require APP . 'bootstrap.php';
require APP . 'BasicApplication.php';

$app = new \Autodesk\Forge\PHP\Sample\BasicApplication();
$app->run();