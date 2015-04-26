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

	// configuration files
	// global
	require_once $_SERVER['DOCUMENT_ROOT'].'/'.__NAMESPACE__.'/conf/global.php';
	// db
	require_once BASE_DIR.__NAMESPACE__.'/conf/db.php';
	// pki
	require_once BASE_DIR.__NAMESPACE__.'/conf/pki.php';

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
		$paste = '';

		// plaintext is set
		// create new paste object
		try
		{
			$paste = new Paste($plainText);

//			echo '<pre>';
//			var_dump($paste);
//			echo '</pre>';
		} catch(\Exception $e)
		{
			$jsonOutput['error'] = $e->getMessage();
		}

		// check if paste() creation was successful
		if($paste !== '')
		{
			// encrypt plaintext within object
			if(!$paste->encryptPlaintext())
			{
				// encryption unsuccessful, set error
				$jsonOutput['error'] = 'Text encryption was unsuccessful. Please verify PKI certificates and application configs.';
			}
			else
			{
				// send text to db
				$dbConn = '';

				// create db connection
				try
				{
					$dbConn = new \PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
				} catch(\PDOException $e)
				{
					$jsonOutput['error'] = $e->getMessage();
				}

				// if connection was successful, attempt paste insertion
				if($dbConn !== '')
				{
					// connection is good, insert text
					$newPasteId = $paste->sendCiphertextToDb($dbConn);

					if($newPasteId > 0)
					{
						// paste insertion was a success, return paste id
						$jsonOutput['paste_id'] = $newPasteId;
					} else
					{
						// something went wrong with the db query, set an error
						$jsonOutput['error'] = 'paste failed to be inserted into the database';
					}
				}
			}
//
//			echo "[DEBUG] CIPHERTEXT:\n";
//			echo $paste->getCiphertext();
//			echo "\n";
		}
	}

	// echo out json output
	echo json_encode($jsonOutput);
?>