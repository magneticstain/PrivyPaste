<?php
	namespace PrivyPaste;

	/**
	 *  PrivyPaste
	 *  Author: Josh Carlson
	 *  Email: jcarlson(at)carlso(dot)net
	 */

	/*
	 *  api/$current_version/paste/add - api feature to insert a new, encrypted paste into the database
	 */

	//
	// CONFIGS AND LIBRARIES
	//

	// global configuration file
	require_once $_SERVER['DOCUMENT_ROOT'].'/'.__NAMESPACE__.'/v1/conf/global.php';

	// class autoloader
	require_once BASE_DIR.__NAMESPACE__.'/v1/lib/autoloader.php';

	//
	// MAIN
	//

	// create new paste object
    try
    {
        $paste = new Paste();

        echo '<pre>';
        var_dump($paste);
        echo '</pre>';
    }
    catch (\Exception $e)
    {
        echo 'ERROR: '.$e->getMessage();
    }
?>