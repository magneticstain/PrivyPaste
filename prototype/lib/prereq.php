<?php
	/**
	 *  PrivyPaste
	 *  Author: Josh Carlson
	 *  Email: jcarlson(at)carlso(dot)net
	 */

	/*
	 * lib/prereq.php - a library that handles all of the commonly used prerequisites for php scripts
	 */

	// set flags to default if not already set (false)
	// NOTE: must be checked separately as some scripts may only have one set purposely
	if(!isset($createPastebinObj))
	{
		$createPastebinObj = false;
	}

	if(!isset($createPasteObj))
	{
		$createPasteObj = false;
	}

	if(!isset($createApiObj))
	{
		$createApiObj = false;
	}

	// try to create objects
	try
	{
		// try to create required db connection to send to object
		$db_conn = Db::connect();

		// try to create user object
		$user = new User($db_conn);

		// create needed objects
		// Pastebin()
		if($createPastebinObj)
		{
			$pasteBin = new Pastebin($db_conn);
		}

		// Paste
		if($createPasteObj)
		{
			$paste = new Paste($db_conn);
		}
		
//		// API
		if($createApiObj)
		{
			$api = new Api($db_conn);
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