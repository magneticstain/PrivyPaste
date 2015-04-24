<?php
	namespace PrivyPaste;

	/**
	 *  PrivyPaste
	 *  Author: Josh Carlson
	 *  Email: jcarlson(at)carlso(dot)net
	 */

	/*
	 *  api/$current_version/paste/add - api feature to insert a new, encrypted paste into the database
	 */

	//
	// CONFIGS AND LIBRARIES
	//

	// global configuration file
	require_once $_SERVER['DOCUMENT_ROOT'].'/'.__NAMESPACE__.'/conf/global.php';

	// class autoloader
	require_once BASE_DIR.__NAMESPACE__.'/lib/autoloader.php';

	//
	// FUNCTIONS
	//
	function checkForRawTxt()
	{
		// check for raw text sent via GET var
		if(isset($_GET['text']) && !empty($_GET['text']))
		{
			// text var is set an non-empty
			return $_GET['text'];
		}

		// in all other cases, return an empty string
		return '';
	}

	//
	// MAIN
	//

	// check if plaintext was sent
	$plainText = checkForRawTxt();
	if($plainText !== '')
	{
		// plaintext is set
		// create new paste object
		try
		{
			$paste = new Paste($plainText);

			echo '<pre>';
			var_dump($paste);
			echo '</pre>';
		} catch(\Exception $e)
		{
			echo 'ERROR: '.$e->getMessage();
		}
	}
?>