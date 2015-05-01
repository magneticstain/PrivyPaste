<?php
	namespace PrivyPaste;

	/**
	 *  PrivyPaste
	 *  Author: Josh Carlson
	 *  Email: jcarlson(at)carlso(dot)net
	 */

	/*
	 *  api/$current_version/paste/get/ - api feature to get decrypted paste from the database
	 */

	//
	// CONFIGS AND LIBRARIES
	//

	// configuration files
	// global
	require_once $_SERVER['DOCUMENT_ROOT'].'/'.__NAMESPACE__.'/conf/global.php';
	// db
	require_once BASE_DIR.__NAMESPACE__.'/conf/db.php';
	// pki
	require_once BASE_DIR.__NAMESPACE__.'/conf/pki.php';

	// classes
	// autoloader
	require_once BASE_DIR.__NAMESPACE__.'/lib/autoloader.php';

	//
	// FUNCTIONS
	//
	function getPasteId()
	{
		// check for paste ID via GET var
		if(isset($_GET['id']) && !empty($_GET['id']))
		{
			// paste id set
			return $_GET['id'];
		}

		// in all other cases, return -1
		return -1;
	}

	//
	// MAIN
	//
	// initialize output var
	$jsonOutput = array();

	// check if paste ID was sent
	$pasteId = getPasteId();
	if($pasteId > 0)
	{
		// paste ID is set
		$paste = new Paste();

		// get paste ciphertext from db
		// create db connection
		$dbConn = '';
		try
		{
			$dbConn = new \PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
		} catch(\PDOException $e)
		{
			$jsonOutput['error'] = $e->getMessage();
		}

		// if connection was successful, try to retrieve encrypted paste
		if($dbConn !== '')
		{
			// connection is good, get text
			if($paste->retrieveCiphertextFromDb($dbConn, $pasteId))
			{
				// paste was retrieved successfully
				// decrypt the current ciphertext
				if($paste->decryptCiphertext())
				{
					// ciphertext was successfully decrypted, return new plaintext to user
					$jsonOutput['paste_text'] = $paste->getPlaintext();
				}
				else
				{
					// couldn't decrypt ciphertext, set error
					$jsonOutput['error'] = 'could not decrypt text';
				}
			}
			else
			{
				// could not get text with that id from the db, set error
				$jsonOutput['error'] = 'could not retrieve text with that paste ID';
			}
		}
	}

	// echo out json output
	echo json_encode($jsonOutput);
?>