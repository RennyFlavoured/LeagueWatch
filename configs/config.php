<?php
// Ensure library/lib_gree is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    get_include_path(),
)));

$leaguewatch_db = array(
    'leaguewatch' => array (
        'adapter'   => 'mysqli',
        'host'      => '127.0.0.1',
        'dbname'    => 'leaguewatch',
        'username'  => 'root',
        'password'  => '',
        'charset'   => 'utf8'
    ),
    'lw_static' => array (
        'adapter'   => 'mysqli',
        'host'      => '127.0.0.1',
        'dbname'    => 'lw_static',
        'username'  => 'root',
        'password'  => '',
        'charset'   => 'utf8'
    )
);

$globals = array(
    'api_key' =>  '36c37189-8a94-4cbf-977f-f443cb691f6f',
);

return array (
    'bootstrap'     => array(
        'path'      => APPLICATION_PATH . '/Bootstrap.php',
        'class'     => 'Bootstrap',
    ),
    'appnamespace'  => 'Application',
    'resources'     => array(
        'layout'    => array(
                'layoutPath' => APPLICATION_PATH . '/layouts/scripts/',
        ),
        'frontController' => array(
            'controllerDirectory'   => APPLICATION_PATH . '/controllers',
            'moduleDirectory'       => APPLICATION_PATH . '/modules',
            'params'                => array(
                'displayExceptions' => 1,
            ),
        ),
        'modules'   => array(),
        'view'      => array(
            // having a view array required to have view available in bootstrap
            'helperPath' => array(
                'Zend_View_Helper' => APPLICATION_PATH . '/views/helpers',
            ),
        ),
        'multidb'   => $leaguewatch_db,
    ),
    'globals'       => $globals,
);