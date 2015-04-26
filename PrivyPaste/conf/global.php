<?php

    /**
     *  PrivyPaste
     *  Author: Josh Carlson
     *  Email: jcarlson(at)carlso(dot)net
     */

    /*
     *  conf/global.php - sets variables used by other functions throughout the project.
     */

    // FILE STRUCTURE
	/*
	 *  location of privypaste folder/webapp files
	 *
	 * Default: $_SERVER['DOCUMENT_ROOT']
	 */
    define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'].'/');

	// DB SETTINGS
	define('DB_HOST', '');
	define('DB_NAME', '');
	define('DB_USER', '');
	define('DB_PASS', '');

	// PKI
	define('PUBLIC_KEY', '/opt/privypaste/pki/public/public_key.pem');
	define('PRIVATE_KEY', '/opt/privypaste/pki/private/private_key.pem');
?>