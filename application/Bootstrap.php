<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	protected function _initResources()
    {
        $configs = $this->getOptions();
        Zend_Registry::set('Config', $configs);
        Zend_Registry::set('currentDomain', @$configs['serviceDomain']);
    }

    protected function _initDate()
    {
        date_default_timezone_set('UTC');
    }

    public function _initDbRegistry()
    {
        $this->bootstrap('db');
    }

    protected function _initAutoload()
    {
        // Establish internal resources and push autoloader to top of stack (new Autoloader = first)
        $resources = new Zend_Loader_Autoloader_Resource(array('namespace' => null, 'basePath' => APPLICATION_PATH));
        $resources->addResourceTypes(array(
            'api'    => array(
                'namespace' => 'API_',
                'path'      => 'api',
            ),
            'service'    => array(
                'namespace' => 'Service_',
                'path'      => 'service',
            ),
            'form'    => array(
                'namespace' => 'Form_',
                'path'      => 'forms',
            ),
            'model'   => array(
                'namespace' => 'Model_',
                'path'      => 'models',
            ),
            'viewhelper' => array(
                'namespace' => 'View_Helper_',
                'path'      => 'views/helpers',
            ),
        ));

        // Load from anywhere PSR-0 compliant
        //Zend_Loader_Autoloader::getInstance()->suppressNotFoundWarnings(true);
        Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);
    }

    protected function _initDoctype()
    {
        $this->bootstrap('view');
        $this->getResource('view')->setEncoding('UTF-8');
    }

    // protected function _initDBMetaCache()
    // {
    //     $secLifetime = 60;

    //     $frontendOpts = array(
    //         'cache_id_prefix' => md5($_SERVER['SERVER_NAME']).'_table_desc_',
    //         'caching' => true,
    //         'lifetime' => $secLifetime,
    //         'automatic_serialization' => true
    //     );

    //     $backendOpts = array(
    //         'servers' =>array(
    //             array(
    //             'host' => '127.0.0.1',
    //             'port' => 11211
    //             )
    //         ),
    //         'compression' => false,
    //     );

    //     $Cache = Zend_Cache::factory('Core', 'Memcached', $frontendOpts, $backendOpts);

    //     // Next, set the cache to be used with all table objects
    //     Zend_Db_Table_Abstract::setDefaultMetadataCache($Cache);
    // }

    // protected function _initPlugins()
    // {
    //     $front = Zend_Controller_Front::getInstance();

    //     // Auth and ACL
    //     require_once 'controllers/plugins/AuthPlugin.php';
    //     $front->registerPlugin(new AuthPlugin());

    //     // Records audit data
    //     require_once 'controllers/plugins/AuditPlugin.php';
    //     $front->registerPlugin(new AuditPlugin());
    // }

    public function _initViewObject()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');

        // Add default script path
        $view->addScriptPath(APPLICATION_PATH . '/views/scripts');

        // Setup Title
        $view->headTitle('League Watch');
        $view->headTitle()->setSeparator(' - ');
        $view->headTitle()->setDefaultAttachOrder('PREPEND');
    }

    public function _initNavigation()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');

        $view->subNavigation = new Zend_Navigation();
    }



}

