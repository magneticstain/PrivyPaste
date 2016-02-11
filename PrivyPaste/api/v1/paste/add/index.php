<?php
	namespace PrivyPaste;

	/*
	 *  PrivyPaste
	 *  Author: Josh Carlson
	 *  Email: jcarlson(at)carlso(dot)net
	 */

	/*
	 *  api/$current_version/paste/add/ - api feature to insert a new, encrypted paste into the database
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
	// initialize output array w/ success variable which is included with all queries
	$jsonOutput = array(
		'success' => 0
	);

	// check if plaintext was sent
	$plainText = checkForRawTxt();
	if($plainText !== '')
	{
		// plaintext is set
		// create new paste object
		$paste = '';
		try
		{
			$paste = new Paste($plainText);
		} catch(\Exception $e)
		{
			$jsonOutput['error'] = $e->getMessage();
		}

		// check if paste() creation was successful
		if($paste !== '')
		{
			// encrypt plaintext within object
			if($iv = $paste->encryptPlaintext())
			{
				// text encryption was successful
				// send text to db
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
				// if connection was successful, attempt paste insertion
				if($db->createDbConnection())
				{
					// connection is good, insert text
					$newPasteId = $paste->sendPasteDataToDb($db);

					// any error with the query will generate a paste ID of -1
					if($newPasteId !== -1)
					{
						// paste insertion was a success, change success flag to true and return paste id
						$jsonOutput = array(
							'success' => 1,
							'paste_id' => $newPasteId
						);
					}
					else
					{
						// something went wrong with the db query, set an error
						$jsonOutput['error'] = 'paste failed to be inserted into the database';
					}
				}
				else
				{
					// could not connect to PP database, set an error
					$jsonOutput['error'] = 'could not connect to backend database';
				}
			}
			else
			{
				// encryption unsuccessful, set error
				$jsonOutput['error'] = 'Text encryption was unsuccessful. Please verify PKI certificates and application configs.';
			}
		}
	}

	// echo out json output
	echo json_encode($jsonOutput);
?>