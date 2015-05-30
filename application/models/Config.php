<?php

class Model_Config
{
    public static function getServiceUri() // TODO DA: Get rid of this
    {
        $Domain = Zend_Registry::get('currentDomain');
        return "{$Domain['schema']}://{$Domain['name']}";
    }

    private static $application = null;

    public static function setApplication($application)
    {
        self::$application = $application;
    }

    public static function getBootstrap()
    {
        if (empty(self::$application)) {
            return null;
        }
        return self::$application->getBootstrap();
    }

    public static function getConfig()
    {
        if (empty(self::$application)) {
            return null;
        }
        return self::$application->getOptions();
    }

    public static function getOauth($type = null)
    {
        $config = self::getConfig();
        $oauth = $config['oauth'];

        if ( (empty($type)) || (! in_array($type, array_keys($oauth))) ) {
            throw new Exception('Invalid type');
        }

        return $oauth[$type];
    }

    public static function getGlobals($type = null)
    {
        $config = self::getConfig();
        $globals = $config['globals'];

        if ( (empty($type)) || (! in_array($type, array_keys($globals))) ) {
            throw new Exception('Invalid type');
        }

        return $globals[$type];
    }
}
