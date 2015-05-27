<?php
	namespace PrivyPaste;

	/**
	 *  PrivyPaste
	 *  Author: Josh Carlson
	 *  Email: jcarlson(at)carlso(dot)net
	 */

	/*
	 *  index.php - main starting point of webapp
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

	$errorMsg = '';
	$content = '';

	// get URL for links
	$fullUrl = PrivyPaste::getServerUrl(BASE_URL_DIR);

	// create db connection required for PrivyPaste()
	$db = '';
	try
	{
		$db = new Databaser(DB_USER, DB_PASS, DB_HOST, DB_NAME);
	} catch(\PDOException $e)
	{
		$errorMsg = "could not connect to PrivyPaste database!";
	}

	// format error message if not blank
	if($errorMsg !== '')
	{
		$errorMsg = 'ERROR: '.$errorMsg;
	}

	// create PrivyPaste() object and echo out page HTML
	$privypaste = new PrivyPaste($db, $content, $errorMsg, $fullUrl);

	// set content
	// must be set after in case a paste UID is sent as a GET var. In that case, we need PrivyPaste->url set before we query the API for the paste plaintext
	$content = '
								<div id="text">
	';
	// check if paste UID was sent
	if(isset($_GET['p']) && $_GET['p'] !== '')
	{
		// generate paste display content and append to $content
		$content .= $privypaste->generatePasteContentHtml($_GET['p'], true);
	}
	else
	{
		// append default content HTML (paste textbox)
		$content .= '
									<div id="textUploadButton">
										<div>
											<img src="media/icons/upload.png" alt="Upload your text" /> Upload Text
										</div>
									</div>
									<textarea id="mainText">Enter your text here!</textarea>
		';
	}
	// close #text div
	$content .= '
								</div>
	';
	$privypaste->setContent($content);

	echo $privypaste;
?>