<?php
	/**
	 *  PrivyPaste
	 *  Author: Josh Carlson
	 *  Email: jcarlson(at)carlso(dot)net
	 */

	/*
	 * lib/prereq.php - a library that handles all of the commonly used prerequisites for php scripts
	 */

	// include autoloader logic
	require_once 'autoloader.php';

	// try to create PasteBin() object
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
?>