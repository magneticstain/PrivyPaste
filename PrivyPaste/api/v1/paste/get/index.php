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
	function getPasteUid()
	{
		// check for paste ID via GET var
		if(isset($_GET['uid']) && !empty($_GET['uid']))
		{
			// paste id set
			return $_GET['uid'];
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
	$pasteUid = getPasteUid();
	if($pasteUid !== -1)
	{
		// paste ID is set
		$paste = new Paste();

		// get paste ciphertext from db
		// create Db() object for db connection
		$db = '';
		try
		{
			$db = new Databaser(DB_USER, DB_PASS, DB_HOST, DB_NAME);
		} catch(\Exception $e)
		{
			$jsonOutput['error'] = $e->getMessage();
		}

		// connect to db
		// if connection was successful, try to retrieve encrypted paste
		if($db->createDbConnection())
		{
			// connection is good, get text
			if($paste->retrieveCiphertextFromDb($db, $pasteUid))
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
				$jsonOutput['error'] = 'could not retrieve text with that paste UID';
			}
		}
		else
		{
			// could not connect to the database
			$jsonOutput['error'] = 'could not connect to database';
		}
	}

	// echo out json output
	echo json_encode($jsonOutput);
?>