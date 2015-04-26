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
	function openLibrary($filename)
	{
		// check if available
		if(is_readable($filename))
		{
			echo 'Loading '.$filename.'...';
			require $filename;
		}
	}

	// AUTOLOAD FUNCTIONS
	function autoloadClass($className)
	{
		// load class library file
		$filename = BASE_DIR.'lib/'.$className.'.php';

		openLibrary($filename);
	}

	/*function autoloadDb()
	{
		// define dependency filenames
		$dependencies = array(
			BASE_DIR.'lib/db.php'
		);

		echo "Autoloading db...";

		openDependencies($dependencies);
	}

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

	function autoloadApi()
	{
		// define dependency filenames
		$dependencies = array(
			BASE_DIR.'lib/api.php'
		);

		openDependencies($dependencies);
	}

	function autoloadUser()
	{
		// define dependency filenames
		$dependencies = array(
			BASE_DIR.'lib/user.php'
		);

		openDependencies($dependencies);
	}*/

	// REGISTER
	spl_autoload_register('autoloadClass');
//	spl_autoload_register('autoloadDb');
//	spl_autoload_register('autoloadUser');
//	spl_autoload_register('autoloadPastebin');
//	spl_autoload_register('autoloadPaste');
//	spl_autoload_register('autoloadApi');
?>