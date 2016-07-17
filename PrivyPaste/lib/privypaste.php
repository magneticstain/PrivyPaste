<?php
	namespace PrivyPaste;

	/*
	 *  PrivyPaste
	 *  Author: Josh Carlson
	 *  Email: jcarlson(at)carlso(dot)net
	 */

    /*
     *  privypaste.php - class containing functions and data pertaining to the entire webapp
     */

    class PrivyPaste 
    {
	    private $url = '';
		private $errorMsg = '';
	    private $subTitle = '';
	    private $content = '';
	    private $dbConn;
	    private $logger;

	    public function __construct($dbConn, $content, $errorMsg = '', $url = 'http://www.example.org/', $subTitle = 'Home')
	    {
		    // start logging
		    $this->logger = new Logger();

		    if(
			    !$this->setUrl($url)
			    || !$this->setErrorMsg($errorMsg)
			    || !$this->setSubTitle($subTitle)
			    || !$this->setContent($content)
			    || !$this->setDbConn($dbConn)
		    )
		    {
			    // something went wrong
			    throw new \Exception('could not set main privypaste object!');
		    }
	    }

	    // SETTERS
	    public function setUrl($url)
	    {
		    /*
             *  Params:
             *      - $url
		     *          - full URL to access PrivyPaste
             *
             *  Usage:
             *      - verifies and sets the URL of the PrivyPaste site
             *
             *  Returns:
             *      - boolean
             */

		    // $url must be in a valid format. whether it's the correct URL will be validated during testing by the user
		    if(filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED) !== false)
		    {
			    // valid URL
			    $this->url = $url;

			    return true;
		    }

		    return false;
	    }

	    public function setErrorMsg($errorMsg)
	    {
		    /*
             *  Params:
             *      - $errorMsg
		     *          - the error msg to be displayed
             *
             *  Usage:
             *      - verifies and sets any error msg that must be displayed to the user
             *
             *  Returns:
             *      - boolean
             */

		    // normalize param to string
		    $errorMsg = (string) $errorMsg;

		    // there are no limitations on what the error message can be, so we can just set it
		    $this->errorMsg = $errorMsg;

		    return true;
	    }

	    public function setSubTitle($subTitle)
	    {
		    /*
             *  Params:
             *      - $subTitle
		     *          - the subtitle to be used with the <title> HTML tag
             *
             *  Usage:
             *      - verifies and sets the page subtitle
             *
             *  Returns:
             *      - boolean
             */

		    // normalize param to string
		    $subTitle = (string) $subTitle;

		    // there are no limitations on what the subtitle can be, so we can just set it
		    $this->subTitle = $subTitle;

		    return true;
	    }

	    public function setContent($content)
	    {
		    /*
             *  Params:
             *      - $content
		     *          - the HTML-formatted content to be used in the content div
             *
             *  Usage:
             *      - verifies and sets the page content
             *
             *  Returns:
             *      - boolean
             */

		    // no limitation on content as it's sometimes set after object creation
		    $this->content = $content;

		    return true;
	    }

	    public function setDbConn($dbConn)
	    {
		    /*
             *  Params:
             *      - $dbConn
		     *          - database connection in the form of a PDO object
             *
             *  Usage:
             *      - verifies and sets the database connection object
             *
             *  Returns:
             *      - boolean
             */

		    // currently, we're just setting it outright since validation of the connection should already have been done
		    // if it hasn't been done, running any queries should generate an error and self-validate
		    $this->dbConn = $dbConn;

		    return true;
	    }

	    // GETTERS
	    public function getErrorMsg()
	    {
		    /*
             *  Params:
             *      - NONE
             *
             *  Usage:
             *      - returns the current error message
             *
             *  Returns:
             *      - string
             */

		    return $this->errorMsg;
	    }

	    public function getSubTitle()
	    {
		    /*
             *  Params:
             *      - NONE
             *
             *  Usage:
             *      - returns the current page subtitle
             *
             *  Returns:
             *      - string
             */

		    return $this->subTitle;
	    }

	    public function getContent()
	    {
		    /*
             *  Params:
             *      - NONE
             *
             *  Usage:
             *      - returns the current page content
             *
             *  Returns:
             *      - string
             */

		    return $this->content;
	    }

	    public function getDbConn()
	    {
		    /*
             *  Params:
             *      - NONE
             *
             *  Usage:
             *      - returns the database connection object (PDO)
             *
             *  Returns:
             *      - PDO
             */

		    return $this->dbConn;
	    }

	    public function getUrl()
	    {
		    /*
             *  Params:
             *      - NONE
             *
             *  Usage:
             *      - returns the full base URL of the PrivyPaste site
             *
             *  Returns:
             *      - string
             */

		    return $this->url;
	    }

	    // OTHER FUNCTIONS
	    // METADATA
	    public static function getServerUrl($basePath)
	    {
		    /*
             *  Params:
		     *      - $basePath
		     *          - base path of URL
		     *          - set in global config
             *
             *  Usage:
             *      - generates the base URL of the PrivyPaste server
		     *      - should be in the format of [PROTOCOL]://[DOMAIN NAME][BASE_PATH]
             *
             *  Returns:
             *      - string
             */

		    // check if base path was set and not empty
		    if(isset($basePath) && !empty($basePath))
		    {
			    // all requirements satisfied
			    // set protocol (HTTP or HTTPS)
			    if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
			    {
				    // use TLS
				    $protocol = 'https';
			    }
			    else
			    {
				    // use normal HTTP
				    $protocol = 'http';
			    }

			    // get domain name
			    $domainName = $_SERVER['SERVER_NAME'];

			    // build and return URL
			    return $protocol.'://'.$domainName.$basePath;
		    }

		    return '';
	    }

	    public function getRelativeTimeFromTimestamp($datetime)
	    {
		    /*
             *  Params:
             *      - $datetime
		     *          - timestamp to convert in datetime format
             *
             *  Usage:
             *      - converts timestamp in datetime format (1970-01-01 00:00:00) to relative time format (35 years ago)
		     *      - prefer not to use exact timestamp when displaying timestamp to user as that can create minor security issues
             *
             *  Returns:
             *      - string
		     *
		     *  Citation:
		     *      - StackOverflow
		     *          - https://stackoverflow.com/users/1011145/nick
		     *          - http://stackoverflow.com/a/8244878
             */

		    // normalize to unix timestamp
		    if(is_numeric($datetime))
		    {
			    $timestamp = $datetime;
		    }
		    elseif(empty($datetime))
		    {
			    $timestamp = time();
		    }
		    else
		    {
			    $timestamp = strtotime($datetime);
		    }

		    // get time difference in seconds
		    $diff = time() - $timestamp;

		    // set time measurements
		    $min = 60;
		    $hour = 60 * 60;
		    $day = 60 * 60 * 24;
		    $month = $day * 30;

		    if($diff < 60)
		    {
			    // under a min
			    $timeAgo = $diff.' seconds';
		    }
		    elseif($diff < $hour)
		    {
			    // under an hour
			    $timeAgo = round($diff/$min).' minutes';
		    }
		    elseif($diff < $day)
		    {
			    // under a day
			    $timeAgo = round($diff/$hour).' hours';
		    }
		    elseif($diff < $month)
		    {
			    // under a month
			    $timeAgo = round($diff/$day).' days';
		    }
		    else
		    {
			    $timeAgo = round($diff/$month).' months';
		    }

		    return $timeAgo;
	    }

	    public static function setCacheControlHTTPHeader($numSecsUntilExp = 2592000)
	    {
		    /*
             *  Params:
             *      - $numSecsUntilExp
		     *          - amount of seconds until the browser should consider the page expired and require a server-side request
		     *          - default is 2592000s or 30 days
             *
             *  Usage:
             *      - sets the HTTP header 'Cache-Control' in order to better control client-side (browser) caching
             *
             *  Returns:
             *      - bool
             */

		    // cast expiration timespan as int
		    $numSecsUntilExp = (int)$numSecsUntilExp;

		    // set header
		    header('Cache-Control: max-age='.$numSecsUntilExp);

		    return true;
	    }

	    // DATA GATHERING
	    public function getMostRecentlyModifiedPastes($numPastes = 5)
	    {
		    /*
             *  Params:
             *      - $numPastes
		     *          - number of most recent pastes to retrieve
             *
             *  Usage:
             *      - gets the X most recent pastes from the db along with the timestamp they were last modified
             *
             *  Returns:
             *      - array
             */

		    // normalize $numberPastes
		    $numPastes = (int) $numPastes;

		    // craft sql query
		    $sql = "SELECT uid, last_modified, ciphertext, initialization_vector FROM pastes ORDER BY last_modified DESC LIMIT :num_pastes";

		    // set sql bind var array
		    $sqlParams = array(
			    'num_pastes' => array(
				    $numPastes,
				    'i'
			    )
		    );

		    // connect to db
		    // if connection was successful, attempt paste insertion
		    if($this->dbConn->createDbConnection())
		    {
			    // connection is good, execute query
			    $dbResults = $this->dbConn->queryDb($sql, 'select', $sqlParams);;
			    if($dbResults)
			    {
				    // results returned by query, get decrypted value of each and re-save
				    $paste = new Paste();
				    $decryptedResults = array();
				    foreach($dbResults as $row)
				    {
					    // extract ciphertext and IV from sql result row
					    $pasteCiphertext = $row['ciphertext'];
					    $pasteIV = $row['initialization_vector'];

					    // set ciphertext of Paste()
					    $paste->setCiphertext($pasteCiphertext);

					    // set IV
					    $paste->setInitializationVector(hex2bin($pasteIV));

					    // decrypt text
					    if($paste->decryptCiphertext())
					    {
						    // set last_modified timestamp and decrypted text as array and append to decrypted results array as new entry
						    array_push($decryptedResults, array($row['uid'], $row['last_modified'], $paste->getPlaintext()));
					    }
					    else
					    {
						    // could not decrypt ciphertext

						    # log error
						    $this->logger->setLogMsg('could not decrypt ciphertext of paste [ '.$row['uid'].' ]');
						    $this->logger->setLogSrcFunction('PrivyPaste() -> getMostRecentlyModifiedPastes()');
						    $this->logger->writeLog();

						    array_push($decryptedResults, array('#', date('Y-m-d H:i:s'), '[UNKNOWN]'));
					    }
				    }

				    return $decryptedResults;
			    }
			    else
			    {
				    // nothing returned from db, return empty array for function
				    return array();
			    }
		    }
		    else
		    {
			    # log error
			    $this->logger->setLogMsg('could not connect to database :: using user ['.$this->dbConn->getUsername().']');
			    $this->logger->setLogSrcFunction('PrivyPaste() -> getMostRecentlyModifiedPastes()');
			    $this->logger->writeLog();
		    }

		    // anything goes wrong, set error and return 0
		    $this->errorMsg = 'Unable to access paste database. Please contact your system administrator.';
		    return 0;
	    }

	    public function getTotalPastes()
	    {
		    /*
             *  Params:
             *      - NONE
             *
             *  Usage:
             *      - gets the total number of pastes currently stored in the db
             *
             *  Returns:
             *      - int
             */

		    // craft SQL query
		    $sql = "SELECT count(*) as cnt FROM pastes";

		    // connect to db
		    // if connection was successful, attempt paste insertion
		    if($this->dbConn->createDbConnection())
		    {
			    // connection is good, execute query
			    $dbResults = $this->dbConn->queryDb($sql, 'select');
			    if($dbResults)
			    {
				    // results returned by query, return results to user
				    return $dbResults[0]['cnt'];
			    }
		    }

		    // anything goes wrong, set error message and return 0
		    $this->errorMsg = 'Unable to access paste database. Please contact your system administrator.';

		    # log error
		    $this->logger->setLogMsg('could not connect to database :: using user ['.$this->dbConn->getUsername().']');
		    $this->logger->setLogSrcFunction('PrivyPaste() -> getTotalPastes()');
		    $this->logger->writeLog();

		    return 0;
	    }

	    // VIEW/HTML
	    public function generateMostRecentlyModifiedPastesHtml()
	    {
		    /*
             *  Params:
             *      - NONE
             *
             *  Usage:
             *      - generates HTML for last X modified pastes
             *
             *  Returns:
             *      - string
             */

		    // get most recent pastes from db
		    $recentPastes = $this->getMostRecentlyModifiedPastes();

		    // generate and return html
		    $recentPasteHtml = '<strong>Most Recent Pastes: </strong>';
		    // check if there were any pastes returned
		    if(count($recentPastes) <= 0)
		    {
			    // no pastes created yet
			    $recentPasteHtml .= '<p>No pastes have been created yet!</p>';
		    }
		    elseif($recentPastes === 0)
		    {
			    // couldn't get pastes for whatever reason
			    $recentPasteHtml .= '<p>No pastes could be found!</p>';
		    }
		    else
		    {
			    foreach($recentPastes as $pastNum => $paste)
			    {
				    // truncate and sanatize paste and convert last modified timestamp to relative timestamp for display in view
				    $rawPaste = htmlspecialchars($paste[2]);

				    // check encoding of raw paste
				    if(mb_check_encoding($rawPaste, 'UTF-8'))
				    {
					    // string is in UTF-8 format, use mb_substr()
					    $truncatedPaste = mb_substr($rawPaste, 0, 25, 'UTF-8');
				    }
				    else
				    {
					    // normal ASCII, use substr()
					    $truncatedPaste = substr($rawPaste, 0, 25);
				    }
				    $relativeLastModifiedTime = $this->getRelativeTimeFromTimestamp($paste[1]);

				    // build paste link and append to recent paste html
				    $recentPasteHtml .= '<a href="'.$this->url.'paste/'.$paste[0].'" title="Last modified '.$relativeLastModifiedTime.' ago">'.$truncatedPaste.'</a>';

				    // append bullet if not last paste
				    if($pastNum !== (count($recentPastes) - 1))
				    {
					    $recentPasteHtml .= ' &bull; ';
				    }
			    }
		    }

		    return $recentPasteHtml;
	    }

	    public function generateHeadingSubArea()
	    {
		    /*
             *  Params:
             *      - NONE
             *
             *  Usage:
             *      - generates HTML for the heading subarea, including user info
             *
             *  Returns:
             *      - string
             */

		    // initialize wrapper div
		    $subAreaHTML = '
								<div id="subArea">
			';

		    // get total number of pastes which will be displayed in the header's subtitle
		    $totalPastes = $this->getTotalPastes();

		    // ################################
		    // TODO check if user is logged in
		    $isLoggedIn = false;
		    // ################################

		    if($isLoggedIn)
		    {
				$subAreaHTML .= '
									<p id="userArea">
										<img src="'.BASE_URL_DIR.'media/icons/user.png" alt="Logged in as [jcarlson]" />
										 <span>logged in as </span><a href="#">jcarlson</a>
									</p>
									<hr />
					                <p id="pasteTotals">
					                    Currently serving '.(string) $totalPastes.' encrypted pastes!
					                </p>
				';
		    }
		    else
		    {
			    $subAreaHTML .= '
					                <p id="pasteTotalsStandalone">
					                    Currently serving '.(string) $totalPastes.' encrypted pastes!
					                </p>
			    ';
		    }

		    // close wrapper div
		    $subAreaHTML .= '
								</div>
		    ';

		    return $subAreaHTML;
	    }

	    public function generatePasteContentHtml($pasteUid, $updateTitle = false)
	    {
		    /*
             *  Params:
             *      - $pasteUid
		     *          - UID of paste to wrap HTML around
             *
             *  Usage:
             *      - generates HTML to be used to display a paste to the user
             *
             *  Returns:
             *      - string
             */

		    $pasteHtml = '';

		    // get paste from API
		    // set API URL
		    $apiUrl = $this->url.'api/v1/paste/get/?uid='.$pasteUid;

		    // query API and decode JSON to array
		    $rawPasteData = file_get_contents($apiUrl);
		    if($rawPasteData === false)
		    {
			    // fallback to disabling certificate verification
			    // this can happen a lot if the user is using an internal or built-in cert
			    // see this stackoverflow article for more info: http://stackoverflow.com/q/26148701/2625915
			    $fileGetContentsOptions=array(
				    "ssl"=>array(
					    "verify_peer"=>false,
					    "verify_peer_name"=>false,
				    ),
			    );

			    $rawPasteData = file_get_contents($apiUrl, false, stream_context_create($fileGetContentsOptions));
		    }

		    // decode results
		    $pasteJson = json_decode($rawPasteData);

		    // see if API query was successful
		    if(isset($pasteJson->paste_text))
		    {
			    // API returned text, generate HTML with it
			    $htmlTranslatedPaste = htmlspecialchars($pasteJson->paste_text);

			    // generate paste title
			    // check encoding of raw paste
			    if(mb_check_encoding($htmlTranslatedPaste, 'UTF-8'))
			    {
				    // string is in UTF-8 format, use mb_substr()
				    $pasteTitle = mb_substr($htmlTranslatedPaste, 0, 20, 'UTF-8');
			    }
			    else
			    {
				    // normal ASCII, use substr()
				    $pasteTitle = substr($htmlTranslatedPaste, 0, 20);
			    }
			    // if paste is longer than 20 characters, add elipses
			    if(strlen($pasteJson->paste_text) > 20)
			    {
				    $pasteTitle = $pasteTitle.'...';
			    }

			    // generate timestamps for paste time metadata
			    $pasteCreation = $this->getRelativeTimeFromTimestamp($pasteJson->creation_time);
			    $pasteLastModified = $this->getRelativeTimeFromTimestamp($pasteJson->last_modified_time);

			    // process paste to HTML
			    // replace newlines in escaped HTML with <br />
			    $pasteTextHtml = nl2br($htmlTranslatedPaste);

			    $pasteHtml = '
	                        <div id="pasteTextHtmlHeading">
	                            <h2>'.$pasteTitle.'</h2>
	                            <div class="metadataCells created">
	                                <p>Created:</p>
	                                <p>'.$pasteCreation.' ago</p>
	                            </div>
	                            <div class="metadataCells lastModified">
	                                <p>Last Modified:</p>
	                                <p>'.$pasteLastModified.' ago</p>
	                            </div>
	                        </div>
	                        <div id="pasteTextHtml">
	                            '.$pasteTextHtml.'
	                        </div>
	                        <div id="pastePlaintextHeading">
	                            <h3>Paste Plaintext</h3>
	                        </div>
	                        <textarea id="pastePlaintext">'.$pasteJson->paste_text.'</textarea>
			    ';
		    }
		    else
		    {
			    // generate HTML for paste not found
			    $pasteTitle = 'Paste Not Found!';
			    $pasteHtml = '
	                        <div id="pasteTextHtmlHeading">
	                            <h2>Paste Not Found!</h2>
	                        </div>
	                        <div id="pasteTextHtml">
								<p>Paste could not be found! Please check your URL and try again.</p>
	                        </div>
			    ';
		    }

		    // see if title should be updated
		    if($updateTitle)
		    {
			    $this->setSubTitle($pasteTitle);
		    }

		    return $pasteHtml;
	    }

	    public function generateHtmlPage()
	    {
		    /*
             *  Params:
             *      - NONE
             *
             *  Usage:
             *      - generates an HTML page from template
             *
             *  Returns:
             *      - string
             */

		    // set title
		    $htmlTitle = 'PrivyPaste | Store your text securely and safely! | '.$this->subTitle;

		    // get most recent pastes that will be displayed at the top of the page
		    $lastModifiedPastesHTML = $this->generateMostRecentlyModifiedPastesHtml();

		    // get header subArea HTML
		    $subAreaHTML = $this->generateHeadingSubArea();

		    // build page html
		    $html = '
		        <!DOCTYPE html>
				<html>
					<head>
						<meta charset="utf-8"/>
						
						<link rel="shortcut icon" type="image/x-icon" href="media/favicon.ico" />

						<title>'.$htmlTitle.'</title>

						<!-- CSS -->
						<link rel="stylesheet" type="text/css" href="'.BASE_URL_DIR.'css/master.css" />

					</head>
					<body id="index">
						<div id="vars">
							<span class="base_url">'.$this->url.'</span>
						</div>
						<div id="ticker">
							'.$lastModifiedPastesHTML.'
						</div>
						<div id="errorMsgWrapper">
							<div id="errorMsg">
								<p>'.$this->errorMsg.'</p>
							</div>
						</div>
						<div id="container">
							<header>
								<!--<p id="accountInfo">Josh Carlson &lt;magneticstain@gmail.com&gt; | <a href="pastes.php" title="View Your Pastes">Pastes</a> | <a href="account.php" title="Update Your Account">Account</a> | <a href="signout.php" title="Sign Out of Your Account">Sign Out</a></p>-->
								<div id="logo">
									<a href="'.$this->url.'">
										<img src="'.BASE_URL_DIR.'media/icons/paper_airplane.png" alt="Welcome to PrivyPaste!" />
										<h1 class="accent">Privy</h1><h1>Paste</h1>
									</a>
								</div>
								'.$subAreaHTML.'
							</header>
							<section id="content">
								'.$this->content.'
							</section>
							<footer>
								<a target="_blank" href="https://github.com/magneticstain/PrivyPaste">Project Home</a> | <a target="_blank" href="http://opensource.org/licenses/MIT">The MIT License (MIT)</a>
							</footer>
						</div>
					
						<!-- js -->
						<script src="'.BASE_URL_DIR.'js/jquery-3.1.0.min.js"></script>
						<script src="'.BASE_URL_DIR.'js/jquery.global.js"></script>
						<script src="'.BASE_URL_DIR.'js/jquery.errorator.js"></script>
						<script src="'.BASE_URL_DIR.'js/jquery.textual.js"></script>
						<script src="'.BASE_URL_DIR.'js/jquery.controller.js"></script>
						<script src="'.BASE_URL_DIR.'js/jquery.js"></script>
					</body>
				</html>
		    ';

		    return $html;
	    }

	    public function __toString()
	    {
		    // magic method that returns data to be used when the object is treated as a string
		    // i.e. echo $privypaste, where $privypaste = new PrivyPaste($content)

		    // get html and return it
		    return $this->generateHtmlPage();
	    }
    }
?>