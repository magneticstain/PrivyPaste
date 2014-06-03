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
	function autoloadPastebien($_conf_base_directory = '')
	{
		// define dependency filenames
		$dependencies = array(
			$_conf_base_directory.'/lib/pastebien.php'
		);

		openDependencies($dependencies);
	}

	function autoloadPastrie($_conf_base_directory = '')
	{
		// define dependency filenames
		$dependencies = array(
			$_conf_base_directory.'/lib/pastrie.php'
		);

		openDependencies($dependencies);
	}

	// REGISTER
	spl_autoload_register('autoloadPastebien');
	spl_autoload_register('autoloadPastrie');
?>