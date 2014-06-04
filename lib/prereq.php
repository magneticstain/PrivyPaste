<?php
	/**
	 *  PrivyPaste
	 *  Author: Josh Carlson
	 *  Email: jcarlson(at)carlso(dot)net
	 */

	/*
	 * lib/prereq.php - a library that handles all of the commonly used prerequisites for php scripts
	 */

	// try to create PasteBin() object
	try
	{
		// try to create required db connection to send to Pastebin()
		$db_conn = Db::connectToDb();

		$pasteBin = new Pastebin($db_conn);
	}
	catch (Exception $e)
	{
		echo "Fatal error. Please contact your system administrator!";

		// log error
		error_log($e->getMessage(), 0);

		exit(1);
	}
?>