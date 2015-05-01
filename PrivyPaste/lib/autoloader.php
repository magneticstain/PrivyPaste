<?php
    namespace PrivyPaste;

    /**
     *  PrivyPaste
     *  Author: Josh Carlson
     *  Email: jcarlson(at)carlso(dot)net
     */

    /*
     *  lib/autoloader.php - logic for autoloading classes
     *
     *  Requires:
     *      * conf/global.php
     */

    class AutoLoader
    {
        public static function loadClass($className)
        {
	        // extract class name
	        // - it is the last string delimited by '\' and we must make sure to normalize the class name to all lowercase to match fil e naming conventions
	        // - we also must get last string of the array separately to comply with php strict coding standards (cannot pass reference as variable)
	        $explodedClassName = explode('\\',$className);
	        $extractedClassName = strtolower(end($explodedClassName));

            // load class from file
	        require BASE_DIR.__NAMESPACE__.'/lib/'.$extractedClassName.'.php';
        }
    }

    // set autoload function in AutoLoad() class
    spl_autoload_register(__NAMESPACE__.'\\AutoLoader::loadClass');

?>