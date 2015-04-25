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
		if(isset($_POST['text']) && !empty($_POST['text']))
		{
			// text var is set an non-empty
			return $_POST['text'];
		}

		// in all other cases, return an empty string
		return '';
	}

	//
	// MAIN
	//
	// initialize output var
	$jsonOutput = array();

	// check if plaintext was sent
	$plainText = checkForRawTxt();
	if($plainText !== '')
	{
		// plaintext is set
		// create new paste object
		try
		{
			$paste = new Paste($plainText);

			// encrypt plaintext within object
			if(!$paste->encryptPlaintext())
			{
				// encryption unsuccessful, set error
				$jsonOutput['error'] = 'Text encryption was unsuccessful. Please verify PKI certificates and application configs.';
			}

			// send text to db

//			echo '<pre>';
//			var_dump($paste);
//			echo '</pre>';
		} catch(\Exception $e)
		{
			$jsonOutput['error'] = $e->getMessage();
		}
	}

	// echo out json output
	echo json_encode($jsonOutput);
?>