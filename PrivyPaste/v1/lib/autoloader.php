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
	        // extract class name - it is the last string delimited by '\'
	        $extractedClassName = end(explode('\\',$className));

            // load class from file, making sure to normalize the classname to all lowercase to match fine naming conventions
	        require BASE_DIR.__NAMESPACE__.'/v1/lib/'.strtolower($extractedClassName).'.php';
        }
    }

    // set autoload function in AutoLoad() class
    spl_autoload_register(__NAMESPACE__.'\\AutoLoader::loadClass');

?>