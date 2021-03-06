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
	// global
	require_once $_SERVER['DOCUMENT_ROOT'].'/'.__NAMESPACE__.'/conf/global.php';
	// db
	require_once BASE_DIR.__NAMESPACE__.'/conf/db.php';
	// pki
	require_once BASE_DIR.__NAMESPACE__.'/conf/pki.php';

	// classes
	// autoloader
	require_once BASE_DIR.__NAMESPACE__.'/lib/autoloader.php';

	$fullUrl = '';
	$errorMsg = '';
	$content = '';

	// create logging object
	try
	{
		# try to start a logging object
		$logger = new Logger();
	} catch(\Exception $e)
	{
		die('[FATAL] :: unable to start logging functionality :: '.$e->getMessage());
	}

	// get URL for links
	try
	{
		$fullUrl = PrivyPaste::getServerUrl(BASE_URL_DIR);
	} catch(\Exception $e)
	{
		$logger->setLogMsg('[WARN] :: could not get server URL :: using base URL dir ['.BASE_URL_DIR.']');
		$logger->setLogSrcFunction('main()');
		$logger->writeLog();
	}

	// create db connection required for PrivyPaste()
	try
	{
		# try to start a db connection
		$db = new Databaser(DB_USER, DB_PASS, DB_HOST, DB_NAME);
	} catch(\Exception $e)
	{
		$logMessage = '[FATAL] :: unable to create database object :: Please check database availability! :: '.$e->getMessage();

		$logger->setLogMsg($logMessage);
		$logger->setLogSrcFunction('main()');
		$logger->writeLog();

		# kill everything
		die($logMessage);
	}

	// format user-facing error message if not blank
	if($errorMsg !== '')
	{
		$errorMsg = 'ERROR: '.$errorMsg;
	}

	// create PrivyPaste() object and echo out page HTML
	try
	{
		$privypaste = new PrivyPaste($db, $content, $errorMsg, $fullUrl);
	} catch(\Exception $e)
	{
		$logMessage = '[FATAL] :: unable to start app engine :: Please check application availability! :: '.$e->getMessage();

		$logger->setLogMsg($logMessage);
		$logger->setLogSrcFunction('main()');
		$logger->writeLog();

		# kill everything
		die($logMessage);
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

		// set Cache-Control header for client-side cache control
		// only set if we're displaying a paste - the home page is dynamic
		PrivyPaste::setCacheControlHTTPHeader();
	}
	else
	{
		// append default content HTML (paste textbox)
		$content .= '
									<div id="newPasteTextUploadButton">
										<div>
											<img src="'.BASE_URL_DIR.'media/icons/upload.png" alt="Upload your text" /> Upload Text
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