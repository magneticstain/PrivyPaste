<?php
	/**
	 *  Josh Carlson
	 *  Created: 5/24/14 10:45 PM
	 *  Email: jcarlson@carlso.net
	 */

	/*
	 *  lib/Autoloader.php - the autoloader library that loads all libs needed for various classes
	 */

	// OTHER FUNCTIONS
	function openDependencies($filenames)
	{
		// open each one
		foreach($filenames as $file)
		{
			// check if available
			if(is_readable($file))
			{
				require $file;
			}
		}
	}

	// DEFINE
	function autoloadPastebin()
	{
		// define dependency filenames
		$dependencies = array(
			BASE_DIR.'lib/pastebin.php'
		);

		openDependencies($dependencies);
	}

	function autoloadPaste()
	{
		// define dependency filenames
		$dependencies = array(
			BASE_DIR.'lib/paste.php'
		);

		openDependencies($dependencies);
	}

	function autoloadDb()
	{
		// define dependency filenames
		$dependencies = array(
			BASE_DIR.'lib/db.php'
		);

		openDependencies($dependencies);
	}

	// REGISTER
	spl_autoload_register('autoloadPastebin');
	spl_autoload_register('autoloadPaste');
	spl_autoload_register('autoloadDb');
?>