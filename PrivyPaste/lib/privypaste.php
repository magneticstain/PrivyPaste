<?php
	namespace privypaste;

	/**
	*  PrivyPaste
	*  Author: Josh Carlson
	*  Email: jcarlson(at)carlso(dot)net
	*/

    /*
     *  privypaste.php - class containing functions and data pertaining to the entire webapp
     */

    class PrivyPaste 
    {
		private $errorMsg = '';
	    private $subTitle = '';
	    private $content = '';
	    private $dbConn;

	    public function __construct($dbConn, $content, $errorMsg = '', $subTitle = 'Home')
	    {
		    if(
			    !$this->setErrorMsg($errorMsg)
			    || !$this->setSubTitle($subTitle)
			    || !$this->setContent($content)
		    )
		    {
			    // something went wrong
			    throw new \Exception('could not set error message!');
		    }
	    }

	    // SETTERS
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

		    // the only limitation is that the content cannot be blank/null
		    if($content !== '' || $content !== null)
		    {
			    // content is good
			    $this->content = $content;

			    return true;
		    }

		    return false;
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

		    // currently, we're just setting it outright since validation of it connection should already have been done
		    // if it hasn't been done, running any queries should generate an error and self-validate
		    $this->dbConn = $dbConn;
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

	    // OTHER FUNCTIONS
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

		    // prepare query
		    $dbStmt = $this->dbConn->prepare($sql);

		    // execute and get result
		    if($dbStmt->execute())
		    {
			    // query was executed successfully
			    $dbResults = $dbStmt->fetchAll();
			    if(count($dbResults) === 1)
			    {
				    // query produced results, return count
				    return $dbResults[0]['cnt'];
			    }
		    }

		    // anything goes wrong, return -1
		    return -1;
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

		    // normalize subtitle to all lowercase so it can be used as the body id
		    $lcSubTitle = strtolower($this->subTitle);

		    // get most recent pastes that will be displayed at the top of the page
		    // TODO: add function to generate actual recent pastes
		    $recentPasteHtml = '<strong>Most Recent Pastes:</strong><a href="#">Test Alpha #1</a> &bull; <a href="#">Test Beta #2</a> &bull; <a href="#">Test Gamma #3</a> &bull; <a href="#">Test Gamma #4</a> &bull; <a href="#">Test Gamma #5</a>';

		    // get total number of pastes which will be displayed in the header's subtitle
		    // TODO: create function to get actual number of pastes in the DB
		    $totalPastes = $this->getTotalPastes();

		    // build page html
		    $html = '
		        <!DOCTYPE html>
				<html>
					<head>
						<meta charset="utf-8"/>

						<title>'.$htmlTitle.'</title>

						<!-- CSS -->
						<link rel="stylesheet" type="text/css" href="css/master.css" />

						<!-- js -->
						<script src="js/jquery-1.11.1.min.js"></script>
						<script src="js/jquery.global.js"></script>
						<script src="js/jquery.errorator.js"></script>
						<script src="js/jquery.textual.js"></script>
						<script src="js/jquery.controller.js"></script>
						<script src="js/jquery.js"></script>
					</head>
					<body id="'.$lcSubTitle.'">
						<div id="ticker">
							'.$recentPasteHtml.'
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
									<img src="media/icons/paper_airplane.png" alt="Welcome to PrivyPaste!" />
									<h1 class="accent">Privy</h1><h1>Paste</h1>
								</div>
				                <p id="subtitle">Currently serving '.(string) $totalPastes.' encrypted pastes!</p>
							</header>
							<section id="content">
								'.$this->content.'
							</section>
							<footer>
								2014 &copy; Joshua Carlson-Purcell | <a target="_blank" href="http://opensource.org/licenses/MIT">The MIT License (MIT)</a>
							</footer>
						</div>
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