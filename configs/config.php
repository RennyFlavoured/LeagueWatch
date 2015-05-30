<?php
// Ensure library/lib_gree is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    get_include_path(),
)));

$db = array (
    'adapter'   => 'mysqli',
    'params'    =>
    array (
        'host'      => '127.0.0.1',
        'dbname'    => 'leaguewatch',
        'username'  => 'root',
        'password'  => '',
        'charset'   => 'utf8',
    ),
);

$globals = array(
    'api_key' =>  '36c37189-8a94-4cbf-977f-f443cb691f6f',
);

$serviceDomain = array(
    'schema'    => 'https',
    'name'      => 'master.moconnect.dev',
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
                //'displayExceptions' => 1,
            ),
        ),
        'modules'   => array(),
        'view'      => array(
            // having a view array required to have view available in bootstrap
            'helperPath' => array(
                'Zend_View_Helper' => APPLICATION_PATH . '/views/helpers',
            ),
        ),
        'db'        => $db,
    ),
    'serviceDomain'     => $serviceDomain,
    'globals'           => $globals,
    'mofun'             => array (
        'baseURL'   => 'http://mofun.tag-games.com/',
        'skins'     => '/var/www/moconnect/mofun/media',
        'skinsURL'  => 'http://mofun.tag-games.com/media',
        'items'     => '/var/www/moconnect/mofun/media',
        'itemsURL'  => 'http://mofun.tag-games.com/media',
    ),
);