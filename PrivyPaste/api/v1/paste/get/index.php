<?php
	namespace PrivyPaste;

	/**
	 *  PrivyPaste
	 *  Author: Josh Carlson
	 *  Email: jcarlson(at)carlso(dot)net
	 */

	/*
	 *  api/$current_version/paste/get/ - api feature to get decrypted paste and its metadata from the database
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
			// normalize and return it
			return trim(strtolower($_GET['uid']));
		}

		// in all other cases, return blank string
		return '';
	}

	//
	// MAIN
	//
	// initialize output array w/ success variable which is included with all queries
	$jsonOutput = array(
		'success' => 0
	);

	// check if paste ID was sent
	$pasteUid = getPasteUid();
	if($pasteUid !== '')
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
			if($paste->retrievePasteDataFromDb($db, $pasteUid))
			{
				// paste was retrieved successfully
				// decrypt the current ciphertext
				if($paste->decryptCiphertext())
				{
					// ciphertext was successfully decrypted, return new plaintext and paste metadata to user
					$jsonOutput = array(
						'success' => 1,
						'paste_text' => $paste->getPlaintext(),
						'creation_time' => $paste->getCreationTime(),
						'last_modified_time' => $paste->getLastModifiedTime()
					);
				}
				else
				{
					// couldn't decrypt ciphertext, set error
					$jsonOutput['error'] = 'Could not decrypt text. Please verify PKI certificates and application configs.';
				}
			}
			else
			{
				// could not get text with that id from the db, set error
				$jsonOutput['error'] = 'Could not retrieve text with that paste UID';
			}
		}
		else
		{
			// could not connect to the database
			$jsonOutput['error'] = 'Could not connect to database';
		}
	}

	// echo out json output
	echo json_encode($jsonOutput);
?>