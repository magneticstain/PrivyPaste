<?php
	/**
	 *  Privypaste
	 *  /conf/base.php
	 *  Josh Carlson
	 *  Email: jcarlson@carlso.net
	 */

	// HTTP PARAMETERS
	// set http parameters - e.g. base url, base directory
	// Base URL
	// change me before putting into production!
	define('BASE_URL', 'http://www.carlsonet.io');

	// Base Directory
	// NOTE: make sure to include the ending '/'
	// you MAY have to change me before putting into production...
	define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'].'PrivyPaste/');

	// USER OPTIONS
	// Add Login Functionality
	// turn on if you would like to utilize HTTP Basic Auth (recommended to use SSL along with this option)
	define('USE_LOGIN', false);

	// SECURITY
	// Certificates
	// specify the location of your RSA certificates (should be generated separately)
	// Private Key
	define('PRIVATE_KEY', '');

	# Private Key Password (Opt.)
	define('PRIVATE_KEY_PASS', '');

	// Public Key
	define('PUBLIC_KEY', '');
?>