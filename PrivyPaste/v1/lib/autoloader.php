<?php
    namespace PrivyPaste;
    /**
     *  PrivyPaste
     *  Author: Josh Carlson
     *  Email: jcarlson(at)carlso(dot)net
     */

    /*
     *  autoloader - logic for autoloading classes
     */

    require_once $_SERVER['DOCUMENT_ROOT'] . '/v1/conf/global.php';

    class AutoLoader
    {
        public static function loadClass($className)
        {
            // load class from file
            require $baseDir.'v1/lib/'.$className;
        }
    }

    spl_autoload_register(function($class)
    {
        // set namespace prefix
        $nsPrefix = 'PrivyPaste';
    });

?>