<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

defined('PROJECT_PATH') || define('PROJECT_PATH', realpath(dirname(__FILE__) . '/..'));
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

// Ensure library is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(PROJECT_PATH . '/library'),
    get_include_path(),
)));

// Zend: Create application, bootstrap, and run
require_once 'Zend/Application.php';
$application = new Zend_Application(
    APPLICATION_ENV,
    PROJECT_PATH . '/configs/config.php'
);

$application->bootstrap();
Model_Config::setApplication($application);

if(! defined('DONT_RUN_APP') || DONT_RUN_APP == false) {
    $application->run();
}