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
		protected $db_conn;
		protected $token;
		protected $userId;
		protected $username;

		public function __construct(
			$db_conn,
			$userId = 0,
			$username = 'default',
			$token = null
		)
		{
			if(
				!$this->setDbConn($db_conn)
				|| !$this->setUserId($userId)
				|| !$this->setUsername($username)
				|| !$this->setToken($token)
			)
			{
	//			echo $this->setUserId($userId)." -- ".$this->setUsername($username)." -- ".$this->setToken($token);
				// invalid; throw exception
				throw new Exception('ERROR: Pastebin() -> could not create new pastebin! Please verify the connection to the database.');
			}
		}

		// GETTERS
		public function getDbConn()
		{
			return $this->db_conn;
		}

		public function getUserId()
		{
			return $this->userId;
		}

		public function getUsername()
		{
			return $this->username;
		}

		public function getToken()
		{
			return $this->token;
		}

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

		public function setUserId($userId)
		{
			// user id must be a numeral up to 11 digits long (due to db constraints)
			if(is_numeric($userId) && ($userId >= 0 && $userId < 100000000000))
			{
				// valid
				$this->userId = $userId;

				return true;
			}

			// invalid
			return false;
		}

		public function setUsername($username)
		{
			// the username can be any string less than 100 characters

			// trim incoming stream
			$username = trim($username);

			if($this->isValidString($username))
			{
				// valid
				$this->username = $username;

				return true;
			}

			// invalid
			return false;
		}

		public function setToken($token)
		{
			// token is a 16 byte (128-bit) hex string of random bytes generated from OpenSSL
			$newToken = '';

			// check input token
			if($token === null)
			{
				// no token sent, start by checking for token cookie
				if(!$newToken = $this->loadCSRFToken())
				{
					// try to generate, add to the database, and set the cookie (<= purpose of two true params below) for a new token
					if(!$newToken = $this->generateCSRFToken(true, true))
					{
						// well, this sucks...
						// log error
						error_log('ERROR: Pastebin() -> could not generate CSRF token - please check OpenSSL library integrity and/or PHP version requirement!', 0);

						// something went wrong; OpenSSL is probably not present or < PHP5 installed
						return false;
					}
				}
			}
			elseif(ctype_xdigit($token) && strlen($token) === 64) // check if valid token string format
			{
				// token already generated
				$newToken = $token;
			}

			// check if there's something to set
			if(!empty($newToken))
			{
				// token found, set as object token
				$this->token = $newToken;

				return true;
			}

			// something went wrong...
			return false;
		}

		// OTHER FUNCTIONS
		// SECURITY/SANATIZATION/VERIFICATION
		public static function isValidString($string)
		{
			// checks if string is valid (i.e. not blank or null)
			// trim whitespace
			$string = trim($string);

			// check
			if(is_string($string) && !empty($string) && $string !== '\0') // protects from NULL byte vuln
			{
				// valid
				return true;
			}

			// invalid
			return false;
		}

		private function loadCSRFToken()
		{
			// function that loads the token cookie
			if(isset($_COOKIE['token']))
			{
				// validate
				if($this->checkTokenInDB($_COOKIE['token']))
				{
					return $_COOKIE['token'];
				}
				else
				{
					// get ip

					// check if behind proxy
					// most proxies will supply the X-FORWARDED-FOR HTTP header field
					$realIp = $_SERVER['REMOTE_ADDR'];
					if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
					{
						$realIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
					}

					error_log('ERROR: Pastebin() -> Invalid token supplied -> Remote IP:['.$_SERVER['REMOTE_ADDR'].'] -> Actual IP: ['.$realIp.']');
				}
			}

			return false;
		}

		private function generateCSRFToken($addCookie = false, $addToDb = false)
		{
			// generates a cryptographically secure token used to prevent CSRF attacks
			// gathered from OpenSSL

			// "cyptographically-strong" variable must be passed by reference with openssl_random_psuedo_bytes(),
			// so cflag is set in its own variable
			$cryptographicallySecure = true;

			$generatedToken = bin2hex(openssl_random_pseudo_bytes(32, $cryptographicallySecure));

			// check if a cookie should be generated for this
			if($addCookie)
			{
				// expire tokens after two weeks
				setcookie('token', $generatedToken, time()+1209600);
			}

			// check if token should be added to db
			if($addToDb)
			{
				if(!$this->addTokenToDB($generatedToken))
				{
					return false;
				}
			}

			return $generatedToken;
		}

		public function checkTokenInDB($token, $userId = 1)
		{
			// try to lookup the token in the db
			// i.e. - check for token validity
			if($this->isValidString($token))
			{
				$tokenId = 0;

				// set sql
				$sql = '
						/* PrivyPaste - Pastebin - User - Token Lookup */
						SELECT
							token_id
						FROM
							tokens
						WHERE
							token = ?
						AND
							user_id = ?
				';

				// lookup
				// create statement
				$db_stmt = $this->db_conn->prepare($sql);
				if($db_stmt)
				{
					// bind params
					$db_stmt->bind_param(
						'si',
						$token,
						$userId
					);

					// execute
					$db_stmt->execute();

					// bind results
					$db_stmt->bind_result($tokenId);

					// fetch
					$db_stmt->fetch();

					// check and return
					if($tokenId > 0)
					{
						// valid
						return true;
					}
				}
				else
				{
					error_log('ERROR: Pastebin() -> Could not lookup token -> ['.$this->db_conn->connect_error.']');
				}
			}

			return false;
		}

		private function addTokenToDB($token, $userId = 1)
		{
			// add a valid token to the token table in the db
			// set sql
			$sql = '
					/* PrivyPaste - Pastebin - User - Token Insert */
					INSERT
					INTO
						tokens
						(token, user_id)
					VALUES
						(?, ?)
			';

			// insert into db
			// create statement
			$db_stmt = $this->db_conn->prepare($sql);
			if($db_stmt)
			{
				// bind params
				$db_stmt->bind_param(
					'si',
					$token,
					$userId
				);

				// execute
				$db_stmt->execute();

				// check and return
				if($db_stmt->affected_rows === 1)
				{
					// valid
					return true;
				}
			}
			else
			{
				error_log('ERROR: Pastebin() -> Could not insert token into db -> ['.$this->db_conn->connect_error.']');
			}

			return false;
		}

		// VIEW
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
								<p id="accountInfo">Josh Carlson &lt;magneticstain@gmail.com&gt; | <a href="pastes.php" title="View Your Pastes">Pastes</a> | <a href="account.php" title="Update Your Account">Account</a> | <a href="signout.php" title="Sign Out of Your Account">Sign Out</a></p>
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