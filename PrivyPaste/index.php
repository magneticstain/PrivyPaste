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
	} catch(\Exception $e)
	{
		die('FATAL ERROR: unable to connect to database!');
	}

	// format error message if not blank
	if($errorMsg !== '')
	{
		$errorMsg = 'ERROR: '.$errorMsg;
	}
//	else
//	{
//		$errorMsg = 'This is a demo of PrivyPaste. Application files will automatically be reset within 10 minutes.';
//	}

	// create PrivyPaste() object and echo out page HTML
	try
	{
		$privypaste = new PrivyPaste($db, $content, $errorMsg, $fullUrl);
	} catch(\Exception $e)
	{
		die('FATAL ERROR: unable to start app engine!');
	}

	// set content
	// must be set after in case a paste UID is sent as a GET var. In that case, we need PrivyPaste->url set before we query the API for the paste plaintext
	$content = '
								<div id="mainTextWorkspace">
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
									<div id="newPasteTextUploadButton">
										<div>
											<img src="media/icons/upload.png" alt="Upload your text" /> Upload Text
										</div>
									</div>
									<textarea id="newPasteText">Enter your text here!</textarea>
		';
	}
	// close #text div
	$content .= '
								</div>
	';

	// set context after it's been updated to reflect the paste UID GET variable
	$privypaste->setContent($content);

	// echo out the html
	echo $privypaste;
?>