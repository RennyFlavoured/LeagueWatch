<?php
class Model_Log
{
    public static function debug($message, $data = null)
    {
        self::_recordLog('debug', $message, $data);
    }

    public static function trace($message, $data = null)
    {
        self::_recordLog('trace', $message, $data);
    }

    public static function info($message, $data = null)
    {
        self::_recordLog('info', $message, $data);
    }

    public static function error($message, $data = null)
    {
        self::_recordLog('error', $message, $data);
    }

    public static function exception($exception)
    {
        $data = array(
            'file' => self::_getFile($exception),
            'trace' => $exception->getTrace(),
        );

        self::_recordLog('exception', $exception->getMessage(), $data);
    }

    private static function _recordLog($type, $message = null, $data = null)
    {
        if (! in_array($type, array('debug', 'info', 'error', 'exception', 'trace'))) {
            throw new Exception("Invalid Log Type ({$type})");
        }

        // BEGIN: http://stackoverflow.com/questions/1252529/get-code-line-and-file-thats-executing-the-current-function-in-php
        $bt = debug_backtrace();
        $caller = array_shift($bt);
        $caller = array_shift($bt);
        $line = "{$caller['file']} ({$caller['line']})";
        // END: http://stackoverflow.com/questions/1252529/get-code-line-and-file-thats-executing-the-current-function-in-php

        $log = array(
            'timestamp' => gmdate('Y-m-d H:i:s'),
            'site' => $_SERVER['SERVER_NAME'],
            'hostname' => php_uname('n'),
            'uri' => $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'],
            'method' => (! empty($_SERVER['REQUEST_METHOD'])) ? $_SERVER['REQUEST_METHOD'] : null,
            'referrer' => (! empty($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : null,
            'ip' => (! empty($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : null,
            'useragent' => (! empty($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : null,

            'line' => $line,
            'message' => $message,

            'GET' => (! empty($_GET)) ? $_GET : null,
            'POST' => (! empty($_POST)) ? $_POST : null,
            'SESSION' => (! empty($_SESSION)) ? $_SESSION : null,

            'data' => $data,
        );

        file_put_contents(self::_getLogFilename($type), json_encode($log)."\n", FILE_APPEND);

        return true;
    }

    private static function _getFile(Exception $e)
    {
        $File = explode('/', $e->getFile());
        $Filename = array_pop($File);
        $Directory = array_pop($File);

        $File = explode('.', $Filename);
        $Filename = array_shift($File);

        return sprintf("%s/%s (%s)", $Directory, $Filename, $e->getLine());
    }

    private static function _getLogFilename($LogType)
    {
        return '/tmp/' . implode('-', array(
            date('Ymd'), // Date
            $_SERVER['SERVER_NAME'], // Domain name
            strtolower($LogType), // Log type
            php_uname('n'), // Server hostname
        ));
    }
}