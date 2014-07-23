<?php
	/**
	 *  Josh Carlson
	 *  Created: 5/9/14 7:39 PM
	 *  Email: jcarlson@carlso.net
	 */

	/*
	 *  lib/Pastebin.php -  a library containing the class Pastebin() - an all-encompassing object with site-wide functions
	 * 						that other classes can inherit from. One of the components of the 'model' portion of the underlying MVC
	 * 						framework.
	 */

	class Pastebin
	{
		private $db_conn;
		private $user;

		public function __construct($db_conn)
		{
			if(
				!$this->setDbConn($db_conn)
//				|| !$this->setUser($user)
			)
			{
	//			echo $this->setUserId($userId)." -- ".$this->setUsername($username)." -- ".$this->setToken($token);
				// invalid; throw exception
				throw new Exception('FATAL ERROR: Pastebin() -> could not create new pastebin! Please verify the connection to the database.');
			}
		}

		// GETTERS
		public function getDbConn()
		{
			return $this->db_conn;
		}

//		public function getUser()
//		{
//			return $this->user;
//		}

		// SETTERS
		public function setDbConn($db_conn)
		{
			// set's db_conn object to perform db queries
			if(is_a($db_conn, 'mysqli'))
			{
				// set database connection object
				$this->db_conn = $db_conn;

				return true;
			}

			return false;
		}

//		public function setUser($user)
//		{
//			// set's User() object to store and manipulate user data
//			if(get_class($user) === 'User')
//			{
//				// set User() obj
//				$this->user = $user;
//
//				return true;
//			}
//
//			return false;
//		}

		// OTHER FUNCTIONS
		// SECURITY/SANATIZATION/VERIFICATION
		public static function isValidString($string)
		{
			// checks if string is valid (i.e. not blank or null)
			if(is_string($string))
			{
				// trim whitespace
				$string = trim($string);

				// check
				if(is_string($string) && !empty($string) && $string !== '\0') // protects from NULL byte vuln
				{
					// valid
					return true;
				}
			}

			// invalid
			return false;
		}

		// STATS
		public function getNumCurrentSessions()
		{
			// gets number of open sessions, server-side
			$numSessions = 0;

			// run sql query to get data
			$sql = "
					/* PrivyPaste - Pastebin - Stats - Session Count Lookup */
					SELECT
						count(*)
					FROM
						sessions
			";

			// create statement
			$db_stmt = $this->db_conn->prepare($sql);
			if($db_stmt)
			{
				// execute
				$db_stmt->execute();

				// bind result
				$db_stmt->bind_result($numSessions);

				// fetch result
				$db_stmt->fetch();
			}
			else
			{
				error_log('ERROR: Pastebin() -> Could not insert token into db -> ['.$this->db_conn->connect_error.']');
			}

			return $numSessions;
		}

		public function getTotalNumPastes()
		{
			// retrieve total number of pastes uploaded
			$totalNumPastes = 0;

			return $totalNumPastes;
		}

		public function getAvgNumPastes()
		{
			// calculates average number of pastes per day (24 hours)
			$avgNumPastes = 0;

			return $avgNumPastes;
		}

		// VIEW
		private function generateOutputNavMenu()
		{
			// generate a nav menu
			// default is stats, or the general menu for non-logged-in users if logins are enabled
			if(USE_LOGIN === true) // prevent mistakes such as using "false", i.e. as a string which would make this true
			{
				// set default login/sign up buttons
				$navMenu = '<p id="accountInfo"><a href="login.php" title="Login to your PrivyPaste Account">Login</a> | <a href="signup.php" title="Sign Up for a PrivyPaste Account">Sign Up</a></p>';

				// can be customized for those that are logged in
				// TODO: change to public static getLoginStatus() function in User() class
				if(isset($_COOKIE['user_id']) && $_COOKIE['user_id'] > 1)
				{
					// customized version
					$navMenu = '<p id="accountInfo">Josh Carlson &lt;magneticstain@gmail.com&gt; | <a href="pastes.php" title="View Your Pastes">Pastes</a> | <a href="account.php" title="Update Your Account">Account</a> | <a href="logout.php" title="Logout of Your Account">Logout</a></p>';
				}
			}
			else
			{
				// generate states
				$totalPastes = $this->getTotalNumPastes();
				$avgPastes = $this->getAvgNumPastes();
				$numSessions = $this->getNumCurrentSessions();

				// format
				$navMenu = '
					<div class="stats">
						<p>['.$totalPastes.']</p><p class="accent">Total Pastes:</p>
						<p>['.$avgPastes.']</p><p class="accent">Avg Pastes Per Day:</p>
						<p>['.$numSessions.']</p><p class="accent">Current Sessions:</p>
					</div>
				';
			}

			return $navMenu;
		}

		protected function outputHtml($key = 'index', $outputToScreen = false)
		{
			// outputs HTML based on given key (i.e. which page)
			// html header
			$header = '
				<!DOCTYPE html>
				<html>
					<head>
						<meta charset="utf-8"/>

						<title>PrivyPaste | Store your text securely and safely! | Home</title>

						<!-- CSS -->
						<link rel="stylesheet" type="text/css" href="css/master.css" />

						<!-- js -->
						<script src="js/jquery-1.11.1.min.js"></script>
						<script src="js/jquery.global.js"></script>
						<script src="js/jquery.textual.js"></script>
						<script src="js/jquery.controller.js"></script>
						<script src="js/jquery.js"></script>
					</head>
					<body id="home">
						<div id="ticker">
							<strong>Most Recent Pastes:</strong><a href="#">Test Alpha #1</a> &bull; <a href="#">Test Beta #2</a> &bull; <a href="#">Test Gamma #3</a> &bull; <a href="#">Test Gamma #4</a> &bull; <a href="#">Test Gamma #5</a>
						</div>
						<div id="container">
							<header>
								'.$this->generateOutputNavMenu().'
								<div id="logo">
									<img src="media/icons/paper_airplane.png" alt="Welcome to PrivyPaste!" />
									<h1 class="accent">Privy</h1><h1>Paste</h1>
								</div>
							</header>
			';

			// content html
			$content = '
							<section id="content">
			';

			// get rest of content html based on key(page)
			if($this->isValidString($key))
			{
				// currently only single key -- case is only an example for further development
				switch($key)
				{
					case '':

						break;

					default:
						// home page
						$content .= '
								<div id="text">
									<div id="textUploadButton">
										<div>
											<img src="media/icons/upload.png" alt="Upload your text" /> Upload Text
										</div>
									</div>
									<textarea id="mainText">Enter your text here!</textarea>
								</div>
						';

						break;
				}
			}

			// footer html
			$footer = '
							</section>
							<footer>
								2014 &copy; Joshua Carlson-Purcell | <a target="_blank" href="http://opensource.org/licenses/MIT">The MIT License (MIT)</a>
							</footer>
						</div>
					</body>
				</html>
			';

			// assemble html stream
			$html = $header.$content.$footer;

			// echo out if needed
			if(is_bool($outputToScreen) && $outputToScreen)
			{
				echo $html;
			}

			// if not visible, return html string
			return $html;
		}

		public function __toString()
		{
			// toString outputs default html, by default
			return $this->outputHtml();
		}
	}
?>