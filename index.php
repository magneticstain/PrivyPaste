<?php
/**
 *  Josh Carlson
 *  Created: 5/24/14 10:42 PM
 *  Email: jcarlson@carlso.net
 */

	// include autoloader
	require_once 'lib/autoloader.php';

	// create PasteBin() object
	try
	{
		$pasteBin = new Pastebin();
	}
	catch (Exception $e)
	{
		echo "Fatal error. Please contact your system administrator!";

		// log error
		error_log($e->getMessage(), 0);

		exit(1);
	}

	// output paste form
	echo $pasteBin->getToken();
?>