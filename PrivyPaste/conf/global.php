<?php

    /**
     *  PrivyPaste
     *  Author: Josh Carlson
     *  Email: jcarlson(at)carlso(dot)net
     */

    /*
     *  conf/global.php - sets variables used by other functions throughout the project.
     */

	// URL
	/*
	 *  BASE_URL_DIR
	 *
	 *  Directory that includes the PrivyPaste installation files,
	 *
	 *  E.g.
	 *  URL is https://pp.example.com/apps/PrivyPaste/
	 *  BASE_URL_DIR = '/apps/PrivyPaste/'
	 *
	 *  Default: '/PrivyPaste/'
	 */
	define('BASE_URL_DIR', '/PrivyPaste/');

    // FILE STRUCTURE
	/*
	 *  BASE_DIR
	 *
	 *  location of privypaste folder/webapp files
	 *
	 *  Default: web server root directory
	 */
    define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'].'/');
?>