<?php
	/**
	 *  PrivyPaste
	 *  Author: Josh Carlson
	 *  Email: jcarlson(at)carlso(dot)net
	 */

	/*
	 * lib/prereq.php - a library that handles all of the commonly used prerequisites for php scripts
	 */

	// try to create objects
	try
	{
		// try to create required db connection to send to object
		$db_conn = Db::connectToDb();

		// created needed objects
		// set them to default if not already set (false)
		// NOTE: must be checked separately as some scripts may only have one set purposely
		if(!isset($createPastebin))
		{
			$createPastebin = false;
		}

		if(!isset($createPaste))
		{
			$createPaste = false;
		}

		// Pastebin()
		if($createPastebin)
		{
			$pasteBin = new Pastebin($db_conn);
		}

		// Paste
		if($createPaste)
		{
			$paste = new Paste($db_conn);
		}
	}
	catch (Exception $e)
	{
		echo "Fatal error. Please contact your system administrator!";

		// log error
		error_log($e->getMessage(), 0);

		exit(1);
	}
?>