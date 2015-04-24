<?php
    namespace PrivyPaste;
    /**
     *  PrivyPaste
     *  Author: Josh Carlson
     *  Email: jcarlson(at)carlso(dot)net
     */

    /*
     *  autoloader.php - logic for autoloading classes
     *
     *  Requires:
     *      * conf/global.php
     */

    class AutoLoader
    {
        public static function loadClass($className)
        {
	        // extract class name
	        // - it is the last string delimited by '\' and we must make sure to normalize the classname to all lowercase to match fine naming conventions
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