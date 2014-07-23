<?php
	/**
	 *  PrivyPaste
	 *  Author: Josh Carlson
	 *  Email: jcarlson(at)carlso(dot)net
	 */

	/*
	 *  lib/User.php -  a library containing the class User() - an all-encompassing object with user-related functions
	 * 						that other classes can inherit from. One of the components of the 'controller' portion of the underlying MVC
	 * 						framework.
	 */

	class User extends Pastebin
	{
		private $db_conn;
		protected $userId;
		protected $username;
		protected $token;

		public function __construct(
			$db_conn,
			$userId = 1,
			$username = 'general_user',
			$token = null
		)
		{
			if(
				!$this->setDbConn($db_conn)
				|| !$this->setUserId($userId)
				|| !$this->setUsername($username)
				|| !$this->setToken($token)
				|| !$this->db_conn = Db::connect()
			)
			{
				// invalid; throw exception
				throw new Exception('FATAL ERROR: User() -> could not create new user object!');
			}
		}

		// GETTERS
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
		public function setUserId($userId)
		{
			// user id must be a numeral up to 11 digits long (due to db constraints)
			if(is_numeric($userId) && ($userId > 0 && $userId < 100000000000))
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

			if(Pastebin::isValidString($username))
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

		public function checkTokenInDB($token)
		{
			// try to lookup the token in the db
			// i.e. - check for token validity
			if(Pastebin::isValidString($token))
			{
				$tokenId = 0;

				// get user ID and place into local variable (necessary for binding mysqli params)
				$userId = $this->userId;

				// set sql
				$sql = '
						/* PrivyPaste - Pastebin - User - Token Verification */
						SELECT
							token_id
						FROM
							sessions
						WHERE
							token = ?
						AND
							user_id = ?
						AND
							created >= DATE_SUB(NOW(), INTERVAL 1 DAY)
						LIMIT 1
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
						sessions
						(token, user_id, created)
					VALUES
						(?, ?, NOW())
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
	}
?>